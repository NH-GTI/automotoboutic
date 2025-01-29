<?php

namespace Sc\Service\Shippingbo\Process;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use DbQuery;
use Exception;
use PDO;
use Sc\ScProcess\ScProcessInterface;
use Sc\ScProcess\Traits\ScProcessWithPaginationTrait;
use Sc\Service\Shippingbo\Repository\ShippingboRepository;
use Sc\Service\Shippingbo\Shippingbo;

class CollectData implements ScProcessInterface
{
    use ScProcessWithPaginationTrait;
    /**
     * @var ShippingboRepository
     */
    protected $api;
    /**
     * @var Shippingbo
     */
    private $service;
    /**
     * @var int
     */
    private $nbProductsCollected;
    /**
     * @var int
     */
    private $nbPacksCollected;
    /**
     * @var int
     */
    private $nbAddRefsCollected;
    /**
     * @var int|null
     */
    private $nbPackComponentsCollected;

    public function __construct(Shippingbo $service)
    {
        $this->service = $service;
        $this->nbProductsCollected = 0;
        $this->nbPacksCollected = 0;
        $this->nbAddRefsCollected = 0;
        $this->nbPackComponentsCollected = 0;
    }

    /**
     * @desc : get last synchronization date with sbo data
     *
     * @return DateTime|false
     *
     * @throws Exception
     */
    public function getStartDate()
    {
        $dbQuery = new DbQuery();
        $dbQuery = 'SELECT MAX(max_updated_at) AS min_updated_at
FROM (
    SELECT MAX(updated_at) AS max_updated_at
    FROM '._DB_PREFIX_.$this->getService()->getShopRelationRepository()->getSboProductsTable().'
    WHERE id_sbo_account = :id_sbo_account 
    UNION ALL
    SELECT MAX(updated_at)
    FROM '._DB_PREFIX_.$this->getService()->getShopRelationRepository()->getSboAdditionalRefsTable().'
    WHERE id_sbo_account = :id_sbo_account
    UNION ALL
    SELECT MAX(updated_at)
    FROM '._DB_PREFIX_.$this->getService()->getShopRelationRepository()->getSboPackComponentTable().'
    WHERE id_sbo_account = :id_sbo_account
) AS max_updated_at_per_table;
        ';

        $lastCollectPsUpdateStmt = $this->getService()->getPdo()->prepare($dbQuery);
        $lastCollectPsUpdateStmt->execute([':id_sbo_account' => $this->getService()->getSboAccount()->getId()]);

        if ($lastCollectPsUpdateStmt->rowCount() > 0)
        {
            return DateTime::createFromFormat('Y-m-d H:i:s', $lastCollectPsUpdateStmt->fetchColumn(), new DateTimeZone(ShippingboRepository::SERVER_TIMEZONE));
        }

        return false;
    }

    /**
     * @return Shippingbo
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @descr on tente de deviner une volumétrie approximative sans endpoint Api pour récupérer le total des produits dans SBO
     * non utilisé
     *
     * @return bool|int
     *
     * @throws Exception
     */
    public function getTotalProducts()
    {
//        $shippingboRepository = $this->getService()->getShippingboRepository();
//        $shippingboRepository->setBatchSize(1);
//        $steps = [1000, 2000, 3000, 5000, 7000, 10000, 20000, 30000, 50000, 70000, 100000];
//        $nbProducts = PHP_INT_MAX;
//        foreach ($steps as $key => $stepQuantity)
//        {
//            $isLessThan = empty($shippingboRepository->getProducts(false, null, $stepQuantity));
//            if ($isLessThan)
//            {
//                $nbProducts = $stepQuantity;
//                break;
//            }
//        }
//
//        return $nbProducts;
    }

    /**
     * @param int $page : current page for api paginated calls
     *
     * @return array
     *
     * @throws Exception
     */
    public function collectProducts($isPack, $lastCollect, $page = 0)
    {
        $shippingboRepository = $this->getService()->getShippingboRepository();
        $shippingboRepository->setBatchSize($this->getBatchSize());

        $products = $shippingboRepository->getProducts($isPack, $lastCollect, $page);

        if ($products)
        {
            if ($isPack)
            {
                $this->nbPacksCollected += count($products);
            }
            else
            {
                $this->nbProductsCollected += count($products);
            }

            // insertion/update dans la table buffer
            $productUpdateStatement = $this->getService()->getProductRepository()->setBufferStatement();

            if (!$this->getService()->getPdo()->inTransaction())
            {
                $this->getService()->getPdo()->beginTransaction();
            }
            foreach ($products as $product)
            {
                $synced_at = new DateTimeImmutable(null, new DateTimeZone(ShippingboRepository::SERVER_TIMEZONE));
                $updated_at = new DateTimeImmutable($product['updated_at'], new DateTimeZone(ShippingboRepository::SERVER_TIMEZONE));
                // add product to buffer table
                $productUpdateStatement->execute([
                    'id' => $product['id'],
                    'id_sbo_account' => $this->getService()->getSboAccount()->getId(),
                    'user_ref' => $product['user_ref'],
                    'is_pack' => (int) $isPack,
                    'title' => $product['title'],
                    'location' => $product['location'],
                    'weight' => $product['weight'],
                    'height' => $product['height'],
                    'length' => $product['length'],
                    'width' => $product['width'],
                    'updated_at' => $updated_at->format('Y-m-d H:i:s'),
                    'synced_at' => $synced_at->format('Y-m-d H:i:s'),
                ]);
            }
            if ($this->getService()->getPdo()->inTransaction())
            {
                $this->getService()->getPdo()->commit();
            }
        }

        return $products;
    }

    /**
     * @param $page : current page for api paginated calls
     *
     * @return array|false
     *
     * @throws Exception
     */
    public function collectAdditionalRefs($lastCollect, $page = 0)
    {
        // récupération des données SBO via api
        $shippingboRepository = $this->getService()->getShippingboRepository();
        $additionalReferences = $shippingboRepository->getAdditionalRefs($lastCollect, $page);
        if ($additionalReferences)
        {
            $this->nbAddRefsCollected += count($additionalReferences);
        }
        // insertion/update dans la table buffer
        if (!empty($additionalReferences))
        {
            $additionalRefsUpdateStatement = $this->getService()->getBatchRepository()->setBufferStatement();
            if (!$this->getService()->getPdo()->inTransaction())
            {
                $this->getService()->getPdo()->beginTransaction();
            }
            // reformatage des données
            foreach ($additionalReferences as $additionalReference)
            {
                $created_at = new DateTimeImmutable($additionalReference['created_at']);
                $updated_at = new DateTimeImmutable($additionalReference['updated_at']);
                $synced_at = new DateTimeImmutable(null, new DateTimeZone(ShippingboRepository::SERVER_TIMEZONE));
                $additionalReference['created_at'] = $created_at->format('Y-m-d H:i:s');
                $additionalReference['updated_at'] = $updated_at->format('Y-m-d H:i:s');
                $additionalReference['synced_at'] = $synced_at->format('Y-m-d H:i:s');
                $additionalReference['id_sbo_account'] = $this->getService()->getSboAccount()->getId();
                $additionalRefsUpdateStatement->execute($additionalReference);
            }

            if ($this->getService()->getPdo()->inTransaction())
            {
                $this->getService()->getPdo()->commit();
            }
        }

        return $additionalReferences;
    }

    /**
     * TODO : A revoir si nouveau endpoint api (un appel pour chaque produit : lourd).
     *
     * @return array|false
     *
     * @throws Exception
     */
    public function collectPackComponents($lastCollect)
    {
        $packComponentUpdateStatement = $this->getService()->getPackRepository()->setBufferStatement();
        $packComponentGetStatement = $this->getService()->getPdo()->prepare($this->getService()->getPackRepository()->getSboComponentsQuery($lastCollect));
        $packComponentGetStatement->execute([':id_sbo_account' => $this->getService()->getSboAccount()->getId()]);
        $packs = $packComponentGetStatement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($packs as $sboProduct)
        {
            // récupération des données SBO via api
            $productDetail = $this->getService()->getShippingboRepository()->getProduct($sboProduct['id']);
            $created_at = new DateTimeImmutable($productDetail['created_at']);
            $updated_at = new DateTimeImmutable($productDetail['updated_at']);
            if (isset($productDetail['pack_components']))
            {
                // insertion/update dans la table buffer
                $this->nbPackComponentsCollected += count($productDetail['pack_components']);
                foreach ($productDetail['pack_components'] as $packComponent)
                {
                    $synced_at = new DateTimeImmutable(null, new DateTimeZone(ShippingboRepository::SERVER_TIMEZONE));
                    $packComponentUpdateStatement->execute([
                        ':id' => $packComponent['id'],
                        ':id_sbo_account' => $this->getService()->getSboAccount()->getId(),
                        ':quantity' => $packComponent['quantity'],
                        ':pack_product_id' => $packComponent['pack_product_id'],
                        ':component_product_id' => $packComponent['component_product_id'],
                        ':created_at' => $created_at->format('Y-m-d H:i:s'),
                        ':updated_at' => $updated_at->format('Y-m-d H:i:s'),
                        ':synced_at' => $synced_at->format('Y-m-d H:i:s'),
                    ]);
                }
            }
        }

        return $packs;
    }

    /**
     * @desc : launch sync by name
     *
     * @throws Exception
     */
    public function get($type, $lastCollect, $page = 0)
    {
        switch ($type) {
            case 'products':
                return $this->collectProducts(false, $lastCollect, $page);
            case 'packs':
                return $this->collectProducts(true, $lastCollect, $page);
            case 'additional_references':
                return $this->collectAdditionalRefs($lastCollect, $page);
            case 'pack_components':
                return $this->collectPackComponents($lastCollect, $page);
        }

        return [];
    }

    public function getProcessMessageForIteration($iteration, $countProcessed, $method, $methodArguments)
    {
        if ($countProcessed === $this->getBatchSize())
        {
            $totalProcessed = ($iteration + 1) * $this->getBatchSize();
        }
        else
        {
            $totalProcessed = ($iteration) * $this->getBatchSize() + ($iteration * $countProcessed);
        }
        if ($totalProcessed)
        {
            return _l('%s %s collected', 0, [$totalProcessed, str_replace('_', ' ', $methodArguments[0])]);
        }

        return _l('No %s found', false, [$methodArguments[0]]);
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
}
