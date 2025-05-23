<?php
if (!defined('STORE_COMMANDER')) { exit; }

@error_reporting(E_ERROR | E_PARSE);
@ini_set('display_errors', 'ON');

$id_lang = Tools::getValue('id_lang', '0');
$action = Tools::getValue('action', '');

$return = 'ERROR: Try again later';

// FUNCTIONS
$reloadCatLeftTree = false;
$reloadCat = false;
$return_datas = array();

// Récupération de toutes les modifications à effectuer
if (Tools::getValue('rows') || $action == 'insert')
{
    if ($action != 'insert')
    {
        if(_PS_MAGIC_QUOTES_GPC_)
            $_POST["rows"] = Tools::getValue('rows');
        $rows = json_decode($_POST["rows"]);
    }
    else
    {
        $rows = array();
        $rows[0] = new stdClass();
        $rows[0]->name = Tools::getValue('act', '');
        $rows[0]->action = Tools::getValue('action', '');
        $rows[0]->row = Tools::getValue('gr_id', '');
        $rows[0]->callback = Tools::getValue('callback', '');
        $rows[0]->params = $_POST;
    }

    if (is_array($rows) && count($rows) > 0)
    {
        $callbacks = '';

        // Première boucle pour remplir la table sc_queue_log
        // avec toutes ces modifications
        $log_ids = array();
        $date = date('Y-m-d H:i:s');
        foreach ($rows as $num => $row)
        {
            $id = QueueLog::add($row->name, $row->row, $row->action, (!empty($row->params) ? $row->params : array()), (!empty($row->callback) ? $row->callback : null), $date);
            $log_ids[$num] = $id;
        }

        // Deuxième boucle pour effectuer les
        // actions les une après les autres
        foreach ($rows as $num => $row)
        {
            if (!empty($log_ids[$num]))
            {
                $gr_id = (int) $row->row;
                $id_category = $row->row;
                $action = $row->action;

                if (!empty($row->callback))
                {
                    $callbacks .= $row->callback.';';
                }

                if ($action != 'insert')
                {
                    $_POST = array();
                    $_POST = (array) json_decode($row->params);
                }

                if (!empty($action) && $action == 'update')
                {
                    $idlist = Tools::getValue('idlist', '');
                    $sub_action = Tools::getValue('sub_action', '');

                    if ($sub_action != '')
                    {
                        switch ($sub_action) {
                            case '1':
                                $sql = 'INSERT IGNORE INTO `'._DB_PREFIX_.'category_group` (id_group,id_category)
                                    VALUES ('.(int) $idlist.','.(int) $gr_id.')';
                                Db::getInstance()->execute($sql);
                                break;
                            case '0':
                                $sql = 'DELETE FROM `'._DB_PREFIX_.'category_group` WHERE `id_group` IN ('.pInSQL($idlist).') AND `id_category` = '.(int) $gr_id;
                                Db::getInstance()->execute($sql);
                                break;
                            case 'multi_add':
                                $ids_groups = explode(',', Tools::getValue('ids_groups', '0'));
                                $ids_categs = explode(',', Tools::getValue('ids_categs', '0'));
                                $find_relation = array();
                                $inserted = 0;
                                $increment = 0;
                                foreach ($ids_categs as $id_category)
                                {
                                    foreach ($ids_groups as $id_group)
                                    {
                                        $sql = 'SELECT *
                                                FROM '._DB_PREFIX_.'category_group
                                                WHERE id_category = '.(int) $id_category.'
                                                AND id_group ='.(int) $id_group;
                                        if (!Db::getInstance()->executeS($sql))
                                        {
                                            $increment++;
                                            $inserted += (int)Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'category_group` (id_group,id_category) VALUES ('.(int) $id_group.','.(int) $id_category.')');
                                        }
                                    }
                                }
                                if ($inserted === $increment)
                                {
                                    $reloadCat = 1;
                                }
                                break;
                            case 'multi_del':
                                $ids_groups = explode(',', Tools::getValue('ids_groups', '0'));
                                $ids_categs = explode(',', Tools::getValue('ids_categs', '0'));
                                $find_relation = array();
                                $deleted = 0;
                                foreach ($ids_categs as $id_category)
                                {
                                    foreach ($ids_groups as $id_group)
                                    {
                                        $sql = 'SELECT *
                                                FROM '._DB_PREFIX_.'category_group
                                                WHERE id_category = '.(int) $id_category.'
                                                AND id_group ='.(int) $id_group;
                                        if (Db::getInstance()->executeS($sql))
                                        {
                                            $deleted += (int)Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'category_group` WHERE id_group = '.(int) $id_group.' AND id_category = '.(int) $id_category);
                                        }
                                    }
                                }
                                if ($deleted)
                                {
                                    $reloadCat = 1;
                                }
                                break;
                        }
                    }
                }

                $return_callback = '';
                if ($reloadCat)
                {
                    $return_datas['refresh_cat'] = '1';
                }
                foreach ($return_datas as $key => $val)
                {
                    if (!empty($key))
                    {
                        if (!empty($return_callback))
                        {
                            $return_callback .= ',';
                        }
                        $return_callback .= $key.":'".str_replace("'", "\'", $val)."'";
                    }
                }
                $return_callback = '{'.$return_callback.'}';
                $callbacks = str_replace('{data}', $return_callback, $callbacks);

                QueueLog::delete(($log_ids[$num]));
            }
        }

        // RETURN
        $return = json_encode(array('callback' => $callbacks));
    }
}
echo $return;
