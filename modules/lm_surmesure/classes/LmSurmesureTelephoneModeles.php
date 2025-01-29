<?php

class LmSurmesureTelephoneModeles extends ObjectModel
{
	public $id_marque;
	public $nom;
    
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'telephone_modeles',
		'primary' => 'id_modele',
		'multilang' => false,
		'fields' => array(
			'id_marque' =>   array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'nom' =>         array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'required' => true, 'size' => 64),
		)
	);

    public static function getModeles ()
    {
        $items = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT mo.*, ma.`nom` AS `nom_marque`, CONCAT(\'[\', ma.`nom`, \'] \', mo.`nom`) AS `marque_modele`
            FROM `'._DB_PREFIX_.'telephone_modeles` mo
            LEFT JOIN `'._DB_PREFIX_.'telephone_marques` ma ON (ma.`id_marque` = mo.`id_marque`)
            ORDER BY ma.`nom` ASC, mo.`nom` ASC'
        );
        
        return $items;
    }

}