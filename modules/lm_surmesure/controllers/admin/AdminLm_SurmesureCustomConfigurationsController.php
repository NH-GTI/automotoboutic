<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureColors.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureConfigurations.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammes.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammesImages.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureModCodeGabarit.php');

class AdminLm_SurmesureCustomConfigurationsController extends ModuleAdminController
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
        $this->toolbar_btn['new']['href'] = $this->context->link->getAdminLink('AdminFeatures', true, array(), array('addfeature_value' => '1', 'id_feature' => (int)$this->module->_id_conf));
    }
    
    public function setItemsList ()
    {
        $this->table = 'customproducts_configurations';
        $this->identifier = 'id';
        $this->className = 'LmSurmesureConfigurations';
        $this->lang = false;
        $this->addRowAction('edit');
        $this->explicitSelect = false;
        $this->allow_export = false;
        $this->deleted = false;

        $this->_select = 'fvl.`value` AS `nom_configuration`';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'feature_value` fv ON (fv.`id_feature_value` = a.`id_conf`)'."\n";
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` fvl ON (fvl.`id_feature_value` = fv.`id_feature_value` AND fvl.`id_lang` = '. (int)$this->context->language->id .')'."\n";
        $this->_where = 'AND fv.`id_feature` = '. (int)$this->module->_id_conf .'';
        $this->_orderWay= 'ASC';
        $this->_orderBy = 'fvl.value';
        
        
		$this->fields_list = array(
			'id_conf' => array(
				'title' => $this->trans('ID', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-center',
				'filter_key' => 'a!id_conf',
				'class' => 'fixed-width-xs'
			),
			'nom_configuration' => array(
				'title' => $this->trans('Configuration', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'fvl!value',
			),
			'description' => array(
				'title' => $this->trans('Description', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'a!description',
			),
			'image' => array(
				'title' => $this->trans('Image', array(), 'Modules.Surmesure.Admin'),
                'align' => 'text-left',
				'filter_key' => 'a!image',
				'callback' => 'getImageForListing',
			),
		);
    }
    
    public function getImageForListing ($echo, $tr)
    {
		if (file_exists(_PS_IMG_DIR_ . 'custom/configurations/'. $echo)) {
            return '<img src="'. _PS_IMG_ . 'custom/configurations/'. $echo .'" alt="" width="25" />';
		}
		return '--';
    }

    public function renderForm()
    {
        if (!($object = $this->loadObject(true))) {
            return;
        }

        $image = _PS_IMG_DIR_ . 'custom/configurations/'.$object->image;
        $image_url = ImageManager::thumbnail($image, $this->table.'_'.(int)$object->id.'.'.$this->imageType, 350, $this->imageType, true, true);
        $image_size = file_exists($image) ? filesize($image) / 1000 : false;
        $object->image_old = $object->image;
        
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->trans('Configurations', array(), 'Modules.Surmesure.Admin'),
                'icon' => 'icon-info-sign'
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_conf',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Configuration', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'nom_configuration',
                    'col' => '4',
                    'disabled' => true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->trans('Description', array(), 'Modules.Surmesure.Admin'),
                    'desc' => $this->trans('Retours à la ligne: | (Alt Gr + 6)', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'description',
                    'col' => '4',
                    'required' => true,
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
        if (Tools::isSubmit('submitAddcustomproducts_configurations')) {
            if ($id_conf = (int)Tools::getValue('id_conf')) {
                $dir = _PS_IMG_DIR_ . 'custom/configurations/';
                
                // Get submitted main image
                $image = $this->module->uploadCustomImage('image', $dir);
                if (!is_string($image)) {
                    $image = Tools::getValue('image_old');
                }
                
                $values = array(
                    'id_conf' => $id_conf, 
                    'image' => $image, 
                    'description' => pSQL(Tools::getValue('description')),
                );
                $query = Db::getInstance()->getRow('SELECT `id` FROM `'. _DB_PREFIX_ .'customproducts_configurations` WHERE `id_conf` = '. $id_conf);
                $result = (!$query) 
                    ? Db::getInstance()->execute('INSERT INTO `'. _DB_PREFIX_ .'customproducts_configurations` 
                        (`id_conf`, `image`, `description`) 
                        VALUES (\''. implode('\',\'', $values) .'\')') 
                    : Db::getInstance()->execute('UPDATE `'. _DB_PREFIX_ .'customproducts_configurations` 
                        SET `image` = \''. pSQL($values['image']) .'\' 
                            , `description` = \''. pSQL($values['description']) .'\' 
                        WHERE `id_conf` = '.$id_conf);
                    
                if (!$result) {
                    $this->errors[] = $this->trans('An error occurred while updating an object.', array(), 'Admin.Notifications.Error').' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
                } else {
                    Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.Tools::getAdminTokenLite('AdminLm_SurmesureCustomConfigurations'));
                }
            }
            $this->errors[] = $this->trans('L\'idenfiant de la couleur est requis.', array(), 'Admin.Notifications.Error');
        } else {
            parent::postProcess(true);
        }
    }
}
