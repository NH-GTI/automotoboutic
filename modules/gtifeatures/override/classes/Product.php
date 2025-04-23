<?php
/** 
 * GTI Features
 * JOIN table ps_ndk_customized_data to get an easier management of naming, orgering and display than native PS customized datas.
 * 
 */

class Product extends ProductCore
{
    /**
     * Customization management
     * 
     */
    public static function getAllCustomizedDatas($id_cart, $id_lang = null, $only_in_cart = true, $id_shop = null, $id_customization = null)
    {
        if (Configuration::get('GTIFEATURES_USE_NDK_EXTENDED_DATA')) {
            return self::getAllCustomizedDatasExtended($id_cart, $id_lang, $only_in_cart, $id_shop, $id_customization);
        }
        
        return parent::getAllCustomizedDatas($id_cart, $id_lang, $only_in_cart, $id_shop, $id_customization);
    }

    protected static function getAllCustomizedDatasExtended($id_cart, $id_lang, $only_in_cart, $id_shop, $id_customization) {
        if (!Customization::isFeatureActive()) {
            return false;
        }

        // No need to query if there isn't any real cart!
        if (!$id_cart) {
            return false;
        }

        if ($id_customization === 0) {
            // Backward compatibility: check if there are no products in cart with specific `id_customization` before returning false
            $product_customizations = (int) Db::getInstance()->getValue('
                SELECT COUNT(`id_customization`) FROM `' . _DB_PREFIX_ . 'cart_product`
                WHERE `id_cart` = ' . (int) $id_cart .
                ' AND `id_customization` != 0');
            if ($product_customizations) {
                return false;
            }
        }

        if (!$id_lang) {
            $id_lang = Context::getContext()->language->id;
        }
        if (Shop::isFeatureActive() && !$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        $sql = '
        SELECT cd.`id_customization`, c.`id_address_delivery`, c.`id_product`, cfl.`id_customization_field`, c.`id_product_attribute`,
            cd.`type`, cd.`index`, cd.`value`, cd.`id_module`, cfl.`name`, cde.`admin_name`, cde.`display`, cde.`display_index`
        FROM `' . _DB_PREFIX_ . 'customized_data` cd
        NATURAL JOIN `' . _DB_PREFIX_ . 'ndk_customized_data_extended` cde
        NATURAL JOIN `' . _DB_PREFIX_ . 'customization` c
        LEFT JOIN `' . _DB_PREFIX_ . 'customization_field_lang` cfl ON (cfl.id_customization_field = cd.`index` AND id_lang = ' . (int) $id_lang .
            ($id_shop ? ' AND cfl.`id_shop` = ' . (int) $id_shop : '') . ')
        WHERE c.`id_cart` = ' . (int) $id_cart .
        ($only_in_cart ? ' AND c.`in_cart` = 1' : '') .
        ((int) $id_customization ? ' AND cd.`id_customization` = ' . (int) $id_customization : '') .
        ' ORDER BY `id_product`,`display_index`,`index`';

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        $customized_datas = [];

        foreach ($result as $row) {
            if ((int) $row['id_module'] && (int) $row['type'] == Product::CUSTOMIZE_TEXTFIELD) {
                // Hook displayCustomization: Call only the module in question
                // When a module saves a customization programmatically, it should add its ID in the `id_module` column
                $row['value'] = Hook::exec('displayCustomization', ['customization' => $row], (int) $row['id_module']);
            }
            $customized_datas[(int) $row['id_product']][(int) $row['id_product_attribute']][(int) $row['id_address_delivery']][(int) $row['id_customization']]['datas'][(int) $row['type']][] = $row;
        }

        if (!$result = Db::getInstance()->executeS(
            'SELECT `id_product`, `id_product_attribute`, `id_customization`, `id_address_delivery`, `quantity`, `quantity_refunded`, `quantity_returned`
            FROM `' . _DB_PREFIX_ . 'customization`
            WHERE `id_cart` = ' . (int) $id_cart .
            ((int) $id_customization ? ' AND `id_customization` = ' . (int) $id_customization : '') .
            ($only_in_cart ? ' AND `in_cart` = 1' : '')
        )) {
            return false;
        }

        foreach ($result as $row) {
            $customized_datas[(int) $row['id_product']][(int) $row['id_product_attribute']][(int) $row['id_address_delivery']][(int) $row['id_customization']]['quantity'] = (int) $row['quantity'];
            $customized_datas[(int) $row['id_product']][(int) $row['id_product_attribute']][(int) $row['id_address_delivery']][(int) $row['id_customization']]['quantity_refunded'] = (int) $row['quantity_refunded'];
            $customized_datas[(int) $row['id_product']][(int) $row['id_product_attribute']][(int) $row['id_address_delivery']][(int) $row['id_customization']]['quantity_returned'] = (int) $row['quantity_returned'];
            $customized_datas[(int) $row['id_product']][(int) $row['id_product_attribute']][(int) $row['id_address_delivery']][(int) $row['id_customization']]['id_customization'] = (int) $row['id_customization'];
        }

        return $customized_datas;
    }
}
