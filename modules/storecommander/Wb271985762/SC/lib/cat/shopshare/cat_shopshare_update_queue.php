<?php
if (!defined('STORE_COMMANDER')) { exit; }

@error_reporting(E_ERROR | E_PARSE);
@ini_set('display_errors', 'ON');

$id_lang = Tools::getValue('id_lang', '0');
$action = Tools::getValue('action', '');

$return = 'ERROR: Try again later';

// FUNCTIONS
$updated_products = array();


// Récupération de toutes les modifications à effectuer
if (Tools::getValue('rows') || $action == 'insert')
{
    if ($action != 'insert')
    {
        if (_PS_MAGIC_QUOTES_GPC_)
        {
            $_POST['rows'] = Tools::getValue('rows');
        }
        $rows = json_decode($_POST['rows']);
    }
    else
    {
        $rows = array();
        $rows[0] = new stdClass();
        $rows[0]->name = Tools::getValue('act', '');
        $rows[0]->action = Tools::getValue('action', '');
        $rows[0]->row = Tools::getValue('gr_id', '');
        $rows[0]->callback = Tools::getValue('callback', '');
        $rows[0]->params = $_POST;
    }

    if (is_array($rows) && count($rows) > 0)
    {
        $callbacks = '';

        // Première boucle pour remplir la table sc_queue_log
        // avec toutes ces modifications
        $log_ids = array();
        $date = date('Y-m-d H:i:s');
        foreach ($rows as $num => $row)
        {
            $id = QueueLog::add($row->name, $row->row, $row->action, (!empty($row->params) ? $row->params : array()), (!empty($row->callback) ? $row->callback : null), $date);
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

                if ($action != 'insert')
                {
                    $_POST = array();
                    $_POST = (array) json_decode($row->params);
                }
                if (empty($gr_id))
                {
                    $gr_id = Tools::getValue('gr_id', '0');
                }

                if (!empty($action) && $action == 'update' && !empty($gr_id))
                {
                    $idlist = $gr_id;
                    $action_upd = Tools::getValue('action_upd', '');
                    $id_lang = Tools::getValue('id_lang', '0');
                    $id_shop = Tools::getValue('id_shop', '0');
                    $id_actual_shop = SCI::getSelectedShop();
                    $value = Tools::getValue('value', '0');
                    $auto_share_imgs = (int) Tools::getValue('auto_share_imgs', '0');

                    if ($value == 'true')
                    {
                        $value = 1;
                    }
                    else
                    {
                        $value = 0;
                    }

                    $ids = explode(',', $idlist);

                    if ($action_upd != '' && !empty($id_shop) && !empty($idlist))
                    {
                        if (!empty($ids) && count($ids) == 1)
                        {
                            $updated_products[$ids[0]] = $ids;
                        }
                        elseif (!empty($ids) && count($ids) > 1)
                        {
                            $updated_products = array_merge($updated_products, $ids);
                        }
                        switch ($action_upd) {
                            // Modification de active pour le shop passé en params
                            // pour un ou plusieurs products passés en params
                            case 'active':
                                foreach ($ids as $id)
                                {
                                    $product = new Product($id, false, null, $id_actual_shop);
                                    if (!$product->isAssociatedToShop($id_shop))
                                    {
                                        $product->id_shop_list = array($id_shop);
                                        $product->active = (int) $value;
                                        $product->save();

                                        $sql = 'UPDATE `'._DB_PREFIX_.'product_shop` ps_final,`'._DB_PREFIX_.'product_shop` ps_source
                                        SET ps_final.active = "'.(int) _s('CAT_PROD_AUTO_ACTIVATION_MB_SHARE').'",
                                            ps_final.unit_price_ratio = ps_source.unit_price_ratio
                                        WHERE ps_final.`id_product` = '.(int) $id.' AND ps_source.`id_product` = '.(int) $id.'
                                        AND ps_final.`id_shop` = '.(int) $id_shop.' AND ps_source.`id_shop` = '.(int) $id_actual_shop;
                                        Db::getInstance()->execute($sql);

                                        $sql = 'SELECT pa.id_product_attribute
                                        FROM `'._DB_PREFIX_.'product_attribute` pa
                                        WHERE pa.`id_product` = '.(int) $id;
                                        $product_attrs = Db::getInstance()->executeS($sql);
                                        foreach ($product_attrs as $product_attr)
                                        {
                                            $combination = new Combination($product_attr['id_product_attribute'], null, $id_actual_shop);
                                            $combination->id_product = $id;
                                            $combination->id_shop_list = array($id_shop);
                                            $combination->minimal_quantity = max(1, (int) $combination->minimal_quantity);
                                            $combination->save();
                                        }

                                        if ($auto_share_imgs)
                                        {
                                            $sql = 'SELECT pis.id_image
                                            FROM `'._DB_PREFIX_.'image_shop` pis
                                                INNER JOIN `'._DB_PREFIX_.'image` pi ON pi.id_image = pis.id_image
                                            WHERE pi.`id_product` = '.(int) $id.'
                                                AND pis.id_shop = "'.(int) $id_actual_shop.'"
                                            GROUP BY pis.id_image';
                                            $images = Db::getInstance()->executeS($sql);
                                            foreach ($images as $image)
                                            {
                                                SCI::duplicateImageToShops($image['id_image'], $id_actual_shop, array($id_shop));
                                            }
                                        }

                                        if (SCAS)
                                        {
                                            $type_advanced_stock_management = 1; // Not Advanced Stock Management
                                            if ($product->advanced_stock_management == 1)
                                            {
                                                $type_advanced_stock_management = 2; // With Advanced Stock Management
                                                if (!StockAvailable::dependsOnStock((int) $id, $id_actual_shop))
                                                {
                                                    $type_advanced_stock_management = 3;
                                                }// With Advanced Stock Management + Manual management
                                            }
                                            if ($type_advanced_stock_management == 2)
                                            {
                                                StockAvailable::setProductDependsOnStock((int) $id, true, $id_shop);
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $sql = 'UPDATE `'._DB_PREFIX_."product_shop` SET active='".(int) $value."' WHERE `id_product` = ".(int) $id.' AND id_shop = '.(int) $id_shop;
                                        Db::getInstance()->Execute($sql);
                                    }
                                }
                                break;
                                // Modification de present pour le shop passé en params
                                // pour un ou plusieurs products passés en params
                            case 'present':
                                foreach ($ids as $id)
                                {
                                    $product = new Product($id, false, null, $id_actual_shop);

                                    if (!$product->isAssociatedToShop($id_shop) && $value == '1')
                                    {
                                        $product->id_shop_list = array($id_shop);
                                        $product->price = floatval($product->price);
                                        $product->save();
                                        SCI::setQuantity($id, null, StockAvailable::getQuantityAvailableByProduct($id, null, $id_actual_shop), $id_shop);

                                        $sql = 'UPDATE `'._DB_PREFIX_.'product_shop` ps_final,`'._DB_PREFIX_.'product_shop` ps_source
                                        SET ps_final.active = "'.(int) _s('CAT_PROD_AUTO_ACTIVATION_MB_SHARE').'",
                                            ps_final.unit_price_ratio = ps_source.unit_price_ratio
                                        WHERE ps_final.`id_product` = '.(int) $id.' AND ps_source.`id_product` = '.(int) $id.'
                                        AND ps_final.`id_shop` = '.(int) $id_shop.' AND ps_source.`id_shop` = '.(int) $id_actual_shop;
                                        Db::getInstance()->execute($sql);

                                        $sql = 'SELECT pa.id_product_attribute
                                        FROM `'._DB_PREFIX_.'product_attribute` pa
                                        WHERE pa.`id_product` = '.(int) $id;
                                        $product_attrs = Db::getInstance()->executeS($sql);

                                        Shop::setContext(Shop::CONTEXT_SHOP, $id_actual_shop);
                                        foreach ($product_attrs as $product_attr)
                                        {
                                            $combination = new Combination($product_attr['id_product_attribute'], null, $id_actual_shop);
                                            $combination->id_product = $id;
                                            $combination->id_shop_list = array((int) $id_shop);
                                            $combination->minimal_quantity = max(1, (int) $combination->minimal_quantity);
                                            $combination->save();
                                        }

                                        if ($auto_share_imgs)
                                        {
                                            $sql = 'SELECT pis.id_image
                                            FROM `'._DB_PREFIX_.'image_shop` pis
                                                INNER JOIN `'._DB_PREFIX_.'image` pi ON pi.id_image = pis.id_image
                                            WHERE pi.`id_product` = '.(int) $id.'
                                                AND pis.id_shop = "'.(int) $id_actual_shop.'"
                                            GROUP BY pis.id_image';
                                            $images = Db::getInstance()->executeS($sql);
                                            foreach ($images as $image)
                                            {
                                                SCI::duplicateImageToShops($image['id_image'], $id_actual_shop, array($id_shop));
                                            }
                                        }

                                        if (SCAS)
                                        {
                                            $type_advanced_stock_management = 1; // Not Advanced Stock Management
                                            if ($product->advanced_stock_management == 1)
                                            {
                                                $type_advanced_stock_management = 2; // With Advanced Stock Management
                                                if (!StockAvailable::dependsOnStock((int) $id, $id_actual_shop))
                                                {
                                                    $type_advanced_stock_management = 3;
                                                }// With Advanced Stock Management + Manual management
                                            }
                                            if ($type_advanced_stock_management == 2)
                                            {
                                                StockAvailable::setProductDependsOnStock((int) $id, true, $id_shop);
                                            }
                                        }
                                    }
                                    elseif ($product->isAssociatedToShop($id_shop) && empty($value))
                                    {
                                        if ($id_shop != $product->id_shop_default)
                                        {
                                            SCI::removeProductFromShop($id, $id_shop);
                                        }
                                    }
                                }
                                break;
                                // Modification la boutique par défaut
                                // pour un ou plusieurs products passés en params
                            case 'default':
                                foreach ($ids as $id)
                                {
                                    $product = new Product($id, null, null, $id_actual_shop);

                                    if (!$product->isAssociatedToShop($id_shop))
                                    {
                                        $product->id_shop_list = array($id_shop);
                                        $product->save();

                                        $sql = 'SELECT pa.id_product_attribute
                                        FROM `'._DB_PREFIX_.'product_attribute` pa
                                        WHERE pa.`id_product` = '.(int) $id;
                                        $product_attrs = Db::getInstance()->executeS($sql);
                                        foreach ($product_attrs as $product_attr)
                                        {
                                            $combination = new Combination($product_attr['id_product_attribute'], null, $id_actual_shop);
                                            $combination->id_product = $id;
                                            $combination->id_shop_list = array($id_shop);
                                            $combination->minimal_quantity = max(1, (int) $combination->minimal_quantity);
                                            $combination->save();
                                        }

                                        if ($auto_share_imgs)
                                        {
                                            $sql = 'SELECT pis.id_image
                                            FROM `'._DB_PREFIX_.'image_shop` pis
                                                INNER JOIN `'._DB_PREFIX_.'image` pi ON pi.id_image = pis.id_image
                                            WHERE pi.`id_product` = '.(int) $id.'
                                                AND pis.id_shop = "'.(int) $id_actual_shop.'"
                                            GROUP BY pis.id_image';
                                            $images = Db::getInstance()->executeS($sql);
                                            foreach ($images as $image)
                                            {
                                                SCI::duplicateImageToShops($image['id_image'], $id_actual_shop, array($id_shop));
                                            }
                                        }

                                        if (SCAS)
                                        {
                                            $type_advanced_stock_management = 1; // Not Advanced Stock Management
                                            if ($product->advanced_stock_management == 1)
                                            {
                                                $type_advanced_stock_management = 2; // With Advanced Stock Management
                                                if (!StockAvailable::dependsOnStock((int) $id, $id_actual_shop))
                                                {
                                                    $type_advanced_stock_management = 3;
                                                }// With Advanced Stock Management + Manual management
                                            }
                                            if ($type_advanced_stock_management == 2)
                                            {
                                                StockAvailable::setProductDependsOnStock((int) $id, true, $id_shop);
                                            }
                                        }
                                    }

                                    Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'product` SET id_shop_default = '.(int) $id_shop.' WHERE id_product = '.(int) $id);

                                    $sql = 'UPDATE `'._DB_PREFIX_.'product_shop` ps_final,`'._DB_PREFIX_.'product_shop` ps_source
                                    SET ps_final.active = "'.(int) _s('CAT_PROD_AUTO_ACTIVATION_MB_SHARE').'",
                                        ps_final.unit_price_ratio = ps_source.unit_price_ratio
                                    WHERE ps_final.`id_product` = '.(int) $id.' AND ps_source.`id_product` = '.(int) $id.'
                                    AND ps_final.`id_shop` = '.(int) $id_shop.' AND ps_source.`id_shop` = '.(int) $id_actual_shop;
                                    Db::getInstance()->execute($sql);

                                    $images = array();
                                    $image_temps = $product->getImages($id_lang);
                                    foreach ($image_temps as $image_temp)
                                    {
                                        if (!empty($image_temp['id_image']))
                                        {
                                            $images[] = $image_temp['id_image'];
                                        }
                                    }
                                    if (!empty($images) && count($images) > 0)
                                    {
                                        SCI::addToShops('image', $images, array($id_shop));
                                    }
                                }
                                break;
                                // Modification de present
                                // pour un ou plusieurs shops passés en params
                                // pour un ou plusieurs products passés en params
                            case 'mass_present':
                                $shops = explode(',', $id_shop);
                                foreach ($shops as $id_shop)
                                {
                                    foreach ($ids as $id)
                                    {
                                        $product = new Product($id, false, null, $id_actual_shop);

                                        if (!$product->isAssociatedToShop($id_shop) && $value == '1')
                                        {
                                            $product->id_shop_list = array($id_shop);
                                            $product->price = floatval($product->price);
                                            $product->save();
                                            SCI::setQuantity($id, null, StockAvailable::getQuantityAvailableByProduct($id, null, $id_actual_shop), $id_shop);

                                            $sql = 'UPDATE `'._DB_PREFIX_.'product_shop` ps_final,`'._DB_PREFIX_.'product_shop` ps_source
                                            SET ps_final.active = "'.(int) _s('CAT_PROD_AUTO_ACTIVATION_MB_SHARE').'",
                                                ps_final.unit_price_ratio = ps_source.unit_price_ratio
                                            WHERE ps_final.`id_product` = '.(int) $id.' AND ps_source.`id_product` = '.(int) $id.'
                                            AND ps_final.`id_shop` = '.(int) $id_shop.' AND ps_source.`id_shop` = '.(int) $id_actual_shop;
                                            Db::getInstance()->execute($sql);

                                            $sql = 'SELECT pa.id_product_attribute
                                            FROM `'._DB_PREFIX_.'product_attribute` pa
                                            WHERE pa.`id_product` = '.(int) $id;
                                            $product_attrs = Db::getInstance()->executeS($sql);
                                            foreach ($product_attrs as $product_attr)
                                            {
                                                $combination = new Combination($product_attr['id_product_attribute'], null, $id_actual_shop);
                                                $combination->id_product = $id;
                                                $combination->id_shop_list = array($id_shop);
                                                $combination->minimal_quantity = max(1, (int) $combination->minimal_quantity);
                                                $combination->save();
                                            }

                                            if ($auto_share_imgs)
                                            {
                                                $sql = 'SELECT pis.id_image
                                                FROM `'._DB_PREFIX_.'image_shop` pis
                                                    INNER JOIN `'._DB_PREFIX_.'image` pi ON pi.id_image = pis.id_image
                                                WHERE pi.`id_product` = '.(int) $id.'
                                                    AND pis.id_shop = "'.(int) $id_actual_shop.'"
                                                GROUP BY pis.id_image';
                                                $images = Db::getInstance()->executeS($sql);
                                                foreach ($images as $image)
                                                {
                                                    SCI::duplicateImageToShops($image['id_image'], $id_actual_shop, array($id_shop));
                                                }
                                            }

                                            if (SCAS)
                                            {
                                                $type_advanced_stock_management = 1; // Not Advanced Stock Management
                                                if ($product->advanced_stock_management == 1)
                                                {
                                                    $type_advanced_stock_management = 2; // With Advanced Stock Management
                                                    if (!StockAvailable::dependsOnStock((int) $id, $id_actual_shop))
                                                    {
                                                        $type_advanced_stock_management = 3;
                                                    }// With Advanced Stock Management + Manual management
                                                }
                                                if ($type_advanced_stock_management == 2)
                                                {
                                                    StockAvailable::setProductDependsOnStock((int) $id, true, $id_shop);
                                                }
                                            }
                                        }
                                        elseif ($product->isAssociatedToShop($id_shop) && empty($value))
                                        {
                                            if ($id_shop != $product->id_shop_default)
                                            {
                                                SCI::removeProductFromShop($id, $id_shop);
                                            }
                                        }
                                    }
                                }
                                break;
                                // Modification de active
                                // pour un ou plusieurs shops passés en params
                                // pour un ou plusieurs products passés en params
                            case 'mass_active':
                                $shops = explode(',', $id_shop);
                                foreach ($shops as $shop)
                                {
                                    foreach ($ids as $id)
                                    {
                                        $product = new Product($id, false, null, $id_actual_shop);
                                        if (!$product->isAssociatedToShop($shop))
                                        {
                                            $product->id_shop_list = array($shop);
                                            $product->active = (int) $value;
                                            $product->save();

                                            $sql = 'UPDATE `'._DB_PREFIX_.'product_shop` ps_final,`'._DB_PREFIX_.'product_shop` ps_source
                                            SET ps_final.active = "'.(int) _s('CAT_PROD_AUTO_ACTIVATION_MB_SHARE').'",
                                                ps_final.unit_price_ratio = ps_source.unit_price_ratio
                                            WHERE ps_final.`id_product` = '.(int) $id.' AND ps_source.`id_product` = '.(int) $id.'
                                            AND ps_final.`id_shop` = '.(int) $id_shop.' AND ps_source.`id_shop` = '.(int) $id_actual_shop;
                                            Db::getInstance()->execute($sql);

                                            $sql = 'SELECT pa.id_product_attribute
                                            FROM `'._DB_PREFIX_.'product_attribute` pa
                                            WHERE pa.`id_product` = '.(int) $id;
                                            $product_attrs = Db::getInstance()->executeS($sql);
                                            foreach ($product_attrs as $product_attr)
                                            {
                                                $combination = new Combination($product_attr['id_product_attribute'], null, $id_actual_shop);
                                                $combination->id_product = $id;
                                                $combination->id_shop_list = array($shop);
                                                $combination->minimal_quantity = max(1, (int) $combination->minimal_quantity);
                                                $combination->save();
                                            }

                                            if ($auto_share_imgs)
                                            {
                                                $sql = 'SELECT pis.id_image
                                                FROM `'._DB_PREFIX_.'image_shop` pis
                                                    INNER JOIN `'._DB_PREFIX_.'image` pi ON pi.id_image = pis.id_image
                                                WHERE pi.`id_product` = '.(int) $id.'
                                                    AND pis.id_shop = "'.(int) $id_actual_shop.'"
                                                GROUP BY pis.id_image';
                                                $images = Db::getInstance()->executeS($sql);
                                                foreach ($images as $image)
                                                {
                                                    SCI::duplicateImageToShops($image['id_image'], $id_actual_shop, array($shop));
                                                }
                                            }

                                            if (SCAS)
                                            {
                                                $type_advanced_stock_management = 1; // Not Advanced Stock Management
                                                if ($product->advanced_stock_management == 1)
                                                {
                                                    $type_advanced_stock_management = 2; // With Advanced Stock Management
                                                    if (!StockAvailable::dependsOnStock((int) $id, $id_actual_shop))
                                                    {
                                                        $type_advanced_stock_management = 3;
                                                    }// With Advanced Stock Management + Manual management
                                                }
                                                if ($type_advanced_stock_management == 2)
                                                {
                                                    StockAvailable::setProductDependsOnStock((int) $id, true, $id_shop);
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $sql = 'UPDATE `'._DB_PREFIX_."product_shop` SET active='".(int) $value."' WHERE `id_product` = ".(int) $id.' AND id_shop = '.(int) $shop;
                                            Db::getInstance()->Execute($sql);
                                        }
                                    }
                                }
                                break;
                        }
                    }
                }

                QueueLog::delete(($log_ids[$num]));
            }
        }

        // PM Cache
        if (!empty($updated_products))
        {
            ExtensionPMCM::clearFromIdsProduct($updated_products);
        }

        // RETURN
        $return = json_encode(array('callback' => $callbacks));
    }
}
echo $return;
