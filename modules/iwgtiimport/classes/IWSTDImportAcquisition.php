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

class IWSTDImportAcquisition
{
    protected $items = array();

    public function __construct($source_path=null, $delete_source=true)
    {
        file_put_contents(_PS_ROOT_DIR_ . '/debug_import.log', date('Y-m-d H:i:s') . " - IWSTDImportAcquisition::__construct: Provided source_path=" . ($source_path ? $source_path : 'null') . "\n", FILE_APPEND);
        
        $this->delete_source = $delete_source;
        if (!empty($source_path)) {
            $this->source_path = $source_path;
            file_put_contents(_PS_ROOT_DIR_ . '/debug_import.log', date('Y-m-d H:i:s') . " - IWSTDImportAcquisition::__construct: Using provided source_path\n", FILE_APPEND);
        } else {
            $this->source_path = IWSTDImportTools::getValue('SOURCE_PATH');
            file_put_contents(_PS_ROOT_DIR_ . '/debug_import.log', date('Y-m-d H:i:s') . " - IWSTDImportAcquisition::__construct: Using SOURCE_PATH from config=" . $this->source_path . "\n", FILE_APPEND);
        }
    }    

    public function getItems()
    {
        return $this->items;
    }

    protected function addItem($input_name)
    {
        file_put_contents(_PS_ROOT_DIR_ . '/debug_import.log', date('Y-m-d H:i:s') . " - Acquisition: Adding item: " . $input_name . "\n", FILE_APPEND);
        $this->items[$input_name] = $input_name;
    }

    public function acquire()
    {
        file_put_contents(_PS_ROOT_DIR_ . '/debug_import.log', date('Y-m-d H:i:s') . " - Acquisition: Starting with source_path: " . $this->source_path . "\n", FILE_APPEND);
        
        if (!isset($this->source_path)) {
            file_put_contents(_PS_ROOT_DIR_ . '/debug_import.log', date('Y-m-d H:i:s') . " - Acquisition: No source path set\n", FILE_APPEND);
            return false;
        }
        
        file_put_contents(_PS_ROOT_DIR_ . '/debug_import.log', date('Y-m-d H:i:s') . " - Acquisition: Source path exists\n", FILE_APPEND);
    }

    protected function createWorkFiles()
    {
        try {
            foreach ($this->items as $f) {
                $sourcefile = IWSTDImportTools::getBackupDataDirectory() . $f;
                $destfile = IWSTDImportTools::getWorkingDataDirectory() . $f;
                if (!copy($sourcefile, $destfile)) {
                    throw new Exception(sprintf('Impossible de copier %1$s sur le répertoire de travail', $f), -1);
                }
            }
        } catch (Exception $e) {
            $this->deleteWorkFiles();
            throw $e;
        }
        return true;
    }

    public function deleteWorkFiles()
    {
        foreach ($this->items as $f) {
            $p = IWSTDImportTools::getWorkingDataDirectory() . $f;
            if (file_exists($p)) {
                unlink($p);
            }
        }
    }

    public function moveWorkFilesToError()
    {
        foreach ($this->items as $f) {
            if (copy(IWSTDImportTools::getWorkingDataDirectory() . $f, IWSTDImportTools::getErrorDataDirectory() . $f)) {
                unlink(IWSTDImportTools::getWorkingDataDirectory() . $f);
            }
        }
    }    
}
