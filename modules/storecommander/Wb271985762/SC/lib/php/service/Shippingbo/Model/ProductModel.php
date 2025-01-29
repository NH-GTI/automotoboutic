<?php

namespace Sc\Service\Shippingbo\Model;

use Db;
use Sc\Service\Lib\Traits\ModelTrait;
use Sc\Service\Model\ServiceModel;
use Sc\Service\Model\ServiceModelInterface;

class ProductModel implements ServiceModelInterface
{
    use ModelTrait;

    public static $definition = [
        'table' => SC_DB_PREFIX.'service_shippingbo_product_buffer',
        'primary' => 'id_'.SC_DB_PREFIX.'service_shippingbo_product_buffer',
        'fields' => [
            'id' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
            'id_sbo_account' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
            'user_ref' => ['type' => ServiceModel::TYPE_STRING],
            'is_pack' => ['type' => ServiceModel::TYPE_INT],
            'title' => ['type' => ServiceModel::TYPE_STRING],
            'location' => ['type' => ServiceModel::TYPE_STRING],
            'weight' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
            'height' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
            'length' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
            'width' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
            'stock' => ['type' => ServiceModel::TYPE_INT, 'required' => true],
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

    /**
     * @return void
     */
    public static function createTablesIfNeeded()
    {
        $pdo = Db::getInstance()->getLink();
        $pdo->query(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'` (
                      `'.self::$definition['primary'].'` int(11) NOT NULL AUTO_INCREMENT,
                      `id` int(11) NOT NULL,
                      `id_sbo_account` INT(11) NOT NULL DEFAULT 1,
                      `user_ref` VARCHAR(255) NOT NULL,
                      `is_pack` INT(1) NOT NULL,
                      `title` VARCHAR(255) NULL DEFAULT NULL,
                      `location` VARCHAR(255) NULL DEFAULT NULL,
                      `weight` INT(11) DEFAULT NULL,
                      `height` INT(11) DEFAULT NULL,
                      `length` INT(11) DEFAULT NULL,
                      `width` INT(11) DEFAULT NULL,
                      `stock` INT(11) DEFAULT NULL,
                      `updated_at` datetime NOT NULL COMMENT "UTC",
                      `synced_at` datetime NOT NULL COMMENT "UTC date row insertion",
                    PRIMARY KEY (`'.self::$definition['primary'].'`),
                    UNIQUE INDEX `id` (`id`,`id_sbo_account`),
                    INDEX `updated_at` (`updated_at`) USING BTREE,
                    INDEX `synced_at` (`synced_at`) USING BTREE,
                    INDEX `is_pack` (`is_pack`) USING BTREE,
                    INDEX `product_stats` (`id`, `id_sbo_account`) USING BTREE
                    ) ENGINE=InnoDb DEFAULT CHARACTER SET utf8 COLLATE=utf8_general_ci;'
        );
    }

    /**
     * @param string $currentVersion
     * @param string $targetVersion
     *
     * @return void
     */
    public static function migrateDb($currentVersion, $targetVersion)
    {
        $pdo = Db::getInstance()->getLink();
        if (version_compare($currentVersion, '0', '!=') && version_compare($currentVersion, $targetVersion, '<'))
        {
            if (version_compare($currentVersion, '1.0.1', '<'))
            {
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` DROP INDEX `id`;');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'`ADD COLUMN `id_sbo_account` INT(11) NOT NULL DEFAULT 1 AFTER `id`');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` ADD CONSTRAINT `id` UNIQUE (`id`,`id_sbo_account`)');
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` ADD INDEX `product_stats` (`id`, `id_sbo_account`);');
            }
            if (version_compare($currentVersion, '1.0.5', '<'))
            {
                $pdo->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'`ADD COLUMN `location` VARCHAR(255) NULL DEFAULT NULL AFTER `title`');
            }
        }
    }
}
