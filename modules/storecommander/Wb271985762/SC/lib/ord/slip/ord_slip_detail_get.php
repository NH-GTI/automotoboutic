<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

    $id_order_slip = Tools::getValue('id_order_slip');

    function getRowsFromDB()
    {
        global $id_order_slip;

        if (!$id_order_slip)
        {
            return '';
        }

        $sql = 'SELECT *
                FROM '._DB_PREFIX_.'order_slip_detail
                WHERE id_order_slip IN ('.pInSQL($id_order_slip).')
                ORDER BY id_order_detail DESC';
        $res = Db::getInstance()->executeS($sql);
        $xml = '';
        if(!$res)
        {
            return $xml;
        }
        foreach ($res as $order_slip_detail)
        {
            $xml .= "<row id='".$order_slip_detail['id_order_slip'].'__'.$order_slip_detail['id_order_detail']."'>";
            $xml .= '<cell>'.$order_slip_detail['id_order_slip'].'</cell>';
            $xml .= '<cell>'.$order_slip_detail['id_order_detail'].'</cell>';
            $xml .= '<cell>'.$order_slip_detail['product_quantity'].'</cell>';
            $xml .= '<cell>'.$order_slip_detail['unit_price_tax_excl'].'</cell>';
            $xml .= '<cell>'.$order_slip_detail['unit_price_tax_incl'].'</cell>';
            $xml .= '<cell>'.$order_slip_detail['total_price_tax_excl'].'</cell>';
            $xml .= '<cell>'.$order_slip_detail['total_price_tax_incl'].'</cell>';
            $xml .= '<cell>'.$order_slip_detail['amount_tax_excl'].'</cell>';
            $xml .= '<cell>'.$order_slip_detail['amount_tax_incl'].'</cell>';
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
<call command="attachHeader"><param><![CDATA[#numeric_filter,#numeric_filter,#numeric_filter,#numeric_filter,#numeric_filter,#numeric_filter,#numeric_filter,#numeric_filter,#numeric_filter]]></param></call>
</beforeInit>
<column id="id_order_slip" width="45" type="ro" align="right" sort="int">id order slip</column>
<column id="id_order_detail" width="45" type="ro" align="right" sort="int">id order detail</column>
<column id="product_quantity" width="45" type="ro" align="right" sort="int"><?php echo _l('Quantity'); ?></column>
<column id="unit_price_tax_excl" width="120" type="ro" format="0.00" align="right" sort="int"><?php echo _l('Unit price Tax excl'); ?></column>
<column id="unit_price_tax_incl" width="120" type="ro" format="0.00" align="right" sort="int"><?php echo _l('Unit price Tax incl'); ?></column>
<column id="total_price_tax_excl" width="120" type="ro" format="0.00" align="right" sort="int"><?php echo _l('Total price Tax excl'); ?></column>
<column id="total_price_tax_incl" width="120" type="ro" format="0.00" align="right" sort="int"><?php echo _l('Total price Tax incl'); ?></column>
<column id="amount_tax_excl" width="120" type="ro" format="0.00" align="right" sort="int"><?php echo _l('Amount tax excl'); ?></column>
<column id="amount_tax_incl" width="120" type="ro" format="0.00" align="right" sort="int"><?php echo _l('Amount tax incl'); ?></column>

</head>
<?php
    echo '<userdata name="uisettings">'.UISettings::getSetting('ord_slip_detail').'</userdata>'."\n";
    echo $xml;
?>
</rows>
