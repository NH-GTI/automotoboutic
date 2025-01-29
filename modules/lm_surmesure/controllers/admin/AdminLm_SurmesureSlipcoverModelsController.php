<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureHoussesMarques.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureHoussesModeles.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureHoussesTaillesModeles.php');

class AdminLm_SurmesureSlipcoverModelsController extends ModuleAdminController
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
        $this->table = 'housses_modeles';
        $this->identifier = 'id_modele';
        $this->className = 'LmSurmesureHoussesModeles';
        $this->lang = false;
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->explicitSelect = false;
        $this->allow_export = false;
        $this->deleted = false;
        
        $this->_select = 'm.`nom` AS `nom_marque`';
        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'housses_marques` m ON (m.`id_marque` = a.`id_marque`)';
        $this->_orderWay= 'ASC';
        $this->_orderBy = 'a.nom';

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->trans('Delete selected', array(), 'Admin.Actions'),
                'confirm' => $this->trans('Delete selected items?', array(), 'Admin.Notifications.Warning'),
                'icon' => 'icon-trash'
            )
        );
        
		$this->fields_list = array(
			'id_modele' => array(
				'title' => $this->trans('ID', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-center',
				'filter_key' => 'a!id_modele',
				'class' => 'fixed-width-xs'
			),
			'nom_marque' => array(
				'title' => $this->trans('Marque', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'm!nom',
			),
			'nom' => array(
				'title' => $this->trans('Modèle', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'a!nom',
			),
		);
    }

    public function renderForm()
    {
        if (!($object = $this->loadObject(true))) {
            return;
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
                        'query' => LmSurmesureHoussesMarques::getMarques(),
                        'id' => 'id_marque',
                        'name' => 'nom'
                    ),
                    'required' => true,
                    'col' => '4',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Modèle', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'nom',
                    'col' => '4',
                    'required' => true,
                ),
            )
        );
        
        if (!empty($object->id)) {
            $this->fields_form['input'][] = array(
                'type' => 'hidden',
                'name' => 'id_modele',
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
}
