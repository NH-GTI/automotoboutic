<?php

namespace InstanWeb\Module\NHBusinessCentral\Model;

use ObjectModel;
use Db;

class Transaction extends ObjectModel
{
    public const TABLE_NAME = 'nhbusinesscentral_transaction';
    public const ID_FIELD   = 'id_nhbusinesscentral_transaction';

    /** @var int ID */
    public $id;

    /** @var string Transaction */
    public $transaction;

    /** @var string Transaction Label */
    public $transaction_label;

    /** @var string Reference */
    public $reference = "";

    /** @var int Phase */
    public $phase = 0;

    /** @var string Phase Label */
    public $phase_label = "";

    /** @var int Process Offset */
    public $process_offset = 0;

    /** @var int Process Size */
    public $process_limit = 10;

    /** @var int itemCount */
    public $item_count = 0;

    /** @var int itemSuccessCount */
    public $item_success_count = 0;

    /** @var string Comment */
    public $comment = "";

    /** @var string Object creation date */
    public $date_add;

    /** @var string Object last modification date */
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => self::TABLE_NAME,
        'primary' => self::ID_FIELD,
        'multilang' => false,
        'multilang_shop' => false,
        'fields' => [
            'transaction' => ['type' => self::TYPE_STRING, 'required' => true],
            'transaction_label' => ['type' => self::TYPE_STRING, 'required' => true],
            'reference' => ['type' => self::TYPE_STRING, 'required' => true],
            'phase' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'phase_label' => ['type' => self::TYPE_STRING],
            'process_offset' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'process_limit' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'item_count' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'item_success_count' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'comment' => ['type' => self::TYPE_STRING],
            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
        ],
    ];

    public static function installDb()
    {
        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . self::TABLE_NAME . '` (
            `' . self::ID_FIELD . '` INT NOT NULL AUTO_INCREMENT,
            `transaction` VARCHAR(63) NOT NULL,
            `transaction_label` VARCHAR(63) NOT NULL,
            `reference` VARCHAR(255) NOT NULL,
            `phase` INT NOT NULL,
            `phase_label` VARCHAR(63) NOT NULL,
            `process_offset` INT NOT NULL,
            `process_limit` INT NOT NULL,
            `item_count` INT NOT NULL,
            `item_success_count` INT NOT NULL,
            `comment` LONGTEXT DEFAULT "",
            `date_add` DATETIME NOT NULL,
            `date_upd` DATETIME NOT NULL,
            PRIMARY KEY  (' . static::ID_FIELD . '),
            UNIQUE KEY `transaction_reference` ( `transaction`, `reference` )
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        $sql[] = '

            INSERT INTO `' . _DB_PREFIX_ . self::TABLE_NAME . '` (`transaction`, `transaction_label`, `reference`, `phase`, `phase_label`, `process_offset`, `process_limit`, `item_count`, `item_success_count`, `comment`, `date_add`, `date_upd`)
            VALUES ("order", "Commande", "", 0, "", 0, 1000, 0, 0, "", "' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') .'")
            ';
        
        foreach ($sql as $query) {
            if (Db::getInstance()->execute($query) == false) {
                return false;
            }
        }

        return true;
    }

    public static function unInstallDb()
    {
        $sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . self::TABLE_NAME . '`';

        foreach ($sql as $query) {
            if (Db::getInstance()->execute($query) == false) {
                return false;
            }
        }

        return true;
    }
}
