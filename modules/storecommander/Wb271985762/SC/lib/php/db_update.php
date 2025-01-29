<?php

// ----------------------------------------------------------------------------
//
//  Function:   isTable
//  Purpose:        Check if table exists in database
//  Arguments:    name of the table to check
//
// ----------------------------------------------------------------------------
    function isTable($name)
    {
        global $sc_tables;
        if (!is_array($sc_tables))
        {
            $sc_tables = array();
            $sql = 'SHOW TABLES';
            $res = Db::getInstance()->ExecuteS($sql);
            foreach ($res as $val)
            {
                $tmp = array_values($val);
                $sc_tables[] = $tmp[0];
            }
        }
        if (sc_in_array(_DB_PREFIX_.$name, $sc_tables, 'DBUpdate_sc_tables'))
        {
            return true;
        }

        return false;
    }

// ----------------------------------------------------------------------------
//
//  Function:   isField
//  Purpose:        Check if field exists in table
//  Arguments:    name of the field to check, name of the table without prefix
//
// ----------------------------------------------------------------------------
    function isField($name, $table)
    {
        global $sc_fields;
        if (!is_array($sc_fields))
        {
            $sc_fields = array();
        }
        if (!sc_array_key_exists($table, $sc_fields))
        {
            $fields = array();
            $sql = 'SHOW COLUMNS FROM '._DB_PREFIX_.psql($table);
            $res = Db::getInstance()->ExecuteS($sql);
            foreach ($res as $val)
            {
                $fields[] = $val['Field'];

                if ($table == 'product_attribute' && $val['Field'] == 'date_upd')
                {
                    if (empty($val['Default']))
                    {
                        Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_."product_attribute` CHANGE `date_upd` `date_upd` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");

                        $nulls = Db::getInstance()->ExecuteS('SELECT id_product_attribute,date_upd FROM `'._DB_PREFIX_.'product_attribute` WHERE date_upd IS NULL');
                        foreach ($nulls as $null)
                        {
                            if (empty($null['date_upd']) && $null['date_upd'] != '0000-00-00 00:00:00')
                            {
                                Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_."product_attribute` SET `date_upd`='0000-00-00 00:00:00' WHERE id_product_attribute='".(int) $null['id_product_attribute']."'");
                            }
                        }
                    }
                }
            }
            $sc_fields[$table] = $fields;
        }
        if (sc_in_array($name, $sc_fields[$table], 'DBUpdate_table'.$table))
        {
            return true;
        }

        return false;
    }

// ----------------------------------------------------------------------------
//
//  Function:   checkDB
//  Purpose:        Check and update DB
//  Arguments:    none
//
// ----------------------------------------------------------------------------
    function checkDB()
    {
        global $sc_tables, $sc_alerts, $sc_agent;

        $sql = 'SHOW TABLE STATUS WHERE Name = "'._DB_PREFIX_.'product"';
        $table_config = Db::getInstance()->ExecuteS($sql);
        $mysqlEngine = 'InnoDB';
        if (!empty($table_config))
        {
            $table_config = $table_config[0];
            if (array_key_exists('Engine', $table_config) && !empty($table_config['Engine']))
            {
                $mysqlEngine = $table_config['Engine'];
            }
        }

        // History
        if (!isTable('storecom_history'))
        {
            $sql = '
                CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_."storecom_history` (
                    `id_history` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
                    `id_employee` INT NOT NULL, 
                    `section` VARCHAR(32) NOT NULL, 
                    `action` VARCHAR(32) NOT NULL, 
                    `object` VARCHAR(32) NOT NULL, 
                    `object_id` INT NOT NULL, 
                    `lang_id` INT NOT NULL, 
                    `dbtable` VARCHAR(32) NOT NULL, 
                    `date_add` DATETIME NOT NULL, 
                    `oldvalue` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
                    `newvalue` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
                    `flag` TINYINT(1) NOT NULL DEFAULT '0') ENGINE =".$mysqlEngine.' DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
                ';
            Db::getInstance()->Execute($sql);
            $sc_tables = 0;
            if (!isTable('storecom_history'))
            {
                exit(_l('Store Commander cannot create the table %s, please contact your hosting support and ask: Can you please confirm that the MySQL user has the necessary permission to execute these commands: SHOW and CREATE TABLE. Upon confirmation, you can restart Store Commander.', 0, _DB_PREFIX_.'storecom_history'));
            }
        }
        $field = Db::getInstance()->ExecuteS('SHOW COLUMNS FROM `'._DB_PREFIX_.'storecom_history` LIKE \'id_employee\'');
        if (!count($field))
        {
            Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'storecom_history` ADD `id_employee` INT NOT NULL');
        }
        // Queue log
        if (!isTable('sc_queue_log'))
        {
            $sql = '
                CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'sc_queue_log` (
                  `id_sc_queue_log` int(11) NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) NOT NULL,
                  `row` varchar(255) NOT NULL,
                  `action` varchar(255) NOT NULL,
                  `params` text,
                  `callback` text,
                  `id_employee` int(11) NOT NULL,
                  `date_add` datetime NOT NULL,
                  PRIMARY KEY (`id_sc_queue_log`)
                ) ENGINE='.$mysqlEngine.' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
                ';
            Db::getInstance()->Execute($sql);
            $sc_tables = 0;
            if (!isTable('sc_queue_log'))
            {
                exit(_l('Store Commander cannot create the table %s, please contact your hosting support and ask: Can you please confirm that the MySQL user has the necessary permission to execute these commands: SHOW and CREATE TABLE. Upon confirmation, you can restart Store Commander.', 0, _DB_PREFIX_.'sc_queue_log'));
            }
        }
        // image filename field in ps_image
        if (_s('CAT_PROD_IMG_SAVE_FILENAME') && !isField('sc_path', 'image'))
        {
            $sql = 'ALTER TABLE `'._DB_PREFIX_."image` ADD `sc_path` VARCHAR( 150 ) NOT NULL DEFAULT ''";
            Db::getInstance()->Execute($sql);
        }
        // date_upd field in ps_product_attribute
        if (!isField('date_upd', 'product_attribute'))
        {
            $sql = 'ALTER TABLE `'._DB_PREFIX_."product_attribute` ADD `date_upd` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'";
            Db::getInstance()->Execute($sql);
        }
        if (!isTable('sc_export'))
        {
            $sql = '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_."sc_export` (
              `id_sc_export` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) NOT NULL,
              `last_export` datetime DEFAULT NULL,
              `exporting` tinyint(1) NOT NULL DEFAULT '0',
              `id_next` int(11) NOT NULL DEFAULT '0',
              `id_combination_next` int(11) NOT NULL DEFAULT '0',
              `total_lines` int(11) NOT NULL DEFAULT '0',
              PRIMARY KEY (`id_sc_export`),
              UNIQUE KEY `name` (`name`)
            ) ENGINE=".$mysqlEngine.'  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
            Db::getInstance()->Execute($sql);
        }
        if (!isTable('sc_export_product'))
        {
            $sql = '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_."sc_export_product` (
              `id_sc_export_product` int(11) NOT NULL AUTO_INCREMENT,
              `id_sc_export` int(11) NOT NULL,
              `id_product` int(11) NOT NULL,
              `id_product_attribute` int(11) NOT NULL DEFAULT '0',
              `handled` tinyint(1) NOT NULL DEFAULT '0',
              `exported` tinyint(1) NOT NULL DEFAULT '0',
              PRIMARY KEY (`id_sc_export_product`)
            ) ENGINE=".$mysqlEngine.'  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
            Db::getInstance()->Execute($sql);
        }
        if (!isField('handled', 'sc_export_product'))
        {
            $sql = 'ALTER TABLE `'._DB_PREFIX_."sc_export_product` ADD `handled`  tinyint(1) NOT NULL DEFAULT '0'";
            Db::getInstance()->Execute($sql);
        }
        if (!isField('exported', 'sc_export_product'))
        {
            $sql = 'ALTER TABLE `'._DB_PREFIX_."sc_export_product` ADD `exported`  tinyint(1) NOT NULL DEFAULT '0'";
            Db::getInstance()->Execute($sql);
        }

        // Corrige un probl�me d'index unique dans Prestashop Table ps_specific_price
        $sql = 'SHOW INDEX FROM '._DB_PREFIX_."specific_price WHERE column_name = 'id_product_attribute' AND non_unique = 0";
        $res = Db::getInstance()->ExecuteS($sql);
        if (count($res) > 0)
        {
            $key_name = $res[0]['Key_name'];
            $sql = 'ALTER TABLE `'._DB_PREFIX_.'specific_price` DROP INDEX  `'.bqSQL($key_name).'` , ADD INDEX `'.bqSQL($key_name).'` (  `id_product` ,  `id_shop` ,  `id_shop_group` ,  `id_currency` ,  `id_country` ,  `id_group` ,  `id_customer` ,  `id_product_attribute` , `from_quantity` ,  `from` ,  `to` )';
            Db::getInstance()->Execute($sql);
        }

        // champs cache déclinaison pour commande
        if (!isField('sc_attr_infos_v1', 'order_detail'))
        {
            $sql = 'ALTER TABLE `'._DB_PREFIX_.'order_detail` ADD `sc_attr_infos_v1` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL';
            Db::getInstance()->Execute($sql);
        }

        if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
        {
            if (!isField('shops', 'storecom_history'))
            {
                $sql = 'ALTER TABLE `'._DB_PREFIX_."storecom_history` ADD `shops` varchar(255) NOT NULL DEFAULT '0'";
                Db::getInstance()->Execute($sql);
            }
        }

        // Check si doublon
        $field = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'configuration` WHERE name = \'SC_VERSIONS\'');
        if (count($field) > 1)
        {
            Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'configuration` WHERE name = \'SC_VERSIONS\'');
        }

        // Check disable_functions
        $disabled_functions = ini_get('disable_functions');
        if ($disabled_functions != '')
        {
            $arr = explode(',', $disabled_functions);
            $err = array();

            if (in_array('parse_ini_file', $arr) && !in_array('parse_ini_file', $err))
            {
                $err[] = 'parse_ini_file';
            }

            if (in_array(' curl_exec', $arr) && !in_array('curl_exec', $err))
            {
                $err[] = 'curl_exec';
            }
            if (in_array('curl_exec', $arr) && !in_array('curl_exec', $err))
            {
                $err[] = 'curl_exec';
            }

            if (!empty($err) && count($err) > 0)
            {
                $sc_alerts[] = _l('These functions are necessary for Store Commander but disabled in PHP configuration:').' '.implode(', ', $err);
            }
        }

        if (isTable('sc_ff_project') && !isField('nb_product', 'sc_ff_project'))
        {
            $sql = 'ALTER TABLE `'._DB_PREFIX_."sc_ff_project` ADD `nb_product` INT NOT NULL DEFAULT '0' AFTER `params`;";
            Db::getInstance()->Execute($sql);

            $ff_projects = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'sc_ff_project` WHERE status NOT IN ("created","configured","pay")');
            foreach ($ff_projects as $ff_project)
            {
                $cat = new Category((int) $ff_project['id_category']);
                $nb = $cat->getProducts(null, 1, 1, null, null, true, false);
                if (!empty($nb))
                {
                    Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'sc_ff_project` SET nb_product = "'.(int) $nb.'" WHERE id_project = "'.(int) $ff_project['id_project'].'"');
                }
            }
        }
        // sc_note => bloc note
        if (!isField('sc_note', 'employee'))
        {
            $sql = 'ALTER TABLE `'._DB_PREFIX_.'employee` ADD `sc_note` text';
            Db::getInstance()->Execute($sql);
        }
        // image compression
        if (!isTable('storecom_imagefile'))
        {
            $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_."storecom_imagefile` (
                    `id_storecom_imagefile` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
                    `name` VARCHAR(255) NOT NULL, 
                    `path` VARCHAR(255) NOT NULL, 
                    `size_origin` INT(11) DEFAULT '0' COMMENT 'en octets', 
                    `size_compressed` INT(11) DEFAULT '0' COMMENT 'en octets', 
                    `size_saved` INT(11) DEFAULT '0' COMMENT 'en octets', 
                    `priority` INT(11) DEFAULT '0', 
                    `image_base` INT(1) DEFAULT '0', 
                    `status` INT(11) DEFAULT '0',
                    `date_last_scan` datetime DEFAULT NULL,
                    `count_compression_request` INT(11) DEFAULT '0',
                    `chmod` INT(11) DEFAULT '0', 
                    `ork_info` VARCHAR(255) DEFAULT NULL,
                    `date_callback` datetime DEFAULT NULL, 
                    UNIQUE KEY `path_unique` (`path`),
                    INDEX (`path`),
                    INDEX (`status`)
                    ) ENGINE =".$mysqlEngine.' DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;';
            Db::getInstance()->Execute($sql);
            $sc_tables = 0;
            if (!isTable('storecom_imagefile'))
            {
                exit(_l('Store Commander cannot create the table %s, please contact your hosting support and ask: Can you please confirm that the MySQL user has the necessary permission to execute these commands: SHOW and CREATE TABLE. Upon confirmation, you can restart Store Commander.', 0, _DB_PREFIX_.'storecom_imagefile'));
            }
        }
        else
        {
            if (!isField('chmod', 'storecom_imagefile'))
            {
                $sql = 'ALTER TABLE `'._DB_PREFIX_.'storecom_imagefile` ADD `chmod` INT(11) DEFAULT "0" AFTER `count_compression_request`;';
                Db::getInstance()->Execute($sql);
            }
            if (!isField('ork_info', 'storecom_imagefile'))
            {
                $sql = 'ALTER TABLE `'._DB_PREFIX_.'storecom_imagefile` ADD `ork_info` TEXT DEFAULT NULL AFTER `chmod`;';
                Db::getInstance()->Execute($sql);
            }
            if (!isField('date_callback', 'storecom_imagefile'))
            {
                $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'storecom_imagefile` ADD `date_callback` datetime DEFAULT NULL AFTER `ork_info`;';
                Db::getInstance()->Execute($sql);
            }
            $unique_path_exist = Db::getInstance()->executeS('SHOW INDEX FROM `'._DB_PREFIX_.'storecom_imagefile` WHERE `Key_name` = "path_unique"');
            if (empty($unique_path_exist))
            {
                // suppression des entrées en doublon avant ALTER TABLE, sinon erreur
                Db::getInstance()->Execute('DELETE t1 FROM `'._DB_PREFIX_.'storecom_imagefile` t1 INNER JOIN `'._DB_PREFIX_.'storecom_imagefile` t2 WHERE t1.id_storecom_imagefile < t2.id_storecom_imagefile AND t1.path = t2.path;');
                Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'storecom_imagefile` ADD UNIQUE INDEX `path_unique` (`path`);');
            }
        }


        ## Sc usage
        if (!isTable('storecom_usage'))
        {
            $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'storecom_usage` (
                      `id_storecom_usage` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                      `all` longtext,
                      `cat` longtext,
                      `cms` longtext,
                      `core` longtext,
                      `cus` longtext,
                      `cusm` longtext,
                      `man` longtext,
                      `ord` longtext,
                      `ser` longtext,
                      `sup` longtext,
                      `ork` longtext
                    ) ENGINE='.$mysqlEngine.' DEFAULT CHARACTER SET utf8 COLLATE=utf8_general_ci;';
            $table_add = Db::getInstance()->Execute($sql);
            if ($table_add)
            {
                Db::getInstance()->execute('INSERT IGNORE INTO `'._DB_PREFIX_.'storecom_usage` (id_storecom_usage) VALUES (1)');
            }
        }
        else if (!isField('ork', 'storecom_usage'))
        {
            Db::getInstance()->execute("ALTER TABLE `" . _DB_PREFIX_ . "storecom_usage` ADD `ork` LONGTEXT DEFAULT NULL AFTER `sup`");
        }

        if (defined('SC_ExportOrders_ACTIVE') && (int)SC_ExportOrders_ACTIVE == 1)
        {
            if (!isTable(SC_DB_PREFIX.'extension_export_order_filter'))
            {
                $query = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . SC_DB_PREFIX . 'extension_export_order_filter` (
                          `id_extension_export_order_filter` int(10) unsigned NOT NULL AUTO_INCREMENT,
                          `id_shop` int(11) DEFAULT 0,
                          `name` varchar(70) NOT NULL,
                          `description` varchar(255) DEFAULT NULL,
                          `static_definition` text,
                          `dynamic_definition` text,
                          `date_add` datetime DEFAULT NULL,
                          `date_upd` datetime DEFAULT NULL,
                          PRIMARY KEY (`id_extension_export_order_filter`)
                        ) ENGINE='.$mysqlEngine.' DEFAULT CHARSET=utf8;';
                if(Db::getInstance()->execute($query))
                {
                    if (isTable('storecommander_order_filter'))
                    {
                        $sql = 'INSERT INTO `' . _DB_PREFIX_ . SC_DB_PREFIX . 'extension_export_order_filter` 
                                    (`id_shop`, `name`, `description`, `static_definition`, `dynamic_definition`, `date_add`, `date_upd`)
                                SELECT `id_shop`, `name`, `description`, `static_definition`, `dynamic_definition`, `date_add`, `date_upd` 
                                    FROM `' . _DB_PREFIX_ . 'storecommander_order_filter`';
                        Db::getInstance()->execute($sql);
                    }
                    else
                    {
                        switch(Language::getIsoById($sc_agent->id_lang))
                        {
                            case 'fr':
                                $listName = 'Commandes derniers 30j';
                                $listDesc = 'Toutes les commandes des derniers 30 jours';
                                break;
                            case 'es':
                                $listName = 'Pedidos en los últimos 30d';
                                $listDesc = 'Todos los pedidos de los últimos 30 días';
                                break;
                            default:
                                $listName = 'Orders in last 30d';
                                $listDesc = 'All orders in the last 30 days';
                        }
                        $rule = 'o_order_state=>NONE|4,5,12,2,3__AND__o_date_add=>NONE|>=|30|DAY';

                        Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . SC_DB_PREFIX . "extension_export_order_filter` (`id_extension_export_order_filter`, `name`, `description`, `dynamic_definition`, `date_add`, `date_upd`)
                                                        VALUES (1, '" . pSQL($listName) . "', '" . pSQL($listDesc) . "', '" . pSQL($rule) . "', NOW(), NOW());");
                    }
                }
            }
            if (!isTable(SC_DB_PREFIX . 'extension_export_order_mapping'))
            {
                $query = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . SC_DB_PREFIX . 'extension_export_order_mapping` (
                          `id_extension_export_order_mapping` int(10) unsigned NOT NULL AUTO_INCREMENT,
                          `name` varchar(128) NOT NULL,
                          `separator` int(11) NOT NULL DEFAULT "1",
                          `fields` text,
                          `export_format` varchar(20) NOT NULL,
                          `format_properties` text,
                          `date_add` datetime DEFAULT NULL,
                          `date_upd` datetime DEFAULT NULL,
                          PRIMARY KEY (`id_extension_export_order_mapping`)
                        ) ENGINE='.$mysqlEngine.' DEFAULT CHARSET=utf8;';
                if(Db::getInstance()->execute($query))
                {
                    if (isTable('storecommander_order_mapping'))
                    {
                        $sql = 'INSERT INTO `' . _DB_PREFIX_ . SC_DB_PREFIX . 'extension_export_order_mapping` 
                                    (`name`, `separator`, `fields`, `export_format`, `format_properties`, `date_add`, `date_upd`)
                                SELECT `name`, `separator`, `fields`, `export_format`, `format_properties`, `date_add`, `date_upd` 
                                    FROM `' . _DB_PREFIX_ . 'storecommander_order_mapping`';
                        Db::getInstance()->execute($sql);
                    }
                    else
                    {
                        switch(Language::getIsoById($sc_agent->id_lang))
                        {
                            case 'fr':
                                $exportName = 'Informations simples sur les commandes';
                                break;
                            case 'es':
                                $exportName = 'Información sencilla para pedidos';
                                break;
                            default:
                                $exportName = 'Orders basic informations';
                        }
                        $fields = 'orderFields|Order_Id__addressInvoiceFields|Address_Invoice_First_name__addressInvoiceFields|Address_Invoice_Last_name__customerFields|Email__addressInvoiceFields|Address_Invoice_Country__orderFields|Order_Invoice_Number__orderFields|Order_Module__orderFields|Order_Total_Paid_Real__orderTotalFields|Currency_Iso_Code__orderFields|Order_Date_Add';
                        $formatProps = 'delimitor::;@@display_header::1@@display_breakdown_shipping::1@@display_breakdown_discounts::1';
                        Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . SC_DB_PREFIX . "extension_export_order_mapping` (`id_extension_export_order_mapping`, `name`, `fields`, `export_format`, `format_properties`, `date_add`, `date_upd`) 
                                                        VALUES(1, '".pSQL($exportName)."', '".pSQL($fields)."', 'CSV', '".pSQL($formatProps)."', NOW(), NOW());"
                        );
                    }
                }
            }
            if (!isTable(SC_DB_PREFIX . 'extension_export_order'))
            {
                $query = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . SC_DB_PREFIX . 'extension_export_order` (
                          `id_extension_export_order` int(10) unsigned NOT NULL AUTO_INCREMENT,
                          `id_extension_export_order_filter` int(11) DEFAULT NULL,
                          `id_extension_export_order_mapping` int(11) DEFAULT NULL,
                          `id_lang` int(11) DEFAULT NULL,
                          `filename` varchar(255) NOT NULL,
                          `token` varchar(255) NOT NULL,
                          `date_last_export` datetime DEFAULT NULL,
                          `date_add` datetime DEFAULT NULL,
                          `date_upd` datetime DEFAULT NULL,
                          PRIMARY KEY (`id_extension_export_order`)
                        ) ENGINE='.$mysqlEngine.' DEFAULT CHARSET=utf8;';
                if(Db::getInstance()->execute($query))
                {
                    $id_extension_export_order_filter = 1;
                    $id_extension_export_order_mapping = 1;
                    $filename = 'export_order';
                    $oldCronConfig = Configuration::get('SC_QUICKACCOUNTING_CRON_CONFIG', null);
                    if($oldCronConfig)
                    {
                        $cronConf = json_decode($oldCronConfig,true);
                        if(isset($cronConf['cron_list'])) {
                            $id_extension_export_order_filter = (int)$cronConf['cron_list'];
                        }
                        if(isset($cronConf['cron_export'])) {
                            $id_extension_export_order_mapping = (int)$cronConf['cron_export'];
                        }
                        if(isset($cronConf['cron_file_name'])) {
                            $filename = $cronConf['cron_file_name'];
                        }
                    }
                    Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . SC_DB_PREFIX . "extension_export_order` (`id_extension_export_order_filter`, `id_extension_export_order_mapping`, `id_lang`, `filename`, `token`, `date_add`, `date_upd`)
                                                    VALUES (".(int)$id_extension_export_order_filter.", ".(int)$id_extension_export_order_mapping.", ".(int)$sc_agent->id_lang.", '".pSQL($filename)."','".pSQL(generateToken())."', NOW(), NOW());");
                }
            }

            if (!isField('sc_qc_product_price', 'order_detail'))
            {
                Db::getInstance()->execute("ALTER TABLE `" . _DB_PREFIX_ . "order_detail` ADD `sc_qc_product_price` DECIMAL( 20, 6 ) NOT NULL DEFAULT '0'");
            }
            if (!isField('carrier_tax_rate', 'orders'))
            {
                Db::getInstance()->execute("ALTER TABLE `" . _DB_PREFIX_ . "orders` ADD `carrier_tax_rate` DECIMAL( 10, 3 ) NOT NULL DEFAULT '0'");
            }
        }

        if (defined('SC_ExportCustomers_ACTIVE') && (int)SC_ExportCustomers_ACTIVE == 1)
        {
            if (!isTable(SC_DB_PREFIX . 'extension_export_customer_filter'))
            {
                $query = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . SC_DB_PREFIX . 'extension_export_customer_filter` (
                          `id_extension_export_customer_filter` int(10) unsigned NOT NULL AUTO_INCREMENT,
                          `id_shop` int(11) DEFAULT 0,
                          `name` varchar(70) NOT NULL,
                          `description` varchar(255) DEFAULT NULL,
                          `static_definition` text,
                          `dynamic_definition` text,
                          `date_add` datetime DEFAULT NULL,
                          `date_upd` datetime DEFAULT NULL,
                          PRIMARY KEY (`id_extension_export_customer_filter`)
                        ) ENGINE='.$mysqlEngine.' DEFAULT CHARSET=utf8;';
                if(Db::getInstance()->execute($query))
                {
                    if (isTable('storecommander_customer_filter'))
                    {
                        $sql = 'INSERT INTO `' . _DB_PREFIX_ . SC_DB_PREFIX . 'extension_export_customer_filter` 
                                    (`id_shop`, `name`, `description`, `static_definition`, `dynamic_definition`, `date_add`, `date_upd`)
                                SELECT `id_shop`, `name`, `description`, `static_definition`, `dynamic_definition`, `date_add`, `date_upd` 
                                    FROM `' . _DB_PREFIX_ . 'storecommander_customer_filter`';
                        Db::getInstance()->execute($sql);
                    }
                    else
                    {
                        switch(Language::getIsoById($sc_agent->id_lang))
                        {
                            case 'fr':
                                $listName = 'Tous les clients';
                                $listDesc = 'Tous les clients avec un compte actif';
                                break;
                            case 'es':
                                $listName = 'Todos los clientes';
                                $listDesc = 'Todos los clientes con una cuenta activa';
                                break;
                            default:
                                $listName = 'All customers';
                                $listDesc = 'All customers with an active account';
                        }
                        $rule = 'c_all=>NONE|1__AND__c_active=>NONE|=|1';

                        Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . SC_DB_PREFIX . "extension_export_customer_filter` (`id_extension_export_customer_filter`, `name`, `description`, `dynamic_definition`, `date_add`, `date_upd`)
                                                        VALUES (1, '" . pSQL($listName) . "', '" . pSQL($listDesc) . "', '" . pSQL($rule) . "', NOW(), NOW());");
                        switch(Language::getIsoById($sc_agent->id_lang))
                        {
                            case 'fr':
                                $listName = 'Cibles newsletter';
                                $listDesc = 'Tous les clients / visiteurs inscrits à la Newsletter';
                                break;
                            case 'es':
                                $listName = 'Objetivos del Newsletter';
                                $listDesc = 'Todos los clientes/visitantes registrados en la Newsletter';
                                break;
                            default:
                                $listName = 'Newsletter target';
                                $listDesc = 'All customers / visitors that have subscribed to newsletter';
                        }
                        $rule = 'c_all=>NONE|1__AND__n_all=>NONE|1__AND__c_news=>NONE|=|1__AND__c_active=>NONE|=|1';

                        Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . SC_DB_PREFIX . "extension_export_customer_filter` (`id_extension_export_customer_filter`, `name`, `description`, `dynamic_definition`, `date_add`, `date_upd`)
                                                        VALUES (2, '" . pSQL($listName) . "', '" . pSQL($listDesc) . "', '" . pSQL($rule) . "', NOW(), NOW());");
                    }
                }
            }
            if (!isTable(SC_DB_PREFIX . 'extension_export_customer_mapping'))
            {
                $query = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . SC_DB_PREFIX . 'extension_export_customer_mapping` (
                          `id_extension_export_customer_mapping` int(10) unsigned NOT NULL AUTO_INCREMENT,
                          `name` varchar(128) NOT NULL,
                          `separator` int(11) NOT NULL DEFAULT "1",
                          `fields` text,
                          `export_format` varchar(20) NOT NULL,
                          `format_properties` text,
                          `date_add` datetime DEFAULT NULL,
                          `date_upd` datetime DEFAULT NULL,
                          PRIMARY KEY (`id_extension_export_customer_mapping`)
                        ) ENGINE='.$mysqlEngine.' DEFAULT CHARSET=utf8;';
                if(Db::getInstance()->execute($query))
                {
                    if (isTable('storecommander_customer_mappingg'))
                    {
                        $sql = 'INSERT INTO `' . _DB_PREFIX_ . SC_DB_PREFIX . 'extension_export_customer_mapping` 
                                    (`name`, `separator`, `fields`, `export_format`, `format_properties`, `date_add`, `date_upd`)
                                SELECT `name`, `separator`, `fields`, `export_format`, `format_properties`, `date_add`, `date_upd` 
                                    FROM `' . _DB_PREFIX_ . 'storecommander_customer_mapping`';
                        Db::getInstance()->execute($sql);
                    }
                    else
                    {
                        switch(Language::getIsoById($sc_agent->id_lang))
                        {
                            case 'fr':
                                $exportName = 'Nom & Email';
                                break;
                            case 'es':
                                $exportName = 'Nombre & Email';
                                break;
                            default:
                                $exportName = 'Name & Email';
                        }
                        Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . SC_DB_PREFIX . "extension_export_customer_mapping` (`id_extension_export_customer_mapping`, `name`, `fields`, `export_format`, `format_properties`, `date_add`, `date_upd`) 
                                                        VALUES(1, '".pSQL($exportName)."', 'customerFields|Gender__customerFields|First_name__customerFields|Last_name__customerFields|Email', 'CSV', 'delimitor::tab@@display_header::1', NOW(), NOW());"
                        );

                        Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . SC_DB_PREFIX . "extension_export_customer_mapping` (`id_extension_export_customer_mapping`, `name`, `fields`, `export_format`, `format_properties`, `date_add`, `date_upd`) 
                                                        VALUES(2, 'Email', 'customerFields|Email', 'CSV', 'delimitor::;@@display_header::1', NOW(), NOW());"
                        );
                    }
                }
            }
            if (!isTable(SC_DB_PREFIX . 'extension_export_customer'))
            {
                $query = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . SC_DB_PREFIX . 'extension_export_customer` (
                          `id_extension_export_customer` int(10) unsigned NOT NULL AUTO_INCREMENT,
                          `id_extension_export_customer_filter` int(11) DEFAULT NULL,
                          `id_extension_export_customer_mapping` int(11) DEFAULT NULL,
                          `id_lang` int(11) DEFAULT NULL,
                          `filename` varchar(255) NOT NULL,
                          `token` varchar(255) NOT NULL,
                          `date_last_export` datetime DEFAULT NULL,
                          `date_add` datetime DEFAULT NULL,
                          `date_upd` datetime DEFAULT NULL,
                          PRIMARY KEY (`id_extension_export_customer`)
                        ) ENGINE='.$mysqlEngine.' DEFAULT CHARSET=utf8;';
                if(Db::getInstance()->execute($query))
                {
                    $id_extension_export_customer_filter = 1;
                    $id_extension_export_customer_mapping = 1;
                    $filename = 'export_customer';
                    $oldCronConfig = Configuration::get('SC_CUSTOMERSEXPORT_CRON_CONFIG', null);
                    if($oldCronConfig)
                    {
                        $cronConf = json_decode($oldCronConfig,true);
                        if(isset($cronConf['cron_list'])) {
                            $id_extension_export_customer_filter = (int)$cronConf['cron_list'];
                        }
                        if(isset($cronConf['cron_export'])) {
                            $id_extension_export_customer_mapping = (int)$cronConf['cron_export'];
                        }
                        if(isset($cronConf['cron_file_name'])) {
                            $filename = $cronConf['cron_file_name'];
                        }
                    }
                    Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . SC_DB_PREFIX . "extension_export_customer` (`id_extension_export_customer_filter`, `id_extension_export_customer_mapping`, `id_lang`, `filename`, `token`, `date_add`, `date_upd`)
                                                    VALUES (".(int)$id_extension_export_customer_filter.", ".(int)$id_extension_export_customer_mapping.", ".(int)$sc_agent->id_lang.", '".pSQL($filename)."','".pSQL(generateToken())."', NOW(), NOW());");
                }
            }
        }

        if (isTable('storecom_service_configuration')){
            if (!isField('id_shop', 'storecom_service_configuration'))
            {
                $sql = 'ALTER TABLE `'._DB_PREFIX_."storecom_service_configuration`  ADD COLUMN `id_shop` tinyint(2) NOT NULL DEFAULT '0' AFTER `type`";
                Db::getInstance()->execute($sql);
                $sql = 'ALTER TABLE `'._DB_PREFIX_."storecom_service_configuration` DROP INDEX `name`, ADD UNIQUE INDEX `name` (`id_service`, `name`, `id_shop`) USING BTREE";
                Db::getInstance()->execute($sql);
            }
        }
        if(version_compare(_PS_VERSION_,'1.7.7.0', '<')){
            // Corrige un probleme d'index unique dans Prestashop Table ps_product
            $sql = 'SHOW INDEX FROM '._DB_PREFIX_."product WHERE column_name = 'reference'";
            $res = Db::getInstance()->ExecuteS($sql);
            if (count($res) === 0)
            {
                $sql = 'ALTER TABLE `'._DB_PREFIX_.'product` ADD INDEX `reference_idx` (  `reference` )';
                Db::getInstance()->Execute($sql);
            }
        }
    }
