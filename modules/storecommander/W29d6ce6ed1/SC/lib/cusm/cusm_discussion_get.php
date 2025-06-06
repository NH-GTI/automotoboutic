<?php
if (!defined('STORE_COMMANDER')) { exit; }

    $id_lang = (int) Tools::getValue('id_lang');
    $filters = (Tools::getValue('filters'));
    $filtersgrid = (Tools::getValue('filtersgrid'));

    $periods = array(
        '1days' => ' AND ct.date_add >= "'.pSQL(date('Y-m-d')).' 00:00:00" ',
        '2days' => ' AND TO_DAYS(NOW()) - TO_DAYS(ct.date_add) < 2',
        '3days' => ' AND TO_DAYS(NOW()) - TO_DAYS(ct.date_add) < 3',
        '5days' => ' AND TO_DAYS(NOW()) - TO_DAYS(ct.date_add) < 5',
        '10days' => ' AND TO_DAYS(NOW()) - TO_DAYS(ct.date_add) < 10',
        '15days' => ' AND TO_DAYS(NOW()) - TO_DAYS(ct.date_add) < 15',
        '30days' => ' AND TO_DAYS(NOW()) - TO_DAYS(ct.date_add) < 30',
        '3months' => ' AND DATE_SUB(NOW(), INTERVAL 3 MONTH) < ct.date_add',
        '6months' => ' AND DATE_SUB(NOW(), INTERVAL 6 MONTH) < ct.date_add',
        '1year' => ' AND DATE_SUB(NOW(), INTERVAL 1 YEAR) < ct.date_add',
        'all' => '',
    );

    $id_segment = 0;
    $id_segment_get = Tools::getValue('id_segment', 0);
    if (!empty($id_segment_get))
    {
        if (substr($id_segment_get, 0, 4) == 'seg_' && SCSG)
        {
            $id_segment = (int) str_replace('seg_', '', $id_segment_get);
        }
    }

    function getRowsFromDB()
    {
        global $id_lang,$id_segment,$filters,$filtersgrid,$periods;

        if (!empty($id_segment))
        {
            $segment = new ScSegment($id_segment);
        }

        $shops = Shop::getShops(false);

        $where = '';
        $where_status = '';
        $where_contact = '';
        $where_lang = '';
        $where_employee = '';
        $where_period = '';

        if (!empty($filters))
        {
            $filters = explode(',', $filters);
            foreach ($filters as $filter)
            {
                list($type, $value) = explode('_', $filter);

                if ($type == 'st')
                {
                    if (!empty($where_status))
                    {
                        $where_status .= ' OR ';
                    }
                    $where_status .= ' ct.status = "'.pSQL($value).'" ';
                }
                elseif ($type == 'ct')
                {
                    if (!empty($where_contact))
                    {
                        $where_contact .= ' OR ';
                    }
                    $where_contact .= ' ct.id_contact = '.(int) $value.' ';
                }
                elseif ($type == 'lg')
                {
                    if (!empty($where_lang))
                    {
                        $where_lang .= ' OR ';
                    }
                    $where_lang .= ' ct.id_lang = '.(int) $value.' ';
                }
                elseif ($type == 'em')
                {
                    if (!empty($where_employee))
                    {
                        $where_employee .= ' OR ';
                    }
                    $where_employee .= ' cm.id_employee = '.(int) $value.' ';
                }
                elseif (strpos($filter, 'from_to_') !== false)
                {
                    $dates = str_replace('from_to_', '', $filter);
                    $exp = explode('_', $dates);
                    $from = $exp[0];
                    $to = '';
                    if (!empty($exp[1]))
                    {
                        $to = $exp[1];
                    }

                    if (!empty($from))
                    {
                        $where_period .= " AND ( ct.date_add >= '".pSQL($from)." 00:00:00' ) ";
                    }
                    if (!empty($to))
                    {
                        $where_period .= " AND ( ct.date_add <= '".pSQL($to)." 23:59:59' ) ";
                    }
                }
                elseif (sc_array_key_exists($filter, $periods))
                {
                    $where_period .= $periods[$filter];
                }
            }
        }

        if (!empty($where_status))
        {
            $where .= ' AND ('.$where_status.') ';
        }
        if (!empty($where_contact))
        {
            $where .= ' AND ('.$where_contact.') ';
        }
        if (!empty($where_lang))
        {
            $where .= ' AND ('.$where_lang.') ';
        }
        if (!empty($where_employee))
        {
            $where .= ' AND ('.$where_employee.') ';
        }
        if (!empty($where_period))
        {
            $where .= ' '.$where_period.' ';
        }

        if (!empty($id_segment) && SCSG)
        {
            if ($segment->type == 'manual')
            {
                $where .= ' AND ct.id_customer_thread IN (SELECT id_element FROM '._DB_PREFIX_.'sc_segment_element WHERE type_element="customer_service" AND id_segment='.(int) $id_segment.')';
            }
            elseif ($segment->type == 'auto')
            {
                $params = array('id_lang' => $id_lang, 'id_segment' => $id_segment, 'access' => 'customer_service');
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
                $where .= SegmentHook::hookByIdSegment('segmentAutoSqlQuery', $segment, $params);
            }
        }
        if (!empty($filtersgrid))
        {
            $filtersgrid = json_decode($filtersgrid,true);
            foreach ($filtersgrid as $type => $row)
            {
                $value = $row['query'];
                if (!empty($value))
                {
                    if ($type == 'id_customer_thread')
                    {
                        $where .= ' AND ct.id_customer_thread = "'.(int) $value.'" ';
                    }
                    elseif ($type == 'customer_name')
                    {
                        $where .= ' AND (LOWER(c.`firstname`) LIKE "%'.pSQL(strtolower($value)).'%" OR LOWER(c.`lastname`) LIKE "%'.pSQL(strtolower($value)).'%") ';
                    }
                    elseif ($type == 'email')
                    {
                        $where .= ' AND LOWER(ct.`email`) LIKE "%'.pSQL(strtolower($value)).'%" ';
                    }
                    elseif ($type == 'id_order')
                    {
                        $where .= ' AND ct.id_order = "'.(int) $value.'" ';
                    }
                }
            }
        }

        $sql = 'SELECT cm.id_customer_thread,
                        cm.message,
                        ct.*,
                        CONCAT(c.`firstname`," ",c.`lastname`) as customer,
                        IF(cm.id_employee > 0 , CONCAT(e.firstname," ",e.lastname),"") as last_employee
                        '.(version_compare(_PS_VERSION_, '1.5.0.0', '<') ? ',0 as id_shop_customer' : ',c.id_shop as id_shop_customer').'
                FROM `'._DB_PREFIX_.'customer_message` cm
                LEFT JOIN `'._DB_PREFIX_.'employee` e
                    ON (e.id_employee = cm.id_employee)
                LEFT JOIN `'._DB_PREFIX_.'customer_thread` ct
                    ON (ct.id_customer_thread = cm.id_customer_thread)
                LEFT JOIN `'._DB_PREFIX_.'customer` c
                    ON (c.id_customer = ct.id_customer)
                WHERE cm.id_customer_message IN (SELECT MAX(id_customer_message)
                                                    FROM `'._DB_PREFIX_.'customer_message`
                                                    GROUP BY id_customer_thread)
                        '.(SCMS && SCI::getSelectedShop() ? 'AND ct.id_shop='.(int) SCI::getSelectedShop() : '').'
                        '.$where.'
                ORDER BY cm.`date_add` DESC
                LIMIT 500';

        $res = Db::getInstance()->ExecuteS($sql);
        $xml = '';
        if (empty($res))
        {
            return $xml;
        }
        foreach ($res as $row)
        {
            $xml .= "<row id='".$row['id_customer_thread']."'>";
            $xml .= '<userdata name="id_customer">'.(int) $row['id_customer'].'</userdata>';
            $xml .= '<userdata name="id_shop_customer">'.(int) $row['id_shop_customer'].'</userdata>';
            $xml .= '<cell><![CDATA['.$row['id_customer_thread'].']]></cell>';
            $xml .= '<cell><![CDATA['.$row['customer'].']]></cell>';
            $xml .= '<cell><![CDATA['.$row['email'].']]></cell>';
            $xml .= '<cell><![CDATA['.$row['status'].']]></cell>';
            $xml .= '<cell><![CDATA['.$row['last_employee'].']]></cell>';
            $xml .= '<cell><![CDATA['.$row['id_contact'].']]></cell>';
            $xml .= '<cell><![CDATA['.$row['date_add'].']]></cell>';
            $xml .= '<cell><![CDATA['.$row['date_upd'].']]></cell>';
            $xml .= '<cell><![CDATA['.truncate($row['message'], 50, '...', true).']]></cell>';
            if (SCMS)
            {
                $xml .= '<cell><![CDATA['.$shops[$row['id_shop']]['name'].']]></cell>';
            }
            $xml .= '<cell><![CDATA['.$row['id_order'].']]></cell>';
            $xml .= '</row>';
        }

        return $xml;
    }

    $contacts = '';
    $rows = Contact::getContacts($id_lang);
    foreach ($rows as $row)
    {
        $contacts .= '<option value="'.$row['id_contact'].'">'.str_replace('&', _l('and'), $row['name']).'</option>';
    }

    //XML HEADER
    if (stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml'))
    {
        header('Content-type: application/xhtml+xml');
    }
    else
    {
        header('Content-type: text/xml');
    }
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

    $xml = getRowsFromDB();
?>
<rows id="0">
<head>
<beforeInit>
<call command="attachHeader"><param><![CDATA[#numeric_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter<?php if (SCMS)
{
    echo ',#select_filter';
} ?>,#numeric_filter]]></param></call>
</beforeInit>
<column id="id_customer_thread" width="40" type="ro" align="right" sort="int"><?php echo _l('ID'); ?></column>
<column id="customer_name" width="140" type="ro" align="left" sort="str"><?php echo _l('Customer'); ?></column>
<column id="email" width="180" type="ed" align="left" sort="str"><?php echo _l('Email'); ?></column>
<column id="status" width="100" type="coro" align="left" sort="str"><?php echo _l('Status'); ?>
    <option value="open"><?php echo _l('Open'); ?></option>
    <option value="pending1"><?php echo _l('Waiting 1'); ?></option>
    <option value="pending2"><?php echo _l('Waiting 2'); ?></option>
    <option value="closed"><?php echo _l('Closed'); ?></option>
</column>
<column id="id_employee" width="140" type="ro" align="left" sort="str"><?php echo _l('Last advisor'); ?>

</column>
<column id="id_contact" width="120" type="coro" align="left" sort="str"><?php echo _l('Service'); ?> / <?php echo _l('Contact'); ?>
    <option value="">--</option>
    <?php echo $contacts; ?>
</column>
<column id="date_add" width="140" type="ro" align="left" sort="str"><?php echo _l('Date add'); ?></column>
<column id="date_upd" width="140" type="ro" align="left" sort="str"><?php echo _l('Date update'); ?></column>
<column id="message" width="200" type="ro" align="left" sort="str"><?php echo _l('Message'); ?></column>
<?php if (SCMS){ ?>
<column id="shop" width="80" type="ro" align="right" sort="int"><?php echo _l('Shop'); ?></column>
<?php } ?>
<column id="id_order" width="60" type="ro" align="right" sort="int"><?php echo _l('ID Order'); ?></column>
<afterInit>
<call command="enableMultiselect"><param>1</param></call>
</afterInit>
</head>
<?php
//  format="%Y-%m-%d 00:00:00"
    echo '<userdata name="uisettings">'.uisettings::getSetting('cusm_grid').'</userdata>'."\n";
    echo $xml;
?>
</rows>
