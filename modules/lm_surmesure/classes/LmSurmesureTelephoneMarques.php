<?php

class LmSurmesureTelephoneMarques extends ObjectModel
{
	public $nom;
    
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'telephone_marques',
		'primary' => 'id_marque',
		'multilang' => false,
		'fields' => array(
			'nom' =>    array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'required' => true, 'size' => 64),
		)
	);

    public static function getMarques ()
    {
        $items = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT *
            FROM `'._DB_PREFIX_.'telephone_marques`
            ORDER BY `nom` ASC'
        );
        
        return $items;
    }

}