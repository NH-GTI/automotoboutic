<?php

class LmSurmesureModCodeGabarit extends ObjectModel
{
	public $id_mod;
	public $code_gabarit;
    
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'customproducts_mod_code_gabarit',
		'primary' => 'id',
		'multilang' => false,
		'fields' => array(
			'id_mod' =>          array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'code_gabarit' =>    array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'required' => true, 'size' => 50),
		)
	);

}
