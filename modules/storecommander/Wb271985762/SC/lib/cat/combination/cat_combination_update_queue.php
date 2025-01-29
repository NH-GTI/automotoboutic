<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

@error_reporting(E_ERROR | E_PARSE);
@ini_set('display_errors', 'ON');

$id_lang = Tools::getValue('id_lang', '0');

$return = 'ERROR: Try again later';

// FUNCTIONS
$updated_products = [];
function checkDefaultAttributes($id_product)
{
    $row = Db::getInstance()->getRow('
                            SELECT id_product, id_product_attribute
                            FROM `'._DB_PREFIX_.'product_attribute`
                            WHERE `default_on` = 1 AND `id_product` = '.(int) ($id_product));
    if ($row)
    {
        return (int) ($row['id_product_attribute']);
    }

    $mini = Db::getInstance()->getRow('
                            SELECT MIN(pa.id_product_attribute) as `id_attr`
                            FROM `'._DB_PREFIX_.'product_attribute` pa
                            WHERE `id_product` = '.(int) ($id_product));
    if (!$mini)
    {
        return 0;
    }

    if (!Db::getInstance()->execute('
                                UPDATE `'._DB_PREFIX_.'product_attribute`
                                SET `default_on` = 1
                                WHERE `id_product_attribute` = '.(int) ($mini['id_attr'])))
    {
        return 0;
    }

    return (int) ($mini['id_attr']);
}

// Récupération de toutes les modifications à effectuer
if (Tools::getValue('rows'))
{
    if (_PS_MAGIC_QUOTES_GPC_)
    {
        $_POST['rows'] = Tools::getValue('rows');
    }
    $rows = json_decode($_POST['rows']);

    if (is_array($rows) && count($rows) > 0)
    {
        $callbacks = '';

        // Première boucle pour remplir la table sc_queue_log
        // avec toutes ces modifications
        $log_ids = [];
        $date = date('Y-m-d H:i:s');
        foreach ($rows as $num => $row)
        {
            $id = QueueLog::add($row->name, $row->row, $row->action, (!empty($row->params) ? $row->params : []), (!empty($row->callback) ? $row->callback : null), $date);
            $log_ids[$num] = $id;
        }

        // Deuxième boucle pour effectuer les
        // actions les une après les autres
        foreach ($rows as $num => $row)
        {
            if (!empty($log_ids[$num]))
            {
                $gr_id = (int) $row->row;
                $action = $row->action;

                if (!empty($row->callback))
                {
                    $callbacks .= $row->callback.';';
                }

                $_POST = [];
                $_POST = (array) json_decode($row->params);

                if (!empty($action) && $action == 'delete' && !empty($gr_id))
                {
                    $idpa_array = explode(',', Tools::getValue('id_product_attribute', '0'));
                    $id_product = (int) Tools::getValue('id_product', '0');
                    $selected_shop_id = SCI::getSelectedShop();
                    $checked_shop_list = SCI::getSelectedShopActionList(false, $id_product);
                    $checked_shop_list_string = implode(',', $checked_shop_list);
                    if (!empty($id_product))
                    {
                        $updated_products[$id_product] = $id_product;

                        Context::getContext()->shop = new Shop($selected_shop_id);
                        Shop::setContext(Shop::CONTEXT_SHOP, $selected_shop_id);
                        $p = new Product($id_product, false, (int) $id_lang, (int) $selected_shop_id);
                        foreach ($idpa_array as $id_product_attribute)
                        {
                            if (is_numeric($id_product_attribute) && $id_product_attribute)
                            {
                                $combination = new Combination($id_product_attribute);
                                $combination->id_shop_list = $checked_shop_list;
                                $combination->delete();
                                foreach ($checked_shop_list as $shop_id)
                                {
                                    StockAvailable::removeProductFromStockAvailable((int) $id_product, (int) $id_product_attribute, (int) $shop_id);
                                }

                                $combination_sql_todo = [];
                                $stocks = Db::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'stock WHERE id_product_attribute = '.(int) $id_product_attribute);
                                if (!empty($stocks))
                                {
                                    foreach ($stocks as $stock)
                                    {
                                        $combination_sql_todo[] = 'DELETE FROM '._DB_PREFIX_.'stock_mvt WHERE id_stock='.(int) $stock['id_stock'];
                                    }
                                }
                                $combination_sql_todo[] = 'DELETE FROM '._DB_PREFIX_.'stock WHERE id_product_attribute = '.(int) $id_product_attribute;
                                $combination_sql_todo[] = 'DELETE FROM '._DB_PREFIX_.'warehouse_product_location WHERE id_product_attribute = '.(int) $id_product_attribute;
                                foreach ($combination_sql_todo as $rowSql)
                                {
                                    Db::getInstance()->execute($rowSql);
                                }
                            }
                        }

                        $p->checkDefaultAttributes();
                        if (!$p->hasAttributes())
                        {
                            if (version_compare(_PS_VERSION_, '1.7.8.0', '>='))
                            {
                                $sql = 'UPDATE '._DB_PREFIX_.'product SET cache_default_attribute=0, product_type = "'.pSQL(PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductType::TYPE_STANDARD).'" WHERE id_product='.(int) $id_product;
                            }
                            else
                            {
                                $sql = 'UPDATE '._DB_PREFIX_.'product SET cache_default_attribute=0 WHERE id_product='.(int) $id_product;
                            }
                            Db::getInstance()->execute($sql);
                            $sql = 'UPDATE '._DB_PREFIX_."product_shop SET cache_default_attribute='0' WHERE id_product='".(int) $id_product."' AND id_shop IN (".SCI::getSelectedShopActionList(true, $id_product).')';
                            Db::getInstance()->execute($sql);
                        }
                        else
                        {
                            if (SCMS)
                            {
                                $id_default_attribute = (int) Product::getDefaultAttribute($id_product);

                                $result = Db::getInstance()->update('product_shop', [
                                        'cache_default_attribute' => $id_default_attribute,
                                ], 'id_product = '.(int) $id_product.' AND id_shop IN ('.pInSQL($checked_shop_list_string).') ');

                                $sql = 'UPDATE '._DB_PREFIX_."product_attribute_shop SET default_on='1' WHERE `id_product_attribute` = '".(int) $id_default_attribute."' AND id_shop IN (".pInSQL($checked_shop_list_string).') ';
                                Db::getInstance()->execute($sql);

                                $result &= Db::getInstance()->update('product', [
                                        'cache_default_attribute' => $id_default_attribute,
                                ], 'id_product = '.(int) $id_product);
                            }
                            else
                            {
                                Product::updateDefaultAttribute((int) $id_product);
                            }
                        }
                        SCI::qtySumStockAvailable($id_product, $checked_shop_list);
                    }
                }
                elseif (!empty($action) && $action == 'update' && !empty($gr_id))
                {
                    $id_product = (int) Tools::getValue('id_product');

                    if (!empty($id_product))
                    {
                        $updated_products[$id_product] = $id_product;

                        $doHookUpdateQuantity = false;
                        $ecotaxrate = SCI::getEcotaxTaxRate();
                        $id_product_attribute = $gr_id;
                        $fields = ['reference', 'supplier_reference', 'ean13', 'upc', 'location', 'default_on', 'wholesale_price', 'minimal_quantity', 'unit_price_impact', 'available_date', 'sc_active'];
                        if (version_compare(_PS_VERSION_, '1.7.0.0', '>='))
                        {
                            $fields[] = 'isbn';
                        }
                        $shopfields = ['wholesale_price', 'unit_price_impact', 'default_on', 'minimal_quantity', 'available_date'];
                        if (version_compare(_PS_VERSION_, '1.7.3.0', '>='))
                        {
                            $fields[] = 'low_stock_alert';
                            $fields[] = 'low_stock_threshold';
                            $shopfields[] = 'low_stock_alert';
                            $shopfields[] = 'low_stock_threshold';
                        }
                        if (version_compare(_PS_VERSION_, '1.7.5.0', '>='))
                        {
                            $fields[] = 'location_new';
                        }
                        if (version_compare(_PS_VERSION_, '1.7.7.0', '>='))
                        {
                            $fields[] = 'mpn';
                        }
                        $updated_field = (Tools::getValue('updated_field'));
                        switch ($updated_field) {
                            case 'price':
                                $updated_field = 'priceextax';
                                break;
                            case 'unit_price_impact_inc_tax':
                                $updated_field = 'unit_price_impact';
                                break;
                            default:
                        }
                        $todo = [];
                        $shoptodo = [];
                        SC_Ext::readCustomCombinationsGridConfigXML('updateSettings');
                        foreach ($fields as $field)
                        {
                            if (isset($_POST[$field]) && $updated_field == $field)
                            {
                                if ($field == 'default_on')
                                {
                                    $val = Tools::getValue($field);
                                    if (empty($val))
                                    {
                                        $val = 'NULL';
                                    }
                                    else
                                    {
                                        $val = (int) $val;
                                    }
                                    $todo[] = '`'.bqSQL($field).'`='.$val;
                                }
                                elseif ($field == 'location_new')
                                {
                                    StockAvailable::setLocation((int) $id_product, psql(Tools::getValue($field)), (int) SCI::getSelectedShop(), (int) $id_product_attribute);
                                    addToHistory('cat_prop_attr', 'modification', $field, (int) $id_product_attribute, 0, _DB_PREFIX_.'stock_available', psql(Tools::getValue($field)));
                                }
                                else
                                {
                                    $todo[] = '`'.bqSQL($field)."`='".psql(Tools::getValue($field))."'";
                                    addToHistory('cat_prop_attr', 'modification', $field, (int) $id_product_attribute, 0, _DB_PREFIX_.'product_attribute', psql(Tools::getValue($field)));
                                }
                            }
                        }
                        if ($updated_field == 'default_on' && isset($_POST['default_on']) && (int) Tools::getValue('default_on') == 1)
                        {
                            $p = new Product($id_product);
                            $p->deleteDefaultAttributes();
                            $p->setDefaultAttribute($id_product_attribute);
                        }
                        if (Sc\Service\Service::exists('shippingbo'))
                        {
                            if ($updated_field == 'id_sbo' && isset($_POST['id_sbo']))
                            {
                                $id_shop = SCI::getSelectedShop();
                                $shippingboService = Sc\Service\Shippingbo\Shippingbo::getInstance();
                                $shippingboService->switchToShopId($id_shop);
                                // existing relation
                                $sboShopRelations = $shippingboService->getShopRelationRepository()->getAllByCombinationId($id_product, $id_product_attribute);
                                // no relation, create ones
                                if ($sboShopRelations)
                                {
                                    foreach ($sboShopRelations as $sboShopRelation)
                                    {
                                        $sboShopRelation
                                            ->setIdSbo(null)
                                            ->save();
                                    }
                                }
                            }
                        }
                        if ((isset($_POST['quantityupdate']) || isset($_POST['quantity'])) && ($updated_field == 'quantityupdate' || $updated_field == 'quantity'))
                        {
                            $quantity = (int) Tools::getValue('quantity');
                            $quantityUpdate = (int) Tools::getValue('quantityupdate', 0);

                            $old_rows = [];
                            $where = '';
                            if (SCMS)
                            {
                                foreach (SCI::getSelectedShopActionList(false, $id_product) as $id_shop)
                                {
                                    $id_shop_group = Shop::getGroupFromShop($id_shop, true);
                                    $shop_group = new ShopGroup($id_shop_group);
                                    if ($id_shop_group && $shop_group->share_stock == 1)
                                    {
                                        $where = " AND id_shop_group ='".(int) $id_shop_group."' ";
                                    }
                                    else
                                    {
                                        $where = " AND id_shop ='".(int) $id_shop."' ";
                                    }
                                    $old_rows[$id_shop] = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_."stock_available WHERE id_product='".(int) $id_product."' AND id_product_attribute='".(int) $id_product_attribute."' ".$where);
                                }
                            }
                            else
                            {
                                $old_rows[SCI::getConfigurationValue('PS_SHOP_DEFAULT')] = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_."stock_available WHERE id_product='".(int) $id_product."' AND id_product_attribute='".(int) $id_product_attribute."'");
                            }

                            $newQuantity = $quantity;
                            foreach (SCI::getSelectedShopActionList(false, $id_product) as $id_shop)
                            {
                                SCI::setQuantity($id_product, $id_product_attribute, $newQuantity, $id_shop);
                            }

                            if (version_compare(_PS_VERSION_, '8.0.0', '<'))
                            {
                                $todo[] = '`quantity`='.(int) $newQuantity;
                            }

                            if (version_compare(_PS_VERSION_, '1.7.2.0', '>='))
                            {
                                foreach ($old_rows as $id_shop => $old_row)
                                {
                                    if (!$old_row)
                                    {
                                        continue;
                                    }
                                    $sign = 1;
                                    if (empty($quantityUpdate))
                                    {
                                        $quantityUpdate = $quantity - $old_row['quantity'];
                                    }

                                    if ($quantityUpdate < 0)
                                    {
                                        $sign = -1;
                                        $quantityUpdate = $quantityUpdate * -1;
                                    }

                                    $stockMvt = new StockMvt();
                                    $stockMvt->id_stock = (int) $old_row['id_stock_available'];
                                    $stockMvt->id_stock_mvt_reason = SCI::getStockMvtEmployeeReasonId($sign);
                                    $stockMvt->id_employee = (int) $sc_agent->id_employee;
                                    $stockMvt->employee_lastname = $sc_agent->lastname;
                                    $stockMvt->employee_firstname = $sc_agent->firstname;
                                    $stockMvt->physical_quantity = (int) $quantityUpdate;
                                    $stockMvt->date_add = date('Y-m-d H:i:s');
                                    $stockMvt->sign = $sign;
                                    $stockMvt->price_te = 0;
                                    $stockMvt->last_wa = 0;
                                    $stockMvt->current_wa = 0;
                                    $stockMvt->add();
                                }
                            }

                            if (!empty($old_rows))
                            {
                                foreach ($old_rows as $old_id_shop => $old_values)
                                {
                                    if (!$old_values)
                                    {
                                        continue;
                                    }
                                    addToHistory('cat_prop_attr', 'modification', 'quantity', (int) $id_product_attribute, $id_lang, _DB_PREFIX_.'product_attribute', (int) $newQuantity, $old_values['quantity'], $old_id_shop);
                                }
                            }
                            else
                            {
                                addToHistory('cat_prop_attr', 'modification', 'quantity', (int) $id_product_attribute, $id_lang, _DB_PREFIX_.'product_attribute', (int) $newQuantity);
                            }
                        }
                        if ((isset($_POST['price']) || isset($_POST['priceextax'])) && ($updated_field == 'price' || $updated_field == 'priceextax'))
                        { // need tax rate?
                            $sql = 'SELECT t.rate,p.price,p.weight FROM `'._DB_PREFIX_.'product` p, `'._DB_PREFIX_.'tax` t WHERE p.id_product='.(int) $id_product.' AND t.id_tax=p.id_tax';
                            $sql = 'SELECT t.rate,p.price,p.weight
                                    FROM `'._DB_PREFIX_.'product` p
                                    LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group` AND tr.`id_country` = '.(int) SCI::getDefaultCountryId().' AND tr.`id_state` = 0)
                                LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
                                    WHERE p.id_product='.(int) $id_product;
                            $p = Db::getInstance()->getRow($sql);
                            $taxrate = $p['rate'] / 100 + 1;
                        }
                        if (isset($_POST['priceextax']) && $updated_field == 'priceextax')
                        { // excluding tax should be placed before including taxe for price round.
//                            $ecotax = (_s('CAT_PROD_ECOTAXINCLUDED') && isset($_POST['ecotax']) ? floatval($_POST['ecotax']) / $ecotaxrate : 0);
//                            $pecotax = (_s('CAT_PROD_ECOTAXINCLUDED') && isset($_POST['productecotax']) ? floatval($_POST['productecotax']) : 0);
                            $shoptodo[] = $todo[] = "`price`='".floatval((floatval(Tools::getValue('priceextax'))) - (floatval(Tools::getValue('productprice'))))."'";
                            addToHistory('cat_prop_attr', 'modification', 'price', (int) $id_product_attribute, 0, _DB_PREFIX_.'product_attribute', (floatval(Tools::getValue('priceextax')) - (floatval(Tools::getValue('productprice')))));
                        }
                        if (isset($_POST['ecotax']) && $updated_field == 'ecotax' && isset($_POST['ecotaxentered']) && Tools::getValue('ecotaxentered') == 1)
                        {
                            $todo[] = "`ecotax`='".(floatval(Tools::getValue('ecotax')) / $ecotaxrate)."'";
                            $shoptodo[] = "`ecotax`='".(floatval(Tools::getValue('ecotax')) / $ecotaxrate)."'";
                            addToHistory('cat_prop_attr', 'modification', 'ecotax', (int) $id_product_attribute, 0, _DB_PREFIX_.'product_attribute', floatval(Tools::getValue('ecotax')));
                        }
                        if (isset($_POST['weight']) && $updated_field == 'weight')
                        {
                            $todo[] = "`weight`='".(floatval(Tools::getValue('weight')) - (floatval(Tools::getValue('pweight'))))."'";
                            $shoptodo[] = "`weight`='".(floatval(Tools::getValue('weight')) - (floatval(Tools::getValue('pweight'))))."'";
                            addToHistory('cat_prop_attr', 'modification', 'weight', (int) $id_product_attribute, 0, _DB_PREFIX_.'product_attribute', (floatval(Tools::getValue('weight')) - (floatval(Tools::getValue('pweight')))));
                        }
                        if (isset($_POST['available_later']) && $updated_field == 'available_later' && SCI::getConfigurationValue('SC_DELIVERYDATE_INSTALLED') == '1')
                        {
                            if (Tools::getValue('available_later'))
                            {
                                $sql = 'SELECT id_sc_available_later FROM '._DB_PREFIX_."sc_available_later WHERE available_later='".pSQL(Tools::getValue('available_later'))."' AND id_lang='".(int) $id_lang."'";
                                $find_available_later = Db::getInstance()->executeS($sql);
                                if (!empty($find_available_later[0]['id_sc_available_later']))
                                {
                                    $_POST['available_later'] = $find_available_later[0]['id_sc_available_later'];
                                }
                                else
                                {
                                    $sql = 'INSERT INTO '._DB_PREFIX_."sc_available_later (id_lang, available_later) VALUES ('".(int) $id_lang."', '".pSQL(Tools::getValue('available_later'))."')";
                                    Db::getInstance()->execute($sql);
                                    $_POST['available_later'] = Db::getInstance()->Insert_ID();
                                }
                            }
                            else
                            {
                                $_POST['available_later'] = 0;
                            }

                            $todo[] = '`id_sc_available_later`='.(int) Tools::getValue('available_later');
                        }

                        if (isset($_POST['out_of_stock']) && $updated_field == 'out_of_stock')
                        {
                            $sql = 'UPDATE `'.bqSQL(_DB_PREFIX_.'stock_available').'` 
                                                            SET `out_of_stock` = '.(int) Tools::getValue('out_of_stock').' 
                                                            WHERE id_product_attribute = '.(int) $id_product_attribute.' 
                                                            AND `id_shop` IN ('.pInSQL(SCI::getSelectedShopActionList(true)).')';
                            Db::getInstance()->execute($sql);
                        }

                        if (count($todo))
                        {
                            $sql = 'UPDATE '._DB_PREFIX_.'product_attribute SET `date_upd`=NOW(),'.join(' , ', $todo).' WHERE id_product_attribute='.(int) $id_product_attribute;
                            Db::getInstance()->execute($sql);

                            if ($doHookUpdateQuantity && isset($newQuantity)
                                && version_compare(_PS_VERSION_, '8.0.0', '<'))
                            { ## passe par la fonction SCi
                                if (!_s('APP_COMPAT_EBAY'))
                                {
                                    if (_s('APP_COMPAT_HOOK'))
                                    {
                                        SCI::hookExec('actionUpdateQuantity',
                                            [
                                            'id_product' => $id_product,
                                            'id_product_attribute' => $id_product_attribute,
                                            'quantity' => $newQuantity,
                                            ]
                                        );
                                    }
                                }
                            }
                        }

                        if (version_compare(_PS_VERSION_, '8.0.0', '<')
                            && ((isset($_POST['quantityupdate']) || isset($_POST['quantity'])) && ($updated_field == 'quantityupdate' || $updated_field == 'quantity')))
                        {
                            Db::getInstance()->execute('
                            UPDATE `'._DB_PREFIX_.'product`
                            SET `quantity` =
                                (
                                SELECT SUM(`quantity`)
                                FROM `'._DB_PREFIX_.'product_attribute`
                                WHERE `id_product` = '.(int) $id_product.'
                                )
                            WHERE `id_product` = '.(int) $id_product);
                        }

                        foreach ($shopfields as $field)
                        {
                            if (isset($_POST[$field]) && $updated_field == $field)
                            {
                                if ($field == 'default_on')
                                {
                                    $val = Tools::getValue($field);
                                    if (empty($val))
                                    {
                                        $val = 'NULL';
                                    }
                                    else
                                    {
                                        $val = (int) $val;
                                    }
                                    $shoptodo[] = '`'.bqSQL($field).'`='.$val;
                                }
                                else
                                {
                                    $shoptodo[] = psql($field)."='".psql(Tools::getValue($field))."'";
                                }
                            }
                        }
                        if (count($shoptodo))
                        {
                            $sql = 'UPDATE '._DB_PREFIX_.'product_attribute_shop SET '.join(' , ', $shoptodo).' WHERE id_product_attribute='.(int) $id_product_attribute.' AND id_shop IN ('.pInSQL(SCI::getSelectedShopActionList(true)).')';
                            Db::getInstance()->execute($sql);
                        }
                        if (isset($_POST['supplier_reference']) && $updated_field == 'supplier_reference')
                        {
                            $sql = 'SELECT id_supplier FROM '._DB_PREFIX_.'product WHERE id_product='.(int) $id_product;
                            $row = Db::getInstance()->getRow($sql);
                            $id_supplier = (int) $row['id_supplier'];

                            if ($id_supplier > 0)
                            {
                                $id_product_supplier = (int) ProductSupplier::getIdByProductAndSupplier((int) $id_product, (int) $id_product_attribute, (int) $id_supplier);

                                if (!$id_product_supplier)
                                {
                                    //create new record
                                    $product_supplier_entity = new ProductSupplier();
                                    $product_supplier_entity->id_product = (int) $id_product;
                                    $product_supplier_entity->id_product_attribute = (int) $id_product_attribute;
                                    $product_supplier_entity->id_supplier = (int) $id_supplier;
                                    $product_supplier_entity->product_supplier_reference = psql(Tools::getValue('supplier_reference'));
                                    $product_supplier_entity->product_supplier_price_te = 0;
                                    $product_supplier_entity->id_currency = 0;
                                    $product_supplier_entity->save();
                                }
                                else
                                {
                                    $product_supplier = new ProductSupplier((int) $id_product_supplier);
                                    $product_supplier->product_supplier_reference = psql(Tools::getValue('supplier_reference'));
                                    $product_supplier->update();
                                }
                            }
                        }
                        if (isset($_POST['wholesale_price']) && $updated_field == 'wholesale_price' && _s('CAT_PROD_WHOLESALEPRICE_SUPPLIER') == 1)
                        {
                            $sql = 'SELECT id_supplier FROM '._DB_PREFIX_.'product WHERE id_product='.(int) $id_product;
                            $row = Db::getInstance()->getRow($sql);
                            $id_supplier = (int) $row['id_supplier'];

                            if ($id_supplier > 0)
                            {
                                $id_product_supplier = (int) ProductSupplier::getIdByProductAndSupplier((int) $id_product, (int) $id_product_attribute, (int) $id_supplier);

                                if (!$id_product_supplier)
                                {
                                    //create new record
                                    $product_supplier_entity = new ProductSupplier();
                                    $product_supplier_entity->id_product = (int) $id_product;
                                    $product_supplier_entity->id_product_attribute = (int) $id_product_attribute;
                                    $product_supplier_entity->id_supplier = (int) $id_supplier;
                                    $product_supplier_entity->product_supplier_price_te = psql(Tools::getValue('wholesale_price'));
                                    $product_supplier_entity->id_currency = 0;
                                    $product_supplier_entity->save();
                                }
                                else
                                {
                                    $product_supplier_entity = new ProductSupplier((int) $id_product_supplier);
                                    $product_supplier_entity->product_supplier_price_te = psql(Tools::getValue('wholesale_price'));
                                    $product_supplier_entity->update();
                                }

                                $wholesale_price = $product_supplier_entity->product_supplier_price_te;
                                $sql = 'UPDATE '._DB_PREFIX_.'product_attribute_shop
                                            SET wholesale_price = '.(float) $wholesale_price.'
                                            WHERE id_product_attribute = '.(int) $id_product_attribute;
                                $sql .= (_s('CAT_PROD_WHOLESALEPRICE_SAVING_METHOD') == 1 ? ' AND id_shop IN ('.pInSQL(SCI::getSelectedShopActionList(true, $id_product)).')' : '');
                                Db::getInstance()->execute($sql);
                            }
                        }

                        $deleted = false;
                        foreach ($_POST as $key => $value)
                        {
                            $sub = substr($key, 0, 5);
                            if ($sub == 'attr_' && $key != 'attr_ids')
                            {
                                if (!$deleted)
                                {
                                    $sql = 'DELETE FROM '._DB_PREFIX_."product_attribute_combination WHERE id_product_attribute='".(int) $id_product_attribute."'";
                                    Db::getInstance()->execute($sql);
                                    $deleted = true;
                                }

                                if (!is_numeric($value))
                                {
                                    $exp = explode('|||', $value);
                                    if (!empty($exp[1]))
                                    {
                                        $value = $exp[1];
                                    }
                                    else
                                    {
                                        $value = ''; ## evite d'insérer une valeur qui sera à 0;
                                    }
                                }
                                else
                                {
                                    $value = '';
                                }
                                if (!empty($value))
                                {
                                    $sql = 'INSERT INTO '._DB_PREFIX_."product_attribute_combination (id_product_attribute, id_attribute)
                                VALUES ('".(int) $id_product_attribute."','".(int) $value."')";
                                    Db::getInstance()->execute($sql);
                                }
                            }
                        }

                        if (!_s('APP_COMPAT_EBAY'))
                        {
                            if (_s('APP_COMPAT_HOOK'))
                            {
                                SCI::hookExec('updateProductAttribute', ['id_product_attribute' => (int) $id_product_attribute, 'product' => new Product((int) $id_product)]);
                            }
                        }
                        elseif (_s('APP_COMPAT_EBAY'))
                        {
                            Configuration::updateValue('EBAY_SYNC_LAST_PRODUCT', min(Configuration::get('EBAY_SYNC_LAST_PRODUCT'), (int) $id_product));
                        }

                        SC_Ext::readCustomCombinationsGridConfigXML('onAfterUpdateSQL');
                    }
                }

                SC_Ext::readCustomCombinationsGridConfigXML('extraVars');

                QueueLog::delete(($log_ids[$num]));
            }
        }

        if (!empty($updated_products))
        {
            foreach ($updated_products as $id_product)
            {
                Db::getInstance()->execute('UPDATE '._DB_PREFIX_."product SET `date_upd`='".psql(date('Y-m-d H:i:s'))."' WHERE id_product=".(int) $id_product);
                Db::getInstance()->execute('UPDATE '._DB_PREFIX_."product_shop SET `date_upd`='".psql(date('Y-m-d H:i:s'))."' WHERE id_product=".(int) $id_product.' AND id_shop IN ('.pInSQL(SCI::getSelectedShopActionList(true)).')');
            }

            if (_s('CAT_APPLY_ALL_CART_RULES'))
            {
                SpecificPriceRule::applyAllRules($updated_products);
            }
            // PM Cache
            ExtensionPMCM::clearFromIdsProduct($updated_products);
        }

        // RETURN
        $return = json_encode(['callback' => $callbacks]);
    }
}
echo $return;
