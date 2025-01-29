<?php

class LmSurmesureBachesModeles extends ObjectModel
{
	public $id_marque;
	public $nom;
	public $t1;
	public $t2;
	public $t3;
	public $t4;
	public $t5;
	public $t6;
    
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'baches_modeles',
		'primary' => 'id_modele',
		'multilang' => false,
		'fields' => array(
			'id_marque' =>  array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'nom' =>    array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'required' => true, 'size' => 64),
			't1' =>    array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'size' => 16),
			't2' =>    array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'size' => 16),
			't3' =>    array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'size' => 16),
			't4' =>    array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'size' => 16),
			't5' =>    array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'size' => 16),
			't6' =>    array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'size' => 16),
		)
	);

    public static function getModeles ()
    {
        $items = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT mo.*, ma.`nom` AS `nom_marque`, CONCAT(\'[\', ma.`nom`, \'] \', mo.`nom`) AS `marque_modele`
            FROM `'._DB_PREFIX_.'baches_modeles` mo
            LEFT JOIN `'._DB_PREFIX_.'baches_marques` ma ON (ma.`id_marque` = mo.`id_marque`)
            ORDER BY ma.`nom` ASC, mo.`nom` ASC'
        );
        
        return $items;
    }
}
