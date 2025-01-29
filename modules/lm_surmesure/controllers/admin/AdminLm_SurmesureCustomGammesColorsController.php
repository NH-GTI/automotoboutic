<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureColors.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureConfigurations.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammes.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammesImages.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureModCodeGabarit.php');

class AdminLm_SurmesureCustomGammesColorsController extends ModuleAdminController
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
        $this->table = 'feature_value';
        $this->identifier = 'id_feature_value';
        $this->className = 'FeatureValue';
        $this->lang = true;
        $this->addRowAction('edit');
        $this->explicitSelect = false;
        $this->allow_export = false;
        $this->deleted = false;

        $this->_select = 'gc.*
                        , g.`alias`
                        , b.`value` as `nom_couleur`
                        , fvl.`value` AS `nom_gamme`';
        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'customproducts_gammes_couleurs` gc ON (gc.`id_couleur` = a.`id_feature_value`)'."\n";
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'customproducts_gammes` g ON (g.`id_gamme` = gc.`id_gam`)'."\n";
        $this->_join .= 'INNER JOIN `'._DB_PREFIX_.'feature_value_lang` fvl ON (fvl.`id_feature_value` = gc.`id_gam` AND fvl.`id_lang` = '. (int)$this->context->language->id .')'."\n ";
        $this->_where = 'AND a.`id_feature` = '. (int)$this->module->_id_color .'';
        $this->_orderWay= 'ASC';
        $this->_orderBy = 'b.value';
        
        
		$this->fields_list = array(
			'id_feature_value' => array(
				'title' => $this->trans('ID', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-center',
				'filter_key' => 'a!id_feature_value',
				'class' => 'fixed-width-xs'
			),
			'nom_couleur' => array(
				'title' => $this->trans('Couleur', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'b!value',
			),
			'nom_gamme' => array(
				'title' => $this->trans('Gamme', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'fvl!value',
			),
			'main_image' => array(
				'title' => $this->trans('Image Principale', array(), 'Modules.Surmesure.Admin'),
                'align' => 'text-left',
				'filter_key' => 'gc!main_image',
				'callback' => 'getMainImageForListing',
			),
		);
    }
    
    public function getMainImageForListing ($echo, $tr)
    {
		if (file_exists(_PS_IMG_DIR_ . 'custom/'. $tr['alias'] .'/'. $echo)) {
            return '<img src="'. _PS_IMG_ . 'custom/'. $tr['alias'] .'/'. $echo .'" alt="" width="100" />';
		}
		return '--';
    }

    public function renderForm()
    {
        $gammesValues = FeatureValue::getFeatureValuesWithLang((int)$this->context->language->id, (int)$this->module->_id_gam);
		$fixValues = FeatureValue::getFeatureValuesWithLang((int)$this->context->language->id, (int)$this->module->_id_fix);
        
        if (!($object = $this->loadObject(true))) {
            return;
        }

        $image = _PS_IMG_DIR_ . 'custom/'. $object->gamme_alias .'/'.$object->main_image;
        $image_url = ImageManager::thumbnail($image, $this->table.'_'.(int)$object->id.'.'.$this->imageType, 350, $this->imageType, true, true);
        $image_size = file_exists($image) ? filesize($image) / 1000 : false;
        $object->main_image_old = $object->main_image;
        
        $object->id_gam = Db::getInstance()->getValue('SELECT `id_gam` FROM `'._DB_PREFIX_.'customproducts_gammes_couleurs` WHERE `id_couleur` = '. (int)$object->id);
        
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->trans('Gammes / Couleurs', array(), 'Modules.Surmesure.Admin'),
                'icon' => 'icon-info-sign'
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_feature_value',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Couleur', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'value',
                    'lang' => true,
                    'col' => '4',
                    'disabled' => true,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->trans('Gamme', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'id_gam',
                    'options' => array(
                        'query' => $gammesValues,
                        'id' => 'id_feature_value',
                        'name' => 'value'
                    ),
                    'required' => true,
                    'col' => '4',
                ),
                array(
                    'type' => 'file',
                    'label' => $this->trans('Image Principale', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'main_image',
                    'image' => $image_url ? $image_url : false,
                    'size' => $image_size,
                    'display_image' => true,
                    'col' => 6,
                    'hint' => $this->trans('Uploader l\'image principale depuis votre ordinateur.', array(), 'Modules.Surmesure.Admin'),
                    'desc' => sprintf($this->trans('Maximum image size: %s.', array(), 'Admin.Global'), ini_get('upload_max_filesize'))
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'main_image_old'
                ),
            )
        );
        
        $index = 1;
        if (!empty($object->images)) {
            foreach ($object->images as $color_image) {
                $image = _PS_IMG_DIR_ . 'custom/'. $object->gamme_alias .'/'.$color_image;
                $image_url = ImageManager::thumbnail($image, $this->table.'_'.(int)$object->id.'_'.(int)$index.'.'.$this->imageType, 350, $this->imageType, true, true);
                $image_size = file_exists($image) ? filesize($image) / 1000 : false;
                $this->fields_form['input'][] = array(
                    'type' => 'file',
                    'label' => sprintf($this->trans('Autre image N°%s', array(), 'Modules.Surmesure.Admin'), $index),
                    'name' => 'image_'.$index,
                    'image' => $image_url ? $image_url : false,
                    'size' => $image_size,
                    'display_image' => true,
                    'col' => 6,
                    'hint' => $this->trans('Uploader l\'image principale depuis votre ordinateur.', array(), 'Modules.Surmesure.Admin'),
                    'desc' => sprintf($this->trans('Maximum image size: %s.', array(), 'Admin.Global'), ini_get('upload_max_filesize'))
                );
                $object->{'image_'.$index .'_old'} = $color_image;
                $this->fields_form['input'][] = array(
                    'type' => 'hidden',
                    'name' => 'image_'.$index .'_old',
                );
                $index++;
            }
        }
        $this->fields_form['input'][] = array(
            'type' => 'file',
            'label' => sprintf($this->trans('Autre image N°%s', array(), 'Modules.Surmesure.Admin'), $index),
            'name' => 'image_'.$index,
            'image' => false,
            'size' => false,
            'display_image' => true,
            'col' => 6,
            'hint' => $this->trans('Uploader l\'image principale depuis votre ordinateur.', array(), 'Modules.Surmesure.Admin'),
            'desc' => sprintf($this->trans('Maximum image size: %s.', array(), 'Admin.Global'), ini_get('upload_max_filesize'))
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
        if (Tools::isSubmit('submitAddfeature_value')) {
            if ($id_feature_value = (int)Tools::getValue('id_feature_value')) {
                // Get Gamme id
                $id_gamme = (int)Tools::getValue('id_gam');
                $gamme_alias = Db::getInstance()->getValue('SELECT `alias` FROM `'. _DB_PREFIX_ .'customproducts_gammes` WHERE `id_gamme` = '. $id_gamme);
                $dir = _PS_IMG_DIR_ . 'custom/'. $gamme_alias .'/';
                
                // Get submitted main image
                $main_image = $this->module->uploadCustomImage('main_image', $dir);
                if (!is_string($main_image)) {
                    $main_image = Tools::getValue('main_image_old');
                }
                
                // Get submitted images
                $images = array();
                foreach ($_FILES as $name => $file) {
                    if (preg_match('/^image_(\d{1,})$/', $name)) {
                        $image = $this->module->uploadCustomImage($name, $dir);
                        if (!is_string($image)) {
                            $image = Tools::getValue($name .'_old');
                        }
                        if (!empty($image) && is_string($image)) {
                            $images[] = $image;
                        }
                    }
                }
                $images = array_unique(array_filter($images));
                
                /* Correspondance Carrosserie */
                $values = array('id_couleur' => $id_feature_value, 'id_gam' => $id_gamme, 'images' => implode('|', $images), 'main_image' => $main_image);
                $query = Db::getInstance()->getRow('SELECT * FROM `'. _DB_PREFIX_ .'customproducts_gammes_couleurs` WHERE `id_couleur` = '. $id_feature_value .' AND `id_gam` = '.$id_gamme );
                $result = (!$query) 
                    ? Db::getInstance()->execute('INSERT INTO `'. _DB_PREFIX_ .'customproducts_gammes_couleurs` (`id_couleur`, `id_gam`, `images`, `main_image`) VALUES (\''. implode('\',\'', $values) .'\')') 
                    : Db::getInstance()->execute('UPDATE `'. _DB_PREFIX_ .'customproducts_gammes_couleurs` SET `images` = \''. pSQL($values['images']) .'\', `main_image` = \''. pSQL($values['main_image']) .'\' WHERE `id_couleur` = '. $id_feature_value .' AND `id_gam` = '.$id_gamme);
                    
                if (!$result) {
                    $this->errors[] = $this->trans('An error occurred while updating an object.', array(), 'Admin.Notifications.Error').' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
                } else {
                    Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.Tools::getAdminTokenLite('AdminLm_SurmesureCustomGammesColors'));
                }
            }
            $this->errors[] = $this->trans('L\'idenfiant du modèle est requis.', array(), 'Admin.Notifications.Error');
        } else {
            parent::postProcess(true);
        }
    }
}
