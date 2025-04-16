<?php
/**
* IW 2021
*
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class IWGTIImportData
{
    public function __construct($db, $export_type)
    {
        $this->db = $db;
        $this->export_type = $export_type;
    }

    public function beforeImport()
    {
    }
    
    public function afterImport()
    {
    }

    public function nextHeader()
    {
        // tant qu'on a des éléments dans config
        if (count($this->config_items)) {
            $item = array_shift($this->config_items);
            $this->current_config = $item;
            $query = 'update ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() . '_header set status = 0';
            Db::getInstance()->Execute($query);
            $query = 'update ' . _DB_PREFIX_ . IWSTDImportTools::getModuleName() . '_detail set status = 0';
            Db::getInstance()->Execute($query);
            $header = $this->db->findHeader($item['header']);
            return $header;
        }
        return false;
    }

    public function prepareValues($name, $values)
    {
        $this->name = $name;
        $this->values[$name] = $values;
        $this->empty = true;
        foreach ($this->values[$this->name] as $key => &$value) {
            $value = trim($value);
            $this->values[$this->name][$key] = $value;
            if ($this->empty && strlen($value)) {
                if ((substr($value, 0, 2) != '::') || (method_exists($this, $value == false))) {
                    $this->empty = false;
                }
            }
        }

        if (!$this->empty) {
            foreach ($this->current_config['fields'] as $f => $v) {
                if (method_exists($this, $v)) {
                    $this->values[$this->name][$v] = call_user_func(array($this, $v));
                }    
            }
        }
    }

    public function importDetail($transaction)
    {
        if ($this->empty) {
            return true;
        }
        return $transaction->controllerImport(array(
            array(
                'values' => array($this->values[$this->name]),
                'fields' => $this->current_config['fields'],
                'params' => array(
                    'table' => _DB_PREFIX_ . $this->current_config['data'],
                    'insert' => true,
                    'update' => true,
                    'key' => $this->current_config['key'],
                )
            ),
        ));
    }
}
