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

class IWSTDImportTools
{
    protected $module_name = null;
    protected $config = null;
    protected static $tools = null;
    protected $tag_last_messages = null;

    // override if necessary
    protected function createConfig()
    {
        $this->config = array(
            'TEST_MODE' => '0',
            'MAIL_CONTACT' => ''
        );
    }

    // override if necessary
    public function run()
    {
        /* exemple
        $tr = array(
            array(
                'acquisition' => new IWSTDImportAcquisition(),
                'analyze'     => new IWSTDImportAnalyze(new IWSTDImportDB()),
                'transaction' => array(
                        new IWSTDImportTransaction(new IWSTDImportDB(), "ident"),
                        new IWSTDImportTransaction(new IWSTDImportDB(), "ident"),
                )
            ),
            array(
                'acquisition' => new IWSTDImportAcquisition(),
                'analyze'     => new IWSTDImportAnalyze(new IWSTDImportDB()),
                'transaction' => array(
                        new IWSTDImportTransaction(new IWSTDImportDB(), "ident"),
                        new IWSTDImportTransaction(new IWSTDImportDB(), "ident"),
                )
        );
*/
//		$this->runProcess($tr);
    }

    // override if necessary
    protected function runProcess($process_array)
    {
        // wiping
        self::dataWiping();
        
        $this->setTagLastMessages();        
        foreach ($process_array as $process) {
            // used for read messages since this tag
            $ids_import = Array();
            try {
                // acquisition / analyze
                if (array_key_exists('prepare', $process)) {
                    foreach ($process['prepare'] as $prepare) {
                        $prepare['acquisition']->acquire();
                        $prepare['analyze']->analyze($prepare['acquisition']);
                        array_push($ids_import, $prepare['analyze']->getIdImport());
                    }
                }
                else {
                    $process['acquisition']->acquire();
                    $process['analyze']->analyze($process['acquisition']);
                    array_push($ids_import, $process['analyze']->getIdImport());
                }

                // transaction
                foreach ($process['transaction'] as $tr) {
                    $tr->import();
                }

                // succeed message
                $this->addSucceedMessage($process);
            } catch (Exception $e) {
                // error message
                $this->addErrorMessage($e, $process);
            } finally {
                $this->sendMessage();
                IWSTDImportDB::updateAllHeaderStatusFromImport($ids_import);
            }
        }

        // wiping
//        self::dataWiping();
        return true;
    }

    // override if necessary
    protected function addErrorMessage($e, $process)
    {
        $msg = sprintf('Erreur %1$s', $e->getMessage());
        foreach ($process['transaction'] as $tr) {
            $pr = $tr->getProperties();
            $msg .= '\n' . $pr['name'];
        }
        return IWSTDImportDB::addMessage($msg, -1);
    }

    // override if necessary
    protected function addSucceedMessage($process)
    {
        $msg = 'Succès';
        $count = 0;
        $names = [];
        if (array_key_exists('prepare', $process)) {
            foreach ($process['prepare'] as $prepare) {
                $count += count($prepare['acquisition']->getItems());
                foreach ($prepare['acquisition']->getItems() as $input_name) {
                    array_push($names, $input_name);
                }    
            }
        }
        else {
            $count = count($process['acquisition']->getItems());
            foreach ($process['acquisition']->getItems() as $input_name) {
                array_push($names, $input_name);
            }
        }
        if ($count == 0) {
            $msg .= ' ' . 'Aucun nouvel élément';
        } else {
            $params = array(
                $count,
                $count > 1 ?
                    'éléments':
                    'élément'
            );
            $msg .= ' ' . sprintf('Acquisition %1$d %2$s', $params[0], $params[1]) . ' ' . implode(' ', $names);
            foreach ($process['transaction'] as $tr) {
                $pr = $tr->getProperties();
                if (isset($pr['name']) && Tools::strlen($pr['name'])) {
                    $msg .= ' [' . $pr['name'] . ']';
                }
                if ($pr['count_detail'] + $pr['count_error_detail'] == 0) {
                    $msg .= ' (' . 'aucun enregistrement' . ')';
                } else {
                    $msg .= ' (';
                    $msg .= ($pr['count_detail'] > 1) ?
                        sprintf('%1$d enregistrements réussis', $pr['count_detail']) :
                        sprintf('%1$d enregistrement réussi', $pr['count_detail']);
                    $err = $pr['count_error_detail'];
                    if ($err) {
                        $msg .= ' ' . sprintf('et %1$d en erreur', $err);
                    }
                    $msg .= ')';
                }
            }
        }
        return IWSTDImportDB::addMessage($msg, 1);
    }

    // override if necessary
    protected function sendMessage()
    {
        return IWSTDImportTools::sendMail('Rapport');
    }

    public function __construct($name)
    {
        self::$tools = $this;
        $this->module_name = $name;
    }

    public function loadConfig()
    {
        if (!isset($this->config)) {
            if (!isset($this->module_name)) {
                return false;
            }
            $this->config = unserialize(Configuration::get($this->module_name));
            if (!isset($this->config) || !$this->config || count(array_keys($this->config)) == 0) {
                $this->createConfig();
                return $this->saveConfig();
            }
        }
        return true;
    }

    public function getConfigArray()
    {
        return $this->config;
    }

    public function getConfigValue($key, $default = null)
    {
        return (isset($this->config[$key]) ? $this->config[$key] : $default);
    }

    public function setConfigValue($key, $value)
    {
        $this->config[$key] = $value;
        return true;
    }

    public function saveConfig()
    {
        if (!isset($this->module_name)) {
            return false;
        }
        if (isset($this->config)) {
            Configuration::updateValue($this->module_name, serialize($this->config));
        }
        return true;
    }

    public function deleteConfig()
    {
        if (!isset($this->module_name)) {
            return false;
        }
        Configuration::deleteByName($this->module_name);
    }

    public static function getBackupDataDirectory()
    {
        // refer to module folder tree
        //return dirname(__FILE__) . '/../data/backup/';
        return '/var/www/html/feuvert2/modules/iwgtiimport/data/backup/';
    }

    public static function getWorkingDataDirectory()
    {
        // refer to module folder tree
        //return dirname(__FILE__) . '/../data/work/';
        return '/var/www/html/feuvert2/modules/iwgtiimport/data/work/';
    }

    public static function getErrorDataDirectory()
    {
        // refer to module folder tree
        //return dirname(__FILE__) . '/../data/error/';
        return '/var/www/html/feuvert2/modules/iwgtiimport/data/error/';
    }

    public static function getValue($key, $default = null)
    {
        $value = self::$tools->getConfigValue($key, $default);
        file_put_contents(_PS_ROOT_DIR_ . '/debug_import.log', date('Y-m-d H:i:s') . " - Tools::getValue: Key=" . $key . ", Value=" . print_r($value, true) . "\n", FILE_APPEND);
        return $value;
    }

    public static function setValue($key, $value)
    {
        return self::$tools->setConfigValue($key, $value);
    }    

    public static function getModuleName()
    {
        return self::$tools->module_name;
    }

    public function setTagLastMessages()
    {
        $this->tag_last_messages = IWSTDImportDB::getCurrentTimestamp();
        return (isset($this->tag_last_messages));
    }

    public function getLastMessages()
    {
        if (isset($this->tag_last_messages)) {
            return IWSTDImportDB::getLastMessages($this->tag_last_messages);
        } else {
            return array();
        }
    }

    public static function sendMail($message = null, $subject = null, $destinataire = null)
    {
        $last_messages = self::$tools->getLastMessages();
        if (!isset($last_messages) || count($last_messages) == 0) {
            return false;
        }
        if (!isset($subject)) {
            $subject = sprintf('Mail automatique du module PrestaShop %1$s', IWSTDImportTools::getModuleName());
        }
        if (!isset($destinataire)) {
            $destinataire = self::$tools->getConfigValue('MAIL_CONTACT');
        }
        if (!isset($destinataire) || Tools::strlen($destinataire) == 0) {
            return false;
        }
        $data = array(
            '{message}' => (isset($message)) ? $message : '',
            '{import_message}' => implode('<BR/><BR/>', str_replace(array('\n'), array('<BR/>'), $last_messages))
        );
        return Mail::Send(
            (int)(Context::getContext()->cookie->id_lang),
            'import_alert',
            $subject,
            $data,
            $destinataire,
            null,
            null,
            null,
            null,
            null,
            _PS_ROOT_DIR_ . '/modules/' . IWSTDImportTools::getModuleName() . '/mails/'
        );
    }

    public static function dataWiping($delay_in_seconds = null)
    {
        // fix limit
        $delay = (isset($delay_in_seconds)) ? $delay_in_seconds : 0; //24 * 3600 * 7;
        $ts_limit = time() - $delay;
        $limit = date('YmdHis', $ts_limit);

        // wipe local files
        $paths = array(self::getBackupDataDirectory(),
                                     self::getWorkingDataDirectory(),
                                     self::getErrorDataDirectory());
        foreach ($paths as $path) {
            $files = scandir($path);
            foreach ($files as $f) {
                if (!is_dir($path . $f) && $f != 'index.php') {
                    $ts = date('YmdHis', filemtime($path . $f));
                    if (Tools::strlen($limit) == Tools::strlen($ts) && $ts < $limit) {
                        unlink($path . $f);
                    }
                }
            }
        }

        // wipe db
        IWSTDImportDB::dataWiping($delay);
    }
}
