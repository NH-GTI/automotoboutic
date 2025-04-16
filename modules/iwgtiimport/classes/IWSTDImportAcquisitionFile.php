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
require_once 'IWSTDImportAcquisition.php';

class IWSTDImportAcquisitionFile extends IWSTDImportAcquisition
{
    protected function fileExtensions()
    {
        return IWSTDImportTools::getValue('FILE_EXTENSION');
    }

    public function acquire()
    {
        return $this->scanFiles() &&
           $this->filterFiles() &&
           $this->getFiles() &&
           $this->createWorkFiles();
    }

    protected function getFiles()
    {
        foreach ($this->items as $f) {
            $source = $this->source_path . $f;
            $destination = IWSTDImportTools::getBackupDataDirectory() . $f;
            if (!copy($source, $destination)) {
                throw new Exception(sprintf('Impossible de copier %1$s vers le répertoire backup', $f), -1);
            }
        }
        if ($this->delete_source) {
            foreach ($this->items as $f) {
                if (!unlink($this->source_path . $f)) {
                    throw new Exception(sprintf('Impossible d\'effacer %1$s du répertoire de scan', $f), -1);
                }
            }
        }
        return true;
    }

    protected function addItem($input_name)
    {
        $f = explode('.', $input_name);
        $this->items[$f[0]] = $input_name;
    }

    protected function scanFiles()
    {
        $dir = $this->source_path;
        if (!isset($dir) || (Tools::strlen($dir) == 0)) {
            throw new Exception('Impossible de scanner les fichiers, le répertoire n\'est pas défini', -1);
        }
        $wrkfiles = scandir($dir);
        $this->items = array();
        foreach ($wrkfiles as $f) {
            if (!is_dir($dir . $f)) {
                $this->addItem($f);
            }
        }
        return true;
    }

    protected function filterFiles()
    {
        if (count($this->items) == 0) {
            return true;
        }
        $file_ext = $this->fileExtensions();
        if (!isset($file_ext) || (Tools::strlen($file_ext) == 0)) {
            return true;
        }
        $file_extension = explode(',', Tools::strtolower($file_ext));
        $wrkfiles = $this->items;
        $this->items = array();
        foreach ($wrkfiles as $k => $f) {
            $filename = explode('.', $f);
            if (in_array(Tools::strtolower(end($filename)), $file_extension)) {
                $this->items[$k] = $f;
            }
        }
        return true;
    }
}
