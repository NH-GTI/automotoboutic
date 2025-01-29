<?php

namespace Sc\Service\Shippingbo\Model;

use Db;
use Sc\Service\Lib\Traits\ModelTrait;
use Sc\Service\Model\ServiceModel;
use Sc\Service\Model\ServiceModelInterface;

/**
 * Table de relation entre les données de la boutique et les données Shippingbo.
 */
class ShopRelationModel implements ServiceModelInterface
{
    use ModelTrait;

    public static $definition = [
        'table' => SC_DB_PREFIX.'service_shippingbo_shop_relation',
        'primary' => 'id_'.SC_DB_PREFIX.'service_shippingbo_shop_relation',
        'fields' => [
            'id_sbo' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
            'id_product' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
            'id_product_attribute' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
            'id_sbo_source' => ['type' => ServiceModel::TYPE_INT, 'required' => false],
            'reference' => ['type' => ServiceModel::TYPE_STRING, 'required' => false],
            'id_shop' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
            'type_sbo' => ['type' => ServiceModel::TYPE_STRING, 'required' => true],
            'is_locked' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
            'created_at' => ['type' => ServiceModel::TYPE_DATE, 'required' => true],
            'updated_at' => ['type' => ServiceModel::TYPE_DATE, 'required' => true],
        ],
    ];

    public static function createTablesIfNeeded()
    {
        $pdo = Db::getInstance()->getLink();
        $pdo->query(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'` (
                      `'.self::$definition['primary'].'` int(11) NOT NULL AUTO_INCREMENT,
                      `id_sbo` int(11) NULL DEFAULT NULL,
                      `id_product` int(11) NULL DEFAULT NULL,
                      `id_product_attribute` int(11) NULL DEFAULT NULL,
                      `id_sbo_source` int(11) NULL DEFAULT NULL COMMENT "id_sbo produit source pour les lots et references additionnelles",
                      `reference` VARCHAR(255) NULL DEFAULT NULL,
                      `id_shop` INT(11) NULL DEFAULT NULL,
                      `type_sbo` ENUM(\'product\',\'pack\',\'batch\',\'additional reference\') NULL,
                      `is_locked` int(1) NULL DEFAULT 1,
                      `created_at` datetime NOT NULL COMMENT "UTC",
                      `updated_at` datetime NOT NULL COMMENT "UTC",
                      PRIMARY KEY (`'.self::$definition['primary'].'`),
                      UNIQUE KEY `ps_product` (`id_product`,`id_product_attribute`,`id_shop`),
                      UNIQUE KEY `sbo_product` (`id_sbo`,`id_shop`),
                      INDEX `type_sbo` (`type_sbo`) USING BTREE,
                      INDEX `is_locked` (`is_locked`) USING BTREE,
                      INDEX `product_stats` (`is_locked`, `id_shop`,`id_product`,`id_product_attribute`,`id_sbo`, `type_sbo`) USING BTREE
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
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'`ADD COLUMN `reference` VARCHAR(50) NULL DEFAULT NULL AFTER `id_sbo_source`');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'`ADD COLUMN `id_shop` INT(11) NULL DEFAULT NULL AFTER `id_sbo_source`');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` DROP INDEX `sbo_product`;');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` DROP INDEX `ps_product`;');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` ADD CONSTRAINT `sbo_product` UNIQUE (`id_sbo`,`id_shop`)');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` ADD CONSTRAINT `ps_product` UNIQUE (`id_product`,`id_product_attribute`,`id_shop`)');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table']."` CHANGE COLUMN `type_sbo` `type_sbo` ENUM('product','pack','batch','additional reference') NULL COLLATE 'utf8_general_ci' AFTER `id_sbo_source`;");
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'`
	ADD INDEX `product_stats` (`is_locked`, `id_shop`,`id_product`,`id_product_attribute`,`id_sbo`, `type_sbo`);');
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
