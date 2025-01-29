<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

$stat_view = Tools::getValue('stat_view', null);
$list_id_product_attribute = Tools::getValue('id_product_attribute_list', null);
$ids = explode(',', $list_id_product_attribute);
foreach ($ids as &$row)
{
    $data = explode('_', $row);
    $row = $data[1];
}
$list_id_product_attribute = implode(',', $ids);
$chart_data = [];
$dhtmlxChart_yAxis_title = '';
$dhtmlxChart_init = [
    'view' => 'bar',
    'container' => '',
    'value' => '#sales#',
    'tooltip' => [
        'template' => '#sales#',
        'dx' => -50,
        'dy' => 20,
    ],
    'color' => '#65dd65',
    'width' => 30,
    'radius' => 6,
    'border' => false,
    'seriesPadding' => 5,
    'xAxis' => [
        'template' => '',
        'title' => '',
    ],
    'yAxis' => [
        'title' => '',
    ],
];
$dhtmlxChart_types = [
    'chart_one' => [
        'container' => 'div_chart_one',
        'color' => '#98a1e6',
        'xAxis' => [
            'template' => '#days#',
            'title' => _l('Last 30 days'),
        ],
    ],
    'chart_two' => [
        'container' => 'div_chart_two',
        'color' => '#dd6363',
        'xAxis' => [
            'template' => '#month#',
            'title' => _l('Last 24 months'),
        ],
    ],
    'chart_three' => [
        'container' => 'div_chart_three',
        'color' => '#65dd65',
        'xAxis' => [
            'template' => '#year#',
            'title' => _l('Last 10 years'),
        ],
    ],
];

if (!empty($stat_view) && !empty($list_id_product_attribute))
{
    switch ($stat_view) {
        case 'combination_quantity':
            ## Y title
            $dhtmlxChart_yAxis_title = _l('Sales');
            ## 30 jours
            for ($i = 30; $i >= 0; --$i)
            {
                $sql = 'SELECT sum(od.product_quantity) AS total, DAY(o.date_add) as day 
                            FROM '._DB_PREFIX_.'orders o
                            LEFT JOIN '._DB_PREFIX_.'order_detail od ON (o.id_order = od.id_order)
                            WHERE o.valid = 1
                            AND od.product_attribute_id IN ('.pInSQL($list_id_product_attribute).')
                            AND DATE(o.date_add) = DATE_ADD(CURDATE(), INTERVAL -'.(int) $i.' DAY) 
                            AND od.id_shop IN ('.pInSQL(SCI::getSelectedShopActionList(true)).')';
                $result = Db::getInstance()->getRow($sql);
                $chart_data['chart_one'][] = [
                    'sales' => (float) $result['total'],
                    'days' => (string) $result['day'],
                ];
            }

            ## 24 mois
            $dateNow = new DateTime();
            $dateNow->modify('-24 month');
            for ($i = 0; $i <= 24; ++$i)
            {
                $sql = 'SELECT sum(od.product_quantity) AS qty, sum(od.`product_quantity_refunded`) AS refunded 
                            FROM '._DB_PREFIX_.'orders o
                            LEFT JOIN '._DB_PREFIX_.'order_detail od ON (o.id_order = od.id_order)
                            WHERE o.valid = 1
                            AND od.product_attribute_id IN ('.pInSQL($list_id_product_attribute).')
                            AND YEAR(o.date_add) = '.(int) $dateNow->format('Y').'
                            AND MONTH(o.date_add) = '.(int) $dateNow->format('n').
                            ' AND o.id_shop IN ('.pInSQL(SCI::getSelectedShopActionList(true)).')';
                $result = Db::getInstance()->getRow($sql);
                $chart_data['chart_two'][] = [
                    'sales' => (float) ($result['qty'] - $result['refunded']),
                    'month' => $dateNow->format('m'), ## m => month with 0
                ];

                $dateNow->modify('+1 month');
            }
            $dateNow = null;

            ## 10 ans
            $startYear = date('Y') - 10;
            $currentMonth = date('m');
            for ($i = $startYear; $i <= $startYear + 10; ++$i)
            {
                $sql = 'SELECT sum(od.product_quantity) AS total 
                            FROM '._DB_PREFIX_.'orders o
                            LEFT JOIN '._DB_PREFIX_.'order_detail od ON (o.id_order = od.id_order)
                            WHERE o.valid = 1
                            AND od.product_attribute_id IN ('.pInSQL($list_id_product_attribute).')
                            AND YEAR(o.date_add) = '.(int) $i.
                            ' AND od.id_shop IN ('.pInSQL(SCI::getSelectedShopActionList(true)).')';
                $result = Db::getInstance()->getValue($sql);
                $chart_data['chart_three'][] = [
                    'sales' => (float) $result,
                    'year' => $i,
                ];
            }
            break;
        case 'combination_total_price':
            ## Y title
            $dhtmlxChart_yAxis_title = sprintf('%s (%s)', _l('Sales'), _l('Total combinations excl. tax'));
            ## 30 jours
            for ($i = 30; $i >= 0; --$i)
            {
                $sql = 'SELECT sum(od.`total_price_tax_excl`'.(version_compare(_PS_VERSION_, '1.7.7.0', '>=') ? '-od.`total_refunded_tax_excl`' : '').') AS total, DAY(o.date_add) as day 
                            FROM '._DB_PREFIX_.'orders o
                            LEFT JOIN '._DB_PREFIX_.'order_detail od ON (o.id_order = od.id_order)
                            WHERE o.valid = 1
                            AND od.product_attribute_id IN ('.pInSQL($list_id_product_attribute).')
                            AND DATE(o.date_add) = DATE_ADD(CURDATE(), INTERVAL -'.(int) $i.' DAY) 
                            AND od.id_shop IN ('.pInSQL(SCI::getSelectedShopActionList(true)).')';
                $result = Db::getInstance()->getRow($sql);
                $chart_data['chart_one'][] = [
                    'sales' => (float) $result['total'],
                    'days' => (string) $result['day'],
                ];
            }

            ## 24 mois
            $dateNow = new DateTime();
            $dateNow->modify('-24 month');
            for ($i = 0; $i <= 24; ++$i)
            {
                $sql = 'SELECT sum(od.`total_price_tax_excl`'.(version_compare(_PS_VERSION_, '1.7.7.0', '>=') ? '-od.`total_refunded_tax_excl`' : '').') AS total 
                            FROM '._DB_PREFIX_.'orders o
                            LEFT JOIN '._DB_PREFIX_.'order_detail od ON (o.id_order = od.id_order AND o.id_shop = od.id_shop)
                            WHERE o.valid = 1
                            AND od.product_attribute_id IN ('.pInSQL($list_id_product_attribute).')
                            AND YEAR(o.date_add) = '.(int) $dateNow->format('Y').'
                            AND MONTH(o.date_add) = '.(int) $dateNow->format('n').'
                            AND o.id_shop IN ('.pInSQL(SCI::getSelectedShopActionList(true)).')';
                $result = Db::getInstance()->getValue($sql);
                $chart_data['chart_two'][] = [
                    'sales' => (float) $result,
                    'month' => (int) $dateNow->format('m'), ## m => month with 0
                ];

                $dateNow->modify('+1 month');
            }
            $dateNow = null;

            ## 10 ans
            $startYear = date('Y') - 10;
            $currentMonth = date('m');
            for ($i = $startYear; $i <= $startYear + 10; ++$i)
            {
                $sql = 'SELECT sum(od.`total_price_tax_excl`'.(version_compare(_PS_VERSION_, '1.7.7.0', '>=') ? '-od.`total_refunded_tax_excl`' : '').') AS total 
                            FROM '._DB_PREFIX_.'orders o
                            LEFT JOIN '._DB_PREFIX_.'order_detail od ON (o.id_order = od.id_order)
                            WHERE o.valid = 1
                            AND od.product_attribute_id IN ('.pInSQL($list_id_product_attribute).')
                            AND YEAR(o.date_add) = '.(int) $i.
                            ' AND od.id_shop IN ('.pInSQL(SCI::getSelectedShopActionList(true)).')';
                $result = Db::getInstance()->getValue($sql);
                $chart_data['chart_three'][] = [
                    'sales' => (float) $result,
                    'year' => $i,
                ];
            }
            break;
        case 'sales_margin':
            ## Y title
            $dhtmlxChart_yAxis_title = _l('Sales margin excl. tax');
            ## 30 jours
            for ($i = 30; $i >= 0; --$i)
            {
                $sql = 'SELECT (sum(od.total_price_tax_excl)-SUM(od.original_wholesale_price)) AS total,DAY(o.date_add) as day 
                        FROM '._DB_PREFIX_.'orders o
                        LEFT JOIN '._DB_PREFIX_.'order_detail od ON (o.id_order = od.id_order)
                        WHERE o.valid = 1
                        AND od.product_attribute_id IN ('.pInSQL($list_id_product_attribute).')
                        AND DATE(o.date_add) = DATE_ADD(CURDATE(), INTERVAL -'.(int) $i.' DAY)
                        AND od.id_shop IN ('.pInSQL(SCI::getSelectedShopActionList(true)).')';
                $result = Db::getInstance()->getRow($sql);
                $chart_data['chart_one'][] = [
                    'sales' => (float) $result['total'],
                    'days' => (string) $result['day'],
                ];
            }

            ## 24 mois
            $dateNow = new DateTime();
            $dateNow->modify('-24 month');
            for ($i = 0; $i <= 24; ++$i)
            {
                $sql = 'SELECT SUM((od.`total_price_tax_excl`'.(version_compare(_PS_VERSION_, '1.7.7.0', '>=') ? '-od.`total_refunded_tax_excl`' : '').')-(od.`original_wholesale_price`*(od.`product_quantity`-od.`product_quantity_refunded`))) AS total
                            FROM '._DB_PREFIX_.'orders o
                            LEFT JOIN '._DB_PREFIX_.'order_detail od ON (o.id_order = od.id_order AND o.id_shop = od.id_shop)
                            WHERE o.valid = 1
                            AND od.product_attribute_id IN ('.pInSQL($list_id_product_attribute).')
                            AND YEAR(o.date_add) = '.(int) $dateNow->format('Y').'
                            AND MONTH(o.date_add) = '.(int) $dateNow->format('n').'
                            AND o.id_shop IN ('.pInSQL(SCI::getSelectedShopActionList(true)).')';
                $result = Db::getInstance()->getValue($sql);
                $chart_data['chart_two'][] = [
                    'sales' => (float) $result,
                    'month' => $dateNow->format('m'),  ## m => month with 0
                ];

                $dateNow->modify('+1 month');
            }
            $dateNow = null;

            ## 10 ans
            $startYear = date('Y') - 10;
            $currentMonth = date('m');
            for ($i = $startYear; $i <= $startYear + 10; ++$i)
            {
                $sql = 'SELECT (sum(od.total_price_tax_excl)-SUM(od.original_wholesale_price)) AS total 
                            FROM '._DB_PREFIX_.'orders o
                            LEFT JOIN '._DB_PREFIX_.'order_detail od ON (o.id_order = od.id_order)
                            WHERE o.valid = 1
                            AND od.product_attribute_id IN ('.pInSQL($list_id_product_attribute).')
                            AND YEAR(o.date_add) = '.(int) $i.'
                            AND od.id_shop IN ('.pInSQL(SCI::getSelectedShopActionList(true)).')';
                $result = Db::getInstance()->getValue($sql);
                $chart_data['chart_three'][] = [
                    'sales' => (float) $result,
                    'year' => $i,
                ];
            }
            break;
    }
}
?>
<style>
    #all_charts {
        display: flex;
        flex-direction: column;
        -ms-flex-direction: column;
        -webkit-flex-direction: column;
        min-height: 100%;
        justify-content: space-between;
        -webkit-justify-content: space-between;
    }

    #all_charts .chart {
        height: 250px;
        display: block;
    }
</style>
<div id="all_charts">
    <?php foreach ($dhtmlxChart_types as $row)
{
    ## setting div for each Chart
    echo '<div id="'.$row['container'].'" class="chart"></div>'."\r\n\t";
} ?>
</div>
<script>
    <?php foreach ($dhtmlxChart_types as $var_name => $content)
{
    ## setting param for each Chart
    $dhtmlxChart_init['container'] = $content['container'];
    $dhtmlxChart_init['color'] = $content['color'];
    $dhtmlxChart_init['xAxis']['template'] = $content['xAxis']['template'];
    $dhtmlxChart_init['xAxis']['title'] = $content['xAxis']['title'];
    $dhtmlxChart_init['yAxis']['title'] = $dhtmlxChart_yAxis_title;
    ## setting Chart
    echo 'let '.$var_name.' = new dhtmlXChart('.json_encode($dhtmlxChart_init).');'."\r\n\t";
    ## parsing data
    echo $var_name.'.parse('.(array_key_exists($var_name, $chart_data) ? json_encode($chart_data[$var_name]) : []).',"json");'."\n\n\t";
} ?>
</script>



