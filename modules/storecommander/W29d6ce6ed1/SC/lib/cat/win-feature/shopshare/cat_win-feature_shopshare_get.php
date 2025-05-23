<?php
if (!defined('STORE_COMMANDER')) { exit; }

    $idlist = Tools::getValue('idlist', 0);
    $id_lang = (int) Tools::getValue('id_lang');
    $cntFeatures = count(explode(',', $idlist));
    $used = array();

    function getRows()
    {
        global $idlist,$id_lang,$used, $cntFeatures,$sc_agent;

        $multiple = false;
        if ($cntFeatures > 1)
        {
            $multiple = true;
        }

        $sql = 'SELECT s.*
                    FROM '._DB_PREFIX_.'shop s
                    '.((!empty($sc_agent->id_employee)) ? ' INNER JOIN '._DB_PREFIX_."employee_shop es ON (es.id_shop = s.id_shop AND es.id_employee = '".(int) $sc_agent->id_employee."') " : '')."
                    WHERE s.deleted != '1'
                    ORDER BY s.id_shop_group ASC, s.name ASC";
        $res = Db::getInstance()->ExecuteS($sql);

        if (!$multiple)
        {
            $feature_shop = Db::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'feature_shop WHERE id_feature = '.(int) $idlist);
            foreach ($res as $shop)
            {
                $used[$shop['id_shop']] = array(0, 0, '', '', 0, '');

                foreach ($feature_shop as $row)
                {
                    if ($shop['id_shop'] == $row['id_shop'])
                    {
                        $used[$shop['id_shop']][0] = 1;
                    }
                }
            }
        }
        else
        {
            $res3 = Db::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'feature_shop WHERE id_feature IN ('.pInSQL($idlist).')');

            foreach ($res as $shop)
            {
                $used[$shop['id_shop']] = array(0, 0, 'DDDDDD', 'DDDDDD', 0, 'DDDDDD');
                $nb_present = 0;
                $nb_active = 0;

                $sql2 = 'SELECT *
                        FROM '._DB_PREFIX_.'feature_shop 
                        WHERE id_feature IN ('.pInSQL($idlist).')
                        AND id_shop = '.(int) $shop['id_shop'];
                $res2 = Db::getInstance()->ExecuteS($sql2);
                foreach ($res2 as $feature)
                {
                    if (!empty($feature['id_feature']))
                    {
                        ++$nb_present;
                    }
                }

                if ($nb_present == $cntFeatures)
                {
                    $used[$shop['id_shop']][0] = 1;
                    $used[$shop['id_shop']][2] = '7777AA';
                }
                elseif ($nb_present < $cntFeatures && $nb_present > 0)
                {
                    $used[$shop['id_shop']][2] = '777777';
                }
                if ($nb_active == $cntFeatures)
                {
                    $used[$shop['id_shop']][1] = 1;
                    $used[$shop['id_shop']][3] = '7777AA';
                }
                elseif ($nb_active < $cntFeatures && $nb_active > 0)
                {
                    $used[$shop['id_shop']][3] = '777777';
                }
            }
        }

        foreach ($res as $row)
        {
            echo '<row id="'.$row['id_shop'].'">';
            echo '<cell><![CDATA['.$row['name'].']]></cell>';
            echo '<cell style="background-color:'.((!empty($used[$row['id_shop']][2])) ? '#'.$used[$row['id_shop']][2] : '').'">'.((!empty($used[$row['id_shop']][0])) ? '1' : '0').'</cell>';
            echo '</row>';
        }
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
?>
<rows>
<head>
<beforeInit>
<call command="attachHeader"><param><![CDATA[#select_filter,#select_filter]]></param></call>
</beforeInit>
<column id="id" width="200" type="ro" align="left" sort="str"><?php echo _l('Shop'); ?></column>
<column id="present" width="80" type="ch" align="center" sort="int"><?php echo _l('Present'); ?></column>
</head>
<?php
    echo '<userdata name="uisettings">'.uisettings::getSetting('cat_prop_shopshare_grid').'</userdata>'."\n";
    if (!empty($idlist))
    {
        getRows();
    }
?>
</rows>