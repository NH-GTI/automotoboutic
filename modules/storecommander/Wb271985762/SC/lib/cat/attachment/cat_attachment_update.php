<?php
if (!defined('STORE_COMMANDER')) { exit; }

$action = Tools::getValue('action', '0');
$value = Tools::getValue('value', '0');
$product_list = Tools::getValue('product_list', '0');
$attachment_list = Tools::getValue('attachment_list', '0');
$description = Tools::getValue('description', '0');
$name = Tools::getValue('name', '0');
$colname = Tools::getValue('colname', '0');
$lang = Tools::getValue('lang', '0');

if (Tools::getValue('act', '') == 'cat_attachment_update')
{
    if ($action == 'delete')
    {
        $list = explode(',', $attachment_list);
        foreach ($list as $id)
        {
            $att = new Attachment((int) $id);
            $att->delete();
        }
    }
    if ($action == 'update')
    {
        $product_listarray = explode(',', $product_list);
        $sql = 'DELETE FROM `'._DB_PREFIX_.'product_attachment` WHERE `id_attachment` IN ('.pInSQL($attachment_list).') AND `id_product` IN ('.pInSQL($product_list).')';
        Db::getInstance()->Execute($sql);
        $sqlstr = '';
        foreach ($product_listarray as $id_product)
        {
            if ($attachment_list != 0 && $id_product != 0)
            {
                $sqlstr .= '('.$attachment_list.','.$id_product.'),';
            }
        }
        $sqlstr = trim($sqlstr, ',');
        if ($value == 1 && $sqlstr != '')
        {
            $sql = 'INSERT INTO `'._DB_PREFIX_.'product_attachment` (id_attachment,id_product) VALUES '.psql($sqlstr);
            Db::getInstance()->Execute($sql);
        }

        $sql = 'UPDATE `'._DB_PREFIX_.'product` SET cache_has_attachments=1 WHERE `id_product` IN ('.pInSQL($product_list).')';
        Db::getInstance()->Execute($sql);

        if ($value == 0)
        {
            $sql = 'UPDATE `'._DB_PREFIX_.'product` SET cache_has_attachments=0 WHERE `id_product` NOT IN (SELECT id_product FROM `'._DB_PREFIX_.'product_attachment`)';
            Db::getInstance()->Execute($sql);
        }
    }
    if ($action == 'updateFilename')
    {
        $sql = 'UPDATE '._DB_PREFIX_."attachment SET `file_name`='".psql($value)."' WHERE id_attachment=".(int) $attachment_list;
        Db::getInstance()->Execute($sql);
    }
    if ($action == 'updateDescription' || $action == 'updateName')
    {
        switch($action)
        {
            case 'updateDescription':
                $field = 'description';
                $needHtml = true;
                break;
            default:
                $field = 'name';
                $needHtml = false;

        }

        if(Tools::isSubmit($field))
        {
            $dataLang = Tools::getValue($field);
            foreach ($languages as $lang)
            {
                if(isset($dataLang[$lang['iso_code']]))
                {
                    $sqltest = 'SELECT * 
                                FROM ' . _DB_PREFIX_ . 'attachment_lang 
                                WHERE id_attachment = ' . (int)$attachment_list . ' 
                                AND id_lang = ' . (int)$lang['id_lang'];
                    $exists = Db::getInstance()->getRow($sqltest);
                    if (!$exists)
                    {
                        $sql = 'INSERT INTO ' . _DB_PREFIX_ . 'attachment_lang (`id_attachment`,`id_lang`,`' . bqSQL($field) . '`) 
                                VALUES (' . (int)$attachment_list . ', ' . (int)$lang['id_lang'] . ', "' . pSQL($dataLang[$lang['iso_code']], $needHtml) . '")';
                    }
                    else
                    {
                        $sql = 'UPDATE '._DB_PREFIX_.'attachment_lang 
                                SET `'.bqSQL($field).'` = "'.pSQL($dataLang[$lang['iso_code']], $needHtml).'" 
                                WHERE id_attachment='.(int) $attachment_list.' 
                                AND id_lang='.(int) $lang['id_lang'];
                    }
                    if(Db::getInstance()->execute($sql))
                    {
                        addToHistory('attachment_lang', 'modification', $field, (int) $attachment_list, $lang['id_lang'], _DB_PREFIX_.'attachment_lang', pSQL($dataLang[$lang['iso_code']], $needHtml), (!$exists ? false : pSQL($exists[$field], $needHtml)));
                    }
                    break;
                }
            }
        }
    }
    if ($action == 'addSelAttachment')
    {
        $sqlstr = array();
        $sqlstrdelete = array();
        $product_listarray = explode(',', $product_list);
        $id_attachmentlist = explode(',', $attachment_list);
        foreach ($product_listarray as $id_product)
        {
            foreach ($id_attachmentlist as $id_attachment)
            {
                if ($id_product != 0 && $id_attachment != 0)
                {
                    $sqlstr[] = '('.$id_product.','.$id_attachment.')';
                    $sqlstrdelete[] = '(id_product='.(int) $id_product.' AND id_attachment='.(int) $id_attachment.')';
                }
            }
        }
        if (count($sqlstr))
        {
            $sqlstr = array_unique($sqlstr);
            $sql = 'DELETE FROM `'._DB_PREFIX_.'product_attachment` WHERE '.psql(join(' OR ', $sqlstrdelete));
            Db::getInstance()->Execute($sql);
            $sql = 'INSERT INTO `'._DB_PREFIX_.'product_attachment` (id_product,id_attachment) VALUES '.psql(join(',', $sqlstr));
            Db::getInstance()->Execute($sql);
        }

        $sql = 'UPDATE `'._DB_PREFIX_.'product` SET cache_has_attachments=1 WHERE `id_product` IN ('.pInSQL($product_list).')';
        Db::getInstance()->Execute($sql);
    }
    if ($action == 'deleteSelAttachment')
    {
        $sql = 'DELETE FROM `'._DB_PREFIX_.'product_attachment` WHERE `id_attachment` IN ('.pInSQL($attachment_list).') AND `id_product` IN ('.pInSQL($product_list).')';
        Db::getInstance()->Execute($sql);

        $sql = 'UPDATE `'._DB_PREFIX_.'product` SET cache_has_attachments=0 WHERE `id_product` NOT IN (SELECT id_product FROM `'._DB_PREFIX_.'product_attachment`)';
        Db::getInstance()->Execute($sql);
    }

    if (!empty($product_list))
    {
        //update date_upd
        Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'product SET date_upd = NOW() WHERE id_product IN ('.pInSQL($product_list).')');
        if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
        {
            Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'product_shop SET date_upd = NOW() WHERE id_product IN ('.pInSQL($product_list).') AND id_shop IN ('.pInSQL(SCI::getSelectedShopActionList(true)).')');
        }
        ExtensionPMCM::clearFromIdsProduct(explode(',', $product_list));
    }
}
