<?php

namespace Sc\Service\Shippingbo\Repository\Prestashop;

use DateTimeImmutable;
use DateTimeZone;
use DbQuery;
use Exception;
use PDOStatement;
use Sc\Service\Shippingbo\Process\ImportData;
use Sc\Service\Shippingbo\Repository\ShippingboRepository;
use Sc\Service\Shippingbo\Shippingbo;

class BatchRepository extends BaseRepository implements RepositoryInterface
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
            $this->getPdo()->prepare('DELETE FROM `'._DB_PREFIX_.$this->getSboAdditionalRefsTable().'` WHERE id_sbo_account = :id_sbo_account')->execute([':id_sbo_account' => $idSboAccount]);
        }
        else
        {
            $this->getPdo()->query('TRUNCATE `'._DB_PREFIX_.$this->getSboAdditionalRefsTable().'`');
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function getLastSyncedDate()
    {
        $query = 'SELECT MAX(updated_at) as last_sync FROM `'._DB_PREFIX_.$this->getSboAdditionalRefsTable().'`';
        $lastSynced = $this->getPdo()->query($query)->fetchColumn();
        if (!$lastSynced)
        {
            return new DateTimeImmutable('now', new DateTimeZone(ShippingboRepository::SERVER_TIMEZONE));
        }

        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $lastSynced, new DateTimeZone(ShippingboRepository::SERVER_TIMEZONE));
    }

    /**
     * @desc : insert or update sbo additional references data in buffer table
     *
     * @return false|PDOStatement
     */
    public function setBufferStatement()
    {
        $queryPrepare = 'INSERT INTO `'._DB_PREFIX_.$this->getSboAdditionalRefsTable().'` (`id`,`id_sbo_account`,`order_item_field`, `product_field`,`order_item_value`,`product_value`,`matched_quantity`,`created_at`,`updated_at`,`synced_at`) VALUES (:id,:id_sbo_account,:order_item_field, :product_field,:order_item_value,:product_value,:matched_quantity,:created_at,:updated_at,:synced_at)
         ON DUPLICATE KEY UPDATE
            `order_item_field` = :order_item_field,
            `product_field` = :product_field,
            `order_item_value` = :order_item_value,
            `product_value` = :product_value,
            `matched_quantity` = :matched_quantity,
            `updated_at` = :updated_at,
            `synced_at` = :synced_at
        ';

        return $this->getPdo()->prepare($queryPrepare);
    }

    public function getSyncedQuery()
    {
        return $this->getSyncedByType(Shippingbo::SBO_PRODUCT_TYPE_BATCH);
    }

    /**
     * @return DbQuery
     */
    public function getAllSboQuery($full = false, $page = false)
    {
        $dbQuery = (new DbQuery())
            ->select("DISTINCT(CONCAT('P#',COALESCE(p.id_product,0),'-A#',COALESCE(pa.id_product_attribute,0),'-SBO#',COALESCE(ps_relation.id_sbo,0))) as rowId")

            ->select('p.id_product')
            ->select('ps_relation.id_storecom_service_shippingbo_shop_relation')
            ->select('COALESCE(pa.id_product_attribute, 0) as id_product_attribute')
            ->select('p.active')
            ->select('COALESCE(pa.reference, p.reference,0) as reference')
            ->select('ps_relation.type_sbo')
            ->select('ps_relation.id_sbo')
            ->select('ps_relation.is_locked')
            ->select('ps_relation.id_sbo IS NOT NULL as is_related')
            ->select('pak.quantity')
            ->select('pak.id_product_pack')
            ->select('pak.id_product_item')
            ->select(' (SELECT CASE
       WHEN
           (SELECT COUNT(*) FROM '._DB_PREFIX_.self::PS_PRODUCT_TABLE_NAME.' p2 WHERE p2.reference = COALESCE(pa.reference, p.reference)) +
           (SELECT COUNT(*) FROM '._DB_PREFIX_.self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME.' pa2 WHERE pa2.reference = COALESCE(pa.reference, p.reference)) > 1
           THEN TRUE
       ELSE FALSE
       END) as duplicate_target_ref')
        ;

        $dbQuery
            ->from(self::PS_PRODUCT_TABLE_NAME, 'p')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'pa', 'pa.id_product = p.id_product')
            ->leftJoin(self::PS_PACK_TABLE_NAME, 'pak', 'pak.id_product_pack = p.id_product')
            ->leftJoin($this->getSboRelationTable(), 'ps_relation', 'ps_relation.id_product = p.id_product')
            ->innerJoin('product_shop', 'ps', 'ps.id_product = p.id_product')
            ->leftJoin($this->getSboAdditionalRefsTable(), 'sbo_addrefs', 'sbo_addrefs.order_item_value = ps_relation.id_sbo')
            ->where('ps_relation.type_sbo = "'.Shippingbo::SBO_PRODUCT_TYPE_BATCH.'"')
            ->where('ps_relation.id_shop = :id_shop')
            ->where('ps_relation.is_locked = :is_locked')
        ;

        if ($full)
        {
            $dbQuery = $this->addPsLangImageParts($dbQuery);
        }

        return $dbQuery;
    }

    /**
     * @param bool $full
     * @param int|false $page
     * @return DbQuery
     */
    public function getMissingSboQuery($full = false, $page = false){
        $dbQuery = $this->getAllSboQuery($full, $page);
        $dbQuery
            ->having('is_related = false OR (is_related = true AND is_locked=true)')
        ;

        return $dbQuery;
    }

    public function addPsLockedComponentPropertyToQuery(DbQuery $dbQuery, &$errors)
    {
        $dbQuery->select('('.$this->psLockedComponentProperty().') as locked_component');
        $errors[] = 'locked_component';
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
            ->where('pak.id_product_item = pack_component.id_product_item')
            ->where('ps_relation_source.id_shop=:id_shop')
            ->where('ps_relation_source.is_locked=true')
        ;

        return 'EXISTS('.$dbQuery.')';
    }

    public function addSboLockedComponentPropertyToQuery(DbQuery $dbQuery, &$errors, $isSubRequest = false)
    {
        $relationComponentTableAlias = 'ps_relation_component'.($isSubRequest ? '_sub' : '');
        $dbQuery->leftJoin($this->getSboRelationTable(), $relationComponentTableAlias, $relationComponentTableAlias.'.id_product = pak.id_product_item AND '.$relationComponentTableAlias.'.id_shop=:id_shop')
            ->select($relationComponentTableAlias.'.id_sbo as id_component_sbo')
        ;
        $dbQuery->select('('.$this->sboLockedComponentProperty().') as locked_component');
        $errors[] = 'locked_component';
    }

    public function addSboDuplicateTargetRefPropertyToQuery(DbQuery $dbQuery, &$errors)
    {
        $errors[] = 'duplicate_target_ref';
    }

    /**
     * récupération des lots présents dans Shiipingbo, non présents dans PS.
     *
     * @return DbQuery
     */
    public function getAllPsQuery($page = false)
    {
        $dbQuery = (new DbQuery())
            ->select("DISTINCT(CONCAT('P#',COALESCE(ps_relation.id_product,0),'-A#',COALESCE(ps_relation.id_product_attribute,0),'-SBO#',COALESCE(sbo_addrefs.id,0))) as rowId")
            ->select('ps_relation.*')
            ->select('COALESCE(ps_relation.is_locked ,false) as is_locked')
            ->select('ps_relation.id_storecom_service_shippingbo_shop_relation')
            ->select('sbo_source.id as id_source_sbo')
            ->select('sbo_source.user_ref as source_ref')
            ->select('sbo_addrefs.order_item_value as user_ref')
            ->select('p_source.id_product as id_product_item')
            ->select('COALESCE(pa_source.id_product_attribute,0) as id_product_attribute_item')
            ->select('sbo_addrefs.product_value')
            ->select('sbo_addrefs.matched_quantity as matched_quantity')
            ->select('ps_relation.id_product IS NOT NULL as is_related')
            ->select(' (SELECT CASE
       WHEN
           (SELECT COUNT(*) FROM '._DB_PREFIX_.self::PS_PRODUCT_TABLE_NAME.' p2 WHERE p2.reference = sbo_addrefs.order_item_value) +
           (SELECT COUNT(*) FROM '._DB_PREFIX_.self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME.' pa2 WHERE pa2.reference = sbo_addrefs.order_item_value) > 1
           THEN TRUE
       ELSE FALSE
       END) as duplicate_target_ref')
        ;

        $dbQuery
            ->from($this->getSboAdditionalRefsTable(), 'sbo_addrefs')
            ->leftJoin($this->getSboRelationTable(), 'ps_relation', 'sbo_addrefs.id = ps_relation.id_sbo AND ps_relation.id_shop=:id_shop')
            ->leftJoin($this->getSboProductsTable(), 'sbo', 'sbo.id = sbo_addrefs.id')
            ->leftJoin($this->getSboProductsTable(), 'sbo_source', 'sbo_addrefs.product_value = sbo_source.id')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME, 'p_source', 'sbo_source.user_ref = p_source.reference')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'pa_source', 'sbo_source.user_ref = pa_source.reference')
            ->leftJoin(self::PS_PACK_TABLE_NAME, 'pak', 'ps_relation.id_product =  pak.id_product_pack')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME, 'p_exists', 'ps_relation.id_product =  p_exists.id_product')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'pa_exists', 'ps_relation.id_product_attribute = pa_exists.id_product_attribute')
            ->where('COALESCE(ps_relation.is_locked ,false) = :is_locked')
            ->where('sbo_source.id_sbo_account = :id_sbo_account')
            ->having('sbo_addrefs.matched_quantity > 1')
        ;

        return $dbQuery;
    }

    public function getMissingPsQuery($page = false)
    {
        $query = $this->getAllPsQuery($page)
            ->having('is_related = false OR (is_related = true AND is_locked=true)')
        ;

        return $query;
    }


    /**
     * @return string[]
     */
    public function getExportColumns()
    {
        return [
            'product_id',
            'matched_quantity',
            'order_item_value',
        ];
    }

    protected function skuTooLongProperty($isSubRequest = false)
    {
        $aliasSuffix = $isSubRequest ? '_sub' : '';
        $sboAddrefsTableAlias = 'sbo_addrefs'.$aliasSuffix;

        return 'LENGTH('.$sboAddrefsTableAlias.'.order_item_value) > :sku_max_length';
    }

    protected function missingRefProperty()
    {
        return 'COALESCE(pa.reference, p.reference,"") = ""';
    }

    protected function sboLockedComponentProperty()
    {
        return 'EXISTS(SELECT locked_component.id_sbo FROM `'._DB_PREFIX_.$this->getSboRelationTable().'` `locked_component` WHERE locked_component.is_locked = true AND locked_component.id_sbo = ps_relation.id_sbo_source AND  locked_component.id_shop = :id_shop)';
    }

    protected function sboComponentWithError($isSubRequest = false)
    {
        $aliasSuffix = $isSubRequest ? '_sub' : '';
        $relationTableAlias = 'ps_relation'.$aliasSuffix;
        $productRepository = new ProductRepository($this->getService());
        $dbQuery = $productRepository->getAllPsQuery(false, $isSubRequest);
        $productRepository->addPsErrorParts($dbQuery, $isSubRequest);
        $dbQuery->where($relationTableAlias.'.id_sbo = sbo_source.id');
        $dbQuery = str_replace(':has_error', 'true', $dbQuery);

        return 'EXISTS('.$dbQuery.')';
    }

    public function getCollected()
    {
        $dbQuery = (new DbQuery())
            ->select('*')
            ->from($this->getSboAdditionalRefsTable())
            ->where('id_sbo_account = :id_sbo_account')
            ->where('matched_quantity > 1')
        ;
        $stmt = $this->getPdo()->prepare($dbQuery);
        $stmt->execute(['id_sbo_account' => $this->getService()->getSboAccount()->getId()]);

        return $stmt;
    }
}
