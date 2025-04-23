<?php
/**
* 2007-2021 PrestaShop
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
*  @copyright 2007-2021 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class GTIFeatures extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'gtifeatures';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'GTI';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('GTI Features');
        $this->description = $this->l('Additional features for GTI');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->controllers = ['ajax'];
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('GTIFEATURES_HIDECHECKOUTSTEPS', false);
        Configuration::updateValue('GTIFEATURES_MANAGESAPNUMBERINCART', false);
        Configuration::updateValue('GTIFEATURES_USE_NDK_EXTENDED_DATA', false);
        Configuration::updateValue('GTIFEATURES_USE_NDK_EXTENDED_DATA_FOR_ORDER_HISTORY_AND_DETAIL', false);
        // GTIFEATURES_REDIRECT_AFTER_ADD_TO_CART needs ajax_cart to be disabled
        Configuration::updateValue('GTIFEATURES_REDIRECT_AFTER_ADD_TO_CART', false);
        Configuration::updateValue('GTIFEATURES_REDIRECT_URL_AFTER_ADD_TO_CART', false);
        Configuration::updateValue('PS_BLOCK_CART_AJAX', false);

        include(dirname(__FILE__).'/sql/install.php');
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
        Configuration::deleteByName('GTIFEATURES_HIDECHECKOUTSTEPS');
        Configuration::deleteByName('GTIFEATURES_MANAGESAPNUMBERINCART');
        Configuration::deleteByName('GTIFEATURES_USE_NDK_EXTENDED_DATA');
        Configuration::deleteByName('GTIFEATURES_USE_NDK_EXTENDED_DATA_FOR_ORDER_HISTORY_AND_DETAIL');
        Configuration::deleteByName('GTIFEATURES_REDIRECT_AFTER_ADD_TO_CART');
        Configuration::deleteByName('GTIFEATURES_REDIRECT_URL_AFTER_ADD_TO_CART');

        include(dirname(__FILE__).'/sql/uninstall.php');
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
        if (((bool)Tools::isSubmit('submitGtifeaturesModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitGtifeaturesModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Hide checkout steps in OrderController bootstrap'),
                        'name' => 'GTIFEATURES_HIDECHECKOUTSTEPS',
                        'is_bool' => true,
                        'desc' => $this->l('If active, hide checkout steps in OrderController bootstrap'),
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
                    array(
                        'type' => 'switch',
                        'label' => $this->l('NDKACF module extension: Mandatory SAP number for ordering'),
                        'name' => 'GTIFEATURES_MANAGESAPNUMBERINCART',
                        'is_bool' => true,
                        'desc' => $this->l('If active, create a mandatory SAP number field in cart (NDKACF module needed)'),
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
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use NDKACF extended data to manage product customizations data'),
                        'name' => 'GTIFEATURES_USE_NDK_EXTENDED_DATA',
                        'is_bool' => true,
                        'desc' => $this->l('If active, Use ndk_customized_data_extended to easily manage product customized data (display, order)'),
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
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use NDKACF extended data for order history and detail'),
                        'name' => 'GTIFEATURES_USE_NDK_EXTENDED_DATA_FOR_ORDER_HISTORY_AND_DETAIL',
                        'is_bool' => true,
                        'desc' => $this->l('If active, display ndk extended data informations for order history and detail pages'),
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
                    array(
                        'type' => 'switch',
                        'label' => $this->l('NDKACF : Redirect to customized URL after adding a product to cart'),
                        'name' => 'GTIFEATURES_REDIRECT_AFTER_ADD_TO_CART',
                        'is_bool' => true,
                        'desc' => $this->l('If active, create a customized URL and redirect user after "Add to cart" action'),
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
                    array(
                        'type' => 'text',
                        'label' => $this->l('NDKACF : Redirect to this customized URL'),
                        'name' => 'GTIFEATURES_REDIRECT_URL_AFTER_ADD_TO_CART',
                        'desc' => $this->l('Customized URL'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'GTIFEATURES_HIDECHECKOUTSTEPS' => Configuration::get('GTIFEATURES_HIDECHECKOUTSTEPS', true),
            'GTIFEATURES_MANAGESAPNUMBERINCART' => Configuration::get('GTIFEATURES_MANAGESAPNUMBERINCART'),
            'GTIFEATURES_USE_NDK_EXTENDED_DATA' => Configuration::get('GTIFEATURES_USE_NDK_EXTENDED_DATA'),
            'GTIFEATURES_USE_NDK_EXTENDED_DATA_FOR_ORDER_HISTORY_AND_DETAIL' => Configuration::get('GTIFEATURES_USE_NDK_EXTENDED_DATA_FOR_ORDER_HISTORY_AND_DETAIL'),
            'GTIFEATURES_REDIRECT_AFTER_ADD_TO_CART' => Configuration::get('GTIFEATURES_REDIRECT_AFTER_ADD_TO_CART'),
            'GTIFEATURES_REDIRECT_URL_AFTER_ADD_TO_CART' => Configuration::get('GTIFEATURES_REDIRECT_URL_AFTER_ADD_TO_CART'),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        if (Configuration::get('GTIFEATURES_MANAGESAPNUMBERINCART')) {
            $this->context->controller->addJS($this->_path.'/views/js/managesapfieldincart.js');
            $this->context->smarty->assign([
                'manageSapInCart' => (bool) Configuration::get('GTIFEATURES_MANAGESAPNUMBERINCART')
            ]);
        }
        if (Configuration::get('GTIFEATURES_USE_NDK_EXTENDED_DATA')) {
            $this->context->smarty->assign([
                'useNdkExtendedData' => (bool) Configuration::get('GTIFEATURES_USE_NDK_EXTENDED_DATA')
            ]);
        }
        if (Configuration::get('GTIFEATURES_USE_NDK_EXTENDED_DATA_FOR_ORDER_HISTORY_AND_DETAIL')) {
            $this->context->smarty->assign([
                'useNdkExtendedDataForOrderHistoryAndDetail' => (bool) Configuration::get('GTIFEATURES_USE_NDK_EXTENDED_DATA_FOR_ORDER_HISTORY_AND_DETAIL')
            ]);
        }
        if (Configuration::get('GTIFEATURES_REDIRECT_AFTER_ADD_TO_CART')) {
            $this->context->controller->registerJavascript('modules-gtifeatures-ajax', 'modules/' . $this->name . '/views/js/redirecttocustomurl.js', ['position' => 'bottom', 'priority' => 151]);
        }
        //$this->context->controller->addJS($this->_path.'/views/js/front.js');
        //$this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }
}
