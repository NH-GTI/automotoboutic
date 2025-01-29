<?php

namespace InstanWeb\Module\NHBusinessCentral\Model;

use ObjectModel;
use Db;

class TransactionDetail extends ObjectModel
{
    public const TABLE_NAME = 'nhbusinesscentral_transaction_detail';
    public const ID_FIELD   = 'id_nhbusinesscentral_transaction_detail';
    public const ID_PARENT_FIELD = 'id_nhbusinesscentral_transaction';

    /** @var int ID */
    public $id;

    /** @var int ID Parent */
    public $id_nhbusinesscentral_transaction;

    /** @var string Item */
    public $item;

    /** @var string Item ID */
    public $id_item;

    /** @var int status */
    public $status = 0;

    /** @var int retry */
    public $retry = 1;

    /** @var string data */
    public $data = '';

    /** @var string comment */
    public $comment = '';

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
            self::ID_PARENT_FIELD => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'item' => ['type' => self::TYPE_STRING, 'required' => true],
            'id_item' => ['type' => self::TYPE_STRING, 'required' => true],
            'status' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'retry' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'data' => ['type' => self::TYPE_STRING],
            'comment' => ['type' => self::TYPE_STRING],
            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
        ],
    ];

    public static function installDb()
    {
        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . self::TABLE_NAME . '` (
            `' . self::ID_FIELD . '` INT NOT NULL AUTO_INCREMENT,
            `' . self::ID_PARENT_FIELD . '` INT NOT NULL,
            `item` VARCHAR(64) NOT NULL,
            `id_item` VARCHAR(64) NOT NULL,
            `status` TINYINT(1) unsigned NOT NULL DEFAULT "0",
            `retry` SMALLINT unsigned NOT NULL DEFAULT "0",
            `data` LONGTEXT NOT NULL DEFAULT "" ,
            `comment` LONGTEXT NOT NULL DEFAULT "" ,
            `date_add` DATETIME NOT NULL,
            `date_upd` DATETIME NOT NULL,
            PRIMARY KEY  ('.self::ID_FIELD.'),
            UNIQUE KEY `parent_item_id_item` (
                ' . self::ID_PARENT_FIELD . ', `item`, `id_item`
            ),
            KEY `date_add_status_retry` (`date_add`, `status`, `retry`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';
        
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
