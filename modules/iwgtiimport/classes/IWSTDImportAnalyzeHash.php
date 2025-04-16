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

class IWSTDImportAnalyzeHash extends IWSTDImportAnalyze
{    
    protected function analyzeItem($item,$key=false)
    {
        $data = file_get_contents(IWSTDImportTools::getWorkingDataDirectory() . $item);
        if ($data === FALSE) {
            throw new Exception(sprintf('Impossible d\'ouvrir le fichier %1$s', $item), -1);
        }
        $values = json_decode($data, TRUE);
        if (is_array($values))
        {
            foreach ($values as $key => $value) {
                $this->db->createDetail();
                $this->processValue("", $value);
            }
        }
        else
        {
        	$this->db->createDetail();
	        $this->processValue("", $item);
        }
        return true;
    }

    protected function processValue($root, $item)
    {
        if (is_array($item)) {
            foreach ($item as $key => $value) {	
                $this->processValue(strlen($root) ? $key.'_'.$root : $key, $value);
            }
        }
        else {
            $this->db->createKeyValue($root, $item);
        }
    }
}
