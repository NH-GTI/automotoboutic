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

require_once 'IWSTDImportAnalyze.php';

class IWSTDImportAnalyzeXLSX extends IWSTDImportAnalyze
{
    protected function isXlsxHeader()
    {
        return IWSTDImportTools::getValue('XLSX_HEADER', true);
    }

    /**
     * see parent method
     * 
     * $item : nom du fichier à lire
     * $key : composé du nom de fichier . code feuille . nom feuille
     * 
     * exemple :
     * CN-Magasins Norauto.Magasins-BE
     * SP-2-Norauto20210817.GA.Gabarit
     * ST-Norauto20210817.GA.Gabarit
     * 
     * L'analyse se fait sur la feuille Excel dont le nom est code feuille . nom feuille (ex GA.Gabarit)
     *
     */
    protected function analyzeItem($item,$key=false)
    {
        $dir = IWSTDImportTools::getWorkingDataDirectory();
        $keynames = $key ? explode('.', $key) : false;
        if ($keynames) {
            array_shift($keynames);
            $keynames = implode('.', $keynames);
        }
        $reader_excel = IOFactory::createReaderForFile($dir . $item);
        $reader_excel->setReadDataOnly(true);
        $excel_file = $reader_excel->load($dir . $item);
        $worksheetNames = $excel_file->getSheetNames();
        foreach($worksheetNames as $wsName) {
            if ($keynames && $keynames != $wsName) {
                continue;
            }
            $worksheet = $excel_file->getSheetByName($wsName);
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
            $first_line = true;
            for ($row = 1; $row <= $highestRow; ++$row) {
                $cols = [];
                for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                    $value = $worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                    $cols[] = $value;
                }
                if ($first_line) {
                    $first_line = false;
                    $is_header = $this->isXlsxHeader();
                    $headers = $this->defineHeaders($is_header, $cols);
                    if ($is_header) {
                        continue;
                    }
                }
                $values = $this->toHash($headers, $cols);
                $this->db->createDetail();
                foreach ($values as $key => $value) {
                    $this->db->createKeyValue($key, $value);
                }
            }
        }
        return true;
    }

    protected function defineHeaders($is_header, $cols)
    {
        $headers = array();
        if ($is_header) {
            foreach($cols as $c) {
                if ($c) {
                    $headers[] = $c;
                }
            }
        } else {
            for ($index = 0; $index < count($cols); $index++) {
                array_push($headers, $index);
            }
        }
        return $headers;
    }

    protected function toHash($headers, $cols)
    {
        $h = array();
        $countcols = count($cols);
        $countheaders = count($headers);

        $index = 0;
        while ($index < $countcols && $index < $countheaders) {
            $key = $headers[$index];
            if (strlen($key)>2 && substr($key,0,2) == '__') {
                // ignore column start with __
                $index += 1;
                continue;
            }
            $value = $cols[$index];
            if ($value == 'NULL') {
                $value = null;
            }
            $h[$key] = $value;
            $index += 1;
        }

        return $h;
    }
}
