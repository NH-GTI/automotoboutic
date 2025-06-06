<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

    error_reporting(E_ERROR);
    @ini_set('display_errors', 'on');

    restore_error_handler();
    restore_exception_handler();

    if (!isset($CRON))
    {
        $CRON = 0;
    }

    $action = Tools::getValue('action');
    $id_lang = (int) Tools::getValue('id_lang');
    $mapping = Tools::getValue('mapping', '');
    $create_categories = (int) Tools::getValue('create_categories', -1);

    if (SCAS)
    {
        $stock_manager = StockManagerFactory::getManager();
    }

    if (version_compare(_PS_VERSION_, '1.5.0.0', '<'))
    {
        include_once SC_PS_PATH_DIR.'images.inc.php';
    }

    include_once SC_DIR.'lib/php/parsecsv.lib.php';
    require_once SC_DIR.'lib/cat/win-import/cat_win-import_tools.php';
    require_once SC_DIR.'lib/cus/win-import/cus_win-import_tools.php';

    switch ($action){
        case 'check_data':
            $files = array_diff(scandir(SC_CSV_IMPORT_DIR.'customers/'), array_merge(['.', '..', 'index.php', '.htaccess', SC_CSV_IMPORT_CONF]));
            readCusImportConfigXML($files);
            $filename = Tools::getValue('filename', 0);
            if ($filename === 0)
            {
                exit(_l('You have to select a file and a mapping.'));
            }
            if (array_key_exists($filename, $importConfig))
            {
                $config = $importConfig[$filename];
            }
            $DATAFILE = file_get_contents(SC_CSV_IMPORT_DIR.'customers/'.$filename);
            $DATA = array_filter(preg_split("/(?:\r\n|\r|\n)/", $DATAFILE));
            if ($importConfig[$filename]['fieldsep'] == 'dcomma')
            {
                $importConfig[$filename]['fieldsep'] = ';';
            }
            if ($importConfig[$filename]['fieldsep'] == 'dcommamac')
            {
                $importConfig[$filename]['fieldsep'] = ';';
            }
            if ($importConfig[$filename]['firstlinecontent'] != '')
            {
                $firstLineData = explode($importConfig[$filename]['fieldsep'], $importConfig[$filename]['firstlinecontent']);
                $FIRST_CONTENT_LINE = 0;
            }
            else
            {
                $firstLineData = explode($importConfig[$filename]['fieldsep'], $DATA[0]);
                $FIRST_CONTENT_LINE = 1;
            }
            $mappingDataArray = explode(';', $mapping);
            $mappingData = ['CSVArray' => [], 'DBArray' => [], 'CSV2DB' => [], 'CSV2DBOptions' => [], 'CSV2DBOptionsMerged' => []];
            foreach ($mappingDataArray as $val)
            {
                if ($val != '')
                {
                    $tmp = explode(',', $val);
                    $tmp2 = $tmp[0];
                    escapeCharForPS($tmp2);
                    $mappingData['DBArray'][] = $tmp[1];
                }
            }
            $required_object_fields_to_check = ['lastname', 'firstname', 'email', 'passwd'];
            $db_field_keys = array_flip($mappingData['DBArray']);
            $key_identificatior = $db_field_keys[$importConfig[$filename]['idby']]; ## mail ou id_customer
            $errors = [];
            for ($current_line = $FIRST_CONTENT_LINE; (($current_line <= (count($DATA) - 1)) && $line = parseCSVLine($importConfig[$filename]['fieldsep'], $DATA[$current_line])); ++$current_line)
            {
                $line = array_map('cleanQuotes', $line);
                $customer = new Customer();
                $identificator = $line[$key_identificatior];
                foreach ($line as $key => $value)
                {
                    if (array_key_exists($key, $mappingData['DBArray']))
                    {
                        $db_field = $mappingData['DBArray'][$key];
                        if (in_array($db_field, $required_object_fields_to_check))
                        {
                            $customer_validation = $customer->validateField($db_field, $value, null, [], true);
                            if ($customer_validation !== true)
                            {
                                $errors[$identificator][] = $customer_validation;
                            }
                        }
                    }
                }
            }
            echo '<div id="outputResult" style="height:100%;overflow:auto;">
            <div style="width: 100%;box-sizing: border-box;height: 100%;line-height: 20px;">';
            if (!empty($errors))
            {
                $error_content = '';
                foreach ($errors as $identificator => $err_list)
                {
                    $error_content .= $identificator.';'.implode('-', $err_list)."\n";
                }
                echo '<i style="width:10px;height:10px;background: red;display: block;border-radius: 11px;margin: 5px 5px 0 0;float: left;line-height: 43px;"></i>'._l('There are some errors:').' <button onClick="$(\'#check_data_result\').select();return false;">'._l('Select all').'</button>
                <br>
                <br>
                <textarea id="check_data_result" style="width: 100%;box-sizing: border-box;height: calc(100% - 60px);resize: none;">'.$error_content.'</textarea>
                </div>';
            }
            else
            {
                echo '<i style="width:10px;height:10px;background:#2dd83a;display: block;border-radius: 11px;margin: 5px 5px 0 0;float: left;line-height: 43px;"></i>'._l('No error').'</div>';
            }
            echo '</div>
            </div>';
            break;
        case 'conf_delete':
            $imp_opt_files = Tools::getValue('imp_opt_files', '');
            if ($imp_opt_files == '')
            {
                exit(_l('You should mark at least one file to delete'));
            }
            $imp_opt_file_array = preg_split('/;/', $imp_opt_files);
            foreach ($imp_opt_file_array as $imp_opt_file)
            {
                if ($imp_opt_file != '')
                {
                    if (@unlink(SC_CSV_IMPORT_DIR.'customers/'.$imp_opt_file))
                    {
                        echo $imp_opt_file.' '._l('deleted')."\n";
                    }
                    else
                    {
                        echo _l('Unable to delete this file, please check write permissions:').' '.$imp_opt_file."\n";
                    }
                }
            }
            break;
        case 'mapping_load':
            echo loadMappingCus(Tools::getValue('filename', ''));
            break;
        case 'mapping_delete':
            $filename = str_replace('.map.xml', '', Tools::getValue('filename'));
            @unlink(SC_CSV_IMPORT_DIR.'customers/'.$filename.'.map.xml');
            break;
        case 'mapping_saveas':
            $filename = str_replace('.map.xml', '', Tools::getValue('filename'));
            @unlink(SC_CSV_IMPORT_DIR.'customers/'.$filename.'.map.xml');
            $mapping = preg_split('/;/', $mapping);
            $content = '<mapping><id_lang>'.(int) $sc_agent->id_lang.'</id_lang>';
            foreach ($mapping as $map)
            {
                $val = preg_split('/,/', $map);
                if (count($val) == 3)
                {
                    $content .= '<map>';
                    $content .= '<csvname><![CDATA['.$val[0].']]></csvname>';
                    $content .= '<dbname><![CDATA['.$val[1].']]></dbname>';
                    $content .= '<options><![CDATA['.$val[2].']]></options>';
                    $content .= '</map>';
                }
            }
            $content .= '</mapping>';
            file_put_contents(SC_CSV_IMPORT_DIR.'customers/'.$filename.'.map.xml', $content);
            echo _l('Data saved!');
            break;
        case 'mapping_process':
            echo '<div id="outputResult" style="height:100%;overflow:auto;">';
            if (SC_BETA)
            {
                $time_start = microtime(true);
            }
            checkDB();
            $scdebug = false;
            global $switchObject; // variable for custom import fields check
            $switchObject = '';
            global $TODO; // actions
            $TODO = [];
            global $id_customer;
            $id_customer = 0;
            $warehousesArray = [];
            $productsStockAdvancedTypeArray = [];
            $addressFields = ['address_title', 'address_country', 'address_state', 'address_company', 'address_lastname', 'address_firstname', 'address_1', 'address_2', 'address_postcode', 'address_city', 'address_other', 'address_phone', 'address_vat_number', 'address_phonemobile'];

            $defaultLanguageId = (int) Configuration::get('PS_LANG_DEFAULT');
            $defaultLanguage = new Language($defaultLanguageId);
            $getIDlangByISO = [];
            $id_lang_sc = (int) Tools::getValue('id_lang_sc');
            foreach ($languages as $lang)
            {
                $getIDlangByISO[$lang['iso_code']] = $lang['id_lang'];
            }

            $files = array_diff(scandir(SC_CSV_IMPORT_DIR.'customers/'), array_merge(['.', '..', 'index.php', '.htaccess', SC_CSV_IMPORT_CONF]));
            readCusImportConfigXML($files);
            $filename = Tools::getValue('filename', 0);

            if ($create_categories <= 0 && isset($importConfig[$filename]['createcategories']))
            {
                $create_categories = (int) $importConfig[$filename]['createcategories'];
            }
            $importlimit = (int) Tools::getValue('importlimit', 0);
            $importlimit = ($importlimit > 0 ? $importlimit : (int) $importConfig[$filename]['importlimit']);
            if ($importConfig[$filename]['firstlinecontent'] != '')
            {
                --$importlimit;
            }
            if ($CRON)
            {
                $mapping = loadMappingCus($importConfig[$filename]['mapping']);
            }
            if ($filename === 0 || $mapping == '')
            {
                exit(_l('You have to select a file and a mapping.'));
            }
            $mappingDataArray = explode(';', $mapping);
            $mappingData = ['CSVArray' => [], 'DBArray' => [], 'CSV2DB' => [], 'CSV2DBOptions' => [], 'CSV2DBOptionsMerged' => []];
            foreach ($mappingDataArray as $val)
            {
                if ($val != '')
                {
                    $tmp = explode(',', $val);
                    $tmp2 = $tmp[0];
                    escapeCharForPS($tmp2);
                    $mappingData['CSVArray'][] = $tmp2;
                    $mappingData['DBArray'][] = $tmp[1];
                    $mappingData['CSV2DB'][$tmp[0]] = $tmp[1];
                    $mappingData['CSV2DBOptions'][$tmp[0]] = $tmp[2];
                    $mappingData['CSV2DBOptionsMerged'][$tmp[0]] = $tmp[1].'_'.$tmp[2];
                }
            }

            // check mapping
            switch ($importConfig[$filename]['idby']){
                case 'idcustomer':
                    if (!sc_in_array('id_customer', $mappingData['DBArray'], 'cusWinImportProcess_DBArray'))
                    {
                        exit(_l('Wrong mapping, mapping should contain the id_customer field'));
                    }
                    break;
                case 'email':
                    if (!sc_in_array('email', $mappingData['DBArray'], 'cusWinImportProcess_DBArray'))
                    {
                        exit(_l('Wrong mapping, mapping should contain the email field'));
                    }
                    break;
                case 'idcustomeradresse':
                    if (!sc_in_array('id_customer', $mappingData['DBArray'], 'cusWinImportProcess_DBArray') && !sc_in_array('address_title', $mappingData['DBArray'], 'cusWinImportProcess_DBArray'))
                    {
                        exit(_l('Wrong mapping, mapping should contain the id_customer and the address title fields'));
                    }
                    elseif (!sc_in_array('id_customer', $mappingData['DBArray'], 'cusWinImportProcess_DBArray'))
                    {
                        exit(_l('Wrong mapping, mapping should contain the id_customer field'));
                    }
                    elseif (!sc_in_array('address_title', $mappingData['DBArray'], 'cusWinImportProcess_DBArray'))
                    {
                        exit(_l('Wrong mapping, mapping should contain the address title field'));
                    }
                    break;
                case 'emailadresse':
                    if (!sc_in_array('email', $mappingData['DBArray'], 'cusWinImportProcess_DBArray') && !sc_in_array('address_title', $mappingData['DBArray'], 'cusWinImportProcess_DBArray'))
                    {
                        exit(_l('Wrong mapping, mapping should contain the email and the address title fields'));
                    }
                    elseif (!sc_in_array('email', $mappingData['DBArray'], 'cusWinImportProcess_DBArray'))
                    {
                        exit(_l('Wrong mapping, mapping should contain the email field'));
                    }
                    elseif (!sc_in_array('address_title', $mappingData['DBArray'], 'cusWinImportProcess_DBArray'))
                    {
                        exit(_l('Wrong mapping, mapping should contain the address title field'));
                    }
                    break;
                case 'idcustomeridadresse':
                    if (!sc_in_array('id_customer', $mappingData['DBArray'], 'cusWinImportProcess_DBArray') && !sc_in_array('id_address', $mappingData['DBArray'], 'cusWinImportProcess_DBArray'))
                    {
                        exit(_l('Wrong mapping, mapping should contain the id_customer and the id_address fields'));
                    }
                    elseif (!sc_in_array('id_customer', $mappingData['DBArray'], 'cusWinImportProcess_DBArray'))
                    {
                        exit(_l('Wrong mapping, mapping should contain the id_customer field'));
                    }
                    elseif (!sc_in_array('id_address', $mappingData['DBArray'], 'cusWinImportProcess_DBArray'))
                    {
                        exit(_l('Wrong mapping, mapping should contain the id_address field'));
                    }
                    break;
                case 'emailidadresse':
                    if (!sc_in_array('email', $mappingData['DBArray'], 'cusWinImportProcess_DBArray') && !sc_in_array('id_address', $mappingData['DBArray'], 'cusWinImportProcess_DBArray'))
                    {
                        exit(_l('Wrong mapping, mapping should contain the email and the id_address fields'));
                    }
                    elseif (!sc_in_array('email', $mappingData['DBArray'], 'cusWinImportProcess_DBArray'))
                    {
                        exit(_l('Wrong mapping, mapping should contain the email field'));
                    }
                    elseif (!sc_in_array('id_address', $mappingData['DBArray'], 'cusWinImportProcess_DBArray'))
                    {
                        exit(_l('Wrong mapping, mapping should contain the id_address field'));
                    }
                    break;
            }

            // create TODO file
            if (substr($filename, strlen($filename) - 9, 9) == '.TODO.csv' && !file_exists(SC_CSV_IMPORT_DIR.'customers/'.$filename))
            {
                exit(_l('The TODO file has been deleted, please select the original CSV file.'));
            }
            if (substr($filename, strlen($filename) - 9, 9) != '.TODO.csv')
            {
                $TODOfilename = substr($filename, 0, -4).'.TODO.csv';
                if (!file_exists(SC_CSV_IMPORT_DIR.'customers/'.$TODOfilename))
                {
                    copy(SC_CSV_IMPORT_DIR.'customers/'.$filename, SC_CSV_IMPORT_DIR.'customers/'.$TODOfilename);
                    foreach ($importConfig[$filename] as $k => $v)
                    {
                        $importConfig[$TODOfilename][$k] = $v;
                        if ($k == 'name')
                        {
                            $importConfig[$TODOfilename][$k] = $TODOfilename;
                        }
                    }
                    writeCusImportConfigXML();
                }
            }
            else
            {
                $TODOfilename = $filename;
            }
            $needSaveTODO = false;

            // Get Other Informations
            $all_genders = [];
            if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
            {
                $sql = 'SELECT id_gender, name
                    FROM '._DB_PREFIX_."gender_lang
                        WHERE id_lang = '".(int) $id_lang_sc."'
                    ORDER BY name";
                $res = Db::getInstance()->executeS($sql);
                foreach ($res as $re)
                {
                    $all_genders[$re['id_gender']] = $re['id_gender'];
                    $all_genders[mb_strtolower($re['name'], ($importConfig[$TODOfilename]['utf8'] ? 'UTF-8' : mb_internal_encoding()))] = $re['id_gender'];
                }

                $id_lang_en = Language::getIdByIso('en');
                if ($id_lang_en != $id_lang_sc)
                {
                    $sql = 'SELECT id_gender, name
                    FROM '._DB_PREFIX_."gender_lang
                        WHERE id_lang = '".(int) $id_lang_en."'
                    ORDER BY name";
                    $res = Db::getInstance()->executeS($sql);
                    foreach ($res as $re)
                    {
                        $all_genders[$re['id_gender']] = $re['id_gender'];
                        $all_genders[mb_strtolower($re['name'], ($importConfig[$TODOfilename]['utf8'] ? 'UTF-8' : mb_internal_encoding()))] = $re['id_gender'];
                    }
                }
                if (version_compare(_PS_VERSION_, '1.6.0.0', '>='))
                {
                    $all_genders[strtolower(_l('Miss'))] = 2;
                    $all_genders[strtolower(('Miss'))] = 2;
                }
            }
            else
            {
                $all_genders[0] = 0;
                $all_genders[strtolower(_l('Unk.'))] = 0;
                $all_genders[strtolower('Unk.')] = 0;
                $all_genders[1] = 1;
                $all_genders[strtolower(_l('Mr.'))] = 1;
                $all_genders[strtolower(('Mr.'))] = 1;
                $all_genders[2] = 2;
                $all_genders[strtolower(_l('Ms.'))] = 2;
                $all_genders[strtolower(('Ms.'))] = 2;
                $all_genders[3] = 3;
                $all_genders[strtolower(_l('Miss'))] = 3;
                $all_genders[strtolower(('Miss'))] = 3;
                $all_genders[4] = 4;
                $all_genders[9] = 9;
            }

                $all_groups = [];
                $sql = 'SELECT id_group, name
                    FROM '._DB_PREFIX_."group_lang
                        WHERE id_lang = '".(int) $id_lang_sc."'
                    ORDER BY name";
                $res = Db::getInstance()->executeS($sql);
                foreach ($res as $re)
                {
                    $all_groups[$re['id_group']] = $re['id_group'];
                    $all_groups[mb_strtolower($re['name'], ($importConfig[$TODOfilename]['utf8'] ? 'UTF-8' : mb_internal_encoding()))] = $re['id_group'];
                }

            $all_countries = [];
            $sql = 'SELECT id_country, name
            FROM '._DB_PREFIX_."country_lang
                WHERE id_lang = '".(int) $id_lang_sc."'
            ORDER BY name";
            $res = Db::getInstance()->executeS($sql);
            foreach ($res as $re)
            {
                $all_countries[$re['id_country']] = $re['id_country'];
                $all_countries[mb_strtolower($re['name'], ($importConfig[$TODOfilename]['utf8'] ? 'UTF-8' : mb_internal_encoding()))] = $re['id_country'];
            }
            $all_states = [];
            $sql = 'SELECT id_state, name
            FROM '._DB_PREFIX_.'state
            ORDER BY name';
            $res = Db::getInstance()->executeS($sql);
            foreach ($res as $re)
            {
                $all_states[$re['id_state']] = $re['id_state'];
                $all_states[mb_strtolower($re['name'], ($importConfig[$TODOfilename]['utf8'] ? 'UTF-8' : mb_internal_encoding()))] = $re['id_state'];
            }

            // open csv filename
            if ($importConfig[$TODOfilename]['fieldsep'] == 'dcomma')
            {
                $importConfig[$TODOfilename]['fieldsep'] = ';';
            }
            if ($importConfig[$TODOfilename]['fieldsep'] == 'dcommamac')
            {
                $importConfig[$TODOfilename]['fieldsep'] = ';';
            }
            // get first line
            $DATAFILE = file_get_contents(SC_CSV_IMPORT_DIR.'customers/'.$TODOfilename);
            $DATA = preg_split("/(?:\r\n|\r|\n)/", $DATAFILE);
            if ($importConfig[$TODOfilename]['firstlinecontent'] != '')
            {
                $firstLineData = explode($importConfig[$TODOfilename]['fieldsep'], $importConfig[$TODOfilename]['firstlinecontent']);
                $FIRST_CONTENT_LINE = 0;
            }
            else
            {
                $firstLineData = explode($importConfig[$TODOfilename]['fieldsep'], $DATA[0]);
                $FIRST_CONTENT_LINE = 1;
            }
            if (count($firstLineData) != count(array_unique($firstLineData)))
            {
                exit(_l('Error : at least 2 columns have the same name in CSV file. You must use a unique name by column in the first line of your CSV file.'));
            }
            foreach ($firstLineData as $key => $val)
            {
                escapeCharForPS($firstLineData[$key]);
            }
            $firstLineData = array_map('cleanQuotes', $firstLineData);
            if ($importConfig[$TODOfilename]['utf8'])
            {
                utf8_encode_array($firstLineData);
            }

            // CHECK FILE VALIDITY
            if (count($mappingData['CSVArray']) > count($firstLineData))
            {
                exit(_l('Error in mapping: too much field to import').' (CSVArray:'.count($mappingData['CSVArray']).' - firstLineData:'.count($firstLineData).')');
            }
            foreach ($mappingData['CSVArray'] as $val)
            {
                if (!sc_in_array($val, $firstLineData, 'cusWinImportProcess_firstLineData'))
                {
                    exit(_l('Error in mapping: the fields are not in the CSV file')._l(':').$val);
                }
            }

            if (isset($errors) && $errors != '')
            {
                exit($errors.'<br/><br/>'._l('The process has been stopped before any modification in the database. You need to fix these errors first.'));
            }

            $stats = ['created' => 0, 'modified' => 0, 'skipped' => 0, 'group_created' => 0];
            $noWholesalepriceArray = [];
            $CSVDataStr = file_get_contents(SC_CSV_IMPORT_DIR.'customers/'.$TODOfilename);
            $CSVData = preg_split("/(?:\r\n|\r|\n)/", $CSVDataStr);
            $lastIdentifier = '';

            $id_shop_default = 1;
            if (SCMS)
            {
                $id_shop_default = [(int) Configuration::get('PS_SHOP_DEFAULT')];
            }
            $lastid_customer = 0;
            $id_shop = $id_shop_default;
            if (!empty($importConfig[$TODOfilename]['id_shop']))
            {
                $id_shop = (int) $importConfig[$TODOfilename]['id_shop'];
            }
            $customersWithTagUpdatedList = [];
            try
            {
                for ($current_line = $FIRST_CONTENT_LINE; ((($current_line <= (count($DATA) - 1)) && $line = parseCSVLine($importConfig[$TODOfilename]['fieldsep'], $DATA[$current_line])) && ($current_line <= $importlimit)); ++$current_line)
                {
                    if ($DATA[$current_line] == '' or $DATA[$current_line] === null)
                    {
                        continue;
                    }
                    $line = array_map('cleanQuotes', $line);
                    if ($scdebug)
                    {
                        echo 'line '.$current_line.': ';
                    }
                    $line[count($line) - 1] = rtrim($line[count($line) - 1]);
                    $TODO = [];
                    $TODOSHOP = [];
                    if ($importConfig[$TODOfilename]['utf8'] == 1)
                    {
                        utf8_encode_array($line);
                    }

                    switch ($importConfig[$TODOfilename]['idby']) {
                        case 'idcustomer':
                            $sql = 'SELECT id_customer,date_upd FROM '._DB_PREFIX_."customer WHERE id_customer='".(int) findCSVLineValue('id_customer')."' ".((SCMS) ? " AND id_shop='".(int) $id_shop."'" : '');
                            break;
                        case 'email':
                            $sql = 'SELECT id_customer,date_upd FROM '._DB_PREFIX_."customer WHERE email='".pSQL(findCSVLineValue('email'))."' ".((SCMS) ? " AND id_shop='".(int) $id_shop."'" : '');
                            break;
                        case 'idcustomeradresse':
                            $sql = 'SELECT id_customer,date_upd FROM '._DB_PREFIX_."customer WHERE id_customer='".(int) findCSVLineValue('id_customer')."' ".((SCMS) ? " AND id_shop='".(int) $id_shop."'" : '');
                            break;
                        case 'emailadresse':
                            $sql = 'SELECT id_customer,date_upd FROM '._DB_PREFIX_."customer WHERE email='".pSQL(findCSVLineValue('email'))."' ".((SCMS) ? " AND id_shop='".(int) $id_shop."'" : '');
                            break;
                        case 'idcustomeridadresse':
                            $sql = 'SELECT id_customer,date_upd FROM '._DB_PREFIX_."customer WHERE id_customer='".(int) findCSVLineValue('id_customer')."' ".((SCMS) ? " AND id_shop='".(int) $id_shop."'" : '');
                            break;
                        case 'emailidadresse':
                            $sql = 'SELECT id_customer,date_upd FROM '._DB_PREFIX_."customer WHERE email='".pSQL(findCSVLineValue('email'))."' ".((SCMS) ? " AND id_shop='".(int) $id_shop."'" : '');
                            break;
                    }
                    $res = Db::getInstance()->getRow($sql);
                    if (is_array($res) && count($res))
                    {
                        $id_customer = $res['id_customer'];
                    }
                    else
                    {
                        $id_customer = 0;
                    }

                    $id_address = 0;
                    if (!empty($id_customer))
                    {
                        $sql_address = '';
                        switch ($importConfig[$TODOfilename]['idby']) {
                            case 'idcustomeradresse':
                                $sql_address = 'SELECT id_address,date_upd FROM '._DB_PREFIX_.'address WHERE id_customer='.(int) $id_customer." AND LOWER(alias)='".pSQL(mb_strtolower(findCSVLineValue('address_title'), ($importConfig[$TODOfilename]['utf8'] ? 'UTF-8' : mb_internal_encoding())))."'";
                                break;
                            case 'emailadresse':
                                $sql_address = 'SELECT id_address,date_upd FROM '._DB_PREFIX_.'address WHERE id_customer='.(int) $id_customer." AND LOWER(alias)='".pSQL(mb_strtolower(findCSVLineValue('address_title'), ($importConfig[$TODOfilename]['utf8'] ? 'UTF-8' : mb_internal_encoding())))."'";
                                break;
                            case 'idcustomeridadresse':
                                $sql_address = 'SELECT id_address,date_upd FROM '._DB_PREFIX_.'address WHERE id_customer='.(int) $id_customer." AND id_address='".(int) findCSVLineValue('id_address')."'";
                                break;
                            case 'emailidadresse':
                                $sql_address = 'SELECT id_address,date_upd FROM '._DB_PREFIX_.'address WHERE id_customer='.(int) $id_customer." AND id_address='".(int) findCSVLineValue('id_address')."'";
                                break;
                        }
                        if (!empty($sql_address))
                        {
                            $res_address = Db::getInstance()->getRow($sql_address);
                            if (is_array($res_address) && count($res_address))
                            {
                                $id_address = $res_address['id_address'];
                            }
                        }
                    }

                    if ($scdebug)
                    {
                        echo findCSVLineValue('email').' : '.$id_customer.'<br/>';
                    }
                    if ($scdebug)
                    {
                        echo 'a';
                    }

                    if ($CRON && isset($CRON_OLDERTHAN) && $CRON_OLDERTHAN > 0)
                    {
                        $date_upd = strtotime($res['date_upd']);
                        $nowres = Db::getInstance()->getRow('SELECT UNIX_TIMESTAMP() AS ut');
                        $now = ($nowres ? $nowres['ut'] : 0);
                        if (($date_upd > ($now - ((int) $CRON_OLDERTHAN * 60))))
                        { // if not a recent updated object...
                            ++$stats['skipped'];
                            ++$importlimit; // on suppose que tous les éléments ont été créés en BDD : le cron ne sert que pour mettre à jour stock et/ou prix
                            continue;
                        }
                    }

                    if ($importConfig[$TODOfilename]['iffoundindb'] == 'skip' && $id_customer)
                    {
                        ++$stats['skipped'];
                        if (_s('CAT_IMPORT_IGNORED_LINES') == 1)
                        {
                            unset($CSVData[$current_line]);
                            $needSaveTODO = true;
                        }
                        // ne pas augmenter la limite totale car les prochaines lignes n'ont pas été analysées et donc des éléments peuvent manquer.
                        //$importlimit++;
                        continue;
                    }
                    elseif ($importConfig[$TODOfilename]['iffoundindb'] == 'replace' && $id_customer)
                    {
                        //in_array($importConfig[$TODOfilename]['idby'],array('idcustomeradresse','emailadresse','idcustomeridadresse','emailidadresse')) ||
                        if (hasAddress())
                        {
                            if (!empty($id_address))
                            {
                                $newaddress = new Address($id_address);
                            }
                            else
                            {
                                $newaddress = new Address();
                                $newaddress->id_customer = $id_customer;
                                $newaddress->active = 1;
                                $newaddress->alias = _l('My address');
                            }
                        }

                        if (SCMS)
                        {
                            $newcustomer = new Customer($id_customer, null, $id_shop);
                        }
                        else
                        {
                            $newcustomer = new Customer($id_customer);
                        }
                    }
                    elseif ($importConfig[$TODOfilename]['iffoundindb'] == 'replaceonly')
                    {
                        $skip = false;

                        if (!empty($id_customer))
                        {
                            if (hasAddress())
                            {
                                if (!empty($id_address))
                                {
                                    $newaddress = new Address($id_address);
                                }
                                else
                                {
                                    $skip = true;
                                }
                            }
                        }
                        else
                        {
                            $skip = true;
                        }

                        if ($skip)
                        {
                            ++$stats['skipped'];
                            if (_s('CAT_IMPORT_IGNORED_LINES') == 1)
                            {
                                unset($CSVData[$current_line]);
                                $needSaveTODO = true;
                            }
                            // ne pas augmenter la limite totale car les prochaines lignes n'ont pas été analysées et donc des éléments peuvent manquer.
                            //$importlimit++;
                            continue;
                        }
                        else
                        {
                            if (SCMS)
                            {
                                $newcustomer = new Customer($id_customer, null, $id_shop);
                            }
                            else
                            {
                                $newcustomer = new Customer($id_customer);
                            }
                        }
                    }
                    else
                    {
                        $email = findCSVLineValue('email');
                        if (empty($email))
                        {
                            exit(_l('Email can\'t be empty to create a customer: line n°').' '.$current_line);
                        }
                        // create new customer with default values
                        $newcustomer = new Customer();
                        $newcustomer->active = 0;
                        $newcustomer->firstname = _l('Firstname');
                        $newcustomer->lastname = _l('Lastname');
                        $newcustomer->email = $email;
                        $newcustomer->passwd = (version_compare(_PS_VERSION_, '8.0.0', '>=') ? Tools::hash('password') : Tools::encrypt('password'));
                        if (SCMS)
                        {
                            $newcustomer->id_shop = $id_shop;
                        }

                        if (hasAddress())
                        {
                            $newaddress = new Address();
                            $newaddress->active = 1;
                            $newaddress->alias = _l('My address');
                        }
                    }

                    if ($scdebug)
                    {
                        echo 'b';
                    }
                    foreach ($line as $key => $value)
                    {
                        $value = trim($value);
                        $GLOBALS['import_value'] = $value;
                        if ($scdebug && !sc_array_key_exists($key, $firstLineData))
                        {
                            echo 'ERR'.$key.'x'.$current_line.'x'.join(';', $line).'xxx'.join(';', array_keys($firstLineData)).'<br/>';
                        }
                        if (sc_array_key_exists($key, $firstLineData) && sc_in_array($firstLineData[$key], $mappingData['CSVArray'], 'cusWinImportProcess_CSVArray'))
                        {
                            if ($scdebug)
                            {
                                echo 'c';
                            }
                            //@$id_lang=(int) $getIDlangByISO[$mappingData['CSV2DBOptions'][$firstLineData[$key]]];
                            $switchObject = $mappingData['CSV2DB'][$firstLineData[$key]];
                            switch ($switchObject) {
                                // CUSTOMER
                                case 'id_customer':
                                    if (_s('CUS_WIN_IMPORT_FORCE_ID') == 2)
                                    {
                                        $newcustomer->id = (int) $value;
                                        $newcustomer->force_id = true;
                                    }
                                    break;
                                case 'company':
                                    $newcustomer->company = ($value);
                                    break;
                                case 'siret':
                                    $newcustomer->siret = ($value);
                                    break;
                                case 'ape':
                                    $newcustomer->ape = ($value);
                                    break;
                                case 'firstname':
                                    $newcustomer->firstname = ($value);
                                    break;
                                case 'lastname':
                                    $newcustomer->lastname = ($value);
                                    break;
                                case 'email':
                                    $newcustomer->email = ($value);
                                    break;
                                case 'passwd':
                                    $newcustomer->passwd = (version_compare(_PS_VERSION_, '8.0.0', '>=') ? Tools::hash($value) : Tools::encrypt($value));
                                    break;
                                case 'birthday':
                                    $newcustomer->birthday = (importConv2Date($value));
                                    break;
                                case 'date_add':
                                    $newcustomer->date_add = (importConv2Date($value));
                                    break;
                                case 'newsletter':
                                    $newcustomer->newsletter = (int) getBoolean($value);
                                    break;
                                case 'optin':
                                    $newcustomer->optin = (int) getBoolean($value);
                                    break;
                                case 'website':
                                    $newcustomer->website = ($value);
                                    break;
                                case 'active':
                                    $newcustomer->active = (int) getBoolean($value);
                                    break;
                                case 'note':
                                    $newcustomer->note = ($value);
                                    break;
                                case 'id_gender':
                                    $gender = mb_strtolower($value, ($importConfig[$TODOfilename]['utf8'] ? 'UTF-8' : mb_internal_encoding()));
                                    if (!empty($all_genders[$gender]))
                                    {
                                        $newcustomer->id_gender = (int) $all_genders[$gender];
                                    }
                                    break;
                                case 'id_default_group':
                                    $group = mb_strtolower($value, ($importConfig[$TODOfilename]['utf8'] ? 'UTF-8' : mb_internal_encoding()));
                                    if (!empty($all_groups[$group]))
                                    {
                                        $newcustomer->id_default_group = (int) $all_groups[$group];
                                        $newcustomer->addGroups([$newcustomer->id_default_group]);
                                    }
                                    else
                                    {
                                        if (!is_numeric($value))
                                        {
                                            $newGroup = new Group();
                                            foreach ($languages as $lang)
                                            {
                                                $newGroup->name[$lang['id_lang']] = $value;
                                            }
                                            $newGroup->price_display_method = 0;
                                            $newGroup->save();

                                            $all_groups[$group] = $newGroup->id;

                                            $newcustomer->id_default_group = (int) $all_groups[$group];
                                            $newcustomer->addGroups([$newcustomer->id_default_group]);
                                            ++$stats['group_created'];
                                        }
                                    }
                                    break;
                                // ADDRESS
                                case 'address_title':
                                    $newaddress->alias = ($value);
                                    break;
                                case 'address_country':
                                    $country = mb_strtolower($value, ($importConfig[$TODOfilename]['utf8'] ? 'UTF-8' : mb_internal_encoding()));
                                    if (sc_array_key_exists($country, $all_countries))
                                    {
                                        $newaddress->id_country = (int) $all_countries[$country];
                                    }
                                    break;
                                case 'address_state':
                                    $state = mb_strtolower($value, ($importConfig[$TODOfilename]['utf8'] ? 'UTF-8' : mb_internal_encoding()));
                                    if (!empty($all_states[$state]))
                                    {
                                        $newaddress->id_state = (int) $all_states[$state];
                                    }
                                    break;
                                case 'address_company':
                                    $newaddress->company = ($value);
                                    break;
                                case 'address_lastname':
                                    $newaddress->lastname = ($value);
                                    break;
                                case 'address_firstname':
                                    $newaddress->firstname = ($value);
                                    break;
                                case 'address_1':
                                    $newaddress->address1 = ($value);
                                    break;
                                case 'address_2':
                                    $newaddress->address2 = ($value);
                                    break;
                                case 'address_postcode':
                                    $newaddress->postcode = ($value);
                                    break;
                                case 'address_city':
                                    $newaddress->city = ($value);
                                    break;
                                case 'address_other':
                                    $newaddress->other = ($value);
                                    break;
                                case 'address_phone':
                                    $newaddress->phone = (str_replace(' ', '', $value));
                                    break;
                                case 'address_phonemobile':
                                    $newaddress->phone_mobile = (str_replace(' ', '', $value));
                                    break;
                                case 'address_vat_number':
                                    $newaddress->vat_number = ($value);
                                    break;
                                // ACTIONS
                                case 'ActionDeleteAllCustomers':
                                    if (getBoolean($value))
                                    {
                                        if (!empty($newcustomer->id))
                                        {
                                            $deleteMethod = ($mappingData['CSV2DBOptions']['delete'] === 'hardDelete') ? 'hard' : 'soft';

                                            if ($deleteMethod === 'hard')
                                            {
                                                $newcustomer->delete();
                                            }
                                            else
                                            {
                                                $newcustomer->deleted = 1;
                                                $newcustomer->save();
                                            }
                                        }
                                    }
                                    break;
                                case 'ActionDeleteAllAddresses':
                                    if (getBoolean($value))
                                    {
                                        if (!empty($newcustomer->id) && empty($newcustomer->deleted))
                                        {
                                            $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address 
                                                            WHERE id_customer = '.(int) $newcustomer->id;
                                            $all_addresses = Db::getInstance()->executeS($sql);
                                            foreach ($all_addresses as $address_id)
                                            {
                                                $address_id = $address_id['id_address'];
                                                $address = new Address((int) $address_id);
                                                $address->delete();
                                            }
                                        }
                                    }
                                    break;
                                case 'ActionRegenerateAllPasswords':
                                    if (getBoolean($value))
                                    {
                                        if (!empty($newcustomer->id) && empty($newcustomer->deleted))
                                        {
                                            $newcustomer->passwd = (version_compare(_PS_VERSION_, '8.0.0', '>=') ? Tools::hash(randomPassword()) : Tools::encrypt(randomPassword()));
                                            $newcustomer->save();
                                        }
                                    }
                                    break;
                                case 'ActionDissociateAllGroups':
                                    if (getBoolean($value))
                                    {
                                        $sql = 'DELETE FROM '._DB_PREFIX_.'customer_group WHERE id_customer = '.(int) $newcustomer->id;
                                        Db::getInstance()->execute($sql);
                                    }
                                    break;
                                //case 'date_add':$TODO[]="UPDATE "._DB_PREFIX_."product SET date_add='".psql($value)."' WHERE id_customer=ID_PRODUCT";break;
                                default:
                                    SC_Ext::readImportCustomerCSVConfigXML('importProcessCustomer');
                            }
                        }
                    }

                    if ($scdebug)
                    {
                        echo 'd';
                    }
                    $newcustomer->date_upd = date('Y-m-d H:i:s');

                    try {
                        $newcustomer->validateFields(true, true);
                    } catch (Exception $e) {
                        echo _l('%s with error', false, [_l('Customer')]);
                        echo '<br/><br/>Detail:<pre>'.$e->getMessage().'</pre>';
                        echo '<br/><br/>'._l('Download the corresponding TODO file to check and fix.<br/>You will then be able to start your import again.');
                        exit;
                    }

                    if ($newcustomer->force_id)
                    {
                        $cus_res = $newcustomer->add();
                        if ($cus_res)
                        {
                            ++$stats['created'];
                        }
                    }
                    else
                    {
                        $cus_res = $newcustomer->save();
                        if ($cus_res)
                        {
                            ++$stats['modified'];
                        }
                    }
                    if ($cus_res)
                    {
                        $lastid_customer = $newcustomer->id;
                        if ($scdebug)
                        {
                            echo 'e';
                        }

                        foreach ($TODO as $sql)
                        {
                            $sql = str_replace('ID_PRODUCT', $newcustomer->id, $sql);
                            Db::getInstance()->execute($sql);
                        }

                        // SAVE ADDRESS
                        if (hasAddress())
                        {
                            $newaddress->id_customer = $lastid_customer;
                            if (empty($newaddress->firstname))
                            {
                                $newaddress->firstname = $newcustomer->firstname;
                            }
                            if (empty($newaddress->lastname))
                            {
                                $newaddress->lastname = $newcustomer->lastname;
                            }
                            try
                            {
                                $newaddress->save();
                            }
                            catch (Exception $e)
                            {
                                echo _l('The customer has been imported but there is a problem with his address.').'<br/>';
                                echo _l('More information about the address')._l(':').'<br/><br/>';
                                echo '<b>'.$e->getMessage().'</b>';
                                echo '<br/><br/>'._l('Download the corresponding TODO file to check and fix.<br/>You will then be able to start your import again.');
                                exit;
                            }
                            catch (Throwable $e)
                            {
                                echo _l('The customer has been imported but there is a problem with his address.').'<br/>';
                                echo _l('More information about the address')._l(':').'<br/><br/>';
                                echo '<b>'.$e->getMessage().'</b>';
                                echo '<br/><br/>'._l('Download the corresponding TODO file to check and fix.<br/>You will then be able to start your import again.');
                                exit;
                            }
                        }

                        // ADD IN GROUPS
                        $name_groups = findCSVLineValue('groups');
                        if (sc_in_array('groups', $mappingData['DBArray'], 'cusWinImportProcess_DBArray') && !empty($name_groups))
                        {
                            $temp_groups = [];
                            $groups = explode($importConfig[$TODOfilename]['valuesep'], $name_groups);
                            foreach ($groups as $value)
                            {
                                $group = mb_strtolower($value, ($importConfig[$TODOfilename]['utf8'] ? 'UTF-8' : mb_internal_encoding()));
                                if (!empty($all_groups[$group]))
                                {
                                    $temp_groups[] = (int) $all_groups[$group];
                                }
                                else
                                {
                                    if (!is_numeric($value))
                                    {
                                        $newGroup = new Group();
                                        foreach ($languages as $lang)
                                        {
                                            $newGroup->name[$lang['id_lang']] = $value;
                                        }
                                        $newGroup->price_display_method = 0;
                                        $newGroup->save();

                                        $all_groups[$group] = $newGroup->id;

                                        $temp_groups[] = (int) $all_groups[$group];
                                        ++$stats['group_created'];
                                    }
                                }
                            }
                            if (!empty($temp_groups))
                            {
                                foreach ($temp_groups as $id_group)
                                {
                                    Db::getInstance()->execute('INSERT IGNORE INTO `'._DB_PREFIX_.'customer_group` (`id_customer`, `id_group`) VALUES ('.(int) $newcustomer->id.','.(int) $id_group.')');
                                }
                            }
                        }

                        unset($CSVData[$current_line]);
                        file_put_contents(SC_CSV_IMPORT_DIR.'customers/'.$TODOfilename, join("\n", $CSVData));
                        $needSaveTODO = false;
                    }

                    SC_Ext::readImportCustomerCSVConfigXML('importProcessCustomerAfter');
                }
            }
            catch (\PrestaShopException $e)
            {
                $error_line = '<strong>Fatal error: </strong>Uncaught PrestashopException : '.$e->getMessage().'('._l('Error on line ').' '.$current_line.')<br/>';
            }

            SC_Ext::readImportCustomerCSVConfigXML('importProcessAfterCreateAll');

            if ($needSaveTODO)
            {
                file_put_contents(SC_CSV_IMPORT_DIR.'customers/'.$TODOfilename, join("\n", $CSVData));
            }
            echo '<b id="process_ending">'._l('Stats:').'</b><br/>';
            $msg = _l('New customers:').' '.$stats['created'].'<br/>';
            $msg .= _l('Modified customers:').' '.$stats['modified'].'<br/>';
            $msg .= _l('Skipped lines:').' '.$stats['skipped'].'<br/>';
            if (!empty($stats['group_created']))
            {
                $msg .= _l('New groups:').' '.$stats['group_created'].'<br/>';
            }
            if (isset($error_line))
            {
                $msg .= '<br/>'.$error_line;
            }
            echo $msg.'<br/>';

            if ((count($CSVData) == 1) || (count($CSVData) == 2 && $CSVData[0] == join('', $CSVData)) || (filesize(SC_CSV_IMPORT_DIR.'customers/'.$TODOfilename) == 0))
            {
                @unlink(SC_CSV_IMPORT_DIR.'customers/'.$TODOfilename);
                echo _l('All customers have been imported. The TODO file is deleted.').'<br/><br/>';
                echo '<b>'._l('End of import process.').'</b><br/><br/>';
                echo '<b>'._l('You need to refresh the page, click here:').' <a target="_top" href="index.php">Go!</a></b><br/>';
                echo '<script>window.top.displayOptionsCus();window.top.stopAutoImportCus(true);</script>';
                $msg2 = 'All customers have been imported.';
            }
            else
            {
                echo '<b>'._l('There are still customers to be imported in the working file. It can mean errors you need to correct or lines which have been ignored on purpose. Once corrections have been made, click again on the import icon to proceed further.').'</b><br/><br/>';
                echo '<script>window.top.displayOptionsCus();window.top.prepareNextStepCus('.($stats['created'] + $stats['modified'] + $stats['skipped'] == 0 ? 0 : filesize(SC_CSV_IMPORT_DIR.'customers/'.$TODOfilename)).');</script>';
                $msg2 = 'Need fix and run import again.';
            }
            $msg3 = '';
            if ($CRON)
            {
                $msg3 .= _l('CRON task name')._l(':').' '.$CRON_NAME.'<br/>';
                $msg3 .= (isset($CRON_DELETETODO) && $CRON_DELETETODO ? $TODOfilename.' '._l('deleted').'<br/>' : '');
                $msg3 .= _l('Update customers older than').' '.$CRON_OLDERTHAN;
            }
            addToHistory('customer_import', 'import', '', '', '', '', 'Imported file: '.$TODOfilename.'<br/>'.$msg.$msg2.($msg3 != '' ? '<br/>'.$msg3 : ''), '');
            echo '</div>';
            break;
        }
