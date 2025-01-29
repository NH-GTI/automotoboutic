<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

    $xml = '';
    $multiple = false;

    if (Tools::isSubmit('ids'))
    {
        if (strpos(Tools::getValue('ids'), ',') !== false || (int) Tools::getValue('ids') === 0)
        {
            $multiple = true;
        }

        if (SCMS)
        {
            $shops = [];
            $shops_array = Shop::getShops(false);
            foreach ($shops_array as $shop)
            {
                $shops[$shop['id_shop']] = $shop['name'];
            }
        }

        $ids = Tools::getValue('ids');
        $idPartner = (int) Tools::getValue('id_partner');

        $sql = (new DbQuery())
            ->select('id_order')
            ->from('orders', 'o')
            ->innerJoin('scaff_commission', 'scom', 'o.`id_order` = scom.`order_id`')
            ->where('scom.`id_partner` = '.(int) $idPartner)
            ->groupBy('o.`id_order`')
            ->orderBy('o.`id_order` DESC')
        ;
        if (!empty($ids))
        {
            $sql->where('o.`id_customer` IN ('.pInSQL($ids).')');
        } else {
            $subSql = (new DbQuery())
                ->select('c.id_customer')
                ->from('customer', 'c')
                ->where('c.`scaff_partner_id` = '.(int) $idPartner)
            ;
            $sql->where('o.`id_customer` IN ('.$subSql->build().')');
        }

        $res = Db::getInstance()->executeS($sql);
        if ($res)
        {
            foreach ($res as $row)
            {
                $order = new Order((int) $row['id_order']);
                $status = $order->getCurrentStateFull((int) Tools::getValue('id_lang'));

                $membre = null;
                $firstname = $lastname = '';
                if (!empty($order->id_customer))
                {
                    $sql = (new DbQuery())
                        ->select('`firstname`')
                        ->select('`lastname`')
                        ->select('`scaff_partner_date_add`')
                        ->from('customer')
                        ->where('`id_customer` = '.(int) $order->id_customer)
                    ;
                    $membre = Db::getInstance()->getRow($sql);
                    if ($membre)
                    {
                        if ($order->date_add < $membre['scaff_partner_date_add'])
                        {
                            continue;
                        }
                        $firstname = $membre['firstname'];
                        $lastname = $membre['lastname'];
                    }
                }
                $xml .= "<row id='".(int) $order->id."'>";
                $xml .= '<cell>'.(int) $order->id.'</cell>';
                if (SCMS)
                {
                    $xml .= '<cell><![CDATA['.$shops[$order->id_shop].']]></cell>';
                }
                $xml .= '<cell><![CDATA['.$firstname.']]></cell>';
                $xml .= '<cell><![CDATA['.$lastname.']]></cell>';
                $xml .= '<cell>'.$order->date_add.'</cell>';
                $xml .= '<cell><![CDATA['.$status['name'].']]></cell>';
                $xml .= '<cell>'.number_format($order->total_products, 2, '.', '').'</cell>';
                $xml .= '<cell>'.number_format($order->total_paid, 2, '.', '').'</cell>';
                $xml .= '</row>';
            }
        }
    }
    //include XML Header (as response will be in xml format)
    if (stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml'))
    {
        header('Content-type: application/xhtml+xml');
    }
    else
    {
        header('Content-type: text/xml');
    }
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

?>
<rows id="0">
<head>
<beforeInit>
<call command="attachHeader"><param><![CDATA[#numeric_filter<?php if (SCMS) { ?>,#select_filter<?php } ?>,#text_filter,#text_filter,#text_filter,#select_filter,#numeric_filter,#numeric_filter]]></param></call>
<call command="attachFooter"><param><![CDATA[,<?php if (SCMS) { ?>,<?php } ?>,,,,#stat_total,#stat_total]]></param></call>
</beforeInit>
<column id="id_order" width="40" type="ro" align="right" sort="int"><?php echo _l('ID'); ?></column>
<?php if (SCMS) { ?>
<column id="id_shop" width="80" type="ro" align="left" sort="str"><?php echo _l('Shop'); ?></column>
<?php } ?>
<column id="firstname" width="100" type="ro" align="left" sort="str_custom"><?php echo _l('Firstname'); ?></column>
<column id="lastname" width="100" type="ro" align="left" sort="str_custom"><?php echo _l('Lastname'); ?></column>
<column id="date_add" width="110" type="ro" align="left" sort="date"><?php echo _l('Ordered on'); ?></column>
<column id="status" width="80" type="ro" align="left" sort="str_custom"><?php echo _l('Status'); ?></column>
<column id="total_products" width="150" type="ro" align="right" sort="int"><?php echo _l('Total product'); ?></column>
<column id="total_paid" width="100" type="ro" align="right" sort="int"><?php echo _l('Total paid'); ?></column>
<afterInit>
<call command="enableHeaderMenu"></call>
</afterInit>
</head>
<?php
    echo '<userdata name="uisettings">'.UISettings::getSetting('gmaorder').'</userdata>'."\n";
    echo $xml;
?>
</rows>
