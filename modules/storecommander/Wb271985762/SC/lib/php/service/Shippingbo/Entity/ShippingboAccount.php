<?php

namespace Sc\Service\Shippingbo\Entity;

use Db;
use DbQuery;
use Exception;
use PDO;
use Sc\Service\Lib\Interfaces\HydratableObjectAwareInterface;
use Sc\Service\Lib\Traits\EntityHydratableTrait;
use Sc\Service\Model\ServiceConfigurationModel;
use Sc\Service\Model\ServiceModel;
use Sc\Service\Shippingbo\Repository\ShippingboAccountRepository;
use Sc\Service\Shippingbo\Repository\ShippingboRepository;
use Sc\Service\Shippingbo\Shippingbo;
use SCI;

class ShippingboAccount implements HydratableObjectAwareInterface
{
    use EntityHydratableTrait;
    private $idAccount = null;
    public $name = null;
    public $apiUrl = 'https://app.shippingbo.com';
    public $apiUser = null;
    public $apiToken = null;
    public $apiVersion = '1';
    public $createdAt = null;
    public $updatedAt = null;
    /**
     * @var array|false
     */
    private $shopIds;

    public function __construct($id = null)
    {
        if ($id)
        {
            $this->hydrateObject($id);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        if (!$this->name)
        {
            $this->setName('Sbo Account'.($this->getId() ? ' #'.$this->getId() : ' '));
        }

        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @param mixed $apiUrl
     */
    public function setApiUrl($apiUrl)
    {
        $apiUrl = $apiUrl ?: Shippingbo::LINK_PRODUCT_URL;
        $this->apiUrl = $apiUrl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiUser()
    {
        return $this->apiUser;
    }

    /**
     * @param mixed $apiUser
     */
    public function setApiUser($apiUser)
    {
        $this->apiUser = $apiUser;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiToken()
    {
        if (!$this->apiToken)
        {
            return '';
        }

        return SCI::decrypt($this->apiToken);
    }

    /**
     * @param mixed $apiToken
     */
    public function setApiToken($apiToken)
    {
        $this->apiToken = SCI::encrypt($apiToken);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @param mixed $apiVersion
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion ?: 1;

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
    public function getId()
    {
        return (int) $this->idAccount;
    }

    /**
     * @param mixed $id_account
     */
    public function setId($id_account)
    {
        $this->idAccount = $id_account;
    }

    public function save()
    {
        $params = [
            'id_account' => $this->getId(),
            'name' => $this->getName(),
            'apiUrl' => $this->getApiUrl(),
            'apiUser' => $this->getApiUser(),
            'apiToken' => $this->apiToken,
            'apiVersion' => $this->getApiVersion(),
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $stmt = Db::getInstance()->getLink()->prepare($this->getRepository()->getUpdateQuery());
        $sboAccountSavec = $stmt->execute($params);
        if (!$this->getId())
        {
            $this->setId(Db::getInstance()->getLink()->lastInsertId());
        }

        return $sboAccountSavec;
    }

    public function delete()
    {
        $params = [
            'id_account' => $this->getId(),
        ];
        $stmt = Db::getInstance()->getLink()->prepare($this->getRepository()->getDeleteQuery());

        return $stmt->execute($params);
    }

    public function check()
    {
        if (!($this->getApiUrl() && $this->getApiUser() && $this->getApiToken() && $this->getApiVersion()))
        {
            throw new Exception('missing Shippingbo account informations');
        }

        return true;
    }

    public function getShopIds()
    {
        if (!$this->shopIds)
        {
            $configQuery = new DbQuery();
            $configQuery->select('config.id_shop')
                ->from((new ServiceModel())->getTableName(), 'service')
                ->innerJoin((new ServiceConfigurationModel())->getTableName(), 'config', 'config.id_service = service.id_service AND service.name = :service_name')
                ->where('config.name=:config_name')
                ->where('config.value=:id_sbo_account')
            ;
            $stmtService = Db::getInstance()->getLink()->prepare($configQuery);
            $stmtService->execute([
                ':service_name' => Shippingbo::SERVICE_NAME,
                ':config_name' => 'id_sbo_account',
                ':id_sbo_account' => $this->getId(),
            ]);
            $this->shopIds = array_column($stmtService->fetchAll(PDO::FETCH_ASSOC), 'id_shop');
        }

        return array_map('intval', $this->shopIds);
    }

    public function getRepository()
    {
        return new ShippingboAccountRepository();
    }

    /**
     * @throws Exception
     */
    public function valid()
    {
        $shippingboRepository = new ShippingboRepository($this);

        return $shippingboRepository->healthCheck();
    }
}
