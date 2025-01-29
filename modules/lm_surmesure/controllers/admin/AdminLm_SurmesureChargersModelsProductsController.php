<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureTelephoneMarques.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureTelephoneModeles.php');

class AdminLm_SurmesureChargersModelsProductsController extends ModuleAdminController
{
    
    public function __construct()
    {
        $this->bootstrap = true;
        $this->context = Context::getContext();

        parent::__construct();
        
        $this->setItemsList();
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
        
        $this->_select = 'ma.`nom` AS `nom_marque`, mo.`nom` AS `nom_modele`, CONCAT(\'[\', ma.`nom`, \'] \', mo.`nom`) AS `marque_modele`';
        $this->_join  = 'LEFT JOIN `'._DB_PREFIX_.'telephone_modeles_products` mp ON (mp.`id_product` = a.`id_product`)';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'telephone_modeles` mo ON (mo.`id_modele` = mp.`id_modele`)';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'telephone_marques` ma ON (ma.`id_marque` = mo.`id_marque`)';
        $this->_where = 'AND a.`id_product` IN (SELECT `id_product` FROM `'._DB_PREFIX_.'category_product` WHERE `id_category` IN ('. implode(',', $this->module->_cat_telephones) .'))';
        $this->_orderWay= 'ASC';
        $this->_orderBy = 'a.id_product';
        
		$this->fields_list = array(
			'id_product' => array(
				'title' => $this->trans('ID', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-center',
				'filter_key' => 'a!id_product',
				'class' => 'fixed-width-xs'
			),
			'name' => array(
				'title' => $this->trans('Produit', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'b!name',
			),
			'marque_modele' => array(
				'title' => $this->trans('Modèles compatibles', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
                'orderby' => false,
                'search' => false,
			),
		);
    }

    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['new']);
    }

    public function renderForm()
    {
        if (!($object = $this->loadObject(true))) {
            return;
        }
        
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->trans('Modèle / Produit', array(), 'Modules.Surmesure.Admin'),
                'icon' => 'icon-info-sign'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->trans('Produit', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'name',
                    'required' => true,
                    'lang' => true,
                    'disabled' => true,
                    'col' => '4',
                ),
                array(
                    'type' => 'checkbox',
                    'label' => $this->trans('Modèles compatibles', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'modeles',
                    'values' => array(
                        'query' => LmSurmesureTelephoneModeles::getModeles(),
                        'id' => 'id_modele',
                        'name' => 'marque_modele'
                    ),
                    'col' => '4',
                    'required' => true,
                ),
            )
        );
        
        if (!empty($object->id)) {
            $this->fields_form['input'][] = array(
                'type' => 'hidden',
                'name' => 'id_product',
            );
        }
                

        $this->fields_form['submit'] = array(
            'title' => $this->trans('Save', array(), 'Admin.Actions'),
        );

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        return parent::renderForm();
    }
    
    public function getFieldsValue($obj)
    {
        parent::getFieldsValue($obj);
        if (!empty($obj->modeles)) {
            foreach ($obj->modeles as $id_modele) {
                $this->fields_value['modeles_'. $id_modele] = 1;
            }
        }
        return $this->fields_value;
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAddproduct')) {
            $id_product = (int)Tools::getValue('id_product');
            $modeles = array();
            foreach ($_POST as $key => $value) {
                $parts = array();
                if (preg_match('/^modeles_(\d{1,})$/', $key, $parts)) {
                    $modeles[] = $parts[1];
                }
            }
            
            $values = array();
            foreach ($modeles as $id_modele) {
                $values[] = '('. (int)$id_modele .', '. (int)$id_product .')';
            }
            // Remove all previous correpondances
            Db::getInstance()->execute('DELETE FROM `'. _DB_PREFIX_ .'telephone_modeles_products` WHERE `id_product` = '. $id_product);
            
            $result = true;
            if (!empty($values)) {
                $result = Db::getInstance()->execute('INSERT INTO `'. _DB_PREFIX_ .'telephone_modeles_products` (`id_modele`, `id_product`) 
                    VALUES '. implode(',', $values)
                );
            }
            
            if (!$result) {
                $this->errors[] = $this->trans('An error occurred while updating an object.', array(), 'Admin.Notifications.Error').' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
            } else {
                Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.Tools::getAdminTokenLite('AdminLm_SurmesureChargersModelsProducts'));
            }
        } else {
            parent::postProcess(true);
        }
    }
}
