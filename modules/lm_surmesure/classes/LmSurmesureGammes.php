<?php

class LmSurmesureGammes extends ObjectModel
{
	public $sort;
	public $id_gamme;
	public $prix;
	public $qualite_contour;
	public $qualite_moquette;
	public $qualite_materiaux;
	public $sous_couche;
	public $coloris;
	public $plus_produit;
	public $avis;
	/*Divioseo 25012021 #457 */
	public $choisi;
	public $surbase;
	public $rating;
	public $alias;
	public $status;
    
	public $nom_gamme;
    
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'customproducts_gammes',
		'primary' => 'id',
		'multilang' => false,
		'fields' => array(
			'sort' =>        array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'id_gamme' =>    array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
            'prix' =>        array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'qualite_contour' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => true),
            'qualite_moquette' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => true),
            'qualite_materiaux' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => true),
            'sous_couche' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => true),
            'coloris' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => true),
            'plus_produit' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => true),
            'avis' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => true),
			/*Divioseo 25012021 #457 */
			'choisi' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => true),
            'surbase' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => true),
			'rating' =>    array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'alias' =>   array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 50),
			'status' =>    array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
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
        }
    }

}
