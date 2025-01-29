<?php

namespace Sc\Service\Model;

use Sc\Service\Lib\Service\Locker\Entity\Locker;
use Sc\Service\Lib\Service\Locker\Traits\LockerTrait;

class ServiceLockerModel
{
    use LockerTrait;
    const TYPE_INT = 1;
    const TYPE_BOOL = 2;
    const TYPE_STRING = 3;
    const TYPE_FLOAT = 4;
    const TYPE_DATE = 5;
    const TYPE_HTML = 6;
    const TYPE_PASSWORD = 9;
    const TYPE_NOTHING = 7;
    const TYPE_SQL = 8;

    /**
     * @var array
     */
    private static $definition = [
        'table' => SC_DB_PREFIX.'service_locker',
        'primary' => 'id_service_locker',
        'fields' => [
            'id_service' => ['type' => self::TYPE_INT,  'required' => true],
            'locker_name' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true],
            'locker_group' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'default' => Locker::DEFAULT_LOCKER_GROUP],
            'status' => ['type' => self::TYPE_STRING, 'required' => true, 'default' => Locker::STATUS_RELEASED],
            'detail' => ['type' => self::TYPE_STRING, 'default' => null],
            'created_at' => ['type' => self::TYPE_DATE, 'required' => true, 'size' => 11],
        ],
    ];

    /**
     * @return mixed
     */
    public function getTableName()
    {
        return self::$definition['table'];
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return self::$definition['primary'];
    }

    /**
     * @return void
     */
    public static function createTablesIfNeeded()
    {
        $pdo = \Db::getInstance()->getLink();
        $pdo->query('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'` (
                      `id_service_locker` int(11) NOT NULL AUTO_INCREMENT,
                      `id_service` int(11) NOT NULL,
                      `locker_name` VARCHAR(255) NOT NULL,
                      `locker_group` VARCHAR(255) NOT NULL DEFAULT "default",
                      `status` ENUM(\''.Locker::STATUS_LOCKED.'\', \''.Locker::STATUS_RELEASED.'\') NULL DEFAULT  \''.Locker::STATUS_RELEASED.'\',
                      `detail` VARCHAR(255) NULL COMMENT "running process information",
                      `created_at` datetime NOT NULL COMMENT "service locker creation date",
                      PRIMARY KEY (`id_service_locker`),
                      UNIQUE KEY `locker` (`id_service`,`locker_name`)
                    ) ENGINE=InnoDb DEFAULT CHARACTER SET utf8 COLLATE=utf8_general_ci;');
    }

    /**
     * @param $currentVersion
     * @param $targetVersion
     *
     * @return void
     */
    public static function migrateDb($currentVersion, $targetVersion)
    {
    }
}
