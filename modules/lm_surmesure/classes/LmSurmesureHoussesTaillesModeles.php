<?php

class LmSurmesureHoussesTaillesModeles extends ObjectModel
{
	public $id_modele;
	public $premium_avant;
	public $premium_complet;
	public $elite_avant;
	public $elite_complet;
    
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'housses_tailles_modeles',
		'primary' => 'id',
		'multilang' => false,
		'fields' => array(
			'id_modele' =>       array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'premium_avant' =>   array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'size' => 10),
			'premium_complet' => array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'size' => 5),
			'elite_avant' =>     array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'size' => 5),
			'elite_complet' =>   array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'size' => 5),
		)
	);

}
