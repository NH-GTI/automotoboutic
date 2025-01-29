<?php

class LmSurmesureConfigurations extends ObjectModel
{
	public $id_conf;
	public $description;
	public $image;
    
	public $nom_configuration;
    
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'customproducts_configurations',
		'primary' => 'id',
		'multilang' => false,
		'fields' => array(
			'id_conf' =>     array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'description' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => true),
			'image' =>       array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 100),
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
                SELECT fvl.`value` AS `nom_configuration`
                FROM `'._DB_PREFIX_.'feature_value_lang` fvl
                WHERE 1 
                    AND fvl.`id_feature_value` = '. $this->id_conf .'
                    AND fvl.`id_lang` = '. (int)$idLang .'';
            $this->nom_configuration = Db::getInstance()->getValue($sql);
        }
    }

}
