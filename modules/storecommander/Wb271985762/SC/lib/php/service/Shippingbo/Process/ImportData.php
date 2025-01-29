<?php

namespace Sc\Service\Shippingbo\Process;

use Exception;
use PDO;
use PrestaShopDatabaseException;
use PrestaShopException;
use Product;
use Sc\ScProcess\ScProcessInterface;
use Sc\ScProcess\Traits\ScProcessWithPaginationTrait;
use Sc\Service\Shippingbo\Repository\Prestashop\SegmentRepository;
use Sc\Service\Shippingbo\Repository\ShopRelationRepository;
use Sc\Service\Shippingbo\Shippingbo;

class ImportData implements ScProcessInterface
{
    use ScProcessWithPaginationTrait;
    const PRODUCT_NAME_TYPE_SKU = 'logistic_sku';
    const PRODUCT_NAME_TYPE_TITLE = 'logistic_title';

    const NB_BATCH_IMPORT = 50;

    /**
     * @var SegmentRepository
     */
    public $segment = null;
    /**
     * @var array|bool[]
     */
    public $fieldsToImport = ['width' => true, 'height' => true, 'length' => true, 'weight' => true];
    /**
     * @var mixed
     */
    public $type;
    /**
     * @var SegmentRepository
     */
    public $lastCreatedSegment;
    /**
     * @var false|mixed
     */
    protected $segmentType = SegmentRepository::TYPE_PENDING;
    /**
     * @var CollectData|ShopRelationRepository
     */
    protected $collect;
    /**
     * @var int
     */
    protected $nbProductsImported;
    /**
     * @var int
     */
    protected $nbPacksImported;
    /**
     * @var int
     */
    protected $nbBatchesImported;
    /**
     * @var int
     */
    protected $nbProductsUpdated;
    /**
     * @var int
     */
    protected $nbBatchesUpdated;
    /**
     * @var int
     */
    protected $nbPacksUpdated;
    /**
     * @var Shippingbo
     */
    private $service;
    /**
     * @var false|mixed
     */
    private $productNameType = self::PRODUCT_NAME_TYPE_TITLE;

    public function __construct(Shippingbo $service)
    {
        $this->service = $service;
        $this->collect = $service->getCollectProcess();
        $this->nbProductsImported = 0;
        $this->nbPacksImported = 0;
        $this->nbBatchesImported = 0;
        $this->nbBatchesUpdated = 0;
        $this->nbProductsUpdated = 0;
        $this->nbPacksUpdated = 0;
    }

    /**
     * @desc : create specific segments if needed
     *
     * @return SegmentRepository
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function getSegment()
    {
        if (!$this->segment)
        {
            // Création segment de base
            $sboRootSegmentId = SegmentRepository::getRootSegmentId();
            switch ($this->segmentType)
            {
                case 'pending':
                    $idSegment = SegmentRepository::getPendingSegmentId($sboRootSegmentId, $this->service->getScAgent());
                    break;
                default:
                    $idSegment = SegmentRepository::getInstantSboSegmentId($sboRootSegmentId, $this->service->getScAgent());
            }
            $segment = new SegmentRepository($this->getService()->getScAgent());
            $segment->id = $idSegment;
            $this->segment = $segment;
            $this->setLastCreatedSegment($this->segment);
        }

        return $this->segment;
    }

    /**
     * @return SegmentRepository
     */
    public function getLastCreatedSegment()
    {
        return $this->lastCreatedSegment;
    }

    /**
     * @param SegmentRepository $lastCreatedSegment
     */
    public function setLastCreatedSegment($lastCreatedSegment)
    {
        $this->lastCreatedSegment = $lastCreatedSegment;

        return $this;
    }

    /**
     * @return int
     */
    public function getNbProductsUpdated()
    {
        return $this->nbProductsUpdated;
    }

    /**
     * @param int $nbProductsUpdated
     */
    public function setNbProductsUpdated($nbProductsUpdated)
    {
        $this->nbProductsUpdated = $nbProductsUpdated;

        return $this;
    }

    /**
     * @return int
     */
    public function getNbProductsImported()
    {
        return $this->nbProductsImported;
    }

    /**
     * @return int
     */
    public function getNbPacksImported()
    {
        return $this->nbPacksImported;
    }

    /**
     * @return int
     */
    public function getNbBatchesImported()
    {
        return $this->nbBatchesImported;
    }

    /**
     * @param int $nbBatchesImported
     */
    public function setNbBatchesImported($nbBatchesImported)
    {
        $this->nbBatchesImported = $nbBatchesImported;

        return $this;
    }

    /**
     * [IMPORT].
     *
     * @param false|mixed $productNameType
     */
    public function setProductNameType($productNameType)
    {
        if ($productNameType)
        {
            $this->productNameType = $productNameType;
        }

        return $this;
    }

    /**
     * @param false|mixed $segmentType
     */
    public function setSegmentType($segmentType = null)
    {
        if ($segmentType)
        {
            $this->segmentType = $segmentType;
        }

        return $this;
    }

    /**
     * @param array|bool[] $fieldsToImport
     */
    public function setFieldsToImport($fieldsToImport = null)
    {
        if ($fieldsToImport)
        {
            $this->fieldsToImport = $fieldsToImport;
        }

        return $this;
    }

    public function startImport()
    {
        try
        {
            $this->createPsProducts();
            $this->createPsBatches();
            $this->createPsPacks();
            $this->getService()->doMatch();
            $this->getService()->getFullStats();
        }
        catch (Exception $e)
        {
            $this->getService()->addError($e);
        }
        $this->segment = null; // initialise segment after import

        return $this->getService();
    }

    /**
     * [IMPORT].
     *
     * @desc : add packs information to PS
     *
     * @return bool
     */
    public function replacePsPack($product_id, $id_product_item, $id_product_attribute_item, $quantity)
    {
        if (!$id_product_item)
        {
            return false;
        }
        $stmtPack = $this->getService()->getPackRepository()->setPsStatement();
        $packParams = [
            ':id_product_pack' => $product_id,
            ':id_product_item' => $id_product_item,
            ':id_product_attribute_item' => $id_product_attribute_item,
            ':quantity' => $quantity,
        ];

        return $stmtPack->execute($packParams);
    }

    /**
     * @return Shippingbo
     */
    public function getService()
    {
        return $this->service;
    }

    public function getProcessMessageForIteration($iteration, $countProcessed, $method, $methodArguments)
    {
        $totalProcessed = ($iteration + 1) * $countProcessed;
        if ($totalProcessed)
        {
            return _l('%s %s updates from Shippingbo', 0, [$totalProcessed, $methodArguments[0]]);
        }

        return _l('No %s update from Shippingbo', false, [$methodArguments[0]]);
    }

    /**
     * @param $message
     *
     * @return string
     */
    public function getProcessMessageCompleted($message)
    {
        return $message;
    }

    /**     * [IMPORT].
     *
     * @param $productInfos : product array
     * @param $type : product type ('product','batch', 'pack')
     * @param $ref : reference index to use from $productInfos
     *
     * @return Product
     *
     * @throws PrestaShopException
     */
    protected function insertPsProduct($productInfos, $type, $ref)
    {
        $productName = ($this->productNameType === self::PRODUCT_NAME_TYPE_SKU) ? ucfirst(_l($type, 1)).' '.$productInfos[$ref] : $productInfos['title'];
        $is_pack = in_array($type, [Shippingbo::SBO_PRODUCT_TYPE_BATCH, Shippingbo::SBO_PRODUCT_TYPE_PACK]);

        return $this->getService()->getProductRepository()->saveProduct($productInfos, $productName, $ref, $is_pack, $this->getService()->getScAgent()->getIdLang());
    }

    /**
     * [IMPORT].
     *
     * @return void
     *
     * @throws Exception
     */
    private function createPsProducts()
    {
        $productRepository = $this->getService()->getProductRepository();
        $query = $productRepository->getMissingPsQuery();
        $productRepository->addPsErrorParts($query);
        $productsStatement = $this->getService()->getPdo()->prepare($query);
        $productsStatement->execute([
            ':id_shop' => $this->getService()->getIdShop(),
            ':id_sbo_account' => $this->getService()->getConfigValue('id_sbo_account'),
            ':is_locked' => false,
            ':has_error' => false,
            ':sku_max_length' => \Product::$definition['fields']['reference']['size'],
        ]);

        $this->nbProductsImported = $productsStatement->rowCount();
        // get all imported SKUS with product information
        foreach ($productsStatement->fetchAll(PDO::FETCH_ASSOC) as $productInfos)
        {
            $product = $this->insertPsProduct($productInfos, 'product', 'user_ref');
            $this->getSegment()->addProduct($product, $this->getService()->getIdShop());
        }
        $this->getService()->getLogger()->info($productsStatement->rowCount().' product(s) imported');
    }

    /**
     * [IMPORT].
     *
     * @return void
     *
     * @throws Exception
     */
    private function createPsBatches()
    {
        $batchesRepository = $this->getService()->getBatchRepository();
        $query = $batchesRepository->getMissingPsQuery();
        $batchesRepository->addPsErrorParts($query); // TODO 2 : remplacer par getMissingPsQueryWithErrors
        $batchesStatement = $this->getService()->getPdo()->prepare($query);
        $batchesStatement->execute([
            ':id_shop' => $this->getService()->getIdShop(),
            ':id_sbo_account' => $this->getService()->getConfigValue('id_sbo_account'),
            ':is_locked' => false,
            ':has_error' => false,
            ':sku_max_length' => \Product::$definition['fields']['reference']['size'],
        ]);
        $this->nbBatchesImported += $batchesStatement->rowCount();
        if (!$this->getService()->getPdo()->inTransaction())
        {
            $this->getService()->getPdo()->beginTransaction();
        }
        foreach ($batchesStatement->fetchAll(PDO::FETCH_ASSOC) as $productInfos)
        {
            if ($productInfos['id_product_item'])
            {
                $product = $this->insertPsProduct($productInfos, 'batch', 'user_ref');
                $this->getSegment()->addProduct($product, $this->getService()->getIdShop());
                $this->replacePsPack($product->id, $productInfos['id_product_item'], $productInfos['id_product_attribute_item'], $productInfos['matched_quantity']);
            }
        }

        if ($this->getService()->getPdo()->inTransaction())
        {
            $this->getService()->getPdo()->commit();
        }
        $this->getService()->getLogger()->info($batchesStatement->rowCount().' batch(es) imported');
    }

    /**
     * @return void
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    private function createPsPacks()
    {
        $isSboAccount = $this->getService()->getSboAccount()->getId();
        $packsRepository = $this->getService()->getPackRepository();
        $query = $packsRepository->getMissingPsQuery();
        $packsRepository->addPsErrorParts($query); // TODO 2 : remplacer par getMissingPsQueryWithErrors
        $packsStatement = $this->getService()->getPdo()->prepare($query);
        $packsStatement->execute([
            ':id_shop' => $this->getService()->getIdShop(),
            ':id_sbo_account' => $isSboAccount,
            ':is_locked' => false,
            ':has_error' => false,
            ':sku_max_length' => \Product::$definition['fields']['reference']['size'],
        ]);
        $this->nbPacksImported += $packsStatement->rowCount() ?: 0;
        $packComponentsPrepareStatement = $this->getService()->getPdo()->prepare($this->getService()->getPackRepository()->getComponentsQuery());
        foreach ($packsStatement->fetchAll(PDO::FETCH_ASSOC) as $productInfos)
        {
            // creation/récupération produit
            $product = $this->insertPsProduct($productInfos, 'pack', 'user_ref');
            $this->getSegment()->addProduct($product, $this->getService()->getIdShop());
            // récupérer les produits liés dans buffer pack
            $packComponentsPrepareStatement->execute([
                ':id_product' => $product->id,
                ':id_sbo_account' => $isSboAccount,
            ]);

            foreach ($packComponentsPrepareStatement->fetchAll(PDO::FETCH_ASSOC) as $packComponent)
            {
                // add pack to db
                $this->replacePsPack($product->id, $packComponent['id_product'], $packComponent['id_product_attribute'], $packComponent['quantity']);
            }
        }
        $this->getService()->getLogger()->info($packsStatement->rowCount().' pack(s) imported');
    }

    /**
     * @return array|bool[]|true[]
     */
    public function getFieldsToImport()
    {
        return $this->fieldsToImport;
    }

    /**
     * @param $key
     *
     * @return bool|mixed
     */
    public function getFieldToImport($key)
    {
        if (!$key)
        {
            return false;
        }
        if (!isset($this->fieldsToImport[$key]))
        {
            return false;
        }

        return $this->fieldsToImport[$key];
    }
}
