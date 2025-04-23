<?php
/**
* IW 2021
*
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once 'IWSTDImportTransaction.php';
require_once 'IWGTIImportDataNDK.php';
require_once 'IWGTIImportDataCustomerNorauto.php';
require_once 'IWGTIImportDataCustomerFeuVert.php';

class IWGTIImportTransactionXlsx extends IWSTDImportTransaction
{
    public function __construct($db, $name, $header_filter, $export_type)
    {
        parent::__construct($db, $name, $header_filter);
        $this->exportType = $export_type;
    }

    /**
     * Opérations réalisées avant l'import
     * - Type d'import exécuté (NDK, clients, ...)
     * @return void
     */
    public function beforeImport()
    {
        $specific  = $this->db->findHeader();
        if (isset($specific['name'])) {
            $name = explode('-', $specific['name']);
            if (count($name) >= 2) {
                $exportType = array_shift($name);
                if ($exportType == 'CN') {
                    // Client Norauto
                    $this->import_data = new IWGTIImportDataCustomerNorauto($this->db, $exportType);
                } elseif ($exportType == 'CF') {
                    // Client Feu Vert
                    $this->import_data = new IWGTIImportDataCustomerFeuVert($this->db, $exportType);    
                } elseif (($exportType == 'ST') || ($exportType == 'SP')) {
                    // NDK
                    $this->import_data = new IWGTIImportDataNDK($this->db, $exportType);
                } else {
                    return;
                }
                if ($this->import_data) {
                    $this->import_data->beforeImport();
                }
            }
        }
    }

    public function afterImport()
    {
        if ($this->import_data) {
            $this->import_data->afterImport();
        }
    }

    public function nextHeader()
    {
        if ($this->import_data) {
            return $this->import_data->nextHeader();
        }
        return false;
    }

    public function prepareValues()
    {
        parent::prepareValues();
        if ($this->import_data) {
            $this->import_data->prepareValues($this->name, $this->values[$this->name]);
        }
    }

    public function importDetail()
    {
        if ($this->import_data) {
            return $this->import_data->importDetail($this);
        }
        return false;
    }
}
