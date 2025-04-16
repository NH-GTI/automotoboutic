<?php

class ndkCfMain
{
		public static $fields_mapping = array();
		public static $values_mapping = array();
		public static function runDatas()
		{
			if(Tools::getIsset('resetAll') || Tools::getIsset('forceIds'))
				self:: resetAllFields();
			if(Tools::getIsset('runfields'))
				self:: runDatasFields();
			if(Tools::getIsset('runprices'))
				self:: runDatasPrices();
			if(Tools::getIsset('backup'))
				self::backupFields(0);
		}
		
		public static function resetAllFields()
		{
			self::backupFields(0);
			
			$tables = array(
				'ndk_customization_field', 
				'ndk_customization_field_lang', 
				'ndk_customization_field_shop', 
				'ndk_customization_field_value', 
				'ndk_customization_field_value_lang', 
				'ndk_customization_field_csv',
				'ndk_customization_field_group',
				'ndk_customization_field_group_lang',
				'ndk_customization_field_group_shop',
				'ndk_customization_field_recipient',
				'ndk_customization_field_specific_price',
			);
				
			foreach($tables as $table)
			{
				$req = 'TRUNCATE TABLE '._DB_PREFIX_.$table;
				Db::getInstance()->execute($req);
			}
		}
		
		public static function backupFields($group)
		{
			$url_json = Tools::getHttpHost(true).__PS_BASE_URI__.'/modules/ndk_advanced_custom_fields/import/exportGroup.php?id_group='.(int)$group.'&key='. Configuration::get('NDKCF_API_KEY');
			$json_datas = file_get_contents($url_json);
			$backp_file = dirname(__FILE__).'/backup/'.date('yyyy-mm-dd').'.json';
			file_put_contents($backp_file, $json_datas);
		}

		public static function runDatasFields()
		{
			//modules/ndk_advanced_custom_fields/import/importDatas.php?service=main&debug=1&runfields=1&src_domain=https://src.site.com&src_group=58&src_key=XXXXXX
			$url_json = Tools::getValue('src_domain').'/modules/ndk_advanced_custom_fields/import/exportGroup.php?id_group='.(int)Tools::getValue('src_group').'&key='.Tools::getValue('src_key');
			$json_datas = file_get_contents($url_json);
			$datas = Tools::jsonDecode($json_datas, true);
			// 1 le goup
			$id_fields = array();
			
			$start = (int)Tools::getValue('start', (int)configuration::get('NDKCF_IMPORT_MAIN'));
			$limit = (int)Tools::getValue('limit', 100);
			configuration::updateValue('NDKCF_IMPORT_MAIN', $start);
			$i = 0;
			foreach($datas['fields'] as $field)
			{
				if($i >= $start && $i < $start+$limit)
				{
					$old_id_field = $field['NdkCf_id_ndk_customization_field'];
					unset($field['NdkCf_id_shop']);
					unset($field['NdkCf_id_ndk_customization_field']);
					$field['NdkCf_admin_name'] = $field['NdkCf_admin_name'].'[imp:'.$old_id_field.']';
					$fieldObj = ndkCfImporter::buildField($field);
					$id_fields[] = $fieldObj->id;
					self::$fields_mapping[0] = 0;
					self::$fields_mapping[(int)$old_id_field] = $fieldObj->id;
					self::setItemFiles($field['files'], $old_id_field, $fieldObj->id);
					// 3 les valeur
					foreach($field['values'] as $val)
					{
						$old_id_field_value = (int)$val['NdkCfValues_id_ndk_customization_field_value'];
						if(empty($val['NdkCfValues_reference']))
						{
							$val['NdkCfValues_reference'] = $old_id_field_value.'-'.preg_replace('/\s/', '-', $val['NdkCfValues_value']);
						}
						else
						{
							$val['NdkCfValues_reference'] = $old_id_field_value.'-'.$val['NdkCfValues_reference'];
						}
						unset($val['NdkCfValues_id_ndk_customization_field_value']);
						unset($val['NdkCfValues_id_lang']);
						$val['NdkCfValues_id_ndk_customization_field'] = $fieldObj->id;
						$valObj = ndkCfImporter::buildValue($val);
						self::$values_mapping[0] = 0;
						self::$values_mapping[(int)$old_id_field_value] =  $valObj->id;	
						self::setItemFiles($val['files'], $old_id_field_value, $valObj->id);
						NdkCf::generateThumbs($fieldObj->id);
						foreach($val['specific_price'] as $price)
						{
							unset($price['NdkCfSpecificPrice_id_ndk_customization_field_specific_price']);
							$price['NdkCfSpecificPrice_id_ndk_customization_field'] = $fieldObj->id;
							$price['NdkCfSpecificPrice_id_ndk_customization_field_value'] = $valObj->id;
							ndkCfImporter::buildSpecificPrice($price);
						}
					}
					configuration::updateValue('NDKCF_IMPORT_MAIN', $i);
					$i++;
					if($i >= (count($datas['fields']) -1))
						configuration::updateValue('NDKCF_IMPORT_MAIN', 0);
				}
				else
				{
					if( $i > $start+$limit)
						exit('limited');
				}
			}
			if((int)Tools::getValue('src_group') > 0)
			{
				$groupOjb = ndkCfImporter::buildGroup(array(
					'NdkCfGroup_name' =>$datas['name'],
					'NdkCfGroup_fields' =>implode(',', $id_fields),
				));
			}
			
			if(count($id_fields) > 0)
			{
				self::restoreInfluences($id_fields);
				self::restoreTargets($id_fields);
			}
			
		}
		
		public static function restoreTargets($id_fields)
		{
			$fields_mapping = self::$fields_mapping;
			$values_mapping = self::$values_mapping;
			$fields = Db::getInstance()->executeS('SELECT id_ndk_customization_field, influences , target, target_child 
			FROM '._DB_PREFIX_.'ndk_customization_field 
			WHERE id_ndk_customization_field IN('.implode(',',$id_fields).') AND target <> ""');
			foreach($fields as $field)
			{
				$req = 'UPDATE '._DB_PREFIX_.'ndk_customization_field 
				set target = '.(int)$fields_mapping[$field['target']].', 
				target_child =  '.(int)$values_mapping[$field['target_child']].'
				WHERE id_ndk_customization_field = '.(int)$field['id_ndk_customization_field'];
				Db::getInstance()->execute($req);
			}
		}
		public static function restoreInfluences($id_fields)
		{
			$fields_mapping = self::$fields_mapping;
			$values_mapping = self::$values_mapping;

			$fields = Db::getInstance()->executeS('SELECT id_ndk_customization_field, influences , target, target_child 
			FROM '._DB_PREFIX_.'ndk_customization_field 
			WHERE id_ndk_customization_field IN('.implode(',',$id_fields).') AND influences <> ""');
			foreach($fields as $field)
			{
				$old_ids = explode(',', $field['influences']);
				$new_ids = array();
				foreach($old_ids as $id)
				{
					$new_ids[] = $fields_mapping[$id];
				}
				
				$req = 'UPDATE '._DB_PREFIX_.'ndk_customization_field 
				set influences ="'.implode(',', $new_ids).'" 
				WHERE id_ndk_customization_field = '.(int)$field['id_ndk_customization_field'];
				
				Db::getInstance()->execute($req);
				
				$values = Db::getInstance()->executeS('SELECT id_ndk_customization_field_value, influences_restrictions, influences_obligations 
				FROM '._DB_PREFIX_.'ndk_customization_field_value 
				WHERE id_ndk_customization_field ='.(int)$field['id_ndk_customization_field']);
				
				foreach($values as $value)
				{
					if(!empty($value['influences_obligations']))
					{
						$new_ids_o = self::reDesignInfluence($value['influences_obligations']);
						if($new_ids_o)
						{
							$req_o = 'UPDATE '._DB_PREFIX_.'ndk_customization_field_value set influences_obligations ="'.implode(',', $new_ids_o).'" 
							WHERE id_ndk_customization_field_value = '.(int)$value['id_ndk_customization_field_value'];
							Db::getInstance()->execute($req_o);
						}
						
					}
					
					if(!empty($value['influences_restrictions']))
					{
						$new_ids_r = self::reDesignInfluence($value['influences_restrictions']);
						if($new_ids_r)
						{
							$req_r = 'UPDATE '._DB_PREFIX_.'ndk_customization_field_value set influences_restrictions ="'.implode(',', $new_ids_r).'" 
							WHERE id_ndk_customization_field_value = '.(int)$value['id_ndk_customization_field_value'];
							Db::getInstance()->execute($req_r);
						}
						
					}
				}
				
			}
		}
		
		public static function reDesignInfluence($old_ids){
			$old_ids = explode(',', $old_ids);
			$new_ids = array();
			$values_mapping = self::$values_mapping;
			$fields_mapping = self::$fields_mapping;

			foreach($old_ids as $id)
			{
				$old_ids_r_arr = explode('-', $id);
				$my_row = array();
				if(sizeof($old_ids_r_arr) < 2)
				return false;
				
				if(array_key_exists($old_ids_r_arr[0], $values_mapping))
					$my_row[] = $values_mapping[$old_ids_r_arr[0]];
				else
					$my_row[] = $old_ids_r_arr[0];
					
				if(array_key_exists($old_ids_r_arr[1], $fields_mapping))
					$my_row[] = $fields_mapping[$old_ids_r_arr[1]];
				else
					$my_row[] = $old_ids_r_arr[1];
				
				$new_ids[] = implode('-', $my_row);
			}
			return $new_ids;
		}
		
		
	
		public static function setItemFiles($files, $old_id, $new_id)
		{
			$my_files = explode(',', $files);
			$path = _PS_IMG_DIR_.'scenes/'.'ndkcf/'; 
			foreach($my_files as $file)
			{
				$file_name_arr = explode('/', $file);
				$file_name = end($file_name_arr);
				$new_file_name = str_replace($old_id, $new_id, $file_name);	
				ndkCfImporter::download_file($file, $path.$new_file_name);
			}
		}

}