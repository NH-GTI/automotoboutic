<?php

namespace Sc\Service\Shippingbo\Repository;

use Db;
use DbQuery;
use mysqli_stmt;
use Pack;
use PDO;
use PDOStatement;
use Product;
use Sc\Service\Shippingbo\Entity\ShopRelation;
use Sc\Service\Shippingbo\Model\ShopRelationModel;
use Sc\Service\Shippingbo\Repository\Prestashop\BaseRepository;
use Sc\Service\Shippingbo\Repository\Prestashop\BatchRepository;
use Sc\Service\Shippingbo\Repository\Prestashop\PackRepository;
use Sc\Service\Shippingbo\Repository\Prestashop\ProductRepository;
use Sc\Service\Shippingbo\Shippingbo;
use SCI;

class ShopRelationRepository extends BaseRepository
{
    /**
     * @var ProductRepository
     */
    public $product;
    /**
     * @var BatchRepository
     */
    public $batch;
    /**
     * @var PackRepository
     */
    public $pack;
    /**
     * @var ShippingboRepository
     */
    public $shippingbo;

    /**
     * @param int $id_shop
     *
     * @return $this
     */
    public function removeRelations($id_shop = 0)
    {
        $logText = $id_shop > 0 ? 'shop #'.$id_shop : 'all shops';
        $this->getService()->getLogger()->debug('Remove relations for '.$logText);

        $sql = 'UPDATE `'._DB_PREFIX_.$this->getSboRelationTable().'` SET id_product = NULL, id_product_attribute = NULL WHERE id_sbo IS NOT NULL AND id_product IS NOT NULL';
        if ($id_shop && $id_shop > 0)
        {
            $sql .= ' AND id_shop = '.(int) $id_shop;
        }
        $this->getPdo()->query($sql);
        $this->getService()->getLogger()->debug('Relations for '.$logText.' removed');

        return $this;
    }

    /**
     * @param int $id_shop
     *
     * @return $this
     */
    public function removeAllRelations($id_shop = 0)
    {
        $logText = $id_shop > 0 ? 'shop #'.$id_shop : 'all shops';
        $this->getService()->getLogger()->debug('Remove ALL relations for '.$logText);
        $sql = 'DELETE FROM `'._DB_PREFIX_.$this->getSboRelationTable().'`';
        if ($id_shop && $id_shop > 0)
        {
            $sql .= ' WHERE id_shop = '.(int) $id_shop;
        }
        $this->getPdo()->query($sql);
        $this->getService()->getLogger()->debug('ALL Relations for '.$logText.' removed');

        return $this;
    }

    /**
     * @param int|null $id_sbo_account
     *
     * @return $this
     */
    public function removeAllRelationsForSboAccountId($id_sbo_account = null)
    {
        $shops = SCI::getAllShops();
        if ($id_sbo_account && $id_sbo_account > 0)
        {
            $shops = $this->getService()->getShopsIdBySboAccountId($id_sbo_account);
        }
        $shopIds = array_column($shops, 'id_shop');
        foreach ($shopIds as $shopId)
        {
            $this->removeAllRelations($shopId);
        }

        return $this;
    }

    /**
     * @return false|mysqli_stmt|PDOStatement
     */
    public function getAddStatement()
    {
        $queryPrepare = 'INSERT INTO `'._DB_PREFIX_.$this->getSboRelationTable().'` (`id_sbo`,`id_shop`,`id_product`,`id_product_attribute`,`reference`,`id_sbo_source`,`is_locked`,`type_sbo`,`created_at`,`updated_at`) VALUES (:id_sbo,:id_shop,:id_product,:id_product_attribute,:reference,:id_sbo_source,:is_locked,:type_sbo,:created_at,:updated_at)
';

        return $this->getPdo()->prepare($queryPrepare);
    }

    /**
     * @return string
     */
    public function getInsertQuery()
    {
        return 'INSERT  `'._DB_PREFIX_.$this->getSboRelationTable().'` (`id_sbo`,`id_shop`,`id_product`,`id_product_attribute`,`reference`,`id_sbo_source`,`is_locked`,`type_sbo`,`created_at`,`updated_at`) VALUES (:id_sbo,:id_shop,:id_product,:id_product_attribute,:reference,:id_sbo_source,:is_locked,:type_sbo,:created_at,:updated_at)';
    }

    /**
     * @return string
     */
    public function getUpdateQuery()
    {
        return 'UPDATE  `'._DB_PREFIX_.$this->getSboRelationTable().'` SET `id_sbo` = :id_sbo, `id_shop`=:id_shop,`id_product`=:id_product,`id_product_attribute`=:id_product_attribute,`reference`=:reference,`id_sbo_source`=:id_sbo_source,`is_locked`=:is_locked,`type_sbo`=:type_sbo,`updated_at`=:updated_at WHERE (id_storecom_service_shippingbo_shop_relation = :id_storecom_service_shippingbo_shop_relation AND id_shop=:id_shop)
';
    }

    /**
     * @return false|mysqli_stmt|PDOStatement
     */
    public function getUpdateStatement()
    {
        return $this->getPdo()->prepare($this->getUpdateQuery());
    }

    /**
     * @desc :  insert or update sbo product data in buffer table
     *
     * @return false|mysqli_stmt|PDOStatement
     */
    public function getAddOrUpdateStatement()
    {
        $queryPrepare = 'INSERT INTO `'._DB_PREFIX_.$this->getSboRelationTable().'` (`id_sbo`,`id_shop`,`id_product`,`id_product_attribute`,`reference`,`id_sbo_source`,`is_locked`,`type_sbo`,`created_at`,`updated_at`) VALUES (:id_sbo,:id_shop,:id_product,:id_product_attribute,:reference,:id_sbo_source,:is_locked,:type_sbo,:created_at,:updated_at)
        ON DUPLICATE KEY UPDATE
            `id_sbo` =  CASE WHEN :id_sbo IS NULL THEN `id_sbo` ELSE :id_sbo END,
            `id_shop` =  CASE WHEN :id_shop IS NULL THEN `id_shop` ELSE :id_shop END,
            `id_product` =  CASE WHEN :id_product IS NULL THEN `id_product` ELSE :id_product END,
            `id_product_attribute` =  CASE WHEN :id_product_attribute IS NULL THEN `id_product_attribute` ELSE :id_product_attribute END,
            `reference` =  CASE WHEN :reference IS NULL THEN `reference` ELSE :reference END,
            `id_sbo_source` =  CASE WHEN :id_sbo_source IS NULL THEN `id_sbo` ELSE :id_sbo_source END,
            `is_locked` = CASE WHEN :is_locked IS NULL THEN `is_locked` ELSE :is_locked END,
            `type_sbo` = CASE WHEN :type_sbo IS NULL THEN `type_sbo` ELSE :type_sbo END,
            `updated_at` = :updated_at
';

        return $this->getPdo()->prepare($queryPrepare);
    }

    /**
     * @return false|mysqli_stmt|PDOStatement
     */
    public function getUpdateTypeForAllShopsStatement()
    {
        $queryPrepare = 'UPDATE  `'._DB_PREFIX_.$this->getSboRelationTable().'` SET 
        `type_sbo`= :type_sbo, updated_at = :updated_at WHERE `id_product`=:id_product AND id_product_attribute = :id_product_attribute;';

        return $this->getPdo()->prepare($queryPrepare);
    }

    /**
     * @return false|mysqli_stmt|PDOStatement
     */
    public function deleteShopRelationStatement()
    {
        $queryPrepare = 'DELETE FROM `'._DB_PREFIX_.$this->getSboRelationTable().'`
            WHERE '.$this->getSboShopRelationTablePrimary().'=:id 
            AND id_shop=:id_shop 
';

        return $this->getPdo()->prepare($queryPrepare);
    }

    /**
     * @return false|mysqli_stmt|PDOStatement
     */
    public function unlinkShopRelationStatement()
    {
        $queryPrepare = 'UPDATE `'._DB_PREFIX_.$this->getSboRelationTable().'` SET `id_product`=NULL, `id_product_attribute`=NULL
            WHERE '.$this->getSboShopRelationTablePrimary().'=:id AND id_shop=:id_shop
';

        return $this->getPdo()->prepare($queryPrepare);
    }

    /**
     * get all id_product and id_product_attribute not present in ps relation table with
     *  * bool match_found field : if product or product_attribute reference is found in ps_relation table (join on buffer tables to find reference)
     *  * bool is_related field : if id_product+id_product_attribute is already associated to shippingbo id.
     *
     * @return string
     */
    public function getMissingFromShopProductsQuery()
    {
        // récupération de tous les produits de PS qui ne sont pas dans la table relation
        $psQuery = (new DbQuery())
            ->select('ps_relation.id_storecom_service_shippingbo_shop_relation as id')
            ->select('ps_relation.id_sbo as `id_sbo`')
            ->select('ps_relation.is_locked')
            ->select('ps_relation.id_product as related_id_product')
            ->select('p.id_product')
            ->select('p.active')
            ->select('pa.id_product_attribute')
            ->select('COALESCE(pas.id_shop, ps.id_shop,0) as id_shop')
            ->select('ps_relation.id_sbo_source')
            ->select('COALESCE(pa.reference,p.reference) as reference')
            ->select('IF(ps_relation.id_sbo IS NULL AND ps_relation.id_product IS NULL,"insert","update") as action')
            ->from(self::PS_PRODUCT_TABLE_NAME, 'p')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME.'_shop', 'ps', 'ps.id_product = p.id_product AND ps.id_shop =:id_shop')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'pa', 'pa.id_product = p.id_product')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME.'_shop', 'pas', 'pas.id_product_attribute = pa.id_product_attribute AND pas.id_shop =:id_shop')
            ->leftJoin($this->getSboRelationTable(), 'ps_relation', '(ps_relation.id_product = p.id_product AND ps_relation.id_product_attribute = COALESCE(pa.id_product_attribute,0)) AND ps_relation.id_shop = :id_shop')
            ->having('(id IS NULL) AND id_shop=:id_shop')
        ;
        BaseRepository::addGuessedSboType($psQuery);

        return $psQuery->__toString();
    }

    /**
     * @return string
     */
    public function buildProductBufferMissingMatchQuery()
    {
        $duplicateRefSubQuery = (new DbQuery())
            ->select('COUNT(*)')
            ->from(self::PS_PRODUCT_TABLE_NAME, 'sub_product')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'sub_product_attribute', 'sub_product_attribute.id_product = sub_product.id_product')
            ->where('COALESCE(sub_product_attribute.reference, sub_product.reference) = sbo.user_ref');

        $dbQuery = (new DbQuery())
            ->select('ps_relation.'.(new ShopRelationModel())->getPrimaryKey().' as relation_id')
            ->select('sbo.id as id_sbo')
            ->select('sbo.location as location')
            ->select('ps_relation.id_sbo as id_sbo_source')
            ->select('COALESCE(ps_relation.is_locked, false) as is_locked')
            ->select('sbo.user_ref as reference')
            ->select('ps_relation.reference as old_reference')
            ->select('COALESCE(pa.id_product, p.id_product) as id_product')
            ->select('pa.id_product_attribute as id_product_attribute')
            ->select('('.$duplicateRefSubQuery.') > 1 as duplicate_target_ref')
            ->select('IF(ps_relation.'.(new ShopRelationModel())->getPrimaryKey().' IS NOT NULL,"update","insert") as action')
            ->select('IF(sbo.is_pack, "'.Shippingbo::SBO_PRODUCT_TYPE_PACK.'","'.Shippingbo::SBO_PRODUCT_TYPE_PRODUCT.'") as type_sbo')
            ->from($this->getSboProductsTable(), 'sbo')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME, 'p', 'p.reference = sbo.user_ref')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'pa', 'p.id_product = pa.id_product OR pa.reference = sbo.user_ref')
            ->leftJoin($this->getSboRelationTable(), 'ps_relation', '((COALESCE(pa.id_product, p.id_product)=ps_relation.id_product AND COALESCE(pa.id_product_attribute,0) =ps_relation.id_product_attribute) OR (ps_relation.id_sbo = sbo.id)) AND ps_relation.id_shop = :id_shop')
            ->where('sbo.id_sbo_account = :id_sbo_account')
            ->groupBy('reference')
        ;

        return $dbQuery->__toString();
    }

    /**
     * @return string
     */
    public function buildAddrefBufferMissingMatchQuery()
    {
        $duplicateRefSubQuery = (new DbQuery())
            ->select('COUNT(*)')
            ->from(self::PS_PRODUCT_TABLE_NAME, 'sub_product')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'sub_product_attribute', 'sub_product_attribute.id_product = sub_product.id_product')
            ->where('COALESCE(sub_product_attribute.reference, sub_product.reference) = sbo_addrefs.order_item_value');

        $dbQuery = (new DbQuery())
            ->select('ps_relation.'.(new ShopRelationModel())->getPrimaryKey().' as relation_id')
            ->select('sbo_addrefs.id as id_sbo')
            ->select('COALESCE(ps_relation.is_locked, false) as is_locked')
            ->select('sbo_addrefs.product_value as id_sbo_source')
            ->select('sbo_addrefs.order_item_value as reference')
            ->select('ps_relation.reference as old_reference')
            ->select('COALESCE(pa.id_product, p.id_product) as id_product')
            ->select('COALESCE(pa.id_product_attribute,0) as id_product_attribute')
            ->select('('.$duplicateRefSubQuery.') > 1 as duplicate_target_ref')
            ->select('IF(ps_relation.'.(new ShopRelationModel())->getPrimaryKey().' IS NOT NULL,"update","insert") as action')
            ->select('IF(sbo_addrefs.matched_quantity > 1, "'.Shippingbo::SBO_PRODUCT_TYPE_BATCH.'","'.Shippingbo::SBO_PRODUCT_TYPE_ADDREF.'") as type_sbo')
            ->from($this->getSboAdditionalRefsTable(), 'sbo_addrefs')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME, 'p', 'p.reference = sbo_addrefs.order_item_value')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'pa', 'p.id_product = pa.id_product OR pa.reference = sbo_addrefs.order_item_value')
            ->leftJoin($this->getSboRelationTable(), 'ps_relation', '((COALESCE(pa.id_product, p.id_product)=ps_relation.id_product AND COALESCE(pa.id_product_attribute,0) =ps_relation.id_product_attribute) OR ps_relation.id_sbo = sbo_addrefs.id) AND ps_relation.id_shop = :id_shop')
            ->where('sbo_addrefs.id_sbo_account = :id_sbo_account')
            ->groupBy('reference')
        ;

        return $dbQuery->__toString();
    }

    /**
     * @desc remove relation in shop relation table if related PS product has been removed
     *
     * @return string
     */
    public function entriesToRemoveQuery()
    {
        $dbQuery = (new DbQuery())
            ->select('ps_relation.*')
            ->select('ps.id_product')
            ->select('p.id_product')
            ->from($this->getSboRelationTable(), 'ps_relation')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME, 'p', 'p.id_product = ps_relation.id_product')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME.'_shop', 'ps', 'ps.id_product = p.id_product AND ps.id_shop = :id_shop')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'pa', 'ps_relation.id_product_attribute = pa.id_product_attribute')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME.'_shop', 'pas', 'pas.id_product_attribute = pa.id_product_attribute AND ps.id_shop = :id_shop')
            ->where('COALESCE(pas.id_product,ps.id_product) IS NULL')
            ->where('ps_relation.is_locked = false')
            ->where('ps_relation.id_shop = :id_shop');

        BaseRepository::addGuessedSboType($dbQuery);

        return $dbQuery->__toString();
    }

    /**
     * @return DbQuery
     */
    public function getOneByIdQuery()
    {
        $dbQuery = (new DbQuery())
            ->select('*')
            ->from($this->getSboRelationTable())
            ->where($this->getSboShopRelationTablePrimary().' = :id')
        ;

        return $dbQuery;
    }

    /**
     * @return DbQuery
     */
    public function getOneByIdSboQuery()
    {
        $dbQuery = (new DbQuery())
            ->select('ps_relation.*')
            ->from($this->getSboRelationTable(), 'ps_relation')
            ->where('id_sbo = :id_sbo')
            ->where('id_shop = :id_shop')
        ;

        return $dbQuery;
    }

    /**
     * @return string
     */
    public function getOneByIdProductQuery()
    {
        $dbQuery = (new DbQuery())
            ->select('*')
            ->from($this->getSboRelationTable())
            ->where('id_product = :id_product')
            ->where('id_shop = :id_shop')
        ;

        return $dbQuery->__toString();
    }

    /**
     * @return string
     */
    public function getOneByIdCombinationQuery()
    {
        $dbQuery = (new DbQuery())
            ->select('*')
            ->from($this->getSboRelationTable())
            ->where('id_product = :id_product')
            ->where('id_product_attribute = :id_product_attribute')
            ->where('id_shop = :id_shop')
        ;

        return $dbQuery->__toString();
    }

    /**
     * @param int $id_sbo
     *
     * @return array<int,mixed>
     *
     * @throws \Exception
     */
    public function getAllByIdSboForCurrentShop($id_sbo)
    {
        $query = $this->getOneByIdSboQuery();
        $stmt = Db::getInstance()->getLink()->prepare($query);

        $stmt->execute([
            'id_sbo' => $id_sbo,
            'id_shop' => $this->getService()->getIdShop(),
        ]);

        $relationCollection = [];
        if ($entries = $stmt->fetchAll(PDO::FETCH_ASSOC))
        {
            foreach ($entries as $entry)
            {
                $relationCollection[] = new ShopRelation($entry[(new ShopRelationModel())->getPrimaryKey()], $this->getService());
            }
        }

        return $relationCollection;
    }

    /**
     * @param int $id_sbo
     *
     * @return array<int, ShopRelation>
     *
     * @throws \Exception
     */
    public function getAllByIdSbo($id_sbo)
    {
        $stmt = Db::getInstance()->getLink()->prepare($this->getOneByIdSboQuery());
        $stmt->execute([
            'id_sbo' => $id_sbo,
            'id_shop' => $this->getService()->getIdShop(),
        ]);
        $relationCollection = [];
        if ($entries = $stmt->fetchAll(PDO::FETCH_ASSOC))
        {
            foreach ($entries as $entry)
            {
                $relationCollection[] = new ShopRelation($entry[(new ShopRelationModel())->getPrimaryKey()], $this->getService());
            }
        }

        return $relationCollection;
    }

    /**
     * @param int $id_product
     *
     * @return array<int,mixed>
     *
     * @throws \Exception
     */
    public function getAllByProductId($id_product)
    {
        $stmt = Db::getInstance()->getLink()->prepare($this->getOneByIdProductQuery());
        $params = [
            ':id_product' => $id_product,
            ':id_shop' => $this->getService()->getIdShop(),
        ];
        $stmt->execute($params);
        $relationCollection = [];
        if ($entries = $stmt->fetchAll(PDO::FETCH_ASSOC))
        {
            foreach ($entries as $entry)
            {
                $relationCollection[] = new ShopRelation($entry[(new ShopRelationModel())->getPrimaryKey()], $this->getService());
            }
        }

        return $relationCollection;
    }

    /**
     * @param int $id_product
     * @param int $id_product_attribute
     *
     * @return ShopRelation[]
     *
     * @throws \Exception
     */
    public function getAllByCombinationId($id_product, $id_product_attribute)
    {
        $stmt = Db::getInstance()->getLink()->prepare($this->getOneByIdCombinationQuery());
        $params = [
            ':id_product' => $id_product,
            ':id_product_attribute' => $id_product_attribute,
            ':id_shop' => $this->getService()->getIdShop(),
        ];
        $stmt->execute($params);
        $relationCollection = [];
        if ($entries = $stmt->fetchAll(PDO::FETCH_ASSOC))
        {
            foreach ($entries as $entry)
            {
                $relationCollection[] = new ShopRelation($entry[(new ShopRelationModel())->getPrimaryKey()], $this->getService());
            }
        }

        return $relationCollection;
    }

    /**
     * @param string $reference
     *
     * @return ShopRelation
     *
     * @throws \Exception
     */
    public function getOneByReference($reference)
    {
        $dbQuery = (new DbQuery())
            ->select('ps_relation.'.$this->getSboShopRelationTablePrimary())
            ->from($this->getSboRelationTable(), 'ps_relation')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME, 'p', 'p.id_product = ps_relation.id_product')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'pa', 'pa.id_product_attribute = ps_relation.id_product_attribute')
            ->where('COALESCE(pa.reference, p.reference,ps_relation.reference) = :reference')
            ->where('ps_relation.id_shop = :id_shop')
        ;
        $stmt = Db::getInstance()->getLink()->prepare($dbQuery);
        $stmt->execute([
            'id_shop' => $this->getService()->getIdShop(),
            'reference' => $reference,
        ]);

        return new ShopRelation($stmt->fetch(PDO::FETCH_COLUMN), $this->getService());
    }

    /**
     * @param int $id_product
     *
     * @return void
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function updateType($id_product)
    {
        $product = new Product($id_product, false, $this->getService()->getScAgent()->getIdLang(), $this->getService()->getIdShop());
        $packItems = Pack::getItems($id_product, $this->getService()->getScAgent()->getIdLang());

        if (count($packItems) === 0)
        {
            $product->product_type = Shippingbo::PS_PRODUCT_TYPE_PRODUCT;
            $sboType = Shippingbo::SBO_PRODUCT_TYPE_PRODUCT;
        }
        elseif (count($packItems) === 1 && (int) $packItems[0]->pack_quantity > 1)
        {
            $product->product_type = Shippingbo::PS_PRODUCT_TYPE_PACK;
            $sboType = Shippingbo::SBO_PRODUCT_TYPE_BATCH;
        }
        else
        {
            $product->product_type = Shippingbo::PS_PRODUCT_TYPE_PACK;
            $sboType = Shippingbo::SBO_PRODUCT_TYPE_PACK;
        }
        $product->update();
        $shopRelation = $this->getOneByReference($product->reference);
        $shopRelation->setTypeSbo($sboType)->save();
    }

    /**
     * @return bool
     */
    public function removeDuplicates()
    {
        $tableName = _DB_PREFIX_.(new ShopRelationModel())->getTableName();
        $tablePrimary = (new ShopRelationModel())->getPrimaryKey();
        $stmt = Db::getInstance()->getLink()->prepare('DELETE t1 FROM '.$tableName.' t1 INNER JOIN '.$tableName.' t2 WHERE t1.'.$tablePrimary.' < t2.'.$tablePrimary.' AND t1.reference = t2.reference AND t1.id_sbo IS NULL AND t1.id_shop=:id_shop AND t1.is_locked=false');

        return $stmt->execute([':id_shop' => $this->getService()->getIdShop()]);
    }

    /**
     * @return bool
     */
    public function removeUnLinked()
    {
        $tableName = _DB_PREFIX_.(new ShopRelationModel())->getTableName();
        $stmt = Db::getInstance()->getLink()->prepare('DELETE FROM '.$tableName.' WHERE (id_product IS NULL OR id_sbo IS NULL) AND id_shop=:id_shop AND is_locked = false');

        return $stmt->execute([':id_shop' => $this->getService()->getIdShop()]);
    }
}
