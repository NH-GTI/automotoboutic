<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

    $shop_id = SCI::getSelectedShop();
    if (empty($shop_id))
    {
        $shop_id = Configuration::get('PS_SHOP_DEFAULT');
    }
    $shop = new Shop($shop_id);
    $shop_group = $shop->getGroup();

    $id_lang = (int) Tools::getValue('id_lang');
    $view = Tools::getValue('view', 'grid_light');
    $period = Tools::getValue('period', '3months');
    $filter_params = Tools::getValue('filter_params', '');
    $status = Tools::getValue('status', '');
    $explodePacks = (bool) Tools::getValue('explodePacks', '0');
    $statusFilter = explode(',', $status);

    $lang_setting = _s('ORD_LANG_PRODUCT_NAME');
    if (!empty($lang_setting))
    {
        if ($lang_setting == '2')
        {
            $id_lang_shop = Configuration::get('PS_LANG_DEFAULT', null, $shop_id, $shop->id_shop_group);
            if (!empty($id_lang_shop))
            {
                $id_lang = $id_lang_shop;
            }
        }
        elseif ($lang_setting != '1')
        {
            $id_lang_wanted = Language::getIdByIso(strtolower($lang_setting));
            if (!empty($id_lang_wanted))
            {
                $id_lang = $id_lang_wanted;
            }
        }
    }

    $periods = [
                                '1days' => ' AND o.date_add >= "'.pSQL(date('Y-m-d')).' 00:00:00" ',
                                '2days' => ' AND TO_DAYS(NOW()) - TO_DAYS(o.date_add) < 2',
                                '3days' => ' AND TO_DAYS(NOW()) - TO_DAYS(o.date_add) < 3',
                                '5days' => ' AND TO_DAYS(NOW()) - TO_DAYS(o.date_add) < 5',
                                '10days' => ' AND TO_DAYS(NOW()) - TO_DAYS(o.date_add) < 10',
                                '15days' => ' AND TO_DAYS(NOW()) - TO_DAYS(o.date_add) < 15',
                                '30days' => ' AND TO_DAYS(NOW()) - TO_DAYS(o.date_add) < 30',
                                '3months' => ' AND DATE_SUB(NOW(), INTERVAL 3 MONTH) < o.date_add',
                                '6months' => ' AND DATE_SUB(NOW(), INTERVAL 6 MONTH) < o.date_add',
                                '1year' => ' AND DATE_SUB(NOW(), INTERVAL 1 YEAR) < o.date_add',
                                'all' => '',
                                ];
    foreach ($statusFilter as $k => $s)
    {
        if (sc_array_key_exists($s, $periods))
        {
            unset($statusFilter[$k]);
        }
        if ($s == 'status')
        {
            unset($statusFilter[$k]);
        }
    }
    if (isset($statusFilter[0]) && $statusFilter[0] == '')
    {
        unset($statusFilter[0]);
    }

    $current_id_segment = Tools::getValue('id_segment', 0);

    $grids = SCI::getGridViews('order');
    SC_Ext::readCustomOrdersGridsConfigXML('gridConfig');

    $cdata = (isset($_COOKIE['cg_ord_treegrid_col_'.$view]) ? $_COOKIE['cg_ord_treegrid_col_'.$view] : '');
    //check validity
    $check = explode(',', $cdata);
    foreach ($check as $c)
    {
        if ($c == 'undefined')
        {
            $cdata = '';
            break;
        }
    }
    if ($cdata != '')
    {
        $grids[$view] = $cdata;
    }

    // get order status
    $orderStatusPS = SCI::getScDisplayableOrderStates($sc_agent->id_lang);
    $orderStatusPSOnlyIndex = array_column($orderStatusPS, 'id_order_state', 'id_order_state');
    $allOrderStatusPS = OrderState::getOrderStates($sc_agent->id_lang);

    $orderStatus = [];
    $arrStatus = [];

    foreach ($allOrderStatusPS as $status)
    {
        if (!isset($orderStatusPSOnlyIndex[$status['id_order_state']]))
        {
            $status['name'] = '';
            $orderStatus[$status['id_order_state']] = $status;
        }
        else
        {
            $orderStatus[$status['id_order_state']] = $status;
            $arrStatus[$status['id_order_state']] = $status['name'];
        }
    }

    // get order carrier
    $prefix_for_end_order = 'ZZZZ#';
    $sql = 'SELECT c.*, IF(c.deleted=1,CONCAT("'.$prefix_for_end_order._l('(deleted %s)').' ",c.name),c.name) as name, cl.delay
                FROM `'._DB_PREFIX_.'carrier` c
                LEFT JOIN `'._DB_PREFIX_.'carrier_lang` cl ON (c.`id_carrier` = cl.`id_carrier` AND cl.`id_lang` = '.(int) $sc_agent->id_lang.(SCMS ? ' AND cl.id_shop="'.(int) SCI::getSelectedShop().'"' : '').')
                '.(SCMS && SCI::getSelectedShop() > 0 ? ' INNER JOIN `'._DB_PREFIX_.'carrier_shop` cs ON (cs.id_carrier=c.id_carrier AND cs.id_shop="'.(int) SCI::getSelectedShop().'")' : '').'
                GROUP BY c.`id_carrier`
                 ORDER BY name ASC, c.name ASC';
    $orderCarrierPS = Db::getInstance()->executeS($sql);
    $orderCarrier = [];
    $arrCarrier = [];
    $arrCarrier['0'] = $orderCarrier['0'] = '0'; ## permet d'utiliser le select si le nom = 0
    foreach ($orderCarrierPS as $carrier)
    {
        $carrier['name'] = str_replace(['%s', $prefix_for_end_order], [$carrier['id_carrier'], ''], $carrier['name']);
        $orderCarrier[$carrier['id_carrier']] = $carrier;
        $arrCarrier[$carrier['id_carrier']] = $carrier['name'];
    }

    // get order country
    $orderCountryPS = Country::getCountries($sc_agent->id_lang);
    $orderCountry = [];
    foreach ($orderCountryPS as $country)
    {
        if (!empty($country['name']))
        {
            $orderCountry[$country['id_country']] = $country['name'];
        }
    }

    // get order country state
    $orderStatePS = State::getStates($sc_agent->id_lang);
    $orderState = [0 => '--'];
    foreach ($orderStatePS as $state)
    {
        if (!empty($state['name']))
        {
            $orderState[$state['id_state']] = $state['name'];
        }
    }

    // get order language
    $orderLanguagePS = Language::getLanguages($sc_agent->id_lang);
    $orderLanguage = [0 => ['name' => '']];
    foreach ($orderLanguagePS as $lang)
    {
        $orderLanguage[$lang['id_lang']] = $lang;
    }

    // get order currency
    $orderCurrencyDb = new DbQuery();
    $orderCurrencyDb->select('c.`id_currency`')
        ->from('currency', 'c')
        ->where('c.`deleted` = 0')
        ->orderBy('`name` ASC');

    if (version_compare(_PS_VERSION_, '1.7.6.0', '>='))
    {
        $orderCurrencyDb->select('cl.`name`');
        $orderCurrencyDb->leftJoin('currency_lang', 'cl', 'cl.`id_currency` = c.`id_currency` AND cl.`id_lang` = '.(int) $sc_agent->id_lang);
    }
    else
    {
        $orderCurrencyDb->select('c.`name`');
    }
    $orderCurrencyPS = Db::getInstance()->executeS($orderCurrencyDb);
    $orderCurrency = [0 => ['name' => '']];
    if ($orderCurrencyPS)
    {
        foreach ($orderCurrencyPS as $cur)
        {
            $orderCurrency[$cur['id_currency']] = $cur;
        }
    }

    // get payemnts
    $pnamelist = Db::getInstance()->executeS('
            SELECT DISTINCT o.`payment`
            FROM `'._DB_PREFIX_.'orders` o
        ');
    foreach ($pnamelist as $n)
    {
        $n['payment'] = str_replace('&', '-', strip_tags($n['payment']));
        $arrPayments[$n['payment']] = $n['payment'];
    }

    $cols = explode(',', $grids[$view]);

    $colSettings = [];
    $colSettings = SCI::getGridFields('order');
    SC_Ext::readCustomOrdersGridsConfigXML('colSettings');

    /**
     * @param array  $periods
     * @param string $period
     *
     * @return array where or having
     */
    function setPeriodFilter($periods, $period = 'all')
    {
        $periodCondition = [
            'where' => '',
            'having' => '',
        ];

        if (strpos($period, 'inv_from_to_') !== false)
        {
            $dates = str_replace('inv_from_to_', '', $period);
            $exp = explode('_', $dates);
            $from = $exp[0];
            $to = '';
            if (!empty($exp[1]))
            {
                $to = $exp[1];
            }

            $where = '';
            if (!empty($from))
            {
                $where .= ' AND (oi.`date_add` >= "'.pSQL($from).'") ';
            }
            if (!empty($to))
            {
                $where .= ' AND (oi.`date_add` <= "'.pSQL($to).'" ) ';
            }
            if (!empty($from) && !empty($to))
            {
                $where = ' AND (oi.`date_add` BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'") ';
            }
            $periodCondition['where'] = ' AND o.id_order IN (SELECT oi.id_order FROM '._DB_PREFIX_.'order_invoice oi WHERE 1 '.$where.' ) ';

            return $periodCondition;
        }

        if (strpos($period, 'from_to_') !== false)
        {
            $dates = str_replace('from_to_', '', $period);
            $exp = explode('_', $dates);
            $from = $exp[0];
            $to = '';
            if (!empty($exp[1]))
            {
                $to = $exp[1];
            }

            $having = '';
            if (!empty($from))
            {
                $having .= ' AND (`date_creation` >= "'.pSQL($from).'") ';
            }
            if (!empty($to))
            {
                $having .= ' AND (`date_creation` <= "'.pSQL($to).'" ) ';
            }
            if (!empty($from) && !empty($to))
            {
                $having = ' AND (`date_creation` BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'") ';
            }
            $periodCondition['having'] = $having;

            return $periodCondition;
        }

        $periodCondition['where'] = $periods[$period];

        return $periodCondition;
    }

    /**
     * @return string
     */
    function getColSettingsAsXML()
    {
        global $cols,$colSettings,$view;

        $uiset = UISettings::getSetting('ord_grid_'.$view);
        $sizes = UISettings::formatSize($uiset);
        $hidden = UISettings::formatHidden($uiset);

        $xml = '';
        if ($view == 'grid_picking' || sc_in_array('id_order_detail', $cols, 'ordGet_cols'))
        {
            $colSettings['payment']['type'] = 'ro';
        }
        if ($view == 'grid_picking' || sc_in_array('id_order_detail', $cols, 'ordGet_cols'))
        {
            $colSettings['status']['type'] = 'ro';
        }
        foreach ($cols as $id => $col)
        {
            if (!sc_array_key_exists($col, $colSettings))
            {
                continue;
            }
            $xml .= '<column id="'.$col.'"'.(sc_array_key_exists('format', $colSettings[$col]) ?
                    ' format="'.$colSettings[$col]['format'].'"' : '').
                    ' width="'.(sc_array_key_exists($col, $sizes) ? $sizes[$col] : ($view == 'grid_combination_price' && $col == 'id' ? $colSettings[$col]['width'] + 50 : $colSettings[$col]['width'])).'"'.
                    ' hidden="'.(sc_array_key_exists($col, $hidden) ? $hidden[$col] : 0).'"'.
                    ' align="'.$colSettings[$col]['align'].'"
                    type="'.$colSettings[$col]['type'].'"
                    sort="'.$colSettings[$col]['sort'].'"
                    color="'.$colSettings[$col]['color'].'">'.$colSettings[$col]['text']."\n";
            if (is_array($colSettings[$col]) && sc_array_key_exists('options', $colSettings[$col]) && is_array($colSettings[$col]['options']) && !empty($colSettings[$col]['options']))
            {
                foreach ($colSettings[$col]['options'] as $k => $v)
                {
                    $xml .= '<option value="'.str_replace('"', '\'', $k).'"><![CDATA['.$v.']]></option>'."\n";
                }
            }

            $xml .= '</column>'."\n";
        }

        return $xml;
    }

    /**
     * @return string
     */
    function getFooterColSettings()
    {
        global $cols,$colSettings;

        $footer = '';
        foreach ($cols as $id => $col)
        {
            if (sc_array_key_exists($col, $colSettings) && sc_array_key_exists('footer', $colSettings[$col]))
            {
                $footer .= $colSettings[$col]['footer'].',';
            }
            else
            {
                $footer .= ',';
            }
        }

        return $footer;
    }

    /**
     * @return string
     */
    function getFilterColSettings()
    {
        global $cols,$colSettings;

        $filters = '';
        foreach ($cols as $id => $col)
        {
            if ($colSettings[$col]['filter'] == 'na')
            {
                $colSettings[$col]['filter'] = '';
            }
            $filters .= $colSettings[$col]['filter'].',';
        }
        $filters = trim($filters, ',');

        return $filters;
    }

    /**
     * @param array  $segment_params
     * @param string $dd
     *
     * @return void
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    function getOrders($segment_params, $dd)
    {
        $shop_id = SCI::getSelectedShop();
        if (empty($shop_id))
        {
            $shop_id = Configuration::get('PS_SHOP_DEFAULT');
        }
        $shop = new Shop($shop_id);
        $shop_group = $shop->getGroup();

        global $sql,$periods,$period,$statusFilter,$filter_params,$orderStatus,$orderCarrier,$orderCountry,$orderState,$orderLanguage,$orderCurrency,$sc_agent,$arrManufacturers,$id_lang,$explodePacks, $cols,$view,$colSettings,$user_lang_iso,$fields_order,$fields_customer,$fields_lang,$fields_other,$col,$orderrow;
        $yesno = [0 => _l('No'), 1 => _l('Yes')];
        $fields_order = ['id_customer', 'total_paid', 'payment', 'invoice_number', 'delivery_number', 'date_add', 'id_carrier', 'reference', 'id_lang', 'id_cart', 'id_currency', 'conversion_rate', 'recyclable', 'gift', 'gift_message', 'total_discounts', 'total_discounts_tax_incl', 'total_discounts_tax_excl', 'total_paid_tax_incl', 'total_paid_tax_excl', 'total_paid_real', 'total_products', 'total_products_wt', 'total_shipping', 'total_shipping_tax_incl', 'total_shipping_tax_excl', 'carrier_tax_rate', 'total_wrapping', 'total_wrapping_tax_incl', 'total_wrapping_tax_excl', 'invoice_date', 'valid', 'date_upd', 'id_shop'];

        $fields_customer = ['firstname', 'lastname', 'email'];
        $fields_customer[] = 'company';
        $fields_lang = ['name'];
        $fields_other = [];
        SC_Ext::readCustomOrdersGridsConfigXML('updateSettings');

        $id_segment = null;
        if (!empty($segment_params) && !empty($segment_params['id_segment']))
        {
            $id_segment = (int) $segment_params['id_segment'];
            $segment = $segment_params['segment_object'];
        }

        $blacklistfields = ['status', 'instock', 'pdf', 'msg'];
        $sqlOrder = '';

        foreach ($cols as $col)
        {
            if (sc_in_array($col, $blacklistfields, 'ordGet_blacklistfields'))
            { // calculated fields
                continue;
            }

            if (sc_in_array($col, $fields_order, 'ordGet_fields_order'))
            {
                $sqlOrder .= ',o.`'.bqSQL($col).'`';
            }
            if (sc_in_array($col, $fields_customer, 'ordGet_fields_customer'))
            {
                $sqlOrder .= ',c.`'.bqSQL($col).'`';
            }
        }
        if (!empty($fields_other))
        {
            foreach ($fields_other as $f)
            {
                $sqlOrder .= $f;
            }
        }
        $sqlOrder = rtrim($sqlOrder, ',');
        if (sc_in_array('pdf', $cols, 'ordGet_cols') && strpos($sqlOrder, 'invoice_number') === false)
        {
            $sqlOrder .= ',o.invoice_number';
        }
        if (sc_in_array('pdf', $cols, 'ordGet_cols') && strpos($sqlOrder, 'delivery_number') === false)
        {
            $sqlOrder .= ',o.delivery_number';
        }

        if ($view == 'grid_picking' || sc_in_array('id_order_detail', $cols, 'ordGet_cols'))
        {
            $ps_location_field = ' p.location AS location';
            if (SCAS)
            {
                $ps_location_field = ' (SELECT wpl2.location FROM '._DB_PREFIX_.'warehouse_product_location wpl2 WHERE wpl2.id_product=od.product_id AND wpl2.id_product_attribute=od.product_attribute_id AND wpl2.id_warehouse=od.id_warehouse LIMIT 1) AS location ';
            }
            elseif (version_compare(_PS_VERSION_, '1.7.5.0', '>='))
            {
                $ps_location_field = ' sa.location AS location';
            }
            $sql = 'SELECT o.id_customer, od.id_order_detail,o.id_order,o.id_currency,'.(SCAS ? 'od.id_warehouse,w.name AS warehousename,' : '').'od.product_quantity,od.product_id,od.product_reference,pl.name as product_name,od.product_name as order_detail_product_name,
                                            od.product_supplier_reference,od.product_ean13,od.product_upc'.(version_compare(_PS_VERSION_, '1.7.0.0', '>=') ? ',od.product_isbn' : '').(!empty($sqlOrder) && substr($sqlOrder, 0, 1) !== ',' ? ','.pSQL($sqlOrder) : pSQL($sqlOrder)).", od.product_id AS id_product,IF( od.product_attribute_id > 0, CONCAT(od.product_id,'-',od.product_attribute_id), od.product_id) AS product_id, CONCAT(od.product_id,'-',od.product_attribute_id,'-',o.id_order,'-',od.id_order_detail) AS unique_id, od.product_attribute_id,
                                             ".$ps_location_field
                                             .',c.id_shop as id_shop_customer';
            if (version_compare(_PS_VERSION_, '1.6.1.14', '>=') && in_array('is_pack', $cols, 'ordGet_cols'))
            {
                $sql .= ',p.cache_is_pack AS is_pack';
            }
            if (sc_in_array('location_old', $cols, 'ordGet_cols'))
            {
                $sql .= ' , p.location AS location_old ';
            }
            if (sc_in_array('total_remaining_paid', $cols, 'ordGet_cols'))
            {
                $sql .= ' , (o.total_paid - o.total_paid_real) AS total_remaining_paid ';
            }
            if (sc_in_array('new_customer', $cols, 'ordGet_cols'))
            {
                $sql .= ' , IF((SELECT so.id_order FROM `'._DB_PREFIX_.'orders` so WHERE so.id_customer = c.id_customer AND so.id_order < o.id_order LIMIT 1) > 0, 0, 1) AS new_customer ';
            }
            if (version_compare(_PS_VERSION_, '1.6.1.1', '>='))
            {
                $sql .= ',od.original_wholesale_price';
            }
            $sql .= ',o.current_state';
            if (sc_in_array('status_date', $cols, 'ordGet_cols'))
            {
                $sql .= ', MAX(oh.`date_add`) AS `status_date` ';
            }
            if (sc_in_array('date_add', $cols, 'ordGet_cols'))
            {
                $sql .= ', IF(o.`date_add` > 0, o.`date_add`, MIN(oh.`date_add`)) as `date_add` ';
                $sql .= ', IF(o.`date_add` > 0, o.`date_add`, MIN(oh.`date_add`)) as `date_creation` ';
            }
            if (sc_in_array('del_address1', $cols, 'ordGet_cols') || sc_in_array('del_postcode', $cols, 'ordGet_cols') || sc_in_array('del_id_country', $cols, 'ordGet_cols'))
            {
                $sql .= ',ad.company AS del_company,ad.firstname AS del_firstname,ad.lastname AS del_lastname,ad.address1 AS del_address1,ad.address2 AS del_address2,ad.postcode AS del_postcode,ad.city AS del_city,ad.id_country AS del_id_country,ad.id_state AS del_id_state,ad.other AS del_other,ad.phone AS del_phone,ad.phone_mobile AS del_phone_mobile ';
            }

            if (sc_in_array('inv_address1', $cols, 'ordGet_cols') || sc_in_array('inv_company', $cols, 'ordGet_cols') || sc_in_array('inv_id_country', $cols, 'ordGet_cols'))
            {
                $sql .= ',adi.company AS inv_company,adi.firstname AS inv_firstname,adi.lastname AS inv_lastname,adi.address1 AS inv_address1,adi.address2 AS inv_address2,adi.postcode AS inv_postcode,adi.city AS inv_city,adi.id_country AS inv_id_country,adi.id_state AS inv_id_state,adi.other AS inv_other,adi.phone AS inv_phone,adi.phone_mobile AS inv_phone_mobile ';
            }
            if (sc_in_array('inv_vat_number', $cols, 'ordGet_cols'))
            {
                $sql .= ',adi.vat_number AS inv_vat_number ';
            }
            if (sc_in_array('inv_dni', $cols, 'ordGet_cols'))
            {
                $sql .= ',adi.dni AS inv_dni ';
            }
            if (sc_in_array('msg', $cols, 'ordGet_cols'))
            {
                $sql .= ',(SELECT COUNT(*) FROM '._DB_PREFIX_.'message m WHERE m.id_order=o.id_order AND m.private!=1) as msg_count ';
                $sql .= ',(SELECT COUNT(*) FROM '._DB_PREFIX_.'message m LEFT JOIN '._DB_PREFIX_.'message_readed mr ON (m.id_message = mr.id_message) WHERE m.id_order=o.id_order AND m.private!=1 AND mr.id_employee='.(int) $sc_agent->id_employee.') as msg_read ';
            }
            if (sc_in_array('instock', $cols, 'ordGet_cols') || sc_in_array('product_quantity_in_stock', $cols, 'ordGet_cols'))
            {
                $sql .= ',od.product_quantity_in_stock ';
            }
            if (sc_in_array('supplier_name', $cols, 'ordGet_cols'))
            {
                $sql .= ',spl.name AS supplier_name ';
            }

            if (sc_in_array('delivery_info', $cols, 'ordGet_cols') || sc_in_array('delivery_date_standard', $cols, 'ordGet_cols') || sc_in_array('delivery_date_limit', $cols, 'ordGet_cols'))
            {
                $sql .= ' ,sdi.delivery_info,sdi.date_standard AS delivery_date_standard,sdi.date_limit AS delivery_date_limit ';
            }

            $sql .= ', ps.id_category_default, cl.name as category_name, ps.wholesale_price ';
            $sql .= ', od.unit_price_tax_excl AS product_price, od.unit_price_tax_incl AS product_price_tax_incl ';

            if (sc_in_array('quantity_physical', $cols, 'ordGet_cols') || sc_in_array('quantity_usable', $cols, 'ordGet_cols') || sc_in_array('quantity_real', $cols, 'ordGet_cols'))
            {
                $sql .= ', ps.advanced_stock_management, od.id_warehouse ';
            }
            if (sc_in_array('shipping_number', $cols, 'ordGet_cols'))
            {
                $sql .= ',oc.tracking_number AS shipping_number ';
            }

            if (sc_in_array('default_group', $cols, 'ordGet_cols'))
            {
                $sql .= ',c.id_default_group AS default_group ';
            }
            if (sc_in_array('customer_note', $cols, 'ordGet_cols'))
            {
                $sql .= ',c.note AS customer_note ';
            }
            $sql .= ' , od.id_shop ';
            $sql .= ',oin.date_add AS invoice_date ';
            if (version_compare(_PS_VERSION_, '1.7.7.0', '>='))
            {
                $sql .= ',od.product_mpn ';
            }
            if (version_compare(_PS_VERSION_, '1.7.8.0', '>='))
            {
                $sql .= ',o.note ';
            }
            $sql .= ', od.sc_attr_infos_v1 AS sc_attr_infos ';
            if (sc_in_array('customization', $cols, 'ordGet_cols'))
            {
                $sql .= ',GROUP_CONCAT(CONCAT(cfl.name,"'._l(':').' ",cd.value) SEPARATOR "<br>") AS customization ';
            }

            SC_Ext::readCustomOrdersGridsConfigXML('SQLSelectDataSelect');
            $sql .= '    FROM '._DB_PREFIX_.'order_detail od
                                LEFT JOIN '._DB_PREFIX_.'shop ssho ON (ssho.id_shop = od.id_shop)
                                LEFT JOIN '._DB_PREFIX_.'orders o ON (o.id_order = od.id_order)
                                LEFT JOIN '._DB_PREFIX_.'product p ON (od.product_id = p.id_product) 
                                LEFT JOIN '._DB_PREFIX_.'product_shop ps ON (ps.id_product = p.id_product AND ps.id_shop = o.id_shop) ';

            if (SCAS)
            {
                $sql .= '    LEFT JOIN '._DB_PREFIX_.'warehouse w ON (od.id_warehouse = w.id_warehouse) ';
            }
            if (sc_in_array('supplier_name', $cols, 'ordGet_cols'))
            {
                $sql .= '    LEFT JOIN '._DB_PREFIX_.'supplier spl ON (p.id_supplier = spl.id_supplier) ';
            }

            $sql .= '    LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (ps.id_product = pl.id_product AND pl.id_lang='.(int) $id_lang.' AND pl.id_shop=o.id_shop) ';
            $sql .= '    LEFT JOIN '._DB_PREFIX_.'category_lang cl ON (ps.id_category_default = cl.id_category AND cl.id_lang='.(int) $id_lang.' AND cl.id_shop=o.id_shop) ';

            $sql .= '    LEFT JOIN '._DB_PREFIX_.'customer c ON (o.id_customer = c.id_customer) ';

            $sql .= '     LEFT JOIN '._DB_PREFIX_.'address ad ON (ad.id_address = o.id_address_delivery) ';

            $sql .= ' LEFT JOIN '._DB_PREFIX_.'address adi ON (adi.id_address = o.id_address_invoice) ';

            if (sc_in_array('delivery_info', $cols, 'ordGet_cols') || sc_in_array('delivery_date_standard', $cols, 'ordGet_cols') || sc_in_array('delivery_date_limit', $cols, 'ordGet_cols'))
            {
                $sql .= ' LEFT JOIN '._DB_PREFIX_.'storecom_delivery_info sdi ON (sdi.id_order = o.id_order) ';
            }
            if (sc_in_array('shipping_number', $cols, 'ordGet_cols'))
            {
                $sql .= ' LEFT JOIN '._DB_PREFIX_.'order_carrier oc ON (oc.id_order = o.id_order) ';
            }
            $sql .= ' LEFT JOIN '._DB_PREFIX_.'order_invoice oin ON (oin.id_order=o.id_order) ';
            $sql .= ' LEFT JOIN '._DB_PREFIX_.'order_history oh ON (oh.id_order=o.id_order) ';
            if (version_compare(_PS_VERSION_, '1.7.5.0', '>='))
            {
                $sql .= ' LEFT JOIN '._DB_PREFIX_.'stock_available sa ON (sa.id_product=od.product_id AND sa.id_product_attribute=od.product_attribute_id AND sa.id_shop = od.id_shop AND sa.id_shop_group = '.($shop_group->share_stock ? 'ssho.id_shop_group' : '0').')';
            }
            if (sc_in_array('customization', $cols, 'ordGet_cols'))
            {
                $sql .= '    LEFT JOIN `'._DB_PREFIX_.'customized_data` cd ON cd.`id_customization` = od.`id_customization`
                            LEFT JOIN `'._DB_PREFIX_.'customization_field_lang` cfl ON cfl.`id_customization_field` = cd.`index` AND cfl.`id_lang` = '.(int) $id_lang.' AND cfl.`id_shop` = od.`id_shop`';
            }

            SC_Ext::readCustomOrdersGridsConfigXML('SQLSelectDataLeftJoin');
            $sql .= '    WHERE 1 ';

            $withSearch = false;
            if (!empty($filter_params))
            {
                $filters = explode(',', $filter_params);
                foreach ($filters as $filter)
                {
                    list($field, $search) = explode('|||', $filter);
                    if (!empty($field) && !empty($search) && sc_in_array($field, $cols, 'ordGet_cols'))
                    {
                        if (sc_in_array($field, ['id_order'], 'ordGet_id_order'))
                        {
                            $searched = " LIKE '".pSQL($search)."%' ";
                            if (strpos($search, '>=') !== false)
                            {
                                $searched = ' >= '.(int) $search.' ';
                            }
                            elseif (strpos($search, '<=') !== false)
                            {
                                $searched = ' <= '.(int) $search.' ';
                            }
                            elseif (strpos($search, '<') !== false)
                            {
                                $searched = ' < '.(int) $search.' ';
                            }
                            elseif (strpos($search, '>') !== false)
                            {
                                $searched = ' > '.(int) $search.' ';
                            }
                            elseif (strpos($search, '..') !== false)
                            {
                                list($from, $to) = explode('..', $search);
                                $searched = "BETWEEN '".pSQL($from)."' AND '".pSQL($to)."' ";
                            }
                            $sql .= ' AND ( o.`'.bqSQL($field).'` '.$searched.') ';
                        }
                        else
                        {
                            $sql .= ' AND ( o.`'.bqSQL($field)."` LIKE '%".pSQL($search)."%' ) ";
                        }
                        $withSearch = true;
                    }
                }
            }
            $havingCondition = '';
            if (empty($withSearch))
            {
                if (!empty($id_segment) && SCSG)
                {
                    if ($segment->type == 'manual')
                    {
                        $sql .= ' AND od.id_order IN (SELECT id_element FROM '._DB_PREFIX_."sc_segment_element WHERE type_element='order' AND id_segment=".(int) $id_segment.')';
                    }
                    elseif ($segment->type == 'auto')
                    {
                        $params = ['id_lang' => $id_lang, 'id_segment' => $id_segment, 'access' => 'orders'];
                        for ($i = 1; $i <= 15; ++$i)
                        {
                            $param = Tools::getValue('segment_params_'.$i);
                            if (!empty($param))
                            {
                                $params['segment_params_'.$i] = $param;
                            }
                        }
                        if (SCMS)
                        {
                            $params['id_shop'] = (int) SCI::getSelectedShop();
                        }
                        $params['is_order'] = '0';
                        $sql .= SegmentHook::hookByIdSegment('segmentAutoSqlQuery', $segment, $params);
                    }
                }
                else
                {
                    $periodCondition = setPeriodFilter($periods, $period);
                    if (!empty($periodCondition['where']))
                    {
                        $sql .= $periodCondition['where'];
                    }
                    if (!empty($periodCondition['having']))
                    {
                        $havingCondition = $periodCondition['having'];
                    }
                }
            }
            $sql .= (SCMS && SCI::getSelectedShop() > 0 ? ' AND o.id_shop = '.(int) SCI::getSelectedShop() : '');

            $sql .= ' GROUP BY od.id_order_detail';
            $sql .= ' HAVING 1 '.$havingCondition;
            $sql .= ' ORDER BY od.id_order_detail DESC';
        }
        else
        {
            $sql = 'SELECT o.id_customer, o.id_order,o.id_currency'.pSQL($sqlOrder).',c.id_shop as id_shop_customer';
            $sql .= ',o.current_state, o.id_shop ';
            if (sc_in_array('status_date', $cols, 'ordGet_cols'))
            {
                $sql .= ', MAX(oh.`date_add`) AS `status_date` ';
            }
            if (sc_in_array('date_add', $cols, 'ordGet_cols'))
            {
                $sql .= ', IF(o.`date_add` > 0, o.`date_add`, MIN(oh.`date_add`)) as `date_add` ';
                $sql .= ', IF(o.`date_add` > 0, o.`date_add`, MIN(oh.`date_add`)) as `date_creation` ';
            }

            if (sc_in_array('total_remaining_paid', $cols, 'ordGet_cols'))
            {
                $sql .= ' , (o.total_paid - o.total_paid_real) AS total_remaining_paid ';
            }
            if (sc_in_array('new_customer', $cols, 'ordGet_cols'))
            {
                $sql .= ' , IF((SELECT so.id_order FROM `'._DB_PREFIX_.'orders` so WHERE so.id_customer = c.id_customer AND so.id_order < o.id_order LIMIT 1) > 0, 0, 1) AS new_customer ';
            }
            if (sc_in_array('msg', $cols, 'ordGet_cols'))
            {
                $sql .= ',(SELECT COUNT(*) FROM '._DB_PREFIX_.'message m WHERE m.id_order=o.id_order AND m.private!=1) as msg_count ';
                $sql .= ',(SELECT COUNT(*) FROM '._DB_PREFIX_.'message m LEFT JOIN '._DB_PREFIX_.'message_readed mr ON (m.id_message = mr.id_message) WHERE m.id_order=o.id_order AND m.private!=1 AND mr.id_employee='.(int) $sc_agent->id_employee.') as msg_read ';
            }
            if (sc_in_array('instock', $cols, 'ordGet_cols'))
            {
                $sql .= ',NOT(SELECT COUNT(id_order) FROM '._DB_PREFIX_.'order_detail od WHERE od.id_order = o.id_order AND product_quantity_in_stock < product_quantity) as instock ';
            }
            if (sc_in_array('del_address1', $cols, 'ordGet_cols') || sc_in_array('del_postcode', $cols, 'ordGet_cols') || sc_in_array('del_id_country', $cols, 'ordGet_cols'))
            {
                $sql .= ',ad.company AS del_company,ad.firstname AS del_firstname,ad.lastname AS del_lastname,ad.address1 AS del_address1,ad.address2 AS del_address2,ad.postcode AS del_postcode,ad.city AS del_city,ad.id_country AS del_id_country,ad.id_state AS del_id_state,ad.other AS del_other,ad.phone AS del_phone,ad.phone_mobile AS del_phone_mobile ';
            }
            $sql .= ',oin.date_add AS invoice_date ';

            if (sc_in_array('inv_address1', $cols, 'ordGet_cols') || sc_in_array('inv_company', $cols, 'ordGet_cols') || sc_in_array('inv_id_country', $cols, 'ordGet_cols'))
            {
                $sql .= ',adi.company AS inv_company,adi.firstname AS inv_firstname,adi.lastname AS inv_lastname,adi.address1 AS inv_address1,adi.address2 AS inv_address2,adi.postcode AS inv_postcode,adi.city AS inv_city,adi.id_country AS inv_id_country,adi.id_state AS inv_id_state,adi.other AS inv_other,adi.phone AS inv_phone,adi.phone_mobile AS inv_phone_mobile ';
            }
            if (sc_in_array('inv_vat_number', $cols, 'ordGet_cols'))
            {
                $sql .= ',adi.vat_number AS inv_vat_number ';
            }
            if (sc_in_array('inv_dni', $cols, 'ordGet_cols'))
            {
                $sql .= ',adi.dni AS inv_dni ';
            }

            if (sc_in_array('default_group', $cols, 'ordGet_cols'))
            {
                $sql .= ',c.id_default_group AS default_group ';
            }

            if (sc_in_array('delivery_info', $cols, 'ordGet_cols') || sc_in_array('delivery_date_standard', $cols, 'ordGet_cols') || sc_in_array('delivery_date_limit', $cols, 'ordGet_cols'))
            {
                $sql .= ' ,sdi.delivery_info,sdi.date_standard AS delivery_date_standard,sdi.date_limit AS delivery_date_limit ';
            }
            if (sc_in_array('shipping_number', $cols, 'ordGet_cols'))
            {
                $sql .= ',oc.tracking_number AS shipping_number ';
            }
            if (sc_in_array('customer_note', $cols, 'ordGet_cols'))
            {
                $sql .= ',c.note AS customer_note ';
            }
            if (version_compare(_PS_VERSION_, '1.7.8.0', '>='))
            {
                $sql .= ',o.note ';
            }
            SC_Ext::readCustomOrdersGridsConfigXML('SQLSelectDataSelect');
            $sql .= ' FROM '._DB_PREFIX_.'orders o
                            LEFT JOIN '._DB_PREFIX_.'customer c ON (o.id_customer = c.id_customer) ';
            $sql .= ' LEFT JOIN '._DB_PREFIX_.'address ad ON (ad.id_address = o.id_address_delivery) ';
            $sql .= ' LEFT JOIN '._DB_PREFIX_.'address adi ON (adi.id_address = o.id_address_invoice) ';

            if (sc_in_array('delivery_info', $cols, 'ordGet_cols') || sc_in_array('delivery_date_standard', $cols, 'ordGet_cols') || sc_in_array('delivery_date_limit', $cols, 'ordGet_cols'))
            {
                $sql .= ' LEFT JOIN '._DB_PREFIX_.'storecom_delivery_info sdi ON (sdi.id_order = o.id_order) ';
            }
            if (sc_in_array('shipping_number', $cols, 'ordGet_cols'))
            {
                $sql .= ' LEFT JOIN '._DB_PREFIX_.'order_carrier oc ON (oc.id_order = o.id_order) ';
            }
            $sql .= ' LEFT JOIN '._DB_PREFIX_.'order_invoice oin ON (oin.id_order = o.id_order) ';
            $sql .= ' LEFT JOIN '._DB_PREFIX_.'order_history oh ON (oh.id_order=o.id_order) ';
            SC_Ext::readCustomOrdersGridsConfigXML('SQLSelectDataLeftJoin');
            $sql .= ' WHERE 1 ';

            $withSearch = false;
            if (!empty($filter_params))
            {
                $filters = explode(',', $filter_params);
                foreach ($filters as $filter)
                {
                    list($field, $search) = explode('|||', $filter);
                    if (!empty($field) && !empty($search) && sc_in_array($field, $cols, 'ordGet_cols'))
                    {
                        if (sc_in_array($field, ['id_order'], 'ordGet_id_order'))
                        {
                            $searched = " LIKE '".pSQL($search)."%' ";
                            if (strpos($search, '>=') !== false)
                            {
                                $searched = ' >= '.(int) $search.' ';
                            }
                            elseif (strpos($search, '<=') !== false)
                            {
                                $searched = ' <= '.(int) $search.' ';
                            }
                            elseif (strpos($search, '<') !== false)
                            {
                                $searched = ' < '.(int) $search.' ';
                            }
                            elseif (strpos($search, '>') !== false)
                            {
                                $searched = ' > '.(int) $search.' ';
                            }
                            elseif (strpos($search, '..') !== false)
                            {
                                list($from, $to) = explode('..', $search);
                                $searched = "BETWEEN '".pSQL($from)."' AND '".pSQL($to)."' ";
                            }
                            $sql .= ' AND ( o.`'.bqSQL($field).'` '.$searched.') ';
                        }
                        else
                        {
                            $sql .= ' AND ( o.`'.bqSQL($field)."` LIKE '%".pSQL($search)."%' ) ";
                        }
                        $withSearch = true;
                    }
                }
            }
            $havingCondition = '';
            if (empty($withSearch))
            {
                if (!empty($id_segment) && SCSG)
                {
                    if ($segment->type == 'manual')
                    {
                        $sql .= ' AND o.id_order IN (SELECT id_element FROM '._DB_PREFIX_."sc_segment_element WHERE type_element='order' AND id_segment=".(int) $id_segment.')';
                    }
                    elseif ($segment->type == 'auto')
                    {
                        $seg_params = unserialize($segment->auto_params);
                        $params = ['id_lang' => $id_lang, 'id_segment' => $id_segment, 'access' => 'orders'];
                        for ($i = 1; $i <= 15; ++$i)
                        {
                            $param = Tools::getValue('segment_params_'.$i);
                            if (!empty($param))
                            {
                                $params['segment_params_'.$i] = $param;
                            }
                        }
                        if (SCMS)
                        {
                            $params['id_shop'] = (int) SCI::getSelectedShop();
                        }
                        $params['is_order'] = '1';
                        $sql .= SegmentHook::hookByIdSegment('segmentAutoSqlQuery', $segment, $params);
                        if ($seg_params['use_filters'] == 1)
                        {
                            $periodCondition = setPeriodFilter($periods, $period);
                            if (!empty($periodCondition['where']))
                            {
                                $sql .= $periodCondition['where'];
                            }
                            if (!empty($periodCondition['having']))
                            {
                                $havingCondition = $periodCondition['having'];
                            }
                        }
                    }
                }
                else
                {
                    $periodCondition = setPeriodFilter($periods, $period);
                    if (!empty($periodCondition['where']))
                    {
                        $sql .= $periodCondition['where'];
                    }
                    if (!empty($periodCondition['having']))
                    {
                        $havingCondition = $periodCondition['having'];
                    }
                }
            }

            $sql .= (SCMS && SCI::getSelectedShop() > 0 ? ' AND o.id_shop = '.(int) SCI::getSelectedShop() : '');
            $sql .= ' GROUP BY o.id_order';
            $sql .= ' HAVING 1 '.$havingCondition;
            $sql .= ' ORDER BY o.id_order DESC';
        }

        $dd = $sql;
        //echo "\n\n\n".$sql."\n\n\n";die();
        $res = Db::getInstance()->executeS($sql);
        if (empty($res))
        {
            return;
        }
        // index le tableau de resultats -> unique_id (composé de id_product-id_product_attribute-id_order)
        $res = array_column($res, null, 'unique_id');

        //echo "\n\n\n".count($res)."\n\n\n";die();

        if (_s('ORD_DATES_ADD_INVOICE_INTERFACE'))
        {
            $tmp_ids = [];
            if (!empty($res))
            {
                foreach ($res as $row)
                {
                    $tmp_ids[] = $row['id_order'];
                }
            }
            if (!empty($tmp_ids))
            {
                $cache_dates_sql = 'SELECT o.reference,MIN(oh.date_add) AS date_add, MIN(oi.date_add) AS invoice_date
                            FROM '._DB_PREFIX_.'orders o
                            LEFT JOIN '._DB_PREFIX_.'order_history oh ON oh.id_order = o.id_order
                             LEFT JOIN '._DB_PREFIX_.'order_invoice oi ON oi.id_order = oh.id_order
                            WHERE o.id_order IN ('.pSQL(implode(',', $tmp_ids)).')
                            GROUP BY o.reference';
                $cache_dates = Db::getInstance()->executeS($cache_dates_sql);
                if (!empty($cache_dates))
                {
                    $tmp = [];
                    foreach ($cache_dates as $r)
                    {
                        $tmp[$r['reference']] = [
                            'date_add' => $r['date_add'],
                            'invoice_date' => $r['invoice_date'],
                        ];
                    }
                    $cache_dates = $tmp;
                }
            }
        }
        $cache_attr = [];
        // recupération des produits du pack
        if (
            version_compare(_PS_VERSION_, '1.6.1.14', '>=')
            && $view == 'grid_picking'
            && $explodePacks
            && Pack::isFeatureActive()
        ) {
            injectPackProductDetails($res, (int) $id_lang);
        }

        foreach ($res as $orderrow)
        {
            if (count($statusFilter) && !sc_in_array($orderrow['current_state'], $statusFilter, 'ordGet_statusFilter'))
            {
                continue;
            }

            if (!empty($orderrow['product_attribute_id']))
            {
                $res_pa = [];

                if (!isset($orderrow['id_warehouse']))
                {
                    $orderrow['id_warehouse'] = 0;
                }

                $id_cache = $orderrow['product_attribute_id'].'_'.(int) $orderrow['id_warehouse'].'_'.(!empty($orderrow['id_shop']) ? (int) $orderrow['id_shop'] : '0');
                if (empty($orderrow['sc_attr_infos']))
                {
                    $sc_attr_infos = '';
                    if (empty($cache_attr[$id_cache]))
                    {
                        $sql_pa = 'SELECT pa.id_product_attribute,
                            '.(SCAS ? ' (SELECT wpl.location FROM '._DB_PREFIX_.'warehouse_product_location wpl WHERE wpl.id_product_attribute=pa.id_product_attribute AND wpl.id_warehouse='.(int) $orderrow['id_warehouse'].' LIMIT 1) AS location, ' : (version_compare(_PS_VERSION_, '8.0.0', '>=') ? ' sa.location AS location, ' : ' pa.location AS location, ')).'
                            '.(sc_in_array('location_old', $cols, 'ordGet_cols') && version_compare(_PS_VERSION_, '8.0.0', '<') ? ' pa.location AS location_old, ' : '').'
                             pas.wholesale_price 
                        FROM '._DB_PREFIX_.'product_attribute pa
                            LEFT JOIN '._DB_PREFIX_."product_attribute_shop pas ON (pa.id_product_attribute=pas.id_product_attribute AND pas.id_shop='".(int) $orderrow['id_shop']."')".
                            (version_compare(_PS_VERSION_, '8.0.0', '>=') ? ' LEFT JOIN '._DB_PREFIX_."stock_available sa ON (sa.id_product_attribute=pas.id_product_attribute AND sa.id_shop='".(int) $orderrow['id_shop']."') " : '')."
                        WHERE pa.id_product_attribute = '".(int) $orderrow['product_attribute_id']."'
                        LIMIT 1";
                        $res_temp = Db::getInstance()->executeS($sql_pa);
                        if (!empty($res_temp[0]))
                        {
                            $res_pa = $res_temp[0];
                            $cache_attr[$id_cache] = $res_pa;
                            $sc_attr_infos = json_encode($cache_attr[$id_cache]);
                        }
                    }
                    else
                    {
                        $sc_attr_infos = json_encode($cache_attr[$id_cache]);
                        $res_pa = $cache_attr[$id_cache];
                    }
                    if (!empty($sc_attr_infos))
                    {
                        $sql_attr_infos = 'UPDATE '._DB_PREFIX_."order_detail SET sc_attr_infos_v1='".pSQL($sc_attr_infos)."' WHERE id_order_detail = '".(int) $orderrow['id_order_detail']."'";
                        Db::getInstance()->execute($sql_attr_infos);
                    }
                }
                else
                {
                    if (empty($cache_attr[$id_cache]))
                    {
                        $cache_attr[$id_cache] = json_decode($orderrow['sc_attr_infos'], true);
                    }
                    $res_pa = $cache_attr[$id_cache];
                }
                if (!empty($res_pa))
                {
                    foreach ($res_pa as $key => $pa_field)
                    {
                        if ($key == 'location')
                        {
                            continue;
                        }
                        $orderrow[$key] = $pa_field;
                    }
                }
            }

            if (version_compare(_PS_VERSION_, '1.7.7.6', '>='))
            {
                $_POST['setShopContext'] = 's-'.(int) $orderrow['id_shop'];
                $context = Context::getContext();
                $context->currency = Currency::getCurrencyInstance((int) $orderrow['id_currency']);
            }

            $avanced_quantities = ['physical_quantity' => 0, 'usable_quantity' => 0];
            $actual_prices = ['price_wt' => 0, 'price_it' => 0, 'price_reduction_wt' => 0, 'price_reduction_it' => 0];
            $tax = [];
            $type_advanced_stock_management = 1; // Not Advanced Stock Management
            $is_advanced_stock_management = false;
            $has_combination = false;
            $not_in_warehouse = true;
            $without_warehouse = true;
            if ($view == 'grid_picking' || sc_in_array('id_order_detail', $cols, 'ordGet_cols'))
            {
                $id_prd = $orderrow['product_id'];
                $id_prd_attr = 0;
                if (strpos($orderrow['product_id'], '-') !== false)
                {
                    $exp = explode('-', $orderrow['product_id']);
                    $id_prd = $exp[0];
                    $id_prd_attr = $exp[1];
                }

                // ACTUAL PRODUCT PRICE
                if ((sc_in_array('actual_product_price_wt', $cols, 'ordGet_cols') || sc_in_array('actual_product_price_it', $cols, 'ordGet_cols') || sc_in_array('actual_product_price_reduction_wt', $cols, 'ordGet_cols') || sc_in_array('actual_product_price_reduction_it', $cols, 'ordGet_cols')))
                {
                    $shop_row = 1;
                    if (!empty($orderrow['id_shop']))
                    {
                        $shop_row = $orderrow['id_shop'];
                    }

                    if (sc_in_array('actual_product_price_reduction_wt', $cols, 'ordGet_cols') || sc_in_array('actual_product_price_reduction_it', $cols, 'ordGet_cols'))
                    {
                        $actual_prices = SCI::getPrice($id_prd, $id_prd_attr, $shop_row, true);
                    }
                    else
                    {
                        $actual_prices = SCI::getPrice($id_prd, $id_prd_attr, $shop_row);
                    }
                }
                // IN STOCK
                $orderrow['instock'] = 0;
                if (sc_in_array('instock', $cols, 'ordGet_cols') || sc_in_array('product_quantity_in_stock', $cols, 'ordGet_cols'))
                {
                    $color_instock = '';
                    $order_in_stock = ($orderrow['product_quantity_in_stock'] >= $orderrow['product_quantity'] ? 1 : 0);
                    if ($order_in_stock == 1)
                    {
                        $orderrow['instock'] = 1;
                    }
                    else
                    {
                        $total_qty_wanted = 0;
                        if (!empty($id_prd))
                        {
                            $actual_quantity_in_stock = SCI::getProductQty((int) $id_prd, (int) $id_prd_attr, (!empty($orderrow['id_warehouse']) ? $orderrow['id_warehouse'] : null));

                            // Dans le cas où le stock au moment de la commande
                            // est négatif, il faut utiliser la différence
                            // de stock pour savoir combien de produits il y a
                            // actuellement par rapport au passage de la commande
                            // Exemple : -15 à la commande et -10 actuellement => 5 en stock
                            if ($orderrow['product_quantity_in_stock'] < 0 && $actual_quantity_in_stock >= $orderrow['product_quantity_in_stock'])
                            {
                                $actual_quantity_in_stock -= $orderrow['product_quantity_in_stock'];
                            }

                            $sql_details = 'SELECT product_quantity FROM '._DB_PREFIX_.'order_detail WHERE product_id='.(int) $id_prd.' AND product_attribute_id='.(int) $id_prd_attr;
                            $res_details = Db::getInstance()->executeS($sql_details);
                            foreach ($res_details as $res_detail)
                            {
                                $total_qty_wanted += $res_detail['product_quantity'];
                            }

                            if ($actual_quantity_in_stock >= $orderrow['product_quantity'])
                            {
                                $orderrow['instock'] = 1;
                            }
                            if ($actual_quantity_in_stock < $total_qty_wanted && $actual_quantity_in_stock > 0)
                            {
                                $orderrow['instock'] = 3;
                                $color_instock = '#FF9900';
                            }
                        }
                    }
                    if ($orderrow['instock'] == 0 && empty($color_instock))
                    {
                        $color_instock = '#FF0000';
                    }
                }
                /*
                 * SCAS
                 */
                if (sc_in_array('quantity_physical', $cols, 'ordGet_cols') || sc_in_array('quantity_usable', $cols, 'ordGet_cols') || sc_in_array('quantity_real', $cols, 'ordGet_cols'))
                {
                    if (SCAS)
                    {
                        // Produit utilise la gestion avancée
                        if ($orderrow['advanced_stock_management'] == 1)
                        {
                            $is_advanced_stock_management = true;
                            $type_advanced_stock_management = 2; // With Advanced Stock Management

                            // Produit est lié à l'entrepôt
                            $temp_check_in_warehouse = WarehouseProductLocation::getIdByProductAndWarehouse((int) $orderrow['product_id'], (int) $orderrow['product_attribute_id'], (int) $orderrow['id_warehouse']);
                            if (!empty($temp_check_in_warehouse))
                            {
                                $not_in_warehouse = false;
                                $without_warehouse = false;
                            }

                            // Produit lié à au moins un entrepôt
                            if ($not_in_warehouse)
                            {
                                $query = new DbQuery();
                                $query->select('wpl.id_warehouse_product_location');
                                $query->from('warehouse_product_location', 'wpl');
                                $query->where('wpl.id_product = '.(int) $orderrow['product_id'].'
                                    AND wpl.id_product_attribute = '.(int) $orderrow['product_attribute_id'].'
                                    AND wpl.id_warehouse != 0'
                                );
                                $rslt = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
                                if (count($rslt) > 0)
                                {
                                    $without_warehouse = false;
                                }
                            }

                            if (!StockAvailable::dependsOnStock((int) $orderrow['product_id'], $orderrow['id_shop']))
                            {
                                $type_advanced_stock_management = 3;
                            }// With Advanced Stock Management + Manual management
                        }

                        if (!empty($orderrow['product_attribute_id']) && !$not_in_warehouse)
                        {
                            $query = new DbQuery();
                            $query->select('st.physical_quantity');
                            $query->select('st.usable_quantity');
                            $query->from('stock', 'st');
                            $query->innerJoin('warehouse_product_location', 'wpl', '(wpl.id_product = st.id_product AND wpl.id_product_attribute = st.id_product_attribute AND wpl.id_warehouse = '.(int) $orderrow['id_warehouse'].')');
                            $query->where('st.id_product = '.(int) $orderrow['product_id']);
                            $query->where('st.id_warehouse = '.(int) $orderrow['id_warehouse']);
                            $query->where('st.id_product_attribute = '.(int) $orderrow['product_attribute_id']);
                            $avanced_quantities = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query);
                        }
                        elseif (!$not_in_warehouse)
                        {
                            $query = new DbQuery();
                            $query->select('st.physical_quantity');
                            $query->select('st.usable_quantity');
                            $query->from('stock', 'st');
                            $query->where('id_product = '.(int) $orderrow['product_id']);
                            $query->where('id_warehouse = '.(int) $orderrow['id_warehouse']);
                            $avanced_quantities = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query);
                        }
                    }
                }

                echo '<row id="'.$orderrow['unique_id'].'">';
                echo '<userdata name="id_order">'.(int) $orderrow['id_order'].'</userdata>';
                echo '<userdata name="id_customer">'.(int) $orderrow['id_customer'].'</userdata>';
                echo '<userdata name="id_shop_customer">'.(int) $orderrow['id_shop_customer'].'</userdata>';
            }
            else
            {
                echo '<row id="'.$orderrow['id_order'].'">';
                echo '<userdata name="id_order">'.(int) $orderrow['id_order'].'</userdata>';
                echo '<userdata name="id_customer">'.(int) $orderrow['id_customer'].'</userdata>';
                echo '<userdata name="id_shop_customer">'.(int) $orderrow['id_shop_customer'].'</userdata>';

                // IN STOCK
                $orderrow['instock'] = 1;
                if (sc_in_array('instock', $cols, 'ordGet_cols') || sc_in_array('product_quantity_in_stock', $cols, 'ordGet_cols'))
                {
                    $color_instock = '';
                    $sql_details = 'SELECT * FROM '._DB_PREFIX_.'order_detail WHERE id_order='.(int) $orderrow['id_order'];
                    $res_details = Db::getInstance()->executeS($sql_details);
                    foreach ($res_details as $res_detail)
                    {
                        $order_in_stock = ($res_detail['product_quantity_in_stock'] >= $res_detail['product_quantity'] ? 1 : 0);
                        if ($order_in_stock == 0)
                        {
                            $total_qty_wanted = 0;
                            $id_prd = $res_detail['product_id'];
                            $id_prd_attr = $res_detail['product_attribute_id'];
                            if (!empty($id_prd))
                            {
                                $actual_quantity_in_stock = SCI::getProductQty((int) $id_prd, (int) $id_prd_attr, (!empty($orderrow['id_warehouse']) ? $orderrow['id_warehouse'] : null));

                                // Dans le cas où le stock au moment de la commande
                                // est négatif, il faut utiliser la différence
                                // de stock pour savoir combien de produits il y a
                                // actuellement par rapport au passage de la commande
                                // Exemple : -15 à la commande et -10 actuellement => 5 en stock
                                if ($res_detail['product_quantity_in_stock'] < 0 && $actual_quantity_in_stock >= $res_detail['product_quantity_in_stock'])
                                {
                                    $actual_quantity_in_stock -= $res_detail['product_quantity_in_stock'];
                                }

                                $sql_details = 'SELECT product_quantity FROM '._DB_PREFIX_.'order_detail WHERE product_id='.(int) $id_prd.' AND product_attribute_id='.(int) $id_prd_attr;
                                $res_details = Db::getInstance()->executeS($sql_details);
                                foreach ($res_details as $res_detail)
                                {
                                    $total_qty_wanted += $res_detail['product_quantity'];
                                }

                                if ($orderrow['instock'] != 0 && $actual_quantity_in_stock < $total_qty_wanted && $actual_quantity_in_stock > 0)
                                {
                                    $orderrow['instock'] = 2;
                                    $color_instock = '#FF9900';
                                }
                                if ($actual_quantity_in_stock <= 0 || (isset($orderrow['product_quantity']) && $actual_quantity_in_stock < $orderrow['product_quantity']))
                                {
                                    $orderrow['instock'] = 0;
                                }
                            }
                        }
                    }
                    if ($orderrow['instock'] == 0)
                    {
                        $color_instock = '#FF0000';
                    }
                }
            }

            echo '<userdata name="open_cat_grid">'.(!empty($orderrow['id_category_default']) ? (int) $orderrow['id_category_default'].'-'.(int) $orderrow['product_id'] : '').'</userdata>';

            SC_Ext::readCustomOrdersGridsConfigXML('rowUserData', $orderrow);
            foreach ($cols as $key => $col)
            {
                switch ($col){
                    case 'id_order':
                        echo '<cell>'.$orderrow['id_order'].'</cell>';
                        break;
                    case 'default_group':
                        if (SCMS)
                        {
                            $group = new Group((int) $orderrow['default_group'], (int) $id_lang, (int) SCI::getSelectedShop());
                        }
                        else
                        {
                            $group = new Group((int) $orderrow['default_group'], (int) $id_lang);
                        }
                        echo '<cell><![CDATA['.$group->name.']]></cell>';
                        break;
                    case 'conversion_rate':
                    case 'total_discounts_tax_incl':
                    case 'total_discounts_tax_excl':
                    case 'total_paid':
                    case 'total_paid_tax_incl':
                    case 'total_paid_tax_excl':
                    case 'total_remaining_paid':
                    case 'total_products':
                    case 'total_products_wt':
                    case 'total_wrapping_tax_incl':
                    case 'total_wrapping_tax_excl':
                        echo '<cell>'.number_format($orderrow[$col], ((int) _s('ORD_ORDER_GRID_PRICEDECIMAL') > 6 || (int) _s('ORD_ORDER_GRID_PRICEDECIMAL') < 0 ? 6 : _s('ORD_ORDER_GRID_PRICEDECIMAL')), '.', '').'</cell>';
                        break;
                    case 'actual_product_price_wt':
                        echo '<cell>'.number_format($actual_prices['price_wt'], ((int) _s('ORD_ORDER_GRID_PRICEDECIMAL') > 6 || (int) _s('ORD_ORDER_GRID_PRICEDECIMAL') < 0 ? 6 : _s('ORD_ORDER_GRID_PRICEDECIMAL')), '.', '').'</cell>';
                        break;
                    case 'actual_product_price_it':
                        echo '<cell>'.number_format($actual_prices['price_it'], ((int) _s('ORD_ORDER_GRID_PRICEDECIMAL') > 6 || (int) _s('ORD_ORDER_GRID_PRICEDECIMAL') < 0 ? 6 : _s('ORD_ORDER_GRID_PRICEDECIMAL')), '.', '').'</cell>';
                        break;
                    case 'actual_product_price_reduction_wt':
                        echo '<cell>'.number_format($actual_prices['price_reduction_wt'], ((int) _s('ORD_ORDER_GRID_PRICEDECIMAL') > 6 || (int) _s('ORD_ORDER_GRID_PRICEDECIMAL') < 0 ? 6 : _s('ORD_ORDER_GRID_PRICEDECIMAL')), '.', '').'</cell>';
                        break;
                    case 'actual_product_price_reduction_it':
                        echo '<cell>'.number_format($actual_prices['price_reduction_it'], ((int) _s('ORD_ORDER_GRID_PRICEDECIMAL') > 6 || (int) _s('ORD_ORDER_GRID_PRICEDECIMAL') < 0 ? 6 : _s('ORD_ORDER_GRID_PRICEDECIMAL')), '.', '').'</cell>';
                        break;
                    case 'quantity_usable':
                        $editable = '';

                        $value = $avanced_quantities['usable_quantity'];
                        if ($type_advanced_stock_management != 2)
                        {
                            $value = '';
                        }

                        echo '<cell'.$editable.'>'.$value.'</cell>';
                        break;
                    case 'quantity_physical':
                        $editable = '';
                        $value = $avanced_quantities['physical_quantity'];
                        if ($type_advanced_stock_management != 2)
                        {
                            $value = '';
                        }

                        echo '<cell>'.$value.'</cell>';
                        break;
                    case 'quantity_real':
                        $editable = '';

                        $value = SCI::getProductRealQuantities($orderrow['product_id'],
                                (int) $orderrow['product_attribute_id'],
                                (int) $orderrow['id_warehouse'],
                                true,
                                $has_combination);
                        if ($type_advanced_stock_management != 2)
                        {
                            $value = '';
                        }

                        echo '<cell>'.$value.'</cell>';
                        break;
                    case 'product_quantity_in_stock':
                        echo '<cell>'.(int) $orderrow['product_quantity_in_stock'].'</cell>';
                        break;
                    case 'is_pack':
                        $style = $title = '';
                        if (isset($orderrow['color']))
                        {
                            $style = 'background-color:'.$orderrow['color'].';';
                        }
                        if (isset($orderrow['is_pack_tooltip']) && $orderrow['is_pack_tooltip'] != '')
                        {
                            $title = 'title="'.htmlspecialchars($orderrow['is_pack_tooltip']).'"';
                        }
                        echo '<cell style="'.$style.'" '.$title.'><![CDATA['.(int) $orderrow[$col].']]></cell>';
                        break;
                    case 'product_name':
                        if ((int) _s('ORD_ORDER_DETAIL_PRODUCT_NAME'))
                        {
                            $prod_name = $orderrow['order_detail_product_name'];
                        }
                        else
                        {
                            $explodedIds = explode('-', $orderrow['product_id']);
                            $id_product = $explodedIds[0];
                            $id_product_attribute = (isset($explodedIds[1]) ? $explodedIds[1] : 0);
                            $combination_detail = null;
                            $key = null;
                            if (!empty($orderrow['id_shop']))
                            {
                                $prod = new Product($id_product, false, $id_lang, $orderrow['id_shop']);
                            }
                            else
                            {
                                $prod = new Product($id_product, false, $id_lang);
                            }
                            if (empty($orderrow['product_name']))
                            {
                                $orderrow['product_name'] = $prod->name;
                            }
                            if (!empty($id_product_attribute))
                            {
                                $attributes = $prod->getAttributesResume($id_lang);
                                if (!empty($attributes))
                                {
                                    foreach ($attributes as $attr)
                                    {
                                        if ($attr['id_product_attribute'] == $id_product_attribute)
                                        {
                                            $combination_detail = $attr['attribute_designation'];
                                            break;
                                        }
                                    }
                                }
                            }
                            $prod_name = $orderrow['product_name'].(!empty($combination_detail) ? ' '.$combination_detail : '');
                            if (!empty($id_product_attribute) && empty($combination_detail))
                            {
                                $prod_name = $orderrow['order_detail_product_name'];
                                $prod_name = $orderrow['order_detail_product_name'];
                            }
                        }
                        echo '<cell><![CDATA['.$prod_name.']]></cell>';
                        break;
                    case 'wholesale_price':
                        echo '<cell>'.number_format($orderrow['wholesale_price'], (_s('CAT_PROD_WHOLESALEPRICE4DEC') ? 4 : 2), '.', '').'</cell>';
                        break;
                    case 'original_wholesale_price':
                        echo '<cell>'.number_format($orderrow['original_wholesale_price'], (_s('CAT_PROD_WHOLESALEPRICE4DEC') ? 4 : 2), '.', '').'</cell>';
                        break;
                    case 'product_price':
                        echo '<cell>'.number_format($orderrow['product_price'], 2, '.', '').'</cell>';
                        break;
                    case 'msg':
                        echo '<cell'.($orderrow['msg_count'] - $orderrow['msg_read'] > 0 ? ' bgColor="#FF0000"  style="color:#FFFFFF"' : '').'><![CDATA['.($orderrow['msg_count'] - $orderrow['msg_read']).'/'.$orderrow['msg_count'].']]></cell>';
                        break;
                    case 'pdf':
                        echo '<cell><![CDATA[';
                        if ($orderrow['invoice_number'])
                        {
                            echo '<a target="_blank" href="'.SC_PS_PATH_ADMIN_REL.'index.php?controller=AdminPdf&submitAction=generateInvoicePDF&id_order='.$orderrow['id_order'].'&token='.$sc_agent->getPSToken('AdminPdf').'"><i class="fad fa-file-invoice in_grid" title="'._l('Download invoice').'"></i></a> ';
                        }
                        if ($orderrow['delivery_number'])
                        {
                            echo '<a target="_blank" href="'.SC_PS_PATH_ADMIN_REL.'index.php?controller=AdminPdf&submitAction=generateDeliverySlipPDF&id_order='.$orderrow['id_order'].'&token='.$sc_agent->getPSToken('AdminPdf').'"><i class="fad fa-truck in_grid" title="'._l('Download delivery slip').'"></i></a> ';
                        }

                        $sql_slips = 'SELECT oslip.*';
                        $sql_slips .= ' FROM '._DB_PREFIX_.'order_slip oslip';
                        $sql_slips .= " WHERE oslip.id_order = '".(int) $orderrow['id_order']."'";
                        $sql_slips .= ' ORDER BY oslip.date_add DESC';
                        $slips = Db::getInstance()->executeS($sql_slips);
                        if (!empty($slips[0]['id_order_slip']))
                        {
                            echo '<a target="_blank" href="'.SC_PS_PATH_ADMIN_REL.'index.php?controller=AdminPdf&submitAction=generateOrderSlipPDF&id_order_slip='.$slips[0]['id_order_slip'].'&token='.$sc_agent->getPSToken('AdminPdf').'"><i class="fad fa-file-invoice-dollar in_grid" title="'._l('Download credit slip').'"></i></a> ';
                        }

                        echo ']]></cell>';
                        break;
                    case 'status':
                        $bgColor = 'bgColor="'.$orderStatus[$orderrow['current_state']]['color'].'"';
                        $txtColor = SCI::getBrightness($orderStatus[$orderrow['current_state']]['color']) < 128 ? ' style="color:#FFFFFF"' : ' style="color:#383838"';
                        $value = ($view == 'grid_picking' || sc_in_array('id_order_detail', $cols, 'ordGet_cols') || _r('ACT_ORD_UPDATE_STATUS') != '1' ? $orderStatus[$orderrow['current_state']]['name'] : $orderrow['current_state']);
                        if ($orderStatus[$orderrow['current_state']]['name'] === '')
                        {
                            $bgColor = 'bgColor="#EFEFEF"';
                            $value = $txtColor = '';
                        }
                        echo '<cell '.$bgColor.$txtColor.'><![CDATA['.$value.']]></cell>';
                        break;
                    case 'instock':
                        if ($orderrow['instock'] == 2)
                        {
                            $instock = _l('Insufficient current total stock');
                        }
                        elseif ($orderrow['instock'] == 3)
                        {
                            $instock = _l('Partial');
                        }
                        else
                        {
                            $instock = $yesno[$orderrow['instock']];
                        }
                        echo '<cell'.(!empty($color_instock) ? ' bgColor="'.$color_instock.'"  style="color:#FFFFFF"' : '').'>'.$instock.'</cell>';
                        break;
                    case 'id_carrier':
                        echo '<cell><![CDATA['.$orderrow['id_carrier'].']]></cell>';
                        break;
                    case 'del_id_state':
                        echo '<cell><![CDATA['.(string) $orderState[$orderrow['del_id_state']].']]></cell>';
                        break;
                    case 'order_weight':
                        $sql_weight = ' SELECT (product_quantity * product_weight) AS detail_weight';
                        $sql_weight .= ' FROM '._DB_PREFIX_.'order_detail';
                        $sql_weight .= " WHERE id_order = '".(int) $orderrow['id_order']."' ";
                        $weight = 0;
                        $res_weight = Db::getInstance()->executeS($sql_weight);
                        foreach ($res_weight as $detail_weight)
                        {
                            $weight += floatval($detail_weight['detail_weight']);
                        }

                        echo '<cell><![CDATA['.$weight.']]></cell>';
                        break;
                    case 'total_assets':
                        $total_assets = 0;
                        $sql_assets = ' SELECT SUM(amount) AS total_assets';
                        $sql_assets .= ' FROM '._DB_PREFIX_.'order_slip';
                        $sql_assets .= " WHERE id_order = '".(int) $orderrow['id_order']."' ";
                        $res_assets = Db::getInstance()->executeS($sql_assets);
                        if (!empty($res_assets[0]['total_assets']))
                        {
                            $total_assets = $res_assets[0]['total_assets'];
                        }

                        echo '<cell>'.$total_assets.'</cell>';
                        break;
                    case 'total_product_quantity':
                        $sql_qty = ' SELECT SUM(product_quantity) AS total_product_quantity';
                        $sql_qty .= ' FROM '._DB_PREFIX_.'order_detail';
                        $sql_qty .= " WHERE id_order = '".(int) $orderrow['id_order']."' ";
                        $total_product_quantity = 0;
                        $res_qty = Db::getInstance()->executeS($sql_qty);
                        if (!empty($res_qty[0]['total_product_quantity']))
                        {
                            $total_product_quantity = ceil($res_qty[0]['total_product_quantity']);
                        }

                        echo '<cell>'.$total_product_quantity.'</cell>';
                        break;
                    case 'actual_quantity_in_stock':
                        echo '<cell>'.SCI::getProductQty($orderrow['product_id'], $orderrow['product_attribute_id'], (!empty($orderrow['id_warehouse']) ? $orderrow['id_warehouse'] : null)).'</cell>';
                        break;
                    case 'id_lang':
                        echo '<cell><![CDATA['.$orderLanguage[$orderrow['id_lang']]['name'].']]></cell>';
                        break;
                    case 'id_currency':
                        echo '<cell><![CDATA['.$orderCurrency[$orderrow['id_currency']]['name'].']]></cell>';
                        break;

                    case 'inv_postcode':
                        echo '<cell><![CDATA['.$orderrow['inv_postcode'].']]></cell>';
                        break;
                    case 'del_postcode':
                        echo '<cell><![CDATA['.$orderrow['del_postcode'].']]></cell>';
                        break;
                    case 'id_warehouse':
                        echo '<cell><![CDATA['.$orderrow['warehousename'].']]></cell>';
                        break;
                    case 'payment':
                        echo '<cell><![CDATA['.str_replace('&', '-', $orderrow['payment']).']]></cell>';
                        break;
                    case 'default_category':
                        echo '<cell><![CDATA['.$orderrow['category_name'].']]></cell>';
                        break;
                    case 'total_wholesale_price':
                        $orderrow['total_wholesale_price'] = '0';
                        $sql_ods = ' SELECT * FROM '._DB_PREFIX_."order_detail od
                            WHERE od.id_order = '".(int) $orderrow['id_order']."'";
                        $ods = Db::getInstance()->executeS($sql_ods);
                        foreach ($ods as $od)
                        {
                            if (!empty($od['product_attribute_id']))
                            {
                                $sql_wholesale = ' SELECT SUM(ps.wholesale_price * od.product_quantity) AS total_wholesale_price';
                                $sql_wholesale .= ' FROM '._DB_PREFIX_.'product_attribute_shop ps
                                    INNER JOIN '._DB_PREFIX_."order_detail od ON (od.product_attribute_id = ps.id_product_attribute AND od.id_order_detail = '".(int) $od['id_order_detail']."' )";
                                $sql_wholesale .= " WHERE ps.id_shop = '".(int) $orderrow['id_shop']."'";
                                $result = Db::getInstance()->executeS($sql_wholesale);
                                if (!empty($result[0]['total_wholesale_price']))
                                {
                                    $orderrow['total_wholesale_price'] += $result[0]['total_wholesale_price'];
                                }
                                else
                                {
                                    $sql_wholesale = ' SELECT SUM(ps.wholesale_price * od.product_quantity) AS total_wholesale_price';
                                    $sql_wholesale .= ' FROM '._DB_PREFIX_.'product_shop ps
                                    INNER JOIN '._DB_PREFIX_."order_detail od ON (od.product_id = ps.id_product AND od.id_order_detail = '".(int) $od['id_order_detail']."' )";
                                    $sql_wholesale .= " WHERE ps.id_shop = '".(int) $orderrow['id_shop']."'";
                                    $result = Db::getInstance()->executeS($sql_wholesale);
                                    if (!empty($result[0]['total_wholesale_price']))
                                    {
                                        $orderrow['total_wholesale_price'] += $result[0]['total_wholesale_price'];
                                    }
                                }
                            }
                            else
                            {
                                $sql_wholesale = ' SELECT SUM(ps.wholesale_price * od.product_quantity) AS total_wholesale_price';
                                $sql_wholesale .= ' FROM '._DB_PREFIX_.'product_shop ps
                                    INNER JOIN '._DB_PREFIX_."order_detail od ON (od.product_id = ps.id_product AND od.id_order_detail = '".(int) $od['id_order_detail']."' )";
                                $sql_wholesale .= " WHERE ps.id_shop = '".(int) $orderrow['id_shop']."'";
                                $result = Db::getInstance()->executeS($sql_wholesale);
                                if (!empty($result[0]['total_wholesale_price']))
                                {
                                    $orderrow['total_wholesale_price'] += $result[0]['total_wholesale_price'];
                                }
                            }
                        }
                        echo '<cell><![CDATA['.number_format($orderrow['total_wholesale_price'], 2, '.', '').']]></cell>';
                        break;
                    case 'del_other':
                        echo '<cell><![CDATA['.str_replace(["\n", "\r"], '', $orderrow[$col]).']]></cell>';
                        break;
                    case 'delivery_date':
                        $dates = Db::getInstance()->getRow('SELECT delivery_date,date_add
                                                                            FROM '._DB_PREFIX_.'order_invoice
                                                                            WHERE id_order = '.(int) $orderrow['id_order'].'
                                                                            AND delivery_number !=""');
                        if (!$dates)
                        {
                            echo '<cell></cell>';
                            break;
                        }
                        if (!empty($dates['delivery_date']))
                        {
                            echo '<cell><![CDATA['.$dates['delivery_date'].']]></cell>';
                        }
                        else
                        {
                            echo '<cell><![CDATA['.$dates['date_add'].']]></cell>';
                        }
                        break;
                    case 'date_add':
                    case 'invoice_date':
                        if (_s('ORD_DATES_ADD_INVOICE_INTERFACE') && !empty($cache_dates)
                            && array_key_exists($orderrow['reference'], $cache_dates)
                            && !empty($cache_dates[$orderrow['reference']][$col]))
                        {
                            echo '<cell><![CDATA['.$cache_dates[$orderrow['reference']][$col].']]></cell>';
                        }
                        else
                        {
                            echo '<cell><![CDATA['.$orderrow[$col].']]></cell>';
                        }
                        break;
                    case 'tracking_url':
                        $row_value = '';
                        if (array_key_exists('shipping_number', $orderrow) && !empty($orderrow['shipping_number']))
                        {
                            $shipping_number = (string) $orderrow['shipping_number'];
                            if (array_key_exists($orderrow['id_carrier'], $orderCarrier))
                            {
                                $row_carrier = $orderCarrier[$orderrow['id_carrier']];
                                if (!empty($row_carrier['url']))
                                {
                                    $url_tracking = (string) str_replace('@', $shipping_number, $row_carrier['url']);
                                    $icon = '<i class="fad fa-rabbit-fast in_grid" title="'._l('Open tracking url').'"></i>';
                                    $row_value = '<a target="_blank" href="'.$url_tracking.'">'.$icon.'</a>';
                                }
                            }
                        }
                         echo '<cell><![CDATA['.$row_value.']]></cell>';
                        break;
                    default:
                        SC_Ext::readCustomOrdersGridsConfigXML('rowData');
                        if (sc_array_key_exists('buildDefaultValue', $colSettings[$col]) && $colSettings[$col]['buildDefaultValue'] != '')
                        {
                            if ($colSettings[$col]['buildDefaultValue'] == 'ID')
                            {
                                echo '<cell>ID'.$orderrow['product_id'].'</cell>';
                            }
                        }
                        else
                        {
                            if ($orderrow[$col] == '' || $orderrow[$col] === 0 || $orderrow[$col] === 1)
                            { // opti perf is_numeric($orderrow[$col]) ||
                                echo '<cell><![CDATA['.$orderrow[$col].']]></cell>';
                            }
                            else
                            {
                                echo '<cell><![CDATA['.$orderrow[$col].']]></cell>';
                            }
                        }
                }
            }
            echo "</row>\n";
        }
    }

    /**
     * fusionne les packs modifiés dans la liste des produits.
     *
     * @param int $id_lang
     *
     * @return void
     */
    function injectPackProductDetails(array &$res, $id_lang)
    {
        $packs_orders = listPackOrders($res);
        if (!empty($packs_orders))
        {
            $pack_items = processPackInfos($packs_orders, (int) $id_lang);
            $res = array_merge($res, $pack_items);
            // suppression des entrées des produits pack pour ne conserver que les produits qui le composent
            $res = array_filter($res, function ($v) use ($pack_items)
            {
                return isset($pack_items[$v['unique_id']]) || $v['is_pack'] != '1';
            });
        }
    }

    /**
     * * récupération des orders correspondant a des packs indéxé par unique_id.
     *
     * @return array
     */
    function listPackOrders(array $res)
    {
        $packsOrders = $res;
        foreach ($res as $key => $order)
        {
            if ($order['is_pack'] != 1)
            {
                unset($packsOrders[$key]);
            }
        }

        return $packsOrders;
    }

    /**
     * modification des infos order des packes.
     *
     * @param int $id_lang
     *
     * @return array
     */
    function processPackInfos(array $packOrders, $id_lang)
    {
        foreach ($packOrders as $id => $order)
        {
            $pack_items = Pack::getItems($id, (int) $id_lang);
            foreach ($pack_items as $pack_item)
            {
                $name = $pack_item->id_pack_product_attribute ? $pack_item->attribute_name : $pack_item->name;
                // utilité ?
                $sc_attr_info_current = json_decode($packOrders[$id]['sc_attr_infos'], true);
                if (isset($sc_attr_info_current['location']) && $sc_attr_info_current['location'] != '')
                {
                    $sc_attr_info_current['location'] = $packOrders[$id]['location'];
                }
                $uniqueId = $pack_item->id.'-'.$pack_item->id_pack_product_attribute.'-'.$order['id_order'];
                $packOrders[$uniqueId] = $order;
                /* @note original_wholesale_price et product_price_tax_incl non disponibles dans $pack_item */
                $overrideProductInfos = [
                    'product_name' => $name,
                    'is_pack_tooltip' => _l('Product is part of ordered product \'%s\' (#%s)', false, [$order['product_name'], $order['product_id']]),
                    'product_id' => $pack_item->id.'-'.$pack_item->id_pack_product_attribute,
                    'id_product' => $pack_item->id,
                    'unique_id' => $uniqueId,
                    'product_attribute_id' => $pack_item->id_pack_product_attribute,
                    'product_supplier_reference' => $pack_item->supplier_reference,
                    'product_ean13' => $pack_item->ean13,
                    'product_upc' => $pack_item->upc,
                    'product_quantity' => $order['product_quantity'] * $pack_item->pack_quantity,
                    'wholesale_price' => $pack_item->wholesale_price,
                    'product_price' => $pack_item->price,
                    'location' => $pack_item->location,
                    'product_quantity_in_stock' => $pack_item->out_of_stock,
                    'supplier_name' => $pack_item->supplier_name,
                    'color' => '#DDDDFF',
                    'sc_attr_infos' => json_encode($sc_attr_info_current),
                ];
                if (version_compare(_PS_VERSION_, '1.7.7.0', '>='))
                {
                    $overrideProductInfos['product_mpn'] = $pack_item->mpn;
                    $overrideProductInfos['product_isbn'] = $pack_item->isbn;
                }

                $packOrders[$uniqueId] = array_merge($packOrders[$uniqueId], $overrideProductInfos);
            }
            unset($packOrders[$id]);
        }

        return $packOrders;
    }

    if (stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml'))
    {
        header('Content-type: application/xhtml+xml');
    }
    else
    {
        header('Content-type: text/xml');
    }
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo '<rows><head>';
    echo getColSettingsAsXML();
    echo '<afterInit>
                    <call command="attachHeader"><param>'.getFilterColSettings().'</param></call>
                    <call command="attachFooter"><param><![CDATA['.getFooterColSettings().']]></param></call>
                </afterInit>';
    echo '</head>';

    $uiset = UISettings::getSetting('ord_grid_'.$view);
    if (!empty($uiset))
    {
        $tmp = explode('|', $uiset);
        $uiset = '|'.$tmp[1].'||'.$tmp[3];
    }
    echo '<userdata name="uisettings">'.$uiset.'</userdata>'."\n";

    echo '<userdata name="LIMIT_SMARTRENDERING">'.(int) _s('CAT_PROD_LIMIT_SMARTRENDERING').'</userdata>';
    $segment_params = [];
    if (SCSG && substr($current_id_segment, 0, 4) == 'seg_')
    {
        $segment_params['id_segment'] = (int) str_replace('seg_', '', $current_id_segment);
        $segment_params['segment_object'] = new ScSegment($segment_params['id_segment']);
        if ($segment_params['segment_object']->type == 'manual')
        {
            echo '<userdata name="manual_segment">1</userdata>'."\n";
        }
    }
    SC_Ext::readCustomOrdersGridsConfigXML('gridUserData');
    echo "\n";
    $dd = '';
    getOrders($segment_params, $dd);
    if (isset($_GET['DEBUG']))
    {
        echo '<az><![CDATA['.$dd.']]></az>';
    }
    echo '</rows>';
