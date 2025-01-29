<?php

namespace Sc\Service\Shippingbo\Process;

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use PDO;
use Product;
use Sc\ScProcess\ScProcessInterface;
use Sc\ScProcess\Traits\ScProcessWithPaginationTrait;
use Sc\Service\Shippingbo\Model\ShopRelationModel as SboShopRelationModel;
use Sc\Service\Shippingbo\Repository\ShippingboRepository;
use Sc\Service\Shippingbo\Shippingbo;

class MatchData implements ScProcessInterface
{
    use ScProcessWithPaginationTrait;
    /**
     * @var Shippingbo
     */
    private $service;

    public function __construct(Shippingbo $service)
    {
        $this->service = $service;
    }

    /**
     * @throws Exception
     */
    public function start()
    {
        // remove relations with no corresponding product/combination in PS table
        $this->removeDeletedProductsFromPs();
        $this->removeUnLinked();
        $this->removeDuplicates();
        $this->addNewProductsFromPs();
        $this->mergeSboData(); // if relation exists and sku changed in SBO

        return $this;
    }

    /**
     * @descr récupère les produits présents dans PS non présents dans la table relation
     *  * les produits de type standard
     *  * les produits de type combinations
     *  * les produits de type pack :
     *    * si plus d'un produit source dans la composition du pack → pack
     *    * si un seul produit source dans la composition du pack et quantités > 1 → batch
     *    * si un seul produit source dans la composition du pack et quantités = 1 → additional_reference
     *
     * @throws Exception
     */
    public function addNewProductsFromPs()
    {
        $shopRelationAddStmt = $this->getService()->getShopRelationRepository()->getAddStatement();
        $now = new DateTimeImmutable(null, new DateTimeZone(ShippingboRepository::SERVER_TIMEZONE));

        if (!$this->getService()->getPdo()->inTransaction())
        {
            $this->getService()->getPdo()->beginTransaction();
        }

        foreach ($this->getPsProductsToProceedInRelation() as $relation)
        {
            $lockedStatus = isset($relation['is_locked']) ? $relation['is_locked'] : false;
            if ($relation['action'] === 'create')
            {
                $lockedStatus = (isset($relation['active']) && !isset($relation['id_sbo'])) && !(bool) $relation['active'] ?: $lockedStatus;
            }
            $params = [
                'id_sbo' => $relation['id_sbo'],
                'id_shop' => $this->getService()->getIdShop(),
                'id_product' => $relation['id_product'],
                'id_product_attribute' => $relation['id_product'] ? $relation['id_product_attribute'] ?: 0 : null,
                'reference' => $relation['reference'],
                'id_sbo_source' => isset($relation['id_sbo_source']) ? $relation['id_sbo_source'] : null,
                'is_locked' => $lockedStatus,
                'type_sbo' => $relation['type_sbo'],
                'created_at' => $now->format('Y-m-d H:i:s'),
                'updated_at' => $now->format('Y-m-d H:i:s'),
            ];
            $shopRelationAddStmt->execute($params);
        }

        if ($this->getService()->getPdo()->inTransaction())
        {
            $this->getService()->getPdo()->commit();
        }
    }

    /**
     * @return Shippingbo
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @desc remove relation if product removed
     *
     * @return void
     */
    public function removeDeletedProductsFromPs()
    {
        $relationsToRemoveStmt = $this->getService()->getPdo()->prepare($this->getService()->getShopRelationRepository()->entriesToRemoveQuery());
        $relationsToRemoveStmt->execute([':id_shop' => $this->getService()->getIdShop()]);

        $relationsToRemove = $relationsToRemoveStmt->fetchAll(PDO::FETCH_ASSOC);
        // suppression de l'entrée dans la table relation
        $removeEntriesForShopStmt = $this->getService()->getShopRelationRepository()->deleteShopRelationStatement();
        // suppression des informations PS de l'entrée dans la table relation
        $unlinkEntriesForShopStmt = $this->getService()->getShopRelationRepository()->unlinkShopRelationStatement();

        if (!$this->getService()->getPdo()->inTransaction())
        {
            $this->getService()->getPdo()->beginTransaction();
        }
        foreach ($relationsToRemove as $relationToRemove)
        {
            $stmt = $removeEntriesForShopStmt;
            // si on a un id_sbo, alors on ne supprime que les infos id_cproduct et id_product_attribute de la table
            if ($relationToRemove['id_sbo'])
            {
                $stmt = $unlinkEntriesForShopStmt;
            }
            $stmt->execute([
                'id' => $relationToRemove[(new SboShopRelationModel())->getPrimaryKey()],
                'id_shop' => $this->getService()->getIdShop(),
            ]);
        }
        if ($this->getService()->getPdo()->inTransaction())
        {
            $this->getService()->getPdo()->commit();
        }
    }

    public function getProcessMessageForIteration($iteration, $countProcessed, $method, $methodArguments)
    {
        $hasSomeMessage = $hasNoneMessage = _l('Matching products');
        $totalProcessed = ($iteration + 1) * $countProcessed;
        switch ($method){
            case 'removeDeletedPsProductsFromRelations':
                $hasSomeMessage = _l('%s relation(s) removed', 0, [$totalProcessed]);
                $hasNoneMessage = _l('No relations to remove', 0, [$totalProcessed]);
                break;
            case 'addAllMissingToRelations':
                $hasSomeMessage = _l('%s relation(s) added', 0, [$totalProcessed]);
                $hasNoneMessage = _l('No relations to add', 0, [$totalProcessed]);
                break;
            case 'updateSKU':
                $hasSomeMessage = _l('%s match(es) found', 0, [$totalProcessed]);
                $hasNoneMessage = _l('No match found', 0, [$totalProcessed]);
                break;
        }
        if ($totalProcessed)
        {
            return $hasSomeMessage;
        }

        return $hasNoneMessage;
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

    public function getSboAddrefsAndBatchesToProceedInRelation()
    {
        $stmt = $this->getService()->getPdo()->prepare($this->getService()->getShopRelationRepository()->buildAddrefBufferMissingMatchQuery());
        $stmt->execute([
            'id_sbo_account' => $this->getService()->getConfigValue('id_sbo_account'),
            'id_shop' => $this->getService()->getIdShop(),
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSboProductsAndPacksToProceedInRelation()
    {
        $stmt = $this->getService()->getPdo()->prepare($this->getService()->getShopRelationRepository()->buildProductBufferMissingMatchQuery());
        $stmt->execute([
            'id_sbo_account' => $this->getService()->getConfigValue('id_sbo_account'),
            'id_shop' => $this->getService()->getIdShop(),
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array|false
     */
    public function getPsProductsToProceedInRelation()
    {
        $stmt = $this->getService()->getPdo()->prepare($this->getService()->getShopRelationRepository()->getMissingFromShopProductsQuery());
        $stmt->execute([
            'id_shop' => $this->getService()->getIdShop(),
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @throws \PrestaShopException
     * @throws \PrestaShopDatabaseException
     * @throws \DateMalformedStringException
     */
    private function mergeSboData()
    {
        $id_shop = $this->getService()->getIdShop();
        $shopRelationAddStmt = $this->getService()->getShopRelationRepository()->getAddStatement();
        $shopRelationUpdateStmt = $this->getService()->getShopRelationRepository()->getUpdateStatement();
        $now = new DateTimeImmutable(null, new DateTimeZone(ShippingboRepository::SERVER_TIMEZONE));

        $sboProductsAndPacksMissing = $this->getSboProductsAndPacksToProceedInRelation();
        $sboAddrefsAndBatchesMissing = $this->getSboAddrefsAndBatchesToProceedInRelation();
        $SboAllMissing = array_merge($sboProductsAndPacksMissing, $sboAddrefsAndBatchesMissing);

        if (!$this->getService()->getPdo()->inTransaction())
        {
            $this->getService()->getPdo()->beginTransaction();
        }

        foreach ($SboAllMissing as $relation)
        {
            // aucune action si reference dupliquée coté PS
            if ((bool) $relation['duplicate_target_ref'])
            {
                continue;
            }

            // locked status
            $lockedStatus = $relation['is_locked'] ?$relation['is_locked']: false; //par defaut false
            if ($relation['action'] === 'insert')
            {
                $lockedStatus = (!isset($relation['id_sbo']) && isset($relation['active']) && !(bool) $relation['active']) ?: $lockedStatus;
            }
            $id_product_attribute = $relation['id_product_attribute'] ? (int) $relation['id_product_attribute'] : null;
            if (!$id_product_attribute && $relation['id_product'])
            {
                $id_product_attribute = 0;
            }

            $params = [
                'id_sbo' => $relation['id_sbo'] ? (int) $relation['id_sbo'] : null,
                'id_shop' => $this->getService()->getIdShop(),
                'id_product' => $relation['id_product'] ? (int) $relation['id_product'] : null,
                'id_product_attribute' => $id_product_attribute,
                'reference' => $relation['reference'] ?: null,
                'id_sbo_source' => $relation['id_sbo_source'] ? (int) $relation['id_sbo_source'] : null,
                'is_locked' => $lockedStatus,
                'type_sbo' => $relation['type_sbo'],
                'created_at' => $now->format('Y-m-d H:i:s'),
                'updated_at' => $now->format('Y-m-d H:i:s'),
            ];

            switch ($relation['action']){
                case 'insert':
                    $shopRelationAddStmt->execute($params);
                    break;
                case 'update':
                    $params['id_storecom_service_shippingbo_shop_relation'] = (int) $relation['relation_id'];
                    unset($params['created_at']);
                    $shopRelationUpdateStmt->execute($params);
                    $this->doUpdatePsRef($relation, $id_shop);
                    break;
            }
        }

        if ($this->getService()->getPdo()->inTransaction())
        {
            $this->getService()->getPdo()->commit();
        }
    }

    /**
     * @$relationparam $relation
     *
     * @return void
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function doUpdatePsRef($relation, int $id_shop)
    {
        if ($relation['reference'] === $relation['old_reference'])
        {
            return;
        }

        if ((int) $relation['id_product_attribute'] > 0)
        {
            $combination = new \Combination($relation['id_product_attribute'], null, $id_shop);
            if ($combination->id)
            {
                $combination->reference = $relation['reference'];
                $combination->update();
            }
        }
        elseif ((int) $relation['id_product'])
        {
            $product = new Product($relation['id_product'], null, false, $id_shop);
            if ($product->id)
            {
                $product->reference = $relation['reference'];
                if (!$product->price)
                {
                    $product->price = 0;
                }
                $product->update();
            }
        }
    }

    private function removeDuplicates()
    {
        $this->getService()->getShopRelationRepository()->removeDuplicates();
    }

    private function removeUnLinked()
    {
        $this->getService()->getShopRelationRepository()->removeUnLinked();
    }
}
