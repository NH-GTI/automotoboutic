<?php
if (!defined('STORE_COMMANDER')) { exit; }

$id_lang = (int) Tools::getValue('id_lang');
$gr_id = (Tools::getValue('gr_id', 0));
$action = Tools::getValue('action', '');
$field = Tools::getValue('field', '');
$value = Tools::getValue('value', '');

if (!empty($action) && $action == 'update')
{
    if (version_compare(_PS_VERSION_, '1.6.0.12', '>=') && SCMS)
    {
        list($id_cms_category, $id_lang, $id_shop) = explode('_', $gr_id);
    }
    else
    {
        list($id_cms_category, $id_lang) = explode('_', $gr_id);
    }

    $todo = array();
    $todo[] = $field."='".pSQL($value)."'";

    if (count($todo))
    {
        $sql = 'UPDATE '._DB_PREFIX_.'cms_category_lang SET '.join(' , ',
                $todo).' WHERE id_cms_category='.(int) $id_cms_category.' AND id_lang='.(int) $id_lang.' ';
        if (version_compare(_PS_VERSION_, '1.6.0.12', '>=') && SCMS)
        {
            $sql .= ' AND id_shop='.(int) $id_shop;
        }
        Db::getInstance()->Execute($sql);
    }
}
