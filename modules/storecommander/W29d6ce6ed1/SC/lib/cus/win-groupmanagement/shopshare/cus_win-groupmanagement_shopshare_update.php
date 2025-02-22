<?php
if (!defined('STORE_COMMANDER')) { exit; }

$idlist = Tools::getValue('idlist', '');
$action = Tools::getValue('action', '');
$id_lang = Tools::getValue('id_lang', '0');
$id_shop = Tools::getValue('id_shop', '0');
$value = Tools::getValue('value', '0');

if ($value == 'true')
{
    $value = 1;
}
else
{
    $value = 0;
}

$multiple = false;
if (strpos($idlist, ',') !== false)
{
    $multiple = true;
}

$ids = explode(',', $idlist);

$noRefreshCategory = false;

if ($action != '' && !empty($id_shop) && !empty($idlist))
{
    switch ($action) {
        // Modification de present pour le shop passé en params
        // pour un ou plusieurs categories passés en params
        case 'present':
            foreach ($ids as $id)
            {
                $group = new Group($id);
                if (!$group->isAssociatedToShop($id_shop) && $value == '1')
                {
                    $group->id_shop_list = array($id_shop);
                    $group->save();
                }
                elseif ($group->isAssociatedToShop($id_shop) && empty($value))
                {
                    if ($id_shop != $group->id_shop_default)
                    {
                        $sql2 = 'DELETE FROM '._DB_PREFIX_."group_shop
                        WHERE id_group = " .(int) $id . " AND id_shop = " .(int) $id_shop . "";
                        Db::getInstance()->execute($sql2);
                    }
                }
            }
        break;
        // Modification de present
        // pour un ou plusieurs shops passés en params
        // pour un ou plusieurs categories passés en params
        case 'mass_present':
            $shops = explode(',', $id_shop);
            foreach ($shops as $shop)
            {
                foreach ($ids as $id)
                {
                    $group = new Group($id);
                    if (!$group->isAssociatedToShop($shop) && $value == '1')
                    {
                        $group->id_shop_list = array($shop);
                        $group->save();
                    }
                    elseif ($group->isAssociatedToShop($shop) && empty($value))
                    {
                        if ($shop != $group->id_shop_default)
                        {
                            $sql2 = 'DELETE FROM '._DB_PREFIX_."group_shop
                            WHERE id_group = " .(int) $id . " AND id_shop = " .(int) $id_shop . "";
                            Db::getInstance()->execute($sql2);
                        }
                    }
                }
            }
        break;
    }
}
