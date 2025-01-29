<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureColors.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureConfigurations.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammes.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammesImages.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureModCodeGabarit.php');

class AdminLm_SurmesureCustomArticlesController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->context = Context::getContext();

        parent::__construct();
        
        $this->setItemsList();
    }

    public function initToolbar()
    {
        parent::initToolbar();
        $this->toolbar_btn['new']['href'] = $this->context->link->getAdminLink('AdminProducts', true, array('addproduct' => '1'));
    }
    
    public function init()
    {
        if (Tools::isSubmit('updateproduct')) {
            $editProductLink = $this->context->link->getAdminLink('AdminProducts', true, array('id_product' => (int)Tools::getValue('id_product'), 'updateproduct' => '1'));
            Tools::redirectAdmin($editProductLink);
        }

        parent::init();
    }
    
    public function setItemsList ()
    {
        $this->table = 'product';
        $this->identifier = 'id_product';
        $this->className = 'Product';
        $this->lang = true;
        $this->addRowAction('edit');
        $this->explicitSelect = false;
        $this->allow_export = false;
        $this->deleted = false;

        $this->_where = 'AND a.`id_product` IN (SELECT `id_product` FROM `'._DB_PREFIX_.'category_product` WHERE `id_category` = '. (int)$this->module->_id_category .')';
        $this->_orderWay= 'ASC';
        $this->_orderBy = 'b.name';

		$this->fields_list = array(
			'id_product' => array(
				'title' => $this->trans('ID', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-center',
				'filter_key' => 'a!id_product',
				'class' => 'fixed-width-xs'
			),
			'name' => array(
				'title' => $this->trans('Article', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'b!name',
			),
			'reference' => array(
				'title' => $this->trans('Type', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-center',
				'callback' => 'getProductTypeForListing',
                'orderby' => false,
                'search' => false,
			),
			'price' => array(
				'title' => $this->trans('Prix', array(), 'Modules.Surmesure.Admin'),
                'align' => 'text-right',
                'type' => 'price',
                'currency' => true,
				'callback' => 'getProductPriceForListing',
                'orderby' => false,
                'search' => false,
			),
			'quantity' => array(
				'title' => $this->trans('Quantité', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-right',
				'callback' => 'getProductQuantityForListing',
                'orderby' => false,
                'search' => false,
			),
		);
    }
    
    public function getProductPriceForListing ($echo, $tr)
    {
        $price = Product::getPriceStatic($tr['id_product'], true, null, (int)Configuration::get('PS_PRICE_DISPLAY_PRECISION'), null, false, true, 1, true);
        return Tools::displayPrice($price);
    }
    
    public function getProductQuantityForListing ($echo, $tr)
    {
        return Product::getRealQuantity($tr['id_product']);
    }
    
    public function getProductTypeForListing ($echo, $tr)
    {
        $features = Product::getFrontFeaturesStatic(Context::getContext()->language->id, $tr['id_product']);
        $type = '--';
        foreach ($features as $feature) {
            if ($feature['id_feature'] == $this->module->_id_type) //type
                $type = $feature['value'];
        }
        return $type;
    }
}
