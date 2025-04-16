<?php
include(dirname(__FILE__).'/../../../config/config.inc.php');
include(dirname(__FILE__).'/../../../init.php');
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/import/ndkCfImporter.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCfGroup.php';

/*
$tables = array(
	'ndk_customization_field -> OK',
	'ndk_customization_field_lang -> OK',
	'ndk_customization_field_value  -> OK',
	'ndk_customization_field_value_lang  -> OK',
	'ndk_customization_field_csv',
	'ndk_customization_field_group -> OK ',
	'ndk_customization_field_group_lang > OK',
	'ndk_customization_field_recipient  -> OK',
	'ndk_customization_field_specific_price',
	);
*/
$key = Configuration::get('NDKCF_API_KEY');
$key_get = Tools::getValue('key');

if($key_get != $key)
{
	die('Please, inform key in your url');
}

$id_group = (int)Tools::getValue('id_group');
$id_lang = Context::getContext()->language->id;
if($id_group == 0)
{
	$export_group['name'] = 'none';
	$export_group['group_fields'] = '';
}
else{
	$group = new NdkCfGroup($id_group);
	$export_group = array();
	$export_group['name'] = $group->name[$id_lang];
	$export_group['group_fields'] = $group->fields;
}


$sql_field = 'SELECT cf.*, cfl.name, cfl.admin_name, cfl.notice, cfl.tooltip FROM '._DB_PREFIX_.'ndk_customization_field AS cf
	LEFT JOIN '._DB_PREFIX_.'ndk_customization_field_lang AS cfl ON (cf.id_ndk_customization_field = cfl.id_ndk_customization_field AND cfl.id_lang = '.(int)$id_lang.')
	LEFT JOIN '._DB_PREFIX_.'ndk_customization_field_recipient AS cfr ON cf.id_ndk_customization_field = cfr.id_ndk_customization_field
	WHERE '.($id_group > 0 ? ' cf.id_ndk_customization_field IN ('.$group->fields.')' : '1' ).' AND cf.id_ndk_customization_field > 0  GROUP BY cf.id_ndk_customization_field';
$fields = Db::getInstance()->executeS($sql_field);
$export_group['fields'] = array();

$i = 0;
foreach($fields as $key=>$value)
{
	$value = NdkCfImporter::array_key_prefix_suffix($value, 'NdkCf_');
	$export_group['fields'][$i] = $value;
	$export_group['fields'][$i]["files"] = NdkCfImporter::getFiles($value["NdkCf_id_ndk_customization_field"]);
	//$export_group['fields'][$i] = array('NdkCf_'.$key => $value);
	$sql_field_value = 'SELECT *
	FROM '._DB_PREFIX_.'ndk_customization_field_value  AS cfv
	LEFT JOIN '._DB_PREFIX_.'ndk_customization_field_value_lang AS cfvl ON (cfv.id_ndk_customization_field_value = cfvl.id_ndk_customization_field_value AND cfvl.id_lang = '.(int)$id_lang.')
	WHERE  cfv.id_ndk_customization_field ='.(int)$value["NdkCf_id_ndk_customization_field"];

	 $fields_values = Db::getInstance()->executeS($sql_field_value);
	 $j = 0;
	 $export_group['fields'][$i]['values'] = array();
	foreach($fields_values as $field_value)
	{
		$field_value = NdkCfImporter::array_key_prefix_suffix($field_value, 'NdkCfValues_');
		$export_group['fields'][$i]['values'][$j] = $field_value;
		$export_group['fields'][$i]['values'][$j]["files"] = NdkCfImporter::getFiles($field_value["NdkCfValues_id_ndk_customization_field_value"]);
	 	$sql = 'SELECT * FROM '._DB_PREFIX_.'ndk_customization_field_specific_price WHERE id_ndk_customization_field_value ='.(int)$field_value["NdkCfValues_id_ndk_customization_field_value"];
		$specifiques_prices = Db::getInstance()->executeS($sql);

		$k = 0;
		$export_group['fields'][$i]['values'][$j]['specific_price'] = array();
		foreach($specifiques_prices as $specific_price)
		{
			$specific_price = NdkCfImporter::array_key_prefix_suffix($specific_price, 'NdkCfSpecificPrice_');
			//var_dump($specific_price);
			$export_group['fields'][$i]['values'][$j]['specific_price'][$k] = $specific_price;
			$k++;
		}
		$j++;
	}

	$i++;
}

$encode_json = json_encode($export_group);
header('Content-Type: application/json');
echo $encode_json;








