<?php

namespace Sc\Service\Shippingbo\Repository\Prestashop;

use DateTimeImmutable;
use DateTimeZone;
use DbQuery;
use Exception;
use PDOStatement;
use PrestaShopException;
use Product;
use Sc\Service\Shippingbo\Repository\ShippingboRepository;
use Sc\Service\Shippingbo\Shippingbo;
use StockAvailable;

class ProductRepository extends BaseRepository implements RepositoryInterface
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
            $this->getPdo()->prepare('DELETE FROM `'._DB_PREFIX_.$this->getSboProductsTable().'` WHERE id_sbo_account = :id_sbo_account')->execute([':id_sbo_account' => $idSboAccount]);
        }
        else
        {
            $this->getPdo()->prepare('TRUNCATE `'._DB_PREFIX_.$this->getSboProductsTable().'`')->execute();
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function getLastSyncedDate()
    {
        $lastSynced = $this->getPdo()->query('SELECT MAX(updated_at) as last_sync FROM `'._DB_PREFIX_.$this->getSboRelationTable().'`')->fetchColumn();
        if (!$lastSynced)
        {
            return new DateTimeImmutable('now', new DateTimeZone(ShippingboRepository::SERVER_TIMEZONE));
        }

        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $lastSynced, new DateTimeZone(ShippingboRepository::SERVER_TIMEZONE));
    }

    // INSERT/UPDATE

    /**
     * @desc :  insert or update sbo product data in buffer table
     *
     * @return PDOStatement
     */
    public function setBufferStatement()
    {
        $queryPrepare = 'INSERT INTO `'._DB_PREFIX_.$this->getSboProductsTable().'` (`id`,`id_sbo_account`,`user_ref`,`is_pack`,`title`,`location`,`weight`,`height`, `length`,`width`,`updated_at`,`synced_at`) VALUES (:id,:id_sbo_account,:user_ref,:is_pack,:title,:location,:weight,:height,:length,:width,:updated_at,:synced_at)
        ON DUPLICATE KEY UPDATE
            `user_ref` = :user_ref,
            `location` =  :location,
            `weight` =  CASE WHEN `weight`="" or `weight` IS NULL THEN :weight END,
            `height` =  CASE WHEN `height`="" or `height` IS NULL THEN :height END,
            `length` =  CASE WHEN `length`="" or `length` IS NULL THEN :length END,
            `width` =  CASE WHEN `width`="" or `width` IS NULL THEN :width END,
            `title` =  :title,
            `updated_at` = :updated_at,
            `synced_at` = :synced_at
        ';

        return $this->getPdo()->prepare($queryPrepare);
    }

    /**
     * @param bool $full
     * @param int|false $page
     * @param bool $isSubRequest
     *
     * @return DbQuery
     */
    public function getAllSboQuery($full = false, $page = false, $isSubRequest = false)
    {
        $aliasSuffix = $isSubRequest ? '_sub' : '';
        $relationTableAlias = 'ps_relation'.$aliasSuffix;

        $dbQuery = (new DbQuery())
            ->select("DISTINCT(CONCAT('P#',COALESCE(p.id_product,0),'-A#',COALESCE(pa.id_product_attribute,0),'-SBO#',COALESCE(".$relationTableAlias.'.id_sbo,0))) as rowId')
            ->select('p.id_product')
            ->select($relationTableAlias.'.id_storecom_service_shippingbo_shop_relation')
            ->select('('.$relationTableAlias.'.id_sbo IS NOT NULL) as is_related')
            ->select('COALESCE(pas.id_shop, ps.id_shop,0) as id_shop')
            ->select('p.width')
            ->select('p.height')
            ->select('p.weight+COALESCE(pa.weight,0) as weight')
            ->select('p.depth as length')
            ->select('p.active')
            ->select('p.reference')
            ->select('pa.reference')
            ->select('COALESCE(pa.id_product_attribute, 0) as id_product_attribute')
            ->select('COALESCE(pa.reference, p.reference,0) as reference')
            ->select('IF(pa.id_product_attribute != 0,pa.ean13,p.ean13) as ean13')
            ->select('IF('.$relationTableAlias.'.is_locked IS NOT NULL,'.$relationTableAlias.'.is_locked,true) as is_locked')
            ->select($relationTableAlias.'.type_sbo')
            ->select($relationTableAlias.'.id_sbo')
            ->select(' (SELECT CASE
       WHEN
           (SELECT COUNT(*) FROM '._DB_PREFIX_.self::PS_PRODUCT_TABLE_NAME.' p2 WHERE p2.reference = COALESCE(pa.reference,p.reference)) +
           (SELECT COUNT(*) FROM '._DB_PREFIX_.self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME.' pa2 WHERE pa2.reference = COALESCE(pa.reference,p.reference)) > 1
           THEN TRUE
       ELSE FALSE
       END) as duplicate_target_ref')
            ->groupBy('rowId')
        ;

        $dbQuery
            ->from(self::PS_PRODUCT_TABLE_NAME, 'p')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'pa', 'pa.id_product = p.id_product')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME.'_shop', 'ps', 'ps.id_product = p.id_product AND ps.id_shop = :id_shop')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME.'_shop', 'pas', 'pas.id_product_attribute = pa.id_product_attribute AND pas.id_shop = :id_shop')
            ->leftJoin($this->getSboRelationTable(), $relationTableAlias, $relationTableAlias.'.id_product = p.id_product AND '.$relationTableAlias.'.id_product_attribute = COALESCE(pa.id_product_attribute,0)')
            ->where($relationTableAlias.'.is_locked = :is_locked')
            ->where($relationTableAlias.'.id_shop = :id_shop')
        ;

        $dbQuery = $this->addLocationParts($dbQuery);
        $dbQuery = $this->addProductTypeParts($dbQuery);

        if ($full)
        {
            $dbQuery = $this->addPsLangImageParts($dbQuery);
        }

        return $dbQuery;
    }

    /**
     * @param bool      $full
     * @param int|false $page
     * @param bool      $isSubRequest
     *
     * @return DbQuery
     */
    public function getMissingSboQuery($full = false, $page = false, $isSubRequest = false)
    {
        $dbQuery = $this->getAllSboQuery($full, $page, $isSubRequest);

        $dbQuery
            ->having('is_related = false OR (is_related = true AND is_locked=true)')
        ;

        return $dbQuery;
    }

    /**
     * @param bool $isSubRequest add a suffix to avoid same alias on
     *
     * @return DbQuery
     */
    public function getAllPsQuery($page = false, $isSubRequest = false)
    {
        $aliasSuffix = $isSubRequest ? '_sub' : '';
        $relationTableAlias = 'ps_relation'.$aliasSuffix;
        $sboProductTableAlias = 'sbo'.$aliasSuffix;
        $sboAddRefTableAlias = 'sbo_addrefs'.$aliasSuffix;
        $sboAddRefSourceTableAlias = 'sbo_addref_source'.$aliasSuffix;

        $dbQuery = (new DbQuery())
            ->select("DISTINCT(CONCAT('P#',COALESCE(p.id_product,0),'-A#',COALESCE(pa.id_product_attribute,0),'-SBO#',COALESCE(".$sboProductTableAlias.'.id,0))) as rowId')
            ->select('COALESCE(pas.id_shop, ps.id_shop,0) as id_shop')
            ->select('COALESCE('.$sboAddRefTableAlias.'.order_item_value, '.$sboProductTableAlias.'.user_ref) as user_ref')
            ->select('COALESCE('.$sboAddRefSourceTableAlias.'.title,'.$sboProductTableAlias.'.title) as title')
            ->select('p.active')
            ->select('p.id_product')
            ->select('pa.id_product_attribute')
            ->select($sboProductTableAlias.'.location')
            ->select($sboProductTableAlias.'.width')
            ->select($sboProductTableAlias.'.height')
            ->select($sboProductTableAlias.'.length')
            ->select($sboProductTableAlias.'.weight')
            ->select($relationTableAlias.'.type_sbo')
            ->select('COALESCE('.$relationTableAlias.'.is_locked ,false) as is_locked')
            ->select($sboProductTableAlias.'.id as id_sbo')
            ->select($relationTableAlias.'.id_storecom_service_shippingbo_shop_relation')
            ->select('(ps_relation.id_product IS NOT NULL) as is_related')
            ->select(' (SELECT CASE
       WHEN
           (SELECT COUNT(*) FROM '._DB_PREFIX_.self::PS_PRODUCT_TABLE_NAME.' p2 WHERE p2.reference = COALESCE('.$sboAddRefTableAlias.'.order_item_value, '.$sboProductTableAlias.'.user_ref)) +
           (SELECT COUNT(*) FROM '._DB_PREFIX_.self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME.' pa2 WHERE pa2.reference = COALESCE('.$sboAddRefTableAlias.'.order_item_value, '.$sboProductTableAlias.'.user_ref)) > 1
           THEN TRUE
       ELSE FALSE
       END) as duplicate_target_ref')
        ;

        $dbQuery
            ->from($this->getSboProductsTable(), $sboProductTableAlias)
            ->leftJoin($this->getSboRelationTable(), $relationTableAlias, $sboProductTableAlias.'.id = '.$relationTableAlias.'.id_sbo AND  '.$relationTableAlias.'.id_shop=:id_shop')
            ->leftJoin($this->getSboAdditionalRefsTable(), $sboAddRefTableAlias, $sboAddRefTableAlias.'.id = '.$sboProductTableAlias.'.id AND '.$sboAddRefTableAlias.'.id_sbo_account=:id_sbo_account')
            ->leftJoin($this->getSboProductsTable(), $sboAddRefSourceTableAlias, $sboAddRefTableAlias.'.product_value = '.$sboAddRefSourceTableAlias.'.id AND '.$sboAddRefSourceTableAlias.'.id_sbo_account=:id_sbo_account')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME, 'p', 'COALESCE('.$sboAddRefTableAlias.'.order_item_value, '.$sboProductTableAlias.'.user_ref)=p.reference')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME.'_shop', 'ps', 'ps.id_product = '.$relationTableAlias.'.id_product AND ps.id_shop = :id_shop')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'pa', 'pa.id_product = p.id_product')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME.'_shop', 'pas', 'pas.id_product_attribute = pa.id_product_attribute')
            ->where('COALESCE('.$relationTableAlias.'.is_locked ,false) = :is_locked')
            ->where($sboProductTableAlias.'.is_pack = false')
            ->where($sboProductTableAlias.'.id_sbo_account=:id_sbo_account')
            ->groupBy('rowId')

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
     * @return string[]
     */
    public function getExportColumns($filtered = false)
    {
        $columnsSettingsExport = (array) json_decode($this->getService()->getConfigValue('defaultDataExport'));

        return array_keys(array_filter($columnsSettingsExport));
    }

    /**
     * @throws PrestaShopException
     */
    public function saveProduct($productInfos, $productName, $ref, $is_pack, $id_lang)
    {
        $product = new Product($productInfos['id_product'], false, $id_lang);
        $shopsUnitConversion = json_decode($this->getService()->getConfigValue('unitConversion'), true);
        if (!isset($productInfos['id_product']))
        {
            $product->name[$id_lang] = $productName;
            $product->link_rewrite[$id_lang] = link_rewrite($productName);
            $product->active = false;
        }
        if (!$product->price)
        {
            $product->price = 0;
        }
        $product->reference = $productInfos[$ref];
        if ($this->getService()->getImportProcess()->getFieldToImport('location') && ($productInfos['location'] && trim($productInfos['location'], ' ') != ''))
        {
            $product->location = $productInfos['location'];
        }

        $product->width = ($this->getService()->getImportProcess()->getFieldToImport('width') && isset($productInfos['width'])) ? $productInfos['width'] / $shopsUnitConversion['dimension'] : null;
        $product->height = ($this->getService()->getImportProcess()->getFieldToImport('height') && isset($productInfos['height'])) ? $productInfos['height'] / $shopsUnitConversion['dimension'] : null;
        $product->depth = ($this->getService()->getImportProcess()->getFieldToImport('length') && isset($productInfos['length'])) ? $productInfos['length'] / $shopsUnitConversion['dimension'] : null;
        $product->weight = ($this->getService()->getImportProcess()->getFieldToImport('weight') && isset($productInfos['weight'])) ? $productInfos['weight'] / $shopsUnitConversion['weight'] : null;
        $product->cache_is_pack = $is_pack;
        if (!in_array($this->getService()->getIdShop(), $product->id_shop_list))
        {
            $product->id_shop_list[] = $this->getService()->getIdShop();
        }
        if (version_compare(_PS_VERSION_, '1.7.8.0', '>='))
        {
            $product->product_type = $is_pack ? 'pack' : 'standard';
        }
        $product->save();

        if (version_compare(_PS_VERSION_, '1.7.5.0', '>=') && $this->getService()->getImportProcess()->getFieldToImport('location'))
        {
            StockAvailable::setLocation((int) $product->id, pSQL($productInfos['location']), $this->getService()->getIdShop());
        }

        return $product;
    }

    public function getSyncedQuery()
    {
        return $this->getSyncedByType([Shippingbo::SBO_PRODUCT_TYPE_PRODUCT, Shippingbo::SBO_PRODUCT_TYPE_ADDREF]);
    }

    protected function skuTooLongProperty($isSubRequest = false)
    {
        $aliasSuffix = $isSubRequest ? '_sub' : '';
        $sboProductTableAlias = 'sbo'.$aliasSuffix;
        $sboAddrefsTableAlias = 'sbo_addrefs'.$aliasSuffix;

        return 'IF(LENGTH(COALESCE('.$sboAddrefsTableAlias.'.order_item_value,'.$sboProductTableAlias.'.user_ref)) > :sku_max_length, true, false)';
    }

    protected function missingRefProperty()
    {
        return 'COALESCE(pa.reference, p.reference, "") = ""';
    }

    protected function lockedComponentProperty()
    {
        // no components in products
        return 'false';
    }

    public function addSboDuplicateTargetRefPropertyToQuery(DbQuery $dbQuery, &$errors)
    {
        $errors[] = 'duplicate_target_ref';
    }

    public function getCollected()
    {
        $dbQuery = (new DbQuery())
            ->select('*')
            ->from($this->getSboProductsTable())
            ->where('id_sbo_account = :id_sbo_account')
            ->where('is_pack = false')
        ;
        $stmt = $this->getPdo()->prepare($dbQuery);
        $stmt->execute(['id_sbo_account' => $this->getService()->getSboAccount()->getId()]);

        return $stmt;
    }

    public function getAddrefCollected()
    {
        $dbQuery = (new DbQuery())
            ->select('*')
            ->from($this->getSboAdditionalRefsTable())
            ->where('id_sbo_account = :id_sbo_account')
            ->where('matched_quantity = 1')
        ;
        $stmt = $this->getPdo()->prepare($dbQuery);
        $stmt->execute(['id_sbo_account' => $this->getService()->getSboAccount()->getId()]);

        return $stmt;
    }

    private function addProductTypeParts(DbQuery $dbQuery)
    {
        if (version_compare(_PS_VERSION_, '1.7.8.0', '<'))
        {
            return $dbQuery->where('p.cache_is_pack = 0');
        }

        return $dbQuery
            ->select('p.product_type')
            ->where('p.product_type IN("'.Shippingbo::PS_PRODUCT_TYPE_PRODUCT.'","'.Shippingbo::PS_PRODUCT_TYPE_COMBINATIONS.'")')
            ;
    }
}
