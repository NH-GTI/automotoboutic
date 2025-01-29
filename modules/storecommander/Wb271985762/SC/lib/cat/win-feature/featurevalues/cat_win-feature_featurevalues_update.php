<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

    $id_lang = (int) Tools::getValue('id_lang');
    $id_feature_value = (int) Tools::getValue('gr_id', 0);
    $id_feature = (int) Tools::getValue('id_feature');
    $action = Tools::getValue('action');
    if (isset($_POST['!nativeeditor_status']) && trim($_POST['!nativeeditor_status']) == 'inserted')
    {
        $newFV = new FeatureValue();
        $newFV->id_feature = $id_feature;
        if (version_compare(_PS_VERSION_, '1.5.0', '>='))
        {
            foreach ($languages as $lang)
            {
                $newFV->value[$lang['id_lang']] = 'new';
            }
        }
        $newFV->save();
        $newId = $newFV->id;
        $action = 'insert';
    }
    elseif (isset($_POST['!nativeeditor_status']) && trim($_POST['!nativeeditor_status']) == 'updated')
    {
        $fields_lang = ['value'];
        foreach ($fields_lang as $field)
        {
            if (Tools::isSubmit($field))
            {
                $dataLang = Tools::getValue($field);
                foreach ($languages as $lang)
                {
                    if (isset($dataLang[$lang['iso_code']]))
                    {
                        $sqltest = 'SELECT * 
                                    FROM '._DB_PREFIX_.'feature_value_lang 
                                    WHERE id_feature_value = '.(int) $id_feature_value.' 
                                    AND id_lang = '.(int) $lang['id_lang'];
                        $exists = Db::getInstance()->getRow($sqltest);
                        if (!$exists)
                        {
                            $sql = 'INSERT INTO '._DB_PREFIX_.'feature_value_lang ('.(int) $id_feature_value.', '.(int) $lang['id_lang'].',"'.bqSQL($dataLang[$lang['iso_code']]).'")';
                        }
                        else
                        {
                            $sql = 'UPDATE '._DB_PREFIX_.'feature_value_lang 
                                    SET `'.bqSQL($field).'` = "'.pSQL($dataLang[$lang['iso_code']]).'" 
                                    WHERE id_feature_value = '.(int) $id_feature_value.' 
                                    AND id_lang = '.(int) $lang['id_lang'];
                        }
                        if (Db::getInstance()->execute($sql))
                        {
                            addToHistory('feature_value', 'modification', $field, (int) $id_feature_value, $lang['id_lang'], _DB_PREFIX_.'feature_value_lang', pSQL($dataLang[$lang['iso_code']]), (!$exists ? false : pSQL($exists[$field])));
                        }
                        break;
                    }
                }
            }
        }

        // PM Cache
        if (!empty($id_feature_value))
        {
            ExtensionPMCM::clearFromIdsFeatureValue($id_feature_value);
        }

        $newId = Tools::getValue('gr_id');
        $action = 'update';
    }
    elseif (isset($_POST['!nativeeditor_status']) && trim($_POST['!nativeeditor_status']) == 'deleted')
    {
        $FV = new FeatureValue($id_feature_value, $id_lang);
        $FV->delete();

        // PM Cache
        if (!empty($id_feature_value))
        {
            ExtensionPMCM::clearFromIdsFeatureValue($id_feature_value);
        }

        $newId = Tools::getValue('gr_id');
        $action = 'delete';
    }
    elseif (!empty($action) && trim($action) == 'merge')
    {
        $featlist = explode(',', Tools::getValue('featlist', 0));
        sort($featlist);
        $id_feature = array_shift($featlist);
        foreach ($featlist as $id)
        {
            $sql = 'UPDATE IGNORE '._DB_PREFIX_.'feature_product SET id_feature_value='.(int) $id_feature.' WHERE id_feature_value='.(int) $id;
            Db::getInstance()->execute($sql);
            $sql = 'DELETE FROM '._DB_PREFIX_.'feature_value_lang WHERE id_feature_value='.(int) $id;
            Db::getInstance()->execute($sql);
            $sql = 'DELETE FROM '._DB_PREFIX_.'feature_value WHERE id_feature_value='.(int) $id;
            Db::getInstance()->execute($sql);
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
    echo '<data>';
    echo "<action type='".$action."' sid='".Tools::getValue('gr_id')."' tid='".$newId."'/>";
    echo $debug && isset($sql) ? '<sql><![CDATA['.$sql.']]></sql>' : '';
    echo '</data>';
