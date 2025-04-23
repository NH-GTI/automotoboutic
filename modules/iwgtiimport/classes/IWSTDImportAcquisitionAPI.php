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
require_once 'IWSTDImportAcquisitionFile.php';

class IWSTDImportAcquisitionAPI extends IWSTDImportAcquisitionFile
{
    protected function itemName($extra=null)
    {
        $r = 'item';
        $source = explode('//', $this->source_path);
        if (count($source) >= 2) {
            $name = explode('/', $source[1]);
            if (count($name) >= 1) {
                $r = $name[0];
            }
        }
        if (isset($extra)) {
            $r .= $extra;
        }
        $r .= '-' . date('YmdHis');
        $f = $r;
        $index = 0;
        while (file_exists ( IWSTDImportTools::getBackupDataDirectory() . $f )) {
            $index += 1;
            $f = $r . $index;
        }
        return $f;
    }

    protected function readApi($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        $response = curl_exec($ch);
        curl_close($ch);            
        if ($response === false) {
            throw new Exception(sprintf('Impossible d\'accéder à %1$s', $url), -1);
        }
        return json_decode($response, true);
    }

    protected function getFiles()
    {
        $values = $this->readApi($this->source_path);
        $name = $this->itemName();
        if (file_put_contents(IWSTDImportTools::getBackupDataDirectory() . $name, json_encode($values)) === FALSE)
            throw new Exception(sprintf('Impossible d\'écrire le fichier %1$s', $name), -1);
        $this->addItem($name);
        return true;
    }
    
    public function acquire()
    {
        return $this->getFiles() &&
            $this->createWorkFiles();
    }

}
