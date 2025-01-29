<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

$return = [];

$licence = SCI::getConfigurationValue('SC_LICENSE_KEY');
if (empty($licence))
{
    $licence = 'demo';
}

$idShops = SCI::getConfigurationValue('SC_TRENDS_ID_SHOPS');
if (!empty($idShops))
{
    $idShops = json_decode($idShops, true);
}

$shops = ShopCore::getShops(false);
$has_results = false;
$segments = '';
$force_stop = false;

/*
 * Functions
 */
function getOrderDetailsBySegmentByShop($first, $last, $segment, $shop, $limit_order)
{
    $return = [];
    if (!empty($first) && !empty($last) && !empty($limit_order))
    {
        $start_order_id = $first;
        $end_order_id = $first + $limit_order - 1;
        if ($end_order_id > $last)
        {
            $end_order_id = $last;
        }

        $return = _getOrderDetailsBySegmentByShop($segment, $shop, $start_order_id, $end_order_id, $last, $limit_order);
    }

    return $return;
}
function _getOrderDetailsBySegmentByShop($segment, $shop, $start_order_id, $end_order_id, $last, $limit_order)
{
    $return = [];
    if (!empty($segment) && !empty($shop) && !empty($start_order_id) && !empty($end_order_id) && !empty($limit_order))
    {
        if ($segment['id_segment'] == '1')
        {
            $where = '';
            if (!empty($segment['id_start']))
            {
                $where .= ' AND od.id_order_detail > '.(int) $segment['id_start'].' ';
            }
            if (!empty($segment['id_end']))
            {
                $where .= ' AND od.id_order_detail <= '.(int) $segment['id_end'].' ';
            }
            if (!empty($segment['dateStart']))
            {
                $where .= ' AND "'.pSQL($segment['dateStart']).'" <= o.date_add ';
            }
            if (!empty($segment['dateEnd']))
            {
                $where .= ' AND o.date_add <= "'.pSQL($segment['dateEnd']).'" ';
            }
            $where .= ' AND o.id_shop = '.(int) $shop['id_shop'].' ';

            $where .= ' AND o.id_order >= '.(int) $start_order_id.' ';
            $where .= ' AND o.id_order <= '.(int) $end_order_id.' ';

            $sql = 'SELECT  o.id_order as order_id, od.id_order_detail as order_detail_id, od.product_quantity as quantity,
                            a_d.postcode as delivery_postcode, c_d.iso_code as delivery_country, a_d.company as delivery_company,
                            ca.name as carrier,
                            a_s.postcode as invoice_postcode,
                            o.total_shipping as shipping_cost,MAX(oh.date_add) as delivery_date, o.delivery_date as shipping_date,
                            p.width as product_width, p.height as product_height, p.depth as product_depth, p.weight as weight_kg
                        FROM '._DB_PREFIX_.'order_detail od
                            INNER JOIN '._DB_PREFIX_.'orders o ON (o.id_order = od.id_order)
                                INNER JOIN '._DB_PREFIX_.'carrier ca ON (ca.id_carrier = o.id_carrier)
                                INNER JOIN '._DB_PREFIX_.'address a_s ON (a_s.id_address = o.id_address_invoice)
                                INNER JOIN '._DB_PREFIX_.'address a_d ON (a_d.id_address = o.id_address_delivery)
                                    INNER JOIN '._DB_PREFIX_.'country c_d ON (a_d.id_country = c_d.id_country)
                                INNER JOIN '._DB_PREFIX_.'order_history oh ON (o.id_order = oh.id_order)
                            INNER JOIN '._DB_PREFIX_.'product p ON (od.product_id = p.id_product)
                        WHERE 1=1
                          AND o.valid = 1
                          '.$where.'
                        GROUP BY od.id_order_detail
                        ORDER BY od.id_order_detail ASC';
            $order_details = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            if (!empty($order_details) && count($order_details) > 0)
            {
                $datas = [];
                foreach ($order_details as $order_detail)
                {
                    if (!empty($order_detail['delivery_company']))
                    {
                        $order_detail['delivery_company'] = 1;
                    }
                    else
                    {
                        $order_detail['delivery_company'] = 0;
                    }
                    $datas[] = $order_detail;
                }
                $return = $datas;
            }
            elseif ($end_order_id < $last)
            {
                $start_order_id = $end_order_id + 1;
                $end_order_id = $start_order_id + $limit_order - 1;
                if ($end_order_id > $last)
                {
                    $end_order_id = $last;
                }

                $return = _getOrderDetailsBySegmentByShop($segment, $shop, $start_order_id, $end_order_id, $last, $limit_order);
            }
        }
        elseif ($segment['id_segment'] == '3')
        {
            /*
            order detail infos
            */
            $where = '';
            if (!empty($segment['id_start']))
            {
                $where .= ' AND od.id_order_detail > '.(int) $segment['id_start'].' ';
            }
            if (!empty($segment['id_end']))
            {
                $where .= ' AND od.id_order_detail <= '.(int) $segment['id_end'].' ';
            }
            if (!empty($segment['dateStart']))
            {
                $where .= ' AND "'.pSQL($segment['dateStart']).'" <= o.date_add ';
            }
            if (!empty($segment['dateEnd']))
            {
                $where .= ' AND o.date_add <= "'.pSQL($segment['dateEnd']).'" ';
            }
            $where .= ' AND o.id_shop = '.(int) $shop['id_shop'].' ';

            $where .= ' AND o.id_order >= '.(int) $start_order_id.' ';
            $where .= ' AND o.id_order <= '.(int) $end_order_id.' ';

            $sql = 'SELECT  od.id_order as order_id, od.id_order_detail as order_detail_id,
                            p.width as product_width, p.height as product_height, p.depth as product_depth
                        FROM '._DB_PREFIX_.'order_detail od
                            INNER JOIN '._DB_PREFIX_.'orders o ON (o.id_order = od.id_order)
                            INNER JOIN '._DB_PREFIX_.'product p ON (od.product_id = p.id_product)
                        WHERE 1=1
                          AND o.valid = 1
                          '.$where.'
                        GROUP BY od.id_order_detail
                        ORDER BY od.id_order_detail ASC';
            $order_details = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            if (!empty($order_details) && count($order_details) > 0)
            {
                $datas = [];
                foreach ($order_details as $order_detail)
                {
                    $datas[] = $order_detail;
                }
                $return = $datas;
            }
            elseif ($end_order_id < $last)
            {
                $start_order_id = $end_order_id + 1;
                $end_order_id = $start_order_id + $limit_order - 1;
                if ($end_order_id > $last)
                {
                    $end_order_id = $last;
                }

                $return = _getOrderDetailsBySegmentByShop($segment, $shop, $start_order_id, $end_order_id, $last, $limit_order);
            }
        }
    }

    return $return;
}

foreach ($shops as $shop)
{
    $maintenance = SCI::getConfigurationValue('SC_TRENDS_ID_SHOPS', null, 0, $shop['id_shop']);
    if ($maintenance == '1')
    {
        continue;
    }
    /*
     * Suscribe shop
     * (in case new shop)
     */
    $url = '';
    $protocol = Tools::getShopProtocol();
    $urlSql = Db::getInstance()->executeS('SELECT CONCAT(domain, physical_uri, virtual_uri) AS url
                FROM '._DB_PREFIX_.'shop_url
                WHERE id_shop = '.(int) $shop['id_shop'].'
                ORDER BY main DESC
                LIMIT 1');
    if (!empty($urlSql[0]['url']))
    {
        $url = $protocol.$urlSql[0]['url'];
    }
    $headers = [];
    $headers[] = 'SCLICENSE: '.$licence;
    $headers[] = 'EMAIL: '.SC_Agent::getInstance()->email;
    $headers[] = 'SHOPID: '.$shop['id_shop'];
    $headers[] = 'SHOPURL: '.$url;
    $headers[] = 'SCVERSION: '.SC_VERSION;
    if (!empty($idShops[$shop['id_shop']]))
    {
        $headers[] = 'IDSHOP: '.$idShops[$shop['id_shop']];
    }
    $return_register = sc_file_get_contents('http://api.storecommander.com/Trends/RegisterShop', 'POST', [], $headers);
    $return_register = json_decode($return_register, true);
    if (!empty($return_register['result']) && $return_register['result'] == 'OK' && !empty($return_register['code']) && $return_register['code'] == '200' && !empty($return_register['id']))
    {
        if (empty($idShops))
        {
            $idShops = [];
        }
        $exp = explode('_', $return_register['id']);
        $idShops[$exp[0]] = $exp[1];
        $idShops_encoded = json_encode($idShops);
        SCI::updateConfigurationValue('SC_TRENDS_ID_SHOPS', $idShops_encoded);
    }

    if (empty($idShops[$shop['id_shop']]))
    {
        continue;
    }
    /*
     * Get wanted segments
     */
    $headers = [];
    $headers[] = 'SCLICENSE: '.$licence;
    $headers[] = 'SHOPID: '.$shop['id_shop'];
    $headers[] = 'SCVERSION: '.SC_VERSION;
    $headers[] = 'IDSHOP: '.$idShops[$shop['id_shop']];
    $ask = sc_file_get_contents('http://api.storecommander.com/Trends/GetShopDataRequest', 'POST', [], $headers);
    $return_segments = json_decode($ask);
    if (empty($return_segments->result))
    {
        foreach ($return_segments as $segment)
        {
            $segment = (array) $segment;
            /*
             * Segment data logistic
             */
            if ($segment['id_segment'] == '1' || $segment['id_segment'] == '3')
            {
                $limit_order = (!empty($segment['limitCount']) ? (int) $segment['limitCount'] : '300');

                $where = '';
                if (!empty($segment['id_start']))
                {
                    $where .= ' AND od.id_order_detail > '.(int) $segment['id_start'].' ';
                }
                if (!empty($segment['id_end']))
                {
                    $where .= ' AND od.id_order_detail <= '.(int) $segment['id_end'].' ';
                }
                if (!empty($segment['dateStart']))
                {
                    $where .= ' AND "'.pSQL($segment['dateStart']).'" <= o.date_add ';
                }
                if (!empty($segment['dateEnd']))
                {
                    $where .= ' AND o.date_add <= "'.pSQL($segment['dateEnd']).'" ';
                }
                $where .= ' AND o.id_shop = '.(int) $shop['id_shop'].' ';

                /*
                 * Get Last order
                 * for asked period
                 */
                $last_order = 0;
                $sql = 'SELECT  o.id_order as order_id, od.id_order_detail as order_detail_id
                    FROM '._DB_PREFIX_.'order_detail od
                        INNER JOIN '._DB_PREFIX_.'orders o ON (o.id_order = od.id_order)
                    WHERE 1=1
                      AND o.valid = 1
                      '.$where.'
                    GROUP BY od.id_order_detail
                    ORDER BY od.id_order_detail DESC
                    LIMIT 1';
                $last_order_result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                if (!empty($last_order_result[0]['order_id']))
                {
                    $last_order = $last_order_result[0]['order_id'];
                }

                /*
                 * Get first order
                 * for asked period
                 */
                $first_order = 0;
                $sql = 'SELECT  o.id_order as order_id, od.id_order_detail as order_detail_id
                    FROM '._DB_PREFIX_.'order_detail od
                        INNER JOIN '._DB_PREFIX_.'orders o ON (o.id_order = od.id_order)
                    WHERE 1=1
                      AND o.valid = 1
                      '.$where.'
                    GROUP BY od.id_order_detail
                    ORDER BY od.id_order_detail ASC
                    LIMIT 1';
                $first_order_result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                if (!empty($first_order_result[0]['order_id']))
                {
                    $first_order = $first_order_result[0]['order_id'];
                }

                if (!empty($first_order) && !empty($last_order))
                {
                    $datas = getOrderDetailsBySegmentByShop($first_order, $last_order, $segment, $shop, $limit_order);
                    if (!empty($datas))
                    {
                        $post = ['id_segment' => $segment['id_segment'], 'data' => []];
                        $post['data'] = json_encode($datas);
                        $headers = [];
                        $headers[] = 'SCLICENSE: '.$licence;
                        $headers[] = 'SHOPID: '.$shop['id_shop'];
                        $headers[] = 'SCVERSION: '.SC_VERSION;
                        $headers[] = 'IDSHOP: '.$idShops[$shop['id_shop']];
                        $ret = sc_file_get_contents('http://api.storecommander.com/Trends/SendShopData', 'POST', $post, $headers);
                        $ret = (array) json_decode($ret);
                        if (!empty($ret['code']) && $ret['code'] == '200')
                        {
                            $has_results = true;
                            $segments .= '-'.$segment['id_segment'];
                        }
                    }
                }
            } /*
         * Segment info shop
         */
            elseif ($segment['id_segment'] == '6' || $segment['id_segment'] == '7' || $segment['id_segment'] == '8' || $segment['id_segment'] == '10')
            {
                if ($segment['id_segment'] == '6')
                {
                    $datas = [];

                    /*
                     * Post code shop
                     */
                    $postcode = '';
                    $sql = 'SELECT `value` FROM '._DB_PREFIX_.'configuration WHERE `name`="PS_SHOP_CODE" AND id_shop = '.(int) $shop['id_shop'];
                    $postcode_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                    if (!empty($postcode_query[0]['value']))
                    {
                        $postcode = $postcode_query[0]['value'];
                    }
                    else
                    {
                        $sql = 'SELECT c.`value` 
                          FROM '._DB_PREFIX_.'configuration c
                            INNER JOIN '._DB_PREFIX_.'shop s ON (c.id_shop_group = s.id_shop_group AND s.id_shop = '.(int) $shop['id_shop'].')
                          WHERE c.`name`="PS_SHOP_CODE" ';
                        $postcode_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                        if (!empty($postcode_query[0]['value']))
                        {
                            $postcode = $postcode_query[0]['value'];
                        }
                        else
                        {
                            $sql = 'SELECT `value` FROM '._DB_PREFIX_.'configuration WHERE `name`="PS_SHOP_CODE" AND (id_shop IS NULL OR id_shop=0) AND (id_shop_group IS NULL OR id_shop_group=0) ';
                            $postcode_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                            if (!empty($postcode_query[0]['value']))
                            {
                                $postcode = $postcode_query[0]['value'];
                            }
                        }
                    }

                    /*
                     * Country shop
                     */
                    $country = '';
                    $sql = 'SELECT ct.`iso_code` 
                        FROM '._DB_PREFIX_.'configuration c
                            INNER JOIN '._DB_PREFIX_.'country ct ON (c.`value`=ct.id_country)
                        WHERE c.`name`="PS_SHOP_COUNTRY_ID" 
                        AND c.id_shop = '.(int) $shop['id_shop'];
                    $country_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                    if (!empty($country_query[0]['iso_code']))
                    {
                        $country = $country_query[0]['iso_code'];
                    }
                    else
                    {
                        $sql = 'SELECT ct.`iso_code` 
                                FROM '._DB_PREFIX_.'configuration c
                                    INNER JOIN '._DB_PREFIX_.'country ct ON (c.`value`=ct.id_country)
                                    INNER JOIN '._DB_PREFIX_.'shop s ON (c.id_shop_group = s.id_shop_group AND s.id_shop = '.(int) $shop['id_shop'].')
                             WHERE c.`name`="PS_SHOP_COUNTRY_ID" ';
                        $country_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                        if (!empty($country_query[0]['iso_code']))
                        {
                            $country = $country_query[0]['iso_code'];
                        }
                        else
                        {
                            $sql = 'SELECT ct.`iso_code` 
                                FROM '._DB_PREFIX_.'configuration c
                                    INNER JOIN '._DB_PREFIX_.'country ct ON (c.`value`=ct.id_country)
                                WHERE c.`name`="PS_SHOP_COUNTRY_ID" 
                                 AND (c.id_shop IS NULL OR c.id_shop=0) AND (c.id_shop_group IS NULL OR c.id_shop_group=0) ';
                            $country_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                            if (!empty($country_query[0]['iso_code']))
                            {
                                $country = $country_query[0]['iso_code'];
                            }
                        }
                    }

                    /*
                     * Business Industry
                     */
                    $busIndus = '';
                    $sql = 'SELECT `value` FROM '._DB_PREFIX_.'configuration WHERE `name`="PS_SHOP_ACTIVITY" AND id_shop = '.(int) $shop['id_shop'];
                    $busIndus_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                    if (!empty($busIndus_query[0]['value']))
                    {
                        $busIndus = $busIndus_query[0]['value'];
                    }
                    else
                    {
                        $sql = 'SELECT c.`value` 
                              FROM '._DB_PREFIX_.'configuration c
                                INNER JOIN '._DB_PREFIX_.'shop s ON (c.id_shop_group = s.id_shop_group AND s.id_shop = '.(int) $shop['id_shop'].')
                              WHERE c.`name`="PS_SHOP_ACTIVITY" ';
                        $busIndus_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                        if (!empty($busIndus_query[0]['value']))
                        {
                            $busIndus = $busIndus_query[0]['value'];
                        }
                        else
                        {
                            $sql = 'SELECT `value` FROM '._DB_PREFIX_.'configuration WHERE `name`="PS_SHOP_ACTIVITY" AND (id_shop IS NULL OR id_shop=0) AND (id_shop_group IS NULL OR id_shop_group=0) ';
                            $busIndus_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                            if (!empty($busIndus_query[0]['value']))
                            {
                                $busIndus = $busIndus_query[0]['value'];
                            }
                        }
                    }

                    /*
                     * Weight unit
                     */
                    $weightUnit = '';
                    $sql = 'SELECT `value` FROM '._DB_PREFIX_.'configuration WHERE `name`="PS_WEIGHT_UNIT" AND id_shop = '.(int) $shop['id_shop'];
                    $weightUnit_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                    if (!empty($weightUnit_query[0]['value']))
                    {
                        $weightUnit = $weightUnit_query[0]['value'];
                    }
                    else
                    {
                        $sql = 'SELECT c.`value` 
                              FROM '._DB_PREFIX_.'configuration c
                                INNER JOIN '._DB_PREFIX_.'shop s ON (c.id_shop_group = s.id_shop_group AND s.id_shop = '.(int) $shop['id_shop'].')
                              WHERE c.`name`="PS_WEIGHT_UNIT" ';
                        $weightUnit_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                        if (!empty($weightUnit_query[0]['value']))
                        {
                            $weightUnit = $weightUnit_query[0]['value'];
                        }
                        else
                        {
                            $sql = 'SELECT `value` FROM '._DB_PREFIX_.'configuration WHERE `name`="PS_WEIGHT_UNIT" AND (id_shop IS NULL OR id_shop=0) AND (id_shop_group IS NULL OR id_shop_group=0) ';
                            $weightUnit_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                            if (!empty($weightUnit_query[0]['value']))
                            {
                                $weightUnit = $weightUnit_query[0]['value'];
                            }
                        }
                    }

                    /*
                     * Dimension unit
                     */
                    $dimensionUnit = '';
                    $sql = 'SELECT `value` FROM '._DB_PREFIX_.'configuration WHERE `name`="PS_DIMENSION_UNIT" AND id_shop = '.(int) $shop['id_shop'];
                    $dimensionUnit_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                    if (!empty($dimensionUnit_query[0]['value']))
                    {
                        $dimensionUnit = $dimensionUnit_query[0]['value'];
                    }
                    else
                    {
                        $sql = 'SELECT c.`value` 
                              FROM '._DB_PREFIX_.'configuration c
                                INNER JOIN '._DB_PREFIX_.'shop s ON (c.id_shop_group = s.id_shop_group AND s.id_shop = '.(int) $shop['id_shop'].')
                              WHERE c.`name`="PS_DIMENSION_UNIT" ';
                        $dimensionUnit_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                        if (!empty($dimensionUnit_query[0]['value']))
                        {
                            $dimensionUnit = $dimensionUnit_query[0]['value'];
                        }
                        else
                        {
                            $sql = 'SELECT `value` FROM '._DB_PREFIX_.'configuration WHERE `name`="PS_DIMENSION_UNIT" AND (id_shop IS NULL OR id_shop=0) AND (id_shop_group IS NULL OR id_shop_group=0) ';
                            $dimensionUnit_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                            if (!empty($dimensionUnit_query[0]['value']))
                            {
                                $dimensionUnit = $dimensionUnit_query[0]['value'];
                            }
                        }
                    }

                    /*
                     * Put in datas
                     */
                    $datas['shop_country'] = $country;
                    $datas['shop_postcode'] = $postcode;
                    $datas['shop_business_industry'] = $busIndus;
                    $datas['shop_weight_unit'] = $weightUnit;
                    $datas['shop_dimension_unit'] = $dimensionUnit;

                    if (!empty($datas))
                    {
                        $post = ['id_segment' => $segment['id_segment'], 'data' => []];
                        $post['data'] = json_encode($datas);
                        $headers = [];
                        $headers[] = 'SCLICENSE: '.$licence;
                        $headers[] = 'SHOPID: '.$shop['id_shop'];
                        $headers[] = 'SCVERSION: '.SC_VERSION;
                        $headers[] = 'IDSHOP: '.$idShops[$shop['id_shop']];
                        $ret = sc_file_get_contents('http://api.storecommander.com/Trends/SendShopData', 'POST', $post, $headers);
                        $ret = (array) json_decode($ret);
                        if (!empty($ret['code']) && $ret['code'] == '200')
                        {
                            $force_stop = true;
                        }
                        else
                        {
                            $has_results = true;
                            $segments .= '-'.$segment['id_segment'];
                        }
                    }
                }
                elseif ($segment['id_segment'] == '7')
                {
                    $datas = [];

                    /*
                     * Nb products
                     */
                    $nb_products = 0;
                    $sql = 'SELECT p.id_product 
                          FROM '._DB_PREFIX_.'product p 
                          INNER JOIN '._DB_PREFIX_.'product_shop ps ON (ps.id_product=p.id_product AND ps.id_shop="'.(int) $shop['id_shop'].'")
                        GROUP BY p.id_product';
                    $nb_products_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                    if (!empty($nb_products_query) && count($nb_products_query) > 0)
                    {
                        $nb_products = count($nb_products_query);
                    }

                    /*
                     * Nb combinations
                     */
                    $nb_combis = 0;
                    $sql = 'SELECT pa.id_product_attribute 
                    FROM '._DB_PREFIX_.'product_attribute pa 
                    INNER JOIN '._DB_PREFIX_.'product_attribute_shop pas ON (pas.id_product_attribute=pa.id_product_attribute AND pas.id_shop="'.(int) $shop['id_shop'].'")
                        GROUP BY pa.id_product_attribute';
                    $nb_combis_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                    if (!empty($nb_combis_query) && count($nb_combis_query) > 0)
                    {
                        $nb_combis = count($nb_combis_query);
                    }

                    /*
                     * Nb categories
                     */
                    $nb_cats = 0;
                    $sql = 'SELECT c.id_category 
                    FROM '._DB_PREFIX_.'category c 
                    INNER JOIN '._DB_PREFIX_.'category_shop cs ON (c.id_category=cs.id_category AND cs.id_shop="'.(int) $shop['id_shop'].'")
                    WHERE active=1
                    GROUP BY c.id_category';
                    $nb_cats_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                    if (!empty($nb_cats_query) && count($nb_cats_query) > 0)
                    {
                        $nb_cats = count($nb_cats_query);
                    }

                    /*
                     * Put in datas
                     */
                    $datas['shop_nb_products'] = $nb_products;
                    $datas['shop_nb_combis'] = $nb_combis;
                    $datas['shop_nb_categories'] = $nb_cats;
                    $datas['email'] = SC_Agent::getInstance()->email;

                    if (!empty($datas))
                    {
                        $post = ['id_segment' => $segment['id_segment'], 'data' => []];
                        $post['data'] = json_encode($datas);
                        $headers = [];
                        $headers[] = 'SCLICENSE: '.$licence;
                        $headers[] = 'SHOPID: '.$shop['id_shop'];
                        $headers[] = 'SCVERSION: '.SC_VERSION;
                        $headers[] = 'IDSHOP: '.$idShops[$shop['id_shop']];
                        $ret = sc_file_get_contents('http://api.storecommander.com/Trends/SendShopData', 'POST', $post, $headers);
                        $ret = (array) json_decode($ret);

                        $has_results = true;
                        $segments .= '-'.$segment['id_segment'];
                    }
                }
                elseif ($segment['id_segment'] == '8')
                {
                    $datas = [];

                    /*
                     * Put in datas
                     */
                    $datas['email'] = SC_Agent::getInstance()->email;

                    if (!empty($datas))
                    {
                        $post = ['id_segment' => $segment['id_segment'], 'data' => []];
                        $post['data'] = json_encode($datas);
                        $headers = [];
                        $headers[] = 'SCLICENSE: '.$licence;
                        $headers[] = 'SHOPID: '.$shop['id_shop'];
                        $headers[] = 'SCVERSION: '.SC_VERSION;
                        $headers[] = 'IDSHOP: '.$idShops[$shop['id_shop']];
                        $ret = sc_file_get_contents('http://api.storecommander.com/Trends/SendShopData', 'POST', $post, $headers);
                        $ret = (array) json_decode($ret);

                        $has_results = true;
                        $segments .= '-'.$segment['id_segment'];
                    }
                }
                elseif ($segment['id_segment'] == '10')
                {
                    $datas = [];

                    /*
                     * Rich Editor
                     */
                    $richeditor = 'ckeditor';
                    if (_s('APP_RICH_EDITOR') == 1)
                    {
                        $richeditor = 'tinymce';
                    }

                    /*
                     * MODULES
                     */
                    $modules = '-';
                    $sql = 'SELECT name FROM '._DB_PREFIX_.'module WHERE active=1';
                    $modules_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                    if (!empty($modules_query))
                    {
                        foreach ($modules_query as $module)
                        {
                            $modules .= $module['name'].'-';
                        }
                    }

                    /*
                     * LANGS
                     */
                    $nb_langs = '0';
                    $sql = 'SELECT id_lang FROM '._DB_PREFIX_.'lang WHERE active=1';
                    $langs_query = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                    if (!empty($langs_query))
                    {
                        $nb_langs = (int) count($langs_query);
                    }

                    /*
                     * Put in datas
                     */
                    $datas['richeditor'] = $richeditor;
                    $datas['modules'] = $modules;
                    $datas['nb_langs'] = $nb_langs;
                    $datas['email'] = SC_Agent::getInstance()->email;
                    $datas['php_version'] = sc_phpversion();
                    $datas['ps_version'] = _PS_VERSION_;

                    if (!empty($datas))
                    {
                        $post = ['id_segment' => $segment['id_segment'], 'data' => []];
                        $post['data'] = json_encode($datas);
                        $headers = [];
                        $headers[] = 'SCLICENSE: '.$licence;
                        $headers[] = 'SHOPID: '.$shop['id_shop'];
                        $headers[] = 'SCVERSION: '.SC_VERSION;
                        $headers[] = 'IDSHOP: '.$idShops[$shop['id_shop']];
                        $ret = sc_file_get_contents('http://api.storecommander.com/Trends/SendShopData', 'POST', $post, $headers);
                        $ret = (array) json_decode($ret);

                        $has_results = true;
                        $segments .= '-'.$segment['id_segment'];
                    }
                }
            }

            if ($has_results)
            {
                break;
            }
        }
    }
    if ($has_results)
    {
        break;
    }
}

if (!$has_results || $force_stop)
{
    $return = ['stop' => '1'];
}
else
{
    $return = ['OK' => '1', 'segments' => $segments];
}

echo json_encode($return);
