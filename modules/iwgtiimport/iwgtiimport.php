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

require_once 'classes/IWGTIImportTools.php';

class IWGTIImport extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'iwgtiimport';
        $this->tab = 'quick_bulk_update';
        $this->version = '1.0.9';
        $this->author = 'instan\'web';
        $this->need_instance = 0;
        $this->display_warnings = null;
        $this->display_messages = null;
        $this->edit_additional_info = false;
        $this->additional_informations = false;
        $this->edit_import_file = false;
        $this->edit_image_file = false;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('IW GTI Import');
        $this->description = $this->l('IW - Data Import for GTI');

        $this->confirmUninstall = $this->l('Uninstall ?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        $this->iw_tools = new IWGTIImportTools($this->name);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        include dirname(__FILE__) . '/sql/install.php';
        Configuration::deleteByName('iwgtiimport');
        Configuration::deleteByName('iwgtiimport_info');
        return parent::install() &&
            $this->iw_tools->loadConfig() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
        include dirname(__FILE__) . '/sql/uninstall.php';
        $this->iw_tools->deleteConfig();
        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        $do_run_process = false;
        $this->display_warnings = array();
        $this->display_messages = array();

        // cms informations additionelles
        $this->additional_informations = unserialize(Configuration::get('iwgtiimport_additional_info'));
        if (((bool)Tools::isSubmit('add_additional_info')) == true) {
            $this->edit_additional_info = $this->updateAdditionalInformation(true, false, false);
        }
        if (Tools::getIsset('edit') && Tools::getIsset('id_information')) {
            $this->edit_additional_info = Tools::getValue('id_information');
        }
        if (((bool)Tools::isSubmit('delete_additional_info')) == true) {
            $this->updateAdditionalInformation(false, true, true);
            $this->edit_additional_info = false;
        }
        if (((bool)Tools::isSubmit('save_additional_info')) == true) {
            $this->updateAdditionalInformation(false, true, false);
            $this->edit_additional_info = false;
        }

        // cms fichiers importés
        $this->import_files = unserialize(Configuration::get('iwgtiimport_import_files'));
        if (((bool)Tools::isSubmit('add_import_file')) == true) {
            $this->edit_import_file = $this->updateImportFile(true, false, false);
        }
        if (Tools::getIsset('edit') && Tools::getIsset('id_import_file')) {
            $this->edit_import_file = Tools::getValue('id_import_file');
        }
        if (((bool)Tools::isSubmit('delete_import_file')) == true) {
            $this->updateImportFile(false, true, true);
            $this->edit_import_file = false;
        }
        if (((bool)Tools::isSubmit('save_import_file')) == true) {
            $this->updateImportFile(false, true, false);
            $this->edit_import_file = false;
        }

        // cms images importés
        $this->image_files = $this->getImageFiles();
        if (((bool)Tools::isSubmit('add_image_file')) == true) {
            $this->edit_image_file = $this->updateImageFile(true, false, false);
        }
        if (Tools::getIsset('edit') && Tools::getIsset('id_image_file')) {
            $this->edit_image_file = Tools::getValue('id_image_file');
        }
        if (((bool)Tools::isSubmit('delete_image_file')) == true) {
            $this->updateImageFile(false, true, true);
            $this->edit_image_file = false;
        }
        if (((bool)Tools::isSubmit('save_image_file')) == true) {
            $this->updateImageFile(false, true, false);
            $this->edit_image_file = false;
        }

        // maj données à partir de fichiers xlsx
        if (((bool)Tools::isSubmit('import_xlsx_value')) == true) {
            $this->postProcess();

            $import_files = unserialize(Configuration::get('iwgtiimport_import_files'));
            foreach($import_files as $ai) {
                if ($ai['selected']) {
                    $this->postProcess();
                    $this->iw_tools->setConfigValue('IMPORT_XLSX_FILE', 'importxlsx');
                    $this->iw_tools->setConfigValue('IMPORT_XLSX_FILENAME', $ai['filename']);
                    $this->iw_tools->saveConfig();
                    if (count($this->display_warnings)==0) {
                        $do_run_process = true;
                        break;
                    }        
                }
            }
        }

        if (((bool)Tools::isSubmit('save_settings')) == true) {
            $this->postProcess();
            $this->adminDisplayInformation($this->l('Enregistré!'));
        }

        if ($do_run_process === true) {
            set_time_limit(0);
            $ctrl = $this->context->controller;
            $this->iw_tools->run();
            $msg = str_replace(array('\n'), array('<BR/>'), $this->iw_tools->getLastMessages());
            $this->context->controller = $ctrl;
            array_unshift($msg, $this->l('Import effectué!'));
            $this->adminDisplayInformation(implode('<br/>', $msg));
            $this->iw_tools->saveConfig();
        }

        if (count($this->display_warnings)) {
            $this->adminDisplayWarning(implode('<br>', $this->display_warnings));
        }
        if (count($this->display_messages)) {
            $this->adminDisplayInformation(implode('<br>', $this->display_messages));
        }

        $this->context->smarty->assign('module_dir', $this->_path);
        $this->context->smarty->assign('description', $this->description);

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output . $this->renderForm();
    }

    protected function getImageFiles()
    {
        $validExtensions = ['jpg', 'png', 'gif'];
        $path = dirname( __FILE__ ).'/data/images/';
        $files = array();
        $idx = 0;
        $iterator = new RecursiveDirectoryIterator($path);
        foreach (new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST) as $file ) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), $validExtensions)) {
                $explpath = explode(DIRECTORY_SEPARATOR, $file->getPath());
                $subpath = array_pop($explpath);
                $name = $file->getFilename();
                $idx += 1;
                $files[] = [
                    'id_file' => $idx,
                    'subpath' => $subpath,
                    'filename' => $name
                ];
            }
        }
        if (count($files)) {
            uasort($files, function ($item1, $item2) {
                return $item1['subpath'].$item1['filename'] <=> $item1['subpath'].$item2['filename'];
            });
        }
        return count($files) ? $files : false;
    }

    /**
     * Create the forms that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->redirect_after = $helper->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $this->getListAdditionalInformationForm().$this->getListImageFileForm().$this->getListImportFileForm().
        $helper->generateForm(
            array(
                $this->getImportPrepareXlsFieldValueForm(),
            )
        );
    }
    
    protected function getImportPrepareXlsFieldValueForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Import fichier(s) sélectionné(s)'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Lire toutes les feuilles'),
                        'name' => 'IWGTIIMPORT_READALLSHEETS',
                        'is_bool' => true,
                        'desc' => $this->l('Si actif, lit toutes les feuilles du tableur (utile si utilisation de recherches multi-feuilles'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'name' => 'import_xlsx_value',
                    'id' =>'import_xlsx_value',
                    'title' => $this->l('Importer'),
                ),
            ),
        );
    }

    protected function getListImportFileForm()
    {
        $this->context->smarty->assign([
            'files' => $this->import_files,
            'link' => $this->context->link,
            'edit' => $this->edit_import_file,
        ]);
        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/list_import_file.tpl');
        return $output;
    }

    protected function updateImportFile($newline,$post,$removeline)
    {
        if ($removeline) {
            $test_id_file = Tools::getValue('delete_import_file');
        } elseif($post) {
            $test_id_file = Tools::getValue('id_import_file');
        } else {
            $test_id_file = 0;
        }
        $id_file = 0;
        $edit = false;
        if ($this->import_files && count($this->import_files)) {
            foreach($this->import_files as $ai) {
                if ($newline && $ai['id_file'] > $id_file) {
                    $id_file = $ai['id_file'];
                } elseif($post && $ai['id_file'] == $test_id_file) {
                    $id_file = $test_id_file;
                    $edit = true;
                }
            }
        }
        if ($newline) {
            $id_file += 1;
        } else {
            $id_file = $test_id_file;
        }
        $file = [
            'id_file' => $id_file,
            'type' => $post ? (string)Tools::getValue('type') : '',
            'target' => $post ? (string)Tools::getValue('target') : '',
            'filename' => $post ? str_replace('-', '', (string)Tools::getValue('filename')) : '',  //remove character '-' because it's used on destination path
            'selected' => $post ? Tools::getIsset('selected') : 0,
        ];
        if ($edit) {
            $index = 0;
            foreach($this->import_files as &$ai) {
                if ($ai['id_file'] == $id_file) {
                    if ($removeline) {
                        // remove file
                        $filetodelete = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'xlsxsource'.DIRECTORY_SEPARATOR.$ai['type'].'-'.($ai['target'] ? $ai['target'].'-' : '').$ai['filename'];
                        unlink($filetodelete);
                        // remove line
                        unset($this->import_files[array_search($ai, $this->import_files)]);
                    } else {
                        $ai = $file;
                    }
                }
                $index += 1;
            }
        } else {
            $this->import_files[] = $file;
        }
        if ($post) {
            Configuration::updateValue('iwgtiimport_import_files', serialize($this->import_files));
            $this->import_files = unserialize(Configuration::get('iwgtiimport_import_files'));
        }
        return $id_file;
    }

    protected function getListImageFileForm()
    {
        $this->context->smarty->assign([
            'files' => $this->image_files,
            'link' => $this->context->link,
            'edit' => $this->edit_image_file,
        ]);
        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/list_image_file.tpl');
        return $output;
    }

    protected function updateImageFile($newline,$post,$removeline)
    {
        if ($removeline) {
            $test_id_file = Tools::getValue('delete_image_file');
        } elseif($post) {
            $test_id_file = Tools::getValue('id_image_file');
        } else {
            $test_id_file = 0;
        }
        $id_file = 0;
        $edit = false;
        if ($this->image_files && count($this->image_files)) {
            foreach($this->image_files as $ai) {
                if ($newline && $ai['id_file'] > $id_file) {
                    $id_file = $ai['id_file'];
                } elseif($post && $ai['id_file'] == $test_id_file) {
                    $id_file = $test_id_file;
                    $edit = true;
                }
            }
        }
        if ($newline) {
            $id_file += 1;
        } else {
            $id_file = $test_id_file;
        }
        $file = [
            'id_file' => $id_file,
            'subpath' => $post ? (string)Tools::getValue('subpath') : '',
            'filename' => $post ? (string)Tools::getValue('filename') : '',
        ];
        if ($edit) {
            $index = 0;
            foreach($this->image_files as &$ai) {
                if ($ai['id_file'] == $id_file) {
                    if ($removeline) {
                        // remove file
                        $filetodelete = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$ai['subpath'].DIRECTORY_SEPARATOR.$ai['filename'];
                        unlink($filetodelete);
                        // remove line
                        unset($this->image_files[array_search($ai, $this->image_files)]);
                    } else {
                        $ai = $file;
                    }
                }
                $index += 1;
            }
        } else {
            $this->image_files[] = $file;
        }
        return $id_file;
    }

    protected function getListAdditionalInformationForm()
    {
        $this->context->smarty->assign([
            'informations' => $this->additional_informations,
            'link' => $this->context->link,
            'edit' => $this->edit_additional_info,
        ]);
        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/list_additional_info.tpl');
        return $output;
    }

    protected function updateAdditionalInformation($newline,$post,$removeline)
    {
        if ($removeline) {
            $test_id_information = Tools::getValue('delete_additional_info');
        } elseif($post) {
            $test_id_information = Tools::getValue('id_information');
        } else {
            $test_id_information = 0;
        }
        $id_information = 0;
        $edit = false;
        if ($this->additional_informations && count($this->additional_informations)) {
            foreach($this->additional_informations as $ai) {
                if ($newline && $ai['id_information'] > $id_information) {
                    $id_information = $ai['id_information'];
                } elseif($post && $ai['id_information'] == $test_id_information) {
                    $id_information = $test_id_information;
                    $edit = true;
                }
            }
        }
        if ($newline) {
            $id_information += 1;
        } else {
            $id_information = $test_id_information;
        }
        $information = [
            'id_information' => $id_information,
            'id_product' => $post ? (string)Tools::getValue('id_product') : '',
            'field_reference' => $post ? (string)Tools::getValue('field_reference') : '',
            'prefix' => $post ? (string)Tools::getValue('prefix') : '',
            'key_column' => $post ? (string)Tools::getValue('key_column') : '',
            'value_by_country' =>  $post ? Tools::getIsset('value_by_country') : 0,
            'position' => $post ? (string)Tools::getValue('position') : '',
            'display' => $post ? Tools::getIsset('display') : 0,
        ];
        if ($edit) {
            $index = 0;
            foreach($this->additional_informations as &$ai) {
                if ($ai['id_information'] == $id_information) {
                    if ($removeline) {
                        unset($this->additional_informations[array_search($ai, $this->additional_informations)]);
                    } else {
                        $ai = $information;
                    }
                }
                $index += 1;
            }
        } else {
            $this->additional_informations[] = $information;
        }
        if ($post) {
            Configuration::updateValue('iwgtiimport_additional_info', serialize($this->additional_informations));
            $this->additional_informations = unserialize(Configuration::get('iwgtiimport_additional_info'));
            $trad = ['{* AUTOMATIC FILLED . DO NOT UPDATE *}'];
            foreach($this->additional_informations as $ai) {
                $t = "{l s='" . $ai['prefix'] . "' mod='iwgtiimport'}";
                if (!in_array($t, $trad)) {
                    $trad[] = $t;
                }
            }
            file_put_contents(_PS_MODULE_DIR_.'iwgtiimport/views/templates/admin/additional_info_trad.tpl', implode("\n", $trad));
        }
        return $id_information;
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        if (!$this->iw_tools->loadConfig()) {
            return array();
        }
        return $this->iw_tools->getConfigArray();
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        foreach (array_keys($form_values) as $key) {
            if (isset($_POST[$key])) {
                $this->iw_tools->setConfigValue($key, Tools::getValue($key));
            }
        }
        $this->iw_tools->saveConfig();
    }

    public static function getConfigValue($key, $default = null)
    {
        $iw_tools = new IWGTIImportTools('iwgtiimport');
        $iw_tools->loadConfig();
        return $iw_tools->getConfigValue($key, $default);
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJS(
                'modules/' . $this->name . '/views/js/back.js'
            );
            $this->context->controller->addCSS(
                'modules/' . $this->name . '/views/css/back.css'    
            );
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->registerJavascript(
            'front-js',
            'modules/' . $this->name . '/views/js/front.js'
        );
        $this->context->controller->registerStylesheet(
            'front-css',
            'modules/' . $this->name . '/views/css/front.css'    
        );
    }
}
