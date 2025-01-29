<?php

class productsEmptyFieldSegment extends SegmentCustom
{
    public $name = 'Products : Empty field';
    public $liste_hooks = ['segmentAutoConfig', 'segmentAutoSqlQueryGrid', 'segmentAutoSqlQuery'];

    public function _executeHook_segmentAutoConfig($name, $params = [])
    {
        $values = [];
        if (!empty($params['values']))
        {
            $values = unserialize($params['values']);
        }

        $search_fields = [];
        if (!empty($values['search_fields']))
        {
            $search_fields = explode('-', $values['search_fields']);
        }

        $selected_langs = [];
        if (!empty($values['selected_langs']))
        {
            $selected_langs = explode(',', $values['selected_langs']);
        }

        $html_options = [
            'name' => _l('Name'),
            'description' => _l('Description'),
            'description_short' => _l('Short description'),
            'reference' => _l('Reference'),
            'supplier_reference' => _l('Supplier reference'),
            'ean13' => _l('EAN13'),
            'upc' => _l('UPC'),
            'meta_title' => _l('meta_title'),
            'meta_description' => _l('meta_description'),
            'manufacturer' => _l('Manufacturer'),
            'supplier' => _l('Supplier'),
            'link_rewrite' => _l('link_rewrite'),
            'location' => _l('Stock location'),
        ];

        if (version_compare(_PS_VERSION_, '1.7.0.0', '>='))
        {
            $html_options['isbn'] = _l('ISBN');
        }

        if (version_compare(_PS_VERSION_, '1.7.7.0', '>='))
        {
            $html_options['mpn'] = _l('MPN');
        }

        $html = '<strong>'._l('Search in?').'</strong><br/>
        <select id="search_fields" style="width: 100%; height: 5em;" multiple="multiple">';
        foreach ($html_options as $field_id => $field_name)
        {
            $html .= '<option value="'.$field_id.'" '.(in_array($field_id, $search_fields) ? 'selected' : '').'>'.$field_name.'</option>';
        }
        $html .= '</select>
        <input type="hidden" name="search_fields" value="'.implode('-', $search_fields).'" />
        
        <br/><br/>
        <strong>'._l('Display products').'</strong><br/>
        <select name="active_pdt" style="width: 100%">
            <option value="all" '.(empty($values['active_pdt']) || $values['active_pdt'] == 'all' ? 'selected' : '').'>'._l('Active and nonactive').'</option>
            <option value="active" '.(!empty($values['active_pdt']) && $values['active_pdt'] == 'active' ? 'selected' : '').'>'._l('Active only').'</option>
            <option value="nonactive" '.(!empty($values['active_pdt']) && $values['active_pdt'] == 'nonactive' ? 'selected' : '').'>'._l('Nonactive only').'</option>
        </select>
        
        <br/><br/>';
        $html .= '<strong>'._l('Languages').'</strong><br/>
        <select id="selected_langs" style="width: 100%; height: 5em;" multiple="multiple">';
        $languages = Db::getInstance()->executeS('SELECT id_lang,name FROM '._DB_PREFIX_.'lang');
        $languages = array_column($languages, 'name', 'id_lang');
        foreach ($languages as $field_id => $field_name)
        {
            $html .= '<option value="'.$field_id.'" '.(in_array($field_id, $selected_langs) ? 'selected' : '').'>'.$field_name.'</option>';
        }
        $html .= '</select>
        <input type="hidden" name="selected_langs" value="'.implode(',', $selected_langs).'" />
                    
        <script>
        $(document).ready(function(){
            $("#search_fields").change(function(){
                let fields = $("#search_fields").val().join("-");
                $("input[name=search_fields]").val(fields);
            });
            $("#selected_langs").change(function(){
                let fields = $("#selected_langs").val().join(",");
                $("input[name=selected_langs]").val(fields);
            });
        });
        </script>';

        return $html;
    }

    public function _executeHook_segmentAutoSqlQueryGrid($name, $params = [])
    {
        $data_products = [];
        $params['id_shop'] = (int) SCI::getSelectedShop();
        $sqlQuery = $this->getMainDbQuery($params);

        if (!$sqlQuery)
        {
            return $data_products;
        }

        $res = Db::getInstance()->executeS($sqlQuery);
        if (!$res)
        {
            return $data_products;
        }

        foreach ($res as $row)
        {
            $type = _l('Product');
            $element = new Product($row['id_product'], SCMS, $params['id_lang']);
            $name = $element->name;
            $infos = $element->reference;
            $data_products[] = [$type, $name, $infos, 'id' => 'product_'.$row['id_product'], 'id_display' => $row['id_product']];
        }

        return $data_products;
    }

    public function _executeHook_segmentAutoSqlQuery($name, $params = [])
    {
        $sql = $this->getMainDbQuery($params);

        if (!$sql)
        {
            return '';
        }

        if (empty($params['auto_params']))
        {
            return false;
        }
        $auto_params = unserialize($params['auto_params']);

        $operator = (empty($params['no_operator']) ? 'AND' : '');

        $aliasTableProduct = (SCMS ? 'prs' : 'p');

        if (!empty($auto_params['active_pdt']) && $auto_params['active_pdt'] != 'all')
        {
            return ' '.$operator.' ( p.`id_product` IN ( '.$sql->build().') AND '.$aliasTableProduct.'.`active` = '.($auto_params['active_pdt'] == 'active' ? 1 : 0).')';
        }

        return ' '.$operator.' p.`id_product` IN ( '.$sql->build().') ';
    }

    /**
     * @param $segmentParams
     *
     * @return DbQuery|false
     */
    protected function getMainDbQuery($segmentParams = [])
    {
        if (empty($segmentParams['auto_params']))
        {
            return false;
        }
        $auto_params = unserialize($segmentParams['auto_params']);

        if (empty($auto_params['search_fields']))
        {
            return false;
        }

        $search_in = [];
        $tmps = explode('-', $auto_params['search_fields']);
        if (empty($tmps))
        {
            return false;
        }

        if (!empty($auto_params['selected_langs']))
        {
            $inLang = ' AND subpl.`id_lang` IN ('.pInSQL($auto_params['selected_langs']).')';
        }

        $sql = (new DbQuery())
            ->select('subp.`id_product`')
            ->from('product', 'subp')
            ->innerJoin('product_shop', 'subprs', 'subprs.`id_product` = subp.`id_product` AND subprs.`id_shop` = '.(int) $segmentParams['id_shop'])
            ->leftJoin('product_lang', 'subpl', 'subpl.`id_product` = subp.`id_product`'.$inLang.' AND subpl.`id_shop` = subprs.`id_shop`')
        ;

        foreach ($tmps as $field)
        {
            switch ($field) {
                case 'reference':
                case 'ean13':
                case 'upc':
                case 'isbn':
                case 'mpn':
                    $search_in[] = '(subp.`'.bqSQL($field).'` IS NULL OR subp.`'.bqSQL($field).'` = "")';
                    break;
                case 'supplier':
                    $search_in[] = '(subp.`id_supplier` IS NULL OR subp.`id_supplier` = "")';
                    break;
                case 'manufacturer':
                    $search_in[] = '(subp.`id_manufacturer` IS NULL OR subp.`id_manufacturer` = "")';
                    break;
                case 'location':
                    if (version_compare(_PS_VERSION_, '1.7.5.0', '>='))
                    {
                        $sql->leftJoin('stock_available', 'subsa', 'subsa.`id_product` = subp.`id_product` AND subsa.`id_product_attribute` = 0 AND subsa.`id_shop` = subprs.`id_shop`');
                        $search_in[] = '(subsa.`'.bqSQL($field).'`IS NULL OR (subsa.`'.bqSQL($field).'`) = "")';
                        break;
                    }
                    $search_in[] = '(subp.`'.bqSQL($field).'` IS NULL OR (subp.`'.bqSQL($field).'`) = "")';
                    break;
                case 'supplier_reference':
                        $sql->leftJoin('supplier_shop', 'subss', 'subss.`id_supplier` = subp.`id_supplier` AND subss.`id_shop` = subprs.`id_shop`');
                        $sql->leftJoin('product_supplier', 'subpsup', 'subpsup.`id_product` = subp.`id_product` AND subpsup.`id_product_attribute` = 0 AND subpsup.`id_supplier` = subss.`id_supplier`');
                        $search_in[] = '(subpsup.`product_supplier_reference` IS NULL OR subpsup.`product_supplier_reference` = "")';
                    break;
                default: ## name, description_short, description, meta_title, meta_description, link_rewrite
                    $search_in[] = '(subpl.`'.bqSQL($field).'` IS NULL OR subpl.`'.bqSQL($field).'` = "")';
            }
        }

        if (empty($search_in))
        {
            return false;
        }

        $sql->where(implode(' OR ', $search_in));

        return $sql;
    }
}
