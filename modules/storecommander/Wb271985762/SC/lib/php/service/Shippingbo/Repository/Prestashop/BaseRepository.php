<?php

namespace Sc\Service\Shippingbo\Repository\Prestashop;

use Db;
use DbQuery;
use Sc\ScLogger\ScLogger;
use Sc\Service\Shippingbo\Model\AdditionalRefsModel as SboAdditionalRefsModel;
use Sc\Service\Shippingbo\Model\PackComponentModel as SboPackComponentModel;
use Sc\Service\Shippingbo\Model\ProductModel as SboProductModel;
use Sc\Service\Shippingbo\Model\ShippingboAccountModel as SboAccountModel;
use Sc\Service\Shippingbo\Model\ShopRelationModel as SboShopRelationModel;
use Sc\Service\Shippingbo\Shippingbo;

abstract class BaseRepository
{
    const PS_PRODUCT_TABLE_NAME = 'product';
    const PS_PRODUCT_ATTRIBUTE_TABLE_NAME = 'product_attribute';
    const PS_PACK_TABLE_NAME = 'pack';

    /**
     * @var string
     */
    public $sboAdditionalRefsTable;

    /**
     * @var string
     */
    public $sboProductsTable;
    /**
     * @var mixed
     */
    public $sboShopRelationTable;
    /**
     * @var Shippingbo
     */
    public $service;

    /**
     * @var mixed
     */
    protected $sboPackComponentTable;
    /**
     * @var mixed
     */
    protected $idLang;
    /**
     * @var \mysqli|\PDO|resource|null
     */
    protected $pdo;
    /**
     * @var mixed
     */
    public $sboShopRelationTablePrimary;

    /**
     * @var mixed
     */
    public $sboAccountTable;
    /**
     * @var ScLogger
     */
    private $logger;

    public function __construct(Shippingbo $service)
    {
        $this->sboProductsTable = (new SboProductModel())->getTableName();
        $this->sboAdditionalRefsTable = (new SboAdditionalRefsModel())->getTableName();
        $this->sboPackComponentTable = (new SboPackComponentModel())->getTableName();
        $this->sboShopRelationTable = (new SboShopRelationModel())->getTableName();
        $this->sboAccountTable = (new SboAccountModel())->getTableName();

        $this->sboShopRelationTablePrimary = (new SboShopRelationModel())->getPrimaryKey();

        $this->service = $service;
        $this->idLang = $service->getScAgent()->getIdLang();
        $this->logger = $service->getLogger();
    }

    /**
     * @param array<int,string>|string $type
     *
     * @return mixed
     */
    public function getSyncedByType($type)
    {
        if (!is_array($type))
        {
            $type = [$type];
        }

        $inClause = '\''.implode('\',\'', $type).'\'';

        return (new DbQuery())
            ->select('*')
            ->from($this->getSboRelationTable(), 'ps_relation')
            ->where('is_locked = false')
            ->where('id_sbo IS NOT NULL')
            ->where('id_product IS NOT NULL')
            ->where('type_sbo IN('.$inClause.') ')
            ->where('id_shop = :id_shop')
        ;
    }

    /**
     * @desc : add product information to query
     *
     * @return \DbQuery
     */
    public function addPsLangImageParts(DbQuery $dbQuery)
    {
        $combinationNameSubQuery = (new DbQuery())
            ->select('GROUP_CONCAT(al.name SEPARATOR " ")')
            ->from('product_attribute', 'pa_sub')
            ->leftJoin('product_attribute_combination', 'pac', 'pac.id_product_attribute = pa_sub.id_product_attribute')
            ->leftJoin('attribute', 'attr', 'attr.id_attribute = pac.id_attribute')
            ->leftJoin('attribute_lang', 'al', 'al.id_attribute = pac.id_attribute AND al.id_lang = :id_lang')
            ->leftJoin('attribute_group_lang', 'agl', ' agl.id_attribute_group = attr.id_attribute_group AND agl.id_lang = :id_lang')
            ->where('pa_sub.id_product = p.id_product AND pa_sub.id_product_attribute =pa.id_product_attribute')
        ;
        $dbQuery
            ->select('pl.name')
            ->select('pl.id_lang')
            ->select('COALESCE(pai.id_image, i.id_image, 0) as id_image')
            ->select('('.$combinationNameSubQuery.') as combination_name')
            ->leftJoin('product_lang', 'pl', 'pl.id_product = p.id_product AND pl.id_lang = :id_lang')
            ->leftJoin('image_shop', 'i', 'i.id_product= p.id_product AND i.cover=1')
            ->leftJoin('product_attribute_image', 'pai', 'pai.id_product_attribute= pa.id_product_attribute')
            ;

        return $dbQuery;
    }

    /**
     * @return \DbQuery
     */
    protected static function getSboIsBatch()
    {
        $dbQuery = (new DbQuery())
            ->select('IF(sub_sbo.is_pack IS NULL AND COUNT(DISTINCT sub_pak.id_product_item) = 1 AND MAX(sub_pak.quantity) > 1,  true,  false) AS is_sbo_batch')
            ->from(self::PS_PACK_TABLE_NAME, 'sub_pak')
            ->leftJoin(self::PS_PRODUCT_TABLE_NAME, 'sub_p', 'sub_p.id_product = sub_pak.id_product_pack')
            ->leftJoin(self::PS_PRODUCT_ATTRIBUTE_TABLE_NAME, 'sub_pa', 'sub_pa.id_product = sub_p.id_product')
            ->leftJoin((new SboProductModel())->getTableName(), 'sub_sbo', 'sub_sbo.user_ref = sub_p.reference OR sub_sbo.user_ref = sub_pa.reference')
            ->leftJoin((new SboAdditionalRefsModel())->getTableName(), 'sub_sbo_addrefs', 'sub_sbo_addrefs.order_item_value = sub_sbo.user_ref')
            ->where('sub_p.id_product = p.id_product')
            ->groupBy('sub_pak.id_product_pack');

        return $dbQuery;
    }

    /**
     * @param DbQuery $dbQuery
     *
     * @return void
     */
    public static function addGuessedSboType(&$dbQuery)
    {
        if (version_compare(_PS_VERSION_, '1.7.8.0', '>='))
        {
            $dbQuery->select('IF(IF(p.product_type = "'.Shippingbo::PS_PRODUCT_TYPE_PACK.'", true, false), IF(('.self::getSboIsBatch().') = 1,"batch","pack"), "product") as type_sbo');
        }
        else
        {
            $dbQuery->select('IF(IF(p.cache_is_pack = 1, true, false), IF(('.self::getSboIsBatch().') = 1,"batch","pack"), "product") as type_sbo');
        }
    }

    /**
     * build select. c'est vilain mais pas d'expression builder dans dbquery.
     *
     * @return string
     */
    public static function getSelectForGuessedSboType()
    {
        $dbQuery = (new DbQuery())
            ->from('dummy'); // dupper le query builder pour ne pas avoir d'erreur si pas de "from"
        self::addGuessedSboType($dbQuery);
        $select = $dbQuery->__toString();
        $select = str_replace('FROM `'._DB_PREFIX_.'dummy`', '', $select);
        $select = str_replace(' as guessed_sbo_type', '', $select);

        return '('.$select.')';
    }

    /**
     * @return DbQuery
     */
    public function addSboErrorParts(DbQuery $dbQuery)
    {
        $exportFields = json_decode($this->getService()->getConfigValue('defaultDataExport'), true);
        $unitConversion = json_decode($this->getService()->getConfigValue('unitConversion'), true);

        $errors = [];

        if (method_exists($this, 'invalidWidth'))
        {
            $dbQuery->select('('.$this->invalidWidth($exportFields, $unitConversion).') as invalid_width');
            $errors[] = 'invalid_width';
        }
        if (method_exists($this, 'invalidHeight'))
        {
            $dbQuery->select('('.$this->invalidHeight($exportFields, $unitConversion).') as invalid_height');
            $errors[] = 'invalid_height';
        }
        if (method_exists($this, 'invalidDepth'))
        {
            $dbQuery->select('('.$this->invalidDepth($exportFields, $unitConversion).') as invalid_depth');
            $errors[] = 'invalid_depth';
        }
        if (method_exists($this, 'invalidWeight'))
        {
            $dbQuery->select('('.$this->invalidWeight($exportFields, $unitConversion).') as invalid_weight');
            $errors[] = 'invalid_weight';
        }
        if (method_exists($this, 'missingRefProperty'))
        {
            $dbQuery->select('('.$this->missingRefProperty().') as missing_ref');
            $errors[] = 'missing_ref';
        }
        if (method_exists($this, 'addSboDuplicateTargetRefPropertyToQuery'))
        {
            $this->addSboDuplicateTargetRefPropertyToQuery($dbQuery, $errors);
        }
        if (method_exists($this, 'addPsLockedComponentPropertyToQuery'))
        {
            $this->addPsLockedComponentPropertyToQuery($dbQuery, $errors);
        }

        if (method_exists($this, 'addPsMissingComponentPropertyToQuery'))
        {
            $this->addPsMissingComponentPropertyToQuery($dbQuery, $errors);
        }
        if (!empty($errors))
        {
            $dbQuery->having('('.implode(' OR ', $errors).') = :has_error');
        }

        return $dbQuery;
    }

    /**
     * @param bool $isSubRequest
     *
     * @return DbQuery
     */
    public function addPsErrorParts(DbQuery $dbQuery, $isSubRequest = false)
    {
        $errors = [];

        if (method_exists($this, 'skuTooLongProperty'))
        {
            $dbQuery->select('('.$this->skuTooLongProperty($isSubRequest).') as sku_too_long');
            $errors[] = 'sku_too_long';
        }

        if (method_exists($this, 'addSboDuplicateTargetRefPropertyToQuery'))
        {
            $this->addSboDuplicateTargetRefPropertyToQuery($dbQuery, $errors, $isSubRequest);
        }
        if (method_exists($this, 'addSboLockedComponentPropertyToQuery'))
        {
            $this->addSboLockedComponentPropertyToQuery($dbQuery, $errors, $isSubRequest);
        }
        if (method_exists($this, 'addSboMissingComponentPropertyToQuery'))
        {
            $this->addSboMissingComponentPropertyToQuery($dbQuery, $errors);
        }
        if (method_exists($this, 'addSboComponentWithErrorPropertyToQuery'))
        {
            $this->addSboComponentWithErrorPropertyToQuery($dbQuery, $errors);
        }
        if (!empty($errors))
        {
            $dbQuery->having('('.implode(' OR ', $errors).') = :has_error');
        }

        return $dbQuery;
    }

    /**
     * @param int|false $page
     *
     * @return DbQuery
     */
    public function setPage(DbQuery $dbQuery, $page = false)
    {
        if ($page !== false)
        {
            $offset = $page * $this->getService()->getGridResultsPerPage();
            $dbQuery->limit($this->getService()->getGridResultsPerPage(), $offset);
        }

        return $dbQuery;
    }

    /**
     * @return mixed|string
     */
    public function getSboProductsTable()
    {
        return $this->sboProductsTable;
    }

    /**
     * @param mixed|string $sboProductsTable
     */
    public function setSboProductsTable($sboProductsTable)
    {
        $this->sboProductsTable = $sboProductsTable;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getSboRelationTable()
    {
        return $this->sboShopRelationTable;
    }

    /**
     * @param mixed|string $sboShopRelationTable
     */
    public function setSboRelationTable($sboShopRelationTable)
    {
        $this->sboShopRelationTable = $sboShopRelationTable;
    }

    /**
     * @return \mysqli|\PDO|resource|null
     */
    public function getPdo()
    {
        if (!$this->pdo)
        {
            $this->setPdo(Db::getInstance()->getLink());
        }

        return $this->pdo;
    }

    /**
     * @param \mysqli|\PDO|resource|null $pdo
     */
    public function setPdo($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return Shippingbo
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param Shippingbo $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function getSboPackComponentTable()
    {
        return $this->sboPackComponentTable;
    }

    /**
     * @param mixed $sboPackComponentTable
     */
    public function setSboPackComponentTable($sboPackComponentTable)
    {
        $this->sboPackComponentTable = $sboPackComponentTable;
    }

    /**
     * @return mixed|string
     */
    public function getSboAdditionalRefsTable()
    {
        return $this->sboAdditionalRefsTable;
    }

    /**
     * @param mixed|string $sboAdditionalRefsTable
     */
    public function setSboAdditionalRefsTable($sboAdditionalRefsTable)
    {
        $this->sboAdditionalRefsTable = $sboAdditionalRefsTable;
    }

    /**
     * @return mixed
     */
    public function getSboShopRelationTablePrimary()
    {
        return $this->sboShopRelationTablePrimary;
    }

    protected function invalidWidth($exportFields, $unitConversion)
    {
        if (!(bool) $exportFields['width'])
        {
            return 'false ';
        }

        return 'MOD(p.width*'.(int) $unitConversion['dimension'].',1) > 0 ';
    }

    protected function invalidHeight($exportFields, $unitConversion)
    {
        if (!(bool) $exportFields['height'])
        {
            return 'false ';
        }

        return 'MOD(p.height*'.(int) $unitConversion['dimension'].',1) > 0 ';
    }

    protected function invalidDepth($exportFields, $unitConversion)
    {
        if (!isset($exportFields['depth']) or !(bool) $exportFields['depth'])
        {
            return 'false ';
        }

        return 'MOD(p.depth*'.(int) $unitConversion['dimension'].',1) > 0 ';
    }

    /**
     * @param array<string,mixed> $exportFields
     * @param array<string,mixed> $unitConversion
     *
     * @return string
     */
    protected function invalidWeight($exportFields, $unitConversion)
    {
        if (!isset($exportFields['weight']) or !(bool) $exportFields['weight'])
        {
            return 'false ';
        }

        return 'MOD((COALESCE(pa.weight,0) +p.weight)*'.(int) $unitConversion['weight'].',1) > 0 ';
    }

    public function getLogger(): ScLogger
    {
        return $this->logger;
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
            ->leftJoin('stock_available', 'sa', 'p.id_product = sa.id_product AND COALESCE(pa.id_product_attribute,0)=sa.id_product_attribute')
            ;
    }
}
