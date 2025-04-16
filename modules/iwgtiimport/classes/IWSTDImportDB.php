<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once 'IWSTDImportTools.php';

class IWSTDImportDB
{
    protected $id_import = 0;
    protected $id_header = 0;
    protected $id_detail = 0;
    protected $id_keyvalue = 0;

    public static function addMessage(
        $message,
        $code = 0,
        $id_import = 0,
        $id_header = 0,
        $id_detail = 0,
        $id_keyvalue = 0
    ) {
        $query = 'INSERT INTO ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() . '_message (
                `message`,
                `code`,
                `id_import`,
                `id_header`,
                `id_detail`,
                `id_keyvalue`)
            VALUES (' .
                    '"' . pSQL($message, true) . '", ' .
                    (int)$code . ', ' .
                    (int)$id_import . ', ' .
                    (int)$id_header . ', ' .
                    (int)$id_detail . ', ' .
                    (int)$id_keyvalue .
            ');';
        return (Db::getInstance()->Execute($query));
    }

    public function message($message, $code = 0)
    {
        $msg = (IWSTDImportTools::getValue('TEST_MODE')=='1') ? 'TEST ' . $message : $message;
        return self::addMessage($msg, $code, $this->id_import, $this->id_header, $this->id_detail, $this->id_keyvalue);
    }

    public function startTransaction()
    {
        Db::getInstance()->query('SET AUTOCOMMIT = 0');
        Db::getInstance()->query('START TRANSACTION');
    }

    public function commit()
    {
        Db::getInstance()->query('COMMIT');
        Db::getInstance()->query('SET AUTOCOMMIT = 1');
    }

    public function rollback()
    {
        Db::getInstance()->query('ROLLBACK');
        Db::getInstance()->query('SET AUTOCOMMIT = 1');
    }

    public function createImport($source)
    {
        $query = 'INSERT INTO ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() .
            '_import (source, status) VALUES ("' . $source . '", 0);';
        if (!Db::getInstance()->Execute($query)) {
            throw new Exception('Impossible de créer un enregistrement dans la table import', -1);
        }
        $query = 'SELECT id_import FROM ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() .
            '_import ORDER BY id_import desc';
        $this->id_import = Db::getInstance()->getValue($query);
        return $this->id_import;
    }

    public function createHeader($input_name, $name)
    {
        $query = 'INSERT INTO ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() . '_header (
            `id_import`,
            `input_name`,
            `name`,
            `status`)
        VALUES (' .
            (int)$this->id_import . ', ' .
            '"' . pSQL($input_name, true) . '", ' .
            '"' . pSQL($name, true) . '", ' .
            '0);';
        if (!Db::getInstance()->Execute($query)) {
            throw new Exception('Impossible de créer un enregistrement dans la table header', -1);
        }
        $query = 'SELECT id_header FROM ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() .
            '_header ORDER BY id_header desc';
        $this->id_header = Db::getInstance()->getValue($query);
        return $this->id_header;
    }

    public function createDetail()
    {
        $query = 'INSERT INTO ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() . '_detail (
            `id_header`,
            `status`)
        VALUES (' .
            (int)$this->id_header . ', ' .
            '0 ' .
            ');';
        if (!Db::getInstance()->Execute($query)) {
            throw new Exception('Impossible de créer un enregistrement dans la table detail', -1);
        }
        $query = 'SELECT id_detail FROM ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() .
            '_detail ORDER BY id_detail desc';
        $this->id_detail = Db::getInstance()->getValue($query);
        return $this->id_detail;
    }

    public function createKeyValue($key, $value)
    {
        $query = 'INSERT INTO ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() . '_keyvalue (
            `id_detail`,
            `key`,
            `value`,
            `status`)
        VALUES (' .
            (int)$this->id_detail . ', ' .
            '"' . pSQL($key, true) . '", ' .
            '"' . pSQL($value, true) . '", ' .
            '0 ' .
            ');';
        if (!Db::getInstance()->Execute($query)) {
            throw new Exception('Impossible de créer un enregistrement dans la table keyvalue', -1);
        }
    }

    public function getHashValues($id_detail = null)
    {
        $id = (isset($id_detail)) ? (int)$id_detail : $this->id_detail;
        $result = array();
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() . '_keyvalue where id_detail=' . $id;
        if ($rows = Db::getInstance()->ExecuteS($query)) {
            foreach ($rows as $row) {
                $result[$row['key']] = $row['value'];
            }
        }
        return $result;
    }

    public function getAlternateValue($name, $key, $alternate_key, $value)
    {
        $query = 'SELECT `value` FROM `' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() . '_keyvalue` WHERE `key` = "'.
                 $alternate_key . '" and `id_detail` in ('.
                 'SELECT `id_detail` FROM `' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() . '_keyvalue` WHERE `key` = "'.
                 $key . '" and `value` = "'. $value .'" and `id_detail` in ('.
                 'SELECT `id_detail` FROM `' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() . '_header` WHERE `id_import` in ('.
                 'SELECT `id_import` FROM `' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() . '_import` WHERE `name` = "' . $name .
                 '" and `status` = 0)))';
        if ($rows = Db::getInstance()->ExecuteS($query)) {
            foreach ($rows as $row) {
                return $row['value'];
            }
        }
        return false;
    }

    public function getAllHeaders()
    {
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() . '_header where status = 0';
        $query .= ' ORDER BY id_header asc';
        $row = Db::getInstance()->executeS($query);
        if ($row) {
            $result = [];
            foreach($row as $r) {
                $result[(int)$r['id_header']] = array('name' => $r['name'], 'input_name' => $r['input_name']);
            }
            return $result;
        }
        return false;
    }

    public function findHeader($name = null)
    {
        $this->id_header = null;
        $this->id_detail = null;
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() . '_header where status = 0';
        if (isset($name)) {
            if (strpos($name, '%') !== false) {
                $query .= ' and name like "' . $name . '"';
            } else {
                $query .= ' and name ="' . $name . '"';
            }
        }
        $query .= ' ORDER BY id_header asc';
        $row = Db::getInstance()->getRow($query);
        if ($row) {
            $this->id_header = $row['id_header'];
            return array('name' => $row['name'], 'input_name' => $row['input_name']);
        }
        return false;
    }

    public function findDetail()
    {
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() .
            '_detail where id_header=' . $this->id_header . ' and status = 0 ORDER BY id_detail asc';
        $row = Db::getInstance()->getRow($query);
        if ($row) {
            $this->id_detail = $row['id_detail'];
            return true;
        }
        return false;
    }

    public function updateDetailStatus($status)
    {
        $query = 'update ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() .
            '_detail set status = ' . $status . ' where id_detail = ' . $this->id_detail;
        if (!Db::getInstance()->Execute($query)) {
            throw new Exception('Impossible d\'écrire l\'état dans la table detail', -1);
        }
    }

    public function updateHeaderStatus($status)
    {
        $query = 'update ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() .
            '_header set status = ' . $status . ' where id_header = ' . $this->id_header;
        if (!Db::getInstance()->Execute($query)) {
            throw new Exception('Impossible d\'écrire l\'état dans la table header', -1);
        }
    }

    public static function updateAllHeaderStatusFromImport($ids_import)
    {
        if (count($ids_import)) {
            $query = 'update ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() .
                    '_header set status = 1 where status = 0 and id_import in (' . implode(',', $ids_import) . ')';
            Db::getInstance()->Execute($query);
        }
    }

    public static function getCurrentTimestamp()
    {
        $query = 'SELECT current_timestamp from dual';
        return Db::getInstance()->getValue($query);
    }

    public static function getLastMessages($timestamp)
    {
        $result = array();
        $msg_success = IWSTDImportTools::getValue('MSG_SUCCESS', true);
        $msg_success_detail = IWSTDImportTools::getValue('MSG_SUCCESS_DETAIL', true);
        $msg_success_no_item = IWSTDImportTools::getValue('MSG_SUCCESS_NO_ITEM', true);
        $exist_items = Db::getInstance()->getValue(
            'SELECT count(*) FROM ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() .
            '_message where created >= "' . $timestamp . '" ' .
            'and code < 1 and id_import > 0'
        );
        $is_errors = Db::getInstance()->getValue(
            'SELECT count(*) FROM ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() .
            '_message where created >= "' . $timestamp . '" ' .
            'and code < 0'
        );
        if ($msg_success_no_item == false && $exist_items == 0 && $is_errors == 0) {
            return array();
        }
        if ($msg_success == false && $exist_items > 0 && $is_errors == 0) {
            return array();
        }
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() .
            '_message where created >= "' . $timestamp . '" ' .
            'and (' .
            'code = 1 or code = -1'.
            ($msg_success_detail ? ' or code = 0 ' : ' ').
            ' or id_import = 0 '.
            ') ' .
            'order by id_message asc';
        if ($rows = Db::getInstance()->ExecuteS($query)) {
            foreach ($rows as $row) {
                $result[] = $row['message'];
            }
        }
        return $result;
    }

    public static function dataWiping($delay)
    {
        $query_import = 'SELECT id_import FROM ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() .
            '_import WHERE CREATED < ( select TIMESTAMPADD(SECOND,  -' . (int)$delay . ', NOW() ) from dual)';
        $query_header = 'SELECT id_header FROM ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() .
            '_header WHERE id_import in (' . $query_import . ')';
        $query_detail = 'SELECT id_detail FROM ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() .
            '_detail WHERE id_header in (' . $query_header . ')';
        $query_keyvalue = 'SELECT id_keyvalue FROM ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() .
            '_keyvalue WHERE id_detail in (' . $query_detail . ')';
        $delete_queries = array(
                str_replace(array('SELECT id_keyvalue'), array('DELETE'), $query_keyvalue),
                str_replace(array('SELECT id_detail'), array('DELETE'), $query_detail),
                str_replace(array('SELECT id_header'), array('DELETE'), $query_header),
                str_replace(array('SELECT id_import'), array('DELETE'), $query_import),
                'DELETE FROM ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() .
                    '_message WHERE CREATED < ( select TIMESTAMPADD(SECOND,  -' . (int)$delay . ', NOW() ) from dual)'
            );
        foreach ($delete_queries as $query) {
            Db::getInstance()->Execute($query);
        }
        return true;
    }
}
