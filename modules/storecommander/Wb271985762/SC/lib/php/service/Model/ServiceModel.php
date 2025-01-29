<?php

namespace Sc\Service\Model;

use Db;
use Sc\Service\Lib\Traits\ModelTrait;

class ServiceModel implements ServiceModelInterface
{
    use ModelTrait;

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
     * @var array<string,mixed>
     */
    public static $definition = [
        'table' => SC_DB_PREFIX.'service',
        'primary' => 'id_service',
        'fields' => [
            'name' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true],
            'version' => ['type' => self::TYPE_STRING, 'required' => true],
            'first_start' => ['type' => self::TYPE_INT, 'required' => true],
            'created_at' => ['type' => self::TYPE_DATE, 'required' => true, 'size' => 11],
        ],
    ];

    /**
     * @return void
     */
    public static function createTablesIfNeeded()
    {
        $pdo = \Db::getInstance()->getLink();
        $pdo->query('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'` (
                      `id_service` int(11) NOT NULL AUTO_INCREMENT,
                      `name` VARCHAR(255) NOT NULL,
                      `version` VARCHAR(255) NOT NULL,
                      `first_start` int(1) NOT NULL DEFAULT 1,
                      `created_at` datetime NOT NULL COMMENT "service creation date",
                      PRIMARY KEY (`id_service`),
                      UNIQUE KEY `name` (`name`)
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
                $stmtFields = Db::getInstance()->getLink()->query('SHOW COLUMNS FROM '._DB_PREFIX_.self::$definition['table'].';');
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
                // add fields
                if (!isset($existingFields['version']))
                {
                    Db::getInstance()->getLink()->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` ADD COLUMN `version` VARCHAR(255) NOT NULL DEFAULT 0 AFTER `name`');
                }
                if (!isset($existingFields['first_start']))
                {
                    Db::getInstance()->getLink()->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` ADD COLUMN `first_start` int(1) NOT NULL DEFAULT 1 AFTER `version`');
                }

                // remove unused fields
                if (isset($existingFields['active']))
                {
                    Db::getInstance()->getLink()->query('ALTER TABLE `'._DB_PREFIX_.self::$definition['table'].'` DROP COLUMN `active`');
                }
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
