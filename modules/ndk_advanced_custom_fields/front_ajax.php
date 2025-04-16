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
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCf.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCfValues.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCfSpecificPrice.php';
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/models/ndkCfConfig.php';

$context = Context::getContext();
$link = new Link();
if ((float)_PS_VERSION_ > 1.6) {
    if (Tools::getValue('action') && Tools::getValue('action') == 'formatPrice') {
        $price = formatNdk(convertAmountNdk(Tools::getValue('price')));
        
        echo $price;
    }
    
    if (Tools::getValue('action') && Tools::getValue('action') == 'getCombination') {
        if (Tools::getValue('group')) {
            $context = Context::getContext();
            
            $id_address = (int)Context::getContext()->cart->id_address_invoice;
            $address = Address::initialize($id_address, true);
            $tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)Tools::getValue('id_product'), Context::getContext()));
            $product_tax_calculator = $tax_manager->getTaxCalculator();
            $usetax = Group::getPriceDisplayMethod(Group::getPriceDisplayMethod(Context::getContext()->customer->id_default_group));
            $usetax = Product::$_taxCalculationMethod == PS_TAX_INC;
            
            $data = array();
            $data['id_product_attribute'] = (int)Product::getIdProductAttributesByIdAttributes((int)Tools::getValue('id_product'), Tools::getValue('group'));
            
            if ((int)$data['id_product_attribute'] == 0) {
                $id_product_attribute = null;
            } else {
                $id_product_attribute = $data['id_product_attribute'];
            }
            
            
            $data['price'] = Product::getPriceStatic((int)Tools::getValue('id_product'), $usetax, $id_product_attribute, 6, null, false, true, (int)Tools::getValue('quantity'), false, (int)$context->customer->id, (int)$context->cart->id);
            $product = new Product((int)Tools::getValue('id_product'));
            $images = Ndkcf::getAttributeImagesAssociations($id_product_attribute, (int)Tools::getValue('id_product'));
            $data['images'] = array();
            if ($images) {
                foreach ($images as $image) {
                    $data['images'][] = (Configuration::get('PS_SSL_ENABLED') == 1 && Configuration::get('PS_SSL_ENABLED_EVERYWHERE') == 1 ? 'https://' : 'http://').$link->getImageLink($product->link_rewrite[Context::getContext()->language->id], $image, Configuration::get('NDK_IMAGE_SIZE'));
                }
            }
            //$data['stock'] = (int)StockAvailable::getQuantityAvailableByProduct((int)Tools::getValue('id_product'), (int)$id_product_attribute);
            $data['stock'] = (int)Product::getQuantity((int)Tools::getValue('id_product'), (int)$id_product_attribute, null, $context->cart);

            
            //echo (int)Product::getIdProductAttributesByIdAttributes((int)Tools::getValue('id_product'), Tools::getValue('group'));
            echo json_encode($data);
        }
    }
}
if (Tools::getValue('action') && Tools::getValue('action') == 'removePriceTaxes') {
    $context = Context::getContext();
    
    $id_address = (int)Context::getContext()->cart->id_address_invoice;
    $address = Address::initialize($id_address, true);
    $tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)Tools::getValue('id_product'), Context::getContext()));
    $product_tax_calculator = $tax_manager->getTaxCalculator();
    $price_without_taxes = $product_tax_calculator->removeTaxes(Tools::getValue('price'));
    echo $price_without_taxes;
}



if (Tools::getValue('action') && Tools::getValue('action') == 'getAttributePrice') {
    $context = Context::getContext();
    
    $id_address = (int)Context::getContext()->cart->id_address_invoice;
    $address = Address::initialize($id_address, true);
    $tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)Tools::getValue('id_product'), Context::getContext()));
    $product_tax_calculator = $tax_manager->getTaxCalculator();
    $usetax = Group::getPriceDisplayMethod(Group::getPriceDisplayMethod(Context::getContext()->customer->id_default_group));
    $usetax = Product::$_taxCalculationMethod == PS_TAX_INC;
    
    
    if ((int)Tools::getValue('id_product_attribute') == 0) {
        $id_product_attribute = null;
    } else {
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
    }
    
    //echo Product::getPriceStatic((int)Tools::getValue('id_product'), true,(int)Tools::getValue('id_product_attribute'), 2);
    $result['price'] =  Product::getPriceStatic((int)Tools::getValue('id_product'), $usetax, $id_product_attribute, 6, null, false, true, (int)Tools::getValue('quantity'), false, (int)$context->customer->id, (int)$context->cart->id);
    
    $result['weight'] = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
        '
        SELECT product_attribute_shop.`weight`
        FROM `'._DB_PREFIX_.'product_attribute` pa
        '.Shop::addSqlAssociation('product_attribute', 'pa').'
        WHERE pa.`id_product_attribute` = '.(int)$id_product_attribute
    );
    
    $p_oos = StockAvailable::outOfStock((int)Tools::getValue('id_product'));
    $result['oos'] = Product::isAvailableWhenOutOfStock($p_oos);
    $result['stock'] = StockAvailable::getQuantityAvailableByProduct((int)Tools::getValue('id_product'), (int)$id_product_attribute);
    
    
    echo json_encode($result);
}

if (Tools::getValue('action') && Tools::getValue('action') == 'getAttributeImg') {
    if ((int)Tools::getValue('id_product_attribute') == 0) {
        $id_product_attribute = null;
    } else {
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
    }
    
    $id_image = Ndkcf::getAttributeImageAssociations($id_product_attribute, (int)Tools::getValue('id_product'));
    echo(Configuration::get('PS_SSL_ENABLED') == 1 && Configuration::get('PS_SSL_ENABLED_EVERYWHERE') == 1 ? 'https://' : 'http://').$link->getImageLink(Tools::getValue('link_rewrite'), $id_image, Configuration::get('NDK_IMAGE_SIZE'));
    //var_dump($id_image);
}

if (Tools::getValue('action') && Tools::getValue('action') == 'getSpecificPrice') {
    $context = Context::getContext();
    $customer_group = $context->customer->getGroups();
    $customer_group[] = 0;
    $id_address = (int)Context::getContext()->cart->id_address_invoice;
    $address = Address::initialize($id_address, true);
    $tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)Tools::getValue('id_product'), Context::getContext()));
    $product_tax_calculator = $tax_manager->getTaxCalculator();
    $usetax = Group::getPriceDisplayMethod(Group::getPriceDisplayMethod(Context::getContext()->customer->id_default_group));
    $usetax = Product::$_taxCalculationMethod == PS_TAX_INC;
    
    if ((int)Tools::getValue('id_product_attribute') == 0) {
        $id_product_attribute = false;
    } else {
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
    }
    
    $reductions = false;
    
    if (sizeof(SpecificPrice::getByProductId((int)Tools::getValue('id_product'), false, false)) > 0) {
        $reductions = SpecificPrice::getByProductId((int)Tools::getValue('id_product'), false, false);
        //echo json_encode($reductions);
    }
    
    //$old_price = Product::getPriceStatic((int)Tools::getValue('id_product'), $usetax,$id_product_attribute, 6, null, false, false, (int)1, false, (int)$context->customer->id, (int)$context->cart->id);
    //$old_price = Product::getPriceStatic((int)Tools::getValue('id_product'), $usetax,$id_product_attribute, 6, null, false, false, (int)1, false);
    $old_price = NdkCf::getBrutPrice((int)Tools::getValue('id_product'), (int)Tools::getValue('ndkcf_id_combination'), $usetax, false);
    $last_qtty = -1;
    $now = date('Y-m-d H:i:00');
    $last_reduc = 0;
    if ($reductions) {
        foreach ($reductions as $key => $value) {
            $from = $value['from'];
            if ($value['to'] == '0000-00-00 00:00:00') {
                $to = '2100-00-00 00:00:00';
            } else {
                $to = $value['to'];
            }
            
            //var_dump(in_array((int)$value['id_group'], $customer_group));
            
            
            if (
                ((int)$value['from_quantity'] <= (int)Tools::getValue('quantity'))
                && ((int)$value['from_quantity'] > $last_qtty)
                && ($now >= $from && $now <= $to)
                && (in_array((int)$value['id_group'], $customer_group))
                && ($value['reduction'] > $last_reduc
                || ($value['price'] > 0 && $value['price'] <  ($old_price - $last_reduc)))
                && ($value['id_product_attribute'] == $id_product_attribute
                || $value['id_product_attribute'] == 0)
                && ($value['id_shop'] == $context->shop->id || $value['id_shop'] == 0)
                && ($value['id_currency'] == $context->currency->id || $value['id_currency'] == 0)
            ) {
                //var_dump($value['price']);
                $last_qtty = $value['from_quantity'];
                if ((int)$value['from_quantity'] <= (int)Tools::getValue('quantity')) {
                    $reduction = $value;
                }
                if ($value['price'] > 0) {
                    if ($usetax) {
                        $value['price'] = $product_tax_calculator->addTaxes($value['price']);
                    }
                    
                    $reduc = $old_price - $value['price'];
                    
                    
                    $reduction['reduction'] = $reduc;
                    $last_reduc = $reduc;
                } else {
                    $last_reduc = $value['reduction'];
                }
            }
        }
    }
    if (isset($reduction)) {
        if ($reduction['reduction_type'] == 'amount' && $reduction['reduction_tax'] == 0 && $usetax) {
            $reduction['reduction'] = $product_tax_calculator->addTaxes($reduction['reduction']);
        }
    }
    //$reduction['public_price'] = Product::getPriceStatic((int)Tools::getValue('id_product'), $usetax,$id_product_attribute, 6, null, false, true, (int)1, false, (int)$context->customer->id, (int)$context->cart->id);
    $reduction['public_price'] = Product::getPriceStatic((int)Tools::getValue('id_product'), $usetax, $id_product_attribute, 6, null, false, true, (int)Tools::getValue('quantity'), false, (int)$context->customer->id, (int)$context->cart->id);
    $reduction['old_price'] = $old_price;
    
    echo json_encode($reduction);
}


if (Tools::getValue('id_value') && Tools::getValue('id_value') > 0 && Tools::getValue('action') && Tools::getValue('action') == 'getRestrictions') {
    $val = new ndkCfValues((int)Tools::getValue('id_value'), Context::getContext()->language->id);
    
    $result = array();
    if ($val->influences_restrictions !='') {
        $values = explode(',', $val->influences_restrictions);
        
        $result['restrictions'] = array();
        foreach ($values as $value) {
            if ($value[0].$value[1].$value[2] == 'all') {
                $result['restrictions'][] = explode('-', $value)[1].'|all|all';
            } else {
                $v = new ndkCfValues((int)$value, Context::getContext()->language->id);
                $result['restrictions'][] = $v->id_ndk_customization_field.'|'.$value.'|'.$v->value;
            }
        }
    }
    
    if ($val->influences_obligations !='') {
        $values = explode(',', $val->influences_obligations);
        $result['obligations'] = array();
        foreach ($values as $value) {
            if ($value[0].$value[1].$value[2] == 'all') {
                $result['obligations'][] = explode('-', $value)[1].'|all|all';
            } else {
                $v = new ndkCfValues((int)$value, Context::getContext()->language->id);
                $result['obligations'][] = $v->id_ndk_customization_field.'|'.$value.'|'.$v->value;
            }
        }
    }
    
    echo Tools::jsonEncode($result);
}

if (Tools::getValue('action') && Tools::getValue('action') == 'getRangePrice') {
    $item_price =  NdkCf::getDimensionPrice((int)Tools::getValue('group'), Tools::getValue('width'), Tools::getValue('height'));
    $id_address = (int)Context::getContext()->cart->id_address_invoice;
    $address = Address::initialize($id_address, true);
    $tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)Tools::getValue('id_product'), Context::getContext()));
    $product_tax_calculator = $tax_manager->getTaxCalculator();
    $usetax = Group::getPriceDisplayMethod(Group::getPriceDisplayMethod(Context::getContext()->customer->id_default_group));
    $usetax = Product::$_taxCalculationMethod == PS_TAX_INC;
    
    if (Product::$_taxCalculationMethod == 0) {
        $usetax = true;
    } else {
        $usetax = false;
    }
    
    if ($usetax) {
        $item_price = $product_tax_calculator->addTaxes($item_price);
    }
    
    echo $item_price;
}


if (Tools::getValue('action') && Tools::getValue('action') == 'getMinHeight') {
    $sql = 'SELECT MIN(height + 0.0) as min FROM '._DB_PREFIX_.'ndk_customization_field_csv WHERE height !="" AND id_ndk_customization_field = '.(int)Tools::getValue('group').' AND width ='.(float)Tools::getValue('width');
    $min = Db::getInstance()->getValue($sql);
    //var_dump($sql);
    echo $min;
}


if (Tools::getValue('action') && Tools::getValue('action') == 'getPricesDiscount') {
    $context = Context::getContext();
    
    $id_address = (int)Context::getContext()->cart->id_address_invoice;
    $address = Address::initialize($id_address, true);
    $tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)Tools::getValue('id_product'), Context::getContext()));
    $product_tax_calculator = $tax_manager->getTaxCalculator();
    $usetax = Group::getPriceDisplayMethod(Group::getPriceDisplayMethod(Context::getContext()->customer->id_default_group));
    $usetax = Product::$_taxCalculationMethod == PS_TAX_INC;
    
    $prices = NdkCf::getCustomizationPrice(Tools::getValue('group'), Tools::getValue('value'), Tools::getValue('id_product'));
    $cprice = 0;
    $priced = false;
    $value = Tools::getValue('value');

    for ($j = 0; $j < sizeof($prices); $j++) {
        if (empty($value) || $value == '') {
            if (!$priced) {
                $price = $prices[$j];
                $cprice = 0;
                $priced = true;
            }
        } elseif ($prices[$j]['valuePrice'] && $prices[$j]['valuePrice'] > 0 && $prices[$j]['value'] && ($prices[$j]['value'] == $value)) {
            if (!$priced) {
                $price = $prices[$j];
             
                if ($prices[$j]['valuePrice'] > 0) {
                    $cprice = $prices[$j]['valuePrice'];
                    //on recupère les discount pour la valeur
                    $specificPrices = NdkCfSpecificPrice::getSpecificPrices((int)Tools::getValue('group'), $prices[$j]['id_ndk_customization_field_value'], (int)Tools::getValue('quantity'));
                    //var_dump($specificPrices);
                    if (sizeof($specificPrices) > 0) {
                        if ($specificPrices[0]['reduction_type'] == 'amount') {
                            $cprice = $cprice - $specificPrices[0]['reduction'];
                        } else {
                            $cprice = $cprice - ($cprice*($specificPrices[0]['reduction']/100));
                        }
                    }
                } elseif ($prices[$j]['price'] > 0) {
                    $cprice = $prices[$j]['price'];
                    //on recupère les discount pour le champs
                    $specificPrices = NdkCfSpecificPrice::getSpecificPrices((int)Tools::getValue('group'), 0, 0);
                    if (sizeof($specificPrices) > 0) {
                        if ($specificPrices[0]['reduction_type'] == 'amount') {
                            $cprice = $cprice - $specificPrices[0]['reduction'];
                        } else {
                            $cprice = $cprice - ($cprice*($specificPrices[0]['reduction']/100));
                        }
                    }
                } else {
                    $cprice = 0;
                }
                $priced = true;
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
            }
        } else {
            if (isset($prices[$j]['type']) && ($prices[$j]['type'] == 0 || $prices[$j]['type'] == 13 || $prices[$j]['type'] == 14 || $prices[$j]['type'] == 6)) {
                $value = str_replace('¶', '', $value);
                $valable_string = explode('[', str_replace(' ', '', $value));
            }
            
            if (!$prices[$j]['valuePrice']) {
                if (isset($valable_string)) {
                    if (!$priced && $valable_string[0] != '') {
                        $price = $prices[$j];
                        if ($prices[$j]['price_per_caracter'] > 0) {
                            $valable_string = explode('[', str_replace(' ', '', $value));
                         
                            $cprice = $prices[$j]['price_per_caracter']*(Tools::strlen($valable_string[0]));
                        } else {
                            $cprice = $prices[$j]['price'];
                        }
                        $priced = true;
                    }
                }
            }
        }
       
        if ($prices[$j]['price_type'] == 'percent') {
            $product_tax_calculator->removeTaxes($cprice);
        }
        //$percent_price[$prices[$j]['id_ndk_customization_field']] = $cprice;
    }
    
    echo json_encode($cprice);
}




if (Tools::getValue('action') && Tools::getValue('action') == 'getAllPricesDiscount') {
    $context = Context::getContext();
    
    $id_address = (int)Context::getContext()->cart->id_address_invoice;
    $address = Address::initialize($id_address, true);
    $tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)Tools::getValue('id_product'), Context::getContext()));
    $product_tax_calculator = $tax_manager->getTaxCalculator();
    $usetax = Group::getPriceDisplayMethod(Group::getPriceDisplayMethod(Context::getContext()->customer->id_default_group));
    $usetax = Product::$_taxCalculationMethod == PS_TAX_INC;
    $return = array();
    $i = 0;
    foreach (Tools::getValue('group') as $key => $value) {
        $group = $key;
        if ((int)$group > 0 && $value !='') {
            $prices = NdkCf::getCustomizationPrice($group, $value, Tools::getValue('id_product'));
            $cprice = 0;
            $priced = false;
            //$value = Tools::getValue('value');
            
            for ($j = 0; $j < sizeof($prices); $j++) {
                if (empty($value) || $value == '') {
                    if (!$priced) {
                        $price = $prices[$j];
                        $cprice = 0;
                        $priced = true;
                    }
                } elseif ($prices[$j]['valuePrice'] && $prices[$j]['valuePrice'] > 0 && $prices[$j]['value'] && ($prices[$j]['value'] == $value)) {
                    if (!$priced) {
                        $price = $prices[$j];
                        
                        if ($prices[$j]['valuePrice'] > 0) {
                            $cprice = $prices[$j]['valuePrice'];
                            //on recupère les discount pour la valeur
                            $specificPrices = NdkCfSpecificPrice::getSpecificPrices((int)$group, $prices[$j]['id_ndk_customization_field_value'], (int)Tools::getValue('quantity'));
                            //var_dump($specificPrices);
                            if (sizeof($specificPrices) > 0) {
                                if ($specificPrices[0]['reduction_type'] == 'amount') {
                                    $cprice = $cprice - $specificPrices[0]['reduction'];
                                } else {
                                    $cprice = $cprice - ($cprice*($specificPrices[0]['reduction']/100));
                                }
                            }
                        } elseif ($prices[$j]['price'] > 0) {
                            $cprice = $prices[$j]['price'];
                            //on recupère les discount pour le champs
                            $specificPrices = NdkCfSpecificPrice::getSpecificPrices((int)$group, 0, 0);
                            if (sizeof($specificPrices) > 0) {
                                if ($specificPrices[0]['reduction_type'] == 'amount') {
                                    $cprice = $cprice - $specificPrices[0]['reduction'];
                                } else {
                                    $cprice = $cprice - ($cprice*($specificPrices[0]['reduction']/100));
                                }
                            }
                        } else {
                            $cprice = 0;
                        }
                        $priced = true;
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
                    }
                } else {
                    if (isset($prices[$j]['type']) && ($prices[$j]['type'] == 0 || $prices[$j]['type'] == 13 || $prices[$j]['type'] == 14 || $prices[$j]['type'] == 6)) {
                        $value = str_replace('¶', '', $value);
                        $valable_string = explode('[', str_replace(' ', '', $value));
                    }
                    
                    if (!$prices[$j]['valuePrice']) {
                        if (isset($valable_string)) {
                            if (!$priced && $valable_string[0] != '') {
                                $price = $prices[$j];
                                if ($prices[$j]['price_per_caracter'] > 0) {
                                    $valable_string = explode('[', str_replace(' ', '', $value));
                                    
                                    $cprice = $prices[$j]['price_per_caracter']*(Tools::strlen($valable_string[0]));
                                } else {
                                    $cprice = $prices[$j]['price'];
                                }
                                $priced = true;
                            }
                        }
                    }
                }
                
                if ($prices[$j]['price_type'] == 'percent') {
                    $product_tax_calculator->removeTaxes($cprice);
                }
                
                $return[$group] = $cprice;
            }
        }
        $i++;
    }
    
    echo json_encode($return);
}


if (Tools::getValue('action') == 'getConfImage') {
    $conf = new NdkCfConfig((int)Tools::getValue('id_conf'));
    print($conf->cover);
}

if (Tools::getValue('action') == 'getSubValues') {
    $ndkAcf = Module::getInstanceByName('ndk_advanced_custom_fields');
    echo $ndkAcf->ajaxCall();
}

// Specific Norauto : ADD or UPDATE SAP field for all products in cart
if (Tools::getValue('action') ==='addSapToCartProducts') {
    $context = Context::getContext();
    $sapValue = Tools::getValue('sapValue');
    $customizations = Tools::getValue('customizations');
    $customizationFields = getCartProductsSapFields($customizations);
    $success = true;
    foreach($customizationFields as $field) {
            // update
            $sql = 'UPDATE `'._DB_PREFIX_.'customized_data` cd
            SET cd.`value` = "'.pSQL($sapValue).'"
            WHERE cd.`id_customization` = '.$field['id_customization'].'
            AND cd.`type` = '.$field['type'].'
            AND cd.`index` = '.$field['index'].'
            ';
            $success &= Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
            $sql = '
            UPDATE `'._DB_PREFIX_.'ndk_customized_data_extended` cd
            SET cd.`value` = "'.pSQL($sapValue).'"
            WHERE cd.`id_customization` = '.$field['id_customization'].'
            AND cd.`type` = '.$field['type'].'
            AND cd.`index` = '.$field['index'].'
            ';
            $success &= Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
        }

    echo json_encode(['success' => $success]);
}

// Specific Norauto : Order only when all SAP fields are given and are the same
if (Tools::getValue('action') == 'isOrderAvailable') {
    $customizations = Tools::getValue('customizations');
    $customizationFields = getCartProductsSapFields($customizations);
    if (count($customizations) === count($customizationFields)) {
        $in = [];
        foreach($customizationFields as $field) {
            foreach ($field as $k => $v) {
                $in[$k][] = (int)$v;
            }
        }

        $sapValues = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT cd.`value`
            FROM `'._DB_PREFIX_.'customized_data` cd
            WHERE cd.`id_customization` IN ('.implode(',', $in['id_customization']).')
            AND cd.`type` IN ('.implode(',', $in['type']).')
            AND cd.`index` IN ('.implode(',', $in['index']).')'
        );
        
        $values = [];
        foreach($sapValues as $value) {
            $values[] = $value['value'];
        }

        $success = count(array_flip($values)) === 1 && !in_array(trim(end($values)), ['-', '']);
    } else {
        $success = false;
    }

    echo json_encode(['activateOrderBtn' => $success]);
}

// fixe les restrictions dynamiques (dynamic_influences) en fonction des valeurs sélectionnées
if (Tools::getValue('action') == 'setDynamicInfluences') {
    $id_product = Tools::getValue('ndkcf_id_product');
    $dynamic_influences = explode(' ',Tools::getValue('dynamic_influences')); // exemple : GA.modele CL.couleur couleur
    if (count($dynamic_influences) < 3) {
        echo json_encode(['error' => 'erreur sur paramètre dynamic_influences']);
        return;
    }
    // exemple : GA.modele CL.couleur couleur (ou GA.modele refnorauto refnorauto pour les champs texte sans données)
    $main_admin_name = $dynamic_influences[0];
    $influence_admin_name = $dynamic_influences[1];
    $prefix = trim($dynamic_influences[2]);

    // NDK champ principal
    $id_main_ndk_field = Db::getInstance()->getValue('SELECT cl.id_ndk_customization_field FROM '._DB_PREFIX_.'ndk_customization_field_lang cl
    INNER JOIN '._DB_PREFIX_.'ndk_customization_field c ON c.id_ndk_customization_field = cl.id_ndk_customization_field AND c.products = "'.$id_product.'"
    WHERE cl.admin_name = "'.$main_admin_name.'"');
    if ($id_main_ndk_field == false) {
        echo json_encode(['error' => 'champ NDK principal inexistant']);
        return;
    }
    // NDK champ à influencer
    $influence_ndk_fields = Db::getInstance()->executeS('SELECT cl.id_ndk_customization_field, c.type, c.products FROM '._DB_PREFIX_.'ndk_customization_field_lang cl
    INNER JOIN '._DB_PREFIX_.'ndk_customization_field c ON c.id_ndk_customization_field = cl.id_ndk_customization_field
    WHERE cl.admin_name = "'.$influence_admin_name.'"');
    if ($influence_ndk_fields == false) {
        echo json_encode(['error' => 'champ NDK à influencer inexistant']);
        return;
    }
    $id_influence_ndk_field = false;
    foreach($influence_ndk_fields as $influence_ndk_field) {
        if ($id_influence_ndk_field) {
            break;
        }
        $products = explode(',', $influence_ndk_field['products']);
        foreach($products as $p) {
            if ($id_influence_ndk_field) {
                break;
            }       
            if ($p == $id_product) {
                $id_influence_ndk_field = $influence_ndk_field['id_ndk_customization_field'];
                $type_influence_ndk_field = $influence_ndk_field['type'];            
            }
        }
    }
    if ($id_influence_ndk_field == false) {
        echo json_encode(['error' => 'ID champ NDK à influencer inexistant']);
        return;
    }
    // additional info field
    $key_column = false;
    $additional_informations = unserialize(Configuration::get('iwgtiimport_additional_info'));
    foreach($additional_informations as $ai) {
        if (($ai['prefix'] == $prefix) && ($ai['id_product'] == $id_product)) {
            $key_column = $ai['key_column'];
            break;
        }
    }
    
    if ($key_column == false) {
        echo json_encode(['error' => 'aucune information additionnelle existante']);
        return;
    }
    $selected = Tools::getValue('selected');
    if (count($selected) == 0) {
        echo json_encode(['error' => 'aucune valeur sélectionnée']);
        return;
    }
    /* exemple
    [
        {
            "group": 5,
            "value": "ALFA"
        },
        {
            "group": 21,
            "value": "ALFA 145 Berline du 01/01/1970 au 01/01/1970"
        },
        {
            "group": 2,
            "value": "Tuft"
        }
    ]
    */
    // ID du champ NDK champ à influencer et valeurs clefs pour recherche dans table ndk_customization_field_additional_info
    $key_id = 0;

    foreach($selected as $s) {
        if ($s['group'] == $id_main_ndk_field) {
            $key_id = $s['id_value']; 
        }
    }
    $search_fields = explode(',', $key_column);
    $search_values = [];

    foreach($search_fields as $sf) {
        $sf = trim($sf);
        // recherche groupe NDK
        $found = false;
        $sf = str_replace('code_', '%.', $sf);
        $search_ndk_fields = Db::getInstance()->executeS('SELECT cl.id_ndk_customization_field, c.products FROM '._DB_PREFIX_.'ndk_customization_field_lang cl
        INNER JOIN '._DB_PREFIX_.'ndk_customization_field c ON c.id_ndk_customization_field = cl.id_ndk_customization_field
        WHERE cl.admin_name LIKE "'.$sf.'"');

        if ($search_ndk_fields) {
            foreach($search_ndk_fields as $search_ndk_field) {
                $id_search_ndk_field = $search_ndk_field['id_ndk_customization_field'];
                $id_search_products = explode(',', $search_ndk_field['products']);
                foreach($id_search_products as $id_search_product) {
                    if (($id_search_product == $id_product) && ($found == false)) {
                        foreach($selected as $s) {
                            if (($s['group'] == $id_search_ndk_field) && ($found == false)) {
                                $key = Db::getInstance()->getValue('SELECT reference FROM '._DB_PREFIX_.'ndk_customization_field_value WHERE id_ndk_customization_field_value = '.(int)$s['id_value']);
 
                                if ($key) {
                                    $search_values[$id_search_ndk_field] = $key;
                                    $found = true;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // recherche valeurs restrictions
    $sql_result_search = 'SELECT value FROM '._DB_PREFIX_.'ndk_customization_field_additional_info WHERE
    id_product = '.(int)$id_product.' AND key_prefix = "'.$prefix.'" AND key_id = '.(int)$key_id.' AND key_values = "'.implode(',', array_values($search_values)).'"';
    $result_search = Db::getInstance()->getValue($sql_result_search);

    // id valeurs restrictions
    $show_values = [];
    $cfgtapisPrices = [];
    if ($result_search) {
        $expl_result_search = explode(',', $result_search);
        while (count($expl_result_search)) {
            $v = array_shift($expl_result_search);
            if ($type_influence_ndk_field == 0) {
                // type text
                $show_values[] = $v;
            } else {
                // type select, image
                $sql_search_value = 'SELECT id_ndk_customization_field_value FROM '._DB_PREFIX_.'ndk_customization_field_value
                WHERE id_ndk_customization_field = '.(int)$id_influence_ndk_field.' AND reference = "'.pSQL($v).'"';
                $id = Db::getInstance()->getValue($sql_search_value);
                $show_values[] = $id;

                // NOTICE : Request to get the prices of the configs.
                // We use the field "key_values" to find the right price. The structure of key_values is like "GT GTB 2AV2AR" or "EL ELN C", if it changes the request won't work anymore. 
                // key_values : first part is the "gamme", the second is the color and the third is the configuration.
                $sql_prices = 'SELECT value FROM '._DB_PREFIX_.'ndk_customization_field_additional_info WHERE 
                id_product = '.(int)$id_product.' AND key_prefix = "prix" AND key_id = '.(int)$key_id.' AND key_values LIKE "'.$search_values[$id_product].' % '.pSQL($v).'"';
                $result_sql_prices = Db::getInstance()->getValue($sql_prices);
                $cfgtapisPrices[$id] = str_replace('.', ',', $result_sql_prices);
            }
        }
    }

    // GTI Feature : cacher les champs NDK suivant ceux sélectionnés et le bouton d'ajout au panier si une des références correspondant aux valeurs sélectionnées existent
    // (les champs NDK ayant un status d'ouverture "hidden" ne sont pas impactés )
    if ($ref_hide_next_fields = Configuration::get('GTIFEATURES_REF_HIDE_NEXT_FIELDS')) {
        $ref_hide_next_fields = explode(',', $ref_hide_next_fields);
        $shownext = true;
        $hidenext = false;
        $hiderefs = [];
        foreach($selected as $s) {
            $hiderefs[] = (int)$s['id_value'];
        }
        if (count($hiderefs)) {
            $hidefieldssql = 'SELECT reference FROM '._DB_PREFIX_.'ndk_customization_field_value
            WHERE id_ndk_customization_field_value IN ('.implode(',',$hiderefs).')';
            if ($hidefields = Db::getInstance()->executeS($hidefieldssql)) {
                foreach($hidefields as $hf) {
                    if (in_array($hf['reference'], $ref_hide_next_fields)) {
                        $shownext = false;
                        $hidenext = true;
                    }
                }
            }
        }
    }

    $from_group = $selected[count($selected)-1];
    $from_group = $from_group['group'];
    $result = [
        //            'dynamic_influences' => $dynamic_influences,
        //            'selected' => $selected,
            'show_values' => $show_values,
            'cfgtapisPrices' => $cfgtapisPrices,
            'group' => $id_influence_ndk_field,
            'from_group' => $from_group,
            'field_type' => $type_influence_ndk_field,
        //            'hidefields' => $hidefields,
        //            'hidefieldssql' => $hidefieldssql,
        //            'additional_info_field' => $additional_informations,
        //            'id_main_ndk_field' => $id_main_ndk_field,
        //            'key_id' => $key_id,
        //            'search_values' => $search_values,
        //            'result_search' => $result_search,
        //            'sql_result_search' => $sql_result_search,
    ];
    if (isset($shownext) && isset($hidenext)) {
        $result['shownext'] = $shownext;
        $result['hidenext'] = $hidenext;
    }
    echo json_encode($result);
}



function convertAmountNdk($price)
{
    return (float)Tools::convertPrice($price);
}

function formatNdk($price)
{
    return Tools::displayPrice($price);
}

// Spécifique Norauto
function getCartProductsSapFields($customizationIds)
{
    $customizationIds = array_map('intval', $customizationIds);
    $customizationIds = implode(',', $customizationIds);
    $keyfieldssql = 'SELECT `id_customization`, `type`, `index` FROM `'._DB_PREFIX_.'ndk_customized_data_extended`
    WHERE `id_customization` IN ('.$customizationIds.') AND admin_name = "numerocommandesap"';

    return  Db::getInstance()->executeS($keyfieldssql);
}