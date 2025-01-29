<?php

namespace Sc\Service\Shippingbo\Model;

use Sc\Service\Lib\Traits\ModelTrait;
use Sc\Service\Model\ServiceModel;
use Sc\Service\Model\ServiceModelInterface;

/**
 * Table de relation entre les données de la boutique et les données Shippingbo.
 */
class ShippingboAccountModel implements ServiceModelInterface
{
    use ModelTrait;

    public static $definition = [
        'table' => SC_DB_PREFIX.'service_shippingbo_account',
        'primary' => 'id_account',
        'fields' => [
            'apiUrl' => ['type' => ServiceModel::TYPE_STRING, 'required' => true],
            'name' => ['type' => ServiceModel::TYPE_STRING, 'required' => true],
            'apiUser' => ['type' => ServiceModel::TYPE_STRING, 'required' => true],
            'apiToken' => ['type' => ServiceModel::TYPE_PASSWORD, 'required' => true],
            'apiVersion' => ['type' => ServiceModel::TYPE_STRING, 'required' => true],
//            'oAuthToken' => ['type' => ServiceModel::TYPE_STRING, 'required' => true],
            'created_at' => ['type' => ServiceModel::TYPE_DATE, 'required' => true],
            'updated_at' => ['type' => ServiceModel::TYPE_DATE, 'required' => true],
        ],
    ];

    public static function createTablesIfNeeded()
    {
        $pdo = \Db::getInstance()->getLink();
        $pdo->query(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'` (
                      `'.self::$definition['primary'].'` int(11) NOT NULL AUTO_INCREMENT,
                      `apiUrl` VARCHAR(255) NOT NULL,
                      `name` VARCHAR(255) NOT NULL,
                      `apiUser` VARCHAR(255) NOT NULL,
                      `apiToken` VARCHAR(255) NOT NULL,
                      `apiVersion` VARCHAR(255) NOT NULL DEFAULT "1",
                      `created_at` datetime NOT NULL COMMENT "UTC",
                      `updated_at` datetime NOT NULL COMMENT "UTC",
                      PRIMARY KEY (`'.self::$definition['primary'].'`),
                      UNIQUE KEY `api_user` (`apiUser`)
                    ) ENGINE=InnoDb DEFAULT CHARACTER SET utf8 COLLATE=utf8_general_ci;'
        );
    }

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

    public static function migrateDb($currentVersion, $targetVersion)
    {
//        $pdo = Db::getInstance()->getLink();
//        if(version_compare($currentVersion, 0, '!=') && version_compare($currentVersion, $targetVersion, '<')){
//            if(version_compare($currentVersion, '1.0.1', '<')){
//
//            }
//        }
    }
}
