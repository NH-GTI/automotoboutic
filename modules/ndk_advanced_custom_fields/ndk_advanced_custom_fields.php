<?php
/**
 *  Tous droits réservés NDKDESIGN
 *
 *  @author    Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*/

require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCf.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCfValues.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCfRecipients.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCfConfig.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCfSpecificPrice.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCfGroup.php';


class ndk_advanced_custom_fields extends Module
{
    public function __construct()
    {
        $this->name = 'ndk_advanced_custom_fields';
        $this->tab = 'front_office_features';
        $this->version = '99.7.6';
        $this->author = 'Ndkdesign';
        $this->displayName = $this->l('Ndk Advanced Custom Fields');
        $this->description = $this->l('Add custom fields with price');
        $this->module_key = 'd5cb948b3ef0c742e3bd4470026b9ff5';
        $this->bootstrap = true;
        $this->author_address = '0x3f68a863AC7843AF0cd6249D720625023F6E1F3a';
        $this->innerTranslations = array(
            'email' => $this->l('email'),
            'firstname' => $this->l('firstname'),
            'lastname' => $this->l('lastname'),
            'message' => $this->l('message'),
            'send_mail' => $this->l('send_mail'),
            'who_offers' => $this->l('who_offers'),
            'yes' => $this->l('yes'),
            'no' => $this->l('no'),
        );
        $this->customized_text = $this->l('Customization');
        $this->bundle_text = $this->l('Bundle');


        $this->types = array(
            array('id_type'=>0, 'name' =>$this->l('text'), 'technical' => 'text'),
            array('id_type'=>1, 'name' =>$this->l('select'), 'technical' => 'select'),
            array('id_type'=>2, 'name' =>$this->l('image'), 'technical' => 'image'),
            array('id_type'=>3, 'name' =>$this->l('color'), 'technical' => 'color'),
            array('id_type'=>28, 'name' =>$this->l('color picker'), 'technical' => 'text'),
            array('id_type'=>4, 'name' =>$this->l('radio'), 'technical' => 'radio'),
            array('id_type'=>5, 'name' =>$this->l('mask'), 'technical' => 'mask'),
            array('id_type'=>6, 'name' =>$this->l('file upload'), 'technical' => 'upload'),
            array('id_type'=>7, 'name' =>$this->l('date'), 'technical' => 'date'),
            array('id_type'=>8, 'name' =>$this->l('surface'), 'technical' => 'surface'),
            array('id_type'=>10, 'name' =>$this->l('view'), 'technical' => 'view'),
            array('id_type'=>11, 'name' =>$this->l('accessory'), 'technical' => 'accessory'),
            array('id_type'=>23, 'name' =>$this->l('accessory (no quantity)'), 'technical' => 'accessory_no_quantity'),
            array('id_type'=>17, 'name' =>$this->l('product accessory'), 'technical' => 'accessory_product'),
            array('id_type'=>24, 'name' =>$this->l('product accessory (no quantity)'), 'technical' => 'accessory_product_no_quantity'),
            array('id_type'=>22, 'name' =>$this->l('combination tab'), 'technical' => 'combination_tab'),
            array('id_type'=>26, 'name' =>$this->l('combination tab  (no quantity)'), 'technical' => 'combination_tab_no_quantity'),
            array('id_type'=>27, 'name' =>$this->l('combination list'), 'technical' => 'combination_list'),
            array('id_type'=>12, 'name' =>$this->l('recipient'), 'technical' => 'recipient'),
            array('id_type'=>13, 'name' =>$this->l('textarea'), 'technical' => 'textarea'),
            array('id_type'=>14, 'name' =>$this->l('full designer'), 'technical' => 'designer'),
            array('id_type'=>15, 'name' =>$this->l('custom font'), 'technical' => 'custom_font'),
            array('id_type'=>16, 'name' =>$this->l('checkbox'), 'technical' => 'checkbox'),
           array('id_type'=>18, 'name' =>$this->l('dimensions (text fields)'), 'technical' => 'dimensions_text'),
           array('id_type'=>19, 'name' =>$this->l('dimensions (select field)'), 'technical' => 'dimensions_select'),
           array('id_type'=>20, 'name' =>$this->l('audio MP3'), 'technical' => 'audio'),
           array('id_type'=>21, 'name' =>$this->l('price table (csv)'), 'technical' => 'table_price'),
           array('id_type'=>25, 'name' =>$this->l('Colorize product cover'), 'technical' => 'colorize'),
           array('id_type'=>99, 'name' =>$this->l('From price'), 'technical' => 'from_price'),
         );

        $this->ndk_key = Configuration::get('NDK_ACF_KEY', false);
        $specific_types = array();
        $specific_types['3CONILS'] = array('id_type'=>29, 'name' =>$this->l('Caracter designer'), 'technical' => 'caracter');
        $specific_types['UNDER_PLAY'] = array('id_type'=>30, 'name' =>$this->l('Underwear'), 'technical' => 'underwear');


        if ($this->ndk_key) {
            if (array_key_exists($this->ndk_key, $specific_types)) {
                array_push($this->types, $specific_types[$this->ndk_key]);
            }
        }


        parent::__construct();
    }


    
    public function hookStandard()
    {
        if ((float)_PS_VERSION_ > 1.6) {
            $this->registerHook('displayReassurance');
        } else {
            $this->registerHook('displayRightColumnProduct');
        }

        return true;
    }

    public function install()
    {
        $id_lang_fr = Language::getIdByIso('FR');
        $id_tab = Tab::getIdFromClassName('AdminNdkCustomFieldsNo');
        if ($id_tab > 0) {
            $this->uninstallModuleTab('AdminNdkCustomFields', $id_tab);
            $this->uninstallModuleTab('AdminNdkCustomization', $id_tab);
            $this->uninstallModuleTab('AdminNdkCustomFieldsGroup', $id_tab);
            $this->uninstallModuleTab('AdminNdkCustomFieldsNo', 0);
        }



        $this->installModuleTab('AdminNdkCustomFieldsNo', array((int)$this->context->language->id=>'Manage Custom Fields'), 0);
        $id_tab = Tab::getIdFromClassName('AdminNdkCustomFieldsNo');
        require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCfInstall.php';
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install()
        && $this->registerHook('Header')
        && $this->setNewFiles()
        && $this->hookStandard()
        && $this->registerHook('actionAdminControllerSetMedia')
        && $this->registerHook('actionFrontControllerSetMedia')
        && $this->registerHook('displayNdkCustomization')
        && $this->registerHook('ActionValidateOrder')
        && $this->registerHook('actionValidateOrder')
        && $this->registerHook('actionOrderStatusPostUpdate')
        && $this->registerHook('actionAfterDeleteProductInCart')
        && $this->registerHook('actionObjectProductInCartDeleteBefore')
        && $this->registerHook('displayPDFRecipient')
        && $this->registerHook('displayProductListReviews')
        //&& $this->registerHook('displayProductListFunctionalButtons')
        && $this->registerHook('displayProductPriceBlock')
        && $this->registerHook('displayCartExtraProductActions')
        && $this->registerHook('updateQuantity')
        && $this->installModuleTab('AdminNdkCustomFieldsGroup', array((int)$this->context->language->id=>'Fields Groups'), $id_tab)
        && $this->installModuleTab('AdminNdkCustomization', array((int)$this->context->language->id=>'Customers customizations'), $id_tab)
        && $this->installModuleTab('AdminNdkCustomFields', array((int)$this->context->language->id=>'Manage Custom Fields'), $id_tab)
        && Configuration::updateValue('NDK_ACF_COLORS', '#333399; #666699; #999966; #CCCC66; #FFFF66; #0000CC; #3333CC; #6666CC; #9999CC; #CCCC99; #FFFF99; #0000FF; #3333FF; #6666FF; #9999FF; #CCCCFF; #FFFFCC; #003300; #336633; #669966; #99CC99; #CCFFCC; #FF00FF; #006600; #339933; #66CC66; #99FF99; #CC00CC; #FF33FF; #009900; #33CC33; #66FF66; #990099; #CC33CC; #FF66FF; #00CC00; #33FF33; #660066; #993399; #CC66CC; #FF99FF; #00FF00; #330033; #663366; #996699; #CC99CC; #FFCCFF; #00FF33; #330066; #663399; #9966CC; #CC99FF; #FFCC00; #00FF66; #330099; #6633CC; #9966FF; #CC9900; #FFCC33; #00FF99; #3300CC; #6633FF; #996600; #CC9933; #FFCC66; #00FFCC; #3300FF; #663300; #996633; #CC9966; #FFCC99; #00FFFF; #330000; #663333; #996666; #CC9999; #FFCCCC; #00CCCC; #33FFFF; #660000; #993333; #CC6666; #FF9999; #009999; #33CCCC; #66FFFF; #990000; #CC3333; #FF6666; #006666; #339999; #66CCCC; #99FFFF; #CC0000; #FF3333; #003333; #336666; #669999; #99CCCC; #CCFFFF; #FF0000; #003366; #336699; #6699CC; #99CCFF; #CCFF00; #FF0033; #003399; #3366CC; #6699FF; #99CC00; #CCFF33; #FF0066; #0033CC; #3366FF; #669900; #99CC33; #CCFF66; #FF0099; #0033FF; #336600; #669933; #99CC66; #CCFF99; #FF00CC; #0066FF; #339900; #66CC33; #99FF66; #CC0099; #FF33CC; #0099FF; #33CC00; #66FF33; #990066; #CC3399; #FF66CC; #00CCFF; #33FF00; #660033; #993366; #CC6699; #FF99CC; #00CC33; #33FF66; #660099; #9933CC; #CC66FF; #FF9900; #00CC66; #33FF99; #6600CC; #9933FF; #CC6600; #FF9933; #00CC99; #33FFCC; #6600FF; #993300; #CC6633; #FF9966; #009933; #33CC66; #66FF99; #9900CC; #CC33FF; #FF6600; #006633; #339966; #66CC99; #99FFCC; #CC00FF; #FF3300; #009966; #33CC99; #66FFCC; #9900FF; #CC3300; #FF6633; #0099CC; #33CCFF; #66FF00; #990033; #CC3366; #FF6699; #0066CC; #3399FF; #66CC00; #99FF33; #CC0066; #FF3399; #006699; #3399CC; #66CCFF; #99FF00; #CC0033; #FF3366; #000000; #333333; #666666; #999999; #CCCCCC; #FFFFFF; #000033; #333300; #666600; #999900; #CCCC00; #FFFF00; #000066; #333366; #666633; #999933; #CCCC33; #FFFF33; #000099;')
        && Configuration::updateValue('NDK_ACF_UPLOAD_EXT', 'jpg;png;jpeg;png;gif;psd;pdf;ai;csv;xls;txt;zip;rar;eps;tif')
        && Configuration::updateValue('NDK_TEMPLATE_TYPE', '1')
        && Configuration::updateValue('NDK_MAKE_FLOAT', '0')
        && Configuration::updateValue('NDK_SHOW_PRICE_HT', '0')
        && Configuration::updateValue('NDK_AUTOLOAD_PACK_FIELDS', '1')
        && Configuration::updateValue('NDK_SHOW_RECAP', '0')
        && Configuration::updateValue('NDK_LET_OPEN', '0')
        && Configuration::updateValue('NDK_MAKE_SLIDE', '0')
        && Configuration::updateValue('NDK_ADMIN_CONFIG', '0')
        && Configuration::updateValue('NDK_USER_CONFIG', '0')
        && Configuration::updateValue('NDK_ORDERED_DELAY', '60')
        && Configuration::updateValue('NDK_UNORDERED_DELAY', '4')
        && Configuration::updateValue('NDK_DISABLE_LOADER', '0')
        && Configuration::updateValue('NDK_DISABLE_AUTOSCROLL', '0')
        && Configuration::updateValue('NDK_ADD_PRODUCT_PRICE', '1')
        && Configuration::updateValue('NDK_SHOW_IMG_TOOLTIP', '1')
        && Configuration::updateValue('NDK_SHOW_SOCIAL_TOOLS', '0')
        && Configuration::updateValue('NDK_SHOW_HD_PREVIEW', '1')
        && Configuration::updateValue('NDK_SHOW_IMG_PREVIEW', '0')
        && Configuration::updateValue('NDK_SHOW_QUICKNAV', '0')
        && Configuration::updateValue('NDK_ALLOW_EDIT', '1')
        && Configuration::updateValue('NDK_USE_ADMIN_NAME', '0')
        && Configuration::updateValue('NDK_KEEP_ORIGINAL_REFERENCE', '0')
        && Configuration::updateValue('NDK_SHOW_BASE_PRODUCT', '0')
        && Configuration::updateValue('NDK_SHOW_TOTAL_COST', '0')
        && Configuration::updateValue('NDK_SHOW_COMBINATION', '0')
        && Configuration::updateValue('NDK_LOAD_ACCESSORIES_FIELDS', '0')
        && Configuration::updateValue('NDK_SPLIT_PACK', '0')
        && Configuration::updateValue('NDK_SHOW_QTTY', '0')
        && Configuration::updateValue('NDKCF_API_KEY', Tools::passwdGen(16))
        && $this->registerHook('actionProductSave')
        && $this->registerHook('actionProductUpdate')
        && Configuration::updateValue('NDK_ACF_FONTS', 'https://fonts.googleapis.com/css?family=Indie+Flower|Lobster|Chewy|Alfa+Slab+One|Rock+Salt|Comfortaa|Audiowide|Yellowtail|Black+Ops+One|Frijole|Press+Start+2P|Kranky|Meddon|Bree+Serif|Love+Ya+Like+A+Sister')
        && $this->createJunkCategory();
    }

    public function uninstall()
    {
        $id_tab = Tab::getIdFromClassName('AdminNdkCustomFieldsNo');
        if (!parent::uninstall() || !$this->uninstallModuleTab('AdminNdkCustomFieldsNo', $id_tab) || !$this->uninstallModuleTab('AdminNdkCustomization', $id_tab) || !$this->uninstallModuleTab('AdminNdkCustomFieldsGroup', $id_tab) || !$this->uninstallModuleTab('AdminNdkCustomFields', 0)|| !$this->uninstallModuleTab('AdminNdkCustomFields', 0)) {
            return false;
        } else {
            return true;
        }
    }

    private function setNewFiles()
    {

/*
            if ((float)_PS_VERSION_ < 1.7)
            Tools::copy(_PS_MODULE_DIR_.'ndk_advanced_custom_fields/override_files/CartController.php', dirname(__FILE__).'/../../override/controller/front/CartController.php');
*/
        Tools::copy(_PS_MODULE_DIR_.'ndk_advanced_custom_fields/override_files/HTMLTemplateRecipient.php', dirname(__FILE__).'/../../override/classes/pdf/HTMLTemplateRecipient.php');
        //Tools::copy(_PS_MODULE_DIR_.'ndk_advanced_custom_fields/views/templates/front/recipient.tpl', dirname(__FILE__).'/../../pdf/recipient.tpl');

        /*
                    Tools::copy(dirname(__FILE__).'/../../override/classes/Cart.php', dirname(__FILE__).'/../../override/classes/Cart.php_back_ndk');
                    Tools::copy(_PS_MODULE_DIR_.'ndk_advanced_custom_fields/override_files/Cart.php', dirname(__FILE__).'/../../override/classes/Cart.php');
        */

        Tools::copy(_PS_IMG_DIR_.'.htaccess', _PS_IMG_DIR_.'.htaccess_back_ndk');
        @unlink(_PS_IMG_DIR_.'.htaccess');
        Tools::copy(_PS_MODULE_DIR_.'ndk_advanced_custom_fields/override_files/htaccess.txt', _PS_IMG_DIR_.'.htaccess');

        if (!is_dir(dirname(__FILE__).'/../../override/controllers/admin/templates/orders/')) {
            @mkdir(dirname(__FILE__).'/../../override/controllers/admin/templates/orders/', 0777);
        }

        Tools::recurseCopy(_PS_MODULE_DIR_.'ndk_advanced_custom_fields/views/templates/admin/orders'.((float)_PS_VERSION_ > 1.6 ? '17' : '').'/', dirname(__FILE__).'/../../override/controllers/admin/templates/orders');

        @unlink(dirname(__FILE__).'/../../override/controllers/admin/templates/orders/_customized_data.tpl');
        Tools::copy(_PS_MODULE_DIR_.'ndk_advanced_custom_fields/views/templates/admin/orders'.((float)_PS_VERSION_ > 1.6 ? '17' : '').'/customized_data.tpl', dirname(__FILE__).'/../../override/controllers/admin/templates/orders/_customized_data.tpl');

        @unlink(dirname(__FILE__).'/../../override/controllers/admin/templates/orders/customized_data.tpl');

        @unlink(dirname(__FILE__).'/../../cache/class_index.php');
        return true;
    }

    private function resetOldFiles()
    {
        return true;
        /*
                if (@unlink(dirname(__FILE__).'/../../override/classes/Product.php'))
                {
                    Tools::copy(dirname(__FILE__).'/../../override/classes/Product.php_back_ndk', dirname(__FILE__).'/../../override/classes/Product.php');
                    @unlink(dirname(__FILE__).'/../../cache/class_index.php');

                    return true;
                } else
                    return false;
        */
    }


    private function createJunkCategory()
    {
        $sql = 'SELECT id_category FROM '._DB_PREFIX_.'category_lang WHERE name = "'.pSQL($this->name).'"';
        $results = Db::getInstance()->executeS($sql);

        $id_category_root = (int)Db::getInstance()->getValue('
        SELECT `id_category`
        FROM `'._DB_PREFIX_.'category`
        WHERE `is_root_category` = 1');

        if (count($results) == 0) {
            $languages = Language::getLanguages(false);

            $junkCat = new Category();
            $junkCat->active = 0;


            $junkCat->id_parent = (int)$id_category_root;
            foreach ($languages as $lang) {
                $junkCat->name[$lang['id_lang']] = $this->name;
                $junkCat->link_rewrite[$lang['id_lang']] = $this->name;
            }
            $junkCat->save();
            Configuration::updateValue('NDK_ACF_CAT', (int)$junkCat->id);
        } else {
            Configuration::updateValue('NDK_ACF_CAT', (int)$results[0]['id_category']);
            $junkCat = new Category((int)$results[0]['id_category']);
            $junkCat->id_parent =  (int)$id_category_root;
            $junkCat->save();
        }
        return true;
    }




    private function installModuleTab($tabClass, $tabName, $idTabParent)
    {
        $tab = new Tab();

        $langues = Language::getLanguages(false);
        foreach ($langues as $langue) {
            $tabName[$langue['id_lang']] = $tabName[(int)$this->context->language->id];
        }


        $tab->name = $tabName;
        $tab->class_name = $tabClass;
        $tab->module = $this->name;
        $tab->id_parent = $idTabParent;
        $id_tab = $tab->save();
        if (!$id_tab) {
            return false;
        }

        //$this->installcleanPositions($tab->id, $idTabParent);

        return true;
    }

    private function uninstallModuleTab($tabClass, $idTabParent)
    {
        $idTab = Tab::getIdFromClassName($tabClass);
        if ($idTab != 0) {
            $tab = new Tab($idTab);
            $tab->delete();
            //$this->uninstallcleanPositions($idTabParent);
            return true;
        }
        return false;
    }

    public function installcleanPositions($id, $id_parent)
    {
        $result = Db::getInstance()->executeS('
            SELECT `id_tab`,`position`
            FROM `'._DB_PREFIX_.'tab`
            WHERE `id_parent` = '.(int)($id_parent).'
            AND `id_tab` != '.(int)($id).'
            ORDER BY `position`');
        $sizeof = count($result);
        for ($i = 0; $i < $sizeof; ++$i) {
            Db::getInstance()->Execute('
                UPDATE `'._DB_PREFIX_.'tab`
                SET `position` = '.(int)($result[$i]['position'] + 1).'
                WHERE `id_tab` = '.(int)($result[$i]['id_tab']));
        }

        Db::getInstance()->Execute('
                UPDATE `'._DB_PREFIX_.'tab`
                SET `position` = 1
                WHERE `id_tab` = '.(int)($id));

        return true;
    }

    public function uninstallcleanPositions($id_parent)
    {
        $result = Db::getInstance()->executeS('
            SELECT `id_tab`
            FROM `'._DB_PREFIX_.'tab`
            WHERE `id_parent` = '.(int)($id_parent).'
            ORDER BY `position`');
        $sizeof = count($result);
        for ($i = 0; $i < $sizeof; ++$i) {
            Db::getInstance()->Execute('
                UPDATE `'._DB_PREFIX_.'tab`
                SET `position` = '.($i + 1).'
                WHERE `id_tab` = '.(int)($result[$i]['id_tab']));
        }
        return true;
    }


    protected function checkEnvironment()
    {
        $cookie = new Cookie('psAdmin', '', (int)Configuration::get('PS_COOKIE_LIFETIME_BO'));
        return isset($cookie->id_employee) && isset($cookie->passwd) && Employee::checkPassword($cookie->id_employee, $cookie->passwd);
    }

    public function smartyRegisterFunctionNdk($smarty, $type, $function, $params, $lazy = true)
    {
        if (!isset($smarty->registered_plugins[$type][$function])) {
            smartyRegisterFunction($smarty, $type, $function, $params, $lazy);
        }
    }

    public function assignCommonVars()
    {
        if ((float)_PS_VERSION_ > 1.6) {
            global $smarty;
            $this->smartyRegisterFunctionNdk($smarty, 'function', 'toolsConvertPrice', 'toolsConvertPrice');
            $this->smartyRegisterFunctionNdk($smarty, 'modifier', 'json_encode', array('Tools', 'jsonEncode'));
            $this->smartyRegisterFunctionNdk($smarty, 'modifier', 'json_decode', array('Tools', 'jsonDecode'));
            $this->smartyRegisterFunctionNdk($smarty, 'function', 'dateFormat', array('Tools', 'dateFormat'));
            $this->smartyRegisterFunctionNdk($smarty, 'function', 'convertPrice', array('Product', 'convertPrice'));
            $this->smartyRegisterFunctionNdk($smarty, 'function', 'convertPriceWithCurrency', array('Product', 'convertPriceWithCurrency'));
            $this->smartyRegisterFunctionNdk($smarty, 'function', 'displayWtPrice', array('Product', 'displayWtPrice'));
            $this->smartyRegisterFunctionNdk($smarty, 'function', 'displayWtPriceWithCurrency', array('Product', 'displayWtPriceWithCurrency'));
            $this->smartyRegisterFunctionNdk($smarty, 'function', 'displayPrice', array('Tools', 'displayPriceSmarty'));
            $this->smartyRegisterFunctionNdk($smarty, 'modifier', 'convertAndFormatPrice', array('Product', 'convertAndFormatPrice')); // used twice
            $this->smartyRegisterFunctionNdk($smarty, 'function', 'addJsDef', array('Media', 'addJsDef'));
            $this->smartyRegisterFunctionNdk($smarty, 'block', 'addJsDefL', array('Media', 'addJsDefL'));
            $this->smartyRegisterFunctionNdk($smarty, 'function', 'include_ndk', array('ndk_advanced_custom_fields', 'include_ndk'));
        }
    }
    
    public static function include_ndk($filename)
    {
        $filename = str_replace('./', '', $filename['file']);
        if (file_exists(_PS_THEME_DIR_.'ndk_advanced_custom_fields/views/templates/hook/'.$filename)) {
            $my_file = _PS_THEME_DIR_.'modules/ndk_advanced_custom_fields/views/templates/hook/'.$filename;
        } else {
            $my_file = _PS_MODULE_DIR_.'ndk_advanced_custom_fields/views/templates/hook/'.$filename;
        }
            
        //var_dump($my_file);
        
        return Context::getContext()->smarty->fetch($my_file);
    }
    
    public function hookDisplayRightColumnProduct($params)
    {
        $this->assignCommonVars();
        $id_group = (int)(Validate::isLoadedObject(Context::getContext()->customer) ? Context::getContext()->customer->id_default_group : 0);
        $key_cache = (int)Tools::getValue('id_product').'_'.(int)Context::getContext()->language->id.'_'.(int)Context::getContext()->shop->id.'_'.$id_group.'_'.(int)Context::getContext()->currency->id;
        $expire = time() + 43200;
        $cache_found = false;
        if ((int)Configuration::get('NDK_ACF_CACHE') == 1) {
            $search_cache = 'SELECT * FROM '._DB_PREFIX_.'ndk_customization_field_cache WHERE key_cache = "'.$key_cache.'" AND expire > '.time();
            $cache_found =  Db::getInstance()->getRow($search_cache);
        }

        //CONFIG BOXES
        $default_config = 0;
        if (Context::getContext()->customer->id > 0) {
            $user_configs = NdkCfConfig::getConfigs((int)Context::getContext()->customer->id, (int)Tools::getValue('id_product'), (int)Context::getContext()->language->id);
        } else {
            $user_configs = false;
        }

        if (Configuration::get('NDK_ADMIN_CONFIG') == 1) {
            $admin_configs = NdkCfConfig::getAdminConfigs((int)Tools::getValue('id_product'), (int)Context::getContext()->language->id);
        } else {
            $admin_configs = false;
        }

        if ($admin_configs) {
            foreach ($admin_configs as $config) {
                if ($config['default_config'] == 1) {
                    $default_config = $config['id_ndk_customization_field_configuration'];
                }
            }
        }

        if ($this->checkEnvironment()) {
            $is_admin_logged = true;
        } else {
            $is_admin_logged = false;
        }

        if (Configuration::get('NDK_USER_CONFIG') == 1) {
            $allow_user_config = true;
        } else {
            $allow_user_config = false;
        }

        if (Configuration::get('NDK_ADMIN_CONFIG') == 1) {
            $allow_admin_config = true;
        } else {
            $allow_admin_config = false;
        }

        $this->context->smarty->assign(array(
                'product_id' => (int)Tools::getValue('id_product'),
                'displayPriceHT' => Configuration::get('NDK_SHOW_PRICE_HT'),
                'ndkcf_show_quantity' => Configuration::get('NDK_SHOW_QTTY'),
                'allow_user_config' => $allow_user_config,
                'allow_admin_config' => $allow_admin_config,
                'is_https' => (Configuration::get('PS_SSL_ENABLED') == 1 && Configuration::get('PS_SSL_ENABLED_EVERYWHERE') == 1 ? true : false),
                'user_configs' => $user_configs,
                'admin_configs' => $admin_configs,
                'is_admin_logged' => $is_admin_logged,
                'id_lang' => (int)Context::getContext()->language->id,
                'logged' => (Context::getContext()->customer->id > 0 ? true : false),
                'default_config' => $default_config,
                'base_dir_ssl' => Tools::getHttpHost(true).__PS_BASE_URI__
            ));
        $config_boxes = $this->display(__FILE__, 'config_boxes.tpl');
        //$config_boxes = $this->context->smarty->fetch($this->local_path.'views/templates/hook/config_boxes.tpl');
        //CONFIG BOXES
        if ($cache_found && $cache_found['key_cache'] == $key_cache && (int)Tools::getValue('id_product') > 0 && (int)Configuration::get('NDK_ACF_CACHE') == 1) {
            //die($key_cache);
            $this->context->smarty->assign('template', $cache_found['content']);
            $this->context->smarty->assign('config_boxes', $config_boxes);
            return $this->display(__FILE__, 'ndkcf_cached.tpl');
        } else {
            $ndkpackitems = array();
            $ndkcsfields = array();
            $features = array();
            $fieldsItems = false;
            $isFields = false;

            $product_reference = Db::getInstance()->getValue('SELECT reference FROM '._DB_PREFIX_.'product WHERE id_product = '.(int)Tools::getValue('id_product'));
            if (Tools::substr($product_reference, 0, 7) == 'custom-') {
                //var_dump($product_reference);
                $id_product_to_redirect =  explode('-', $product_reference);
                if (Tools::getValue('customref')) {
                    Tools::redirect(
                        Context::getContext()->link->getProductLink(
                            (int)$id_product_to_redirect[1],
                            null,
                            null,
                            null,
                            null,
                            null,
                            (int)$id_product_to_redirect[2],
                            false,
                            false,
                            false,
                            array('customref'=>$product_reference, 'refProd' => (int)Tools::getValue('id_product') )
                        )
                    );
                } else {
                    Tools::redirect(
                        Context::getContext()->link->getProductLink(
                            (int)$id_product_to_redirect[1],
                            null,
                            null,
                            null,
                            null,
                            null,
                            (int)$id_product_to_redirect[2]
                        ),
                        'HTTP/1.1 301 Moved Permanently'
                    );
                }
            }
            if (Pack::isPack((int)Tools::getValue('id_product')) && (int)Configuration::get('NDK_AUTOLOAD_PACK_FIELDS') == 1) {
                $items = Db::getInstance()->executeS('SELECT id_product_item, id_product_attribute_item, quantity FROM `'._DB_PREFIX_.'pack` where id_product_pack = '.(int)Tools::getValue('id_product'));
                //array_push($items, array('id_product_item' => (int)Tools::getValue('id_product'), 'id_product_attribute_item' => 0));
                foreach ($items as $item) {
                    //$product =new Product($item['id_product_item'], Context::getContext()->language->id);
                    $id_category_default = Db::getInstance()->getRow('SELECT id_category_default FROM `'._DB_PREFIX_.'product` WHERE id_product = '.(int)$item['id_product_item']);

                    $pname = Db::getInstance()->getRow('SELECT name, link_rewrite FROM `'._DB_PREFIX_.'product_lang` WHERE id_product = '.(int)$item['id_product_item'].' AND id_lang = '.(int)Context::getContext()->language->id);
                    $ndkpackitems[$item['id_product_item']]['name'] = $pname['name'];
                    $ndkpackitems[$item['id_product_item']]['features'] = Product::getFrontFeaturesStatic((int)Context::getContext()->language->id, $item['id_product_item']);
                    $ndkpackitems[$item['id_product_item']]['link_rewrite'] = $pname['link_rewrite'];
                    $ndkpackitems[$item['id_product_item']]['cover'] = Product::getCover($item['id_product_item'])['id_image'];

                    $ndkpackitems[$item['id_product_item']]['ndkcsfields'] = NdkCf::getCustomFields($item['id_product_item'], $id_category_default['id_category_default'])['fields'];
                    if (count($ndkpackitems[$item['id_product_item']]['ndkcsfields']) > 0) {
                        $fieldsItems = true;
                    }
                }

                $ndkcsfields = NdkCf::getCustomFields((int)Tools::getValue('id_product'), $id_category_default['id_category_default']);
            } else {
                //$product =new Product(Tools::getValue('id_product'));
                $features = Product::getFrontFeaturesStatic(Context::getContext()->language->id, (int)Tools::getValue('id_product'));
                $id_category_default = Db::getInstance()->getRow('SELECT id_category_default FROM `'._DB_PREFIX_.'product` WHERE id_product = '.(int)Tools::getValue('id_product'));
                $ndkcsfields = NdkCf::getCustomFields((int)Tools::getValue('id_product'), $id_category_default['id_category_default']);
                if (count($ndkcsfields['fields']) > 0) {
                    $isFields = true;
                }
            }

            //$this->context->controller->registerJavascript('type_29', 'modules/'.$this->name.'/views/js/types/type_29.js', array('position' => 'bottom', 'priority' => 150));

            /*
                        foreach($ndkcsfields['types'] as $type)
                            $this->context->controller->registerJavascript('type_'.$type, 'modules/'.$this->name.'/views/js/types/type_'.$type.'.js', array('position' => 'bottom', 'priority' => 150));
            */


            /*if($isFields || $fieldsItems)
                $this->context->controller->addJS(_PS_MODULE_DIR_.'blockcart/ajax-cart.js');*/
            $fonts = array();
            if (Configuration::get('NDK_ACF_FONTS')) {
                $families = explode('family=', Configuration::get('NDK_ACF_FONTS'));
                $families = $families[1];
                $fonts = explode('|', $families);
                $fonts = str_replace(array('\'', '"', "'"), '', $fonts);
                $i = 0;
                foreach ($fonts as $key => $value) {
                    $fonts[$i] = str_replace('+', ' ', $value);
                    $i ++;
                }
            }

            $colors = array();
            if (Configuration::get('NDK_ACF_COLORS')) {
                $colors = explode(';', Configuration::get('NDK_ACF_COLORS'));
            }



            $oos = Db::getInstance()->execute('SELECT out_of_stock FROM `'._DB_PREFIX_.'product` WHERE id_product = '.(int)Tools::getValue('id_product'));

            $encountredField = array();




            foreach ($ndkcsfields['fields'] as $field) {
                array_push($encountredField, $field['id_ndk_customization_field']);
            }


            $this->context->smarty->assign(array(
                'ndkcsfields' => $ndkcsfields['fields'],
                'product_id' => (int)Tools::getValue('id_product'),
                'ndkpackitems' => $ndkpackitems,
                'fieldsItems' => $fieldsItems,
                'features' => $features,
                'fonts' => $fonts,
                'colors_ndk' => $colors,
                'make_float' => Configuration::get('NDK_MAKE_FLOAT'),
                'displayPriceHT' => Configuration::get('NDK_SHOW_PRICE_HT'),
                'showRecap' => Configuration::get('NDK_SHOW_RECAP'),
                'showQuicknav' => Configuration::get('NDK_SHOW_QUICKNAV'),
                'allowEdit' => Configuration::get('NDK_ALLOW_EDIT'),
                'let_open' => Configuration::get('NDK_LET_OPEN'),
                'make_slide' => Configuration::get('NDK_MAKE_SLIDE'),
                'allow_user_config' => $allow_user_config,
                'allow_admin_config' => $allow_admin_config,
                'template_type' => Configuration::get('NDK_TEMPLATE_TYPE'),
                'is_https' => (Configuration::get('PS_SSL_ENABLED') == 1 && Configuration::get('PS_SSL_ENABLED_EVERYWHERE') == 1 ? true : false),
                'is_admin_logged' => $is_admin_logged,
                'id_lang' => (int)Context::getContext()->language->id,
                'logged' => (Context::getContext()->customer->id > 0 ? true : false),
                'allow_oosp' => Product::isAvailableWhenOutOfStock($oos),
                'encountredField' => $encountredField,
                'jsonDatas' => $ndkcsfields['jsonDatas'],
                'config_boxes' => $config_boxes,
                'img_col_dir'   => _PS_COL_IMG_DIR_,
                'theme_col_dir'   => _THEME_COL_DIR_,
                'seeMoreTextTrans' => $this->l('See more'),
                'seeLessTextTrans' => $this->l('See less'),
                'nonContractualPic' => $this->l('Non-contractual pictures')
            ));


            $this->hookActionFrontControllerSetMedia(array('types' => $ndkcsfields['types']));
            Hook::exec(
                'actionFrontControllerSetMedia',
                array('types' => $ndkcsfields['types']),
                $this->id
            );


            if ((int)Configuration::get('NDK_ACF_CACHE') == 1) {
                Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'ndk_customization_field_cache WHERE key_cache = "'.$key_cache.'"');
            }
            $template = $this->display(__FILE__, 'ndkcf.tpl');
            //$template = $this->context->smarty->fetch($this->local_path.'views/templates/hook/ndkcf.tpl');
            if (get_magic_quotes_gpc()) {
                $template = addslashes($template);
            }

            $template = '<!--START CACHE NDK-->'.$this->minifyHTML($template).'<!--END CACHE NDK-->';

            $setCache = 'INSERT INTO '._DB_PREFIX_.'ndk_customization_field_cache (key_cache, content, expire) VALUES ("'.pSQL($key_cache).'", "'.pSQL($template, true).'", '.$expire.')';
            //var_dump($setCache);
            if ((int)Configuration::get('NDK_ACF_CACHE') == 1) {
                Db::getInstance()->execute($setCache);
            }
            $this->context->smarty->assign('template', $this->minifyHTML($template));
            if ((int)Tools::getValue('id_product') > 0) {
                if (
                    Configuration::get('NDK_TEMPLATE_TYPE') == 'popup'
                    || Tools::getValue('NDK_TEMPLATE_TYPE') == 'popup'
                    || (Configuration::get('NDK_TEMPLATE_TYPE') == 'popup_mobile' && ($this->context->isMobile() || $this->context->isTablet()))
                ) {
                    return $this->display(__FILE__, 'popup_ndkcf.tpl');
                } else {
                    return $this->display(__FILE__, 'ndkcf_uncached.tpl');
                }
            }
        }
    }

    public function hookActionFrontControllerSetMedia($params)
    {
        if ('product' === $this->context->controller->php_self) {
            /*
                        $this->context->controller->registerStylesheet(
                            'module-modulename-style',
                            'modules/'.$this->name.'/css/modulename.css',
                            [
                              'media' => 'all',
                              'priority' => 200,
                            ]
                        );
            */
            /*foreach($params['types'] as $id_type)
            {
                $type = NdkCf::getTypeTechName($id_type);
                //var_dump('modules/'.$this->name.'/views/js/types/type_'.$type.'.js');
                $this->context->controller->registerJavascript(
                'module-'.$this->name.'-'.$type,
                'modules/'.$this->name.'/views/js/types/type_'.$type.'.js',
                [
                  'position' => 'bottom',
                  'priority' => 200,
                ]
            );
            }   */
        }
    }

    public static function minifyHTML($html_content)
    {
        if (Tools::strlen($html_content) > 0) {
            require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/tools/minify_html.class.php';
            $html_content = str_replace(chr(194).chr(160), '&nbsp;', $html_content);
            if (trim($minified_content = Minify_HTML_NDK::minify($html_content, array('cssMinifier', 'jsMinifier'))) != '') {
                $html_content = $minified_content;
            }

            return $html_content;
        }
        return false;
    }


    public function hookDisplayNdkCustomization($params)
    {
        if (Context::getContext()->controller->php_self == 'product') {
            return $this->hookDisplayRightColumnProduct($params);
        }
    }

    public function hookDisplayReassurance($params)
    {
        if (Context::getContext()->controller->php_self == 'product') {
            return $this->hookDisplayRightColumnProduct($params);
        }
    }

    public function hookDisplayCartExtraProductActions($params)
    {
        $result = array();
        $customizedDatas = Product::getAllCustomizedDatas((int)Context::getContext()->cart->id);
        $link_edit = false;

        $product_reference = $params['product']['reference'];
        if (isset($customizedDatas[$params['product']['id_product']])) {
            //var_dump($id_customization);
            $search_name = 'custom-'.$params['product']['id_product'].'-'.$params['product']['id_product_attribute'].'-'.Context::getContext()->cart->id.'-'.$params['product']['id_customization'];

            $searchConfig = Db::getInstance()->executeS(
                'SELECT fc.id_ndk_customization_field_configuration as id, fcl.name, fc.id_product FROM '._DB_PREFIX_.'ndk_customization_field_configuration fc
                    LEFT JOIN `'._DB_PREFIX_.'ndk_customization_field_configuration_lang` fcl
                        ON (fc.`id_ndk_customization_field_configuration` = fcl.`id_ndk_customization_field_configuration` AND fcl.`id_lang` = '.(int)Context::getContext()->language->id.')
                    WHERE fc.id_customization = '.(int)$params['product']['id_customization']
            );

            //var_dump($searchConfig);

            if (count($searchConfig) > 0 && (int)$searchConfig[0]['id_product'] == (int)$params['product']['id_product']) {
                $editConfig = $searchConfig[0];
                //$link_edit = Context::getContext()->link->getProductLink((int)$editConfig['id_product']).'?customref='.$editConfig['name'].'&refProd='.(int)$editConfig['id_product'];
                if (Tools::substr($params['product']['reference'], 0, 7) == 'custom-') {
                    $id_product_to_redirect =  explode('-', $product_reference);
                    //var_dump((int)$id_product_to_redirect[2]);
                    if ((float)_PS_VERSION_ > 1.6) {
                        $link_edit = Context::getContext()->link->getProductLink(
                            (int)$id_product_to_redirect[1],
                            null,
                            null,
                            null,
                            null,
                            null,
                            (int)$id_product_to_redirect[2],
                            false,
                            false,
                            false,
                            array('customref'=>$product_reference, 'refProd' => (int)$params['product']['id_product'] )
                        );
                    } else {
                        $link_edit = Context::getContext()->link->getProductLink((int)$id_product_to_redirect[1]).'?customref='.$product_reference.'&refProd='.(int)$params['product']['id_product'];
                    }
                } else {
                    if ((float)_PS_VERSION_ > 1.6) {
                        $link_edit = Context::getContext()->link->getProductLink(
                            (int)$editConfig['id_product'],
                            null,
                            null,
                            null,
                            null,
                            null,
                            (int)$params['product']['id_product_attribute'],
                            false,
                            false,
                            false,
                            array('customref'=>$editConfig['name'], 'refProd' => (int)$editConfig['id_product'] )
                        );
                    } else {
                        $link_edit = Context::getContext()->link->getProductLink((int)$editConfig['id_product']).'?customref='.$editConfig['name'].'&refProd='.(int)$editConfig['id_product'];
                    }
                }
            } elseif (Tools::substr($params['product']['reference'], 0, 7) == 'custom-') {
                $id_product_to_redirect =  explode('-', $product_reference);
                if ((float)_PS_VERSION_ > 1.6) {
                    $link_edit = Context::getContext()->link->getProductLink(
                        (int)$id_product_to_redirect[1],
                        null,
                        null,
                        null,
                        null,
                        null,
                        (int)$id_product_to_redirect[2],
                        false,
                        false,
                        false,
                        array('customref'=>$product_reference, 'refProd' => (int)$params['product']['id_product'] )
                    );
                } else {
                    $link_edit = Context::getContext()->link->getProductLink((int)$id_product_to_redirect[1]).'?customref='.$product_reference.'&refProd='.(int)$params['product']['id_product'];
                }
            }

            $result[] = array(
                    'link_edit' => $link_edit,
                    'id_product' => $params['product']['id_product'],
                    'customizationId' => $params['product']['id_customization'],
                    'id_address_delivery' => $params['product']['id_address_delivery'],
                    'id_product_attribute' => $params['product']['id_product_attribute']
                    );
        }


        $this->context->smarty->assign(array(
            'result' => $result,
            'ps_version' => (float)_PS_VERSION_,
            'is_https' => (Configuration::get('PS_SSL_ENABLED') == 1 && Configuration::get('PS_SSL_ENABLED_EVERYWHERE') == 1 ? true : false),
        ));

        return $this->display(__FILE__, 'shopping-cart.tpl');
    }

    public function hookActionAdminControllerSetMedia($params)
    {
        $controllers = array('AdminNdkCustomFields','AdminNdkCustomFieldsGroup', 'AdminNdkCustomization' );
        if(in_array(Tools::getValue('controller'), $controllers))
        {
            $this->context->controller->addJquery();
            $this->context->controller->addCSS(($this->_path).'views/css/admin.css', 'all');
            $this->context->controller->addJS($this->_path.'views/js/admin.js');
        }
    }

    public static function class_exists_ndk($class)
    {
        return class_exists($class);
    }

    public function hookHeader($params)
    {
        global $smarty;
        $this->smartyRegisterFunctionNdk($smarty, 'function', 'class_exists', array('ndk_advanced_custom_fields', 'class_exists_ndk'));
        $this->context->controller->addJqueryPlugin('fancybox');
        //$this->context->controller->addJqueryPlugin('chosen');
        $this->context->controller->addJqueryPlugin('jquery.colorpicker');
        if ((float)_PS_VERSION_ < 1.7) {
            $this->context->controller->addJqueryUI('ui.tooltip');
        }
        $this->context->controller->addCSS(($this->_path).'views/css/front.css', 'all');
        $this->context->controller->addCSS(($this->_path).'views/css/chosen.css', 'all');
        //$this->context->controller->addJS($this->_path.'views/js/ndklazy.js');
        $this->assignCommonVars();
        $this->context->controller->addJS($this->_path.'views/js/fromprice.js');
        if (Tools::getValue('id_product') && Context::getContext()->controller->php_self == 'product') {
            if (Context::getContext()->controller->php_self == 'product') {
                $this->context->controller->addCSS(($this->_path).'views/css/font-awesome.min.css', 'all');
                $this->context->controller->addCSS(($this->_path).'views/css/ndkacf.css', 'all');
                $this->context->controller->addCSS(($this->_path).'views/css/fontselector.css', 'all');
                $this->context->controller->addCSS(($this->_path).'views/css/loader.css', 'all');
                $this->context->controller->addCSS(($this->_path).'views/css/ndkdesigner.css', 'all');
                $this->context->controller->addCSS(($this->_path).'views/css/custom.css', 'all');
                $this->context->controller->addCSS(Configuration::get('NDK_ACF_FONTS').'&effect=shadow-multiple|3d-float|fire', 'all');
                $this->context->controller->addJS($this->_path.'views/js/jquery.touchSwipe.min.js');
                $this->context->controller->addJS($this->_path.'views/js/ndkdesigner.js');
                $this->context->controller->addJS($this->_path.'views/js/jquery.resize.js');
                $this->context->controller->addJS($this->_path.'views/js/jquery.chosen.js');
                $this->context->controller->addJqueryUI('ui.draggable');
                $this->context->controller->addJqueryUI('ui.datepicker');
                $this->context->controller->addJqueryUI('ui.resizable');
                $this->context->controller->addJqueryUI('ui.sortable');
                $this->context->controller->addJqueryPlugin('colorpicker');
                $this->context->controller->addJqueryPlugin('fancybox');
                $this->context->controller->addJS($this->_path.'views/js/poper.min.js');
                $this->context->controller->addJS($this->_path.'views/js/imagetracer_v1.2.4.js');
                $this->context->controller->addJS($this->_path.'views/js/ndkacf.js');
                //$this->context->controller->addJS($this->_path.'views/js/url.js');
                if (Tools::getIsset('testNdk')) {
                    $this->context->controller->addJS($this->_path.'views/js/ndkacfDEV.js');
                }
                    
                $this->context->controller->addJS($this->_path.'views/js/custom.js');
                    
                foreach ($this->types as $type) {
                    $this->context->controller->addJS($this->_path.'views/js/types/type_'.$type['id_type'].'.js');
                    $this->context->controller->addCSS($this->_path.'views/css/fields/type_'.$type['id_type'].'.css');
                }

                /*if ((float)_PS_VERSION_ < 1.7)
                $this->context->controller->addJS($_SERVER['DOCUMENT_ROOT'].'/modules/blockcart/ajax-cart.js');*/

                if ((int)Configuration::get('NDK_SHOW_IMG_PREVIEW') > 0) {
                    $this->context->controller->addJS($this->_path.'views/js/html2canvas.ndk.js');
                    $this->context->controller->addJS($this->_path.'views/js/html2canvas.svg.js');
                }
                $this->context->controller->addJS($this->_path.'views/js/nodecontainer.js');
                $this->context->controller->addJS($this->_path.'views/js/jquery.fontselector.js');
                $this->context->controller->addJS($this->_path.'views/js/jquery.simulate.js');
                $this->context->controller->addJS($this->_path.'views/js/jquery.lettering.js');
                $this->context->controller->addJS($this->_path.'views/js/circletype.min.js');
                $this->context->controller->addJS($this->_path.'views/js/jquery.maxlength.js');
                $this->context->controller->addJS($this->_path.'views/js/jquery.mask.js');
                $this->context->controller->addJS($this->_path.'views/js/rawinflate.js');
                $this->context->controller->addJS($this->_path.'views/js/rawdeflate.js');
                if (Configuration::get('NDK_DISABLE_LOADER') != 1) {
                    $this->context->controller->addJS($this->_path.'views/js/ndkloader.min.js');
                }

                $this->context->controller->addJqueryPlugin(array('tagify', 'scrollTo'));
                $this->context->controller->addCSS(($this->_path).'views/css/dynamicprice.css');
                if ((float)_PS_VERSION_ > 1.6) {
                    $this->context->controller->registerJavascript('jquery-rotatable', 'modules/'.$this->name.'/views/js/jquery.ui.rotatable.min.js', array('position' => 'bottom', 'priority' => 150));
                    $this->context->controller->registerJavascript('ndkacf', 'modules/'.$this->name.'/views/js/dynamicprice.js', array('position' => 'bottom', 'priority' => 100));
                    $this->context->controller->registerJavascript('touch-punch', 'modules/'.$this->name.'/views/js/jquery.ui.touch-punch.min.js', array('position' => 'bottom', 'priority' => 100));
                    $this->context->controller->registerJavascript('ndkTools', 'modules/'.$this->name.'/views/js/ndkTools.js', array('position' => 'bottom', 'priority' => 150));
                } else {
                    $this->context->controller->addJS(($this->_path).'views/js/dynamicprice.js');
                    $this->context->controller->addJS($this->_path.'views/js/jquery.ui.touch-punch.min.js');
                    $this->context->controller->addJS($this->_path.'views/js/jquery.ui.rotatable.min.js');
                    $this->context->controller->addJS($this->_path.'views/js/ndkTools.js');
                }
            }


            if (Configuration::get('NDK_ACF_FONTS')) {
                $families = explode('family=', Configuration::get('NDK_ACF_FONTS'));
                $families = $families[1];
                $fonts = explode('|', $families);
                $fonts = str_replace(array('\'', '"'), '', $fonts);
                $i = 0;
                foreach ($fonts as $key => $value) {
                    $fonts[$i] = str_replace('+', ' ', $value);
                    $i ++;
                }
            }
            if (Configuration::get('NDK_ACF_COLORS')) {
                $colors = explode(';', Configuration::get('NDK_ACF_COLORS'));
            }

            $oos = Db::getInstance()->execute('SELECT out_of_stock FROM `'._DB_PREFIX_.'product` WHERE id_product = '.(int)Tools::getValue('id_product'));

            if (Tools::getValue('make_slide')) {
                $make_slide = (int)Tools::getValue('make_slide');
            } else {
                $make_slide = Configuration::get('NDK_MAKE_SLIDE');
            }

            if (Tools::getValue('let_open')) {
                $let_open = (int)Tools::getValue('let_open');
            } else {
                $let_open = Configuration::get('NDK_LET_OPEN');
            }

            if (Tools::getValue('make_float')) {
                $make_float = (int)Tools::getValue('make_float');
            } else {
                $make_float = Configuration::get('NDK_MAKE_FLOAT');
            }

            if (Tools::getValue('template_type')) {
                $template_type = (int)Tools::getValue('template_type');
            } else {
                $template_type = Configuration::get('NDK_TEMPLATE_TYPE');
            }

            if (Tools::getValue('displayPriceHT')) {
                $displayPriceHT = (int)Tools::getValue('displayPriceHT');
            } else {
                $displayPriceHT = Configuration::get('NDK_SHOW_PRICE_HT');
            }

            if (Tools::getValue('showRecap')) {
                $showRecap = (int)Tools::getValue('showRecap');
            } else {
                $showRecap = Configuration::get('NDK_SHOW_RECAP');
            }

            if (Tools::getValue('showQuicknav')) {
                $showQuicknav = (int)Tools::getValue('showQuicknav');
            } else {
                $showQuicknav = Configuration::get('NDK_SHOW_QUICKNAV');
            }

            if (Tools::getValue('allowEdit')) {
                $allowEdit = (int)Tools::getValue('allowEdit');
            } else {
                $allowEdit = Configuration::get('NDK_ALLOW_EDIT');
            }


            $old_ref = false;
            $editConfig = 0;
            $conf = false;
            $product_visibility = Db::getInstance()->getValue('SELECT visibility FROM '._DB_PREFIX_.'product_shop WHERE id_shop ='.(int)Context::getContext()->shop->id.' AND id_product = '.(int)Tools::getValue('id_product'));
            $nofollow = false;
            if ($product_visibility == 'none') {
                $nofollow = true;
            }

            if (Tools::getValue('customref')) {
                $old_ref = Tools::getValue('customref');
                $searchConfig = Db::getInstance()->executeS(
                    'SELECT fc.id_ndk_customization_field_configuration as id FROM '._DB_PREFIX_.'ndk_customization_field_configuration fc
                        LEFT JOIN `'._DB_PREFIX_.'ndk_customization_field_configuration_lang` fcl
                            ON (fc.`id_ndk_customization_field_configuration` = fcl.`id_ndk_customization_field_configuration` AND fcl.`id_lang` = '.(int)Context::getContext()->language->id.')
                        WHERE fcl.name="'.pSQL(Tools::getValue('customref')).'"'
                );
                if (count($searchConfig) > 0) {
                    $editConfig = (int)$searchConfig[0]['id'];
                }
            } elseif (Tools::getValue('id_ndk_customization_field_configuration')) {
                $editConfig = (int)Tools::getValue('id_ndk_customization_field_configuration');
                $old_ref=false;
                $conf = new NdkCfConfig($editConfig);
            }

            $currSign = Context::getContext()->currency->sign;
            switch ($currSign) {
                        case '$':
                                 $currencyFormat17 = 1;
                                break;
                        case '€':
                                 $currencyFormat17 = 2;
                                break;
                        default:
                                 $currencyFormat17 = 1;
                                 break;
                        }


            $this->context->smarty->assign(array(
                    'fonts' => $fonts,
                    'colors_ndk' => $colors,
                    'make_float' => $make_float,
                    'let_open' => $let_open,
                    'template_type' => $template_type,
                    'allow_oosp' => Product::isAvailableWhenOutOfStock($oos),
                    'make_slide' => $make_slide,
                    'ps_version' => (float)_PS_VERSION_,
                    'edit_config' => $editConfig,
                    'old_ref' => $old_ref,
                    'displayPriceHT' => $displayPriceHT,
                    'showRecap' => $showRecap,
                    'showQuicknav' => $showQuicknav,
                    'allowEdit' => $allowEdit,
                    'ref_prod' => (int)Tools::getValue('refProd'),
                    'conf' => $conf,
                    'is_https' => (Configuration::get('PS_SSL_ENABLED') == 1 && Configuration::get('PS_SSL_ENABLED_EVERYWHERE') == 1 ? true : false),
                    'currencyFormat17' => $currencyFormat17,
                    'nofollow' => $nofollow

                ));


            return $this->display(__FILE__, 'headers.tpl');
        } elseif (Context::getContext()->controller->php_self == 'order' || Context::getContext()->controller->php_self == 'order-opc' || Context::getContext()->controller->php_self == 'cart') {
            $this->context->controller->addJS($this->_path.'views/js/order.js');
            //$this->context->controller->addJS($this->_path.'views/js/custom.js');
            $this->context->controller->addCSS(($this->_path).'views/css/order.css', 'all');
            $this->context->controller->addCSS(($this->_path).'views/css/custom.css', 'all');
            $this->context->controller->addJqueryPlugin('fancybox');
        } else {
            return $this->display(__FILE__, 'common_headers.tpl');
        }
    }

    public function hookActionAfterDeleteProductInCart($params)
    {
        if ((int)$params['id_product'] > 0) {
            NdkCf::deleteTempPackFromCart((int)$params['id_product'], (int)$params['customization_id'], (int)$params['id_product_attribute']);
        }
    }

    public function hookActionObjectProductInCartDeleteBefore($params)
    {

        /*if ((int)$params['id_product'] > 0 && (float)_PS_VERSION_ > 1.6 )
        {
            NdkCf::deleteTempPackFromCart17((int)$params['id_product'], (int)$params['customization_id'], (int)$params['id_product_attribute']);
        }*/
        //return true;
    }

    public function checkOptionsStock($params)
    {
        $order = new Order((int)$params['id_order']);
        $context = Context::getContext();
        $id_lang = (int)$context->language->id;
        $id_shop = (int)$context->shop->id;
        $products = $order->getProducts();
        $new_os = $params['newOrderStatus'];


        foreach ($products as $key => $product) {
            //on s'occupe des envois si destinataire
            if ($product['customizedDatas']) {
                foreach ($product['customizedDatas'] as $row) {
                    //var_dump($row);
                    foreach ($row as $k => $v) {
                        if (isset($k) && $k > 0) {
                            $id_customization = $k;
                        }
                    }
                }

                $product_reference = Db::getInstance()->getValue('SELECT reference FROM '._DB_PREFIX_.'product WHERE id_product = '.(int)$product['product_id']);

                if (Tools::substr($product_reference, 0, 7) == 'custom-') {
                    $id_product_to_search =  explode('-', $product_reference);
                    $my_id_product = $id_product_to_search[1];
                } else {
                    $my_id_product = $product['product_id'];
                }
                if ($my_id_product != $product['product_id']) {
                    $my_id_product_attribute = 0;
                } else {
                    $my_id_product_attribute = $product['product_attribute_id'];
                }


                $custom_values = NdkCf::getCartProductCustomization(Context::getContext()->cart->id, $product['product_id'], $my_id_product_attribute, $my_id_product);
                //var_dump($custom_values);

                foreach ($custom_values as $custom_value) {
                    if ($custom_value['set_quantity'] && $custom_value['set_quantity'] == 1) {
                        $old_quantity = $custom_value['quantity'];
                        $value = new NdkCfValues((int)$custom_value['id_ndk_customization_field_value']);
                        if ($new_os->logable) {
                            $value->quantity = (int)$old_quantity - $custom_value['orderQuantity'];
                        } elseif ($new_os->id == (int)Configuration::get('PS_OS_CANCELED')) {
                            $value->quantity = (int)$old_quantity + $custom_value['orderQuantity'];
                        }

                        $value->save();
                    }
                }
            }
        }
    }


    public function hookActionOrderStatusPostUpdate($params)
    {
        $this->checkOptionsStock($params);
        $order = new Order((int)$params['id_order']);
        $new_os = $params['newOrderStatus'];
        $products = $order->getProducts();
        $id_customization = 0;
        if (Configuration::get('NDK_SPLIT_PACK') == 1) {
            $items = array();
            $my_products = array();
            $encounter = array();
            foreach ($order->getProducts() as $product) {
                if (strpos($product['product_reference'], 'custom-') !== false && Pack::isPack($product['id_product'])) {
                    array_push($items, NdkCf::splitTempPackFromOrder((int)$product['id_product'], $order, (int)$product['id_order_invoice'], (int)$product['product_quantity']));
                }
            }
            if (count($items) > 0) {
                foreach ($items as $pack) {
                    foreach ($pack as $k => $prod) {
                        if (in_array($k, $encounter)) {
                            $my_products[$k]['cart_quantity']+=$prod['cart_quantity'];
                        } else {
                            $my_products[$k] = $prod;
                            $encounter[] = $k;
                        }
                    }
                }
                $order_detail = new OrderDetail(null, null, Context::getContext());
                $order_detail->createList($order, new Cart($order->id_cart), $order->current_state, $my_products, 0, true);
                /*foreach($my_products as $my_product)
                {
                    NdkCf::createOrderDetail($order, new Cart($order->id_cart), $my_product, $order->current_state, 0);
                }*/
            }
        }

        if ($new_os->paid) {
            $this->generatePdfRecipient($order->id);
        }

        /*if($new_os->shipped)
            return NdkCf::deleteTempPackFromCart((int)Tools::getValue('id_product'), (int)Tools::getValue('id_customization'));*/



        return true;
    }

    public function generatePdfRecipient($id_order)
    {
        $order = new Order((int)$id_order);
        $products = $order->getProducts();
        $id_customization = 0;

        foreach ($products as $key => $product) {
            if ($product['customizedDatas']) {
                foreach ($product['customizedDatas'] as $row) {
                    //var_dump($row);
                    foreach ($row as $k => $v) {
                        if (isset($k) && $k > 0) {
                            $id_customization = $k;
                        }

                        if ($id_customization > 0) {
                            $recipient = new NdkCfRecipients((int)NdkCfRecipients::getRecipientForOrder($order->id_cart, $product['product_id'], $product['product_attribute_id'], $id_customization));
                            if ($recipient->id > 0) {
                                $recipient->id_order = $order->id;
                                $recipient->title = $product['product_name'];
                                $recipient->save();
                                NdkCfRecipients::sendGiftMail($order, $recipient);
                            }
                        }
                    }
                }
            }
        }
        return true;
    }


    public function hookDisplayPDFRecipient($params)
    {
    }

    public function actionValidateOrder($params)
    {
        return $this->hookActionValidateOrder($params);
    }

    public function hookActionValidateOrder($params)
    {
        return true;
    }
    /**
         * Load the configuration form
         */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        $message = false;
        if (((bool)Tools::isSubmit('submitNkdAcfThumbnails')) == true) {
            NdkCf::reGenerateThumbs(true);
            $message = $this->displayConfirmation($this->l('Thumbnails generated with success'));
        }
            
        if (((bool)Tools::isSubmit('submitNkdAcfcleanAssociations')) == true) {
            NdkCf::cleanAssociations();
            $message = $this->displayConfirmation($this->l('Associations cleaned with success'));
        }
            
            
            
        if (((bool)Tools::isSubmit('submitNdk_advanced_custom_fieldsModule')) == true) {
            $message = $this->postProcess();
        }

        if (!$message) {
            $msg = $this->displayConfirmation($this->l('The settings have been updated.'));
        } else {
            $msg = $message;
        }

        $this->context->smarty->assign('module_dir', $this->_path);
        $this->context->smarty->assign('base_url', Tools::getHttpHost(true).__PS_BASE_URI__);
        $this->context->smarty->assign('message', $msg);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
        $output .='
            <style>
            .help-block {
                clear: both !important;
                float: left !important;
                width: 100%;
            }
            </style>';
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
        $helper->submit_action = 'submitNdk_advanced_custom_fieldsModule';
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
        $images_types = ImageType::getImagesTypes('products');
        return array(
                'form' => array(
                    'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                    ),
                    'input' => array(

                        array(
                            'type' => 'text',
                            'prefix' => '',
                            'desc' => $this->l('How many days unordered product generated by module may be stored?'),
                            'name' => 'NDK_UNORDERED_DELAY',
                            'label' => $this->l('Unordered product delay before delete'),
                            'class' => 'clearfix'
                        ),
                        array(
                            'type' => 'text',
                            'prefix' => '',
                            'desc' => $this->l('How many days ordered product generated by module may be stored?'),
                            'name' => 'NDK_ORDERED_DELAY',
                            'label' => $this->l('Ordered product delay before delete'),
                            'class' => 'clearfix'
                        ),

                        array(
                            'type' => 'select',
                            'class' => '',
                            'label' => $this->l('image size'),
                            'name' => 'NDK_IMAGE_SIZE',
                            'desc' => $this->l('Select image thumbnail size for listing'),
                            'options' => array(
                                'query' => $images_types,
                                'id' => 'name',
                                'name' => 'name'
                                ),
                        ),

                        array(
                            'type' => 'select',
                            'class' => '',
                            'label' => $this->l('large image size'),
                            'name' => 'NDK_IMAGE_LARGE_SIZE',
                            'desc' => $this->l('Select large image size for area mapping and visual edition'),
                            'options' => array(
                                'query' => $images_types,
                                'id' => 'name',
                                'name' => 'name'
                                ),
                        ),
                        array(
                        'type' => 'select',
                            'class' => '',
                            'label' => $this->l('Choose a template'),
                            'name' => 'NDK_TEMPLATE_TYPE',
                            'desc' => $this->l('Select template for your customizable products, use "Default" if you have any problem'),
                            'options' => array(
                                'query' => array(
                                    array('id' => 0, 'name'=>'Default'),
                                    array('id' => 'popup', 'name'=>'Popup'),
                                    array('id' => 'popup_mobile', 'name'=>'Popup only on mobiles and tablets')
                                ),
                                'id' => 'id',
                                'name' => 'name'
                                ),
                        ),

                        array(
                        'type' => 'switch',
                            'class' => '',
                            'label' => $this->l('Enable module cache ?'),
                            'name' => 'NDK_ACF_CACHE',
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'class' => '',
                            'label' => $this->l('Let fields blocks opened ?'),
                            'name' => 'NDK_LET_OPEN',
                            'desc' => $this->l('if no fields will automatically be closed as page load'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'label' => $this->l('Make product image float'),
                            'name' => 'NDK_MAKE_FLOAT',
                            'desc' => $this->l('Make product image float for your customizable products, use "No" if you have any problem'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => true,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'label' => $this->l('Show prices without taxe in addition to price includes ?'),
                            'name' => 'NDK_SHOW_PRICE_HT',
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),



                        array(
                        'type' => 'switch',
                            'label' => $this->l('Load each product fields for packs ?'),
                            'name' => 'NDK_AUTOLOAD_PACK_FIELDS',
                            'desc' => $this->l('if yes every fields from each products will be loaded'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'label' => $this->l('Load each product fields for accessories ?'),
                            'name' => 'NDK_LOAD_ACCESSORIES_FIELDS',
                            'desc' => $this->l('if yes every fields from each accessories will be loaded'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),


                        array(
                        'type' => 'switch',
                            'label' => $this->l('Show overview box ?'),
                            'name' => 'NDK_SHOW_RECAP',
                            'desc' => $this->l('if yes a floating box will appear with options et prices details'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'label' => $this->l('Show quick navigation box ?'),
                            'name' => 'NDK_SHOW_QUICKNAV',
                            'desc' => $this->l('if yes a floating box will appear as quick navigation'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'label' => $this->l('Auto save user config for editing ?'),
                            'name' => 'NDK_ALLOW_EDIT',
                            'desc' => $this->l('if yes a user will be able to edit his customization (slow down add to cart process)'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'class' => '',
                            'label' => $this->l('Use admin name for fields record ?'),
                            'name' => 'NDK_USE_ADMIN_NAME',
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'class' => '',
                            'label' => $this->l('Show fields in a slider'),
                            'name' => 'NDK_MAKE_SLIDE',
                            'desc' => $this->l('Fields blocks will be shawn in a slider'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'label' => $this->l('Disable loader'),
                            'name' => 'NDK_DISABLE_LOADER',
                            'desc' => $this->l('if yes will disable percentage loader on product page'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'label' => $this->l('Disable auto scroll'),
                            'name' => 'NDK_DISABLE_AUTOSCROLL',
                            'desc' => $this->l('if yes will disable auto page srcolling on option select'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),
                        array(
                        'type' => 'switch',
                            'label' => $this->l('Add Product Price to calculated price'),
                            'name' => 'NDK_ADD_PRODUCT_PRICE',
                            'desc' => $this->l('if no base product price will not be added to the calculated price and product will not be decrease from stock, will only be disabled if customization has additional cost'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'label' => $this->l('Add base product field'),
                            'name' => 'NDK_SHOW_BASE_PRODUCT',
                            'desc' => $this->l('if yes a custom field containing standard product name will be added'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),
                        array(
                        'type' => 'switch',
                            'class' => '',
                            'label' => $this->l('Split pack after order ?'),
                            'name' => 'NDK_SPLIT_PACK',
                            'desc' => $this->l('pack will be splitted into order, usefull for sell statistics'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),
                        array(
                        'type' => 'switch',
                            'class' => '',
                            'label' => $this->l('Show quantities ?'),
                            'name' => 'NDK_SHOW_QTTY',
                            'desc' => $this->l('Will show available quantities for accessories'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),
                        array(
                        'type' => 'switch',
                            'label' => $this->l('Add customization total cost field'),
                            'name' => 'NDK_SHOW_TOTAL_COST',
                            'desc' => $this->l('if yes a custom field containing customization total cost will be added'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'label' => $this->l('Add product attribute field'),
                            'name' => 'NDK_SHOW_COMBINATION',
                            'desc' => $this->l('if yes a custom field containing product attribute details will be added'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'label' => $this->l('Add a zoom on image fields'),
                            'name' => 'NDK_SHOW_IMG_TOOLTIP',
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),




                        array(
                        'type' => 'switch',
                            'label' => $this->l('Keep original reference'),
                            'name' => 'NDK_KEEP_ORIGINAL_REFERENCE',
                            'desc' => $this->l('If yes original reference will be used, if no a custom reference will be generated'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'label' => $this->l('Save HD preview'),
                            'name' => 'NDK_SHOW_HD_PREVIEW',
                            'desc' => $this->l('Save file for HD printing'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'label' => $this->l('Show social sharing'),
                            'name' => 'NDK_SHOW_SOCIAL_TOOLS',
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'label' => $this->l('Save image preview'),
                            'name' => 'NDK_SHOW_IMG_PREVIEW',
                            'desc' => $this->l('Save and show image preview in customization'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'class' => '',
                            'label' => $this->l('Purpose defined configurations to customers ?'),
                            'name' => 'NDK_ADMIN_CONFIG',
                            'desc' => $this->l('As administrator you will be able to save pre-defined configurations that will be purposed to your customers'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                        'type' => 'switch',
                            'class' => '',
                            'label' => $this->l('Allow customers to save their configurations ?'),
                            'name' => 'NDK_USER_CONFIG',
                            'desc' => $this->l('Registered users will be able to save and retrive their configurations'),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('No')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Yes')
                                )
                            ),
                        ),

                        array(
                            'rows' => 5,
                            'cols' => 100,
                            'type' => 'textarea',
                            'prefix' => '',
                            'desc' => $this->l('go to https://www.google.com/fonts/ , select font and copy you link here'),
                            'name' => 'NDK_ACF_FONTS',
                            'label' => $this->l('Fonts'),
                            'class' => 'clearfix'
                        ),
                        array(
                            'rows' => 5,
                            'cols' => 100,
                            'type' => 'textarea',
                            'prefix' => '',
                            'desc' => $this->l('Enter hexadecimal colors here semicolon separated'),
                            'name' => 'NDK_ACF_COLORS',
                            'label' => $this->l('Couleurs'),
                        ),
                        array(
                            'rows' => 5,
                            'cols' => 100,
                            'type' => 'textarea',
                            'prefix' => '',
                            'desc' => $this->l('Enter extensions here semicolon separated'),
                            'name' => 'NDK_ACF_UPLOAD_EXT',
                            'label' => $this->l('Allowed extensions for upload'),
                        ),
                        array(
                            'type' => 'text',
                            'prefix' => '',
                            'desc' => $this->l('if you have a specific key'),
                            'name' => 'NDK_ACF_KEY',
                            'label' => $this->l('KEY'),
                            'class' => 'clearfix'
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
        $default_fonts = '<link href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto|Lato|Oswald|Slabo+27px|Roboto+Condensed|Lora|Source+Sans+Pro|Montserrat|PT+Sans|Open+Sans+Condensed:300|Raleway" rel="stylesheet" type="text/css">';

        $defaults_colors = '#ffffff, #000000';
        return array(
                'NDK_ACF_FONTS' => Configuration::get('NDK_ACF_FONTS'),
                'NDK_ACF_COLORS' => Configuration::get('NDK_ACF_COLORS'),
                'NDK_IMAGE_SIZE' => Configuration::get('NDK_IMAGE_SIZE'),
                'NDK_IMAGE_LARGE_SIZE' => Configuration::get('NDK_IMAGE_LARGE_SIZE'),
                'NDK_TEMPLATE_TYPE' => Configuration::get('NDK_TEMPLATE_TYPE'),
                'NDK_MAKE_FLOAT' => Configuration::get('NDK_MAKE_FLOAT'),
                'NDK_SHOW_PRICE_HT' => Configuration::get('NDK_SHOW_PRICE_HT'),
                'NDK_SHOW_RECAP' => Configuration::get('NDK_SHOW_RECAP'),
                'NDK_SHOW_QUICKNAV' => Configuration::get('NDK_SHOW_QUICKNAV'),
                'NDK_ALLOW_EDIT' => Configuration::get('NDK_ALLOW_EDIT'),
                'NDK_USE_ADMIN_NAME' => Configuration::get('NDK_USE_ADMIN_NAME'),
                'NDK_LET_OPEN' => Configuration::get('NDK_LET_OPEN'),
                'NDK_MAKE_SLIDE' => Configuration::get('NDK_MAKE_SLIDE'),
                'NDK_DISABLE_LOADER' => Configuration::get('NDK_DISABLE_LOADER'),
                'NDK_DISABLE_AUTOSCROLL' => Configuration::get('NDK_DISABLE_AUTOSCROLL'),
                'NDK_ADD_PRODUCT_PRICE' => Configuration::get('NDK_ADD_PRODUCT_PRICE'),
                'NDK_SHOW_IMG_TOOLTIP' => Configuration::get('NDK_SHOW_IMG_TOOLTIP'),
                'NDK_KEEP_ORIGINAL_REFERENCE' => Configuration::get('NDK_KEEP_ORIGINAL_REFERENCE'),
                'NDK_SHOW_SOCIAL_TOOLS' => Configuration::get('NDK_SHOW_SOCIAL_TOOLS'),
                'NDK_SHOW_HD_PREVIEW' => Configuration::get('NDK_SHOW_HD_PREVIEW'),
                'NDK_SHOW_IMG_PREVIEW' => Configuration::get('NDK_SHOW_IMG_PREVIEW'),
                'NDK_ADMIN_CONFIG' => Configuration::get('NDK_ADMIN_CONFIG'),
                'NDK_USER_CONFIG' => Configuration::get('NDK_USER_CONFIG'),
                'NDK_UNORDERED_DELAY' => Configuration::get('NDK_UNORDERED_DELAY'),
                'NDK_ORDERED_DELAY' => Configuration::get('NDK_ORDERED_DELAY'),
                'NDK_AUTOLOAD_PACK_FIELDS' => Configuration::get('NDK_AUTOLOAD_PACK_FIELDS'),
                'NDK_SHOW_BASE_PRODUCT' => Configuration::get('NDK_SHOW_BASE_PRODUCT'),
                'NDK_SHOW_COMBINATION' => Configuration::get('NDK_SHOW_COMBINATION'),
                'NDK_SHOW_TOTAL_COST' => Configuration::get('NDK_SHOW_TOTAL_COST'),
                'NDK_LOAD_ACCESSORIES_FIELDS' => Configuration::get('NDK_LOAD_ACCESSORIES_FIELDS'),
                'NDK_SPLIT_PACK' => Configuration::get('NDK_SPLIT_PACK'),
                'NDK_SHOW_QTTY' => Configuration::get('NDK_SHOW_QTTY'),
                'NDK_ACF_CACHE' => Configuration::get('NDK_ACF_CACHE'),
                'NDK_ACF_UPLOAD_EXT' => Configuration::get('NDK_ACF_UPLOAD_EXT'),
                'NDK_ACF_KEY' => Configuration::get('NDK_ACF_KEY'),

            );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $message = array();
        $form_values = $this->getConfigFormValues();
        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
        return $message;
    }

    public function hookDisplayProductPriceBlock($params)
    {
        $this->assignCommonVars();
        if (isset($params['product']) && is_array($params['product'])) {
            //var_dump($params['product']['id_category_default']);
            $id_product = (int)$params['product']['id_product'];
            if (!isset($params['product']['id_category_default'])) {
                $id_category_default = Db::getInstance()->getValue('SELECT id_category_default FROM '._DB_PREFIX_.'product WHERE id_product = '.$id_product);
            } else {
                $id_category_default = (int)$params['product']['id_category_default'];
            }
            if ((float)_PS_VERSION_ < 1.7) {
                $good_type = 'price';
            } else {
                $good_type = 'before_price';
            }
        } elseif (isset($params['product']) && is_object($params['product'])) {
            $id_product = (int)$params['product']->id;
            $id_category_default = (int)$params['product']->id_category_default;
            $good_type = 'price';
        } else {
            return false;
        }

        if (Context::getContext()->controller->php_self == 'product') {
            $good_type = 'price';
        }

        $field = NdkCf::getFieldFromPrice($id_product, 0);
        $is_required = NdkCf::isRequiredCustomization($id_product, (int)$id_category_default);
        if (count($field) > 0 || $is_required) {
            $price = (count($field) > 0 ? $field[0]['price'] : false);
            $field = (count($field) > 0 ? $field[0] : false);
            $from = true;
            if (!$price) {
                if ($price = NdkCfConfig::getDefaultConfigPrice($id_product)) {
                    $field = true;
                    $from = false;
                }
            }

            $this->context->smarty->assign(array(
                            'field' => $field ,
                            'price' => $price,
                            'id_product' => $id_product,
                            'is_required' => $is_required,
                            'from' => $from,
                            'link' => $this->context->link,
                            'type' => $params['type']
                        ));
            if ($params['type'] == $good_type) {
                return $this->display(__FILE__, 'ndkcffromprice.tpl');
            }
        }
    }


    public function hookUpdateQuantity($params)
    {
        $id_product = (int)$params['id_product'];
        $is_part_of_custom = NdkCfValues::isPartOfCustomization((int)$id_product);
        if ($is_part_of_custom) {
            NdkCf::clearAllCache();
        }
    }

    public function hookActionProductSave($params)
    {
        return $this->hookUpdateQuantity($params);
    }

    public function hookActionProductUpdate($params)
    {
        return $this->hookUpdateQuantity($params);
    }


    public function hookDisplayProductListReviews($params)
    {
        $product = $params['product'];
        $color_fields = NdkCf::getCustomFields((int)$product['id_product'], (int)$product['id_category_default'], 25);
        $this->context->smarty->assign(array('color_fields' => $color_fields, 'target_product' =>(int)$product['id_product'], 'ps_version' => (float)_PS_VERSION_ ));
        return $this->display(__FILE__, 'product-list-colors.tpl');
    }

    public function ajaxCall()
    {
        $this->assignCommonVars();
        $id_field = Tools::getValue('id_field');
        if (strpos($id_field, '-') !== false) {
            $id_field = explode('-', $id_field)[0];
        }

        $id_value = Tools::getValue('id_value');
        $type = Tools::getValue('type');
        $fields = NdkCf::getCustomFields(0, 0, false, $id_field, $id_value);
        $this->context->smarty->assign(array(
                'field' => $fields['fields'][0],
                'base_dir_ssl' => Tools::getHttpHost(true).__PS_BASE_URI__,
                'base_dir' => Tools::getHttpHost(true).__PS_BASE_URI__,
                'link' => $this->context->link,
                'priceDisplay' => Group::getPriceDisplayMethod(Group::getPriceDisplayMethod(Context::getContext()->customer->id_default_group)),
                'ndk_image_size' => Configuration::get('NDK_IMAGE_SIZE'),
                'displayPriceHT' => Configuration::get('NDK_SHOW_PRICE_HT'),
                'ndkcf_show_quantity' => Configuration::get('NDK_SHOW_QTTY'),
            ));
        if (count($fields['fields'][0]['values']) < 1) {
            return false;
        }
        $res = $this->display(__FILE__, '/values/type_'.$type.'.tpl');
        return $res;
    }
}
