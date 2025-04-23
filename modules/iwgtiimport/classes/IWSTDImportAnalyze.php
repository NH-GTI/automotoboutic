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

class IWSTDImportAnalyze
{
    protected $acquition = null;
    protected $db = null;
    protected $id_import = null;

    public function __construct($db, $acquisition = null)
    {
        $this->acquisition = $acquisition;
        $this->db = $db;
        $this->id_import = null;
    }

    public function getIdImport()
    {
        return $this->id_import;
    }

    public function analyze($acquisition = null)
    {
        if (isset($acquisition)) {
            $this->acquisition = $acquisition;
        }
        try {
            $this->db->startTransaction();
            $this->id_import = $this->db->createImport(basename(__FILE__, '.php'));
            foreach ($this->acquisition->getItems() as $k => $item) {
                $this->db->createHeader($item, $k);
                $this->analyzeItem($item, $k);
            }
        } catch (Exception $e) {
            $this->db->rollback();
            $this->afterAnalyzeOnError();
            throw $e;
        }
        $this->db->commit();
        $this->afterAnalyzeOnOK();
        return true;
    }

    protected function analyzeItem($item, $key=false)
    {
        return isset($item);
    }

    protected function afterAnalyzeOnOK()
    {
        // all is ok, delete working files
        $this->acquisition->deleteWorkFiles();
    }

    protected function afterAnalyzeOnError()
    {
        // ko, files are moved to error
        $this->acquisition->moveWorkFilesToError();
    }
    
}
