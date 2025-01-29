<?php

class LmSurmesureGammesImages extends ObjectModel
{
	public $id_gamme;
	public $image;
	public $legende;
	public $sort;
    
	public $nom_gamme;
	public $gamme_alias;
    
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'customproducts_gammes_images',
		'primary' => 'id',
		'multilang' => false,
		'fields' => array(
			'id_gamme' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'image' =>   array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 64),
			'legende' =>    array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 255),
			'sort' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
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
                SELECT fvl.`value` AS `nom_gamme`
                FROM `'._DB_PREFIX_.'feature_value_lang` fvl
                WHERE 1 
                    AND fvl.`id_feature_value` = '. $this->id_gamme .'
                    AND fvl.`id_lang` = '. (int)$idLang .'';
            $this->nom_gamme = Db::getInstance()->getValue($sql);
            $sql = '
                SELECT g.`alias`
                FROM `'._DB_PREFIX_.'customproducts_gammes` g
                WHERE 1 
                    AND g.`id_gamme` = '. $this->id_gamme .'';
            $this->gamme_alias = Db::getInstance()->getValue($sql);
        }
    }

}
