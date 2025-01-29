<?php

class LmSurmesureColors extends ObjectModel
{
	public $id_color;
	public $image;
	public $alias;
    
	public $nom_couleur;
    
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'customproducts_couleurs',
		'primary' => 'id',
		'multilang' => false,
		'fields' => array(
			'id_color' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'image' =>    array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 100),
			'alias' =>    array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 30),
		)
	);
    
    public function __construct($id = null, $idLang = null)
    {
        parent::__construct($id, $idLang);
        
        if ($this->id) {
            if (empty($idLang)) {
                $idLang = Context::getContext()->language->id;
            }
            $sql = '
                SELECT fvl.`value` AS `nom_couleur`
                FROM `'._DB_PREFIX_.'feature_value_lang` fvl
                WHERE 1 
                    AND fvl.`id_feature_value` = '. $this->id_color .'
                    AND fvl.`id_lang` = '. (int)$idLang .'';
            $this->nom_couleur = Db::getInstance()->getValue($sql);
        }
    }

}
