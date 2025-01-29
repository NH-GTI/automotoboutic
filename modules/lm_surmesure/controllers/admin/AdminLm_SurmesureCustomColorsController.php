<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureColors.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureConfigurations.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammes.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammesImages.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureModCodeGabarit.php');

class AdminLm_SurmesureCustomColorsController extends ModuleAdminController
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
        $this->toolbar_btn['new']['href'] = $this->context->link->getAdminLink('AdminFeatures', true, array(), array('addfeature_value' => '1', 'id_feature' => (int)$this->module->_id_color));
    }
    
    public function setItemsList ()
    {
        $this->table = 'customproducts_couleurs';
        $this->identifier = 'id';
        $this->className = 'LmSurmesureColors';
        $this->lang = false;
        $this->addRowAction('edit');
        $this->explicitSelect = false;
        $this->allow_export = false;
        $this->deleted = false;

        $this->_select = 'fvl.`value` AS `nom_couleur`';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'feature_value` fv ON (fv.`id_feature_value` = a.`id_color`)'."\n";
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` fvl ON (fvl.`id_feature_value` = fv.`id_feature_value` AND fvl.`id_lang` = '. (int)$this->context->language->id .')'."\n";
        $this->_where = 'AND fv.`id_feature` = '. (int)$this->module->_id_color .'';
        $this->_orderWay= 'ASC';
        $this->_orderBy = 'fvl.value';
        
        
		$this->fields_list = array(
			'id_color' => array(
				'title' => $this->trans('ID', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-center',
				'filter_key' => 'a!id_color',
				'class' => 'fixed-width-xs'
			),
			'nom_couleur' => array(
				'title' => $this->trans('Couleur', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'fvl!value',
			),
			'image' => array(
				'title' => $this->trans('Image', array(), 'Modules.Surmesure.Admin'),
                'align' => 'text-left',
				'filter_key' => 'a!image',
				'callback' => 'getImageForListing',
			),
			'alias' => array(
				'title' => $this->trans('Alias', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'a!alias',
			),
		);
    }
    
    public function getImageForListing ($echo, $tr)
    {
		if (file_exists(_PS_IMG_DIR_ . 'colors/'. $echo)) {
            return '<img src="'. _PS_IMG_ . 'colors/'. $echo .'" alt="" width="25" />';
		}
		return '--';
    }

    public function renderForm()
    {
        if (!($object = $this->loadObject(true))) {
            return;
        }

        $image = _PS_IMG_DIR_ . 'colors/'.$object->image;
        $image_url = ImageManager::thumbnail($image, $this->table.'_'.(int)$object->id.'.'.$this->imageType, 350, $this->imageType, true, true);
        $image_size = file_exists($image) ? filesize($image) / 1000 : false;
        $object->image_old = $object->image;
        
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->trans('Couleurs', array(), 'Modules.Surmesure.Admin'),
                'icon' => 'icon-info-sign'
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_color',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Couleur', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'nom_couleur',
                    'col' => '4',
                    'disabled' => true,
                ),
                array(
                    'type' => 'file',
                    'label' => $this->trans('Image', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'image',
                    'image' => $image_url ? $image_url : false,
                    'size' => $image_size,
                    'display_image' => true,
                    'col' => 6,
                    'hint' => $this->trans('Uploader l\'image depuis votre ordinateur.', array(), 'Modules.Surmesure.Admin'),
                    'desc' => sprintf($this->trans('Maximum image size: %s.', array(), 'Admin.Global'), ini_get('upload_max_filesize'))
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'image_old'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Alias', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'alias',
                    'col' => '4',
                    'required' => true,
                ),
            )
        );
                

        $this->fields_form['submit'] = array(
            'title' => $this->trans('Save', array(), 'Admin.Actions'),
        );

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        return parent::renderForm();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAddcustomproducts_couleurs')) {
            if ($id_color = (int)Tools::getValue('id_color')) {
                $dir = _PS_IMG_DIR_ . 'colors/';
                
                // Get submitted main image
                $image = $this->module->uploadCustomImage('image', $dir);
                if (!is_string($image)) {
                    $image = Tools::getValue('image_old');
                }
                
                $values = array(
                    'id_color' => $id_color, 
                    'image' => $image, 
                    'alias' => pSQL(Tools::getValue('alias')),
                );
                $query = Db::getInstance()->getRow('SELECT `id` FROM `'. _DB_PREFIX_ .'customproducts_couleurs` WHERE `id_color` = '. $id_color);
                $result = (!$query) 
                    ? Db::getInstance()->execute('INSERT INTO `'. _DB_PREFIX_ .'customproducts_couleurs` 
                        (`id_color`, `image`, `alias`) 
                        VALUES (\''. implode('\',\'', $values) .'\')') 
                    : Db::getInstance()->execute('UPDATE `'. _DB_PREFIX_ .'customproducts_couleurs` 
                        SET `image` = \''. pSQL($values['image']) .'\' 
                            , `alias` = \''. pSQL($values['alias']) .'\' 
                        WHERE `id_color` = '.$id_color);
                    
                if (!$result) {
                    $this->errors[] = $this->trans('An error occurred while updating an object.', array(), 'Admin.Notifications.Error').' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
                } else {
                    Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.Tools::getAdminTokenLite('AdminLm_SurmesureCustomColors'));
                }
            }
            $this->errors[] = $this->trans('L\'idenfiant de la couleur est requis.', array(), 'Admin.Notifications.Error');
        } else {
            parent::postProcess(true);
        }
    }
}
