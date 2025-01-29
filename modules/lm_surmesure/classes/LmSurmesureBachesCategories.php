<?php

class LmSurmesureBachesCategories extends ObjectModel
{
	public $nom;
    
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'baches_categories',
		'primary' => 'id_categorie',
		'multilang' => false,
		'fields' => array(
			'nom' =>    array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'required' => true, 'size' => 64),
		)
	);

    public static function getCategories ()
    {
        $items = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT *
            FROM `'._DB_PREFIX_.'baches_categories`
            ORDER BY `nom` ASC'
        );
        
        return $items;
    }

}
