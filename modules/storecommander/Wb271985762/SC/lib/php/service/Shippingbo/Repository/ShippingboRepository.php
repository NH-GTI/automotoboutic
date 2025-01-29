<?php

namespace Sc\Service\Shippingbo\Repository;

use DateTimeImmutable;
use DateTimeZone;
use Db;
use DbQuery;
use Exception;
use Sc\ScLogger\ScLogger;
use Sc\ScLogger\Traits\ScLoggerTrait;
use Sc\ScProcess\Traits\ScProcessWithPaginationTrait;
use Sc\Service\ServiceInterface;
use Sc\Service\Shippingbo\Entity\ShippingboAccount;
use Sc\Service\Shippingbo\Model\AdditionalRefsModel;
use Sc\Service\Shippingbo\Model\PackComponentModel;
use Sc\Service\Shippingbo\Model\ProductModel as SboProductModel;
use SCI;

/**
 *
 */
class ShippingboRepository implements ShippingboRepositoryInterface
{
    use ScProcessWithPaginationTrait;
    use ScLoggerTrait;

    const SERVER_TIMEZONE = 'UTC';
    const PROPAGATION_THRESHOLD = '30sec';

    /**
     * @var array|mixed[]
     */
    protected $config;
    /**
     * @var ServiceInterface
     */
    private $sboAccount;
    /**
     * @var DateTimeImmutable|false
     */
    private $lastProductCollectDate;
    /**
     * @var DateTimeImmutable|false
     */
    private $lastPackCollectDate;
    /**
     * @var DateTimeImmutable|false
     */
    private $lastAddRefCollectDate;
    private $lastPackComponentCollectDate;
    private $service;

    public function __construct(ShippingboAccount $sboAccount, ScLogger $logger = null)
    {
        $this->setSboAccount($sboAccount);
        if ($logger)
        {
            $this->setLogger($logger);
        }
    }

    public function getTimeZone()
    {
        return self::SERVER_TIMEZONE;
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function call($endpoint, $method = 'GET', $params = [], $timeout = 5)
    {
        $sboAccount = $this->getSboAccount();
        $headers = [
            'Content-Type: application/json',
            'X-API-USER: '.$sboAccount->getApiUser(),
            'X-API-TOKEN: '.$sboAccount->getApiToken(),
            'X-API-VERSION: '.$sboAccount->getApiVersion(),
        ];
        $url = $sboAccount->getApiUrl().$endpoint;

        return $this->get($url, $method, $params, $headers, $timeout);
    }

    /**
     * @return DateTimeImmutable|false
     *
     * @throws Exception
     */
    public function getLastProductCollectDate()
    {
        if (!$this->lastProductCollectDate)
        {
            $dbQuery = (new DbQuery())
                ->select('MAX(synced_at)')
                ->from((new SboProductModel())->getTableName())
                ->where('is_pack = :is_pack')
                ->where('id_sbo_account = :id_sbo_account')
                ->limit(1)
            ;
            $stmt = Db::getInstance()->getLink()->prepare($dbQuery);
            $stmt->execute([':is_pack' => false, ':id_sbo_account' => $this->getSboAccount()->getId()]);
            $lastSync = $stmt->fetchColumn();
            if (!$lastSync)
            {
                return null;
            }
            $this->setLastProductCollectDate(DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $lastSync, new DateTimeZone(SCI::getConfigurationValue('PS_TIMEZONE'))));
        }

        return $this->lastProductCollectDate;
    }

    public function getLastPackCollectDate()
    {
        if (!$this->lastPackCollectDate)
        {
            $dbQuery = (new DbQuery())
                ->select('MAX(synced_at)')
                ->from((new SboProductModel())->getTableName())
                ->where('is_pack = :is_pack')
                ->where('id_sbo_account = :id_sbo_account')
                ->limit(1)
            ;
            $stmt = Db::getInstance()->getLink()->prepare($dbQuery);
            $stmt->execute([':is_pack' => true, ':id_sbo_account' => $this->getSboAccount()->getId()]);
            $lastSync = $stmt->fetchColumn();
            if (!$lastSync)
            {
                return null;
            }
            $this->setLastPackCollectDate(DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $lastSync, new DateTimeZone(SCI::getConfigurationValue('PS_TIMEZONE'))));
        }

        return $this->lastPackCollectDate;
    }

    /**
     * @return DateTimeImmutable|false
     *
     * @throws Exception
     */
    public function getLastAddRefCollectDate()
    {
        if (!$this->lastAddRefCollectDate)
        {
            $dbQuery = (new DbQuery())
                ->select('MAX(synced_at)')
                ->from((new AdditionalRefsModel())->getTableName())
                ->where('id_sbo_account = :id_sbo_account')
                ->limit(1);
            $stmt = Db::getInstance()->getLink()->prepare($dbQuery);
            $stmt->execute([':id_sbo_account' => $this->getSboAccount()->getId()]);
            $lastSync = $stmt->fetchColumn();
            if (!$lastSync)
            {
                return null;
            }
            $this->setLastAddRefCollectDate(DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $lastSync, new DateTimeZone(SCI::getConfigurationValue('PS_TIMEZONE'))));
        }

        return $this->lastAddRefCollectDate;
    }

    /**
     * @return DateTimeImmutable|false
     *
     * @throws Exception
     */
    public function getLastPackComponentCollectDate()
    {
        if (!$this->lastPackComponentCollectDate)
        {
            $dbQuery = (new DbQuery())
                ->select('LEAST(MAX(p.synced_at),MAX(pc.synced_at))')
                ->from((new SboProductModel())->getTableName(), 'p')
                ->leftJoin((new PackComponentModel())->getTableName(), 'pc', 'pc.pack_product_id = p.id')
                ->where('pc.id_sbo_account = :id_sbo_account')
                ->limit(1)
            ;
            $stmt = Db::getInstance()->getLink()->prepare($dbQuery);
            $stmt->execute([':id_sbo_account' => $this->getSboAccount()->getId()]);
            $lastSync = $stmt->fetchColumn();
            if (!$lastSync)
            {
                return null;
            }
            $this->setLastPackComponentCollectDate(DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $lastSync, new DateTimeZone(SCI::getConfigurationValue('PS_TIMEZONE'))));
        }

        return $this->lastPackComponentCollectDate;
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function getProduct($id_product)
    {
        return $this->call('/products/'.$id_product);
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function getProducts($isPack = false, $lastCollect = false, $page = 0)
    {
        $params = [
            'is_pack' => $isPack ? 'true' : 'false',
            'offset' => $page * $this->getBatchSize(),
        ];
        if ($lastCollect)
        {
            $params['search']['updated_at__gt'] = $lastCollect->modify('-'.self::PROPAGATION_THRESHOLD)->format('c');
        }

        return $this->call('/products', 'GET', $params);
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function getAdditionalRefs($lastCollect = false, $page = 0)
    {
        $params = [
            'offset' => $page * $this->getBatchSize(),
        ];
        if ($lastCollect)
        {
            $params['search']['updated_at__gt'] = $lastCollect->format('c');
        }

        return $this->call('/order_item_product_mappings', 'GET', $params);
    }

    /**
     * @return ServiceInterface|ShippingboAccount
     */
    public function getSboAccount()
    {
        return $this->sboAccount;
    }

    /**
     * @param ServiceInterface|ShippingboAccount $sboAccount
     *
     * @return ShippingboRepository
     */
    public function setSboAccount($sboAccount)
    {
        $this->sboAccount = $sboAccount;

        return $this;
    }

    /**
     * @param DateTimeImmutable|false $lastProductCollectDate
     */
    public function setLastProductCollectDate($lastProductCollectDate)
    {
        $this->lastProductCollectDate = $lastProductCollectDate;

        return $this;
    }

    /**
     * @param DateTimeImmutable|false $lastPackCollectDate
     */
    public function setLastPackCollectDate($lastPackCollectDate)
    {
        $this->lastPackCollectDate = $lastPackCollectDate;

        return $this;
    }

    /**
     * @param DateTimeImmutable|false $lastAddRefCollectDate
     */
    public function setLastAddRefCollectDate($lastAddRefCollectDate)
    {
        $this->lastAddRefCollectDate = $lastAddRefCollectDate;

        return $this;
    }

    /**
     * @param mixed $lastPackComponentCollectDate
     */
    public function setLastPackComponentCollectDate($lastPackComponentCollectDate)
    {
        $this->lastPackComponentCollectDate = $lastPackComponentCollectDate;

        return $this;
    }

    /**
     * @return false|mixed
     *
     * @throws \Exception
     */
    private function get($url, $method, $params = [], $headers = [], $timeout = 3)
    {
        $params['limit'] = isset($params['limit']) ? $params['limit'] : $this->getBatchSize();
        $params['offset'] = isset($params['offset']) ? $params['offset'] : 0;
        $this->getLogger()->debug('['.$method.'] '.$url.' '.json_encode($params, true));
        $entries = json_decode(sc_file_get_contents($url, $method, $params, $headers, $timeout), true);
        if (!empty($entries))
        {
            $entries = reset($entries);
        }

        if (isset($entries['message']) && $entries['message'] !== '404 NOT FOUND')
        {
            $message = _l('Shippingbo API Error : %s. Please verify API configuration.', null, [$entries['message']]);
            if ($entries['message'] === '403 FORBIDDEN')
            {
                $message = _l('Authentication problem: please check Shippingbo connection settings.<br/> If the problem persists, please contact the Shippingbo team.', false);
            }
            throw new \Exception($message);
        }

        return $entries;
    }

    public function healthCheck()
    {
        $params = [
            'limit' => 1,
            'is_pack' => 'false',
        ];
        if (!is_array($this->call('/products', 'GET', $params)))
        {
            throw new Exception('Shippingbo account seems to be incorrect');
        }

        return true;
    }
}
