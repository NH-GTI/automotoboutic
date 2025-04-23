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

require_once 'IWSTDImportAnalyze.php';

class IWSTDImportAnalyzeCSV extends IWSTDImportAnalyze
{
    protected function isCsvHeader()
    {
        return IWSTDImportTools::getValue('CSV_HEADER', true);
    }

    protected function csvSeparator()
    {
        return IWSTDImportTools::getValue('CSV_SEPARATOR', ',');
    }

    protected function csvQuote()
    {
        return IWSTDImportTools::getValue('CSV_QUOTE', '"');
    }

    protected function analyzeItem($item, $key=false)
    {
        $dir = IWSTDImportTools::getWorkingDataDirectory();
        $handle = fopen($dir . $item, 'r');
        try {
            if (!$handle) {
                throw new Exception(sprintf('Impossible d\'ouvrir le fichier %1$s', $item), -1);
            }
            $first_line = true;
            while (($cols = fgetcsv($handle, 8192, $this->csvSeparator(), $this->csvQuote())) !== false) {
                if (count($cols) == 1 && $cols[0] == NULL) {
                    continue;
                }
                if ($first_line) {
                    $first_line = false;
                    $is_header = $this->isCsvHeader();
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
            if (!feof($handle)) {
                throw new Exception(sprintf('Le fichier %1$s n\'a pas été lu correctement', $item), -1);
            }
        } finally {
            if ($handle) {
                fclose($handle);
            }
        }
        return true;
    }

    protected function defineHeaders($is_header, $cols)
    {
        $headers = array();
        if ($is_header) {
            $headers = $cols;
            if (Tools::strlen(end($headers)) == 0) {
                array_pop($headers);
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
        if (count($cols) == count($headers)) {
            $count = count($cols);
            $index = 0;
            while ($index < $count) {
                $key = $headers[$index];
                $value = $cols[$index];
                if ($value == 'NULL') {
                    $value = null;
                }
                $h[$key] = $value;
                $index += 1;
            }
        } else {
            $line = implode(',', $cols);
            throw new Exception(sprintf('Une ligne ne contient pas %1$d valeurs', count($headers)).' '.count($cols).' '.$line, -1);
        }
        return $h;
    }
}
