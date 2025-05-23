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

function removeInShop($cms_categ_id, $shop_id)
{
    if (!empty($cms_categ_id) && !empty($shop_id))
    {
        $sql = 'DELETE FROM `'._DB_PREFIX_."cms_category_shop` WHERE `id_cms_category` = '".(int) $cms_categ_id."' AND id_shop = '".(int) $shop_id."'";
        Db::getInstance()->Execute($sql);

        $sql = 'DELETE FROM `'._DB_PREFIX_."cms_category_lang` WHERE `id_cms_category` = '".(int) $cms_categ_id."' AND id_shop = '".(int) $shop_id."'";
        Db::getInstance()->Execute($sql);
    }
}

if ($action != '' && !empty($id_shop) && !empty($idlist))
{
    switch ($action) {
        // Modification de present pour le shop passé en params
        // pour un ou plusieurs categories passés en params
        case 'present':
            foreach ($ids as $id)
            {
                $cmsCategory = new CMSCategory($id, false, $id_actual_shop);

                if (!$cmsCategory->isAssociatedToShop($id_shop) && $value == '1')
                {
                    $cmsCategory->id_shop_list = array($id_shop);
                    $cmsCategory->save();
                }
                elseif ($cmsCategory->isAssociatedToShop($id_shop) && empty($value))
                {
                    if ($id_shop != Configuration::get('PS_SHOP_DEFAULT'))
                    {
                        removeInShop($id, $id_shop);
                    }
                }
            }
        break;
        // Modification de present
        // pour un ou plusieurs shops passés en params
        // pour un ou plusieurs categories passés en params
        case 'mass_present':
            $shops = explode(',', $id_shop);
            foreach ($shops as $id_shop)
            {
                foreach ($ids as $id)
                {
                    $cmsCategory = new CMSCategory($id, false, $id_actual_shop);

                    if (!$cmsCategory->isAssociatedToShop($id_shop) && $value == '1')
                    {
                        $cmsCategory->id_shop_list = array($id_shop);
                        $cmsCategory->save();
                    }
                    elseif ($cmsCategory->isAssociatedToShop($id_shop) && empty($value))
                    {
                        if ($id_shop != Configuration::get('PS_SHOP_DEFAULT'))
                        {
                            removeInShop($id, $id_shop);
                        }
                    }
                }
            }
        break;
    }
}
