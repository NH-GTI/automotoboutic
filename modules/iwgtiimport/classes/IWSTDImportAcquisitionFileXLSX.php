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

use PhpOffice\PhpSpreadsheet\IOFactory;

require_once 'IWSTDImportAcquisitionFile.php';

class IWSTDImportAcquisitionFileXLSX extends IWSTDImportAcquisitionFile
{
    protected function addItem($input_name)
    {
        $filename = IWSTDImportTools::getValue('IMPORT_XLSX_FILENAME');
        if ($filename) {
            $ok = false;
            $name = $input_name;
            while (!$ok) {
                if ($filename == $name) {
                    $ok = true;
                    break;
                }
                $explfilename = explode('-', $name);
                array_shift($explfilename);
                if (count($explfilename) == 0) {
                    break;
                }
                $name = implode('-', $explfilename);
            }
            if ($ok == false) {
                return;
            }
        }
        // store name.sheet
        $item = explode('.', $input_name);
        if (count($item) == 2) {
            if ((strtolower($item[1]) == 'xlsx') || (strtolower($item[1]) == 'xls')) {
                $dir = $this->source_path;
                $reader_excel = IOFactory::createReaderForFile($dir . $input_name);
                $reader_excel->setReadDataOnly(true);
                $reader_excel->setLoadAllSheets();
                $excel_file = $reader_excel->load($dir . $input_name);
                $worksheetNames = $excel_file->getSheetNames();
                foreach($worksheetNames as $wsName) {
                    if (strlen($wsName)>2 && substr($wsName,0,2) != '__') { // don't take sheets with name started by __
                        $final_name = $item[0].'.'.$wsName;
                        $this->items[$final_name] = $input_name;
                    }
                }
            }
        }
    }
}
