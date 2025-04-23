<?php
/**
 *  Tous droits réservés NDKDESIGN
 *
 *  @author    Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*/

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/ndk_advanced_custom_fields.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCf.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCfValues.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCfRecipients.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCfSpecificPrice.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkProdCreator.php';

if (sizeof(Tools::getValue('ndkcsfield')) < 1) {
    $return['id_product'] = (int)Tools::getValue('id_product');
    $return['id_cart'] = (int)Context::getContext()->cart->id;
    $return['id_customization'] = 0;
    print(Tools::jsonEncode($return));
    die();
}
$json_datas = array();
$json_datas['ndkcf'] = array();
$ndkPc = new ndkProdCreator();
$module = new ndk_advanced_custom_fields();
$return = array();
$context = Context::getContext();
$default_currency = new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT'));
$user_currency = $context->currency;

$disabe_product_price = false;
$ndkcf_itself = false;

$languages = Language::getLanguages();
$id_lang = Context::getContext()->language->id;
$product = new Product((int)Tools::getValue('id_product'), (int)$id_lang);
$json_datas['id_product_original'] = $product->id;
$json_datas['reference_original'] = $product->reference;
$wholesale_price = $product->wholesale_price;
$real_pprice = $product->base_price;
$empty_form = true;
$is_recipient = false;
$newWeight = 0;
$packitemlist = array();
/*$cookieRealPrice = new Cookie('ndkRealPrice_'.(int)Tools::getValue('id_product'));
$cookieRealPrice->price = $real_pprice;
if (isset($cookieRealPrice)) {
    if (isset($cookieRealPrice->price)){
        $real_pprice = $cookieRealPrice->price;
    }
    else {
       $cookieRealPrice->price = $real_pprice;
    }
}*/

//$product->customizable = 1;
//$product->price = $real_pprice;
//$product->setFieldsToUpdate(array('customizable' => 1));
//$product->update();
Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'product` SET customizable = 1 WHERE id_product = '.(int)$product->id);
Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'product_shop` SET customizable = 1 WHERE id_product = '.(int)$product->id);
//Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'product` SET minimal_quantity = 0 WHERE id_product = '.(int)$product->id);
//Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'product_shop` SET minimal_quantity = 0 WHERE id_product = '.(int)$product->id);

if ((int)Tools::getValue('old_id_customization') > 0) {
    $customisation = new Customization((int)Tools::getValue('old_id_customization'));
    $customProd = new Product((int)$customisation->id_product);
    //var_dump($customisation);
        
        
    if ($customProd->id != Tools::getValue('id_product')) {
        
        //$context->cart->updateQty((int)Tools::getValue('qty'), (int)$customProd->id, 0, (int)Tools::getValue('old_id_customization'), 'down');
        
        if ((int)Tools::getValue('ndkcf_id_combination') > 0) {
            $combNames = $product->getAttributesResume($id_lang);
            foreach ($combNames as $row) {
                if ($row['id_product_attribute'] == (int)Tools::getValue('ndkcf_id_combination')) {
                    $combName = $row['attribute_designation'];
                }
            }
        } else {
            $combName = false;
        }
        $combName = false;
        
        
        foreach ($languages as $lang) {
            $customProd->name[$lang['id_lang']] = Tools::truncateString($module->customized_text.' '.$product->name[$id_lang].(isset($combName) && $combName != '' ? ' - '.$combName : ''), 125);
            $customProd->link_rewrite[$lang['id_lang']] = Tools::str2url($product->name[$id_lang].(isset($combName) && $combName != '' ? ' - '.$combName : ''));
            $customProd->description_short[$lang['id_lang']] = $module->customized_text.' :'.$product->name[$id_lang].(isset($combName) && $combName != '' ? ' - '.$combName : '');
        }
        $customProd->save();
        
        $newCustomProd = $customProd->id;
        //Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'image` WHERE id_product = '.(int)$newCustomProd);
        //Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'image_shop` WHERE id_product = '.(int)$newCustomProd);
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'pack` WHERE id_product_pack = '.(int)$newCustomProd);
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'ndk_customization_field_configuration` WHERE id_ndk_customization_field_configuration = '.(int)Tools::getValue('old_conf'));
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'ndk_customization_field_configuration_lang` WHERE id_ndk_customization_field_configuration = '.(int)Tools::getValue('old_conf'));
    } else {
        $newCustomProd = NdkCf::createProductCustom($product, (int)Tools::getValue('ndkcf_id_combination'), 0, $module->customized_text);
        //$context->cart->updateQty((int)Tools::getValue('qty'), (int)Tools::getValue('id_product'), (int)Tools::getValue('ndkcf_id_combination'), (int)Tools::getValue('old_id_customization'), 'down');
    }
    $context->cart->updateQty((int)$customisation->quantity, (int)$customisation->id_product, (int)$customisation->id_product_attribute, (int)$customisation->id, 'down');
    $customisation->delete();
} else {
    //$newCustomProd = NdkCf::createProductCustom($product, (int)Tools::getValue('ndkcf_id_combination'), 0, $module->customized_text);
    $newCustomProd = $product->id;
}


$id_address = (int)Context::getContext()->cart->id_address_invoice;
$address = Address::initialize($id_address, true);
$tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)Tools::getValue('id_product'), Context::getContext()));
$product_tax_calculator = $tax_manager->getTaxCalculator();
$usetax = Product::$_taxCalculationMethod == PS_TAX_INC;




if (Tools::getValue('id_product') && sizeof(Tools::getValue('ndkcsfield')) > 0) {
    // If cart has not been saved, we need to do it so that customization fields can have an id_cart
    // We check that the cookie exists first to avoid ghost carts
   
    if (!$context->cart->id) {
        $context->cart->add();
        $context->cookie->id_cart = (int)$context->cart->id;
    }

    // @Damien create new product each time in order to keep good customizations order
    $newCustomProd = NdkCf::createProductCustom(
        $product,
        (int)Tools::getValue('ndkcf_id_combination'),
        0, $module->customized_text
    );
    
    // additional values from IWGTIImport
    $additional_customizations = [];
    $additional_infos = unserialize(Configuration::get('iwgtiimport_additional_info'));
    if ($additional_infos) {
        foreach($additional_infos as $ai) {
            if ((int)$ai['id_product'] == (int)Tools::getValue('id_product')) {
                $additional_customizations['config'][] = $ai;
            }
        }
        // @Damien sorting by position
        uasort($additional_customizations['config'], function ($item1, $item2) {
            return $item1['position'] <=> $item2['position'];
        });
        foreach (Tools::getValue('ndkcsfield') as $field => $value) {
            $additional_customizations['values'][$field] = $value;
        }
        if (isset($additional_customizations['config'])) {
            $default_lang = Configuration::get('PS_LANG_DEFAULT');
            foreach($additional_customizations['config'] as $ac) {
                $sql = 'SELECT fl.id_ndk_customization_field FROM '._DB_PREFIX_.'ndk_customization_field_lang fl
                INNER JOIN '._DB_PREFIX_.'ndk_customization_field f on f.id_ndk_customization_field = fl.id_ndk_customization_field
                WHERE fl.admin_name = "' . pSQL($ac['field_reference']) . '" AND fl.id_lang = ' . $default_lang . ' AND f.products = "'. $ac['id_product'] . '"';
                $id_field_reference = (int)Db::getInstance()->getValue($sql);
                if ($id_field_reference && isset($additional_customizations['values'][$id_field_reference])) {
                    $v = $additional_customizations['values'][$id_field_reference];
                    $sql = 'SELECT vl.id_ndk_customization_field_value FROM '._DB_PREFIX_.'ndk_customization_field_value_lang vl
                    INNER JOIN '._DB_PREFIX_.'ndk_customization_field_value v ON vl.id_ndk_customization_field_value = v.id_ndk_customization_field_value
                    WHERE vl.value = "' . pSQL($v) . '" AND vl.id_lang = ' . $id_lang . ' AND v.id_ndk_customization_field = ' . $id_field_reference;
                    $id_reference = (int)Db::getInstance()->getValue($sql);
                    if ($id_reference) {
                        $key_values = [];
                        $keys = [];
                        $key_column = $ac['key_column'];
                        $vertical_mode = $key_column && strpos($key_column, '.');
                        if ($key_column) {
                            if ($vertical_mode == false) {
                                $keys = explode(',', $key_column);
                            } else {
                                // les clefs sont dans le configurateur NDK
                                $expl_prefix = explode('.', $key_column);
                                $sheet = $expl_prefix[0].'.%';
                                $sql = 'SELECT fl.admin_name, fl.complementary_name FROM '._DB_PREFIX_.'ndk_customization_field_lang fl
                                INNER JOIN '._DB_PREFIX_.'ndk_customization_field f on f.id_ndk_customization_field = fl.id_ndk_customization_field AND f.products = "' . $ac['id_product'] .'"
                                WHERE fl.admin_name LIKE "' . pSQL($sheet) . '" AND fl.id_lang = ' . $default_lang;
//                                file_put_contents(_PS_ROOT_DIR_.'/temp.log', 'sql:'.$sql."\n", FILE_APPEND);
                                if ($rows = Db::getInstance()->getRow($sql)) {
                                    $adname = $rows['admin_name'];
                                    $coname = $rows['complementary_name'];
                                    $expl_adname = explode('.', $adname);
                                    $keys[] = $expl_adname[1];
                                    $expl_coname = explode(' ', $coname);
                                    $keys = array_merge($keys, $expl_coname);
                                }
//                                file_put_contents(_PS_ROOT_DIR_.'/temp.log', 'keys:'.var_export($keys, true)."\n", FILE_APPEND);
                            }

                            foreach($keys as $key) {
                                $key = trim($key);
                                if ($key) {
                                    $pkey = strpos($key, '#');
                                    if ($pkey !== false) {
                                        $key = str_replace('#','',$key);
                                        if (isset($additional_customizations['additional_values'][$key]) && isset($additional_customizations['additional_values'][$key]['value'])) {
                                            $key_values[] = $additional_customizations['additional_values'][$key]['value'];
                                        }
                                    } else {
                                        $key_search = str_replace('code_', '.', $key);
                                        // id from admin_name
                                        $id_field_key = (int)Db::getInstance()->getValue('SELECT fl.id_ndk_customization_field FROM '._DB_PREFIX_.'ndk_customization_field_lang fl
                                        INNER JOIN '._DB_PREFIX_.'ndk_customization_field f on f.id_ndk_customization_field = fl.id_ndk_customization_field AND f.products = "' . $ac['id_product'] .'"
                                        WHERE fl.admin_name LIKE "%' . pSQL($key_search) . '" AND fl.id_lang = ' . $default_lang);
//                                        file_put_contents(_PS_ROOT_DIR_.'/temp.log', 'id_field_key:'.var_export($id_field_key, true)."\n", FILE_APPEND);
                                        if ($id_field_key && isset($additional_customizations['values'][$id_field_key])) {
                                            $v = $additional_customizations['values'][$id_field_key];
                                            $id_key = (int)Db::getInstance()->getValue('SELECT vl.id_ndk_customization_field_value FROM '._DB_PREFIX_.'ndk_customization_field_value_lang vl 
                                            INNER JOIN '._DB_PREFIX_.'ndk_customization_field_value v ON v.id_ndk_customization_field_value = vl.id_ndk_customization_field_value
                                            WHERE v.id_ndk_customization_field = '.$id_field_key.' AND vl.value = "' . pSQL($v) . '" AND vl.id_lang = ' . $id_lang);
//                                            file_put_contents(_PS_ROOT_DIR_.'/temp.log', 'id_key:'.var_export($id_key, true)."\n", FILE_APPEND);
                                            if ($id_key) {
                                                // field reference
                                                $field_reference = Db::getInstance()->getValue('SELECT reference FROM '._DB_PREFIX_.'ndk_customization_field_value WHERE id_ndk_customization_field_value = ' . $id_key);
//                                                file_put_contents(_PS_ROOT_DIR_.'/temp.log', 'field_reference:'.var_export($field_reference, true)."\n", FILE_APPEND);
                                                $key_values[] = $field_reference;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $key_country = false;
                        if ($ac['value_by_country'] && $context->cart->id_customer) {
                            $q = new DbQuery();
                            $q->select('c.iso_code');
                            $q->from('country', 'c');
                            $q->innerJoin('address', 'a', 'a.id_country = c.id_country');
                            $q->where('a.id_customer = '.(int)$context->cart->id_customer);
                            $key_country = Db::getInstance()->getValue($q);
                        }
                        if (count($keys) == count($key_values)) {
                            $prefix = $ac['prefix'];
                            $sql = new DbQuery();
                            $sql->select('value');
                            $sql->from('ndk_customization_field_additional_info');
                            $sql->where('id_product = ' . (int)$ac['id_product']);
                            $sql->where('key_id = ' . $id_reference);
                            $sql->where('key_prefix = "' . pSQL($prefix). '"');
                            if (count($key_values)) {
                                $separator = $vertical_mode ? ' ' : ',';
                                $sql->where('key_values = "' . pSQL(implode($separator, $key_values)) . '"');
                            }
                            if ($key_country) {
                                $sql->where('key_iso = "' . pSQL($key_country) . '"');
                            }
                            if ($additional_value = Db::getInstance()->getValue($sql)) {
                                if ($prefix == 'iwgtiimportprice') {
                                    $additional_value = str_replace(',', '.', $additional_value);
                                }
                                $additional_customizations['additional_values'][$prefix] = array(
                                    'value' => $additional_value,
                                    'position' => isset($ac['position']) ? (int)$ac['position'] : false,
                                    'display' => isset($ac['display']) ? (int)$ac['display'] > 0 : false,
                                );
                            }
                        }
                    }
                }        
            }
        }
    }
   
    $images = Tools::getValue('image-url');
    //$decoded = base64_decode(str_replace('data:image/png;base64,', '', $image));
    $im = 1;
    $cartImgs = array();
    $cover_id = Image::getCover((int)Tools::getValue('id_product'));
    $baseImage = new Image((int)$cover_id['id_image']);
   
    $cartImgs[0] = _PS_PROD_IMG_DIR_.$baseImage->getImgPath().'.'.$baseImage->image_format;
    foreach ($images as $image) {
        $decoded = mb_convert_encoding(str_replace('data:image/png;base64,', '', $image), "UTF-8", "BASE64");
        $name = time();
        file_put_contents(_PS_UPLOAD_DIR_.'ndkacf_'. $context->cart->id . '-'.$im.'.png', $decoded);
        $cartImgs[$im] = _PS_UPLOAD_DIR_.'ndkacf_'. $context->cart->id . '-'.$im.'.png';
        $im++;
      
        //print('<img src="'.$image.'"/>');
    }
   
    $i = 0;
    $labels_detail = array();
    $labels_image = array();
    $labels_price = array();
    $labels_index = array();
    $labels_comb = array();
    $labels_base = array();
    $labels_preview = array();
    $labels_preview_img = array();
    $labels_custom_reference = array();
   
    $new_desc = array();
    foreach ($languages as $language) {
        $new_desc[$language['id_lang']] = '';
    }
    //$prices_text = $product->name[$id_lang].' : '.Tools::displayPrice(Product::getPriceStatic($product->id, $usetax)).'  ' ."\n" ;
    $prices_text ='';
    foreach ($languages as $language) {
        $labels_detail[$language['id_lang']][0]['name'] = NdkCf::l('Details');
        $labels_price[$language['id_lang']][0]['name'] = Tools::getValue('cusTextTotal');
        $labels_index[$language['id_lang']][0]['name'] = Tools::getValue('cusTextRef');
        $labels_comb[$language['id_lang']][0]['name'] = Tools::getValue('cusTextComb');
        $labels_base[$language['id_lang']][0]['name'] = NdkCf::l('Base product');
        $labels_preview[$language['id_lang']][0]['name'] = Tools::getValue('previewText');
        $labels_preview_img[$language['id_lang']][0]['name'] = NdkCf::l('Preview (image)');
        $labels_custom_reference[$language['id_lang']][0]['name'] = NdkCf::l('reference');
    }
   
    $customizationPrice = 0;
    $ndkcustomvalue = array();
    $ndkPrices = Tools::getValue('prices');
    $recipientDetails = '';
    $accessoryProdQuantity = array();
    $dimensions = array();
    $percent_price = array();
    $surfaceQuantity = array();
    $encountredSurface = array();
    $orientations = array();
    //$ndkFields = NdkCf::getCustomFieldsForCreation(Tools::getValue('id_product'), $product->id_category_default);
    $custom_reference = '';
    foreach (Tools::getValue('ndkcsfield') as $field => $value) {
        if ($field == 'orientation') {
            foreach ($value as $k=>$v) {
                $orientations[$k] = $v;
            }
        }
    }

    foreach (Tools::getValue('ndkcsfield') as $field => $value) {

        /*foreach($ndkFields as $ndkField){
         $field = $ndkField['id_ndk_customization_field'];
         $value = Tools::getValue('ndkcsfield')[$ndkField['id_ndk_customization_field']];*/
        
        /*
            if($field == 'orientation')
                foreach($value as $k=>$v)
                    $orientations[$k] = $v;
        */

        /* Hack for hidden field SAP Number (default value) */
        if (empty($value)) {
            $open_status = (int)Db::getInstance()->getValue('SELECT open_status
                FROM `'._DB_PREFIX_.'ndk_customization_field` cf 
                WHERE cf.`id_ndk_customization_field` = '.(int)$field);
                if ($open_status == 3) {
                $value = '-';
            }
        }
        /* /Hack for hidden field SAP Number (default value)*/

        if (!empty($value) && $value !='') {
            $values = array();
            $empty_form = false;
            //1 on crée les champs
            $labels = array();
            $required = Db::getInstance()->executeS(
                'SELECT cf.`required`
         FROM `'._DB_PREFIX_.'ndk_customization_field`cf 
         WHERE cf.`id_ndk_customization_field` = '.(int)$field
            );
         
            foreach ($languages as $language) {
                $labels[$language['id_lang']] = Db::getInstance()->executeS(
                    'SELECT '.(Configuration::get('NDK_USE_ADMIN_NAME') == 1 ? 'cfl.`admin_name`' : 'cfl.`name`').' as name 
         FROM `'._DB_PREFIX_.'ndk_customization_field_lang`cfl 
         WHERE cfl.`id_ndk_customization_field` = '.(int)$field.' AND cfl.`id_lang` = '.(int)$language['id_lang']
                );
            }
         
            createLabel($languages, 1, (int)$newCustomProd, $labels, ($required ? $required[0]['required'] : 0));
         
            //$product->customizable = 1;
            //$product->update(array('customizable' =>1));
            //Db::getInstance()->update('product', array('customizable' => 1), '`id_product` = '.(int)$product->id, 0, false);
         
            /* on gère les quantités */
            $accessoryQuantity = array();
            $custom_value = '';
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if ($k == 'quantity') {
                        //var_dump($v);
                        foreach ($v as $k2 => $v2) {
                            $values[] = $k2;
                            $accessoryQuantity[$k2] = $v2;
                        }
                    } elseif ($k == 'surface') {
                        //var_dump($v);
                        foreach ($v as $k2 => $v2) {
                            $values[] = $k2;
                            $surfaceQuantity[$k2] = $v2;
                        }
                    } elseif ($k == 'quantityProd') {
                        foreach ($v as $k2 => $v2) {
                            $values[] = $k2;
                            $accessoryProdQuantity[$k2]['quantity'] = $v2;
                        }
                    } elseif ($k == 'accessory_customization') {
                        foreach ($v as $k2 => $v2) {
                            if (empty($v2)) {
                                unset($v[$k2]);
                            }
                        }
                            
                        $custom_value = 'FORMAT|'.sizeof($v).'|';
                        foreach ($v as $k2 => $v2) {
                            if ($v2 != '') {
                                $splited = explode('|', $k2);
                                $attr_name = $splited[4];
                                $number = $splited[3];
                                
                                $custom_value .= 'JUMPLINE|'.$attr_name.'|'.$number.'|'.$v2.'|'.$attr_name.'|';
                            }
                        }
                        //$values[$field] = $custom_value;
                        $values[] = $custom_value;
                    } elseif ($k == 'checkbox') {
                        //var_dump($v);
                        foreach ($v as $k2 => $v2) {
                            $values[] = $v2;
                            $accessoryQuantity[$k2] = 1;
                        }
                    } elseif ($k == 'width') {
                        $dimensions[$field] = $value;
                        $values[] = $field;
                    } elseif ($k == 'recipient') {
                        $is_recipient = false;
                        $imp = 1;
                        $imploded = '';
                        $recipientInfos = $v;
                        $recipientField = new NdkCf((int)$field, $id_lang);
                        $recipientInfos['availability'] = $recipientField->validity;
                        $recipientInfos['title'] = $recipientField->notice;
                        $recipientInfos['id_ndk_customization_field'] = (int)$field;
                        $ndk = new ndk_advanced_custom_fields();
                        foreach ($v as $k2 => $v2) {
                            if ($k2 == 'send_mail') {
                                if ($v2 == 1) {
                                    $v2 = $ndk->l('yes');
                                } else {
                                    $v2 = $ndk->l('no');
                                }
                            }
                    
                            if ($k2 == 'email' && $v2 !='') {
                                $is_recipient = true;
                            }
                    
                        
                            $imploded .= '<strong>'.$ndk->l($k2).' </strong>'.$v2.($imp < sizeof($v) ? ' </br> ': '');
                            $imp++;
                        }
                        if ($is_recipient) {
                            $values[] = $imploded;
                        }
                    }
                }
            } else {
                //var_dump($value);
                $values[]= $value;
            }
         
            //var_dump($values);
            //on demarra la boucle
            /*if(!$is_recipient)
            $recipientDetails .= $labels[$language['id_lang']][0]['name'].' : ';*/
         
            foreach ($values as $value) {
                if (count($accessoryProdQuantity) > 0 && isset($accessoryProdQuantity[$value])) {
                    //on ajoute les accessoires produits
                    $line = '';
                    $incart = 0;
                    //$newWeight = 0;
                    $pack_available_quantity = 0;
                    $last_quantity_encountred = 999999999999;
                                
                    foreach ($accessoryProdQuantity as $key => $v) {
                        if ($v['quantity'] > 0 && $key == $value) {
                            $id_value = explode('|', $key)[0];
                            $id_product = explode('|', $key)[1];
                            $id_product_attribute = explode('|', $key)[2];
                            if ((int)$id_product == (int)Tools::getValue('id_product')) {
                                $disabe_product_price = true;
                                $ndkcf_itself = true;
                            }
                                            
                            $maxP = $v['quantity'];
                            $prodItem = NdkCf::getProductInfos((int)$id_product, (int)$id_product_attribute);
                            $prodItem = $prodItem[0];
                                                
                            $sql_prices =
                                                'SELECT f.price as fieldPrice, f.show_price, v.price as valuePrice, v.reference FROM `'._DB_PREFIX_.'ndk_customization_field_value` v 
                                                LEFT JOIN `'._DB_PREFIX_.'ndk_customization_field` f ON f.id_ndk_customization_field = v.id_ndk_customization_field 
                                                WHERE (v.price > 0 OR f.price > 0) AND v.id_ndk_customization_field_value = '.(int)$id_value;
                                                
                            $itemPrices = Db::getInstance()->getRow($sql_prices);
                                            
                            $show_price = Db::getInstance()->getValue(
                                'SELECT f.show_price FROM `'._DB_PREFIX_.'ndk_customization_field_value` v 
                                                LEFT JOIN `'._DB_PREFIX_.'ndk_customization_field` f ON f.id_ndk_customization_field = v.id_ndk_customization_field 
                                                WHERE v.id_ndk_customization_field_value = '.(int)$id_value
                            );
                                            
                            if (!$context) {
                                $context = Context::getContext();
                            }
                                            
                            //$context->cart->updateQty((int)$v['quantity'], (int)$id_product, (int)$id_product_attribute, null, 'up');
                            if ((int)$id_product_attribute == 0) {
                                $id_product_attribute = null;
                            }
                                                
                            $item_quantity = (int)$v['quantity'];
                            if ((int)Tools::getValue('totalprodquantity-'.(int)$id_value.'-'.(int)$id_product) > 0) {
                                $item_quantity = (int)Tools::getValue('totalprodquantity-'.(int)$id_value.'-'.(int)$id_product);
                            }
                                                
                            $item_price =  Product::getPriceStatic((int)$id_product, $usetax, $id_product_attribute, 6, null, false, true, (int)$item_quantity, false, (int)$context->customer->id, (int)$context->cart->id);
                                                
                                            
                            if ($itemPrices) {
                                if ($itemPrices['reference'] != '') {
                                    $custom_reference.='-'.str_replace('[:id_product]', (int)$newCustomProd, $itemPrices['reference']);
                                }
                                                    
                                                    
                                if ($itemPrices['valuePrice'] > 0) {
                                    $item_price = $itemPrices['valuePrice'];
                                    $dontUseTax = false;
                                } elseif ($itemPrices['fieldPrice'] > 0) {
                                    $item_price = $itemPrices['fieldPrice'];
                                    $dontUseTax = false;
                                } else {
                                    $item_price = $prodItem['orderprice'];
                                    //$item_price = $prodItem['price'];
                                    $dontUseTax = true;
                                }
                                                
                                $id_address = (int)Context::getContext()->cart->id_address_invoice;
                                $address = Address::initialize($id_address, true);
                                $tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)$product->id, Context::getContext()));
                                $product_tax_calculator = $tax_manager->getTaxCalculator();
                                $usetax = Product::$_taxCalculationMethod == PS_TAX_INC;
                                                
                                                
                                                
                                                
                                if ($usetax && !$dontUseTax) {
                                    $item_price = $product_tax_calculator->addTaxes($item_price);
                                }
                            }
                                            
                            if ($id_product_attribute > 0) {
                                $p = new Product((int)$id_product);
                                $accessorycombNames = $p->getAttributesResume(Context::getContext()->language->id);
                                foreach ($accessorycombNames as $comb) {
                                    //var_dump($comb);
                                    if ($comb['id_product_attribute'] == $id_product_attribute) {
                                        $accessorycombName = $comb['attribute_designation'];
                                    }
                                }
                            }
                            $item_price = Tools::convertPriceFull($item_price, $user_currency, $default_currency, 6);
                                            
                            if ($item_price > 0 && (int)$show_price != 0) {
                                $price_details = ' = '.Tools::displayPrice(Tools::convertPriceFull((float)($item_price*$maxP), $default_currency, $user_currency, 6)).' ';
                            } else {
                                $price_details = '';
                            }
                                            
                            $tax_name = new TaxRulesGroup(Product::getIdTaxRulesGroupByIdProduct((int)$id_product, Context::getContext()));
                                            
                            if ((int)$show_price != 0) {
                                $line .= $maxP.' x '.Tools::displayPrice(Tools::convertPriceFull((float)($item_price), $default_currency, $user_currency, 6)).' - '.$prodItem['name'].' '.($id_product_attribute > 0 ? ' - '.$accessorycombName: '').' '.$price_details.($usetax ? ' ('.$tax_name->name.')' : '').'<br/>';
                            } else {
                                $line .= $maxP.' x  - '.$prodItem['name'].' '.($id_product_attribute > 0 ? ' - '.$accessorycombName: '').'<br/>';
                            }
                                            
                                      
                                       
                            $customizationPrice+= (float)($item_price*$maxP);
                            if (isset($prodItem['attrWeight'])) {
                                if ((float)$prodItem['attrWeight'] > 0) {
                                    $newWeight += $prodItem['attrWeight']*$maxP;
                                } else {
                                    $newWeight += $prodItem['weight']*$maxP;
                                }
                            } else {
                                $newWeight += $prodItem['weight']*$maxP;
                            }
                                            
                            //var_dump($newWeight);
                            $packitemlist[] = array('id_product' => (int)$id_product, 'quantity' => (int)$maxP, 'id_product_attribute' =>(int)$id_product_attribute);
                            //Pack::addItem((int)$newCustomProd, (int)$id_product, (int)$maxP, (int)$id_product_attribute);
                            $wholesale_price += $prodItem['wholesale_price']*$maxP;
                            $incart += $maxP;
                            $prod_available = StockAvailable::getQuantityAvailableByProduct($id_product, $id_product_attribute)/$maxP;
                                            
                            $json_datas['ndkcf'][] = array(
                                          'field' =>  $labels[(int) Context::getContext()->language->id][0]['name'],
                                          'qtty' => (int)$maxP,
                                          'id_product' => (int)$id_product,
                                          'id_product_attribute' => (int)$id_product_attribute,
                                          'price' => (float)$item_price,
                                          'ndkcf_datas' => $itemPrices
                                        );
                            //$context->cart->updateQty((int)$v['quantity'], (int)$id_product, (int)$id_product_attribute, null, 'down');
                                            
                            if ($prod_available < $last_quantity_encountred) {
                                $pack_available_quantity = $prod_available;
                                $last_quantity_encountred = $prod_available;
                            }
                            createLabel($languages, 1, (int)$newCustomProd, $labels, $required[0]['required']);
                            $ndkcustomvalue[]= array('index' => createLabel($languages, 1, (int)$newCustomProd, $labels, $required[0]['required']), 'value' => $line);
                        }
                    }
                } elseif (count($dimensions) > 0 && isset($dimensions[$value])) {
                    if (
                        isset($dimensions[$value]['width']) && isset($dimensions[$value]['height'])
                        && $dimensions[$value]['width'] !='' &&  $dimensions[$value]['width'] !=' '
                        && $dimensions[$value]['height'] !='' &&  $dimensions[$value]['height'] !=' '
                    ) {
                        $item_price = NdkCf::getDimensionPrice((int)$field, $dimensions[$value]['width'], $dimensions[$value]['height']);
                        $id_address = (int)Context::getContext()->cart->id_address_invoice;
                        $address = Address::initialize($id_address, true);
                        $tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)$product->id, Context::getContext()));
                        $product_tax_calculator = $tax_manager->getTaxCalculator();
                        $usetax = Product::$_taxCalculationMethod == PS_TAX_INC;
                        if ($usetax) {
                            $item_price = $product_tax_calculator->addTaxes($item_price);
                        }
                        
                        $item_price = Tools::convertPriceFull($item_price, $user_currency, $default_currency, 6);
                        
                        $customizationPrice+= $item_price;
                        $price_detail = ' '.Tools::displayPrice(Tools::convertPriceFull($item_price, $default_currency, $user_currency, 6)).' ';
                        
                        //var_dump($item_price);
                        $line = $dimensions[$value]['width'].'x'.$dimensions[$value]['height'].$price_detail;
                        createLabel($languages, 1, (int)$newCustomProd, $labels, $required[0]['required']);
                        $ndkcustomvalue[]= array('index' => createLabel($languages, 1, (int)$newCustomProd, $labels, $required[0]['required']), 'value' => $line);
                    }
                } elseif ((count($surfaceQuantity) > 0 && isset($surfaceQuantity[$value])) || in_array($field, $encountredSurface)) {
                    //var_dump($field);
                        
                    if (!in_array($field, $encountredSurface)) {
                        //var_dump($surfaceQuantity);
                        $prices = NdkCf::getCustomizationPrice($field, $value, Tools::getValue('id_product'));
                        $item_price = $prices[0]['price'];
                            
                        $line = '';
                        foreach ($surfaceQuantity as $key=>$value) {
                            $valObj = new NdkCfValues((int)$key, $id_lang);
                            $item_price = $item_price*(float)$value;
                            $line.= $valObj->value.'  '.$value.' ; ';
                            unset($surfaceQuantity[$key]);
                        }
    
                        $item_price = Tools::convertPriceFull($item_price, $user_currency, $default_currency, 6);
                        $customizationPrice+= $item_price;
                        $price_detail = ' '.Tools::displayPrice(Tools::convertPriceFull($item_price, $default_currency, $user_currency, 6)).' ';
                                
                        //var_dump($item_price);
                                
                        createLabel($languages, 1, (int)$newCustomProd, $labels, $required[0]['required']);
                        $ndkcustomvalue[]= array('index' => createLabel($languages, 1, (int)$newCustomProd, $labels, $required[0]['required']), 'value' => $line.$price_detail);
                        $encountredSurface[] = $field;
                    }
                } else {
                    //2 on renseigne les personnalisations
                    //var_dump($value);
                       
                       
                    $formated_value = false;
                    $my_multiplicator = 1;
                    $value = $value;
                    //var_dump(substr($value, 0, 7));
                    if (Tools::substr($value, 0, 7) == 'FORMAT|') {
                        $valable_string = '';
                        $lines = explode('JUMPLINE', $value);
                        $formated_value = '';
                        $value = '';
                        $l = 0;
                        foreach ($lines as $line) {
                            if ($l == 0) {
                                $my_multiplicator = (int)str_replace('FORMAT|', '', $line);
                            } else {
                                $vars = explode('|', $line);
                                $valable_string .= explode('[', str_replace(' ', '', $vars[3]))[0];
                                $value = $vars[3];
                                
                                $formated_value .= '<p class="cus_sub col-xs-6 col-md-3"><span class="cus_sub_container"><span class="cust_title">'.$vars[1].' '.$vars[2].'</span>'." \n".$vars[3].'</span></p>';
                            }
                            
                            $l++;
                        }
                    }
                       
                    //var_dump($valable_string);
                    //var_dump($my_multiplicator);
                       
                    $prices = NdkCf::getCustomizationPrice($field, $value, Tools::getValue('id_product'));
                       
                    //var_dump($prices);
                    if ($i+1 < sizeof(Tools::getValue('ndkcsfield'))) {
                        $suffix = ' - ' ."\n";
                        $virgule = '<br />';
                    } else {
                        $suffix = ' ';
                        $virgule = '';
                    }
                       
                    $cprice = 0;
                    $priced = false;
                       
                    //$price = $ndkPrices;
                    //$cprice = $ndkPrices[$field];
                    $percent = false;
                    for ($j = 0; $j < sizeof($prices); $j++) {
                        $link_multiplicator = 1;
                         
                         
                         
                        if ((int)$prices[$j]['quantity_link'] > 0) {
                            $quantity_link = (int)$prices[$j]['quantity_link'];
                            if (Tools::getValue('ndkcsfield')[$quantity_link]) {
                                $link_multiplicator = 0;
                                foreach (Tools::getValue('ndkcsfield')[$quantity_link]['quantityProd'] as $key => $qtty) {
                                    $link_multiplicator += $qtty;
                                }
                            }
                        }
                         
                        //var_dump($prices[$j]['type']);
                        //var_dump($value);
                         
                                    
                        if (empty($value) || $value == '') {
                            if (!$priced) {
                                $price = $prices[$j];
                                $cprice = 0;
                                $priced = true;
                            }
                        } elseif ($prices[$j]['valuePrice'] && $prices[$j]['valuePrice'] > 0 && $prices[$j]['value'] && ($prices[$j]['value'] == $value)) {
                            if (!$priced) {
                                if (!isset($accessoryQuantity[$value]) || (int)$accessoryQuantity[$value] == 0) {
                                    $accessoryQuantity[$value] = 1;
                                }
                                $price = $prices[$j];
                                if ($prices[$j]['valuePrice'] > 0) {
                                    $cprice = $prices[$j]['valuePrice'];
                                    //on recupère les discount pour la valeur
                                    $specificPrices = NdkCfSpecificPrice::getSpecificPrices((int)$prices[$j]['id_ndk_customization_field'], $prices[$j]['id_ndk_customization_field_value'], ((int)Tools::getValue('qty')*$accessoryQuantity[$value])*$link_multiplicator, true, Tools::getValue('id_product'));
                                    if ($specificPrices && sizeof($specificPrices) > 0) {
                                        if ((float)$specificPrices[0]['reduction'] > 0) {
                                            if ($specificPrices[0]['reduction_type'] == 'amount') {
                                                $cprice = $cprice - (float)$specificPrices[0]['reduction'];
                                            } else {
                                                $cprice = $cprice - ($cprice*((float)$specificPrices[0]['reduction']/100));
                                            }
                                        }
                                    }
                                    if ($price['reference'] != '') {
                                        $custom_reference.='-'.str_replace('[:id_product]', (int)$newCustomProd, $price['reference']);
                                    }
                                } elseif ($prices[$j]['price'] > 0) {
                                    $cprice = $prices[$j]['price'];
                                    //on recupère les discount pour le champs
                                    $specificPrices = NdkCfSpecificPrice::getSpecificPrices((int)$prices[$j]['id_ndk_customization_field'], 0, 0, true, Tools::getValue('id_product'));
                                    if ($specificPrices && sizeof($specificPrices) > 0) {
                                        if ((float)$specificPrices[0]['reduction'] > 0) {
                                            if ($specificPrices[0]['reduction_type'] == 'amount') {
                                                $cprice = $cprice - (float)$specificPrices[0]['reduction'];
                                            } else {
                                                $cprice = $cprice - ($cprice*((float)$specificPrices[0]['reduction']/100));
                                            }
                                        }
                                    }
                                } else {
                                    $cprice = 0;
                                    $priced = true;
                                
                                    if ($price['reference'] != '') {
                                        $custom_reference.='-'.str_replace('[:id_product]', (int)$newCustomProd, $price['reference']);
                                    }
                                }
                            }
                        } elseif ($prices[$j]['valuePrice'] <= 0 && $prices[$j]['price_per_caracter'] <= 0) {
                            if (!$priced) {
                                $price = $prices[$j];
                                if ($prices[$j]['price'] > 0) {
                                    $cprice = $prices[$j]['price'];
                                } else {
                                    $cprice = 0;
                                }
                                $priced = true;
                               
                                if ($price['reference'] != '') {
                                    $custom_reference.='-'.str_replace('[:id_product]', (int)$newCustomProd, $price['reference']);
                                }
                            }
                        } else {
                            if (isset($prices[$j]['type']) && ($prices[$j]['type'] == 0 || $prices[$j]['type'] == 13 || $prices[$j]['type'] == 14 || $prices[$j]['type'] == 6)) {
                                if (!isset($valable_string)) {
                                    $value = str_replace('¶', '', $value);
                                    $valable_string = explode('[', str_replace(array("\r\n", "\n", "\r", " "), '', $value));
                                }
                            }
                                
                            if (!$prices[$j]['valuePrice']) {
                                if (isset($valable_string)) {
                                    if (!$priced && $valable_string[0] != '') {
                                        $price = $prices[$j];
                                        if ($prices[$j]['price_per_caracter'] > 0) {
                                            $my_multiplicator = 1;
                                            $valable_string = explode('[', str_replace(array("\r\n", "\n", "\r", " "), '', $value));
                                            $cprice = $prices[$j]['price_per_caracter']*(mb_strlen(trim($valable_string[0])));
                                        } else {
                                            $cprice = $prices[$j]['price'];
                                        }
                                        $priced = true;
                                    }
                                }
                            }
                        }
                          
                          
                        if ($prices[$j]['price_type'] == 'percent') {
                            $percent_price[$prices[$j]['id_ndk_customization_field']] = $cprice;
                            $percent = true;
                        }
                    }
                       
                       
                    if (count($accessoryQuantity) == 0) {
                        $accessoryQuantity[$value] = 0;
                    //$value ='';
                    } else {
                        $cprice = $cprice*$accessoryQuantity[$value];
                        if ($accessoryQuantity[$value] == 0) {
                            $value ='';
                        }
                    }
                       
                       
                        
                        
                    $price_detail = '';
                    if (isset($prices[$j])) {
                        if ($prices[$j]['price_type'] == 'one_time') {
                            $cprice = $cprice/(int)Tools::getValue('qty');
                        }
                    }
                      
                      
                    $cprice = $cprice*$my_multiplicator*$link_multiplicator;
                    if ($cprice > 0) {
                        if ($percent) {
                            $price_detail = ' +'.$cprice.'% ';
                        } else {
                            $price_detail = ' '.Tools::displayPrice(Tools::convertPriceFull($cprice, $default_currency, $user_currency, 6)).' ';
                        }
                            
                        $show_price = Db::getInstance()->getValue(
                            'SELECT show_price FROM '._DB_PREFIX_.'ndk_customization_field 
                                                WHERE id_ndk_customization_field = '.(int)$field
                        );
                        if ((int)$show_price != 0) {
                            $prices_text .= $labels[$id_lang][0]['name'].' : +'.Tools::displayPrice(Tools::convertPriceFull($cprice, $default_currency, $user_currency, 6)).$suffix;
                        } else {
                            $price_detail = '';
                        }
                    }
                        
                       
                    if (!$percent) {
                        $customizationPrice += (float)($cprice);
                    }
                            
                          
                          
                    //$value_image = _PS_IMG_DIR_.'scenes/'.'ndkcf/'.$prices[$j]['id_ndk_customization_field_value'].'.jpg';
                    $value_image_output = '';
                    /*if(file_exists($value_image))
                       $value_image_output = '<img src="/img/scenes/'.'ndkcf/'.$prices[$j]['id_ndk_customization_field_value'].'.jpg'.'"/>';*/
                          
                    if (!empty($value) && $value !='') {
                        $orientation = '';
                        if (isset($orientations[$field])) {
                            $orientation = ' ['.$orientations[$field].']';
                        }
                                
                        $ndkcustomvalue[]= array('index' => createLabel($languages, 1, (int)$newCustomProd, $labels, $required[0]['required']), 'value' => ($formated_value ? $formated_value : $value). ($accessoryQuantity[$value] > 0 ? ' x'.$accessoryQuantity[$value] : '').$orientation.' '.$price_detail.' '.$value_image_output, 'field' => $field);
                             
                        $json_datas['ndkcf'][] = array(
                           'field' => $labels[(int) Context::getContext()->language->id][0]['name'],
                           'value' => ($formated_value ? $formated_value : $value),
                           'qtty' => (int)$accessoryQuantity[$value],
                           'id_product' => null,
                           'id_product_attribute' => null,
                           'price' => (float)$cprice,
                           
                         );
                        //var_dump($value);
                        if (!$is_recipient) {
                            $recipientDetails .= ($accessoryQuantity[$value] > 0 ? $accessoryQuantity[$value].'x ' : '').($formated_value ? $formated_value : $value). $orientation.' '.$virgule;
                        }
                             
                        createLabel($languages, 1, (int)$newCustomProd, $labels, $required[0]['required']);
                        //addTextFieldToProduct(Tools::getValue('id_product'), $index_field, 1, $value);
                             
                        foreach ($languages as $language) {
                            if (!empty($value)) {
                                $new_desc[$language['id_lang']] .= $labels[$language['id_lang']][0]['name'] .' : '.(isset($formated_value) ? $formated_value : $value). ($accessoryQuantity[$value] > 0 ? ' x'.$accessoryQuantity[$value] : '').'<br/>';
                            }
                        }
                    }
                }//else
            }
                        
            $i++;
        }
    }
   
    $context = Context::getContext();
    $cur_cart = $context->cart;
    $id_currency = (int)Configuration::get('PS_CURRENCY_DEFAULT');
             
    $id_country = (int)$context->country->id;
    $id_state = 0;
    $zipcode = 0;
    $id_address = 0;
    $id_customer = 0;
    $id_group = null;
    if (sizeof($percent_price) >0) {
        $tempPrice = 0;
        $customizationPricePercent = 0;
        //get product price
        $myProductPrice = Product::getPriceStatic((int)Tools::getValue('id_product'), false, (int)Tools::getValue('ndkcf_id_combination'), 6, null, false, false, 1, false, (int)$context->customer->id, (int)$context->cart->id);
        $myProductPrice -= (float)$product->ecotax;
        $myProductPrice =Tools::convertPriceFull($myProductPrice, $user_currency, $default_currency, 6);
        
        foreach ($percent_price as $key=>$value) {
            if ($value > 0) {
                $valueHT = $value;
                $multiplicatorHT = $valueHT/100;
                $totalPrice = $myProductPrice + $customizationPrice + $customizationPricePercent;
                $toAdd = $totalPrice*$multiplicatorHT;
                $customizationPricePercent += $toAdd;
            }
        }
        $customizationPrice += $customizationPricePercent;
    }
    /*
       if(sizeof($percent_price) >0)
       {
             $tempPrice = 0;
             //get product price
            $myProductPrice = Product::getPriceStatic((int)Tools::getValue('id_product'), false,(int)Tools::getValue('ndkcf_id_combination'), 6, null, false, false, 1, false, (int)$context->customer->id, (int)$context->cart->id);
            $myProductPrice -= (float)$product->ecotax;
            $tempPrice +=Tools::convertPriceFull($myProductPrice, $user_currency, $default_currency, 6);

            foreach($percent_price as $key=>$value){
                if($value > 0)
                {
                    $valueHT = $product_tax_calculator->removeTaxes($value);
                    $multiplicatorHT = $valueHT/100;
                    $customizationPrice += $product_tax_calculator->addTaxes($tempPrice*$multiplicatorHT);
                    $tempPrice += $tempPrice*$multiplicatorHT;

                }

            }
       }
    */

    if (isset($additional_customizations['additional_values']) && isset($additional_customizations['additional_values']['iwgtiimportprice'])) {
        $customizationPrice = (float)$additional_customizations['additional_values']['iwgtiimportprice']['value'];
    }

    if ($customizationPrice > 0 || sizeof($accessoryProdQuantity) > 0) {
        $id_address = (int)Context::getContext()->cart->id_address_invoice;
        $address = Address::initialize($id_address, true);
        $tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)$product->id, Context::getContext()));
        $product_tax_calculator = $tax_manager->getTaxCalculator();
        $usetax = Product::$_taxCalculationMethod == PS_TAX_INC;
      
        if ($usetax) {
            $newprice = $product_tax_calculator->removeTaxes($customizationPrice);
        } else {
            $newprice = $customizationPrice;
        }
      
        if (Configuration::get('NDK_ADD_PRODUCT_PRICE') == 1 && !$disabe_product_price) {
            //$myProductPrice = Product::getPriceStatic((int)Tools::getValue('id_product'), false,(int)Tools::getValue('ndkcf_id_combination'), 6, null, false, false, 1, false, (int)$context->customer->id, (int)$context->cart->id);
            $myProductPrice = NdkCf::getBrutPrice((int)Tools::getValue('id_product'), (int)Tools::getValue('ndkcf_id_combination'));
            //$myProductPrice -= (float)$product->ecotax;
            $newprice +=Tools::convertPriceFull($myProductPrice, $user_currency, $default_currency, 6);
        }
      
        $newCustomProdObj = new Product($newCustomProd);
      
        if (sizeof($packitemlist) > 0) {
            $packProdItems = array();
            foreach ($packitemlist as $item) {
                if (!isset($packProdItems[$item['id_product'].'-'.$item['id_product_attribute']])) {
                    $packProdItems[$item['id_product'].'-'.$item['id_product_attribute']] = array();
                }
                if (!isset($packProdItems[$item['id_product'].'-'.$item['id_product_attribute']]['quantity'])) {
                    $packProdItems[$item['id_product'].'-'.$item['id_product_attribute']]['quantity'] = 0;
                }
            
                $packProdItems[$item['id_product'].'-'.$item['id_product_attribute']]['quantity'] += $item['quantity'];
                $packProdItems[$item['id_product'].'-'.$item['id_product_attribute']]['id_product'] = $item['id_product'];
                $packProdItems[$item['id_product'].'-'.$item['id_product_attribute']]['id_product_attribute'] = $item['id_product_attribute'];
            }
            foreach ($packProdItems as $item) {
                Pack::addItem((int)$newCustomProd, (int)$item['id_product'], (int)$item['quantity'], (int)$item['id_product_attribute']);
            }
        
            if ((int)Tools::getValue('ndkcf_id_combination') > 0) {
                $combNames = $product->getAttributesResume($id_lang);
                foreach ($combNames as $row) {
                    if ($row['id_product_attribute'] == (int)Tools::getValue('ndkcf_id_combination')) {
                        $combName = $row['attribute_designation'];
                    }
                }
            } else {
                $combName = false;
            }
        
            foreach ($languages as $lang) {
                $newCustomProdObj->name[$lang['id_lang']] = Tools::truncateString($module->bundle_text.' '.$product->name[$lang['id_lang']].(isset($combName) && $combName != '' ? ' - '.$combName : ''), 125);
            }
        }
      
        // corrige le pb de reduction par groupe on ajoute si necessaire
        if (isset($context->customer->id_group)) {
            $reduction_from_category = GroupReduction::getValueForProduct(Tools::getValue('id_product'), (int)$context->customer->id_group);
        }
        if (isset($reduction_from_category) && $reduction_from_category !== false) {
            $group_reduc = (float)$reduction_from_category;
        } else { // apply group reduction if there is no group reduction for this category
            $group_reduc = Group::getReductionByIdGroup((int)$context->customer->id_default_group);
        }
        if ((float)$group_reduc !=0) {
            $coeff =  1 - (float)$group_reduc / 100;
            $newprice = $newprice / (float)$coeff;
        }
        //
      
      
        $newCustomProdObj->price = number_format($newprice, 6, '.', '');
        $newCustomProdObj->wholesale_price = number_format($wholesale_price, 6, '.', '');
        if ($newWeight > 0) {
            $newCustomProdObj->weight = (float)$product->weight + (float)$newWeight;
        }
        if (Configuration::get('NDK_SPLIT_PACK') == 1) {
            $newCustomProdObj->pack_stock_type = 2;
        } else {
            $newCustomProdObj->pack_stock_type = 1;
        }

        foreach ($languages as $language) {
            $newCustomProdObj->description[$language['id_lang']] = $new_desc[$language['id_lang']];
        }
      
        $qttytoset = (int)StockAvailable::getQuantityAvailableByProduct((int)Tools::getValue('id_product'), (int)Tools::getValue('ndkcf_id_combination'));
      
        $qty_to_check = Tools::getValue('qty', 1);
        $cart_products = $context->cart->getProducts();
      
        if (is_array($cart_products)) {
            foreach ($cart_products as $cart_product) {
                if (Pack::isPack((int)$cart_product['id_product'])) {
                    $packItems = Db::getInstance()->executeS('SELECT id_product_item, id_product_attribute_item, quantity FROM `'._DB_PREFIX_.'pack` where id_product_pack = '.(int)$cart_product['id_product']);
                    foreach ($packItems as $item) {
                        if ((!Tools::getValue('ndkcf_id_combination') || $item['id_product_attribute_item'] == Tools::getValue('ndkcf_id_combination')) &&
                               (Tools::getValue('id_product') && $item['id_product_item'] == Tools::getValue('id_product'))) {
                            $qty_to_check += $item['quantity']*$cart_product['cart_quantity'];
                            //$qty_to_check += Tools::getValue('qty');
                        }
                    }
                }
                     
                if ((!Tools::getValue('ndkcf_id_combination') || $cart_product['id_product_attribute'] == Tools::getValue('ndkcf_id_combination')) &&
                         (Tools::getValue('id_product') && $cart_product['id_product'] == Tools::getValue('id_product'))) {
                    $qty_to_check += $cart_product['cart_quantity'];
                    //$qty_to_check += Tools::getValue('qty');
                }
            }
        }
      
        // Check product quantity availability
        if (Tools::getValue('ndkcf_id_combination') > 0) {
            if (!Product::isAvailableWhenOutOfStock($product->out_of_stock) && !Attribute::checkAttributeQty((int)Tools::getValue('ndkcf_id_combination'), $qty_to_check)) {
                $qttytoset = 0;
            }
        } elseif ($product->hasAttributes()) {
            $minimumQuantity = ($product->out_of_stock == 2) ? !Configuration::get('PS_ORDER_OUT_OF_STOCK') : !$product->out_of_stock;

            if (!Product::isAvailableWhenOutOfStock($product->out_of_stock) && !Attribute::checkAttributeQty((int)Tools::getValue('ndkcf_id_combination'), $qty_to_check)) {
                $qttytoset = 0;
            }
        } elseif (!$product->checkQty($qty_to_check)) {
            $qttytoset = 0;
        }
      
      
      
        $newCustomProdObj->quantity = $qttytoset;
        $newCustomProdObj->out_of_stock = $product->out_of_stock;
        $newCustomProdObj->update();
        $refProduct = $newCustomProdObj->id;
        Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'stock_available` SET `quantity` =  '.$qttytoset.' WHERE id_product = '.(int)$newCustomProdObj->id);
      
        if ((int)Tools::getValue('ndkcf_id_combination') > 0 && Configuration::get('NDK_SHOW_COMBINATION') == 1) {
            $combName = '';
            $combNames = $product->getAttributesResume($id_lang);
            foreach ($combNames as $row) {
                if ($row['id_product_attribute'] == (int)Tools::getValue('ndkcf_id_combination')) {
                    $combName = $row['attribute_designation'];
                }
               
                $combs = explode(',', $combName);
                foreach ($combs as $comb) {
                    $rows = explode(' - ', $comb);
                    $my_labels_comb = array();
                    foreach ($languages as $language) {
                        $my_labels_comb[$language['id_lang']][0]['name'] = $rows[0];
                    }
                    if ($rows[0] != '' && $rows[1] !='') {
                        $my_index_comb = createLabel($languages, 1, $refProduct, $my_labels_comb);
                        addTextFieldToProduct((int)$refProduct, $my_index_comb, 1, $rows[1]);
                    }
                }
            }
        }
      
      
        //forpack
      
        if (Pack::isPack((int)$product->id)) {
            $items = Db::getInstance()->executeS('SELECT id_product_item, id_product_attribute_item, quantity FROM `'._DB_PREFIX_.'pack` where id_product_pack = '.(int)$product->id);
         
            foreach ($items as $item) {
                Pack::addItem((int)$newCustomProdObj->id, (int)$item['id_product_item'], (int)$item['quantity'], (int)$item['id_product_attribute_item']);
            }
        } else {
            if (Configuration::get('NDK_ADD_PRODUCT_PRICE') == 1 && !$disabe_product_price) {
                Pack::addItem((int)$newCustomProdObj->id, (int)$product->id, (int)1, (int)Tools::getValue('ndkcf_id_combination'));
            }
        }
      
        NdkCf::duplicateGroupReductionCache((int)Tools::getValue('id_product'), $newCustomProdObj->id);
    } else {
        $refProduct = (int)Tools::getValue('id_product');
        //$newCustomProdObj = new Product($newCustomProd);
      //$newCustomProdObj->delete();
    }
   
    // IW Additional fields : add customization
    if (isset($additional_customizations['additional_values'])) {
        foreach($additional_customizations['additional_values'] as $field => $value) {
            if (in_array($field, ['iwgtiimportprice', 'iwgtiimportrefproduct'])) { // included in product
                continue;
            }
            $labels = array();
            foreach ($languages as $language) {
                $iso_code = $language['iso_code'];
                $file = _PS_MODULE_DIR_ . 'iwgtiimport' . '/translations/' . $iso_code . '.php';
                $fieldtranslated = $field;
                if (file_exists($file) && include($file)) {
                    if (isset($_MODULE) && is_array($_MODULE)) {
                        // key string
                        $key = '<{iwgtiimport}prestashop>'.'additional_info_trad'.'_';
                        $keytranslate = $key.md5(preg_replace("/\\\*'/", "\'", $field));
                        // translate
                        $fieldtranslated = isset($_MODULE[$keytranslate]) ? $_MODULE[$keytranslate] : $field;
                    }
                }
                $labels[$language['id_lang']][0]['name'] = $fieldtranslated;
            }

            $idxLabel = createLabel($languages, 1, (int)$refProduct, $labels, 0);
            addTextFieldToProduct((int)$refProduct, $idxLabel, 1, $value['value'], $field, $value['display'], $value['position']);
        }
    }   
   
    $details_field = createLabel($languages, 1, $refProduct, $labels_detail);
    $preview_field = createLabel($languages, 1, $refProduct, $labels_preview);
    $preview_field_img = createLabel($languages, 1, $refProduct, $labels_preview_img);
    if (Tools::getValue('is_visual')!= 0) {
        if (Configuration::get('NDK_SHOW_HD_PREVIEW') == 1) {
            addTextFieldToProduct($refProduct, $preview_field, 1, NdkCf::l('No preview required'));
        }
        if (Configuration::get('NDK_SHOW_IMG_PREVIEW') == 1) {
            addTextFieldToProduct($refProduct, $preview_field_img, 1, NdkCf::l('No preview required'));
        }
    }
   
    $customization_price_field = createLabel($languages, 1, $refProduct, $labels_price);
    $link_index = createLabel($languages, 1, $refProduct, $labels_index);
    if ($customizationPrice > 0 || sizeof($accessoryProdQuantity) > 0) {
        //print((int)$newCustomProdObj->id);
      
        //addTextFieldToProduct((int)Tools::getValue('id_product'), $link_index, 1, $newCustomProdObj->reference.' id:'.$newCustomProdObj->id);
        if (Configuration::get('NDK_SHOW_TOTAL_COST') == 1) {
            addTextFieldToProduct($refProduct, $customization_price_field, 1, Tools::displayPrice(Tools::convertPriceFull($customizationPrice, $default_currency, $user_currency, 6)));
        }
        //$myIdCustomization = addTextFieldToProduct($refProduct, $details_field, 1, $prices_text);
      
       
        //compatibilité packs
        if (class_exists('NdkSpack')) {
            $steps = NdkSpack::getStepsForProduct(Tools::getValue('id_product'));
            if ($steps) {
                foreach ($steps as $id_step) {
                    $step = new NdkSpackStep((int)$id_step);
                    $curr_prods = $step->products;
                    $step->products = ($curr_prods !='' ? $curr_prods.','.$refProduct : $refProduct);
                    //$step->products = $curr_prod.','.$refProduct;
                    $step->save();
                }
            }
        }
    }
   
    //var_dump($ndkcustomvalue);
    $newNdkcustomvalue = array();
    $indexed = array();
    $indexedKey = array();
  
    $z = 0;
    foreach ($ndkcustomvalue as $value) {
        if (in_array($value['index'], $indexed)) {
            //$newNdkcustomvalue[ $indexed[$value['index']] ]['index']  = $value['index'];
            $newNdkcustomvalue[ $value['index'] ]['value']  = $newNdkcustomvalue[ $value['index'] ]['value'].'; '.$value['value'];
        } else {
            $newNdkcustomvalue[$value['index']] = $value;
            $z++;
        }
     
        $indexed[] = $value['index'];
        //$indexedKey[$value['index']] = $z;
     //$z++;
    }
  
    //var_dump($newNdkcustomvalue);
  
    Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'customization_field` SET required = 0 WHERE id_product = '.(int)$refProduct);
    $newDesc = '';
    foreach ($newNdkcustomvalue as $val) {
        $ext_position = false;
        $admin_name = false;
        if (isset($val['field'])) {
            $ext_position = (int)Db::getInstance()->getValue('SELECT cf.`position` FROM `'._DB_PREFIX_.'ndk_customization_field` cf WHERE cf.`id_ndk_customization_field` = '.(int)$val['field']);
            $admin_name = Db::getInstance()->getValue('SELECT cfl.`admin_name` FROM `'._DB_PREFIX_.'ndk_customization_field_lang` cfl WHERE cfl.`id_ndk_customization_field` = '.(int)$val['field'].' AND id_lang = '.(int)Configuration::get('PS_LANG_DEFAULT'));
        }
        $myIdCustomization = addTextFieldToProduct($refProduct, $val['index'], 1, $val['value'], $admin_name, (int)$ext_position > 0 ? 1 : 0 , $ext_position);
        $fieldLabel =
        Db::getInstance()->getRow('
         SELECT name FROM `'._DB_PREFIX_.'customization_field_lang` WHERE `id_customization_field` = '.(int)$val['index'].' AND `id_lang`= '.(int)Context::getContext()->language->id);
       
        //var_dump($fieldLabel);
        $newDesc .= '<p><b>'.$fieldLabel['name'].' : </b>'.$val['value'].'</p>';
    }
   
    //on retourne les valeurs
    if ((int)$context->customer->id > 0) {
        $return['id_customer'] = (int)$context->customer->id;
    } else {
        $return['id_customer'] = 0;
    }
      
    $return['id_product'] = (int)$refProduct;
    $return['id_cart'] = (int)$context->cart->id;
    $return['id_customization'] = (int)$myIdCustomization;
    $return['preview_field'] = $preview_field;
    $return['preview_field_img'] = $preview_field_img;
   
    print(Tools::jsonEncode($return));
    //on insere le recipient
    if (isset($recipientInfos)) {
        if ($recipientInfos['firstname'] !='' && $recipientInfos['lastname'] !='') {
            $recipient = new NdkCfRecipients();
            $recipient->id_product = (int)$refProduct;
            $recipient->id_combination = (int)Tools::getValue('ndkcf_id_combination');
            $recipient->id_cart = (int)$context->cart->id;
            $recipient->id_customization = (int)$myIdCustomization;
            $recipient->id_ndk_customization_field = $recipientInfos['id_ndk_customization_field'];
            $recipient->firstname = $recipientInfos['firstname'];
            $recipient->lastname = $recipientInfos['lastname'];
            $recipient->email = $recipientInfos['email'];
            $recipient->message = $recipientInfos['message'];
            $recipient->who_offers = $recipientInfos['who_offers'];
            $recipient->availability = $recipientInfos['availability'];
            $recipient->title = $recipientInfos['title'];
            $recipient->send_mail = $recipientInfos['send_mail'];
            $recipient->details = $recipientDetails;
            $recipient->code = 'WEB'.Tools::strtoupper(Tools::passwdGen(9, 'NO_NUMERIC'));
            $recipient->date = date('Y-m-d H:i:s');
            $recipient->save();
        }
    }
   
    if ($customizationPrice > 0 || sizeof($accessoryProdQuantity) > 0) {
        if (Configuration::get('NDK_KEEP_ORIGINAL_REFERENCE') == 1) {
            if ((int)Tools::getValue('ndkcf_id_combination') > 0) {
                $combination = new Combination((int)Tools::getValue('ndkcf_id_combination'));
                $newCustomProdObj->reference = $combination->reference;
            } else {
                $newCustomProdObj->reference = $product->reference;
            }
        } else {
            if (isset($additional_customizations['additional_values']) && isset($additional_customizations['additional_values']['iwgtiimportrefproduct'])) {
                $newCustomProdObj->reference = $additional_customizations['additional_values']['iwgtiimportrefproduct']['value'];
            } else {
                $newCustomProdObj->reference = Tools::str2url('custom-'.$product->id.'-'.(int)Tools::getValue('ndkcf_id_combination').'-'.Context::getContext()->cart->id.'-'.$myIdCustomization);
            }
        }
        
        $newCustomProdObj->description = $newDesc;
        $newCustomProdObj->active = 1;
      
        if ($ndkcf_itself) {
            foreach ($languages as $lang) {
                $newCustomProdObj->name[$lang['id_lang']] = Tools::truncateString($module->bundle_text.' '.$product->name[$lang['id_lang']], 125);
                $newCustomProdObj->link_rewrite[$lang['id_lang']] = Tools::str2url($product->name[$lang['id_lang']]);
                $newCustomProdObj->description_short[$lang['id_lang']] = $module->bundle_text.' :'.$product->name[$lang['id_lang']];
            }
        }
      
        $newCustomProdObj->save();
        if (!$ndkcf_itself) {
            Product::duplicateSpecificPrices((int)$product->id, $newCustomProdObj->id);
            GroupReduction::duplicateReduction((int)$product->id, $newCustomProdObj->id);
        }
      
        //get current price for group/customer
        $myNewPrice = Product::getPriceStatic((int)$newCustomProdObj->id, $usetax, (int)0, 6, null, false, true, (int)Tools::getValue('qty'), false, (int)$context->customer->id, (int)$context->cart->id);
        if (sizeof($percent_price) >0) {
            foreach ($percent_price as $key=>$value) {
                if ($value > 0) {
                    $multiplicatorHT = $value/100;
                    $myNewPrice += $myNewPrice*$multiplicatorHT;
                }
            }
        } else {
            $myNewPrice+=$customizationPrice;
        }
      
      
      
      
        foreach (SpecificPrice::getIdsByProductId((int)$newCustomProdObj->id) as $data) {
            $specific_price = new SpecificPrice((int)$data['id_specific_price']);
            //$specific_price->price = -1;
            if ($specific_price->price > 0) {
                $specific_price->price = number_format($myNewPrice, 6, '.', '');
            }
          
            if ((int)Configuration::get('NDK_REDUC_ONLY_PRODUCT') == 1) {
                if ($specific_price->reduction_type == 'percentage') {
                    //on transforme en montant
                    $price = Product::getPriceStatic((int)$product->id, true, (int)0, 6, null, false, false, (int)Tools::getValue('qty'), false, (int)$context->customer->id, (int)$context->cart->id);
                    //var_dump($price);
                    $specific_price->reduction_type = 'amount';
                    $reduc_percent = $specific_price->reduction;
                    $new_amount = $price*$reduc_percent;
                    $specific_price->reduction = $new_amount;
                }
            }
          
          
            $specific_price->update();
        }
    }
   
    $tax_name = new TaxRulesGroup(Product::getIdTaxRulesGroupByIdProduct((int)Tools::getValue('id_product'), Context::getContext()));
    //on ajoute le produit de base en tant que champs + le prix
    if (Product::getPriceStatic($product->id, $usetax, (int)Tools::getValue('ndkcf_id_combination'), 6) > 0) {
        //$myProductPrice = Product::getPriceStatic((int)Tools::getValue('id_product'), $usetax,(int)Tools::getValue('ndkcf_id_combination'), 6, null, false, true, (int)Tools::getValue('qty'), false, (int)$context->customer->id, (int)$context->cart->id);
        $myProductPrice = Product::getPriceStatic((int)Tools::getValue('id_product'), $usetax, (int)Tools::getValue('ndkcf_id_combination'), 6, null, false, false, 1, false, (int)$context->customer->id, (int)$context->cart->id);
        
        if ((int)Tools::getValue('ndkcf_id_combination') > 0) {
            $combNames = $product->getAttributesResume($id_lang);
            foreach ($combNames as $row) {
                if ($row['id_product_attribute'] == (int)Tools::getValue('ndkcf_id_combination')) {
                    $combName = $row['attribute_designation'];
                }
            }
        } else {
            $combName = false;
        }
        
        
        $base_text = $product->name[$id_lang].(isset($combName) && $combName !='' ? '('.$combName.')' : ' - '.$product->reference).'  = '.Tools::displayPrice($myProductPrice).($usetax ? ' ('.$tax_name->name.')' : '') ."\n" ;
       
        $custom_reference = $product->reference;


        $link_index_reference = createLabel($languages, 1, $refProduct, $labels_custom_reference);
        if ($custom_reference !='') {
            addTextFieldToProduct((int)$refProduct, $link_index_reference, 1, $custom_reference);
        }
        
        
        if (Configuration::get('NDK_ADD_PRODUCT_PRICE') == 1 && Configuration::get('NDK_SHOW_BASE_PRODUCT') == 1 && !$disabe_product_price) {
            $link_index_base = createLabel($languages, 1, $refProduct, $labels_base);
            addTextFieldToProduct((int)$refProduct, $link_index_base, 1, $base_text);
        }
    }
   
    if (!$empty_form) {
   
         //enregistrement image
        $errors = array();
         
        $product_picture_width = (int)Configuration::get('PS_PRODUCT_PICTURE_WIDTH');
        $product_picture_height = (int)Configuration::get('PS_PRODUCT_PICTURE_HEIGHT');
        $suff = 1;
         
        foreach ($cartImgs as $key => $value) {
            foreach ($languages as $language) {
                $labels_image[$language['id_lang']][0]['name'] = 'Image '.$suff;
            }
            $image_field = createLabel($languages, 0, (int)$newCustomProd, $labels_image);
            $file_name = md5(uniqid(rand(), true));
            $tmp_name = $value;
            /* Original file */
            if (!ImageManager::resize($tmp_name, _PS_UPLOAD_DIR_.$file_name)) {
                $errors[] = '';
            }//Tools::displayError('An error occurred during the image upload process.');
            /* A smaller one */
            elseif (!ImageManager::resize($tmp_name, _PS_UPLOAD_DIR_.$file_name.'_small', $product_picture_width, $product_picture_height)) {
                $errors[] = '';
            }//Tools::displayError('An error occurred during the image upload process.');
            elseif (!chmod(_PS_UPLOAD_DIR_.$file_name, 0777) || !chmod(_PS_UPLOAD_DIR_.$file_name.'_small', 0777)) {
                $errors[] = '';
            }//Tools::displayError('An error occurred during the image upload process.');
            /*else
            $context->cart->addPictureToProduct((int)$refProduct, $image_field, 0,$file_name);*/
            
            
            /*if($customizationPrice > 0) {

               //add image to product
               $image = new Image();
               $image->id_product = $newCustomProd;
               $image->position = Image::getHighestPosition($newCustomProd) + 1;
               $image->cover = ($suff == 1 ? true : false); // or false;
               if (($image->validateFields(false, true)) === true &&
               ($image->validateFieldsLang(false, true)) === true && $image->add())
               {
                   $shops = Shop::getContextListShopID();
                   $image->associateTo($shops);

                   if (!NdkCf::copyImg($newCustomProd, $image->id, $tmp_name, 'products', true))
                   {
                       $image->delete();
                   }
               }
               //eof
            }*/
            $suff++;
        }
         
        /*if($customizationPrice > 0) {

           //add image to product
           $product_images = Image::getImages((int)$id_lang, (int)Tools::getValue('id_product'), (int)Tools::getValue('ndkcf_id_combination'));
           if(sizeof($product_images) > 0)
           {
               $image = new Image( (int)$product_images[0]['id_image'] );
               $image->id_product = $newCustomProd;
               $image->position = Image::getHighestPosition($newCustomProd) + 1;
               $image->cover = true; // or false;
               if (($image->validateFields(false, true)) === true &&
               ($image->validateFieldsLang(false, true)) === true && $image->add())
               {
                   $shops = Shop::getContextListShopID();
                   $image->associateTo($shops);

                   if (!NdkCf::copyImg($newCustomProd, $image->id, $tmp_name, 'products', true))
                   {
                       $image->delete();
                   }
               }
               //eof
            }
        }*/
         
        $customization_product = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'customization`
         WHERE `id_cart` = '.(int)$context->cart->id.' AND `id_product` = '.(int)Tools::getValue('id_product'));
         
         
        //print($customization_product[0]['id_customization']);
    }


}

  $json_datas['result'] = $return;
  saveJsonDatas($json_datas);
  
  function saveJsonDatas($json_datas)
  {
      $id_product = $json_datas['result']['id_product'];
      $id_customer = Context::getContext()->customer->id;
      $id_customization = $json_datas['result']['id_customization'];
      if (!is_dir(_PS_IMG_DIR_.'scenes/'.'ndkcf/pdf/')) {
          mkdir(_PS_IMG_DIR_.'scenes/'.'ndkcf/pdf/', 0777);
      }
      if (!is_dir(_PS_IMG_DIR_.'scenes/'.'ndkcf/pdf/'.(int)$id_customer)) {
          mkdir(_PS_IMG_DIR_.'scenes/'.'ndkcf/pdf/'.(int)$id_customer, 0777);
      }
      
      if (!is_dir(_PS_IMG_DIR_.'scenes/'.'ndkcf/pdf/'.(int)$id_customer.'/'.(int)$id_product)) {
          mkdir(_PS_IMG_DIR_.'scenes/'.'ndkcf/pdf/'.(int)$id_customer.'/'.(int)$id_product, 0777);
      }
    
      if (!is_dir(_PS_IMG_DIR_.'scenes/'.'ndkcf/pdf/'.(int)$id_customer.'/'.(int)$id_product.'/'.(int)$id_customization)) {
          mkdir(_PS_IMG_DIR_.'scenes/'.'ndkcf/pdf/'.(int)$id_customer.'/'.(int)$id_product.'/'.(int)$id_customization, 0777);
      }
    
      file_put_contents(_PS_IMG_DIR_.'scenes/'.'ndkcf/pdf/'.(int)$id_customer.'/'.(int)$id_product.'/'.(int)$id_customization.'/config.json', Tools::jsonEncode($json_datas));
  }

   function createLabel($languages, $type, $id_product, $labels, $required = 0)
   {
       $result = false;
       $count = 0;
       $id_customization_field = 0;
       $required = 0;
       if ($labels[(int) Context::getContext()->language->id]) {
           if ($labels[(int) Context::getContext()->language->id][0]['name'] !='') {
               //on recherche un champs existant
               $result = Db::getInstance()->executeS('
               SELECT cf.`id_product`, cfl.id_customization_field
               FROM `'._DB_PREFIX_.'customization_field` cf
               NATURAL JOIN `'._DB_PREFIX_.'customization_field_lang` cfl
               WHERE cf.`id_product` = '.(int)$id_product. ' AND cfl.`id_lang` = '.(int) Context::getContext()->language->id.' AND cfl.name = \''.pSQL($labels[(int) Context::getContext()->language->id][0]['name']).'\'
               ORDER BY cf.`id_customization_field`');
               $count += sizeof($result);
           }
       }


       if ($count == 0 && $labels[(int) Context::getContext()->language->id]) {
           // Label insertion
           if (!Db::getInstance()->execute('
            INSERT INTO `'._DB_PREFIX_.'customization_field` (`id_product`, `type`, `required`)
            VALUES ('.(int)$id_product.', '.(int)$type.', '.(int)$required.')') ||
            !$id_customization_field = (int)Db::getInstance()->Insert_ID()) {
               return false;
           }

           // Multilingual label name creation
           $values = '';

           foreach (Shop::getContextListShopID() as $id_shop) {
               foreach ($languages as $language) {
                   $values .= '('.(int)$id_customization_field.', '.(int) $language['id_lang'].', '.(int)$id_shop.', \''.pSQL($labels[(int) Context::getContext()->language->id][0]['name']).'\'), ';
               }
           }

           $values = rtrim($values, ', ');
           if (!Db::getInstance()->execute('
                    INSERT INTO `'._DB_PREFIX_.'customization_field_lang` (`id_customization_field` ,`id_lang`, `id_shop`, `name`)
                    VALUES '.$values)) {
               return false;
           }

           // Set cache of feature detachable to true
           Configuration::updateGlobalValue('PS_CUSTOMIZATION_FEATURE_ACTIVE', '1');
       } else {
           if ($result) {
               $id_customization_field = $result[0]['id_customization_field'];
           }
           Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'customization_field` SET `required` = '.(int)$required.' WHERE id_customization_field = '.(int)$id_customization_field);
       }

       return (int)$id_customization_field;
   }
   
   function addTextFieldToProduct($id_product, $index, $type, $text_value, $ext_admin_name=false, $ext_display=false, $ext_display_index=false)
   {
       return _addCustomization($id_product, 0, $index, $type, $text_value, 0, $ext_admin_name, $ext_display, $ext_display_index);
   }
   
      /**
       * Add customer's pictures
       *
       * @return bool Always true
       */
      function addPictureToProduct($id_product, $index, $type, $file)
      {
          return _addCustomization($id_product, 0, $index, $type, $file, 0);
      }
   
   
   
   function _addCustomization($id_product, $id_product_attribute, $index, $type, $field, $quantity, $ext_admin_name=false, $ext_display=false, $ext_display_index=false)
   {
       $context = Context::getContext();
         
       $exising_customization = Db::getInstance()->executeS(
           '
            SELECT cu.`id_customization`, cd.`index`, cd.`value`, cd.`type` FROM `'._DB_PREFIX_.'customization` cu
            LEFT JOIN `'._DB_PREFIX_.'customized_data` cd
            ON cu.`id_customization` = cd.`id_customization`
            WHERE cu.id_cart = '.(int)$context->cart->id.'
            AND cu.id_product = '.(int)$id_product.'
            AND in_cart = 0'
       );
   
       if ($exising_customization) {
           // If the customization field is alreay filled, delete it
           foreach ($exising_customization as $customization) {
               if ($customization['type'] == $type && $customization['index'] == $index) {
                   Db::getInstance()->execute('
                     DELETE FROM `'._DB_PREFIX_.'customized_data`
                     WHERE id_customization = '.(int)$customization['id_customization'].'
                     AND type = '.(int)$customization['type'].'
                     AND `index` = '.(int)$customization['index']);
                   if ($type == Product::CUSTOMIZE_FILE) {
                       @unlink(_PS_UPLOAD_DIR_.$customization['value']);
                       @unlink(_PS_UPLOAD_DIR_.$customization['value'].'_small');
                   };
                   Db::getInstance()->execute('
                     DELETE FROM `'._DB_PREFIX_.'ndk_customized_data`
                     WHERE id_customization = '.(int)$customization['id_customization'].'
                     AND type = '.(int)$customization['type'].'
                     AND `index` = '.(int)$customization['index']);
                   break;
               }
           }
           $id_customization = $exising_customization[0]['id_customization'];
       } else {
           Db::getInstance()->execute(
               'INSERT INTO `'._DB_PREFIX_.'customization` (`id_cart`, `id_product`, `id_product_attribute`, `quantity`)
               VALUES ('.(int)$context->cart->id.', '.(int)$id_product.', '.(int)$id_product_attribute.', '.(int)$quantity.')'
           );
           $id_customization = Db::getInstance()->Insert_ID();
       }
   
       /*$query = 'INSERT INTO `'._DB_PREFIX_.'customized_data` (`id_customization`, `type`, `index`, `value`)
          VALUES ('.(int)$id_customization.', '.(int)$type.', '.(int)$index.', \''.pSQL($field).'\')';*/
         
        if ( $ext_display ) {
            $query = 'INSERT INTO `'._DB_PREFIX_.'customized_data` (`id_customization`, `type`, `index`, `value`)
                    VALUES ('.(int)$id_customization.', '.(int)$type.', '.(int)$index.', \''.addslashes(nl2br($field)).'\')';
            if (!Db::getInstance()->execute($query)) {
                return false;
            }
        }

       if ($ext_admin_name !== false) {
            $query  = 'INSERT INTO `'._DB_PREFIX_.'ndk_customized_data_extended` (`id_customization`, `type`, `index`, `admin_name`, `display`, `display_index`, `value`)
            VALUES ('.(int)$id_customization.', '.(int)$type.', '.(int)$index.', "'. pSQL($ext_admin_name) .'", '.(int)$ext_display . ', '.(int)$ext_display_index.', "'.addslashes(nl2br($field)).'")';
            if (!Db::getInstance()->execute($query)) {
                return false;
            }     
       }
       
       return $id_customization;
   }
