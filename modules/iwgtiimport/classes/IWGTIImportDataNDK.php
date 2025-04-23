<?php
/**
* IW 2021
*
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once 'IWGTIImportData.php';
require_once 'IWSTDIterator.php';

class IWGTIImportDataNDK extends IWGTIImportData
{
    /**
     * Opérations réalisées avant l'import
     * - Prendre à partir de NDK le code administrateur de chaque groupe de champs pour chaque produit dans l’ordre donnée par position
     * - Déterminer le fichier de données (standard ou spécifique)
     * @return void
     */
    public function beforeImport()
    {
        // NDK Config
        $erase_config_products = array();
        $this->config_items = array();
        $this->sheet_column_ID = array();
        $sql = 'SELECT f.id_ndk_customization_field, f.`type`, f.products, f.position, fl.admin_name, fl.name, f.filter_by, f.dynamic_influences, fl.complementary_name, f.influences
        FROM `' . _DB_PREFIX_ .'ndk_customization_field` f
        INNER JOIN `' . _DB_PREFIX_ .'ndk_customization_field_lang` fl ON fl.id_ndk_customization_field = f.id_ndk_customization_field AND fl.id_lang = ' . Context::getContext()->language->id . '
        ORDER BY f.products, f.open_status DESC, f.position ASC';
        if ($rows = Db::getInstance()->executeS($sql)) {
            $influences_items = array();
            foreach($rows as $row) {
                $id_product = (int)$row['products'];
                $admin_name = $row['admin_name'];   // feuille.colonne
                $split = explode('.', $admin_name);
                if (count($split) == 2) {    
                    $sheet = $split[0];
                    $column = $split[1];
                    // spécifique ou standard
                    $key_specific = 'SP-'.$id_product.'-%.'.$sheet.'%';
                    $key_standard = 'ST-%.'.$sheet.'%';
                    $specific  = $this->db->findHeader($key_specific);
                    $standard = $specific ? false : $this->db->findHeader($key_standard);
                    if ($specific == false && $standard == false) {
                        continue;
                    }
                    $input_name = $specific ? $specific['input_name'] : $standard['input_name'];
                    $input_name = explode('.', $input_name);
                    array_pop($input_name);
                    $input_name = implode('.', $input_name);
                    $this->reportfile = _PS_ROOT_DIR_._MODULE_DIR_.'iwgtiimport/log/'.date('YmdHis').'-'.$input_name.'-report.csv';
                    if (!in_array($id_product, $erase_config_products)) {
                        $erase_config_products[] = $id_product;
                    }    
                    $this->config_items[] = [
                        'id_ndk_customization_field' => $row['id_ndk_customization_field'],
                        'filter_by' => strlen($row['filter_by']) ? $row['filter_by'] : false,
                        'dynamic_influences' => strlen($row['dynamic_influences']) ? $row['dynamic_influences'] : false,
                        'complementary_name' => strlen($row['complementary_name']) ? $row['complementary_name'] : false,
                        'type' => (int)$row['type'],
                        'influences' => false,
                        'id_product' => $id_product,
                        'sheet' => $sheet,
                        'column' => $column,
                        'sheet_column' => $sheet.'.'.$column,
                        'header' => $specific ? $key_specific : $key_standard,
                        'additional' => false,
                    ];
                    $this->sheet_column_ID[$sheet.'.'.$column] = (int)$row['id_ndk_customization_field'];
                    if (strlen($row['influences'])) {
                        $influences_items[] = [
                            'id_ndk_customization_field' => $row['id_ndk_customization_field'],
                            'filter_by' => false,
                            'dynamic_influences' => false,
                            'complementary_name' => strlen($row['complementary_name']) ? $row['complementary_name'] : false,
                            'type' => 0,
                            'influences' => $row['influences'],
                            'id_product' => $id_product,
                            'sheet' => $sheet,
                            'column' => $column,
                            'sheet_column' => $sheet.'.'.$column,
                            'header' => $specific ? $key_specific : $key_standard,
                            'additional' => false,
                        ];
                    }
                }
            }
            // influences après import des données
            foreach ($influences_items as $item) {
                $this->config_items[] = $item;
            }
        }
        // Additional info
        $additional_informations = unserialize(Configuration::get('iwgtiimport_additional_info'));
        if ($additional_informations) {
            uasort($additional_informations, function($a,$b) {
                return ((int)$a['position'] <=> (int)$b['position']);
            });
            foreach($additional_informations as $ai) {
                // find ndk field
                if ($ndk_field = Db::getInstance()->getRow(
                    'SELECT * FROM '._DB_PREFIX_.'ndk_customization_field_lang l
                    INNER JOIN '._DB_PREFIX_.'ndk_customization_field f on f.id_ndk_customization_field = l.id_ndk_customization_field
                    WHERE f.products = "'. $ai['id_product'] .'" AND l.admin_name = "'. $ai['field_reference'].'" AND l.id_lang = '.Context::getContext()->language->id)) {
                    $id_product = (int)$ndk_field['products'];
                    $split = explode('.', $ndk_field['admin_name']);
                    if (count($split) == 2) {    
                        $sheet = $split[0];
                        $column = $split[1];
                        // spécifique ou standard
                        $key_specific = 'SP-'.$id_product.'-%.'.$sheet.'%';
                        $key_standard = 'ST-%.'.$sheet.'%';
                        $specific  = $this->db->findHeader($key_specific);
                        $standard = $specific ? false : $this->db->findHeader($key_standard);
                        if ($specific == false && $standard == false) {
                            continue;
                        }    
                        $this->config_items[] = [
                            'id_ndk_customization_field' => $ndk_field['id_ndk_customization_field'],
                            'filter_by' => false,
                            'dynamic_influences' => false,
                            'complementary_name' => strlen($ndk_field['complementary_name']) ? $ndk_field['complementary_name'] : false,
                            'type' => 0,
                            'influences' => false,
                            'id_product' => $id_product,
                            'sheet' => $sheet,
                            'column' => $column,
                            'sheet_column' => $sheet.'.'.$column,
                            'header' => $specific ? $key_specific : $key_standard,
                            'additional' => $ai,
                        ];
                    }
                }
            }
        }
        // Languagues
        $this->config_languages = array();
        $this->config_dateformat = array();
        $sql = 'SELECT id_lang, iso_code, date_format_lite FROM `' . _DB_PREFIX_ .'lang`';
        if ($rows = Db::getInstance()->executeS($sql)) {
            foreach($rows as $r) {
                $this->config_languages[$r['iso_code']] = (int)$r['id_lang'];
                $this->config_dateformat[$r['iso_code']] = $r['date_format_lite'];
            }
        }
        // Countries
        $this->config_countries = array();
        $sql = 'SELECT id_country, iso_code FROM `' . _DB_PREFIX_ .'country` WHERE active = 1';
        if ($rows = Db::getInstance()->executeS($sql)) {
            foreach($rows as $r) {
                $this->config_countries[$r['iso_code']] = (int)$r['id_country'];
            }
        }

        // Erase all config for specified products
        foreach($erase_config_products as $ecp) {
            Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ .'ndk_customization_field_additional_info` WHERE id_product = '. $ecp);
            if ($ndkFields = Db::getInstance()->executeS('SELECT id_ndk_customization_field FROM `' . _DB_PREFIX_ .'ndk_customization_field` WHERE products = "'.$ecp.'"')) {
                $ndkIds = [];
                foreach($ndkFields as $f) {
                    if ($ndkId = (int)$f['id_ndk_customization_field']) {
                        $ndkIds[] = $ndkId;
                    }
                }
                Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ .'ndk_customization_field_value_lang` WHERE id_ndk_customization_field_value IN (SELECT id_ndk_customization_field_value FROM `' . _DB_PREFIX_ .'ndk_customization_field_value` WHERE id_ndk_customization_field IN ('.implode(',',$ndkIds).'))');
                Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ .'ndk_customization_field_value` WHERE id_ndk_customization_field IN ('.implode(',',$ndkIds).')');
            }
        }
        $this->config_images_path = _PS_IMG_DIR_.'scenes/ndkcf/';
        // Erase all ndk config images (don't erase on multi config)
        /*$config_images = scandir($this->config_images_path);
        foreach($config_images as $i) {
            if (preg_match('/^.*\.(jpg|jpeg|png|gif|svg)$/i', $i)) {
                $i = $this->config_images_path.$i;
                unlink($i);
            }
        }*/
    }

    public function afterImport()
    {
        if (Module::getInstanceByName('ndk_advanced_custom_fields')) {
            NdkCf::reGenerateThumbs(true);
        }
    }

    public function nextHeader()
    {
        $header = parent::nextHeader();
        if ($header && $this->current_config['influences']) {
            $this->current_config['influences_lists'] = $this->makeInfluencesLists($this->current_config['influences']);
            $this->current_config['influences_columns'] = $this->getInfluencesColumns($this->current_config['influences']);
        }
        return $header;
    }

    public function getNdkFieldIdFromReference($label)
    {
        $ll = explode('.', $label);
        $sql = 'SELECT v.id_ndk_customization_field_value FROM ' . _DB_PREFIX_ . 'ndk_customization_field_value v
        WHERE v.reference = "' . $this->values[$this->name][$ll[1]] . '" AND v.id_ndk_customization_field = ' . $this->sheet_column_ID[$label];
        return (int)Db::getInstance()->getValue($sql);
    }

    public function getInfluencesRestrictions($config_influences, $lists, $columns)
    {
        $excludeIds = array();
        $id_influences = explode(',', $config_influences);
        foreach($id_influences as $id_influence) {
            if ((int)$id_influence) {
                $allIds = $lists[(int)$id_influence];
                $restricted_column = $columns[(int)$id_influence];
                $code_restricted_column = 'code_'.$columns[(int)$id_influence];
                $included = false;
                if (isset($this->values[$this->name][$code_restricted_column])) {
                    $included = explode(',', $this->values[$this->name][$code_restricted_column]);
                } elseif (isset($this->values[$this->name][$restricted_column])) {
                    $included = explode(',', $this->values[$this->name][$restricted_column]);
                }
                if ($included) {
                    $tmp_included = [];
                    foreach($included as $t) {
                        $t = trim($t);
                        if ($t && !in_array($t, $tmp_included)) {
                            $tmp_included[] = $t;
                        }
                    }
                    foreach($allIds as $id => $label) {
                        if (!in_array($label, $tmp_included)) {
                            $excludeIds[] = $id;
                        }
                    }
                }
            }
        }
        return $excludeIds;
    }

    public function makeInfluencesLists($config_influences)
    {
        $result = array();
        $id_influences = explode(',', $config_influences);
        foreach($id_influences as $id_influence) {
            if ((int)$id_influence) {
                $sql = 'SELECT v.id_ndk_customization_field_value, v.reference FROM ' . _DB_PREFIX_ . 'ndk_customization_field_value v
                WHERE v.id_ndk_customization_field = '.(int)$id_influence;
                if ($rows = Db::getInstance()->executeS($sql)) {
                    foreach($rows as $r) {
                        $result[(int)$id_influence][(int)$r['id_ndk_customization_field_value']] = $r['reference']; 
                    }
                }
            }
        }
        return $result;
    }

    public function getInfluencesColumns($config_influences)
    {
        $result = array();
        $id_influences = explode(',', $config_influences);
        foreach($id_influences as $id_influence) {
            if ((int)$id_influence) {
                $sql = 'SELECT vl.admin_name 
                FROM ' . _DB_PREFIX_ . 'ndk_customization_field_lang vl
                WHERE vl.id_ndk_customization_field = '.(int)$id_influence;
                if ($admin_name = Db::getInstance()->getValue($sql)) {
                    $split = explode('.', $admin_name);
                    if (count($split) == 2) {
                        $admin_name = $split[1];
                    }
                }
                $result[(int)$id_influence] = $admin_name;
            }
        }
        return $result;
    }

    public function prepareValues($name, $values)
    {
        $this->name = $name;
        $this->values[$name] = $values;

        $dates_commercialisation = false;
        foreach ($this->values[$this->name] as $key => &$value) {
            if ($key == 'date_debut_commercialisation' || $key == 'date_fin_commercialisation') {
                $dates_commercialisation = true;
                if (substr($value,0,4) != '0000' && substr($value,0,4) != '9999') {
                    //$value = date('d/m/Y', strtotime($value));
                } else {
                    $value = '';
                }
                if ($key == 'date_debut_commercialisation') {
                    $date_debut_commercialisation = $value;
                }
                if ($key == 'date_fin_commercialisation') {
                    $date_fin_commercialisation = $value;
                }
            }
        }
        if ($dates_commercialisation) {
            // cas 1 : 0000 & 9999 => <vide>
            // cas 2 : 0000 & fin  => jusqu'au ...
            // cas 3 : début & 9999 => à partir du ...
            // cas 4 : début & fin => du ... au ...
            foreach($this->config_languages as $iso_code => $id_lang) {
                // Find translations
                $keyfromthe = '';
                $valuefrom = 'from';
                $valueto = 'to';
                $valuefromthe = 'from the';
                $valueuntil = 'until';
                /* pour traduction */
                $iwgtiimportmodule = Module::getInstanceByName('iwgtiimport');
                $iwgtiimportmodule->l('from');
                $iwgtiimportmodule->l('to');
                $iwgtiimportmodule->l('from the');
                $iwgtiimportmodule->l('until');
                /* / */
                $file = _PS_MODULE_DIR_ . 'iwgtiimport' . '/translations/' . $iso_code . '.php';
                if (file_exists($file) && include($file)) {
                    if (isset($_MODULE) && is_array($_MODULE)) {
                        // key string
                        $key = '<{iwgtiimport}prestashop>'.strtolower(get_class($this)).'_';
                        $keyfrom = $key.md5(preg_replace("/\\\*'/", "\'", $valuefrom));
                        $keyto = $key.md5(preg_replace("/\\\*'/", "\'", $valueto));
                        $keyfromthe = $key.md5(preg_replace("/\\\*'/", "\'", $valuefromthe));
                        $keyuntil = $key.md5(preg_replace("/\\\*'/", "\'", $valueuntil));                        
                        // translate
                        $valuefrom = isset($_MODULE[$keyfrom]) ? $_MODULE[$keyfrom] : $valuefrom;
                        $valueto = isset($_MODULE[$keyto]) ? $_MODULE[$keyto] : $valueto;
                        $valuefromthe = isset($_MODULE[$keyfromthe]) ? $_MODULE[$keyfromthe] : $valuefromthe;
                        $valueuntil = isset($_MODULE[$keyuntil]) ? $_MODULE[$keyuntil] : $valueuntil;
                    }
                }
                if ($iso_code == 'fr') {
                    $f = 'dates_commercialisation';
                } else {
                    $f = 'dates_commercialisation_' . $iso_code;
                }
                $dd = $date_debut_commercialisation ? date($this->config_dateformat[$iso_code], strtotime($date_debut_commercialisation)) : '';
                $df = $date_fin_commercialisation ? date($this->config_dateformat[$iso_code], strtotime($date_fin_commercialisation)) : '';
                if ($date_debut_commercialisation == false && $date_fin_commercialisation == false) {
                    $dc = '';                    
                } elseif ($date_debut_commercialisation == false && $date_fin_commercialisation) {
                    $dc = $valueuntil.' '.$df;
                } elseif ($date_debut_commercialisation && $date_fin_commercialisation == false) {
                    $dc = $valuefromthe.' '.$dd;
                } else {
                    $dc = $valuefrom.' '.$dd.' '.$valueto.' '.$df;
                }
                $this->values[$this->name][$f] = $dc;
            }
        }
    }

    public function importDetail($transaction)
    {
        $result = true;
        $do_lang = false;
        $influences_parent_id = 0;
        // préparation des champs
        // ... référence
        $column = $this->current_config['column'];
        $code_column = 'code_'.$column;
        if (isset($this->values[$this->name][$code_column])) {
            $tmp_import_reference = explode(',', $this->values[$this->name][$code_column]);
            $tmp_array = [];
            foreach($tmp_import_reference as $tmp) {
                $tmp = trim($tmp);
                if ($tmp && !in_array($tmp, $tmp_array)) {
                    $tmp_array[] = $tmp;
                }
            }
            $import_reference = implode(',', $tmp_array);
        } else {
            $import_reference = $this->values[$this->name][$column];
        }
        if (isset($this->values[$this->name][$code_column]) == false && $this->current_config['complementary_name']) {
            $complementary = $this->current_config['complementary_name'];
            $complementary_name_separator = ' ';
            if (strpos($complementary,',') !== false) {
                $complementary_name_separator = ',';
            }
            $split = explode($complementary_name_separator, $complementary);
            $v = [$import_reference];
            foreach($split as $s) {
                if (isset($this->values[$this->name][$s])) {
                    $v[] = $this->values[$this->name][$s];
                }
            }
            $import_reference = implode($complementary_name_separator, $v);
        }
        $this->values[$this->name]['import_reference'] = $import_reference;
        $fields = [
            'id_ndk_customization_field' => '::'.$this->current_config['id_ndk_customization_field'],
            'input_type' => '::select',
            'reference' => 'import_reference',
        ];

        if (strlen($import_reference)) {
            if ($this->current_config['additional']) {
                // additional values
                $default_lang = Configuration::get('PS_LANG_DEFAULT');            
                $id_product = $this->current_config['additional']['id_product'];
                $prefix = $this->current_config['additional']['prefix'];
                $value_by_country = $this->current_config['additional']['value_by_country'];
                $key_column = $this->current_config['additional']['key_column'];
                $vertical_mode = false;
                $pos = strpos($key_column, '.');
                if ($key_column && $pos) {
                    $vertical_mode = true;
                }
                $reportline['id_product'] = $id_product;
                $reportline['reference'] = $this->values[$this->name]['import_reference'];
                $key_id = Db::getInstance()->getValue('
                SELECT vl.id_ndk_customization_field_value FROM '._DB_PREFIX_.'ndk_customization_field_value vl
                WHERE vl.id_ndk_customization_field = '.(int)$this->current_config['id_ndk_customization_field'].'
                AND vl.reference = "'.pSQL($this->values[$this->name]['import_reference']).'"');
                $reportline['key_id'] = $key_id;
                if ($key_id) {
                    $iso_codes = [];
                    if ($value_by_country && $vertical_mode == false) {
                        foreach(array_keys($this->config_countries) as $iso_code) {
                            $iso_codes[] = $iso_code;
                        }
                    } else {
                        $iso_codes[] = 'ALL';
                    }
                    foreach($iso_codes as $iso_code) {
                        $insert = true;
                        $all_columns = [];
                        $all_key_values = [];
                        $columns = $this->current_config['additional']['key_column'];
                        $reportline['columns_'.$iso_code] = $columns;
                        if ($columns) {
                            $split_columns = explode(',', $columns);
                            foreach($split_columns as $c) {
                                $c = str_replace('#', '', $c);
                                $c = trim($c);
                                if ($c) {
                                    if ($vertical_mode == false) {
                                        if (isset($this->values[$this->name][$c])) {
                                            $cv = $this->values[$this->name][$c];
                                            if ($cv) {
                                                $search_column[$c] = explode(',', $cv);
                                            } else {
                                                $insert = false;
                                            }
                                        } else {
                                            // lecture des valeurs du champ NDK
                                            $insert = false;
                                        }
                                    }
                                    if ($vertical_mode) {
                                        // reprise des valeurs déjà injectées dans la feuille de ce champ (peu importe le champ)
                                        $field_key = explode('.', $key_column);
                                        $field_key = $field_key[0].'.%';
                                        $sql_id_field_key = 'SELECT vl.id_ndk_customization_field FROM '._DB_PREFIX_.'ndk_customization_field_lang vl
                                        INNER JOIN '._DB_PREFIX_.'ndk_customization_field v ON v.id_ndk_customization_field = vl.id_ndk_customization_field AND v.products = "' . $id_product . '"
                                        WHERE vl.admin_name LIKE "' . pSQL($field_key) . '" AND vl.id_lang = ' . $default_lang;
                                        // id from admin_name
                                        $id_field_key = (int)Db::getInstance()->getValue($sql_id_field_key);
                                        $rows = Db::getInstance()->executeS('SELECT reference FROM '._DB_PREFIX_.'ndk_customization_field_value WHERE id_ndk_customization_field = ' . $id_field_key );
                                        $cv = [];
                                        if ($rows) {
                                            foreach($rows as $r) {
                                                $cv[] = $r['reference'];
                                            }
                                            $search_column[$c] = $cv;
                                        } else {
                                            $insert = false;
                                        }
                                    }
                                }
                            }
                            if ($insert) {
                                $count = 0;
                                foreach($search_column as $c => $v) {
                                    if (count($v)>$count) {
                                        $count = count($v);
                                    } else {
                                        $count += 1;
                                    }
                                }
                                $iterator = new IWSTDIterator(count($split_columns), $count, true);
                                while ($iterator->next()) {
                                    $values = [];
                                    foreach($iterator->current() as $key => $index) {
                                        $c = $split_columns[$key];
                                        $c = str_replace('#', '', $c);
                                        $c = trim($c);
                                        if ($c) {
                                            if ($index < count($search_column[$c])) {
                                                $values[] = $search_column[$c][$index];
                                            } else {
                                                $values = false;
                                                break;
                                            }
                                        }
                                    }
                                    if ($values) {
                                        $all_key_values[] = $values;
                                        array_unshift($values, $prefix);
                                        if ($value_by_country) {
                                            array_push($values, $iso_code);
                                        }
                                        $all_columns[] = $values;
                                    }
                                }
                            }
                        } else {
                            $values = [];
                            $all_key_values[] = $values;
                            array_unshift($values, $prefix);
                            if ($value_by_country) {
                                array_push($values, $iso_code);
                            }
                            $all_columns[] = $values;
                        }
                        $reportline['insert_'.$iso_code] = $insert;
                        $reportline['count_all_columns_'.$iso_code] = count($all_columns);
                        $reportline['count_all_key_values_'.$iso_code] = count($all_key_values);
                        if ($insert && (count($all_columns) == count($all_key_values))) {
                            // for all values
                            $reportindex = 0;
                            foreach($all_columns as $columns) {
                                $reportindex += 1;
                                $key_values = array_shift($all_key_values);
                                $reportline['key_values_'.$iso_code.'_'.$reportindex] = implode(',', $key_values);
                                $reportline['columns_'.$iso_code.'_'.$reportindex] = implode('_', $columns);
                                if ($vertical_mode == false) {
                                    $search = implode('_', $columns);
                                    if (isset($this->values[$this->name][$search])) {
                                        $insertvalues = [
                                            $id_product,
                                            $key_id,
                                            pSQL($prefix),
                                            pSQL(implode(',', $key_values)),
                                            $value_by_country ? $iso_code : '',
                                            $this->values[$this->name][$search]
                                        ];
                                        $insertsql = 'INSERT INTO '._DB_PREFIX_.'ndk_customization_field_additional_info (`id_product`,`key_id`,`key_prefix`,`key_values`,`key_iso`,`value`)
                                        VALUES("'.implode('","', $insertvalues).'")';
                                        $reportline['value_'.$iso_code.'_'.$reportindex] = $this->values[$this->name][$search];
                                        Db::getInstance()->execute($insertsql);
                                    }
                                } else {
                                    $iw_key = explode('.', $key_column);
                                    $iw_key = array_pop($iw_key);
                                    $iw_key_values = array_pop($key_values);
                                    $sql = 'SELECT v.`value` FROM `'._DB_PREFIX_.'iwgtiimport_keyvalue` v WHERE v.`key` = "'.pSQL($this->current_config['additional']['prefix']).'" AND v.id_detail = (
                                    SELECT id_detail  FROM `'._DB_PREFIX_.'iwgtiimport_keyvalue` WHERE `key` = "'.pSQL($iw_key).'" AND `value` = "'.pSQL($iw_key_values).'")';
                                    if ($iw_value = Db::getInstance()->getValue($sql)) {
                                        //$expl_iw_key_values = explode(' ', $iw_key_values);
                                        $insertvalues = [
                                            $id_product,
                                            $key_id,
                                            pSQL($prefix),
                                            //pSQL(implode(',', $expl_iw_key_values)),
                                            pSQL($iw_key_values),
                                            $value_by_country ? $iso_code : '',
                                            $iw_value
                                        ];
                                        $insertsql = 'INSERT INTO '._DB_PREFIX_.'ndk_customization_field_additional_info (`id_product`,`key_id`,`key_prefix`,`key_values`,`key_iso`,`value`)
                                        VALUES("'.implode('","', $insertvalues).'")';
                                        Db::getInstance()->execute($insertsql);
                                    }
                                }
                            }
                        }
                    }
                }
                file_put_contents($this->reportfile, implode(';', $reportline)."\n", FILE_APPEND);
                return $result;
            } elseif ($this->current_config['influences']) {
                $exclude_ids = $this->getInfluencesRestrictions($this->current_config['influences'], $this->current_config['influences_lists'], $this->current_config['influences_columns']);
                if (count($exclude_ids)) {
                    $this->values[$this->name]['import_influences_restrictions'] = count($exclude_ids) ? implode(',', $exclude_ids) : '';
                    $fields['influences_restrictions'] =  'import_influences_restrictions';
                }
            } else {
                $do_lang = true;
                if ($this->current_config['filter_by']) {
                    $influences_parent_id = $this->getNdkFieldIdFromReference($this->current_config['filter_by']);
                }
                $fields['price'] = isset($this->values[$this->name]['prix']) ? 'prix' : '::0';
                $fields['position'] = isset($this->values[$this->name]['position']) ? 'position' : '::0';
                $fields['influences_parent_id'] = '::'.$influences_parent_id;
            }

            $result &= $transaction->controllerImport(array(
                array(
                    'values' => array($this->values[$this->name]),
                    'fields' => $fields,
                    'params' => array(
                        'table' => _DB_PREFIX_ . 'ndk_customization_field_value',
                        'insert' => true,
                        'update' => true,
                        'key' => 'id_ndk_customization_field,reference',
                        'primary' => 'id_ndk_customization_field_value',
                    )
                ),
            ));
            $last_Insert_ID = isset($transaction->last_Insert_ID) ? $transaction->last_Insert_ID : 0;
            if ($this->current_config['type'] == 2) {
                // copie image + vignette
                $pattern = _PS_ROOT_DIR_._MODULE_DIR_.'iwgtiimport/data/images/'.$this->current_config['sheet'].'/'.$import_reference;
                $images = glob($pattern.'.*');
                $vignettes = glob($pattern.'-*');
                $copyimages = [];
                if (count($images)) {
                    $copyimages[] = array_pop($images);
                }
                if (count($vignettes)) {
                    $copyimages[] = array_pop($vignettes);
                }
                foreach($copyimages as $cp) {
                    $i = basename($cp);
                    $dst = $this->config_images_path.str_replace($import_reference, $last_Insert_ID, $i);
                    if (file_exists($dst)) {
                        unlink($dst);
                    }
                    copy($cp, $dst);
                }
            }
            if ($do_lang) {
                foreach(['fr','other'] as $lang) {
                    foreach($this->config_languages as $iso_code => $id_lang) {
                        if (($lang == 'fr' && $lang == $iso_code) || ($lang != 'fr' && $iso_code != 'fr')) {
                            $source_fields = [$this->current_config['column']];
                            $complementary_name_separator = ' ';
                            if ($this->current_config['complementary_name']) {
                                $complementary = $this->current_config['complementary_name'];
                                if (strpos($complementary,',') !== false) {
                                    $complementary_name_separator = ',';
                                    $split = explode(',', $complementary);                                    
                                } else {
                                    $split = explode(' ', $complementary);
                                }
                                foreach($split as $s) {
                                    if (isset($this->values[$this->name][$s])) {
                                        $source_fields[] = $s;
                                    }
                                }
                            }
                            $v = [];
                            foreach($source_fields as $f) {
                                $source_field = $lang == 'fr' ? $f : $f.'_'.$iso_code;
                                if ($lang != 'fr') {
                                    if (!isset($this->values[$this->name][$source_field]) || strlen($this->values[$this->name][$source_field]) == 0) {
                                        $source_field = $f;
                                    }
                                }
                                $vv = trim($this->values[$this->name][$source_field]);
                                if ($vv) {
                                    $v[] = $this->values[$this->name][$source_field];
                                }
                            }
                            $this->values[$this->name]['import_value'] = implode($complementary_name_separator,$v);
                            $description_field = $lang == 'fr' ? 'description' : 'description_'.$iso_code;
                            if ($lang != 'fr') {
                                if (!isset($this->values[$this->name][$description_field]) || strlen($this->values[$this->name][$description_field]) == 0) {
                                    $description_field = 'description';
                                }
                            }
                            $fields = [
                                'id_ndk_customization_field_value' => '::'.$last_Insert_ID,
                                'id_lang' => '::'.$id_lang,
                                'value' => 'import_value',
                                'tags' => '::',
                                'textmask' => '::',
                                'description' => $description_field,
                            ];
                            $result &= $transaction->controllerImport(array(
                                array(
                                    'values' => array($this->values[$this->name]),
                                    'fields' => $fields,
                                    'params' => array(
                                        'table' => _DB_PREFIX_ . 'ndk_customization_field_value_lang',
                                        'insert' => true,
                                        'update' => true,
                                        'key' => 'id_ndk_customization_field_value,id_lang,value',
                                    )
                                ),
                        ));
                        }
                    }
                }
            }
            return $result;
        } else {
            return true;
        }
    }
}
