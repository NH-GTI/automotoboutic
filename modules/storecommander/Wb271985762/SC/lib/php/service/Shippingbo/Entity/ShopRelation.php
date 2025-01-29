<?php

namespace Sc\Service\Shippingbo\Entity;

use Db;
use Pack;
use Sc\Service\Lib\Interfaces\HydratableObjectAwareInterface;
use Sc\Service\Lib\Traits\EntityHydratableTrait;
use Sc\Service\Shippingbo\Model\PackComponentModel;
use Sc\Service\Shippingbo\Repository\ShopRelationRepository;
use Sc\Service\Shippingbo\Shippingbo;

class ShopRelation implements HydratableObjectAwareInterface
{
    use EntityHydratableTrait;
    private $id;
    private $idStorecomServiceShippingboShopRelation;
    private $idSbo;
    private $idProduct;
    private $idProductAttribute;
    private $idShop;
    private $reference;
    private $idSboSource;
    private $typeSbo;
    private $isLocked;
    private $createdAt;
    private $updatedAt;

    private $lockChanged = false;
    /**
     * @var Shippingbo
     */
    private $service;

    /**
     * @throws \Exception
     */
    public function __construct($id = null, Shippingbo $service)
    {
        $this->setService($service);
        if ($id)
        {
            $this->hydrateObject($id, 'id_storecom_service_shippingbo_shop_relation');
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdProduct()
    {
        return $this->idProduct;
    }

    /**
     * @param mixed $idProduct
     */
    public function setIdProduct($idProduct)
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdProductAttribute()
    {
        return $this->idProductAttribute;
    }

    /**
     * @param mixed $idProductAttribute
     */
    public function setIdProductAttribute($idProductAttribute)
    {
        $this->idProductAttribute = $idProductAttribute;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdShop()
    {
        return $this->idShop;
    }

    /**
     * @param mixed $idShop
     */
    public function setIdShop($idShop)
    {
        $this->idShop = $idShop;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdSboSource()
    {
        return $this->idSboSource;
    }

    /**
     * @param mixed $idSboSource
     */
    public function setIdSboSource($idSboSource)
    {
        $this->idSboSource = $idSboSource;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTypeSbo()
    {
        return $this->typeSbo;
    }

    /**
     * @param mixed $typeSbo
     */
    public function setTypeSbo($typeSbo)
    {
        $this->typeSbo = $typeSbo;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsLocked()
    {
        return (bool) $this->isLocked;
    }

    /**
     * @param mixed $isLocked
     */
    public function setIsLocked($isLocked)
    {
        $this->isLocked = $isLocked;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdSbo()
    {
        return $this->idSbo;
    }

    /**
     * @param mixed $idSbo
     */
    public function setIdSbo($idSbo)
    {
        $this->idSbo = $idSbo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->idStorecomServiceShippingboShopRelation;
    }

    /**
     * @param mixed $id
     *
     * @return ShopRelation
     */
    public function setId($id)
    {
        $this->idStorecomServiceShippingboShopRelation = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return bool
     */
    public function save()
    {
        $params = [
            'id_storecom_service_shippingbo_shop_relation' => $this->getId(),
            'id_sbo' => $this->getIdSbo(),
            'id_product' => $this->getIdProduct(),
            'id_product_attribute' => $this->getIdProductAttribute(),
            'id_shop' => $this->getIdShop(),
            'reference' => $this->getReference(),
            'id_sbo_source' => $this->getIdSboSource(),
            'type_sbo' => $this->getTypeSbo(),
            'is_locked' => $this->getIsLocked(),
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $query = $this->getRepository()->getUpdateQuery();
        if (!$this->getId())
        {
            unset($params['id_storecom_service_shippingbo_shop_relation']);
            $query = $this->getRepository()->getInsertQuery();
        }
        $stmt = Db::getInstance()->getLink()->prepare($query);

        return $stmt->execute($params);
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $context
     */
    private function setService($context)
    {
        $this->service = $context;

        return $this;
    }

    private function updateProduct(array $params)
    {
        $stmt = Db::getInstance()->getLink()->prepare((new ShopRelationRepository($this->getService()))->getUpdateQuery());
        $stmt->execute($params);
    }

    private function updateFromPsPackItems(array $params)
    {
        $pack = new Pack((int) $params['id_product']);
        if (!empty($pack->getItems($params['id_product'], $this->getService()->getScAgent()->getIdLang())))
        {
            $packItemsIdProducts = array_column(
                $pack->getItems($params['id_product'], $this->getService()->getScAgent()->getIdLang()),
                'id'
            );
            $updatePackComponentsLock = Db::getInstance()->getLink()->prepare('UPDATE '._DB_PREFIX_.(new ShopRelationRepository($this->getService()))->getSboRelationTable().' SET is_locked=:is_locked WHERE id_product IN (:id_product) AND id_shop = :id_shop');
            $updatePackComponentsLock->execute([
                ':is_locked' => (bool) $params['is_locked'],
                ':id_product' => implode(',', $packItemsIdProducts),
                ':id_shop' => $this->getService()->getIdShop(),
            ]);
        }
    }

    private function updateFromSboBatchBuffer(array $params)
    {
        $updateBatchesContainingProduct = Db::getInstance()->getLink()->prepare('UPDATE '._DB_PREFIX_.(new ShopRelationRepository($this->getService()))->getSboRelationTable().' SET is_locked=:is_locked WHERE id_sbo_source = :id_sbo_source AND id_shop = :id_shop');
        $updateBatchesContainingProduct->execute([
            ':is_locked' => (bool) $params['is_locked'],
            ':id_sbo_source' => $this->getIdSbo(),
            ':id_shop' => $this->getService()->getIdShop(),
        ]);
    }

    private function updateFromSboPackComponentBuffer(array $params)
    {
        $updatePacksContainingProduct = Db::getInstance()->getLink()->prepare('UPDATE '._DB_PREFIX_.(new ShopRelationRepository($this->getService()))->getSboRelationTable().' ps_relation LEFT JOIN '._DB_PREFIX_.(new PackComponentModel())->getTableName().' pc ON pc.pack_product_id = ps_relation.id_sbo AND pc.id_sbo_account=:id_sbo_account SET ps_relation.is_locked=:is_locked WHERE pc.component_product_id = :id_sbo_source AND id_shop = :id_shop');
        $updatePacksContainingProduct->execute([
            ':is_locked' => (bool) $params['is_locked'],
            ':id_sbo_source' => $this->getIdSbo(),
            ':id_sbo_account' => $this->getService()->getSboAccount()->getId(),
            ':id_shop' => $this->getService()->getIdShop(),
        ]);
    }

    public function getRepository()
    {
        return new ShopRelationRepository($this->getService());
    }
}
