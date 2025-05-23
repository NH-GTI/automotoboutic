<?php
if (!defined('STORE_COMMANDER')) { exit; }

    $id_feature = (int) Tools::getValue('gr_id');
    $id_lang = (int) Tools::getValue('id_lang');
    $action = Tools::getValue('action', 0);
    $newId = 0;

    if (!empty($action) && $action == 'position')
    {
        $todo = array();
        $row = explode(';', Tools::getValue('positions'));
        foreach ($row as $v)
        {
            if ($v != '')
            {
                $pos = explode(',', $v);
                $todo[] = 'UPDATE '._DB_PREFIX_.'feature SET position='.(int) $pos[1].' WHERE id_feature='.(int) $pos[0];
            }
        }
        foreach ($todo as $task)
        {
            Db::getInstance()->Execute($task);
        }
    }
    elseif (isset($_POST['!nativeeditor_status']) && trim($_POST['!nativeeditor_status']) == 'inserted')
    {
        $newFeature = new Feature();
        if (version_compare(_PS_VERSION_, '1.5.0', '>='))
        {
            foreach ($languages as $lang)
            {
                $newFeature->name[$lang['id_lang']] = 'new';
            }
            $newFeature->id_shop_list = Shop::getShops(true, null, true);
        }
        $newFeature->save();
        $newId = $newFeature->id;
        $action = 'insert';
    }
    elseif (isset($_POST['!nativeeditor_status']) && trim($_POST['!nativeeditor_status']) == 'duplicated')
    {
        $features = explode(',', Tools::getValue('features', ''));
        $updated_features = array();
        foreach ($features as $id_feature)
        {
            $sql = 'INSERT INTO '._DB_PREFIX_.'feature () VALUES ()';
            Db::getInstance()->Execute($sql);
            $newFeatureID = Db::getInstance()->Insert_ID();
            $updated_features[$newFeatureID] = $newFeatureID;
            if (version_compare(_PS_VERSION_, '1.5.0', '>='))
            {
                $shops = SCI::getSelectedShopActionList();
                foreach ($shops as $shop)
                {
                    $sql = 'INSERT INTO '._DB_PREFIX_.'feature_shop (id_feature,id_shop) VALUES ('.(int) $newFeatureID.','.(int) $shop.')';
                }
                Db::getInstance()->Execute($sql);
            }
            $sql = '
            SELECT * FROM '._DB_PREFIX_.'feature f
            LEFT JOIN '._DB_PREFIX_.'feature_lang fl ON (fl.id_feature=f.id_feature)
            WHERE f.id_feature='.(int) $id_feature;
            $featurelang = Db::getInstance()->ExecuteS($sql);
            foreach ($featurelang as $f)
            {
                $sql = 'INSERT INTO '._DB_PREFIX_.'feature_lang (id_feature,id_lang,name) VALUES ('.(int) $newFeatureID.','.(int) $f['id_lang'].",'".psql($f['name'])."')";
                Db::getInstance()->Execute($sql);
            }
            $sql = '
            SELECT * FROM '._DB_PREFIX_.'feature_value fv
            LEFT JOIN '._DB_PREFIX_.'feature_value_lang fvl ON (fvl.id_feature_value=fv.id_feature_value)
            WHERE fv.id_feature='.(int) $id_feature.' AND fv.custom=0';
            $featurevalues = Db::getInstance()->ExecuteS($sql);
            $inserted = array();
            foreach ($featurevalues as $fv)
            {
                if (!in_array($fv['id_feature_value'], $inserted))
                {
                    $sql = 'INSERT INTO '._DB_PREFIX_.'feature_value (id_feature) VALUES ('.$newFeatureID.')';
                    Db::getInstance()->Execute($sql);
                    $newFVID = Db::getInstance()->Insert_ID();
                    $inserted[] = $fv['id_feature_value'];
                }
                $sql = 'INSERT INTO '._DB_PREFIX_.'feature_value_lang (id_feature_value,id_lang,value) VALUES ('.$newFVID.",'".$fv['id_lang']."','".psql($fv['value'])."')";
                Db::getInstance()->Execute($sql);
            }
        }

        // PM Cache
        if (!empty($updated_features))
        {
            ExtensionPMCM::clearFromIdsFeature($updated_features);
        }

        $newId = 0;
        $_POST['gr_id'] = 0;
        $action = 'duplicate';
    }
    elseif (isset($_POST['!nativeeditor_status']) && trim($_POST['!nativeeditor_status']) == 'updated')
    {
        $fields_lang = ['name'];

        foreach ($fields_lang as $field)
        {
            if (Tools::isSubmit($field))
            {
                $dataLang = Tools::getValue($field);
                foreach ($languages as $lang)
                {
                    if(isset($dataLang[$lang['iso_code']]))
                    {
                        $sqltest = 'SELECT * 
                                    FROM ' . _DB_PREFIX_ . 'feature_lang 
                                    WHERE id_feature = ' . (int)$id_feature . ' 
                                    AND id_lang = ' . (int)$lang['id_lang'];
                        $exists = Db::getInstance()->getRow($sqltest);
                        if (!$exists)
                        {
                            $sql = 'INSERT INTO ' . _DB_PREFIX_ . 'feature_lang ('.(int) $id_feature.', '.(int) $lang['id_lang'].', "' . bqSQL($dataLang[$lang['iso_code']]) . '")';
                        }
                        else
                        {
                            $sql = 'UPDATE '._DB_PREFIX_.'feature_lang 
                                    SET `'.bqSQL($field).'` = "'.pSQL($dataLang[$lang['iso_code']]).'" 
                                    WHERE id_feature = '.(int) $id_feature .' 
                                    AND id_lang = '.(int) $lang['id_lang'];
                        }
                        if(Db::getInstance()->execute($sql))
                        {
                            addToHistory('feature', 'modification', $field, (int) $id_feature, $lang['id_lang'], _DB_PREFIX_.'feature_lang', pSQL($dataLang[$lang['iso_code']]), (!$exists ? false : pSQL($exists[$field])));
                        }
                        break;
                    }
                }
            }
        }

        // PM Cache
        if (!empty($id_feature))
        {
            ExtensionPMCM::clearFromIdsFeature($id_feature);
        }

        $newId = Tools::getValue('gr_id');
        $action = 'update';
    }
    elseif (isset($_POST['!nativeeditor_status']) && trim($_POST['!nativeeditor_status']) == 'deleted')
    {
        $feature = new Feature($id_feature, $id_lang);
        $feature->delete();

        Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'feature` WHERE `id_feature` = '.(int) $id_feature);
        Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'feature_lang` WHERE `id_feature` = '.(int) $id_feature);
        Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'feature_shop` WHERE `id_feature` = '.(int) $id_feature);

        // PM Cache
        if (!empty($id_feature))
        {
            ExtensionPMCM::clearFromIdsFeature($id_feature);
        }

        $newId = Tools::getValue('gr_id');
        $action = 'delete';
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
    echo $debug && isset($sql2) ? '<sql><![CDATA['.$sql2.']]></sql>' : '';
    echo $debug && isset($sql3) ? '<sql><![CDATA['.$sql3.']]></sql>' : '';
    echo '</data>';
