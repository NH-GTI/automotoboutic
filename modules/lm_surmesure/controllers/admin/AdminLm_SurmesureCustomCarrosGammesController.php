<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureColors.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureConfigurations.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammes.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammesImages.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureModCodeGabarit.php');

class AdminLm_SurmesureCustomCarrosGammesController extends ModuleAdminController
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
        $this->table = 'feature_value';
        $this->identifier = 'id_feature_value';
        $this->className = 'FeatureValue';
        $this->lang = true;
        $this->addRowAction('delete');
        $this->explicitSelect = false;
        $this->allow_export = false;
        $this->deleted = false;

        $this->_select = 'CONCAT(a.`id_feature_value`,\'-\', cc.`id_gam`) AS `carro_gam_key`, cc.*, b.`value` AS `carrosserie`, fvl1.`value` AS `gamme`';
        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'customproducts_carro_gam` cc ON (cc.`id_carro` = a.`id_feature_value`)'."\n";
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` fvl1 ON (fvl1.`id_feature_value` = cc.`id_gam` AND fvl1.`id_lang` = '. (int)$this->context->language->id .')'."\n";
        $this->_where = 'AND a.`id_feature` = '. (int)$this->module->_id_carro .' AND NOT ISNULL(cc.`id_gam`) AND NOT ISNULL(cc.`id_carro`)';
        $this->_orderWay= 'ASC';
        $this->_orderBy = 'a.id_feature_value';
        
		$this->fields_list = array(
			'id_feature_value' => array(
				'title' => $this->trans('ID', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-center',
				'filter_key' => 'a!id_feature_value',
				'class' => 'fixed-width-xs'
			),
			'carrosserie' => array(
				'title' => $this->trans('Carrosseries', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'b!value',
			),
			'id_gam' => array(
				'title' => $this->trans('ID', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-center',
				'filter_key' => 'cc!id_gam',
				'class' => 'fixed-width-xs'
			),
			'gamme' => array(
				'title' => $this->trans('Gammes', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'fvl1!value',
			),
		);
    }

    public function renderForm()
    {
        // Carrosserie Fixations Coffre Code Gabarit
		$carroValues = FeatureValue::getFeatureValuesWithLang((int)$this->context->language->id, (int)$this->module->_id_carro);
		$gammesValues = FeatureValue::getFeatureValuesWithLang((int)$this->context->language->id, (int)$this->module->_id_gam);
        
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->trans('Carrosseries / Gammes', array(), 'Modules.Surmesure.Admin'),
                'icon' => 'icon-info-sign'
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->trans('Carrosserie', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'id_carrosserie',
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
                    'label' => $this->trans('Gammes', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'id_gamme',
                    'options' => array(
                        'query' => $gammesValues,
                        'id' => 'id_feature_value',
                        'name' => 'value'
                    ),
                    'required' => true,
                    'col' => '4',
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
        if (Tools::isSubmit('submitAddfeature_value')) {
            /* Carrosserie gamme */
            $values = array('id_carro' => (int)Tools::getValue('id_carrosserie'), 'id_gam' => (int)Tools::getValue('id_gamme'));
            $query = Db::getInstance()->getRow('SELECT * FROM `'. _DB_PREFIX_ .'customproducts_carro_gam` WHERE `id_carro` = '. (int)$values['id_carro'] .' AND `id_gam` = '. (int)$values['id_gam'] .'');
            $result = false;
            if (empty($query)) {
                $result = Db::getInstance()->execute('INSERT INTO `'. _DB_PREFIX_ .'customproducts_carro_gam` (`id_carro`, `id_gam`) VALUES ('. implode(',', $values) .')');
            } else {
                $this->errors[] = $this->trans('La correspondance existe déjà.', array(), 'Admin.Notifications.Error');
            }
                
            if (!$result) {
                $this->errors[] = $this->trans('An error occurred while updating an object.', array(), 'Admin.Notifications.Error').' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
            } else {
                Tools::redirectAdmin(self::$currentIndex.'&conf=3&token='.Tools::getAdminTokenLite('AdminLm_SurmesureCustomCarrosGammes'));
            }
        
        } elseif (Tools::isSubmit('deletefeature_value')) {
            /* Carrosserie gamme */
            $carro_gam_key = explode('-', Tools::getValue('carro_gam_key', ''));
            $result = false;
            if (count($carro_gam_key) == 2) {
                $values = array('id_carro' => (int)$carro_gam_key[0], 'id_gam' => (int)$carro_gam_key[1]);
                $result = Db::getInstance()->execute('DELETE FROM `'. _DB_PREFIX_ .'customproducts_carro_gam` WHERE `id_carro` = '. (int)$values['id_carro'] .' AND `id_gam` = '. (int)$values['id_gam'] .'');
            }
            
            if (!$result) {
                $this->errors[] = $this->trans('An error occurred while deleting an object.', array(), 'Admin.Notifications.Error').' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
            } else {
                Tools::redirectAdmin(self::$currentIndex.'&conf=1&token='.Tools::getAdminTokenLite('AdminLm_SurmesureCustomCarrosGammes'));
            }
        } else {
            parent::postProcess(true);
        }
    }
    
    public function renderList()
    {
        if (!($this->fields_list && is_array($this->fields_list))) {
            return false;
        }
        $this->getList($this->context->language->id);

        // If list has 'active' field, we automatically create bulk action
        if (isset($this->fields_list) && is_array($this->fields_list) && array_key_exists('active', $this->fields_list)
            && !empty($this->fields_list['active'])) {
            if (!is_array($this->bulk_actions)) {
                $this->bulk_actions = array();
            }

            $this->bulk_actions = array_merge(array(
                'enableSelection' => array(
                    'text' => $this->l('Enable selection'),
                    'icon' => 'icon-power-off text-success'
                ),
                'disableSelection' => array(
                    'text' => $this->l('Disable selection'),
                    'icon' => 'icon-power-off text-danger'
                ),
                'divider' => array(
                    'text' => 'divider'
                )
            ), $this->bulk_actions);
        }

        $helper = new HelperList();

        // Empty list is ok
        if (!is_array($this->_list)) {
            $this->displayWarning($this->l('Bad SQL query', 'Helper').'<br />'.htmlspecialchars($this->_list_error));
            return false;
        }

        $this->setHelperDisplay($helper);
        $helper->_default_pagination = $this->_default_pagination;
        $helper->_pagination = $this->_pagination;
        $helper->tpl_vars = $this->getTemplateListVars();
        $helper->tpl_delete_link_vars = $this->tpl_delete_link_vars;

        // For compatibility reasons, we have to check standard actions in class attributes
        foreach ($this->actions_available as $action) {
            if (!in_array($action, $this->actions) && isset($this->$action) && $this->$action) {
                $this->actions[] = $action;
            }
        }

        $helper->is_cms = $this->is_cms;
        $helper->sql = $this->_listsql;
        $helper->identifier = 'carro_gam_key';
        $list = $helper->generateList($this->_list, $this->fields_list);

        return $list;
    }
}
