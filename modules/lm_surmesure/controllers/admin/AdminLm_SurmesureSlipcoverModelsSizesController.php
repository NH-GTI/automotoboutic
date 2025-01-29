<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureHoussesMarques.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureHoussesModeles.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureHoussesTaillesModeles.php');

class AdminLm_SurmesureSlipcoverModelsSizesController extends ModuleAdminController
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
        $this->table = 'housses_tailles_modeles';
        $this->identifier = 'id';
        $this->className = 'LmSurmesureHoussesTaillesModeles';
        $this->lang = false;
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->explicitSelect = false;
        $this->allow_export = false;
        $this->deleted = false;
        
        $this->_select = 'ma.`nom` AS `nom_marque`, mo.`nom` AS `nom_modele`, CONCAT(\'[\', ma.`nom`, \'] \', mo.`nom`) AS `marque_modele`';
        $this->_join  = 'LEFT JOIN `'._DB_PREFIX_.'housses_modeles` mo ON (mo.`id_modele` = a.`id_modele`)';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'housses_marques` ma ON (ma.`id_marque` = mo.`id_marque`)';
        $this->_orderWay= 'ASC';
        $this->_orderBy = 'a.id_modele';

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->trans('Delete selected', array(), 'Admin.Actions'),
                'confirm' => $this->trans('Delete selected items?', array(), 'Admin.Notifications.Warning'),
                'icon' => 'icon-trash'
            )
        );
        
		$this->fields_list = array(
			'id' => array(
				'title' => $this->trans('ID', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-center',
				'filter_key' => 'a!id',
				'class' => 'fixed-width-xs'
			),
			'marque_modele' => array(
				'title' => $this->trans('Modèle', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'ma!nom',
			),
			'premium_avant' => array(
				'title' => $this->trans('Premium avant', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'a!premium_avant',
			),
			'premium_complet' => array(
				'title' => $this->trans('Premium complet', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'a!premium_complet',
			),
			'elite_avant' => array(
				'title' => $this->trans('Elite avant', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'a!elite_avant',
			),
			'elite_complet' => array(
				'title' => $this->trans('Elite complet', array(), 'Modules.Surmesure.Admin'),
				'align' => 'text-left',
				'filter_key' => 'a!elite_complet',
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
                'title' => $this->trans('Modèles / Tailles', array(), 'Modules.Surmesure.Admin'),
                'icon' => 'icon-info-sign'
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->trans('Modèle', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'id_modele',
                    'options' => array(
                        'query' => LmSurmesureHoussesModeles::getModeles(),
                        'id' => 'id_modele',
                        'name' => 'marque_modele'
                    ),
                    'required' => true,
                    'col' => '4',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Premium avant', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'premium_avant',
                    'col' => '4',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Premium complet', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'premium_complet',
                    'col' => '4',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Elite avant', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'elite_avant',
                    'col' => '4',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Elite complet', array(), 'Modules.Surmesure.Admin'),
                    'name' => 'elite_complet',
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
}
