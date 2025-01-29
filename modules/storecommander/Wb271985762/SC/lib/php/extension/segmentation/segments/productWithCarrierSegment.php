<?php

class productWithCarrierSegment extends SegmentCustom
{
    public $name = 'Products with a specific carrier';
    public $liste_hooks = ['segmentAutoConfig', 'segmentAutoSqlQuery', 'segmentAutoSqlQueryGrid'];

    public function _executeHook_segmentAutoConfig($name, $params = [])
    {
        $html = '<strong>'._l('Carrier').' : '.'</strong><br/>';
        $html .= '<select id="id_carrier" style="width: 100%; height: 10em;" multiple="multiple">';

        $values = ['id_carriers' => ''];
        if (!empty($params['values']))
        {
            $values = unserialize($params['values']);
        }

        $id_carriers = [];
        if (!empty($values['id_carriers']))
        {
            $id_carriers = array_filter(explode('-', $values['id_carriers']));
        }

        $sql = (new DbQuery())
            ->select('MAX(c.`id_carrier`) as id_carrier, c.`name`')
            ->from('carrier', 'c')
            ->innerJoin('carrier_shop', 'cs', 'cs.`id_carrier` = cs.`id_carrier` AND cs.id_shop ='.(int) SCI::getSelectedShop())
            ->where('c.`active` = 1')
            ->orderBy('c.`name` ASC')
            ->groupBy('c.`id_reference`');

        $carriers = Db::getInstance()->executeS($sql);
        foreach ($carriers as $carrier)
        {
            $html .= '<option value="'.$carrier['id_carrier'].'" '.(in_array($carrier['id_carrier'], $id_carriers) ? 'selected' : '').'>'.$carrier['name'].'</option>';
        }
        $html .= '</select>
        <input type="hidden" name="id_carriers" value="'.$values['id_carriers'].'" />
        
        <script>
        $(document).ready(function(){
            $("#id_carrier").change(function(){
                var fields = "";
                $.each($("#id_carrier option:selected"), function(num, element){
                    var val = $(element).val();
                    fields = fields + val + "-";
                });
                $("input[name=id_carriers]").val(fields);
            });
        });
        </script>
                    
        <br/><br/>
        <strong>'._l('Display products').'</strong><br/>
        <select name="active_pdt" style="width: 100%">
            <option value="all" '.(empty($values['active_pdt']) || $values['active_pdt'] == 'all' ? 'selected' : '').'>'._l('Active and nonactive').'</option>
            <option value="active" '.(!empty($values['active_pdt']) && $values['active_pdt'] == 'active' ? 'selected' : '').'>'._l('Active only').'</option>
            <option value="nonactive" '.(!empty($values['active_pdt']) && $values['active_pdt'] == 'nonactive' ? 'selected' : '').'>'._l('Nonactive only').'</option>
        </select>';

        return $html;
    }

    public function _executeHook_segmentAutoSqlQueryGrid($name, $params = [])
    {
        $array = [];

        if (!empty($params['auto_params']))
        {
            $auto_params = unserialize($params['auto_params']);

            $array_id_carriers = array_filter(explode('-', $auto_params['id_carriers']));
            $ids = implode(',', $array_id_carriers);

            if (!empty($auto_params['id_carriers']))
            {
                $sql = (new DbQuery())
                    ->select('p.`id_product`')
                    ->from('product', 'p')
                    ->innerJoin('product_shop', 'ps', 'p.`id_product` = ps.`id_product` AND ps.`id_shop` ='.(int) SCI::getSelectedShop())
                    ->innerJoin('product_carrier', 'pc', 'p.`id_product`= pc.`id_product` AND pc.`id_shop` = ps.`id_shop`')
                    ->innerJoin('carrier', 'c', 'pc.`id_carrier_reference` = c.`id_reference`')
                    ->where('c.`id_carrier` IN ('.pInSQL($ids).')')
                    ->groupBy('p.`id_product`')
                    ->orderBy('p.`id_product`')
                ;
                if (!empty($auto_params['active_pdt']) && $auto_params['active_pdt'] != 'all')
                {
                    $sql->where('ps.`active` = '.(int) ($auto_params['active_pdt'] == 'active'));
                }
                $res = Db::getInstance()->executeS($sql);
                if (!empty($res))
                {
                    foreach ($res as $row)
                    {
                        $type = _l('Product');
                        $element = new Product($row['id_product'], SCMS);
                        $name = $element->name[$params['id_lang']];
                        $infos = $element->reference;
                        $array[] = [$type, $name, $infos, 'id' => 'product_'.$row['id_product'], 'id_display' => $row['id_product']];
                    }
                }
            }
        }

        return $array;
    }

    public function _executeHook_segmentAutoSqlQuery($name, $params = [])
    {
        $where = '';
        if (!empty($params['auto_params']))
        {
            $auto_params = unserialize($params['auto_params']);
            if (!empty($auto_params['id_carriers']))
            {
                $array_id_carriers = array_filter(explode('-', $auto_params['id_carriers']));
                $ids = implode(',', $array_id_carriers);

                $where = ' '.(empty($params['no_operator']) ? 'AND' : '').' ( (
                    p.id_product IN (SELECT DISTINCT(pc_seg.id_product)
                        FROM `'._DB_PREFIX_.'product_carrier` pc_seg 
                        INNER JOIN `'._DB_PREFIX_.'carrier` c_seg ON (pc_seg.id_carrier_reference=c_seg.id_reference AND pc_seg.id_shop='.(int) SCI::getSelectedShop().')
                        WHERE c_seg.id_carrier IN ('.pInSQL($ids).'))
                )'.(!empty($auto_params['active_pdt']) && $auto_params['active_pdt'] != 'all' ? " AND p.active='".($auto_params['active_pdt'] == 'active' ? '1' : '0')."'" : '').' ) ';
            }
        }

        return $where;
    }
}
