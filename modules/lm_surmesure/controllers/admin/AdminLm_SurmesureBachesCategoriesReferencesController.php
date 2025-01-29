<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureBachesCategories.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureBachesMarques.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureBachesModeles.php');

class AdminLm_SurmesureBachesCategoriesReferencesController extends ModuleAdminController
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
        $this->addRowAction('delete');
        $this->explicitSelect = false;
        $this->allow_export = false;
        $this->deleted = false;
        
        $this->_select = 'bc.`nom` AS `nom_categorie`, bcr.`taille`';
        $this->_join  = 'LEFT JOIN `'._DB_PREFIX_.'baches_categories_references` bcr ON (bcr.`reference` = a.`reference`)';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'baches_categories` bc ON (bc.`id_categorie` = bcr.`id_categorie`)';
        $this->_where = 'AND a.`id_product` IN (SELECT `id_product` FROM `'._DB_PREFIX_.'category_product` WHERE `id_category` IN ('. implode(',', $this->module->_cat_baches) .'))';
        $this->_orderWay= 'ASC';
        $this->_orderBy = 'b.name';

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->trans('Delete selected', array(), 'Admin.Actions'),
                'confirm' => $this->trans('Delete selected items?', array(), 'Admin.Notifications.Warning'),
                'icon' => 'icon-trash'
            )
        );
        
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
			'reference' => array(
				'title' => $this->trans('Référence', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'a!reference',
			),
			'taille' => array(
				'title' => $this->trans('Taille', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'bcr!taille',
			),
			'nom_categorie' => array(
				'title' => $this->trans('Categorie', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'bc!nom',
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
                    'type' => 'text',
                    'label' => $this->trans('Reference', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'reference',
                    'required' => true,
                    'disabled' => true,
                    'col' => '4',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->trans('Catégorie', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'id_categorie',
                    'options' => array(
                        'query' => LmSurmesureBachesCategories::getCategories(),
                        'id' => 'id_categorie',
                        'name' => 'nom'
                    ),
                    'required' => true,
                    'col' => '4',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Taille', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'taille',
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

        return parent::renderForm();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAddproduct')) {
            $id_product = (int)Tools::getValue('id_product');
            $reference = Db::getInstance()->getValue('SELECT `reference` FROM `'._DB_PREFIX_.'product` WHERE `id_product` = '. $id_product);
            $values = array(
                'id_categorie' => (int)Tools::getValue('id_categorie'), 
                'reference' => pSQL($reference), 
                'taille' => pSQL(Tools::getValue('taille'))
            );
            
            $query = Db::getInstance()->getRow('SELECT * FROM `'. _DB_PREFIX_ .'baches_categories_references` WHERE `reference` = \''. pSQL($reference) .'\'');
            $result = (!$query) 
                ? Db::getInstance()->execute('INSERT INTO `'. _DB_PREFIX_ .'baches_categories_references` 
                    (`id_categorie`, `reference`, `taille`) 
                    VALUES (\''. implode('\',\'', $values) .'\')') 
                : Db::getInstance()->execute('UPDATE `'. _DB_PREFIX_ .'baches_categories_references` 
                    SET `id_categorie` = '. (int)$values['id_categorie'] .'
                        , `taille` = \''. $values['taille'] .'\'
                    WHERE `reference` = '. $values['reference']);
                
            if (!$result) {
                $this->errors[] = $this->trans('An error occurred while updating an object.', array(), 'Admin.Notifications.Error').' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
            } else {
                Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.Tools::getAdminTokenLite('AdminLm_SurmesureBachesCategoriesReferences'));
            }
        } else {
            parent::postProcess(true);
        }
    }
    
    public function processDelete()
    {
        if (Validate::isLoadedObject($object = $this->loadObject())) {
            if (Db::getInstance()->execute('
                DELETE FROM `'. _DB_PREFIX_ .'baches_categories_references`
                WHERE `reference` = '. $object->reference
            )) {
                $this->redirect_after = self::$currentIndex.'&conf=1&token='.$this->token;
            }
            $this->errors[] = $this->trans('An error occurred during deletion.', array(), 'Admin.Notifications.Error');
        } else {
            $this->errors[] = $this->trans('An error occurred while deleting the object.', array(), 'Admin.Notifications.Error').
                ' <b>baches_categories_references</b> '.
                $this->trans('(cannot load object)', array(), 'Admin.Notifications.Error');
        }
        return $object;
    }
    
    protected function processBulkDelete()
    {
        if (is_array($this->boxes) && !empty($this->boxes)) {
            $object = new $this->className();
            
            $result = true;
            foreach ($this->boxes as $id) {
                $to_delete = new $this->className($id);
                $delete_ok = true;
                if (Db::getInstance()->execute('
                    DELETE FROM `'. _DB_PREFIX_ .'baches_categories_references`
                    WHERE `reference` = '. $to_delete->reference
                )) {
                    $this->redirect_after = self::$currentIndex.'&conf=1&token='.$this->token;
                }

                if (!$delete_ok) {
                    $this->errors[] = sprintf($this->trans('Can\'t delete #%d', array(), 'Admin.Notifications.Error'), $id);
                }
            }
            if ($result) {
                $this->redirect_after = self::$currentIndex.'&conf=2&token='.$this->token;
            }
            $this->errors[] = $this->trans('An error occurred while deleting this selection.', array(), 'Admin.Notifications.Error');
        } else {
            $this->errors[] = $this->trans('You must select at least one element to delete.', array(), 'Admin.Notifications.Error');
        }

        if (isset($result)) {
            return $result;
        } else {
            return false;
        }
    }
}
