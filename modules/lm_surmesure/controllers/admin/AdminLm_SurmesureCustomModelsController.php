<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureColors.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureConfigurations.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammes.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammesImages.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureModCodeGabarit.php');

class AdminLm_SurmesureCustomModelsController extends ModuleAdminController
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
        //$this->toolbar_btn['new']['href'] = $this->context->link->getAdminLink('AdminFeatures', true, array(), array('addfeature_value' => '1', 'id_feature' => (int)$this->module->_id_mod));
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
//                        , fvl2.`value` AS `fixations`
        $this->_select = 'b.`value` as `marque`, b.`value` as `modele`
                        , mcg.`code_gabarit`
                        , fvl1.`value` AS `carrosseries`
                        , IF(mc.`coffre` = 1, \''. $this->trans('YES', array(), 'Modules.Surmesure.Admin') .'\', \''. $this->trans('NO', array(), 'Modules.Surmesure.Admin') .'\') AS `coffre`';
        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'customproducts_mod_code_gabarit` mcg ON (mcg.`id_mod` = a.`id_feature_value`)'."\n ";
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'customproducts_carro_mod` cm ON (cm.`id_mod` = a.`id_feature_value`)'."\n ";
        $this->_join .= 'INNER JOIN `'._DB_PREFIX_.'feature_value_lang` fvl1 ON (fvl1.`id_feature_value` = cm.`id_carro` AND fvl1.`id_lang` = '. (int)$this->context->language->id .')'."\n ";
  //    $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'customproducts_mod_fix` mf ON (mf.`id_mod` = a.`id_feature_value`)'."\n ";
  //    $this->_join .= 'INNER JOIN `'._DB_PREFIX_.'feature_value_lang` fvl2 ON (fvl2.`id_feature_value` = mf.`id_fix` AND fvl2.`id_lang` = '. (int)$this->context->language->id .')'."\n ";
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'customproducts_mod_coffre` mc ON (mc.`id_mod` = a.`id_feature_value`)'."\n ";
        $this->_where.= 'AND a.`id_feature` = '. (int)$this->module->_id_mod .'';
        $this->_orderWay= 'ASC';
        $this->_orderBy = 'b.value';
        
		$this->fields_list = array(
			'id_feature_value' => array(
				'title' => $this->trans('ID', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-center',
				'filter_key' => 'a!id_feature_value',
				'class' => 'fixed-width-xs'
			),
			'marque' => array(
				'title' => $this->trans('Marque', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'b!value',
				'callback' => 'getMarqueForListing',
			),
			'modele' => array(
				'title' => $this->trans('Modèle', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'callback' => 'getModelForListing',
				'filter_key' => 'b!value',
			),
			'carrosseries' => array(
				'title' => $this->trans('Carrosseries', array(), 'Modules.Surmesure.Admin'),
                'align' => 'text-left',
				'filter_key' => 'fvl1!value',
			),
			'fixations' => array(
				'title' => $this->trans('Fixations', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'fvl2!value',
			),
			'coffre' => array(
				'title' => $this->trans('Coffre', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-center',
				'filter_key' => 'mc!coffre',
			),
			'code_gabarit' => array(
				'title' => $this->trans('Code Gabarit', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-center',
				'filter_key' => 'mcg!code_gabarit',
			),
		);
    }
    
    public function getMarqueForListing ($echo, $tr)
    {
        if (is_array($echo)) {
            $treated = array();
            foreach ($echo as $key => $value) {
                $split = explode('] ',$value);
                $treated[$key] = str_replace('[','',$split[0]);
            }
            return $treated;
        } else {
    		$split = explode('] ',$echo);
    		return str_replace('[','',$split[0]);
        }
    }
    
    public function getModelForListing ($echo, $tr)
    {
        if (is_array($echo)) {
            $treated = array();
            foreach ($echo as $key => $value) {
                $split = explode('] ',$value);
                $treated[$key] = isset($split[1]) ? $split[1] : '';
            }
            return $treated;
        } else {
            $split = explode('] ',$echo);
    		return isset($split[1]) ? $split[1] : '--';
        }
    }
    
    public function getFeatureValueIdByValue ($value, $id_feature, $id_lang)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT fv.`id_feature_value`
			FROM `'._DB_PREFIX_.'feature_value` fv
			LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` fvl ON (fv.`id_feature_value` = fvl.`id_feature_value` AND fvl.`id_lang` = '. (int)$id_lang .')
			WHERE fv.`id_feature` = '.(int) $id_feature .' 
                AND fvl.`value` = \''.pSQL($value) .'\''
        );
    }

    public function renderForm()
    {
        // Carrosserie Fixations Coffre Code Gabarit
		$brandValues = FeatureValue::getFeatureValuesWithLang((int)$this->context->language->id, (int)$this->module->_id_marque);
		$carroValues = FeatureValue::getFeatureValuesWithLang((int)$this->context->language->id, (int)$this->module->_id_carro);
		$fixValues = FeatureValue::getFeatureValuesWithLang((int)$this->context->language->id, (int)$this->module->_id_fix);
        
        $feature_value = null;
        if ($this->loadObject(true)) {
            $feature_value = $this->object->value;
        }
        
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->trans('Modèle', array(), 'Modules.Surmesure.Admin'),
                'icon' => 'icon-info-sign'
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->trans('Marque', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'id_marque',
                    'options' => array(
                        'query' => $brandValues,
                        'id' => 'id_feature_value',
                        'name' => 'value'
                    ),
                    'required' => true,
                    'col' => '4',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Nom du modèle', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'value',
                    'lang' => true,
                    'size' => 33,
                    'col' => '4',
                    'hint' => $this->trans('Invalid characters:', array(), 'Admin.Notifications.Info').' <>;=#{}',
                    'required' => true
                ),
                array(
                    'type' => 'select',
                    'label' => $this->trans('Carrosserie', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'id_carrosseries',
                    'options' => array(
                        'query' => $carroValues,
                        'id' => 'id_feature_value',
                        'name' => 'value'
                    ),
                    'required' => true,
                    'col' => '4',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->trans('Fixation', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'id_fixations',
                    'options' => array(
                        'query' => $fixValues,
                        'id' => 'id_feature_value',
                        'name' => 'value'
                    ),
                    'required' => true,
                    'col' => '4',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Code Gabarit', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'code_gabarit',
                    'required' => true,
                    'col' => '4',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->trans('Coffre', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'coffre',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'coffre_on',
                            'value' => 1,
                            'label' => $this->trans('Yes', array(), 'Admin.Global')
                        ),
                        array(
                            'id' => 'coffre_off',
                            'value' => 0,
                            'label' => $this->trans('No', array(), 'Admin.Global')
                        )
                    ),
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'id_feature',
                    'required' => true,
                ),
            )
        );
        $this->fields_value['id_feature'] = (int)$this->module->_id_mod;
        $marque = $this->getMarqueForListing($feature_value, null);
        if (is_array($marque)) {
            $marque = isset($marque[(int)$this->context->language->id]) ? $marque[(int)$this->context->language->id] : current($marque);
        }
        $this->fields_value['id_marque'] = (int)$this->getFeatureValueIdByValue($marque, (int)$this->module->_id_marque, (int)$this->context->language->id);
        $this->fields_value['value'] = ($feature_value ? $this->getModelForListing($feature_value, false) : $feature_value);

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
            // Edit the model FeatureValue
            $marqueFeatureValue = new FeatureValue((int)Tools::getValue('id_marque'));
            if (empty($marqueFeatureValue->id)) {
                $this->errors[] = $this->trans('La marque est requise.', array(), 'Admin.Notifications.Error');
                return;
            }
            
            $modelFeatureValue = new FeatureValue((int)Tools::getValue('id_feature_value'));
            $modelFeatureValue->id_feature = (int)Tools::getValue('id_feature');
            $modelFeatureValue->value = array();
            foreach (Language::getLanguages(false) as $language) {
                $modelFeatureValue->value[(int)$language['id_lang']] = '['. $marqueFeatureValue->value[$language['id_lang']] .'] '. Tools::getValue('value_'. $language['id_lang']);
            }
            
            if ($modelFeatureValue->save() && $modelFeatureValue->id) {
                $_POST['id_feature_value'] = (int)$modelFeatureValue->id;
            } else {
                $this->errors[] = $this->trans('L\'enregistrement du modèle a échoué.', array(), 'Admin.Notifications.Error');
                return;
            }
            
            if ($id_feature_value = (int)Tools::getValue('id_feature_value')) {
                
                /* Correspondance Carrosserie */
                $values = array('id_mod' => $id_feature_value, 'id_carro' => (int)Tools::getValue('id_carrosseries'));
                $query = Db::getInstance()->getRow('SELECT * FROM `'. _DB_PREFIX_ .'customproducts_carro_mod` WHERE `id_mod` = '. $id_feature_value);
                $result = (!$query) 
                    ? Db::getInstance()->execute('INSERT INTO `'. _DB_PREFIX_ .'customproducts_carro_mod` (`id_mod`, `id_carro`) VALUES (\''. implode('\',\'', $values) .'\')') 
                    : Db::getInstance()->execute('UPDATE `'. _DB_PREFIX_ .'customproducts_carro_mod` SET `id_carro` = '. (int)$values['id_carro'] .' WHERE `id_mod` = '. (int)$id_feature_value);
                    
                /* Correspondance Fixations */
                $values = array('id_mod' => $id_feature_value, 'id_fix' => (int)Tools::getValue('id_fixations'));
                $query = Db::getInstance()->getRow('SELECT * FROM `'. _DB_PREFIX_.'customproducts_mod_fix` WHERE `id_mod` = '.(int)$id_feature_value);
                $result *= (!$query) 
                    ? Db::getInstance()->execute('INSERT INTO `'. _DB_PREFIX_ .'customproducts_mod_fix` (`id_mod`, `id_fix`) VALUES (\''. implode('\',\'', $values) .'\')') 
                    : Db::getInstance()->execute('UPDATE `'. _DB_PREFIX_ .'customproducts_mod_fix` SET `id_fix` = '. (int)$values['id_fix'] .' WHERE `id_mod` = '. (int)$id_feature_value);
                    
                /* Correspondance Coffre */
                $values = array('id_mod' => $id_feature_value, 'coffre' => (int)Tools::getValue('coffre'));
                $query = Db::getInstance()->getRow('SELECT * FROM `'. _DB_PREFIX_.'customproducts_mod_coffre` WHERE `id_mod` = '.(int)$id_feature_value);
                $result *= (!$query) 
                    ? Db::getInstance()->execute('INSERT INTO `'. _DB_PREFIX_ .'customproducts_mod_coffre` (`id_mod`, `coffre`) VALUES (\''. implode('\',\'', $values) .'\')') 
                    : Db::getInstance()->execute('UPDATE `'. _DB_PREFIX_ .'customproducts_mod_coffre` SET `coffre` = '. (int)$values['coffre'] .' WHERE `id_mod` = '. (int)$id_feature_value);
                    
                // code Gabarit
                $values = array('id_mod' => $id_feature_value, 'code_gabarit' => Tools::getValue('code_gabarit'));
                $query = Db::getInstance()->getRow('SELECT * FROM `'. _DB_PREFIX_.'customproducts_mod_code_gabarit` WHERE `id_mod` = '.(int)$id_feature_value);
                $result *= (!$query) 
                    ? Db::getInstance()->execute('INSERT INTO `'. _DB_PREFIX_ .'customproducts_mod_code_gabarit` (`id_mod`, `code_gabarit`) VALUES (\''. implode('\',\'', $values) .'\')') 
                    : Db::getInstance()->execute('UPDATE `'. _DB_PREFIX_ .'customproducts_mod_code_gabarit` SET `code_gabarit` = \''. pSQL($values['code_gabarit']) .'\' WHERE `id_mod` = '. (int)$id_feature_value);
                    
                if (!$result) {
                    $this->errors[] = $this->trans('An error occurred while updating an object.', array(), 'Admin.Notifications.Error').' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
                } else {
                    Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.Tools::getAdminTokenLite('AdminLm_SurmesureCustomModels'));
                }
            }
            $this->errors[] = $this->trans('L\'idenfiant du modèle est requis.', array(), 'Admin.Notifications.Error');
        } else {
            parent::postProcess(true);
        }
    }
}
