<?php

class productsCombinationsWithoutImagesSegment extends SegmentCustom
{
    public $name = 'Products combinations without image';
    public $liste_hooks = array('segmentAutoConfig', 'segmentAutoSqlQuery', 'segmentAutoSqlQueryGrid');

    public function _executeHook_segmentAutoConfig($name, $params = array())
    {
        $values = array();
        if (!empty($params['values']))
        {
            $values = unserialize($params['values']);
        }

        $html = '<strong>'._l('Display products').'</strong><br/>
        <select name="active_pdt" style="width: 100%">
            <option value="all" '.(empty($values['active_pdt']) || $values['active_pdt'] == 'all' ? 'selected' : '').'>'._l('Active and nonactive').'</option>
            <option value="active" '.(!empty($values['active_pdt']) && $values['active_pdt'] == 'active' ? 'selected' : '').'>'._l('Active only').'</option>
            <option value="nonactive" '.(!empty($values['active_pdt']) && $values['active_pdt'] == 'nonactive' ? 'selected' : '').'>'._l('Nonactive only').'</option>
        </select>';

        return $html;
    }

    public function _executeHook_segmentAutoSqlQueryGrid($name, $params = array())
    {
        $array = array();

        if (!empty($params['auto_params']))
        {
            $auto_params = unserialize($params['auto_params']);
        }

        $sql = 'SELECT DISTINCT a.id_product
        FROM '._DB_PREFIX_.'product AS a 
        INNER JOIN '._DB_PREFIX_.'product_attribute AS b ON a.id_product =  b.id_product 
        WHERE 
            b.id_product_attribute NOT IN (SELECT DISTINCT(id_product_attribute) FROM '._DB_PREFIX_.'product_attribute_image) '.
            (!empty($auto_params['active_pdt']) && $auto_params['active_pdt'] != 'all' ? " AND a.active='".($auto_params['active_pdt'] == 'active' ? '1' : '0')."'" : '');
        $res = Db::getInstance()->ExecuteS($sql);
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
        if (!empty($params['auto_params']))
        {
            $auto_params = unserialize($params['auto_params']);
        }

        $where = ' '.(empty($params['no_operator']) ? 'AND' : '').' ( p.id_product IN (SELECT DISTINCT pcws_pdt.id_product
        FROM '._DB_PREFIX_.'product AS pcws_pdt 
        INNER JOIN '._DB_PREFIX_.'product_attribute AS pcws_combi ON pcws_pdt.id_product =  pcws_combi.id_product 
        WHERE pcws_combi.id_product_attribute NOT IN (SELECT DISTINCT(id_product_attribute) FROM '._DB_PREFIX_.'product_attribute_image))'.
        (!empty($auto_params['active_pdt']) && $auto_params['active_pdt'] != 'all' ? " AND p.active='".($auto_params['active_pdt'] == 'active' ? '1' : '0')."'" : '').' ) ';

        return $where;
    }
}
