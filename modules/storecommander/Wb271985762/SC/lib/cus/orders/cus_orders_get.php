<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

    $id_customer = Tools::getValue('id_customer_list');
    $id_lang = (int) Tools::getValue('id_lang');

    function getRowsFromDB()
    {
        global $id_customer,$id_lang;

        $sql = 'SELECT o.*,
                (SELECT oh.id_order_state FROM '._DB_PREFIX_.'order_history oh WHERE oh.id_order=o.id_order ORDER BY date_add DESC LIMIT 1) as current_state
                FROM '._DB_PREFIX_.'orders o';
        $sql .= ' WHERE o.id_customer IN ('.pInSQL($id_customer).')';
        $sql .= (SCMS && SCI::getSelectedShop() > 0 ? ' AND o.id_shop = '.(int) SCI::getSelectedShop() : '');
        $sql .= ' ORDER BY o.id_order DESC';

        $customers = [];

        $res = Db::getInstance()->executeS($sql);
        $xml = '';
        foreach ($res as $order)
        {
            if (SCMS)
            {
                $shop = new Shop($order['id_shop']);
                $order['id_shop'] = $shop->name;
            }

            if (empty($customers[$order['id_customer']]))
            {
                $customer = new Customer($order['id_customer']);
                $customers[$order['id_customer']] = $customer;
            }
            else
            {
                $customer = $customers[$order['id_customer']];
            }

            $status = new OrderState($order['current_state'], $id_lang);

            $color = '';
            if (!empty($order['valid']))
            {
                $color = 'style="background-color: #95ca82;"';
            }

            $xml .= "<row id='".$order['id_order']."'>";
            $xml .= '<cell '.$color.'>'.$order['id_order'].'</cell>';
            if (SCMS)
            {
                $xml .= '<cell><![CDATA['.$order['id_shop'].']]></cell>';
            }
            $xml .= '<cell>'.$order['id_customer'].'</cell>';
            $xml .= '<cell><![CDATA['.$customer->firstname.']]></cell>';
            $xml .= '<cell><![CDATA['.$customer->lastname.']]></cell>';
            $xml .= '<cell><![CDATA['.$customer->email.']]></cell>';
            $xml .= '<cell><![CDATA['.getPdfDetail($order).']]></cell>';
            $xml .= '<cell><![CDATA['.$order['total_paid'].']]></cell>';
            $xml .= '<cell><![CDATA['.$order['payment'].']]></cell>';
            $xml .= '<cell><![CDATA['.$status->name.']]></cell>';
            $xml .= '<cell><![CDATA['.$order['invoice_number'].']]></cell>';
            $xml .= '<cell><![CDATA['.$order['delivery_number'].']]></cell>';
            $xml .= '<cell><![CDATA['.$order['date_add'].']]></cell>';
            $xml .= '</row>';
        }

        return $xml;
    }

    /**
     * @param $orderData
     *
     * @return string
     */
    function getPdfDetail($orderData)
    {
        $data = [];
        if (!empty($orderData['invoice_number']))
        {
            $data[] = '<a target="_blank" href="'.SC_PS_PATH_ADMIN_REL.'index.php?controller=AdminPdf&submitAction=generateInvoicePDF&id_order='.(int) $orderData['id_order'].'&token='.SC_Agent::getInstance()->getPSToken('AdminPdf').'"><i class="fad fa-file-invoice in_grid" title="'._l('Download invoice').'"></i></a>';
        }
        if (!empty($orderData['delivery_number']))
        {
            $data[] = '<a target="_blank" href="'.SC_PS_PATH_ADMIN_REL.'index.php?controller=AdminPdf&submitAction=generateDeliverySlipPDF&id_order='.(int) $orderData['id_order'].'&token='.SC_Agent::getInstance()->getPSToken('AdminPdf').'"><i class="fad fa-truck in_grid" title="'._l('Download delivery slip').'"></i></a> ';
        }

        return implode(' ', $data);
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
<call command="attachHeader"><param><![CDATA[#numeric_filter,<?php if (SCMS){ ?>#numeric_filter,<?php } ?>#numeric_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter]]></param></call>
<call command="attachFooter"><param><![CDATA[,<?php if (SCMS){ ?>,<?php } ?>,,,,,#stat_total]]></param></call>
</beforeInit>
<column id="id_order" width="45" type="ro" align="right" sort="int"><?php echo _l('id order'); ?></column>
<?php if (SCMS) { ?>
<column id="id_shop" width="45" type="ro" align="right" sort="int"><?php echo _l('id shop'); ?></column>
<?php } ?>
<column id="id_customer" width="45" type="ro" align="right" sort="int"><?php echo _l('id customer'); ?></column>
<column id="firstname" width="70" type="ro" align="left" sort="str"><?php echo _l('Firstname'); ?></column>
<column id="lastname" width="70" type="ro" align="left" sort="str"><?php echo _l('Lastname'); ?></column>
<column id="email" width="100" type="ro" align="left" sort="str"><?php echo _l('Email'); ?></column>
<column id="pdf" width="90" type="ro" align="left" sort="str"><?php echo _l('PDF'); ?></column>
<column id="total_paid" width="70" type="ro" align="right" sort="int"><?php echo _l('Total paid'); ?></column>
<column id="payment" width="80" type="ro" align="left" sort="str"><?php echo _l('Payment'); ?></column>
<column id="order_status" width="200" type="ro" align="left" sort="str"><?php echo _l('Order status'); ?></column>
<column id="invoice_number" width="45" type="ro" align="right" sort="str"><?php echo _l('Invoice No'); ?></column>
<column id="delivery_number" width="45" type="ro" align="right" sort="str"><?php echo _l('Delivery No'); ?></column>
<column id="date_add" width="140" type="ro" align="right" sort="str"><?php echo _l('Creation date'); ?></column>
</head>
<?php
    echo '<userdata name="uisettings">'.UISettings::getSetting('cus_orders').'</userdata>'."\n";
    echo $xml;
?>
</rows>
