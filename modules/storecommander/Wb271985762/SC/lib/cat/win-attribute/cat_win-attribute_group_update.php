<?php
if (!defined('STORE_COMMANDER')) { exit; }

    $id_attribute_group = (int) Tools::getValue('gr_id');
    $id_lang = (int) Tools::getValue('id_lang');
    $action = Tools::getValue('action', 0);

    if (!empty($action) && $action == 'position')
    {
        $todo = array();
        $row = explode(';', Tools::getValue('positions'));
        foreach ($row as $v)
        {
            if ($v != '')
            {
                $pos = explode(',', $v);
                $todo[] = 'UPDATE '._DB_PREFIX_.'attribute_group SET position='.$pos[1].' WHERE id_attribute_group='.(int) $pos[0];
            }
        }
        foreach ($todo as $task)
        {
            Db::getInstance()->Execute($task);
        }
    }
    elseif (isset($_POST['!nativeeditor_status']) && trim($_POST['!nativeeditor_status']) == 'inserted')
    {
        $newgroup = new AttributeGroup();
        if (version_compare(_PS_VERSION_, '1.5.0', '>='))
        {
            foreach ($languages as $lang)
            {
                $newgroup->name[$lang['id_lang']] = 'new';
                $newgroup->public_name[$lang['id_lang']] = 'new';
            }
            $newgroup->group_type = 'select';
            $newgroup->position = AttributeGroup::getHigherPosition() + 1;
            $newgroup->id_shop_list = Shop::getShops(true, null, true);
        }
        $newgroup->add();
        $newId = $newgroup->id;
        $action = 'insert';
    }
    elseif (isset($_POST['!nativeeditor_status']) && trim($_POST['!nativeeditor_status']) == 'updated')
    {
        $fields = array('is_color_group', 'group_type');
        $fields_lang = array('name', 'public_name');
        $todo = array();
        $todo_lang = array();

        foreach ($fields as $field)
        {
            if (Tools::isSubmit($field))
            {
                $sql = 'UPDATE '._DB_PREFIX_.'attribute_group 
                        SET `'.bqSQL($field).'` = "'.pSQL(html_entity_decode(Tools::getValue($field))).'" 
                        WHERE id_attribute_group='.(int) $id_attribute_group;
                if(Db::getInstance()->Execute($sql))
                {
                    addToHistory('attribute_group', 'modification', $field, (int)$id_attribute_group, $id_lang, _DB_PREFIX_ . 'attribute_group', pSQL(Tools::getValue($field)));
                }
                break;
            }
        }

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
                                    FROM ' . _DB_PREFIX_ . 'attribute_group_lang 
                                    WHERE id_attribute_group = ' . (int)$id_attribute_group . ' 
                                    AND id_lang = ' . (int)$lang['id_lang'];
                        $exists = Db::getInstance()->getRow($sqltest);
                        if (!$exists)
                        {
                            $sql = 'INSERT INTO ' . _DB_PREFIX_ . 'attribute_group_lang ('.(int) $id_attribute_group.', '.(int) $lang['id_lang'].',"' . bqSQL($dataLang[$lang['iso_code']]) . '", "' . bqSQL($dataLang[$lang['iso_code']]) . '")';
                        }
                        else
                        {
                            $sql = 'UPDATE '._DB_PREFIX_.'attribute_group_lang 
                                    SET `'.bqSQL($field).'` = "'.pSQL($dataLang[$lang['iso_code']]).'" 
                                    WHERE id_attribute_group = '.(int) $id_attribute_group .' 
                                    AND id_lang = '.(int) $lang['id_lang'];
                        }
                        if(Db::getInstance()->execute($sql))
                        {
                            addToHistory('attribute_group', 'modification', $field, (int) $id_attribute_group, $lang['id_lang'], _DB_PREFIX_.'attribute_group_lang', pSQL($dataLang[$lang['iso_code']]), (!$exists ? false : pSQL($exists[$field])));
                        }
                        break;
                    }
                }
            }
        }

        $newId = Tools::getValue('gr_id');
        $action = 'update';
    }
    elseif (isset($_POST['!nativeeditor_status']) && trim($_POST['!nativeeditor_status']) == 'deleted')
    {
        $group = new AttributeGroup($id_attribute_group, $id_lang);
        $group->delete();

        if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
        {
            /* Select children in order to find linked combinations */
            $attribute_ids = Db::getInstance()->executeS('
                SELECT `id_attribute`
                FROM `'._DB_PREFIX_.'attribute`
                WHERE `id_attribute_group` = '.(int) $id_attribute_group
            );
            if ($attribute_ids !== false)
            {
                /* Removing attributes to the found combinations */
                $to_remove = array();
                foreach ($attribute_ids as $attribute)
                {
                    $to_remove[] = (int) $attribute['id_attribute'];
                }
                if (!empty($to_remove))
                {
                    Db::getInstance()->execute('
                    DELETE FROM `'._DB_PREFIX_.'product_attribute_combination`
                    WHERE `id_attribute`
                        IN ('.implode(', ', $to_remove).')');
                }
            }
            /* Remove combinations if they do not possess attributes anymore */
            AttributeGroup::cleanDeadCombinations();

            /* Also delete related attributes */
            Db::getInstance()->execute('
                DELETE FROM `'._DB_PREFIX_.'attribute_lang`
                WHERE `id_attribute`
                    IN (SELECT id_attribute FROM `'._DB_PREFIX_.'attribute` WHERE `id_attribute_group` = '.(int) $id_attribute_group.')');
            Db::getInstance()->execute('
                DELETE FROM `'._DB_PREFIX_.'attribute_shop`
                WHERE `id_attribute`
                    IN (SELECT id_attribute FROM `'._DB_PREFIX_.'attribute` WHERE `id_attribute_group` = '.(int) $id_attribute_group.')');
            if (version_compare(_PS_VERSION_, '8.0.0', '<'))
            {
                Db::getInstance()->execute('
                    DELETE FROM `'._DB_PREFIX_.'attribute_impact`
                    WHERE `id_attribute`
                        IN (SELECT id_attribute FROM `'._DB_PREFIX_.'attribute` WHERE `id_attribute_group` = '.(int) $id_attribute_group.')');
            }
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute` WHERE `id_attribute_group` = '.(int) $id_attribute_group);
            $group->cleanPositions();

            $sql2 = 'DELETE FROM '._DB_PREFIX_.'attribute_group WHERE id_attribute_group='.(int) $id_attribute_group;
            Db::getInstance()->Execute($sql2);
            $sql2 = 'DELETE FROM '._DB_PREFIX_.'attribute_group_lang WHERE id_attribute_group='.(int) $id_attribute_group;
            Db::getInstance()->Execute($sql2);
            $sql2 = 'DELETE FROM '._DB_PREFIX_.'attribute_group_shop WHERE id_attribute_group='.(int) $id_attribute_group;
            Db::getInstance()->Execute($sql2);
        }

        $newId = Tools::getValue('gr_id');
        $action = 'delete';
    }

    // PM Cache
    if (!empty($id_attribute_group))
    {
        ExtensionPMCM::clearFromIdsAttributeGroup($id_attribute_group);
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
