<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

    $list = Tools::getValue('id_order_list');

    function getRowsFromDB($orderListId)
    {
        $sql = '
        SELECT m.*,e.lastname,e.firstname
        FROM '._DB_PREFIX_.'message m
        LEFT JOIN '._DB_PREFIX_.'employee e ON (m.id_employee=e.id_employee)
        WHERE m.id_order IN ('.pInSQL($orderListId).')
        ORDER BY m.date_add DESC';
        $res = Db::getInstance()->executeS($sql);
        $xml = '';
        if (!$res)
        {
            return $xml;
        }
        foreach ($res as $message)
        {
            $xml .= "<row id='".$message['id_message']."'>";
            $xml .= '<cell style="color:#999999">'.$message['id_message'].'</cell>';
            $xml .= '<cell >'.(int) $message['id_customer'].'</cell>';
            $xml .= '<cell >'.(int) $message['id_order'].'</cell>';
            $xml .= '<cell><![CDATA['.($message['id_employee'] != 0 ? $message['firstname'][0].'. '.$message['lastname'] : '').']]></cell>';
            $xml .= '<cell><![CDATA['.nl2br($message['message']).']]></cell>';
            $xml .= '<cell>'.$message['date_add'].'</cell>';
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

    $xml = getRowsFromDB($list);
?>
<rows id="0">
<head>
<column id="id_message" width="45" type="ro" align="right" sort="int"><?php echo _l('ID'); ?></column>
<column id="id_order" width="45" type="ro" align="right" sort="int"><?php echo _l('id order'); ?></column>
<column id="id_customer" width="45" type="ro" align="right" sort="int"><?php echo _l('id customer'); ?></column>
<column id="author" width="80" type="ro" align="left" sort="str"><?php echo _l('Author'); ?></column>
<column id="message" width="200" type="ro" align="left" sort="str"><?php echo _l('Message'); ?></column>
<column id="date_add" width="140" type="ro" align="left" sort="str"><?php echo _l('Creation date'); ?></column>
</head>
<?php
    echo '<userdata name="uisettings">'.UISettings::getSetting('ord_message').'</userdata>'."\n";
    echo $xml;
?>
</rows>
