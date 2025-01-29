<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureBachesCategories.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureBachesMarques.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureBachesModeles.php');

class AdminLm_SurmesureBachesModelsController extends ModuleAdminController
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
        $this->table = 'baches_modeles';
        $this->identifier = 'id_modele';
        $this->className = 'LmSurmesureBachesModeles';
        $this->lang = false;
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->explicitSelect = false;
        $this->allow_export = false;
        $this->deleted = false;
        
        $this->_select = 'm.`nom` AS `nom_marque`';
        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'baches_marques` m ON (m.`id_marque` = a.`id_marque`)';
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
			't1' => array(
				'title' => $this->trans('T1', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'a!t1',
			),
			't2' => array(
				'title' => $this->trans('T2', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'a!t2',
			),
			't3' => array(
				'title' => $this->trans('T3', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'a!t3',
			),
			't4' => array(
				'title' => $this->trans('T4', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'a!t4',
			),
			't5' => array(
				'title' => $this->trans('T5', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'a!t5',
			),
			't6' => array(
				'title' => $this->trans('T6', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'a!t6',
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
                        'query' => LmSurmesureBachesMarques::getMarques(),
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
                array(
                    'type' => 'text',
                    'label' => $this->trans('T1', array(), 'Modules.Surmesure.Admin'),
                    'name' => 't1',
                    'col' => '4',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('T2', array(), 'Modules.Surmesure.Admin'),
                    'name' => 't2',
                    'col' => '4',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('T3', array(), 'Modules.Surmesure.Admin'),
                    'name' => 't3',
                    'col' => '4',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('T4', array(), 'Modules.Surmesure.Admin'),
                    'name' => 't4',
                    'col' => '4',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('T5', array(), 'Modules.Surmesure.Admin'),
                    'name' => 't5',
                    'col' => '4',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('T6', array(), 'Modules.Surmesure.Admin'),
                    'name' => 't6',
                    'col' => '4',
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
