<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureHoussesMarques.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureHoussesModeles.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureHoussesTaillesModeles.php');

class AdminLm_SurmesureSlipcoverBrandsController extends ModuleAdminController
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
        $this->table = 'housses_marques';
        $this->identifier = 'id_marque';
        $this->className = 'LmSurmesureHoussesMarques';
        $this->lang = false;
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->explicitSelect = false;
        $this->allow_export = false;
        $this->deleted = false;
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
			'id_marque' => array(
				'title' => $this->trans('ID', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-center',
				'filter_key' => 'a!id_marque',
				'class' => 'fixed-width-xs'
			),
			'nom' => array(
				'title' => $this->trans('Marque', array(), 'Modules.Surmesure.Admin'),
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
                'title' => $this->trans('Marque', array(), 'Modules.Surmesure.Admin'),
                'icon' => 'icon-info-sign'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->trans('Marque', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'nom',
                    'col' => '4',
                    'required' => true,
                ),
            )
        );
        
        if (!empty($object->id)) {
            $this->fields_form['input'][] = array(
                'type' => 'hidden',
                'name' => 'id_marque',
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
