<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 'ON');

$id_lang = Tools::getValue('sc_id_lang', '0');
$action = Tools::getValue('action', '');

$return = 'ERROR: Try again later';

// FUNCTIONS
$debug = false;
$extraVars = '';

// Récupération de toutes les modifications à effectuer
if (Tools::getValue('rows') || $action == 'insert')
{
    if ($action == 'format')
    {
        $type = Tools::getValue('type', '');
        switch ($type){
            case 'capitalize':
                $customers = Db::getInstance()->executeS('SELECT `id_customer`, LOWER(`lastname`) as lastname, LOWER(`firstname`) as firstname FROM `'._DB_PREFIX_.'customer`');
                if (!empty($customers))
                {
                    foreach ($customers as $customer)
                    {
                        $lastname = ucwords($customer['lastname'], "'- ");
                        $firstname = ucwords($customer['firstname'], "'- ");
                        Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'customer` 
                                                            SET `firstname`="'.pSQL($firstname).'", `lastname`="'.pSQL($lastname).'"
                                                            WHERE `id_customer` = '.(int) $customer['id_customer']);
                    }
                }
                exit('OK');
            case 'uppercase':
                $customers = Db::getInstance()->executeS('SELECT `id_customer`, UPPER(`lastname`) as lastname, LOWER(`firstname`) as firstname FROM `'._DB_PREFIX_.'customer`');
                if (!empty($customers))
                {
                    foreach ($customers as $customer)
                    {
                        $firstname = ucwords($customer['firstname'], "'- ");
                        Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'customer` 
                                                            SET `firstname`="'.pSQL($firstname).'", `lastname`="'.pSQL($customer['lastname']).'"
                                                            WHERE `id_customer` = '.(int) $customer['id_customer']);
                    }
                }
                exit('OK');
        }
        exit;
    }
    if ($action != 'insert')
    {
        if (_PS_MAGIC_QUOTES_GPC_)
        {
            $_POST['rows'] = Tools::getValue('rows');
        }
        $rows = json_decode($_POST['rows']);
    }
    else
    {
        $rows = [];
        $rows[0] = new stdClass();
        $rows[0]->name = Tools::getValue('act', '');
        $rows[0]->action = Tools::getValue('action', '');
        $rows[0]->row = Tools::getValue('gr_id', '');
        $rows[0]->callback = Tools::getValue('callback', '');
        $rows[0]->params = $_POST;
    }

    if (is_array($rows) && count($rows) > 0)
    {
        $callbacks = $return_datas = [];

        // Première boucle pour remplir la table sc_queue_log
        // avec toutes ces modifications
        $log_ids = [];
        $date = date('Y-m-d H:i:s');

        foreach ($rows as $num => $row)
        {
            $id = QueueLog::add($row->name, $row->row, $row->action, (!empty($row->params) ? $row->params : []), (!empty($row->callback) ? $row->callback : null), $date);
            $log_ids[$num] = $id;
        }

        // Deuxième boucle pour effectuer les
        // actions les une après les autres

        foreach ($rows as $num => $row)
        {
            if (!empty($log_ids[$num]))
            {
                $gr_id = (int) $row->row;
                $action = $row->action;

                if (!empty($row->callback))
                {
                    $callbacks[$num] = trim($row->callback, ';');
                }

                if ($action != 'insert')
                {
                    $_POST = [];
                    $_POST = (array) json_decode($row->params);
                }
                $_POST['gr_id'] = $gr_id;

                ## insert = PS
                if (!empty($action) && $action == 'delete' && !empty($gr_id))
                {
                    if (array_key_exists('id_customer', $_POST))
                    {
                        $id_customer = (int) Tools::getValue('id_customer');
                        $full_delete = (bool) Tools::getValue('full_delete');
                        if ($full_delete)
                        {
                            $customer = new Customer((int) $id_customer);
                            $customer->delete();
                        }
                        else
                        {
                            $sql = 'UPDATE `'._DB_PREFIX_.'customer`
                                    SET `deleted` = 1, date_upd=NOW()
                                    WHERE `id_customer` = '.(int) $id_customer;
                            Db::getInstance()->execute($sql);
                        }
                        addToHistory('customer', 'delete', 'customer', (int) $id_customer, null, _DB_PREFIX_.'customer', null, null);
                    }
                }
                elseif (!empty($action) && $action == 'update' && !empty($gr_id))
                {
                    $fields = ['id_gender', 'siret', 'ape', 'firstname', 'lastname', 'email', 'active', 'newsletter', 'optin', 'birthday', 'id_default_group', 'note', 'id_lang', 'website'];
                    $fields_address = ['firstname', 'lastname', 'address1', 'address2', 'postcode', 'city', 'id_state', 'id_country', 'other', 'phone', 'phone_mobile', 'vat_number'];

                    $id_address = (int) Tools::getValue('id_address');
                    if ($id_address)
                    {
                        $fields_address[] = 'company';
                    }
                    else
                    {
                        $fields[] = 'company';
                    }
                    $id_customer = (int) ($id_address ? Tools::getValue('id_customer') : $gr_id);

                    SC_Ext::readCustomCustomersGridsConfigXML('updateSettings');
//                    SC_Ext::readCustomCustomersGridsConfigXML('onBeforeUpdateSQL'); // pas présent dans GEP

                    // customers
                    try {
                        $customer = new Customer($id_customer);
                        foreach ($fields as $field)
                        {
                            if (array_key_exists($field, $_POST))
                            {
                                $customer->{$field} = Tools::getValue($field);
                                addToHistory('customer', 'modification', $field, (int) $id_customer, 0, _DB_PREFIX_.'customer', pSQL(Tools::getValue($field)));
                            }
                        }
//                        $customer->groupBox=$customer->getGroups();
                        $customer->hydrate($customer->getFields());
                        $customer->update();
                    } catch(Exception $e) {
                        exit($e->getMessage());
                    }


                    // addresses
                    if ($id_address)
                    {
                        try {
                            $address = new Address($id_address);
                            foreach ($fields_address as $field)
                            {
                                if (array_key_exists($field, $_POST))
                                {
                                    $address->{$field} = Tools::getValue($field);
                                    addToHistory('address', 'modification', $field, (int) $id_address, 0, _DB_PREFIX_.'address', pSQL(Tools::getValue($field)));
                                }
                            }
                            $address->hydrate($address->getFields());
                            $address->update();
                        } catch(Exception $e) {
                            exit($e->getMessage());
                        }

                    }


                    $for_address = $id_address > 0; ## pour GEP
                    SC_Ext::readCustomCustomersGridsConfigXML('onAfterUpdateSQL');

                }

                $callbacks[$num] = str_replace('{data}', (string) json_encode($return_datas), $callbacks[$num]);

                QueueLog::delete(($log_ids[$num]));
            }
        }

        // RETURN
        $return = json_encode(['callback' => implode(';', $callbacks)]);
    }
}

echo $return;
