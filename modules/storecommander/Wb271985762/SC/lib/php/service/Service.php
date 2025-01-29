<?php

namespace Sc\Service;

use Configuration;
use Context;
use DateTimeImmutable;
use DateTimeZone;
use Db;
use DbQuery;
use Exception;
use IntlDateFormatter;
use PDO;
use Sc\ScLogger\ScLogger;
use Sc\Service\Model\ServiceConfigurationModel as serviceConfigurationModel;
use Sc\Service\Model\ServiceLockerModel;
use Sc\Service\Model\ServiceModel;
use SC_Agent;
use SCI;

abstract class Service implements ServiceInterface
{
    const VERSION = '1.0.2';

    /**
     * @var Service
     */
    protected static $instance;

    /**
     * @var PDO|resource|null
     */
    protected $pdo;
    /**
     * @var ScLogger|null
     */
    protected $logger = null;
    /**
     * @var \SC_Agent
     */
    protected $scAgent;
    public $serviceName;
    /**
     * @var array|string[]
     */
    protected $configDefinition;
    /**
     * @var array
     */
    protected $errors = [];
    /**
     * @var array
     */
    private $config;

    protected $idShop;
    private $currentVersion;

    private $firstStart;
    private $serviceId;
    /**
     * @var array
     */
    private $oldFilesToRemove;

    public function __construct()
    {
        static::$instance = $this;
    }

    /**
     * instantiate Dynamic class and register default configuration.
     *
     * @param $serviceName : snake_case
     */
    public static function autoRegister($serviceName)
    {
        $serviceName = ucfirst(str_replace('_', '', ucwords($serviceName, '_')));
        $serviceClassName = 'Sc\\Service\\'.$serviceName.'\\'.$serviceName;
        if (class_exists($serviceClassName))
        {
            static::createTablesIfNeeded();
            $service = new $serviceClassName();
            $service->createTablesIfNeeded();
            if (!$service->isActive())
            {
                $service->unregister();
                $service->uninstall();

                return false;
            }
            if (!$service->isRegistered())
            {
                $service->setNeededPSConfig();
                $service->register();
            }
            $service->migrateDb($service::VERSION);

            return true;
        }

        return false;
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * @param DateTimeImmutable|false $dateTime
     * @param $formatPattern
     *
     * @return bool|string
     */
    public function getLocaleDate($dateTime, $formatPattern)
    {
        // date
        $timezoneName = in_array(Configuration::get('PS_TIMEZONE'), timezone_identifiers_list()) ? Configuration::get('PS_TIMEZONE') : '';
        $timezone = new DateTimeZone($timezoneName);
        $formatter = new IntlDateFormatter(Context::getContext()->language->locale, IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, $timezone);
        $formatter->setPattern($formatPattern);

        return $formatter->format($dateTime->getTimestamp());
    }

    public static function exists($serviceName)
    {
        $serviceName = ucfirst(str_replace('_', '', ucwords($serviceName, '_')));
        $classFile = $serviceName.'/'.$serviceName.'.php';
        $exists = file_exists(__DIR__.'/'.$classFile);
        if ($exists)
        {
            $serviceClassName = 'Sc\\Service\\'.$serviceName.'\\'.$serviceName;
            /* @var ServiceInterface $serviceClassName */
            try
            {
                $service = $serviceClassName::getInstance();
            }
            catch (Exception $e)
            {
                return false;
            }

            return $service->isActive();
        }

        return $exists;
    }

    protected static function getServiceCurrentVersion()
    {
        $currentVersion = '0';
        if (Db::getInstance()->getLink()->query('SELECT * FROM information_schema.tables WHERE table_name = \''._DB_PREFIX_.(new ServiceModel())->getTableName().'\' and table_schema= \''._DB_NAME_.'\' LIMIT 1;')->rowCount() > 0)
        {
            $currentVersion = '1.0.0';
        }

        return SCI::getConfigurationValue('SC_SERVICE_VERSION') ? SCI::getConfigurationValue('SC_SERVICE_VERSION') : $currentVersion;
    }

    protected static function getServiceTargetVersion()
    {
        return !defined(Service::VERSION) ? Service::VERSION : '1.0.0';
    }

    protected static function createTablesIfNeeded()
    {
        $currentVersion = self::getServiceCurrentVersion();
        $targetVersion = self::getServiceTargetVersion();

        ServiceModel::createTablesIfNeeded();
        ServiceConfigurationModel::createTablesIfNeeded();
        ServiceLockerModel::createTablesIfNeeded();
        ServiceModel::migrateDb($currentVersion, $targetVersion);
        ServiceLockerModel::migrateDb($currentVersion, $targetVersion);
        ServiceConfigurationModel::migrateDb($currentVersion, $targetVersion);
        SCI::updateConfigurationValue('SC_SERVICE_VERSION', $targetVersion);
    }

    public function unregister()
    {
        $stmt = $this->getPdo()->prepare('SELECT id_service FROM `'._DB_PREFIX_.(new ServiceModel())->getTableName().'` WHERE name = :name');
        $stmt->execute([':name' => $this->serviceName]);
        $serviceId = $stmt->fetch(PDO::FETCH_COLUMN);

        $stmtRemoveService = $this->getPdo()->prepare('DELETE FROM `'._DB_PREFIX_.(new ServiceModel())->getTableName().'` WHERE id_service = :id_service');

        $stmtRemoveConfigurations = $this->getPdo()->prepare('DELETE FROM `'._DB_PREFIX_.(new ServiceConfigurationModel())->getTableName().'` WHERE id_service = :id_service');
        if (!$this->getPdo()->inTransaction())
        {
            $this->getPdo()->beginTransaction();
        }
        if (!$stmtRemoveService->execute([':id_service' => $serviceId]))
        {
            $this->getPdo()->rollback();

            return false;
        }

        if (!$stmtRemoveConfigurations->execute([':id_service' => $serviceId]))
        {
            $this->getPdo()->rollback();

            return false;
        }
        $this->getPdo()->commit();

        return true;
    }

    /**
     * @descr a voir pour supprimer les fichier depuis le service et non pas le l'utf16 : pertinent ?
     *
     * @return void
     */
    public function uninstall()
    {
        // TODO ?
    }

    public function register()
    {
        // register service
        $stmtService = $this->getPdo()->prepare('INSERT IGNORE INTO `'._DB_PREFIX_.(new ServiceModel())->getTableName().'` (`name`,`created_at`) VALUES(:service_name,:created_at) ');
        $stmtService->execute([':service_name' => $this->serviceName, ':created_at' => date('Y-m-d H:i:s')]);

        $stmt = $this->getPdo()->prepare('SELECT id_service FROM `'._DB_PREFIX_.(new ServiceModel())->getTableName().'` WHERE name = :name');
        $stmt->execute([':name' => $this->serviceName]);
        $serviceId = $stmt->fetch(PDO::FETCH_COLUMN);

        // register service configuration
        $stmtConfigs = $this->getPdo()->prepare('INSERT IGNORE INTO `'._DB_PREFIX_.(new ServiceConfigurationModel())->getTableName().'` (`id_service`,`name`,`value`,`type`,`id_shop`,`created_at`,`updated_at`) VALUES(:id_service,:name,:value,:type,:id_shop,:created_at,:updated_at) ');

        if (!$this->getPdo()->inTransaction())
        {
            $this->getPdo()->beginTransaction();
        }
        foreach ($this->configDefinition as $name => $data)
        {
            $type = $data['type'] ? $data['type']:'standard';
            $dateTime = date('Y-m-d H:i:s');
            $stmtConfigs->bindParam(':id_service', $serviceId, PDO::PARAM_INT);
            $stmtConfigs->bindParam(':name', $name);
            $stmtConfigs->bindParam(':value', $data['value']);
            $stmtConfigs->bindParam(':type', $type);
            $stmtConfigs->bindParam(':id_shop', Configuration::get('PS_SHOP_DEFAULT'));
            $stmtConfigs->bindParam(':created_at', $dateTime);
            $stmtConfigs->bindParam(':updated_at', $dateTime);
            $stmtConfigs->bindParam(':created_at', $dateTime);
            $stmtConfigs->execute();
        }

        if ($this->getPdo()->inTransaction())
        {
            $this->getPdo()->commit();
        }
        $this->getLogger()->setFilesToKeep($this->getConfigValue('logFilesToKeep'));
    }

    public function getConfig($refresh = false)
    {
        if (!$this->config || $refresh)
        {
            $this->config = $this->getConfigDefinition();
            $configQuery = new \DbQuery();
            $configQuery->select('config.*')
                ->from((new ServiceModel())->getTableName(), 'service')
                ->innerJoin((new ServiceConfigurationModel())->getTableName(), 'config', 'config.id_service = service.id_service AND service.name = :service_name')
                ->where('config.id_shop = :id_shop')
            ;
            $stmtService = $this->getPdo()->prepare($configQuery);
            $stmtService->execute([
                ':service_name' => $this->serviceName,
                ':id_shop' => $this->getIdShop(),
            ]);

            $results = $stmtService->fetchAll(PDO::FETCH_ASSOC);

            $config = array_column((array) $results, null, 'name');

            $this->config = array_replace_recursive($this->config, $config);
            foreach ($this->config as $key => $config)
            {
                if (isset($config['type']) && $config['type'] === 'password')
                {
                    $this->config[$key]['value'] = SCI::decrypt($this->config[$key]['value']);
                }
            }
        }

        return $this->config;
    }

    /**
     * @param array $params
     *
     * @return $this|mixed
     *
     * @throws Exception
     */
    public function setConfig($params)
    {
        try
        {
            $this->getPdo()->beginTransaction();
            $stmt = $this->getPdo()->prepare('SELECT id_service FROM `'._DB_PREFIX_.(new ServiceModel())->getTableName().'` WHERE name = :name');
            $stmt->execute([':name' => $this->serviceName]);
            $serviceId = $stmt->fetchColumn();
            $serviceConfigTableName = _DB_PREFIX_.(new ServiceConfigurationModel())->getTableName();

            $sql = <<<SQL
    INSERT INTO `{$serviceConfigTableName}` (id_service, name, value, type, id_shop, created_at, updated_at) VALUES (:id_service, :name, :value, :type, :id_shop, :created_at,:updated_at)
    ON DUPLICATE KEY UPDATE
                `value` = :value,
                `updated_at` = :updated_at
SQL;

            $serviceConfiguration = $this->getConfigDefinition();
            $stmtService = $this->getPdo()->prepare($sql);
            $stmtService->bindValue(':id_service', $serviceId);
            foreach ($params as $key => $value)
            {
                if (is_array($value))
                {
                    $value = $value['value'];
                }

                $type = isset($serviceConfiguration[$key]['type']) ? $serviceConfiguration[$key]['type'] : 'standard';
                $stmtService->bindValue(':name', $key);
                $stmtService->bindValue(':value', $value);
                $stmtService->bindValue(':type', $type);
                $stmtService->bindValue(':id_shop', $this->getIdShop());
                $stmtService->bindValue(':created_at', date('Y-m-d H:i:s'));
                $stmtService->bindValue(':updated_at', date('Y-m-d H:i:s'));
                $stmtService->execute();
            }
            if ($this->getPdo()->inTransaction())
            {
                $this->getPdo()->commit();
            }
            $this->getConfig(true);

            return $this;
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }

    public function isActive()
    {
        return false;
    }

    /**
     * @param $paramName
     *
     * @return bool|mixed
     *
     * @throws Exception
     */
    public function checkConfig($paramName = false)
    {
        $config = $this->getConfig(true);

        if (empty($config))
        {
            throw new Exception('Unable to get config from database');
        }
        if ($paramName)
        {
            $paramValue = $config[$paramName]['value'];

            return isset($paramValue) ? $paramValue : false;
        }
        else
        {
            foreach ($this->getConfigDefinition(true) as $key => $requiredParam)
            {
                if (!isset($config[$key]) or empty($config[$key]['value']))
                {
                    throw new Exception('Required field '.$key.' is invalid', '300');
                }
            }
        }

        return true;
    }

    public function readyForSync()
    {
        try
        {
            return $this->checkConfig();
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    /**
     * @return array|string[]
     */
    public function getConfigDefinition($onlyRequired = false)
    {
        $configParams = $this->configDefinition;
        if ($onlyRequired)
        {
            $requiredConfigParams = $configParams;
            foreach ($requiredConfigParams as $key => $requiredConfigParam)
            {
                if (!(bool) $requiredConfigParam['required'])
                {
                    unset($requiredConfigParams[$key]);
                }
            }
            $configParams = $requiredConfigParams;
        }

        return $configParams;
    }

    /**
     * @param array<string, array<string, bool|int|string|null>> $configDefinition
     */
    public function setConfigDefinition($configDefinition)
    {
        $this->configDefinition = $configDefinition;

        return $this;
    }

    /**
     *  // TODO 2 : response object !
     *
     * @return $this
     */
    public function addError(Exception $exception, $addTrace = true)
    {
        $error = $exception->getMessage();
        if (_PS_MODE_DEV_)
        {
            restore_exception_handler();
            restore_error_handler();
            ini_set('xdebug.max_nesting_level', '2048');
            ini_set('xdebug.var_display_max_depth', '15');
            ini_set('xdebug.var_display_max_data;', '15');
            ini_set('xdebug.max_nesting_level;', '15');
            ini_set('xdebug.var_display_max_children;', '15');
            ini_set('xdebug.show_error_trace', '10');
            ini_set('xdebug.show_exception_trace', '10');
            if ($addTrace)
            {
                $error .= "<pre>\n\r".var_export($exception->getTrace(), true).'</pre>';
            }
        }
        $this->errors[] = $error;
        $this->getLogger()->error($error);

        return $this;
    }

    /**
     * @desc : send response as json
     * // TODO 2 : response object !
     *
     * @param string   $successMessage
     * @param array    $extra
     * @param string[] $headers
     *
     * @return false|int|string
     */
    public function sendResponse($successMessage = 'success', $extra = [], $headers = ['Content-Type' => 'application/json; charset=utf-8'])
    {
        $this->getLogger()->debug($successMessage);

        foreach ($headers as $key => $value)
        {
            header($key.': '.$value);
        }

        $response = $this->getResponse($successMessage, $extra);

        echo json_encode($response);
        exit;
    }

    public function getResponse($successMessage, $options = [])
    {
        $response = ['state' => true, 'extra' => ['code' => 200, 'message' => $successMessage]];
        if (!empty($this->errors))
        {
            $response['state'] = false;
            $response['extra']['code'] = 103;
            $response['extra']['message'] = '<ul><li>'.implode('</li><li>', $this->errors).'</li></ul>';
        }
        $response['extra'] = array_merge($response['extra'], (array) $options);

        return $response;
    }

    /**
     * @return array<string,mixed>|false
     */
    public function getConfigValue($key)
    {
        $config = $this->getConfig(true);
        if (isset($config[$key]))
        {
            if (is_array($config[$key]))
            {
                // merge with default values
                if (isset(static::${$key}))
                {
                    $valueAsArray = json_decode($config[$key]['value'], true);
                    $config[$key]['value'] = json_encode(array_replace_recursive(static::${$key}, $valueAsArray));
                }

                return $config[$key]['value'];
            }

            return $config[$key];
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isRegistered()
    {
        if (!isTable((new ServiceModel())->getTableName()))
        {
            return false;
        }
        $stmt = $this->getPdo()->prepare('SELECT * FROM  `'._DB_PREFIX_.(new ServiceModel())->getTableName().'` sc_service WHERE sc_service.name = :service_name');
        $stmt->execute([':service_name' => $this->getServiceName()]);

        return $stmt->rowCount() > 0;
    }

    public function getLogger()
    {
        if (!$this->logger)
        {
            $this->setLogger(new ScLogger($this->getServiceName()));
        }

        return $this->logger;
    }

    /**
     * @param ScLogger $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return mixed
     */
    public function setScAgent(Sc_Agent $scAgent)
    {
        $this->scAgent = $scAgent;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getScAgent()
    {
        if (!$this->scAgent)
        {
            $this->setScAgent(\SC_Agent::getInstance());
        }

        return $this->scAgent;
    }

    /**
     * @return PDO|resource|null
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
     * @param PDO|resource|null $pdo
     */
    public function setPdo($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return int
     */
    public function getIdShop()
    {
        if (!$this->idShop)
        {
            $this->idShop = Configuration::get('PS_SHOP_DEFAULT');
        }

        return (int) $this->idShop;
    }

    /**
     * @param int $idShop
     */
    public function switchToShopId($idShop)
    {
//        \Shop::setContext(\Shop::CONTEXT_SHOP, (int) $idShop);
        $this->idShop = (int) $idShop;

        return $this;
    }

    public function getCurrentVersion()
    {
        if (!$this->currentVersion)
        {
            $versionQuery = Db::getInstance()->getLink()->query('SELECT version FROM `'._DB_PREFIX_.(new ServiceModel())->getTableName().'` WHERE name = \''.$this->getServiceName().'\'');
            $this->currentVersion = $versionQuery ? $versionQuery->fetchColumn() : '0';
        }

        return (string) $this->currentVersion ?: '0';
    }

    public static function updateVersion($targetVersion)
    {
        Db::getInstance()->getLink()->query('UPDATE `'._DB_PREFIX_.(new ServiceModel())->getTableName().'` SET version = "'.$targetVersion.'"');
    }

    /**
     * @return bool
     */
    public function isFirstStart()
    {
        if (!$this->firstStart)
        {
            $firstStartQuery = Db::getInstance()->getLink()->query('SELECT first_start FROM `'._DB_PREFIX_.(new ServiceModel())->getTableName().'`');
            $this->firstStart = $firstStartQuery ? $firstStartQuery->fetchColumn() : null;
        }

        return (bool) $this->firstStart;
    }

    /**
     * @param int $firstStart void
     */
    public static function setFirstStart($firstStart)
    {
        Db::getInstance()->getLink()->query('UPDATE `'._DB_PREFIX_.(new ServiceModel())->getTableName().'` SET first_start = '.(int) $firstStart);
    }

    /**
     * @return mixed
     */
    public function getServiceId()
    {
        if (!$this->serviceId)
        {
            $query = new DbQuery();
            $query->select('id_service')
                ->from((new ServiceModel())->getTableName(), 's')
                ->where('s.name = :service_name')
            ;
            $stmt = $this->getPdo()->prepare($query);
            $stmt->execute([
                ':service_name' => $this->serviceName,
            ]);
            $this->serviceId = $stmt->fetchColumn();
        }

        return $this->serviceId;
    }

    /**
     * @return mixed
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @param mixed $serviceName
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;

        return $this;
    }
}
