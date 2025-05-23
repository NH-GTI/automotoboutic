<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

    $id_customer = Tools::getValue('id_customer_list');

    function getRowsFromDB()
    {
        global $id_customer;

        if (!$id_customer)
        {
            return '';
        }

        $sql = 'SELECT o.id_shop, o.reference ,oslip.*
                FROM '._DB_PREFIX_.'order_slip oslip
                LEFT JOIN '._DB_PREFIX_.'orders o ON (o.id_order=oslip.id_order)
                WHERE o.id_customer IN ('.pInSQL($id_customer).')';
        $sql .= (SCMS && SCI::getSelectedShop() > 0 ? ' AND o.id_shop = '.(int) SCI::getSelectedShop() : '');
        $sql .= ' ORDER BY oslip.id_order DESC';

        $customers = [];
        $shops = [];

        $res = Db::getInstance()->executeS($sql);
        $xml = '';
        if(!$res)
        {
            return $xml;
        }
        foreach ($res as $slip)
        {
            if (SCMS)
            {
                $shop = '';
                if (empty($shops[$slip['id_shop']]))
                {
                    $shopObj = new Shop($slip['id_shop']);
                    $shops[$slip['id_shop']] = $shopObj->name;
                    $shop = $shops[$slip['id_shop']];
                }
                else
                {
                    $shop = $shops[$slip['id_shop']];
                }
            }

            if (empty($customers[$slip['id_customer']]))
            {
                $customer = new Customer($slip['id_customer']);
                $customers[$slip['id_customer']] = $customer;
            }
            else
            {
                $customer = $customers[$slip['id_customer']];
            }

            $xml .= "<row id='".$slip['id_order_slip']."'>";
            $xml .= '<cell>'.$slip['id_order'].'</cell>';
            $xml .= '<cell>'.$slip['reference'].'</cell>';
            $xml .= '<cell>'.$slip['id_order_slip'].'</cell>';
            if (SCMS)
            {
                $xml .= '<cell>'.$shop.'</cell>';
            }
            $xml .= '<cell>'.$slip['id_customer'].'</cell>';
            $xml .= '<cell><![CDATA['.$customer->firstname.']]></cell>';
            $xml .= '<cell><![CDATA['.$customer->lastname.']]></cell>';
            $xml .= '<cell><![CDATA['.$customer->email.']]></cell>';
            $xml .= '<cell><![CDATA['.$slip['date_add'].']]></cell>';
            $xml .= '<cell><![CDATA['.$slip['total_products_tax_excl'].']]></cell>';
            $xml .= '<cell><![CDATA['.$slip['total_products_tax_incl'].']]></cell>';
            $xml .= '<cell><![CDATA['.$slip['total_shipping_tax_excl'].']]></cell>';
            $xml .= '<cell><![CDATA['.$slip['total_shipping_tax_incl'].']]></cell>';
            $xml .= '<cell><![CDATA['.$slip['conversion_rate'].']]></cell>';
            $xml .= '<cell><![CDATA['.($slip['amount'] + $slip['shipping_cost_amount']).']]></cell>';
            $xml .= '</row>';
        }

        return $xml;
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
<call command="attachHeader"><param><![CDATA[#numeric_filter,#text_filter,#numeric_filter,<?php if (SCMS){ ?>#text_filter,<?php } ?>#numeric_filter,#text_filter,#text_filter,#text_filter,#text_filter,#numeric_filter,#numeric_filter,#numeric_filter,#numeric_filter,#numeric_filter,#numeric_filter]]></param></call>
</beforeInit>
<column id="id_order" width="45" type="ro" align="right" sort="int"><?php echo _l('id order'); ?></column>
<column id="reference" width="70" type="ro" align="left" sort="str"><?php echo _l('Reference'); ?></column>
<column id="id_order_slip" width="45" type="ro" align="right" sort="int"><?php echo _l('id order slip'); ?></column>
<?php if (SCMS){ ?><column id="id_shop" width="45" type="ro" align="right" sort="int"><?php echo _l('Shop'); ?></column><?php } ?>
<column id="id_customer" width="45" type="ro" align="right" sort="int"><?php echo _l('id customer'); ?></column>
<column id="firstname" width="70" type="ro" align="left" sort="str"><?php echo _l('Firstname'); ?></column>
<column id="lastname" width="70" type="ro" align="left" sort="str"><?php echo _l('Lastname'); ?></column>
<column id="email" width="100" type="ro" align="left" sort="str"><?php echo _l('Email'); ?></column>
<column id="date_add" width="140" type="ro" align="right" sort="str"><?php echo _l('Creation date'); ?></column>
<column id="total_products_tax_excl" width="80" type="ro" format="0.00" align="right" sort="int"><?php echo _l('Total pdt. Tax excl'); ?></column>
<column id="total_products_tax_incl" width="80" type="ro" format="0.00" align="right" sort="int"><?php echo _l('Total pdt. Tax incl'); ?></column>
<column id="total_shipping_tax_excl" width="80" type="ro" format="0.00" align="right" sort="int"><?php echo _l('Total shipping Tax excl'); ?></column>
<column id="total_shipping_tax_incl" width="80" type="ro" format="0.00" align="right" sort="int"><?php echo _l('Total shipping Tax incl'); ?></column>
<column id="conversion_rate" width="80" type="ro" format="0.00" align="right" sort="int"><?php echo _l('Conversion rate'); ?></column>
<column id="amount" width="80" type="ron" format="0.000000" align="right" sort="int"><?php echo _l('Amount'); ?></column>
</head>
<?php
    echo '<userdata name="uisettings">'.UISettings::getSetting('ord_slip').'</userdata>'."\n";
    echo $xml;
?>
</rows>
