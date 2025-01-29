<?php

namespace Sc\Service\Shippingbo\Model;

use Db;
use Sc\Service\Lib\Traits\ModelTrait;
use Sc\Service\Model\ServiceModel;
use Sc\Service\Model\ServiceModelInterface;

class AdditionalRefsModel implements ServiceModelInterface
{
    use ModelTrait;

    public static $definition = [
        'table' => SC_DB_PREFIX.'service_shippingbo_additional_refs_buffer',
        'primary' => 'id_'.SC_DB_PREFIX.'service_shippingbo_additional_refs_buffer',
        'fields' => [
            'id' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
            'id_sbo_account' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
            'order_item_field' => ['type' => ServiceModel::TYPE_STRING],
            'product_field' => ['type' => ServiceModel::TYPE_STRING],
            'order_item_value' => ['type' => ServiceModel::TYPE_STRING],
            'product_value' => ['type' => ServiceModel::TYPE_INT],
            'matched_quantity' => ['type' => ServiceModel::TYPE_STRING],
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
                      `order_item_field` VARCHAR(255) NOT NULL,
                      `product_field` VARCHAR(255) NOT NULL,
                      `order_item_value` VARCHAR(255) NOT NULL,
                      `product_value` INT(11) NOT NULL,
                      `matched_quantity` VARCHAR(255) NOT NULL,
                      `created_at` datetime NOT NULL COMMENT "UTC",
                      `updated_at` datetime NOT NULL COMMENT "UTC",
                      `synced_at` datetime NOT NULL COMMENT "UTC date row insertion",
                      PRIMARY KEY (`'.self::$definition['primary'].'`),
                      UNIQUE KEY `order_item_value` (`order_item_value`,`id_sbo_account`),
                      INDEX `id` (`id`) USING BTREE,
                      INDEX `product_value` (`product_value`) USING BTREE,
                      INDEX `matched_quantity` (`matched_quantity`) USING BTREE,
                      INDEX `synced_at` (`synced_at`) USING BTREE,
                      INDEX `product_stats` (`id`, `id_sbo_account`) USING BTREE
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
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` DROP INDEX `order_item_value`;');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` ADD CONSTRAINT `order_item_value` UNIQUE (`order_item_value`,`id_sbo_account`)');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` ADD INDEX `product_stats` (`id`, `id_sbo_account`);');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'`
	CHANGE COLUMN `product_value` `product_value` INT NOT NULL DEFAULT 0 AFTER `order_item_value`;');
            }
        }
    }
}
