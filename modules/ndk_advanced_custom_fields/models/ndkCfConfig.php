<?php
/**
 *  Tous droits réservés NDKDESIGN
 *
 *  @author    Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*/

class NdkCfConfig extends ObjectModel 
{
	
	public $id_user;
	public $id_product;
	public $id_customization;
	public $is_admin;
	public $name;
	public $tags;
	public $default_config;
	public $price;
	public $id_lang_default;
	public $json_values;
		
	public static $definition = array(
		'table' => 'ndk_customization_field_configuration',
		'primary' => 'id_ndk_customization_field_configuration',
		'multilang' => true,
		'fields' => array(
					'id_user' =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
					'id_lang_default' =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
					'id_product' =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
					'id_customization' =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
					'is_admin' => 	array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => false),
					'default_config' => 	array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => false),
					'price' => array('type' => ObjectModel::TYPE_FLOAT,'required' => false),
					'json_values' => 	array('type' => self::TYPE_HTML, 'lang' => false, 'required' => false),
					'name' => 	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => false),
					'tags' => 	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => false),
		),
	);
	
	public $leftFile = 'default';
	public $rightFile = 'default';
	public $layerFile = 'default';
	public $pdffile = 'default';
	public $cover = false;
	public $pdfDir = false;
	public $jsonFile = false;
	
	
	public function __construct($id = null, $id_lang = null)
	{
	    parent::__construct($id, $id_lang);
	    $leftFile = _PS_IMG_DIR_.'scenes/'.'ndkcf/configs/'.$this->id_user.'/'.$this->id_product.'/'.$this->id.'-left.html';
	    $rightFile = _PS_IMG_DIR_.'scenes/'.'ndkcf/configs/'.$this->id_user.'/'.$this->id_product.'/'.$this->id.'-right.html';
	    $layerFile = _PS_IMG_DIR_.'scenes/'.'ndkcf/configs/'.$this->id_user.'/'.$this->id_product.'/'.$this->id.'-layer.html';

	    if(isset($this->id_customization)){
		    $id_customization = (int)$this->id_customization;
		    $sqlc = "SELECT c.id_product FROM "._DB_PREFIX_."customization c 
		    WHERE c.id_customization = ".(int)$id_customization;
		    $pdffile = _PS_IMG_DIR_.'scenes/ndkcf/pdf/'.$this->id_user.'/'.(int)Db::getInstance()->getRow($sqlc)['id_product'].'/'.$id_customization.'/render.html';
		    $this->pdffile = ($this->id && file_exists($pdffile) ? $pdffile : false);
			
			$jsonFile = _PS_IMG_DIR_.'scenes/ndkcf/pdf/'.$this->id_user.'/'.(int)Db::getInstance()->getRow($sqlc)['id_product'].'/'.$id_customization.'/config.json';
			$this->jsonFile = ($this->id && file_exists($jsonFile) ? $jsonFile : false);

		    $this->pdfDir = _PS_IMG_DIR_.'scenes/ndkcf/pdf/'.$this->id_user.'/'.(int)Db::getInstance()->getRow($sqlc)['id_product'].'/'.$id_customization;
	    }
	    $this->leftFile = ($this->id && file_exists($leftFile) ? $leftFile : false);
	    $this->rightFile = ($this->id && file_exists($rightFile) ? $rightFile : false);
	   	$this->layerFile = ($this->id && file_exists($layerFile) ? $layerFile : false);
	   	
	   	if(file_exists(_PS_IMG_DIR_.'scenes/'.'ndkcf/configs/'.$this->id_user.'/'.$this->id_product.'/'.$this->id.'-0-img.jpg'))
	   		if(getimagesize(_PS_IMG_DIR_.'scenes/'.'ndkcf/configs/'.$this->id_user.'/'.$this->id_product.'/'.$this->id.'-0-img.jpg') > 0)
	   			$this->cover = 'ndkcf/configs/'.$this->id_user.'/'.$this->id_product.'/'.$this->id.'-0-img.jpg';
	   		elseif(file_exists(_PS_IMG_DIR_.'scenes/'.'ndkcf/configs/'.$this->id_user.'/'.$this->id_product.'/'.$this->id.'-1-img.jpg'))
	   			if(getimagesize(_PS_IMG_DIR_.'scenes/'.'ndkcf/configs/'.$item['id_user'].'/'.$this->id_user.'/'.$this->id_product.'/'.$this->id.'-1-img.jpg') > 0)
	   				$this->cover = 'ndkcf/configs/'.$this->id_user.'/'.$this->id_product.'/'.$this->id.'-1-img.jpg';
	   
	    
	}
	
	public function delete()
	{
		if($this->leftFile)
			unlink($this->leftFile);
		if($this->rightFile)
			unlink($this->rightFile);
		if($this->layerFile)
			unlink($this->layerFile);
		if($this->pdffile)
			unlink($this->pdffile);
		if($this->pdffile)
			unlink($this->pdffile);
		if($this->cover)
			unlink(_PS_IMG_DIR_.'scenes/'.$this->cover);
		if($this->jsonFile)
			$this->rrmdir($this->jsonFile);
			
		for ($i = 0; $i < 100; $i++) {
			if(file_exists(_PS_IMG_DIR_.'scenes/'.'ndkcf/configs/'.$this->id_user.'/'.$this->id_product.'/'.$this->id.'-'.$i.'-img.jpg'))
				unlink(_PS_IMG_DIR_.'scenes/'.'ndkcf/configs/'.$this->id_user.'/'.$this->id_product.'/'.$this->id.'-'.$i.'-img.jpg');
		}
		
		return parent::delete();
	}
	
	public static function checkEnvironment()
	{
		$cookie = new Cookie('psAdmin', '', (int)Configuration::get('PS_COOKIE_LIFETIME_BO'));
		return isset($cookie->id_employee) && isset($cookie->passwd) && Employee::checkPassword($cookie->id_employee, $cookie->passwd);
	}
	
	public static function getConfigs($id_user, $id_product = false, $id_lang)
	{
			$search = Db::getInstance()->executeS(
				'SELECT fc.*, fcl.* FROM '._DB_PREFIX_.'ndk_customization_field_configuration fc
				LEFT JOIN `'._DB_PREFIX_.'ndk_customization_field_configuration_lang` fcl
					ON (fc.`id_ndk_customization_field_configuration` = fcl.`id_ndk_customization_field_configuration` AND fcl.`id_lang` = '.(int)$id_lang.')
				WHERE 1 AND fc.id_lang_default = '.(int)$id_lang.'  
				AND id_user = '.(int)$id_user.' 
				'.($id_product > 0 ? 'AND id_product = '.(int)$id_product.' ': '')
			);
			$i =0;
			foreach($search as $item)
			{
				$search[$i]['img'] = false;
				if(file_exists(_PS_IMG_DIR_.'scenes/'.'ndkcf/configs/'.$item['id_user'].'/'.$item['id_product'].'/'.$item['id_ndk_customization_field_configuration'].'-0-img.jpg'))
					if(getimagesize(_PS_IMG_DIR_.'scenes/'.'ndkcf/configs/'.$item['id_user'].'/'.$item['id_product'].'/'.$item['id_ndk_customization_field_configuration'].'-0-img.jpg') > 0)
						$search[$i]['img'] = 'ndkcf/configs/'.$item['id_user'].'/'.$item['id_product'].'/'.$item['id_ndk_customization_field_configuration'].'-0-img.jpg';
					elseif(file_exists(_PS_IMG_DIR_.'scenes/'.'ndkcf/configs/'.$item['id_user'].'/'.$item['id_product'].'/'.$item['id_ndk_customization_field_configuration'].'-1-img.jpg'))
						if(getimagesize(_PS_IMG_DIR_.'scenes/'.'ndkcf/configs/'.$item['id_user'].'/'.$item['id_product'].'/'.$item['id_ndk_customization_field_configuration'].'-1-img.jpg') > 0)
							$search[$i]['img'] = 'ndkcf/configs/'.$item['id_user'].'/'.$item['id_product'].'/'.$item['id_ndk_customization_field_configuration'].'-1-img.jpg';
					
					
				$i++;
			}
			if (sizeof($search) > 0)
			{
				return $search;
			}
	}
	
	public static function getAdminConfigs($id_product = false, $id_lang)
	{
			$search = Db::getInstance()->executeS(
				'SELECT fc.*, fcl.* FROM '._DB_PREFIX_.'ndk_customization_field_configuration fc
				LEFT JOIN `'._DB_PREFIX_.'ndk_customization_field_configuration_lang` fcl
					ON (fc.`id_ndk_customization_field_configuration` = fcl.`id_ndk_customization_field_configuration` AND fcl.`id_lang` = '.(int)$id_lang.') 
				WHERE 1 
				AND is_admin = 1 AND fc.id_lang_default = '.(int)$id_lang.' 
				'.($id_product > 0 ? 'AND id_product = '.(int)$id_product.' ': '')
			);
			
			$i =0;
			foreach($search as $item)
			{
				$search[$i]['img'] = false;
				if(file_exists(_PS_IMG_DIR_.'scenes/'.'ndkcf/configs/'.$item['id_user'].'/'.$item['id_product'].'/'.$item['id_ndk_customization_field_configuration'].'-0-img.jpg'))
					$search[$i]['img'] = 'ndkcf/configs/'.$item['id_user'].'/'.$item['id_product'].'/'.$item['id_ndk_customization_field_configuration'].'-0-img.jpg';
					
				$i++;
			}
			
			if (sizeof($search) > 0)
			{
				return $search;
			}
	}
	
	public function rrmdir($dir) { 
	   if (is_dir($dir)) { 
	     $objects = scandir($dir); 
	     foreach ($objects as $object) { 
	       if ($object != "." && $object != "..") { 
	         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
	       } 
	     } 
	     reset($objects); 
	     rmdir($dir); 
	   } 
	} 
	
	public static function setDefaultConfig($id_ndk_customization_field_configuration)
	{
	   $config = new NdkCfConfig((int)$id_ndk_customization_field_configuration);
	   $id_lang = Context::getContext()->language->id;
	   if($config->default_config == 0)
	   Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'ndk_customization_field_configuration SET default_config = 0 WHERE id_product = '.(int)$config->id_product.' AND id_lang_default='.(int)$id_lang);
	   Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'ndk_customization_field_configuration SET default_config = 
	   case
	      when default_config = 0 then 1
	   else 0
	      end 
	   WHERE id_ndk_customization_field_configuration = '.(int)$id_ndk_customization_field_configuration);
	   NdkCfConfig::clearAllCache();
	}
	
	public static function getDefaultConfigPrice($id_product)
	{
	  $id_lang = Context::getContext()->language->id;
	  $search = Db::getInstance()->getRow(
	  	'SELECT price FROM '._DB_PREFIX_.'ndk_customization_field_configuration 
	  	WHERE id_product = '.(int)$id_product.' AND default_config = 1 AND id_lang_default = '.(int)$id_lang);
	  
	  if($search && $search['price'] > 0)
	  {
	  	$id_address = (int)Context::getContext()->cart->id_address_invoice;
	  	$address = Address::initialize($id_address, true);
	  	$tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)$id_product, Context::getContext()));
	  	$product_tax_calculator = $tax_manager->getTaxCalculator();
	  	$usetax = Group::getPriceDisplayMethod(Group::getPriceDisplayMethod(Context::getContext()->customer->id_default_group));
	  	if(Product::$_taxCalculationMethod == 0){
	  		$usetax = true;
	  	}
	  	else
	  	{
	  		$usetax = false;
	  	}
	  	$price = $product_tax_calculator->addTaxes($search['price']);
	  	return $price;
	  }
	  else
	  {
	  	return false;
	  }
	}
	
	public static function clearAllCache() {
		Db::getInstance()->Execute('TRUNCATE TABLE `'._DB_PREFIX_.'ndk_customization_field_cache`');
	}
}
?>