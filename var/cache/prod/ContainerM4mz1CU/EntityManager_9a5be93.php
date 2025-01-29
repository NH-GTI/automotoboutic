<?php

class EntityManager_9a5be93 extends \Doctrine\ORM\EntityManager implements \ProxyManager\Proxy\VirtualProxyInterface
{
    private $valueHoldera327a = null;
    private $initializerd853a = null;
    private static $publicPropertiesaa09a = [
        
    ];
    public function getConnection()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getConnection', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->getConnection();
    }
    public function getMetadataFactory()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getMetadataFactory', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->getMetadataFactory();
    }
    public function getExpressionBuilder()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getExpressionBuilder', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->getExpressionBuilder();
    }
    public function beginTransaction()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'beginTransaction', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->beginTransaction();
    }
    public function getCache()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getCache', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->getCache();
    }
    public function transactional($func)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'transactional', array('func' => $func), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->transactional($func);
    }
    public function wrapInTransaction(callable $func)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'wrapInTransaction', array('func' => $func), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->wrapInTransaction($func);
    }
    public function commit()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'commit', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->commit();
    }
    public function rollback()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'rollback', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->rollback();
    }
    public function getClassMetadata($className)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getClassMetadata', array('className' => $className), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->getClassMetadata($className);
    }
    public function createQuery($dql = '')
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'createQuery', array('dql' => $dql), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->createQuery($dql);
    }
    public function createNamedQuery($name)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'createNamedQuery', array('name' => $name), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->createNamedQuery($name);
    }
    public function createNativeQuery($sql, \Doctrine\ORM\Query\ResultSetMapping $rsm)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'createNativeQuery', array('sql' => $sql, 'rsm' => $rsm), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->createNativeQuery($sql, $rsm);
    }
    public function createNamedNativeQuery($name)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'createNamedNativeQuery', array('name' => $name), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->createNamedNativeQuery($name);
    }
    public function createQueryBuilder()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'createQueryBuilder', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->createQueryBuilder();
    }
    public function flush($entity = null)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'flush', array('entity' => $entity), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->flush($entity);
    }
    public function find($className, $id, $lockMode = null, $lockVersion = null)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'find', array('className' => $className, 'id' => $id, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->find($className, $id, $lockMode, $lockVersion);
    }
    public function getReference($entityName, $id)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getReference', array('entityName' => $entityName, 'id' => $id), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->getReference($entityName, $id);
    }
    public function getPartialReference($entityName, $identifier)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getPartialReference', array('entityName' => $entityName, 'identifier' => $identifier), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->getPartialReference($entityName, $identifier);
    }
    public function clear($entityName = null)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'clear', array('entityName' => $entityName), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->clear($entityName);
    }
    public function close()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'close', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->close();
    }
    public function persist($entity)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'persist', array('entity' => $entity), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->persist($entity);
    }
    public function remove($entity)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'remove', array('entity' => $entity), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->remove($entity);
    }
    public function refresh($entity)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'refresh', array('entity' => $entity), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->refresh($entity);
    }
    public function detach($entity)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'detach', array('entity' => $entity), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->detach($entity);
    }
    public function merge($entity)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'merge', array('entity' => $entity), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->merge($entity);
    }
    public function copy($entity, $deep = false)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'copy', array('entity' => $entity, 'deep' => $deep), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->copy($entity, $deep);
    }
    public function lock($entity, $lockMode, $lockVersion = null)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'lock', array('entity' => $entity, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->lock($entity, $lockMode, $lockVersion);
    }
    public function getRepository($entityName)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getRepository', array('entityName' => $entityName), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->getRepository($entityName);
    }
    public function contains($entity)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'contains', array('entity' => $entity), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->contains($entity);
    }
    public function getEventManager()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getEventManager', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->getEventManager();
    }
    public function getConfiguration()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getConfiguration', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->getConfiguration();
    }
    public function isOpen()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'isOpen', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->isOpen();
    }
    public function getUnitOfWork()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getUnitOfWork', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->getUnitOfWork();
    }
    public function getHydrator($hydrationMode)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getHydrator', array('hydrationMode' => $hydrationMode), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->getHydrator($hydrationMode);
    }
    public function newHydrator($hydrationMode)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'newHydrator', array('hydrationMode' => $hydrationMode), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->newHydrator($hydrationMode);
    }
    public function getProxyFactory()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getProxyFactory', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->getProxyFactory();
    }
    public function initializeObject($obj)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'initializeObject', array('obj' => $obj), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->initializeObject($obj);
    }
    public function getFilters()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'getFilters', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->getFilters();
    }
    public function isFiltersStateClean()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'isFiltersStateClean', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->isFiltersStateClean();
    }
    public function hasFilters()
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, 'hasFilters', array(), $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        return $this->valueHoldera327a->hasFilters();
    }
    public static function staticProxyConstructor($initializer)
    {
        static $reflection;
        $reflection = $reflection ?? new \ReflectionClass(__CLASS__);
        $instance   = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $instance, 'Doctrine\\ORM\\EntityManager')->__invoke($instance);
        $instance->initializerd853a = $initializer;
        return $instance;
    }
    protected function __construct(\Doctrine\DBAL\Connection $conn, \Doctrine\ORM\Configuration $config, \Doctrine\Common\EventManager $eventManager)
    {
        static $reflection;
        if (! $this->valueHoldera327a) {
            $reflection = $reflection ?? new \ReflectionClass('Doctrine\\ORM\\EntityManager');
            $this->valueHoldera327a = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);
        }
        $this->valueHoldera327a->__construct($conn, $config, $eventManager);
    }
    public function & __get($name)
    {
        $this->initializerd853a && ($this->initializerd853a->__invoke($valueHoldera327a, $this, '__get', ['name' => $name], $this->initializerd853a) || 1) && $this->valueHoldera327a = $valueHoldera327a;
        if (isset(self::$publicPropertiesaa09a[$name])) {
            return $this->valueHoldera327a->$name;
        }
        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');
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
        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');
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
        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');
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
        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');
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
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);
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
