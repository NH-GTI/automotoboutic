<?php

class ModuleRepository_091bb2f extends \PrestaShop\PrestaShop\Core\Module\ModuleRepository implements \ProxyManager\Proxy\VirtualProxyInterface
{
    /**
     * @var \PrestaShop\PrestaShop\Core\Module\ModuleRepository|null wrapped object, if the proxy is initialized
     */
    private $valueHoldera327a = null;

    /**
     * @var \Closure|null initializer responsible for generating the wrapped object
     */
    private $initializerd853a = null;

    /**
     * @var bool[] map of public properties of the parent class
     */
    private static $publicPropertiesaa09a = [
        
    ];

    public function getList() : \PrestaShop\PrestaShop\Core\Module\ModuleCollection
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getList', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;

        return $this->valueHoldera327a->getList();
    }

    public function getInstalledModules() : \PrestaShop\PrestaShop\Core\Module\ModuleCollection
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getInstalledModules', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;

        return $this->valueHoldera327a->getInstalledModules();
    }

    public function getMustBeConfiguredModules() : \PrestaShop\PrestaShop\Core\Module\ModuleCollection
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getMustBeConfiguredModules', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;

        return $this->valueHoldera327a->getMustBeConfiguredModules();
    }

    public function getUpgradableModules() : \PrestaShop\PrestaShop\Core\Module\ModuleCollection
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getUpgradableModules', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;

        return $this->valueHoldera327a->getUpgradableModules();
    }

    public function getModule(string $moduleName) : \PrestaShop\PrestaShop\Core\Module\ModuleInterface
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getModule', array('moduleName' => $moduleName), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;

        return $this->valueHoldera327a->getModule($moduleName);
    }

    public function getModulePath(string $moduleName) : ?string
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getModulePath', array('moduleName' => $moduleName), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;

        return $this->valueHoldera327a->getModulePath($moduleName);
    }

    public function setActionUrls(\PrestaShop\PrestaShop\Core\Module\ModuleCollection $collection) : \PrestaShop\PrestaShop\Core\Module\ModuleCollection
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'setActionUrls', array('collection' => $collection), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;

        return $this->valueHoldera327a->setActionUrls($collection);
    }

    public function clearCache(?string $moduleName = null, bool $allShops = false) : bool
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'clearCache', array('moduleName' => $moduleName, 'allShops' => $allShops), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;

        return $this->valueHoldera327a->clearCache($moduleName, $allShops);
    }

    /**
     * Constructor for lazy initialization
     *
     * @param \Closure|null $initializer
     */
    public static function staticProxyConstructor($initializer)
    {
        static $reflection;

        $reflection = $reflection ?? new \ReflectionClass(__CLASS__);
        $instance   = $reflection->newInstanceWithoutConstructor();

        \Closure::bind(function (\PrestaShop\PrestaShop\Core\Module\ModuleRepository $instance) {
            unset($instance->moduleDataProvider, $instance->adminModuleDataProvider, $instance->hookManager, $instance->cacheProvider, $instance->modulePath, $instance->installedModules, $instance->modulesFromHook, $instance->contextLangId);
        }, $instance, 'PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository')->__invoke($instance);

        $instance->initializerd853a = $initializer;

        return $instance;
    }

    public function __construct(\PrestaShop\PrestaShop\Adapter\Module\ModuleDataProvider $moduleDataProvider, \PrestaShop\PrestaShop\Adapter\Module\AdminModuleDataProvider $adminModuleDataProvider, \Doctrine\Common\Cache\CacheProvider $cacheProvider, \PrestaShop\PrestaShop\Adapter\HookManager $hookManager, string $modulePath, int $contextLangId)
    {
        static $reflection;

        if (! $this->valueHoldera327a) {
            $reflection = $reflection ?? new \ReflectionClass('PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository');
            $this->valueHoldera327a = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\PrestaShop\PrestaShop\Core\Module\ModuleRepository $instance) {
            unset($instance->moduleDataProvider, $instance->adminModuleDataProvider, $instance->hookManager, $instance->cacheProvider, $instance->modulePath, $instance->installedModules, $instance->modulesFromHook, $instance->contextLangId);
        }, $this, 'PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository')->__invoke($this);

        }

        $this->valueHoldera327a->__construct($moduleDataProvider, $adminModuleDataProvider, $cacheProvider, $hookManager, $modulePath, $contextLangId);
    }

    public function & __get($name)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, '__get', ['name' => $name], $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;

        if (isset(self::$publicPropertiesaa09a[$name])) {
            return $this->valueHoldera327a->$name;
        }

        $realInstanceReflection = new \ReflectionClass('PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldera327a;

            $backtrace = debug_backtrace(false, 1);
            trigger_error(
                sprintf(
                    'Undefined property: %s::$%s in %s on line %s',
                    $realInstanceReflection->getName(),
                    $name,
                    $backtrace[0]['file'],
                    $backtrace[0]['line']
                ),
                \E_USER_NOTICE
            );
            return $targetObject->$name;
        }

        $targetObject = $this->valueHoldera327a;
        $accessor = function & () use ($targetObject, $name) {
            return $targetObject->$name;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = & $accessor();

        return $returnValue;
    }

    public function __set($name, $value)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, '__set', array('name' => $name, 'value' => $value), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;

        $realInstanceReflection = new \ReflectionClass('PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldera327a;

            $targetObject->$name = $value;

            return $targetObject->$name;
        }

        $targetObject = $this->valueHoldera327a;
        $accessor = function & () use ($targetObject, $name, $value) {
            $targetObject->$name = $value;

            return $targetObject->$name;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = & $accessor();

        return $returnValue;
    }

    public function __isset($name)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, '__isset', array('name' => $name), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;

        $realInstanceReflection = new \ReflectionClass('PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldera327a;

            return isset($targetObject->$name);
        }

        $targetObject = $this->valueHoldera327a;
        $accessor = function () use ($targetObject, $name) {
            return isset($targetObject->$name);
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = $accessor();

        return $returnValue;
    }

    public function __unset($name)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, '__unset', array('name' => $name), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;

        $realInstanceReflection = new \ReflectionClass('PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldera327a;

            unset($targetObject->$name);

            return;
        }

        $targetObject = $this->valueHoldera327a;
        $accessor = function () use ($targetObject, $name) {
            unset($targetObject->$name);

            return;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $accessor();
    }

    public function __clone()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, '__clone', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;

        $this->valueHoldera327a = clone $this->valueHoldera327a;
    }

    public function __sleep()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, '__sleep', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;

        return array('valueHoldera327a');
    }

    public function __wakeup()
    {
        \Closure::bind(function (\PrestaShop\PrestaShop\Core\Module\ModuleRepository $instance) {
            unset($instance->moduleDataProvider, $instance->adminModuleDataProvider, $instance->hookManager, $instance->cacheProvider, $instance->modulePath, $instance->installedModules, $instance->modulesFromHook, $instance->contextLangId);
        }, $this, 'PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository')->__invoke($this);
    }

    public function setProxyInitializer(\Closure $initializer = null) : void
    {
        $this->initializerd853a = $initializer;
    }

    public function getProxyInitializer() : ?\Closure
    {
        return $this->initializerd853a;
    }

    public function initializeProxy() : bool
    {
        return $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'initializeProxy', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
    }

    public function isProxyInitialized() : bool
    {
        return null !== $this->valueHoldera327a;
    }

    public function getWrappedValueHolderValue()
    {
        return $this->valueHoldera327a;
    }
}
