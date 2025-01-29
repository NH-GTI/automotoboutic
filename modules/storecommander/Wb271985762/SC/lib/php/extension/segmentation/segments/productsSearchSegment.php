<?php

class productsSearchSegment extends SegmentCustom
{
    public $name = 'Products: expression search';
    public $liste_hooks = array(
            'segmentAutoConfig',
            'segmentAutoSqlQuery',
            'segmentAutoSqlQueryGrid',
        );

    protected $whereCondition;

    public function _executeHook_segmentAutoConfig($name, $params = array())
    {
        $values = array();
        if (!empty($params['values']))
        {
            $values = unserialize($params['values']);
        }

        $search_fields = array();
        if (!empty($values['search_fields']))
        {
            $search_fields = explode('-', $values['search_fields']);
        }

        $html = '<strong>'._l('What term do you want to look for?').'</strong><br/>
                <input type="text" name="search_words" value="'.((!empty($values['search_words'])) ? $values['search_words'] : '').'" style="width: 100%;" />
                <br/><br/>
                <strong>'._l('Search in?').'</strong><br/>
                <select id="search_fields" style="width: 100%; height: 10em;" multiple="multiple">
                    <option value="name" '.(in_array('name', $search_fields) ? 'selected' : '').'>'._l('Name').'</option>
                    <option value="description" '.(in_array('description', $search_fields) ? 'selected' : '').'>'._l('Description').'</option>
                    <option value="reference" '.(in_array('reference', $search_fields) ? 'selected' : '').'>'._l('Reference').'</option>
                    <option value="supplier_reference" '.(in_array('supplier_reference', $search_fields) ? 'selected' : '').'>'._l('Supplier reference').'</option>
                    <option value="ean13" '.(in_array('ean13', $search_fields) ? 'selected' : '').'>'._l('EAN13').'</option>
                    <option value="upc" '.(in_array('upc', $search_fields) ? 'selected' : '').'>'._l('UPC').'</option>
                </select>
                <input type="hidden" name="search_fields" value="'.(isset($values['search_fields']) ? $values['search_fields'] : '').'" />
                            
                <br/><br/>
                <strong>'._l('Display products').'</strong><br/>
                <select name="active_pdt" style="width: 100%">
                    <option value="all" '.(empty($values['active_pdt']) || $values['active_pdt'] == 'all' ? 'selected' : '').'>'._l('Active and nonactive').'</option>
                    <option value="active" '.(!empty($values['active_pdt']) && $values['active_pdt'] == 'active' ? 'selected' : '').'>'._l('Active only').'</option>
                    <option value="nonactive" '.(!empty($values['active_pdt']) && $values['active_pdt'] == 'nonactive' ? 'selected' : '').'>'._l('Nonactive only').'</option>
                </select>
                            
                <script>
                $(document).ready(function(){
                    $("#search_fields").change(function(){
                        let fields = $(this).val()
                        if(fields !== null)
                        {
                            fields = fields.join("-");
                        }
                        $("input[name=search_fields]").val(fields);
                    });
                });
                </script>';

        return $html;
    }

    /**
     * @param $segmentParams
     * @return DbQuery|false
     */
    protected function getMainDbQuery($segmentParams = [])
    {
        if (empty($segmentParams['auto_params']))
        {
            return false;
        }

        $auto_params = unserialize($segmentParams['auto_params']);
        if (empty($auto_params['search_fields']) || empty($auto_params['search_words']))
        {
            return false;
        }

        $searchValue = $auto_params['search_words'];
        $searchFieldSelection = explode('-', $auto_params['search_fields']);
        if (empty($searchFieldSelection))
        {
            return false;
        }

        $productsQuery = new DbQuery();
        $searchIn = [];

        foreach ($searchFieldSelection as $field)
        {
            switch ($field)
            {
                case 'supplier_reference':
                    ## using same alias as "cat_product_get" to avoid error in AutoSqlQuery
                    $productsQuery->leftJoin('product_supplier', 'ps', '(ps.id_product = p.id_product)');
                    $searchIn[] = 'LOWER(p.`'.bqSQL($field).'`) LIKE ("%'.pSQL(strtolower($searchValue)).'%")';
                    $searchIn[] = 'LOWER(ps.`product_supplier_reference`) LIKE ("%'.pSQL(strtolower($searchValue)).'%")';
                    break;
                default:
                    if (!empty($field))
                    {
                        $searchIn[] = 'LOWER('.pSQL($field).') LIKE ("%'.pSQL(strtolower($searchValue)).'%")';
                    }
            }
        }

        if (empty($searchIn))
        {
            return false;
        }

        $searchIn = '('.implode(' OR ', $searchIn).')';

        $productsQuery
            ->select('DISTINCT (p.id_product)')
            ->from('product','p')
            ->innerJoin('product_lang', 'pl', '(p.id_product = pl.id_product AND pl.id_lang = '.(int)$segmentParams['id_lang'].')')
        ;

        if (!empty($auto_params['active_pdt']) && $auto_params['active_pdt'] != 'all')
        {
            $this->whereCondition = $searchIn.' AND p.active = '.(int) ($auto_params['active_pdt'] == 'active');
        }
        else
        {
            $this->whereCondition = $searchIn;
        }

        $productsQuery->where($this->whereCondition);

        return $productsQuery;
    }

    public function _executeHook_segmentAutoSqlQueryGrid($name, $params = array())
    {
        $array = [];

        $sqlQuery = $this->getMainDbQuery($params);
        if(!$sqlQuery)
        {
            return $array;
        }

        $res = Db::getInstance()->executeS($sqlQuery);
        if(!$res)
        {
            return $array;
        }

        foreach ($res as $row)
        {
            $type = _l('Product');
            $element = new Product($row['id_product'], SCMS);
            $name = $element->name[$params['id_lang']];
            $infos = $element->reference;
            $array[] = array($type, $name, $infos, 'id' => 'product_'.$row['id_product'], 'id_display' => $row['id_product']);
        }

        return $array;
    }

    public function _executeHook_segmentAutoSqlQuery($name, $params = array())
    {
        $where = 'FALSE';
        $operator = (empty($params['no_operator']) ? 'AND' : '');

        $sqlQuery = $this->getMainDbQuery($params);
        if($sqlQuery)
        {
            $where = '('.$this->whereCondition.')';
        }

        return ' '.$operator.' '.$where;
    }
}
