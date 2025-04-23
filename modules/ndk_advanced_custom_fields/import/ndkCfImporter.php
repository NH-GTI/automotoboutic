<?php

require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCf.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCfValues.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCfGroup.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCfSpecificPrice.php';

class NdkCfImporter
{
	public function _construct()
	{

	}


	public static function buildDatas($datas)
	{
		return $datas;
	}

	public static function prepareDatasForObject($row, $class)
	{
		$objDatas = array();
		foreach ($row as $k => $v)
		{
			if(strpos($k, $class.'_') > -1)
			 $objDatas[str_replace($class.'_', '', $k)] = $v;

		}
		return $objDatas;
	}


	public static function buildField($datas)
	{
		$datas = self::buildDatasFromDef($datas, 'NdkCf');
		$current = (int)NdkCf::getIdByAdminName($datas['admin_name'][Configuration::get('PS_LANG_DEFAULT')]);
		$o = new NdkCf($current);
		$rec = self::setObjectFromDatas($o, $datas);
		if($current == 0)
			self::addToShops($rec);
		return $rec;
	}

	public static function buildValue($datas)
	{
		$datas = self::buildDatasFromDef($datas, 'NdkCfValues');
		$current = (int)NdkCfValues::getIdByReference($datas['reference']);
		$o = new NdkCfValues($current);
		$rec = self::setObjectFromDatas($o, $datas);
		return $rec;
	}



	public static function processDuplicateValue($id)
   {
		   $value = new NdkCfValues($id);
		   if (Validate::isLoadedObject($value))
		   {
			  $value_new = $value->duplicateObject();
			  $value_new->update();
			  NdkCfValues::duplicateSpecificPrice($value->id_ndk_customization_field, $value_new->id_ndk_customization_field, $value->id, $value_new->id);
			  NdkCfValues::duplicateImages($value->id, $value_new->id);
			  NdkCfValues::duplicateImagesSvg($value->id, $value_new->id);
			  NdkCfValues::duplicateMP3($value->id, $value_new->id);
			  return $value_new;
		   }
   }

	public static function buildGroup($datas)
	{
		$datas = self::buildDatasFromDef($datas, 'NdkCfGroup');
		$current = (int)NdkCfGroup::getIdByName($datas['name'][Configuration::get('PS_LANG_DEFAULT')]);
		$o = new NdkCfGroup($current);
		$rec = self::setObjectFromDatas($o, $datas);
		return $rec;
	}

	public static function buildSpecificPrice($datas)
	{
		$datas = self::buildDatasFromDef($datas, 'NdkCfSpecificPrice');
		$current = (int)NdkCfSpecificPrice::getIdByPrimary($datas['id_ndk_customization_field_value'], $datas['from_quantity']);
		$o = new NdkCfSpecificPrice($current);
		$rec = self::setObjectFromDatas($o, $datas);
	}

	public static function setObjectFromDatas($o, $datas)
	{
		foreach($datas as $k=>$v)
		{
			$o->$k = $v;
		}
		$o->save();
		return $o;
	}

	public static function addToShops($obj)
	{
		$id_shop_list = Shop::getShops(true, null, true);
		// Database insertion for multishop fields related to the object
			$fields = $obj->getFieldsShop();
			$fields[$obj::$definition['primary']] = (int) $obj->id;

			foreach ($id_shop_list as $id_shop) {
				$fields['id_shop'] = (int) $id_shop;
				$result = Db::getInstance()->insert($obj::$definition['table'] . '_shop', $fields, false);
			}

	}


	public static function buildDatasFromDef($datas, $class)
	{
		$datas = self::prepareDatasForObject($datas, $class);
		$o = new $class();
		$def = $o::$definition['fields'];
		$i = 0;
		foreach($datas as $k=>$v)
		{
			$d = $def[$k];
			if(array_key_exists('lang', $d))
			{
				if($d['lang'])
				{
					$datas[$k] = self::buildLanguageData($v);
				}
			}
			$i++;
		}
		return $datas;
	}

	public static function buildLanguageData($datas)
	{
		$languages = Language::getLanguages(false);
		$l_datas = array();
		foreach($languages as $lang)
		{
			$l_datas[$lang['id_lang']] = self::removeQuotes($datas);
		}
		return $l_datas;
	}


	public static function download_file($url, $path) {
	  $newfilename = $path;
	  if(!file_exists($newfilename))
	  {
		  $file = fopen ($url, "rb");
		  if ($file) {
			$newfile = fopen ($newfilename, "wb");
			if ($newfile)
			while(!feof($file)) {
			  fwrite($newfile, fread($file, 1024 * 8 ), 1024 * 8);
			}
		  }
		  if ($file) {
			fclose($file);
		  }
		  if ($newfile) {
			fclose($newfile);
		  }
		}
	 }

	public static function download_fileByFtp($ftp_hote, $ftp_username = '', $ftp_password = '', $ftp_port = 21, $remote_file, $local_file)
	{
		//var_dump($ftp_hote);die();
		$ftp_connect = ftp_connect($ftp_hote, $ftp_port);
		$ftp_login = ftp_login($ftp_connect, $ftp_username, $ftp_password);
		// Vérification de la connexion
		if ((!$ftp_connect) || (!$ftp_login)) {
			echo "La connexion FTP a échoué !";
			echo "Tentative de connexion au serveur $ftp_hote pour l'utilisateur $ftp_username";
			exit;
		}
		else
		{
			if(!file_exists($local_file))
			{
				// download server file
				if (ftp_get($ftp_connect, $local_file, $remote_file, FTP_ASCII))
				  {
				  echo "Successfully written to $local_file.";
				  }
				else
				  {
				  echo "Error downloading $remote_file.";
				  }
			}
		}

		// Fermeture de la connexion
		ftp_close($ftp_connect);

	}

	 public static function tofloat($num, $percentage = false) {
		//$percentage = false;
		$dotPos = strrpos($num, '.');
		$commaPos = strrpos($num, ',');
		$sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
			((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);


		if (!$sep) {
			$myNum =  floatval(preg_replace("/[^0-9]/", "", $num));
			if($percentage)
				$myNum += $myNum*($percentage/100);
			return $myNum;
		}

		$myNum = floatval(
			preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
			preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
		);
		if($percentage)
				$myNum += $myNum*($percentage/100);
		return $myNum;
	}

	public static function getIdByReference($reference)
	{
		if (empty($reference)) {
			return 0;
		}

		if (!Validate::isReference($reference)) {
			return 0;
		}

		$query = new DbQuery();
		$query->select('p.id_product');
		$query->from('product', 'p');
		$query->where('p.reference = \'' . pSQL($reference) . '\'');

		$pid =  Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
		if(!$pid)
		{
			$query = new DbQuery();
			$query->select('pa.id_product');
			$query->from('product_attribute', 'pa');
			$query->where('pa.reference = \'' . pSQL($reference) . '\'');
			return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
		}
	}

	public static function removeQuotes($str)
	{
			return str_replace( array( "'","'", '"', '\"', "\'" ),'',$str );
	}

	public static function dump($data, $die = false)
	{
		echo '<pre>';
			print_r($data);
		echo '</pre>';
		if($die)
			die();
	}

	public static function array_key_prefix_suffix(&$array,$prefix='',$suffix='')
	{
		$key_array = array_keys($array);
		$key_string = $prefix.implode($suffix.','.$prefix,$key_array).$suffix;
		$key_array = explode(',', $key_string);
		$array = array_combine($key_array, $array);
		return $array;
	}

	public static function getFiles($id)
	{
		$shop_domain = (Configuration::get('PS_SSL_ENABLED') == 1 && Configuration::get('PS_SSL_ENABLED_EVERYWHERE') == 1 ? 'https://' : 'http://').Context::getContext()->shop->domain;
		$my_files = array();
		$possibles_files = array(
				$id.'.jpg',
				$id.'-mask.jpg',
				$id.'-texture.jpg',
				$id.'-picto.jpg',
				$id.'.csv',
				$id.'.svg',
				$id.'.mp3',
			);
		foreach($possibles_files as $file)
		{
			$base_img_path = _PS_IMG_DIR_.'scenes/'.'ndkcf/'.$file;

			if (file_exists($base_img_path))
			{
				$my_files[] = $shop_domain.'/img/scenes/'.'ndkcf/'.$file;
			}

		}
		return implode(',', $my_files);
	}
}
