<?php

class LmSurmesureBachesMarques extends ObjectModel
{
	public $nom;
	public $sort;
    
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'baches_marques',
		'primary' => 'id_marque',
		'multilang' => false,
		'fields' => array(
			'nom' =>     array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'required' => true, 'size' => 64),
			'sort' =>    array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
		)
	);

    public static function getMarques ()
    {
        $items = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT *
            FROM `'._DB_PREFIX_.'baches_marques`
            ORDER BY `sort` ASC, `nom` ASC'
        );
        
        return $items;
    }

}
