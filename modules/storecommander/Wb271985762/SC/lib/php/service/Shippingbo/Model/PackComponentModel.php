<?php

namespace Sc\Service\Shippingbo\Model;

use Db;
use Sc\Service\Lib\Traits\ModelTrait;
use Sc\Service\Model\ServiceModel;
use Sc\Service\Model\ServiceModelInterface;

class PackComponentModel implements ServiceModelInterface
{
    use ModelTrait;

    public static $definition = [
        'table' => SC_DB_PREFIX.'service_shippingbo_pack_component_buffer',
        'primary' => 'id_'.SC_DB_PREFIX.'service_shippingbo_pack_component_buffer',
        'fields' => [
            'id' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
            'id_sbo_account' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
            'quantity' => ['type' => ServiceModel::TYPE_STRING],
            'pack_product_id' => ['type' => ServiceModel::TYPE_INT],
            'component_product_id' => ['type' => ServiceModel::TYPE_INT],
            'created_at' => ['type' => ServiceModel::TYPE_DATE, 'required' => true],
            'updated_at' => ['type' => ServiceModel::TYPE_DATE, 'required' => true],
            'synced_at' => ['type' => ServiceModel::TYPE_DATE, 'required' => true],
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

    public static function createTablesIfNeeded()
    {
        $pdo = \Db::getInstance()->getLink();
        $pdo->query(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'` (
                      `'.self::$definition['primary'].'` int(11) NOT NULL AUTO_INCREMENT,
                      `id` int(11) NOT NULL,
                      `id_sbo_account` INT(11) NOT NULL DEFAULT 1,
                      `quantity` VARCHAR(255) NOT NULL,
                      `pack_product_id` INT(11) NOT NULL,
                      `component_product_id` INT(11) NOT NULL,
                      `created_at` datetime NOT NULL COMMENT "UTC",
                      `updated_at` datetime NOT NULL COMMENT "UTC",
                      `synced_at` datetime NOT NULL COMMENT "UTC date row insertion",
                      PRIMARY KEY (`'.self::$definition['primary'].'`),
                      UNIQUE KEY `pack_product_id` (`pack_product_id`,`component_product_id`,`id_sbo_account`),
                      INDEX `id` (`id`) USING BTREE,
                      INDEX `component_product_id` (`component_product_id`) USING BTREE,
                      INDEX `quantity` (`quantity`) USING BTREE,
                      INDEX `synced_at` (`synced_at`) USING BTREE
                    ) ENGINE=InnoDb DEFAULT CHARACTER SET utf8 COLLATE=utf8_general_ci;'
        );
    }

    public static function migrateDb($currentVersion, $targetVersion)
    {
        $pdo = Db::getInstance()->getLink();
        if (version_compare($currentVersion, 0, '!=') && version_compare($currentVersion, $targetVersion, '<'))
        {
            if (version_compare($currentVersion, '1.0.1', '<'))
            {
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'`ADD COLUMN `id_sbo_account` INT(11) NOT NULL DEFAULT 1 AFTER `id`');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` DROP INDEX `pack_product_id`;');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` ADD CONSTRAINT `pack_product_id` UNIQUE (`pack_product_id`,`component_product_id`,`id_sbo_account`)');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'`
	ADD INDEX `pack_component` (`pack_product_id`, `id_sbo_account`);');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'`
	CHANGE COLUMN `pack_product_id` `pack_product_id` INT NOT NULL DEFAULT 0;');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'`
	CHANGE COLUMN `component_product_id` `component_product_id` INT NOT NULL DEFAULT 0;');
            }
        }
    }
}
