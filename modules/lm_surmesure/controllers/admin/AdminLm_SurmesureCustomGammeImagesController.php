<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureColors.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureConfigurations.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammes.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammesImages.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureModCodeGabarit.php');

class AdminLm_SurmesureCustomGammeImagesController extends ModuleAdminController
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
        $this->table = 'customproducts_gammes_images';
        $this->identifier = 'id';
        $this->className = 'LmSurmesureGammesImages';
        $this->lang = false;
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->explicitSelect = false;
        $this->allow_export = false;
        $this->deleted = false;

        $this->_select = 'g.`alias`, fvl.`value` AS `nom_gamme`';
        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'customproducts_gammes` g ON (g.`id_gamme` = a.`id_gamme`)'."\n";
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` fvl ON (fvl.`id_feature_value` = a.`id_gamme` AND fvl.`id_lang` = '. (int)$this->context->language->id .')'."\n";
        $this->_orderWay= 'ASC';
        $this->_orderBy = 'fvl.value';

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->trans('Delete selected', array(), 'Admin.Actions'),
                'confirm' => $this->trans('Delete selected items?', array(), 'Admin.Notifications.Warning'),
                'icon' => 'icon-trash'
            )
        );
        
		$this->fields_list = array(
			'id_gamme' => array(
				'title' => $this->trans('ID', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-center',
				'filter_key' => 'a!id_gamme',
				'class' => 'fixed-width-xs'
			),
			'nom_gamme' => array(
				'title' => $this->trans('Gamme', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'fvl!value',
			),
			'image' => array(
				'title' => $this->trans('Image', array(), 'Modules.Surmesure.Admin'),
                'align' => 'text-left',
				'filter_key' => 'q!image',
				'callback' => 'getImageForListing',
			),
			'legende' => array(
				'title' => $this->trans('Légende', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'a!legende',
			),
		);
    }
    
    public function getImageForListing ($echo, $tr)
    {
		if (file_exists(_PS_IMG_DIR_ . 'custom/'. $tr['alias'] .'/'. $echo)) {
            return '<img src="'. _PS_IMG_ . 'custom/'. $tr['alias'] .'/'. $echo .'" alt="" width="100" />';
		}
		return '--';
    }

    public function renderForm()
    {
        $gammesValues = FeatureValue::getFeatureValuesWithLang((int)$this->context->language->id, (int)$this->module->_id_gam);
        
        if (!($object = $this->loadObject(true))) {
            return;
        }
        
        $image_url = $image_size = false;
        if ($object->id) {
            $image = _PS_IMG_DIR_ . 'custom/'. $object->gamme_alias .'/'.$object->image;
            $image_url = ImageManager::thumbnail($image, $this->table.'_'.(int)$object->id.'.'.$this->imageType, 350, $this->imageType, true, true);
            $image_size = file_exists($image) ? filesize($image) / 1000 : false;
            $object->image_old = $object->image;
        }
        
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->trans('Images Gammes', array(), 'Modules.Surmesure.Admin'),
                'icon' => 'icon-info-sign'
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->trans('Gamme', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'id_gamme',
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
                    'label' => $this->trans('Légende', array(), 'Modules.Surmesure.Admin'),
                    'desc' => $this->trans('Ajouter [more] pour afficher "en savoir plus"', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'legende',
                    'col' => '4',
                    'required' => true,
                ),
            )
        );
        
        if (!empty($object->id)) {
            $this->fields_form['input'][] = array(
                'type' => 'hidden',
                'name' => 'id',
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

    public function postProcess()
    {
        if (Tools::isSubmit('submitAddcustomproducts_gammes_images')) {
            $id = (int)Tools::getValue('id');
            // Get Gamme id
            $id_gamme = (int)Tools::getValue('id_gamme');
            $gamme_alias = Db::getInstance()->getValue('SELECT `alias` FROM `'. _DB_PREFIX_ .'customproducts_gammes` WHERE `id_gamme` = '. $id_gamme);
            $dir = _PS_IMG_DIR_ . 'custom/'. $gamme_alias .'/';
            
            // Get submitted image
            $image = $this->module->uploadCustomImage('image', $dir);
            if (!is_string($image)) {
                $image = Tools::getValue('image_old');
            }
            
            $values = array('id_gamme' => $id_gamme, 'image' => pSQL($image), 'legende' => pSQL(Tools::getValue('legende')));
            $query = Db::getInstance()->getRow('SELECT * FROM `'. _DB_PREFIX_ .'customproducts_gammes_images` WHERE `id` = '. (int)$id );
            $result = (!$query) 
                ? Db::getInstance()->execute('INSERT INTO `'. _DB_PREFIX_ .'customproducts_gammes_images` (`id_gamme`, `image`, `legende`) VALUES (\''. implode('\',\'', $values) .'\')') 
                : Db::getInstance()->execute('UPDATE `'. _DB_PREFIX_ .'customproducts_gammes_images` SET `id_gamme` = '. (int)$values['id_gamme'] .', `image` = \''. $values['image'] .'\', `legende` = \''. $values['legende'] .'\' WHERE `id` = '. (int)$id);
                
            if (!$result) {
                $this->errors[] = $this->trans('An error occurred while updating an object.', array(), 'Admin.Notifications.Error').' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
            } else {
                Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.Tools::getAdminTokenLite('AdminLm_SurmesureCustomGammeImages'));
            }
        } else {
            parent::postProcess(true);
        }
    }
}
