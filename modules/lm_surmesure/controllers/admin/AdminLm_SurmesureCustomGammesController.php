<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureColors.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureConfigurations.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammes.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammesImages.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureModCodeGabarit.php');

class AdminLm_SurmesureCustomGammesController extends ModuleAdminController
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
        $this->toolbar_btn['new']['href'] = $this->context->link->getAdminLink('AdminFeatures', true, array(), array('addfeature_value' => '1', 'id_feature' => (int)$this->module->_id_gam));
    }
    
    public function setItemsList ()
    {
        $this->table = 'customproducts_gammes';
        $this->identifier = 'id';
        $this->className = 'LmSurmesureGammes';
        $this->lang = false;
        $this->addRowAction('edit');
        $this->explicitSelect = false;
        $this->allow_export = false;
        $this->deleted = false;

        $this->_select = 'fvl.`value` AS `nom_gamme`';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'feature_value` fv ON (fv.`id_feature_value` = a.`id_gamme`)'."\n";
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` fvl ON (fvl.`id_feature_value` = fv.`id_feature_value` AND fvl.`id_lang` = '. (int)$this->context->language->id .')'."\n";
        $this->_where = 'AND fv.`id_feature` = '. (int)$this->module->_id_gam .'';
        $this->_orderWay= 'ASC';
        $this->_orderBy = 'fvl.value';
        
        
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
			'prix' => array(
				'title' => $this->trans('Prix', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-right',
				'filter_key' => 'a!prix',
                'type' => 'price',
                'currency' => true,
				'callback' => 'getProductPriceForListing',
                'orderby' => false,
                'search' => false,
			),
			'rating' => array(
				'title' => $this->trans('Note', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-center',
				'filter_key' => 'a!rating',
			),
			'coloris' => array(
				'title' => $this->trans('Coloris', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'a!coloris',
			),
		);
    }
    
    public function getProductPriceForListing ($echo, $tr)
    {
        return Tools::displayPrice($echo);
    }

    public function renderForm()
    {
        if (!($object = $this->loadObject(true))) {
            return;
        }
        
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->trans('Gammes', array(), 'Modules.Surmesure.Admin'),
                'icon' => 'icon-info-sign'
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_gamme',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Gamme', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'nom_gamme',
                    'col' => '4',
                    'disabled' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Prix', array(), 'Modules.Surmesure.Admin'),
                    'suffix' => $this->trans('€ TTC', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'prix',
                    'col' => '4',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Ordre d\'affichage', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'sort',
                    'col' => '4',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Qualité du contour', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'qualite_contour',
                    'col' => '4',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Qualité moquette', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'qualite_moquette',
                    'col' => '4',
                    'required' => true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->trans('Qualité des materiaux', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'qualite_materiaux',
                    'col' => '4',
                    'required' => true,
                    'rows' => '2',
                    'cols' => '10',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Sous-couche', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'sous_couche',
                    'col' => '4',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Coloris', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'coloris',
                    'col' => '4',
                    'required' => true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->trans('+ Produit', array(), 'Modules.Surmesure.Admin'),
                    'desc' => $this->trans('Retours à la ligne: | (Alt Gr + 6)', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'plus_produit',
                    'col' => '4',
                    'required' => true,
                    'rows' => '2',
                    'cols' => '10',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Avis', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'avis',
                    'col' => '4',
                    'required' => true,
                ),
				/*Divioseo 25012021 #457 */
				array(
                    'type' => 'text',
                    'label' => $this->trans('Texte sous prix', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'surbase',
                    'col' => '4',
                    'required' => true,
                ),
				array(
                    'type' => 'text',
                    'label' => $this->trans('Texte bouton', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'choisi',
                    'col' => '4',
                    'required' => true,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->trans('Note', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'rating',
                    'options' => array(
                        'query' => array(
                            array('id' => 1, 'value' => 1),
                            array('id' => 2, 'value' => 2),
                            array('id' => 3, 'value' => 3),
                            array('id' => 4, 'value' => 4),
                            array('id' => 5, 'value' => 5),
                            array('id' => 6, 'value' => 6),
                        ),
                        'id' => 'id',
                        'name' => 'value'
                    ),
                    'required' => true,
                    'col' => '4',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Alias', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'alias',
                    'col' => '4',
                    'required' => true,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->trans('Actif', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'status',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'status_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled', array(), 'Admin.Global')
                        ),
                        array(
                            'id' => 'status_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled', array(), 'Admin.Global')
                        )
                    ),
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
            if ($id_gamme = (int)Tools::getValue('id_gamme')) {
                $values = array(
                    'id_gamme' => $id_gamme, 
                    'prix' => (float)Tools::getValue('prix'), 
                    'sort' => (int)Tools::getValue('sort'), 
                    'qualite_contour' => pSQL(Tools::getValue('qualite_contour')), 
                    'qualite_moquette' => pSQL(Tools::getValue('qualite_moquette')), 
                    'qualite_materiaux' => pSQL(Tools::getValue('qualite_materiaux')), 
                    'sous_couche' => pSQL(Tools::getValue('sous_couche')), 
                    'coloris' => pSQL(Tools::getValue('coloris')), 
                    'plus_produit' => pSQL(Tools::getValue('plus_produit')), 
                    'avis' => pSQL(Tools::getValue('avis')),
					/*Divioseo 25012021 #457 */
					'choisi' => pSQL(Tools::getValue('choisi')), 
                    'surbase' => pSQL(Tools::getValue('surbase')), 					
                    'rating' => (int)Tools::getValue('rating'), 
                    'alias' => pSQL(Tools::getValue('alias')), 
                    'status' => (int)Tools::getValue('status'), 
                );
                $query = Db::getInstance()->getRow('SELECT `id` FROM `'. _DB_PREFIX_ .'customproducts_gammes` WHERE `id_gamme` = '. $id_gamme);
                $result = (!$query) 
                    ? Db::getInstance()->execute('INSERT INTO `'. _DB_PREFIX_ .'customproducts_gammes` 
                        (`id_gamme`, `prix`, `sort`, `qualite_contour`, `qualite_moquette`, `qualite_materiaux`, `sous_couche`, `coloris`, `plus_produit`, `avis`, `rating`, `alias`, `status`) 
                        VALUES (\''. implode('\',\'', $values) .'\')') 
                    : Db::getInstance()->execute('UPDATE `'. _DB_PREFIX_ .'customproducts_gammes` 
                        SET `prix` = '. $values['prix'] .'
                            , `sort` = '. $values['sort'] .'
                            , `qualite_contour` = \''. pSQL($values['qualite_contour']) .'\' 
                            , `qualite_moquette` = \''. pSQL($values['qualite_moquette']) .'\' 
                            , `qualite_materiaux` = \''. pSQL($values['qualite_materiaux']) .'\' 
                            , `sous_couche` = \''. pSQL($values['sous_couche']) .'\' 
                            , `coloris` = \''. pSQL($values['coloris']) .'\' 
                            , `plus_produit` = \''. pSQL($values['plus_produit']) .'\' 
                            , `avis` = \''. pSQL($values['avis']) .'\'
							, `choisi` = \''. pSQL($values['choisi']) .'\' 
                            , `surbase` = \''. pSQL($values['surbase']) .'\' 
                            , `rating` = '. $values['rating'] .'
                            , `alias` = \''. pSQL($values['alias']) .'\' 
                            , `status` = '. $values['status'] .'
                        WHERE `id_gamme` = '.$id_gamme);
                    
                if (!$result) {
                    $this->errors[] = $this->trans('An error occurred while updating an object.', array(), 'Admin.Notifications.Error').' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
                } else {
                    Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.Tools::getAdminTokenLite('AdminLm_SurmesureCustomGammes'));
                }
            }
            $this->errors[] = $this->trans('L\'idenfiant du modèle est requis.', array(), 'Admin.Notifications.Error');
        } else {
            parent::postProcess(true);
        }
    }
}
