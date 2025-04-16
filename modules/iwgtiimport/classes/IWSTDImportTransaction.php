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
require_once 'IWSTDImportDB.php';

class IWSTDImportTransaction
{
    protected $db = null;
    protected $name = null;
    protected $header_filter = null;
    protected $values = null;
    protected $header = null;
    protected $count_detail = 0;
    protected $count_error_detail = 0;
    protected $count_header_detail = 0;
    protected $count_header_error_detail = 0;
    protected $count_header = 0;
    protected $warnings = array();
    protected $errors = array();

    public function __construct($db, $name, $header_filter)
    {
        $this->db = $db;
        $this->name = $name;
        $this->header_filter = $header_filter;
    }

    public function nextHeader()
    {
        return $this->db->findHeader($this->header_filter);
    }

    public function import()
    {
        $mem_display_errors = ini_get('display_errors');
        $mem_memory_limit = ini_get('memory_limit');
        ini_set('display_errors', false);
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 3600);
        $this->count_detail = 0;
        $this->count_error_detail = 0;
        $this->count_header = 0;
        $this->beforeImport();

        while ($this->header = $this->nextHeader()) {
            $this->count_header += 1;
            $this->count_header_detail = 0;
            $this->count_header_error_detail = 0;
            $header_status = 1;
            while ($this->db->findDetail()) {
                $detail_status = 1;
                $this->db->startTransaction();
                try {
                    $this->prepareValues();
                    if (!$this->importDetail()) {
                        throw new Exception('Impossible de mettre à jour la base de données PrestaShop', -1);
                    }
                    if (IWSTDImportTools::getValue('TEST_MODE') != '1') {
                        $this->db->commit();
                    } else {
                        $this->db->rollback();
                    }
                    $this->count_detail += 1;
                    $this->count_header_detail += 1;
                    $this->addDetailMessage();
                } catch (Exception $e) {
                    $this->db->rollback();
                    $this->count_error_detail += 1;
                    $this->count_header_error_detail += 1;
                    $header_status = -1;
                    $detail_status = -1;
                    $this->addDetailErrorMessage($e);
                }
                $this->db->updateDetailStatus($detail_status);
            }
            $this->db->updateHeaderStatus($header_status);
            $this->addHeaderMessage();
        }
        ini_set('display_errors', $mem_display_errors);
        ini_set('memory_limit', $mem_memory_limit);
        $this->afterImport();
    }

    protected function beforeImport()
    {
    }

    protected function afterImport()
    {
    }    

    public function getProperties()
    {
        return array(
            'name' => $this->name,
            'count_header' => $this->count_header,
            'count_detail' => $this->count_detail,
            'count_error_detail' => $this->count_error_detail,
        );
    }

    protected function prepareValues()
    {
        $this->values = array();
        $this->values[$this->name] = $this->db->getHashValues();
    }

    protected function getAlternateValue($name, $key, $alternate_key, $value, $default=null)
    {
        $alternate_value = $this->db->getAlternateValue($name, $key, $alternate_key, $value);
        if ($alternate_value === false) {
            if (isset($default)) {
                return $default;
            }
            return $value;
        }
        return $alternate_value;
    }

    protected function importDetail()
    {
        return true;
    }

    protected function filterDetailMessage()
    {
        return null;
    }

    protected function addDetailMessage()
    {
        $msg = 'Succès';
        if (isset($this->header)) {
            $msg .= ' ' . sprintf('avec %1$s', $this->header['input_name']);
        }
        $strvalues = $this->valuesToString($this->filterDetailMessage());
        if (Tools::strlen($strvalues)) {
            $msg .= ' ' . $strvalues;
        }
        return $this->db->message($msg);
    }

    protected function addDetailErrorMessage($e)
    {
        $msg = $e->getMessage();
        if (isset($this->header)) {
            $msg .= ' ' . sprintf('avec %1$s', $this->header['input_name']);
        }
        $strvalues = $this->valuesToString();
        if (Tools::strlen($strvalues)) {
            $msg .= ' ' . $strvalues;
        }
        $this->db->message($msg, $e->getCode());
    }

    protected function addHeaderMessage()
    {
        $msg = 'Terminé';
        if (isset($this->header)) {
            $msg .= ' ' . sprintf('avec %1$s', $this->header['input_name']);
        }
        $msg .= ' ';
        $msg .= ($this->count_header_detail > 1) ? sprintf('%1$d enregistrements réussis', $this->count_header_detail) :
                                               sprintf('%1$d enregistrement réussi', $this->count_header_detail);
        if ($this->count_header_error_detail) {
            $msg .= ' ';
            $msg .= ($this->count_header_error_detail > 1) ?
                sprintf('et %1$d erreurs', $this->count_header_error_detail) :
                sprintf('et %1$d erreur', $this->count_header_error_detail);
        }
        $this->db->message($msg, 1);
        return true;
    }

    protected function convertKeys($values_array, $keys, $separator = ',')
    {
        // result is an array of values compatible with import model (see convertTab of IWSTDImportModel)
        $result = array();
        reset($keys);
        while (key($keys) !== null) {
            $key = $keys[key($keys)];
            if ($key !== null) {
                // echo var_export($values_array,true);
                // echo var_export($keys,true);
                $list_key = explode(',', $key); // key is an array of separated comma values
                $r = array();
                foreach ($list_key as $keydefault) {
                    // split key and default value (preceeded by ::)
                    $sep = strpos($keydefault, '::');
                    $default = ($sep === false) ? '' : Tools::substr($keydefault, $sep + 2);
                    $new_key = ($sep === false) ? $keydefault : Tools::substr($keydefault, 0, $sep);
                    $v = '';
                    foreach ($values_array as $values) {
                        if (array_key_exists($new_key, $values)) {
                            $v = $values[$new_key];
                            break;
                        }
                    }
                    if (Tools::strlen($v) == 0) {
                        $v = $default;
                    }
                    if (Tools::strlen($v)) {
                        $r[] = $v;
                    }
                }
                $result[] = count($r) ? implode($separator, $r) : '';
            } else { // key is null
                $result[] = null;
            }
            next($keys);
        }
        return $result;
    }

    protected function valuesToCSVFile($values, $filename = null)
    {
        if (!isset($filename)) {
            $filename = AdminImportController::getPath(
                (string)(preg_replace('/\.{2,}/', '.', IWSTDImportTools::getModuleName() . '.csv'))
            );
        }
        if (file_exists($filename)) {
            unlink($filename);
        }
        $fp = fopen($filename, 'w');
        foreach ($values as &$v) {
            if ($v == null) {
                $v = '';
            }
        }
        fputcsv($fp, $values, ';');
        fclose($fp);
    }

    public function prepareCSVFileAndParams($values_array, $fields, $params)
    {
        $csv_values = $this->convertKeys($values_array, $fields);
        $this->valuesToCSVFile($csv_values);
        if (!array_key_exists('skip', $params)) {
            $params['skip'] = 0;
        }
        if (!array_key_exists('csv', $params)) {
            $params['csv'] = IWSTDImportTools::getModuleName() . '.csv';
        }
        if (!array_key_exists('iso_lang', $params)) {
            $params['iso_lang'] = 'fr';
        }
        if (!array_key_exists('convert', $params)) {
            $params['convert'] = false;
        }
        if (!array_key_exists('forceIDs', $params)) {
            $params['forceIDs'] = true;
        }
        if (!array_key_exists('match_ref', $params)) {
            $params['match_ref'] = true;
        }
        if (!array_key_exists('regenerate', $params)) {
            $params['regenerate'] = false;
        }
        if (!array_key_exists('import', $params)) {
            $params['import'] = '1';
        }
        foreach ($params as $p => $v) {
            $_POST[$p] = $v;
        }
    }

    protected function createModelController()
    {
        return new IWSTDImportModel();
    }

    protected function afterControllerImport($params)
    {
        return (isset($params));
    }

    protected function getSQLFieldsValues($values, $fields, $params)
    {
        if (isset($params)) {
            if (isset($params['convertID'])) {
                $convertID = $params['convertID'];
            }
        };
        $fv = array();
        reset($fields);
        foreach ($values as $v) {
            $k = key($fields);
            $fv[$k] = $v;
            next($fields);
        }
        if (isset($convertID)) {
            foreach($fv as $k => &$v) {
                if (isset($convertID[$k])) {
                    $sql = 'SELECT ' . $k . ' FROM ' . $convertID[$k]['table'] . ' WHERE ' . $convertID[$k]['label'] . ' = "' . $v . '"';
                    if (isset($convertID[$k]['filter'])) {
                        $filters = explode(',', $convertID[$k]['filter']);
                        foreach($filters as $f) {
                            $sql .= ' AND (' . $f . '= "' . $fv[$f] . '") ';
                        }
                    }
                    if ($vID = Db::getInstance()->getValue($sql)) {
                        $v = $vID;
                    } else {
                        $v = '';
                    }
                }
            }
        }
        return $fv;
    }

    protected function getSQLValue($value)
    {
        $v = isset($value) ? pSQL($value, true) : '';
        return '"' . $v . '"';
    }

    protected function getSQLWhere($sql_fields_values, $keys, $fields_lang, $params_lang)
    {
        if (!isset($keys)) {
            return '';
        }
        if (Tools::strlen($keys) == 0) {
            return '';
        }
        $keylist = explode(',', $keys);
        $keylistlang = $params_lang && isset($params_lang['key']) ? $params_lang['key'] : false;
        $z = array();
        foreach ($sql_fields_values as $k => $v) {
            if (in_array($k, $keylist)) {
                if ($params_lang && $keylistlang && 
                    is_array($keylistlang) && array_key_exists($k, $keylistlang) && 
                    array_key_exists('table', $params_lang)) {
                    array_push($z, $k . ' IN (SELECT ' . $k . ' FROM '. $params_lang['table'] . ' WHERE ' . $keylistlang[$k] . ' = ' . $this->getSQLValue($v) .')');
                } else {
                    array_push($z, $k . '=' . $this->getSQLValue($v));
                }
            }
        }
        $where = implode(' and ', $z);
        return $where;
    }

    protected function getSQLInsertFields($sql_fields_values)
    {
        $sqlfields = array();
        foreach ($sql_fields_values as $k => $v) {
            if (isset($v)) {
                array_push($sqlfields, $k);
            }
        }
        return implode(',', $sqlfields);
    }

    protected function getSQLInsertValues($sql_fields_values)
    {
        $sqlvalues = array();
        foreach ($sql_fields_values as $v) {
            if (isset($v)) {
                array_push($sqlvalues, $this->getSQLValue($v));
            }
        }
        return implode(',', $sqlvalues);
    }

    protected function getSQLUpdateFieldsValues($sql_fields_values)
    {
        $sqlvalues = array();
        foreach ($sql_fields_values as $k => $v) {
            if (isset($v)) {
                array_push($sqlvalues, $k . '=' . $this->getSQLValue($v));
            }
        }
        return implode(', ', $sqlvalues);
    }

    protected function getSQLInsert($table, $sql_fields_values)
    {
        $sqlfields = $this->getSQLInsertFields($sql_fields_values);
        $sqlvalues = $this->getSQLInsertValues($sql_fields_values);
        return 'insert into ' . $table . ' (' . $sqlfields . ') values (' . $sqlvalues . ')';
    }

    protected function getSQLUpdate($table, $sql_fields_values, $key)
    {
        $sqlfieldsvalues = $this->getSQLUpdateFieldsValues($sql_fields_values);
        return 'update ' . $table . ' set ' . $sqlfieldsvalues .
            ' where  ' . $this->getSQLWhere($sql_fields_values, $key, false, false);
    }

    protected function getSQLDelete($table, $sql_fields_values, $key)
    {
        return 'delete from ' . $table . ' where  ' . $this->getSQLWhere($sql_fields_values, $key, false, false);
    }

    protected function isSQLConditionEnabled($condition, $sql_fields_values)
    {
        $enabled = false;
        if (is_bool($condition) && $condition == true) {
            $enabled = true;
        } elseif (is_string($condition)) {
            $cc = explode(',', $condition);
            $test = true;
            foreach ($cc as $c) {
                foreach ($sql_fields_values as $k => $v) {
                    if ($k == $c) {
                        if (isset($v)) {
                            $test = $test && (Tools::strlen($v) > 0);
                        } else {
                            $test = false;
                        }
                    }
                }
            }
            $enabled = $test;
        }
        return $enabled;
    }

    public function verifyExportCSV($values, $fields, $params)
    {
        return true;
    }

    public function exportCSV($values_array, $fields, $params)
    {
        $filename = $params['exportcsv'];
        $separator = $params['separatorcsv'];
        $values = $this->convertKeys($values_array, $fields);
        if (!file_exists($filename)) {
            $fp = fopen($filename, 'a');
            fputcsv($fp, array_keys($fields), $separator);
            fclose($fp);    
        }
        if ($this->verifyExportCSV($values, $fields, $params)) {
            foreach ($values as &$v) {
                if ($v == null) {
                    $v = '';
                }
            }
            $fp = fopen($filename, 'a');
            fputcsv($fp, $values, $separator);
            fclose($fp);
        }
    }   

    public function updateTable($values_array, $fields, $params, $fields_lang = false, $params_lang = false)
    {
        $table = $params['table'];
        $values = $this->convertKeys($values_array, $fields);
        $sql_fields_values = $this->getSQLFieldsValues($values, $fields, $params);
        if ($fields_lang && $params_lang) {
            $values_lang = $this->convertKeys($values_array, $fields_lang);
            $sql_fields_values_lang = $this->getSQLFieldsValues($values_lang, $fields_lang, $params_lang);
        }
        $exist = 0;
        if ($fields_lang && $params_lang && array_key_exists('key', $params_lang) && $this->isSQLConditionEnabled($params_lang['key'], $sql_fields_values_lang)) {
            $exist = -1;
            $query = 'select * from ' . $params_lang['table'] . ' where ' . $this->getSQLWhere($sql_fields_values_lang, $params_lang['key'], $fields_lang, $params_lang);
            if (array_key_exists('filter_params_select', $params_lang)) {
                foreach($params_lang['filter_params_select'] as $k => $f) {
                    if (isset($sql_fields_values[$f])) {
                        $query .= ' AND (' . $k . ' IN (SELECT ' . $k . ' FROM ' . $table . ' WHERE ' . $f . ' = "' . $sql_fields_values[$f] . '"))';
                    }
                }
            }
            $exist_row = Db::getInstance()->getRow($query);
            if ($exist_row) {
                $first_key = explode(',', $params['key']);
                $first_key = array_shift($first_key);
                $sql_fields_values[$first_key] = $exist_row[$first_key];
                $exist = 1;
            }
        } elseif (array_key_exists('key', $params) && $this->isSQLConditionEnabled($params['key'], $sql_fields_values)) {
            $exist = -1;
            $query = 'select * from ' . $table . ' where ' . $this->getSQLWhere($sql_fields_values, $params['key'], $fields_lang, $params_lang);
            $exist_row = Db::getInstance()->getRow($query);
            if ($exist_row) {
                $exist = 1;
            }
        }
        $query = null;
        $insertQuery = false;
        $updateQuery = false;
        unset($this->last_Insert_ID);
        if (($exist == 0 || $exist == -1) && array_key_exists('insert', $params) &&
            $this->isSQLConditionEnabled($params['insert'], $sql_fields_values)) {                
            $query = $this->getSQLInsert($table, $sql_fields_values);
            $insertQuery = true;
        }
        if ($exist == 1 && array_key_exists('update', $params) &&
            $this->isSQLConditionEnabled($params['update'], $sql_fields_values)) {
            $query = $this->getSQLUpdate($table, $sql_fields_values, $params['key']);
            $updateQuery = true;
        }
        if ($exist == 1 && array_key_exists('delete', $params) &&
            $this->isSQLConditionEnabled($params['delete'], $sql_fields_values)) {
            $query = $this->getSQLDelete($table, $sql_fields_values, $params['key']);
        }
        if (isset($query)) {
            if (!Db::getInstance()->Execute($query)) {
                throw new Exception(sprintf('erreur avec %1$s', $query), -1);
            }
            if ($insertQuery) {
                $this->last_Insert_ID = Db::getInstance()->Insert_ID();
            } elseif ($updateQuery && isset($params['primary'])) {
                $this->last_Insert_ID = Db::getInstance()->getValue('SELECT '.$params['primary'].' FROM '.$table.' WHERE ' . $this->getSQLWhere($sql_fields_values, $params['key'], false, false));
            }
            if ($fields_lang && $params_lang && isset($params['key'])) {
                $first_key = explode(',', $params['key']);
                $first_key = array_shift($first_key);
                if ($exist == 0 || $exist == -1) {
                    $fields_lang[$first_key] = '::'.Db::getInstance()->Insert_ID();
                } else {
                    $fields_lang[$first_key] = '::'.$exist_row[$first_key];
                }
                $params_lang['key'] .= ','.$first_key;
                $this->updateTable($values_array, $fields_lang, $params_lang);
            }
        }
        return true;
    }

    public function controllerImport($params)
    {
        $this->errors = array();
        $this->warnings = array();
        try {
            foreach ($params as $p) {
                $pe = $p['params'];
                if (array_key_exists('entity', $pe)) {
                    // prepare csv file
                    $this->prepareCSVFileAndParams($p['values'], $p['fields'], $p['params']);
                    // exec import
                    $controller = $this->createModelController();
                    $controller->postProcess();
                    $this->warnings = array_merge($this->warnings, $controller->warnings);
                    $this->errors = array_merge($this->errors, $controller->errors);
                } elseif (array_key_exists('table', $pe)) {
                    $this->updateTable($p['values'], $p['fields'], $p['params'], isset($p['fields_lang']) ? $p['fields_lang'] : false, isset($p['params_lang']) ? $p['params_lang'] : false);
                } elseif (array_key_exists('exportcsv', $pe)) {
                    $this->exportCSV($p['values'], $p['fields'], $p['params']);
                }
            }
            $this->afterControllerImport($params);
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            throw $e;
        } finally {
            if (IWSTDImportTools::getValue('TEST_MODE') != '1') {
                $filename = AdminImportController::getPath(
                    (string)(preg_replace('/\.{2,}/', '.', IWSTDImportTools::getModuleName() . '.csv'))
                );
                if (file_exists($filename)) {
                    unlink($filename);
                }
            }
            $errorMessage = '';
            foreach ($this->errors as $err) {
                $errorMessage .= $err . '\n';
            }
            if (count($this->errors)) {
                foreach ($this->warnings as $warn) {
                    $errorMessage .= $warn . '\n';
                }
                throw new Exception($errorMessage, -1);
            }
        }
/*        foreach ($this->warnings as $warn) {
            IWSTDImportDB::addMessage($warn);
        } */
        return true;
    }

    public function valuesToString($names=null)
    {
        $without_names = false;
        $result = '';
        if (isset($this->values)) {
            foreach ($this->values as $name => $val) {
                if (isset($name) && Tools::strlen($name)) {
                    $result .= '[' . $name . ']';
                }
                $result .= ' : ';
                $r = '';
                foreach ($val as $k => $v) {
                    if (isset($names) && !in_array($k, $names)) {
                        continue;
                    }                        
                    if (Tools::strlen($r)) {
                        $r .= ' ';
                    }
                    $r .= $k . '="' . $v . '"';
                }
                if (strlen($r)==0 && isset($names)) {
                    $without_names = true;
                }
                $result .= $r;
            }
        }
        if ($without_names) {
            return $this->valuesToString();
        }
        return $result;
    }
}
