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

require_once 'IWSTDImportDB.php';
require_once 'IWSTDImportTools.php';
require_once 'IWSTDImportAcquisitionFile.php';
require_once 'IWSTDImportAcquisitionFileXLSX.php';
require_once 'IWSTDImportAcquisitionFileFTP.php';
require_once 'IWSTDImportAnalyzeCSV.php';
require_once 'IWSTDImportAnalyzeXLSX.php';
require_once 'IWSTDImportAnalyzeHash.php';

require_once 'IWGTIImportTransactionXlsx.php';

class IWGTIImportTools extends IWSTDImportTools
{
    protected function createConfig()
    {
        parent::createConfig();

        $this->config['IMPORT_XLSX_FILE'] = false;
        $sourcePath = '/var/www/html/feuvert2/modules/iwgtiimport/data/xlsxsource/';
        file_put_contents(_PS_ROOT_DIR_ . '/debug_import.log', date('Y-m-d H:i:s') . " - IWGTIImportTools::createConfig: Setting source path to: " . $sourcePath . "\n", FILE_APPEND);
        $this->config['IMPORT_XLSX_FILE_PATH'] = $sourcePath;
        $this->config['SOURCE_PATH'] = $sourcePath;

        $this->config['FILE_EXTENSION'] = 'csv,xlsx,xls';    // comma separated (csv,txt)
        $this->config['CSV_HEADER'] = true;
        $this->config['CSV_SEPARATOR'] = ';';
        $this->config['CSV_QUOTE'] = '"';
        $this->config['MSG_SUCCESS'] = true;
        $this->config['MSG_SUCCESS_DETAIL'] = false;
        $this->config['MSG_SUCCESS_NO_ITEM'] = true;
    }

    public function run()
    {
        $db = new IWSTDImportDB();
        $tr = array();

        if ($this->config['IMPORT_XLSX_FILE']) {
            $export_type = explode(',', $this->config['IMPORT_XLSX_FILE']);
            foreach($export_type as $et) {
                file_put_contents(_PS_ROOT_DIR_ . '/debug_import.log', date('Y-m-d H:i:s') . " - IWGTIImportTools::run: Setting up XLSX import for type: " . $et . "\n", FILE_APPEND);
                file_put_contents(_PS_ROOT_DIR_ . '/debug_import.log', date('Y-m-d H:i:s') . " - IWGTIImportTools::run: Using IMPORT_XLSX_FILE_PATH=" . $this->config['IMPORT_XLSX_FILE_PATH'] . "\n", FILE_APPEND);
                file_put_contents(_PS_ROOT_DIR_ . '/debug_import.log', date('Y-m-d H:i:s') . " - IWGTIImportTools::run: Using SOURCE_PATH=" . $this->config['SOURCE_PATH'] . "\n", FILE_APPEND);
                
                array_push($tr,
                    array(
                        'acquisition' => new IWSTDImportAcquisitionFileXLSX('/var/www/html/feuvert2/modules/iwgtiimport/data/xlsxsource/', false),
                        'analyze' => new IWSTDImportAnalyzeXLSX($db),
                        'transaction' => array(new IWGTIImportTransactionXlsx($db, "xls*", "%", $et)),
                    )
                );
            }
        }
        
        $this->runProcess($tr);
    }
}
