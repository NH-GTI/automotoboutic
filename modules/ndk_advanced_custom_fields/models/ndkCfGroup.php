<?php
/**
 *  Tous droits réservés NDKDESIGN
 *
 *  @author    Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*/

class NdkCfGroup extends ObjectModel 
{
	
	public $products;
	public $categories;
	public $fields;
	public $name;
	public $mode;
		
	public static $definition = array(
		'table' => 'ndk_customization_field_group',
		'primary' => 'id_ndk_customization_field_group',
		'multilang' => true,
		'fields' => array(
					'products' =>			array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
					'mode' =>			array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
					'categories' =>			array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
					'fields' =>			array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
					'name' => 	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => false),
		),
	);
	
	
	public function __construct($id = null, $id_lang = null)
	{
	    parent::__construct($id, $id_lang);
	    	    
	}
	
	public function delete()
	{
		
		
		return parent::delete();
	}
	
	public static function getFieldsLight($current_group)
	{
		$id_lang = Context::getContext()->language->id;
		$sql_fields = 'SELECT cfg.`fields` 
		FROM `'._DB_PREFIX_.'ndk_customization_field_group` cfg 
		WHERE cfg.id_ndk_customization_field_group != '.(int)$current_group;
		
		$locked_fields = Db::getInstance()->executeS($sql_fields);
		$field_list = array();
		$field_list[] = (int)0;
		foreach($locked_fields as $row)
		{
			$row_f = explode(',', $row['fields']);
			foreach($row_f as $f)
				$field_list[] = (int)$f;
		}
		
		$sql = '
			SELECT cf.`id_ndk_customization_field` as id, cfl.`admin_name` as name  
			FROM `'._DB_PREFIX_.'ndk_customization_field` cf 
			LEFT JOIN `'._DB_PREFIX_.'ndk_customization_field_lang` cfl ON (cfl.`id_ndk_customization_field`= cf.`id_ndk_customization_field` AND cfl.`id_lang` = '.(int)$id_lang.' ) 
			WHERE  cf.`id_ndk_customization_field` NOT IN ('.implode(',', $field_list).') 
			GROUP BY  cf.id_ndk_customization_field';
		
		$fields = Db::getInstance()->executeS($sql);
		return $fields;
		
	}
	
	public static function getProductsLight()
	{
		$id_lang = Context::getContext()->language->id;
		$id_shop = Context::getContext()->shop->id;
		$sql = '
			SELECT p.`id_product` as id, CONCAT(\'#\', p.id_product,\' [\', cl.`name`, \'] \',  pl.`name`, \'  - ref: \' ,p.reference) as name  
			FROM `'._DB_PREFIX_.'product_shop` ps 
			LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product`= ps.`id_product`  
			LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product`= ps.`id_product` AND pl.`id_lang` = '.(int)$id_lang.' ) 
			LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (cl.`id_category`= ps.`id_category_default` AND cl.`id_lang` = '.(int)$id_lang.' ) 
			WHERE p.supplier_reference NOT LIKE \'myndkcustomprodPack\' AND p.id_category_default !='.(int)Configuration::get('NDK_ACF_CAT').' AND ps.`id_shop` = '.(int)$id_shop.' GROUP BY p.id_product ORDER BY cl.name, pl.name';
		
		$products = Db::getInstance()->executeS($sql);
		return $products;
		
	}
	
	public static function getIdByName($query)
	{
		if($query == '')
		return 0;
		$id_lang = Context::getContext()->language->id;
		$fields = Db::getInstance()->getValue('
				SELECT `id_ndk_customization_field_group` as id
				FROM `'._DB_PREFIX_.'ndk_customization_field_group_lang`
				WHERE name = "'.$query.'"');
		return $fields;
	}
	
}
?>