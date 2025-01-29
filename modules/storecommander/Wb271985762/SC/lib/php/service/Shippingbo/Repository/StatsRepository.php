<?php

namespace Sc\Service\Shippingbo\Repository;

use DbQuery;
use Sc\ScProcess\ScProcessInterface;
use Sc\ScProcess\Traits\ScProcessWithPaginationTrait;
use Sc\Service\Shippingbo\Model\ShopRelationModel;
use Sc\Service\Shippingbo\Repository\Prestashop\BaseRepository;
use Sc\Service\Shippingbo\Shippingbo;

class StatsRepository implements ScProcessInterface
{
    use ScProcessWithPaginationTrait;
    private $shippingboService;

    public function __construct(Shippingbo $shippingboService)
    {
        $this->shippingboService = $shippingboService;
    }

    /**
     * @return Shippingbo
     */
    public function getShippingboService()
    {
        return $this->shippingboService;
    }


    /**
     * @return array|array[]
     * @throws \Exception
     */
    public function getFullStats()
    {
        $sboAccountId = $this->getShippingboService()->getSboAccount()->getId();
        $idShop = $this->getShippingboService()->getIdShop();
        $results = $this->getAll();
        $results += [
            'sbo' => [
                'products' => [
                    'all' => null,
                    'synced' => null,
                ],
                'batches' => [
                    'all' => null,
                    'synced' => null,
                ],
                'packs' => [
                    'all' => null,
                    'synced' => null,
                ],
            ],
            'ps' => [
                'products' => [
                    'all' => null,
                    'synced' => null,
                ],
                'batches' => [
                    'all' => null,
                    'synced' => null,
                ],
                'packs' => [
                    'all' => null,
                    'synced' => null,
                ],
            ]
        ];

        // -----------------------------
        // SBO
        // -----------------------------
        // PRODUCTS
        $time_pre = microtime(true);
        $sboAllProductsQuery = $this->getShippingboService()->getProductRepository()->getAllPsQuery();
        $stmt = $this->getShippingboService()->getPdo()->prepare($sboAllProductsQuery);
        $stmt->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => false,
        ]);
        $results['ps']['products']['all'] = (int) $stmt->rowCount();

        // BATCHES
        // SBO all batches
        $sboAllBatchesQuery = $this->getShippingboService()->getBatchRepository()->getAllPsQuery();
        $stmt = $this->getShippingboService()->getPdo()->prepare($sboAllBatchesQuery);
        $stmt->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => false,
        ]);
        $results['ps']['batches']['all'] = (int) $stmt->rowCount();

        // PACKS
        // SBO all packs count
        $sboAllPacksQuery = $this->getShippingboService()->getPackRepository()->getAllPsQuery();
        $stmt = $this->getShippingboService()->getPdo()->prepare($sboAllPacksQuery);
        $stmt->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => false,
        ]);
        $results['ps']['packs']['all'] = (int) $stmt->rowCount();
        $time_post = microtime(true);
        $this->getShippingboService()->getLogger()->debug('STATS sbo all in '.($time_post - $time_pre).' s');

        // -----------------------------
        // PRESTASHOP
        // -----------------------------
        //PRODUCTS
        $time_pre = microtime(true);
        // PS all products count
        $psNbProductsQuery = $this->getShippingboService()->getProductRepository()->getAllSboQuery();
        $stmt = $this->getShippingboService()->getPdo()->prepare($psNbProductsQuery);
        $stmt->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => false,
        ]);
        $results['sbo']['products']['all'] = $stmt->rowCount();

        // BATCHES
        // PS all batches count
        $psNbBatchesQuery = $this->getShippingboService()->getBatchRepository()->getAllSboQuery();
        $stmt = $this->getShippingboService()->getPdo()->prepare($psNbBatchesQuery);
        $stmt->execute([
            ':id_shop' => $idShop,
            ':is_locked' => false,
        ]);
        $results['sbo']['batches']['all'] = $stmt->rowCount();

        // PACKS
        // PS overall packs count
        $psNbPacksQuery = $this->getShippingboService()->getPackRepository()->getAllSboQuery();
        $stmt = $this->getShippingboService()->getPdo()->prepare($psNbPacksQuery);
        $stmt->execute([
            'id_shop' => $idShop,
            ':is_locked' => false,
        ]);
        $results['sbo']['packs']['all'] = $stmt->rowCount();
        $time_post = microtime(true);
        $this->getShippingboService()->getLogger()->debug('STATS PS all in '.($time_post - $time_pre).' s');


        // SYNCED (PS = SBO)
        $psProductsSyncedQuery = $this->getShippingboService()->getProductRepository()->getSyncedQuery();
        $stmt = $this->getShippingboService()->getPdo()->prepare($psProductsSyncedQuery);
        $stmt->execute([
            ':id_shop' => $idShop
        ]);

        $results['synced']['products'] = $stmt->rowCount();

        $psBacthesSyncedQuery = $this->getShippingboService()->getBatchRepository()->getSyncedQuery();
        $stmt = $this->getShippingboService()->getPdo()->prepare($psBacthesSyncedQuery);
        $stmt->execute([
            ':id_shop' => $idShop
        ]);
        $results['synced']['batches'] = $stmt->rowCount();

        $psPacksSyncedQuery = $this->getShippingboService()->getPackRepository()->getSyncedQuery();
        $stmt = $this->getShippingboService()->getPdo()->prepare($psPacksSyncedQuery);
        $stmt->execute([
            ':id_shop' => $idShop
        ]);
        $results['synced']['packs'] = $stmt->rowCount();

        return $results;
    }

    public function getAll()
    {
        $sboAccountId = $this->getShippingboService()->getSboAccount()->getId();
        $idShop = $this->getShippingboService()->getIdShop();
        $results = [
            'sbo' => [
                'products' => [
                    'missing' => null,
                    'awaiting' => null,
                    'error' => null,
                    'locked' => null,
                ],
                'batches' => [
                    'missing' => null,
                    'awaiting' => null,
                    'error' => null,
                    'locked' => null,
                ],
                'packs' => [
                    'missing' => null,
                    'awaiting' => null,
                    'error' => null,
                    'locked' => null,
                ],
            ],
            'ps' => [
                'products' => [
                    'missing' => null,
                    'awaiting' => null,
                    'error' => null,
                    'locked' => null,
                ],
                'batches' => [
                    'missing' => null,
                    'awaiting' => null,
                    'error' => null,
                    'locked' => null,
                ],
                'packs' => [
                    'missing' => null,
                    'awaiting' => null,
                    'error' => null,
                    'locked' => null,
                ],
            ],
        ];
        $timer = $results;

        // -----------------------------
        // PRESTASHOP
        // -----------------------------
        $timer['ps']['all'] = microtime(true);
        //PRODUCTS
        // prepare base statement
        $psStatsProducts = $this->getShippingboService()->getProductRepository()->getMissingPsQuery();
        $stmtPsBase = $this->getShippingboService()->getPdo()->prepare($psStatsProducts);

        // prepare error statement
        $sboProductsWithErrorsQuery = $this->getShippingboService()->getProductRepository()->addPsErrorParts($psStatsProducts);
        $stmtWithError = $this->getShippingboService()->getPdo()->prepare($sboProductsWithErrorsQuery);

        // PS missing products
        $timer_temp = microtime(true);
        $stmtPsBase->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
        ]);
        $results['ps']['products']['missing'] = $stmtPsBase->rowCount();
        $timer['ps']['products']['missing'] = microtime(true) - $timer_temp;

        // PS locked products
        $timer_temp = microtime(true);
        $stmtPsBase->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_LOCKED,
        ]);
        $results['ps']['products']['locked'] = $stmtPsBase->rowCount();
        $timer['ps']['products']['locked'] = microtime(true) - $timer_temp;

        // PS awaiting products
        $timer_temp = microtime(true);
        $stmtWithError->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
            ':sku_max_length' => \Product::$definition['fields']['reference']['size'],
            ':has_error' => false,
        ]);

        $results['ps']['products']['awaiting'] = $stmtWithError->rowCount();
        $timer['ps']['products']['awaiting'] = microtime(true) - $timer_temp;

        // PS error products
        $timer_temp = microtime(true);
        $stmtWithError->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
            ':sku_max_length' => \Product::$definition['fields']['reference']['size'],
            ':has_error' => true,
        ]);

        $results['ps']['products']['error'] = $stmtWithError->rowCount();
        $timer['ps']['products']['error'] = microtime(true) - $timer_temp;

        // BATCHES
        // prepare base statement
        $psStatsBatches = $this->getShippingboService()->getBatchRepository()->getMissingPsQuery();
        $stmtPsBase = $this->getShippingboService()->getPdo()->prepare($psStatsBatches);
        // prepare error statement
        $sboBatchesWithErrorsQuery = $this->getShippingboService()->getBatchRepository()->addPsErrorParts($psStatsBatches);
        $stmtWithError = $this->getShippingboService()->getPdo()->prepare($sboBatchesWithErrorsQuery);

        // PS missing batches
        $timer_temp = microtime(true);
        $stmtPsBase->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
        ]);

        $results['ps']['batches']['missing'] = $stmtPsBase->rowCount();
        $timer['ps']['batches']['missing'] = microtime(true) - $timer_temp;

        // PS locked batches
        $timer_temp = microtime(true);
        $stmtPsBase->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_LOCKED,
        ]);
        $results['ps']['batches']['locked'] = $stmtPsBase->rowCount();
        $timer['ps']['batches']['locked'] = microtime(true) - $timer_temp;

        // PS awaiting batches
        $timer_temp = microtime(true);
        $stmtWithError->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
            ':sku_max_length' => \Product::$definition['fields']['reference']['size'],
            ':has_error' => false,
        ]);
        $results['ps']['batches']['awaiting'] = $stmtWithError->rowCount();
        $timer['ps']['batches']['awaiting'] = microtime(true) - $timer_temp;

        // PS error batches
        $timer_temp = microtime(true);
        $stmtWithError->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
            ':sku_max_length' => \Product::$definition['fields']['reference']['size'],
            ':has_error' => true,
        ]);
        $results['ps']['batches']['error'] = $stmtWithError->rowCount();
        $timer['ps']['batches']['error'] = microtime(true) - $timer_temp;

        // PACKS
        // prepare base statement
        $psStatsPacks = $this->getShippingboService()->getPackRepository()->getMissingPsQuery();
        $stmtPsBase = $this->getShippingboService()->getPdo()->prepare($psStatsPacks);
        // prepare error statement
        $sboPacksWithErrorsQuery = $this->getShippingboService()->getPackRepository()->addPsErrorParts($psStatsPacks);
        $stmtWithError = $this->getShippingboService()->getPdo()->prepare($sboPacksWithErrorsQuery);

        // PS missing packs
        $timer_temp = microtime(true);
        $stmtPsBase->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
        ]);
        $results['ps']['packs']['missing'] = $stmtPsBase->rowCount();
        $timer['ps']['packs']['missing'] = microtime(true) - $timer_temp;

        // PS locked packs
        $timer_temp = microtime(true);
        $stmtPsBase->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_LOCKED,
        ]);
        $results['ps']['packs']['locked'] = $stmtPsBase->rowCount();
        $timer['ps']['packs']['locked'] = microtime(true) - $timer_temp;

        // PS awaiting packs
        $timer_temp = microtime(true);
        $stmtWithError->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
            ':sku_max_length' => \Product::$definition['fields']['reference']['size'],
            ':has_error' => false,
        ]);
        $results['ps']['packs']['awaiting'] = $stmtWithError->rowCount();
        $timer['ps']['packs']['awaiting'] = microtime(true) - $timer_temp;

        // PS error packs
        $timer_temp = microtime(true);
        $stmtWithError->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
            ':sku_max_length' => \Product::$definition['fields']['reference']['size'],
            ':has_error' => true,
        ]);
        $results['ps']['packs']['error'] = $stmtWithError->rowCount();
        $timer['ps']['packs']['error'] = microtime(true) - $timer_temp;

        $timer['ps']['all'] = microtime(true) - $timer['ps']['all'];

        $this->getShippingboService()->getLogger()->debug('STATS PS -> '.$this->getTimerStatsForLogs($timer, 'ps'));

        // -----------------------------
        // EXPORT SBO
        // -----------------------------
        // PRODUCTS
        // prepare base statement
        $sboStatsProducts = $this->getShippingboService()->getProductRepository()->getMissingSboQuery();

        $stmtSboBase = $this->getShippingboService()->getPdo()->prepare($sboStatsProducts);
        // prepare error statement
        $sboProductsWithErrorsQuery = $this->getShippingboService()->getProductRepository()->addSboErrorParts($sboStatsProducts);
        $stmtWithError = $this->getShippingboService()->getPdo()->prepare($sboProductsWithErrorsQuery);

        $timer['sbo']['all'] = microtime(true);

        // SBO missing products
        $timer_temp = microtime(true);
        $stmtSboBase->execute([
            ':id_shop' => $idShop,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
        ]);
        $results['sbo']['products']['missing'] = $stmtSboBase->rowCount();
        $timer['sbo']['products']['missing'] = microtime(true) - $timer_temp;

        // SBO locked products
        $timer_temp = microtime(true);
        $stmtSboBase->execute([
            ':id_shop' => $idShop,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_LOCKED,
        ]);
        $results['sbo']['products']['locked'] = $stmtSboBase->rowCount();
        $timer['sbo']['products']['locked'] = microtime(true) - $timer_temp;

        // SBO awaiting products
        $timer_temp = microtime(true);
        $stmtWithError->execute([
            ':id_shop' => $idShop,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
            ':has_error' => false,
        ]);
        $results['sbo']['products']['awaiting'] = $stmtWithError->rowCount();
        $timer['sbo']['products']['awaiting'] = microtime(true) - $timer_temp;

        // SBO error products
        $timer_temp = microtime(true);
        $stmtWithError->execute([
            ':id_shop' => $idShop,
            ':has_error' => true,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
        ]);
        $results['sbo']['products']['error'] = $stmtWithError->rowCount();
        $timer['sbo']['products']['error'] = microtime(true) - $timer_temp;

        // BATCHES
        // prepare base statement
        $sboStatsBatches = $this->getShippingboService()->getBatchRepository()->getMissingSboQuery();
        $stmtSboBase = $this->getShippingboService()->getPdo()->prepare($sboStatsBatches);
        // prepare error statement
        $sboBatchesWithErrorsQuery = $this->getShippingboService()->getBatchRepository()->addSboErrorParts($sboStatsBatches);
        $stmtWithError = $this->getShippingboService()->getPdo()->prepare($sboBatchesWithErrorsQuery);

        // SBO missing batches
        $timer_temp = microtime(true);
        $stmtSboBase->execute([
            ':id_shop' => $idShop,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
        ]);
        $results['sbo']['batches']['missing'] = $stmtSboBase->rowCount();
        $timer['sbo']['batches']['missing'] = microtime(true) - $timer_temp;

        // SBO locked batches
        $timer_temp = microtime(true);
        $stmtSboBase->execute([
            ':id_shop' => $idShop,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_LOCKED,
        ]);
        $results['sbo']['batches']['locked'] = $stmtSboBase->rowCount();
        $timer['sbo']['batches']['locked'] = microtime(true) - $timer_temp;

        // SBO awaiting batches
        $timer_temp = microtime(true);
        $stmtWithError->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
            ':has_error' => false,
        ]);
        $results['sbo']['batches']['awaiting'] = $stmtWithError->rowCount();
        $timer['sbo']['batches']['awaiting'] = microtime(true) - $timer_temp;

        // SBO error batches
        $timer_temp = microtime(true);
        $stmtWithError->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
            ':has_error' => true,
        ]);

        $results['sbo']['batches']['error'] = $stmtWithError->rowCount();
        $timer['sbo']['batches']['error'] = microtime(true) - $timer_temp;

        // PACKS
        // prepare base statement
        $sboStatsPacks = $this->getShippingboService()->getPackRepository()->getMissingSboQuery();
        $stmtSboBase = $this->getShippingboService()->getPdo()->prepare($sboStatsPacks);
        // prepare error statement
        $sboPacksWithErrorsQuery = $this->getShippingboService()->getPackRepository()->addSboErrorParts($sboStatsPacks);
        $stmtWithError = $this->getShippingboService()->getPdo()->prepare($sboPacksWithErrorsQuery);
        $params = [
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
        ];

        // SBO missing packs
        $timer_temp = microtime(true);
        $stmtSboBase->execute($params);
        $results['sbo']['packs']['missing'] = $stmtSboBase->rowCount();
        $timer['sbo']['packs']['missing'] = microtime(true) - $timer_temp;

        // SBO locked packs
        $timer_temp = microtime(true);
        $stmtSboBase->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_LOCKED,
        ]);
        $results['sbo']['packs']['locked'] = $stmtSboBase->rowCount();
        $timer['sbo']['packs']['locked'] = microtime(true) - $timer_temp;

        // SBO awaiting packs
        $timer_temp = microtime(true);
        $stmtWithError->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
            ':has_error' => false,
        ]);
        $results['sbo']['packs']['awaiting'] = $stmtWithError->rowCount();
        $timer['sbo']['packs']['awaiting'] = microtime(true) - $timer_temp;

        // SBO error packs$time_pre = microtime(true);
        $timer_temp = microtime(true);
        $stmtWithError->execute([
            ':id_shop' => $idShop,
            ':id_sbo_account' => $sboAccountId,
            ':is_locked' => Shippingbo::SBO_PRODUCT_IS_UNLOCKED,
            ':has_error' => true,
        ]);
        $results['sbo']['packs']['error'] = $stmtWithError->rowCount();
        $timer['sbo']['packs']['error'] = microtime(true) - $timer_temp;

        $timer['sbo']['all'] = microtime(true) - $timer['sbo']['all'];

        $this->getShippingboService()->getLogger()->debug('STATS sbo -> '.$this->getTimerStatsForLogs($timer, 'sbo'));

        return $results;
    }

    private function getTimerStatsForLogs(array $timer, $platform)
    {
        $overAllDuration = $timer[$platform]['all'];

        $output = 'Total duration :'.$overAllDuration.'s';
        if ((float) $overAllDuration < 1)
        {
            return $output;
        }
        $output .= "\r\n";
        foreach ($timer[$platform] as $entityType => $values)
        {
            if ($entityType != 'all')
            {
                $entityDuration = 0;
                $statusDurations = [];
                foreach ($values as $status => $duration)
                {
                    $entityDuration += (float) $duration;
                    $statusDurations[] = "\t\t* ".$status.' -> '.$duration.' s';
                }
                $statusDurationText = implode("\r\n", $statusDurations);
                $output .= "\t* ".$entityType.': '.round(($entityDuration / $overAllDuration) * 100).'% ('.$entityDuration.' s)';
                $output .= "\r\n".$statusDurationText;
                $output .= "\r\n";
            }
        }
        $output .= "\r\n";

        return $output;
    }

    public function getProcessMessageForIteration($iteration, $countProcessed, $method, $methodArguments)
    {
        return _l('Generating stats');
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
