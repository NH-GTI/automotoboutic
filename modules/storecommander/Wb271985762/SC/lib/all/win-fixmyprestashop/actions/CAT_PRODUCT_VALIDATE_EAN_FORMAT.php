<?php
if (!defined('STORE_COMMANDER')) { exit; }

$post_action = Tools::getValue('action');
$action_name = 'CAT_PRODUCT_VALIDATE_EAN_FORMAT';
$tab_title = _l('EAN not valid');

if (!empty($post_action) && $post_action == 'do_check')
{
    function regenerate_check_digit($EAN_12) {
        $sum=0;
        $digit_array = str_split($EAN_12,1);
        foreach ($digit_array as $k => $v)
        {
            $multiplier = ($k % 2) ? 3 : 1;
            $sum += $v * $multiplier;
        }
        $next_ten=ceil($sum/10)*10 ;
        $check_digit = $next_ten - $sum;

        return (int) $check_digit;
    }

    $id_lang = (int)Tools::getValue('id_lang');

    $res_bad_format = array();
    $sql_bad = 'SELECT DISTINCT p.id_product, pl.name, p.ean13, "BAD FORMAT (NOT 13 NUMERIC DIGIT)" as problem, ps.id_category_default, ps.active 
                FROM `'._DB_PREFIX_.'product` p
                INNER JOIN '._DB_PREFIX_.'product_lang pl ON (pl.id_product = p.id_product AND pl.id_lang = '.(int) $id_lang.')
                INNER JOIN '._DB_PREFIX_.'product_shop ps ON (ps.id_product = p.id_product AND ps.id_shop = p.id_shop_default)
                WHERE p.ean13 != "" 
                AND p.ean13 NOT REGEXP "^[0-9]{13}$"
                ORDER BY p.id_product ASC';
    $res_bad_format = Db::getInstance()->executeS($sql_bad);

    $res_good_format = array();
    $sql_good = 'SELECT DISTINCT p.id_product, pl.name, p.ean13, "WRONG CHECK DIGIT" as problem, ps.id_category_default, ps.active 
                FROM `'._DB_PREFIX_.'product` p
                INNER JOIN '._DB_PREFIX_.'product_lang pl ON (pl.id_product = p.id_product AND pl.id_lang = '.(int) $id_lang.')
                INNER JOIN '._DB_PREFIX_.'product_shop ps ON (ps.id_product = p.id_product AND ps.id_shop = p.id_shop_default)
                WHERE p.ean13 != "" 
                AND p.ean13 REGEXP "^[0-9]{13}$"
                ORDER BY p.id_product ASC';
    $res_good_format = Db::getInstance()->executeS($sql_good);

    $res_bad_check_digit = array();
    foreach ($res_good_format as $row) {
        $ean13 = $row['ean13'];
        $ean12 = substr($ean13, 0, 12);
        $ean13_last_digit = (int) substr($ean13, 12);

        if ($ean13_last_digit != regenerate_check_digit($ean12)) {
            $res_bad_check_digit[] = $row;
        }
    }

    $res = array_merge($res_bad_format, $res_bad_check_digit);

    $content = '';
    $content_js = '';
    $results = 'OK';
    if (!empty($res) && count($res) > 0)
    {
        $res = array_chunk($res, 1500);
        $res = $res[0];

        $forJson = [
            'rows' => []
        ];
        foreach($res as $row)
        {
            $forJson['rows'][] = [
                'id' => $row['id_category_default'].'-'.$row['id_product'],
                'data'=> [
                    $row['id_product'],
                    $row['ean13'],
                    $row['name'],
                    _l($row['problem']),
                    (!empty($row['active']) ? _l('Yes') : _l('No'))
                ]
            ];
        }

        $results = 'KO';
        ob_start(); ?>
        <script>

            var tbProductBadFormatEAN = dhxlSCExtCheck.tabbar.cells("table_<?php echo $action_name; ?>").attachToolbar();
            tbProductBadFormatEAN.setIconset('awesome');
            var idProductBadFormatEAN = '';
            tbProductBadFormatEAN.addButton("gotocatalog", 0, "", 'fad fa-external-link green', 'fad fa-external-link green');
            tbProductBadFormatEAN.setItemToolTip('gotocatalog','<?php echo _l('Go to the product in catalog.'); ?>');
            tbProductBadFormatEAN.addButton("exportcsv", 0, "", 'fad fa-file-csv green', 'fad fa-file-csv green');
            tbProductBadFormatEAN.setItemToolTip('exportcsv','<?php echo _l('Export grid to clipboard in CSV format for MSExcel with tab delimiter.'); ?>');
            tbProductBadFormatEAN.attachEvent("onClick",
                function(id){
                    if (id=='gotocatalog')
                    {
                        if(idProductBadFormatEAN !== '') {
                            let url = "?page=cat_tree&open_cat_grid="+idProductBadFormatEAN;
                            window.open(url,'_blank');
                        }

                    }
                    if(id=='exportcsv') {
                        displayQuickExportWindow(gridProductBadFormatEAN,1);
                    }
                });

            var gridProductBadFormatEAN = dhxlSCExtCheck.tabbar.cells("table_<?php echo $action_name; ?>").attachGrid();
            gridProductBadFormatEAN.enableSmartRendering(true);
            gridProductBadFormatEAN.enableMultiselect(false);

            gridProductBadFormatEAN.setHeader("ID <?php echo _l('product'); ?>,<?php echo _l('EAN'); ?>,<?php echo _l('Name'); ?>,<?php echo _l('Error'); ?>,<?php echo _l('Active'); ?>");
            gridProductBadFormatEAN.setInitWidths("60,100,200,150,*");
            gridProductBadFormatEAN.setColAlign("left,left,left,left,left");
            gridProductBadFormatEAN.setColTypes("ro,ro,ro,ro,ro");
            gridProductBadFormatEAN.setColSorting("int,str,str,str,str");
            gridProductBadFormatEAN.attachHeader("#numeric_filter,#text_filter,#text_filter,#select_filter,#select_filter");
            gridProductBadFormatEAN.init();

            gridProductBadFormatEAN.attachEvent('onRowSelect',function(id){
                idProductBadFormatEAN = id;
            });

            gridProductBadFormatEAN.json = <?php echo json_encode($forJson); ?>;
            gridProductBadFormatEAN.parse(gridProductBadFormatEAN.json, 'json');

            sbProductBadFormatEAN=dhxlSCExtCheck.tabbar.cells("table_<?php echo $action_name; ?>").attachStatusBar();
            function getGridStat_ProductBadFormatEAN(){
                var filteredRows=gridProductBadFormatEAN.getRowsNum();
                var selectedRows=(gridProductBadFormatEAN.getSelectedRowId()?gridProductBadFormatEAN.getSelectedRowId().split(',').length:0);
                sbProductBadFormatEAN.setText('<?php echo count($res).' '._l('Errors'); ?>'+" - <?php echo _l('Filter')._l(':'); ?> "+filteredRows+" - <?php echo _l('Selection')._l(':'); ?> "+selectedRows);
            }
            gridProductBadFormatEAN.attachEvent("onFilterEnd", function(elements){
                getGridStat_ProductBadFormatEAN();
            });
            gridProductBadFormatEAN.attachEvent("onSelectStateChanged", function(id){
                getGridStat_ProductBadFormatEAN();
            });
            getGridStat_ProductBadFormatEAN();
        </script>
        <?php
        $content_js = ob_get_clean();
    }
    echo json_encode(array(
        'results' => $results,
        'contentType' => 'grid',
        'content' => $content,
        'title' => $tab_title,
        'contentJs' => $content_js,
    ));
}