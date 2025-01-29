<?php

namespace Sc\Service\Model;

use Configuration;
use Db;

class ServiceConfigurationModel
{
    /**
     * @var array<string,mixed>
     */
    public static $definition = [
        'table' => SC_DB_PREFIX.'service_configuration',
        'primary' => 'id_service_configuration',
        'fields' => [
            'id_service' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
            'name' => ['type' => ServiceModel::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true],
            'value' => ['type' => ServiceModel::TYPE_STRING, 'validate' => 'isGenericName'],
            'type' => ['type' => ServiceModel::TYPE_STRING, 'validate' => 'isGenericName', 'default' => 'standard'],
            'id_shop' => ['type' => ServiceModel::TYPE_INT, 'required' => true, 'default' => 0],
            'created_at' => ['type' => ServiceModel::TYPE_DATE, 'required' => true, 'size' => 11],
            'updated_at' => ['type' => ServiceModel::TYPE_DATE, 'required' => true, 'size' => 11],
        ],
    ];

    /**
     * @return void
     */
    public static function createTablesIfNeeded()
    {
        $pdo = \Db::getInstance()->getLink();
        $pdo->query(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'` (
                  `id_service_configuration` int(11) NOT NULL AUTO_INCREMENT,
                  `id_service` int(11) NOT NULL,
                  `name` VARCHAR(255) NOT NULL,
                  `value` VARCHAR(255) NULL,
                  `type` VARCHAR(255) NOT NULL DEFAULT "standard",
                  `id_shop` INT(11) NULL DEFAULT '.(int) Configuration::get('PS_SHOP_DEFAULT').',
                  `created_at` datetime NOT NULL COMMENT "configuration creation date",
                  `updated_at` datetime NOT NULL COMMENT "configuration update date",
                  PRIMARY KEY (`id_service_configuration`),
                  UNIQUE KEY `name` (`id_service`,`name`,`id_shop`)
                ) ENGINE=InnoDb DEFAULT CHARACTER SET utf8 COLLATE=utf8_general_ci;');
    }

    /**
     * @param string $currentVersion
     * @param string $targetVersion
     *
     * @return void
     */
    public static function migrateDb($currentVersion, $targetVersion)
    {
        if (version_compare($currentVersion, '0', '!='))
        {
            if (version_compare($currentVersion, '1.0.1', '<'))
            {
                $stmtFields = Db::getInstance()->getLink()->query('SHOW COLUMNS FROM '._DB_PREFIX_.self::$definition['table'].'2;');
                if (!$stmtFields)
                {
                    return;
                }
                $stmtFields->execute();
                if (!($columns = $stmtFields->fetchAll()))
                {
                    return;
                }
                $existingFields = array_flip(array_column($columns, 'Field'));
                // add id_shop field
                if (!isset($existingFields['id_shop']))
                {
                    Db::getInstance()->getLink()->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'`
            ADD COLUMN `id_shop` int(11) NOT NULL DEFAULT '.(int) Configuration::get('PS_SHOP_DEFAULT').' AFTER `type`');
                }
                Db::getInstance()->getLink()->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` DROP INDEX `name`;');
                Db::getInstance()->getLink()->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` ADD CONSTRAINT `name` UNIQUE (`id_service`,`name`,`id_shop`)');
            }
        }
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
}
