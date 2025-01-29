<?php

namespace Sc\Service\Shippingbo\Repository\Prestashop;

use DateTimeImmutable;
use DateTimeZone;
use DbQuery;
use PDOStatement;
use Sc\Service\Shippingbo\Repository\ShippingboRepository;
use Sc\Service\Shippingbo\Shippingbo;

class PackRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * @desc : truncate sbo product buffer table
     *
     * @return $this
     */
    public function clear($idSboAccount = null)
    {
        if ($idSboAccount)
        {
            $this->getPdo()->prepare('DELETE FROM `'._DB_PREFIX_.$this->getSboPackComponentTable().'` WHERE id_sbo_account = :id_sbo_account')->execute([':id_sbo_account' => $idSboAccount]);
        }
        else
        {
            $this->getPdo()->query('TRUNCATE `'._DB_PREFIX_.$this->getSboPackComponentTable().'`');
        }

        return $this;
    }

    /**
     * @desc : insert or update sbo pack component data in buffer table
     *  No endpoint for this on shippingbo api, the linked product will be triggered as updated,
     *  so other entries on this table can be updated even if not modified in shippingbo interface
     *
     * @return false|\mysqli_stmt|PDOStatement
     */
    public function setBufferStatement()
    {
        $packQueryPrepare = 'INSERT INTO `'._DB_PREFIX_.$this->getSboPackComponentTable().'` (`id`,`id_sbo_account`,`quantity`, `pack_product_id`,`component_product_id`,`created_at`,`updated_at`, `synced_at`) VALUES (:id,:id_sbo_account,:quantity,:pack_product_id,:component_product_id,:created_at,:updated_at, :synced_at)
         ON DUPLICATE KEY UPDATE
            `quantity` = :quantity,
            `pack_product_id` = :pack_product_id,
            `component_product_id` = :component_product_id,
            `updated_at` = :updated_at,
            `synced_at` = :synced_at
        ';

        return $this->getPdo()->prepare($packQueryPrepare);
    }

    /**
     * @return DateTimeImmutable|false
     *
     * @throws \Exception
     */
    public function getLastSyncedDate()
    {
        $lastSyncedStmt = $this->getPdo()->query('SELECT MAX(updated_at) as last_sync FROM `'._DB_PREFIX_.$this->getSboPackComponentTable().'`');
        if (!$lastSyncedStmt)
        {
            return new DateTimeImmutable('now', new DateTimeZone(ShippingboRepository::SERVER_TIMEZONE));
        }
        $lastSyncedStmt->execute();
        $lastSynced = $lastSyncedStmt->fetchColumn();

        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $lastSynced, new DateTimeZone(ShippingboRepository::SERVER_TIMEZONE));
    }

    /**
     * @return false|\mysqli_stmt|PDOStatement
     */
    public function setPsStatement()
    {
        $packQueryPrepare = 'INSERT INTO `'._DB_PREFIX_.self::PS_PACK_TABLE_NAME.'` (`id_product_pack`,`id_product_item`,`id_product_attribute_item`,`quantity`) VALUES (:id_product_pack,:id_product_item,:id_product_attribute_item,:quantity)
         ON DUPLICATE KEY UPDATE
            `quantity` = :quantity
        ';

        return $this->getPdo()->prepare($packQueryPrepare);
    }

    /**
     * @return DbQuery
     */
    public function getAllPsQuery($page = false)
    {
        $dbQuery = (new DbQuery())
            ->select("DISTINCT(CONCAT('P#',COALESCE(ps_relation.id_product,0),'-A#',COALESCE(ps_relation.id_product_attribute,0),'-SBO#',COALESCE(ps_relation.id_sbo,0))) as rowId")
            ->select('pak.id_product_item')
            ->select('ps_relation.*')
            ->select('IF(ps_relation.is_locked IS NOT NULL,ps_relation.is_locked,false) as is_locked')
            ->select('ps_relation.id_storecom_service_shippingbo_shop_relation')
            ->select('sbo.id as id_sbo')
            ->select('sbo.user_ref')
            ->select('sbo.title')
            ->select('ps_relation.id_product IS NOT NULL as is_related')
            ->select('(SELECT CASE
       WHEN
           (SELECT COUNT(*) FROM '._DB_PREFIX_.self::PS_PRODUCT_TABLE_NAME.' p2 WHERE p2.reference = sbo.user_ref) +
           (SELECT COUNT(*) FROM '._DB_PREFIX_.self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME.' pa2 WHERE pa2.reference = sbo.user_ref) > 1
           THEN TRUE
       ELSE FALSE
       END) as duplicate_target_ref')
        ;

        $dbQuery->from($this->getSboProductsTable(), 'sbo')
            ->leftJoin($this->getSboRelationTable(), 'ps_relation', 'sbo.id = ps_relation.id_sbo AND ps_relation.id_shop=:id_shop')
            ->leftJoin(self::PS_PACK_TABLE_NAME, 'pak', 'ps_relation.id_product =  pak.id_product_pack')
            ->leftJoin($this->getSboPackComponentTable(), 'sbo_pack_component', 'ps_relation.id_sbo = sbo_pack_component.pack_product_id AND sbo_pack_component.id_sbo_account=:id_sbo_account')
            ->where('sbo.is_pack = true')
            ->where('COALESCE(ps_relation.is_locked ,false) = :is_locked')
            ->where('sbo.id_sbo_account = :id_sbo_account')
            ->groupBy('sbo.id')
        ;

        return $dbQuery;
    }

    public function getMissingPsQuery($page = false)
    {
        $dbQuery = $this->getAllPsQuery($page);
        $dbQuery
            ->having('is_related = false OR (is_related = true AND is_locked=true)')
        ;

        return $dbQuery;
    }

    /**
     * @return DbQuery
     */
    public function getAllSboQuery($full = false, $page = false)
    {
        $nbComponentsSubQuery = (new DbQuery())
            ->select('id_product_item,quantity')
            ->from(self::PS_PACK_TABLE_NAME)
            ->where('id_product_pack = ps_relation.id_product')
            ->having('COUNT(id_product_item) > 1 OR quantity = 1')
        ;

        $dbQuery = (new DbQuery())
            ->select("DISTINCT(CONCAT('P#',COALESCE(ps_relation.id_product,0),'-A#',COALESCE(ps_relation.id_product_attribute,0),'-SBO#',COALESCE(ps_relation.id_sbo,0))) as rowId")
            ->select('ps_relation.id_product')
            ->select('COALESCE(pa.id_product_attribute, 0) as id_product_attribute')
            ->select('p.ean13')
            ->select('p.active')
            ->select('COALESCE(pa.reference, p.reference, 0) as reference')
            ->select('ps_relation.type_sbo')
            ->select('ps_relation.id_sbo')
            ->select('ps_relation.is_locked')
            ->select('ps_relation.id_sbo IS NOT NULL as is_related')
            ->select('ps_relation.id_storecom_service_shippingbo_shop_relation')
            ->select('EXISTS('.$nbComponentsSubQuery.') as is_pack')
            ->select('(SELECT CASE
       WHEN
           (SELECT COUNT(*) FROM '._DB_PREFIX_.self::PS_PRODUCT_TABLE_NAME.' p2 WHERE p2.reference = COALESCE(pa.reference, p.reference)) +
           (SELECT COUNT(*) FROM '._DB_PREFIX_.self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME.' pa2 WHERE pa2.reference = COALESCE(pa.reference, p.reference)) > 1
           THEN TRUE
       ELSE FALSE
       END) as duplicate_target_ref')
        ;

        $dbQuery
            ->from($this->getSboRelationTable(), 'ps_relation')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME, 'p', 'p.id_product = ps_relation.id_product')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME.'_shop', 'ps', 'p.id_product = ps.id_product AND ps.id_shop = :id_shop')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'pa', 'pa.id_product = p.id_product')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME.'_shop', 'pas', 'pa.id_product_attribute = pas.id_product_attribute AND pas.id_shop = :id_shop')
            ->where('ps_relation.type_sbo = "'.Shippingbo::SBO_PRODUCT_TYPE_PACK.'"')
            ->where('ps_relation.is_locked = :is_locked')
            ->where('ps_relation.id_shop = :id_shop')
            ->having('is_pack = true ')
            ->groupBy('reference')
        ;
        $dbQuery = $this->addLocationParts($dbQuery);
        if ($full)
        {
            $dbQuery = $this->addPsLangImageParts($dbQuery);
        }

        return $dbQuery;
    }

    public function getSyncedQuery()
    {
        return $this->getSyncedByType(Shippingbo::SBO_PRODUCT_TYPE_PACK);
    }

    /**
     * @param bool      $full
     * @param int|false $page
     *
     * @return DbQuery
     */
    public function getMissingSboQuery($full = false, $page = false)
    {
        $dbQuery = $this->getAllSboQuery($full, $page);

        $dbQuery
            ->having('is_related = false OR (is_related = true AND is_locked=true)')
        ;

        return $dbQuery;
    }

    /**
     * @desc : get product components information
     *
     * @return DbQuery
     */
    public function getComponentsQuery()
    {
        $dbQuery = (new DbQuery())
            ->select('p_linked.id_product')
            ->select('COALESCE(pa_linked.id_product_attribute,0) as id_product_attribute')
            ->select('sbo_pak.quantity')
            ->from($this->getSboPackComponentTable(), 'sbo_pak')
            ->leftJoin($this->getSboProductsTable(), 'sbo', 'sbo_pak.pack_product_id = sbo.id')
            ->leftJoin($this->getSboProductsTable(), 'sbo_linked', 'sbo_pak.component_product_id = sbo_linked.id')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME, 'p', 'sbo.user_ref = p.reference')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME, 'p_linked', 'sbo_linked.user_ref = p_linked.reference')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'pa_linked', 'sbo_linked.user_ref = pa_linked.reference')
            ->where('sbo.is_pack = 1')
            ->where('p.id_product = :id_product')
            ->where('sbo_pak.id_sbo_account = :id_sbo_account');

        return $dbQuery;
    }

    /**
     * @desc get all sbo products from buffer table and check if product is linked or not in PS
     * depending on sbo product user_ref or sbo additional ref order_item_value
     *
     * @param false|DateTimeImmutable $lastCollect
     *
     * @return DbQuery
     */
    public function getSboComponentsQuery($lastCollect = false)
    {
        $dbQuery = (new DbQuery())
            ->select('sbo.*')
            ->select('IF(p.id_product IS NOT NULL OR pa.id_product_attribute IS NOT NULL, true, false) as exists_in_ps')
            ->from($this->getSboProductsTable(), 'sbo')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME, 'p', 'sbo.user_ref = p.reference')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'pa', 'sbo.user_ref = pa.reference')
            ->groupBy('sbo.id')
            ->where('sbo.is_pack = 1')
            ->where('sbo.id_sbo_account=:id_sbo_account')
        ;
        if ($lastCollect)
        {
            $dbQuery->where('sbo.updated_at > "'.$lastCollect->format('Y-m-d H:i:s').'"');
        }

        return $dbQuery;
    }

    public function addPsMissingComponentPropertyToQuery(DbQuery $dbQuery, &$errors)
    {
        $dbQuery->select('('.$this->psMissingComponentProperty().') as missing_component');
        $errors[] = 'missing_component';
    }

    public function addSboMissingComponentPropertyToQuery(DbQuery $dbQuery, &$errors)
    {
        $dbQuery->select('('.$this->sboMissingComponentProperty().') as missing_component');
        $errors[] = 'missing_component';
    }

    public function addSboLockedComponentPropertyToQuery(DbQuery $dbQuery, &$errors, $isSubRequest = false)
    {
        $dbQuery->select('('.$this->sboLockedComponentProperty().') as locked_component');
        $errors[] = 'locked_component';
    }

    public function addPsLockedComponentPropertyToQuery(DbQuery $dbQuery, &$errors)
    {
        $dbQuery->select('('.$this->psLockedComponentProperty().') as locked_component');
        $errors[] = 'locked_component';
    }

    public function addSboDuplicateTargetRefPropertyToQuery(DbQuery $dbQuery, &$errors)
    {
        $errors[] = 'duplicate_target_ref';
    }

    /**
     * @return string
     */
    public function getMissingComponentsSboQuery($full = false)
    {
        $dbQuery = (new DbQuery())
            ->select('COALESCE(pa.reference,p.reference,0) as pack_product_ref')
            ->select('COALESCE(pa_comp.reference,p_comp.reference,0) as component_product_ref')
            ->select('pak.quantity')
            ->select('sbo.id')
            ->from(self::PS_PACK_TABLE_NAME, 'pak')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME, 'p', 'pak.id_product_pack = p.id_product')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'pa', 'p.id_product = pa.id_product')
            ->leftJoin($this->getSboRelationTable(), 'ps_relation', 'ps_relation.id_product = p.id_product')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME, 'p_comp', 'pak.id_product_item = p_comp.id_product')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'pa_comp', 'pak.id_product_attribute_item = pa_comp.id_product_attribute')
            ->leftJoin($this->getSboProductsTable(), 'sbo', 'p.reference = sbo.user_ref OR pa.reference = sbo.user_ref')
            ->where('ps_relation.type_sbo = "'.Shippingbo::SBO_PRODUCT_TYPE_PACK.'"')
            ->where('ps_relation.is_locked != 1')
            ->where('ps_relation.id_shop = :id_shop')
            ->having('sbo.id IS NULL');
        if ($full)
        {
            $dbQuery = $this->addPsLangImageParts($dbQuery);
        }

        return $dbQuery->__toString();
    }

    /**
     * @return string[]
     */
    public function getExportColumns()
    {
        return [
            'userRef',
            'ean13',
            'title',
            'location',
            'pictureUrl',
        ];
    }

    /**
     * @return string[]
     */
    public function getExportComponentsColumns()
    {
        return [
            'pack_product_ref',
            'component_product_ref',
            'quantity',
        ];
    }

    protected function skuTooLongProperty($isSubRequest = false)
    {
        $aliasSuffix = $isSubRequest ? '_sub' : '';
        $sboProductTableAlias = 'sbo'.$aliasSuffix;

        return 'IF(LENGTH('.$sboProductTableAlias.'.user_ref) > :sku_max_length, true, false)';
    }

    protected function missingRefProperty()
    {
        return 'IF(COALESCE(pa.reference, p.reference,"") = "" , true,false)';
    }

    protected function psMissingComponentProperty()
    {
        $dbQuery = (new DbQuery())
            ->select('sbo_pak.component_product_id')
            ->from($this->getSboPackComponentTable(), 'sbo_pak')
            ->where('sbo_pak.pack_product_id = ps_relation.id_sbo')
        ;

        return 'EXISTS('.$dbQuery.')';
    }

    public function addSboComponentWithErrorPropertyToQuery(DbQuery $dbQuery, &$errors, $isSubRequest = false)
    {
        $dbQuery->select('('.$this->sboComponentWithError($isSubRequest).') as components_with_error');
        $errors[] = 'components_with_error';
    }

    protected function psLockedComponentProperty()
    {
        $dbQuery = (new DbQuery())
            ->select('ps_relation_source.id_product')
            ->from(self::PS_PACK_TABLE_NAME, 'pack_component')
            ->innerJoin($this->getSboRelationTable(), 'ps_relation_source', 'pack_component.id_product_item = ps_relation_source.id_product')
            ->where('ps_relation_source.id_shop=:id_shop')
            ->where('ps_relation_source.is_locked=true')
        ;

        return 'EXISTS('.$dbQuery.')';
    }

    protected function SboMissingComponentProperty()
    {
        $dbQuery = (new DbQuery())
            ->select('sbo_pak.component_product_id')
            ->from($this->getSboPackComponentTable(), 'sbo_pak')
            ->where('sbo_pak.pack_product_id = ps_relation.id_sbo')
        ;

        return '!EXISTS('.$dbQuery.')';
    }

    protected function sboComponentWithError($isSubRequest = false)
    {
        $query = (new DbQuery())
            ->select('sbo_sub.id')
            ->select('('.$this->skuTooLongProperty($isSubRequest).') as sku_too_long')
            ->from($this->getSboPackComponentTable(), 'sbo_pack_component_sub')
            ->leftJoin($this->getSboProductsTable(), 'sbo_sub', 'sbo_sub.id = sbo_pack_component_sub.component_product_id')
            ->where('sbo_sub.id_sbo_account = :id_sbo_account')
            ->where('sbo_pack_component_sub.pack_product_id = sbo_pack_component.pack_product_id')
            ->having('sku_too_long = true')
        ;

        return 'EXISTS('.$query->__toString().')';
    }

    public function getCollected()
    {
        $dbQuery = (new DbQuery())->select('*')
            ->from($this->getSboProductsTable())
            ->where('id_sbo_account = :id_sbo_account')
            ->where('is_pack = true')
        ;
        $stmt = $this->getPdo()->prepare($dbQuery);
        $stmt->execute([':id_sbo_account' => $this->getService()->getSboAccount()->getId()]);

        return $stmt;
    }

    public function getCollectedComponents()
    {
        $dbQuery = (new DbQuery())->select('*')
            ->from($this->getSboPackComponentTable())
            ->where('id_sbo_account = :id_sbo_account')
        ;
        $stmt = $this->getPdo()->prepare($dbQuery);
        $stmt->execute([':id_sbo_account' => $this->getService()->getSboAccount()->getId()]);

        return $stmt;
    }

    private function sboLockedComponentProperty($isSubRequest = false)
    {
        $query = (new DbQuery())
            ->select('relation_sub.is_locked')
            ->from($this->getSboRelationTable(), 'relation_sub')
            ->where('relation_sub.id_shop = :id_shop')
            ->where('relation_sub.id_sbo = sbo_pack_component.component_product_id')
            ->having('relation_sub.is_locked = true')
        ;

        return 'EXISTS('.$query->__toString().')';
    }

    /**
     * @return DbQuery
     */
    protected function addLocationParts(DbQuery $dbQuery)
    {
        if (version_compare(_PS_VERSION_, '1.7.5.0', '<'))
        {
            return $dbQuery->select('COALESCE(pa.location,p.location) as location');
        }

        return $dbQuery->select('sa.location')
            ->leftJoin('stock_available', 'sa', 'ps_relation.id_product = sa.id_product AND COALESCE(ps_relation.id_product_attribute,0)=sa.id_product_attribute')
            ;
    }
}
