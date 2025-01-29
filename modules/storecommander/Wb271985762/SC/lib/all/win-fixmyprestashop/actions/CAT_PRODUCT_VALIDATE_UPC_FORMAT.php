<?php
if (!defined('STORE_COMMANDER')) { exit; }

$post_action = Tools::getValue('action');
$action_name = 'CAT_PRODUCT_VALIDATE_UPC_FORMAT';
$tab_title = _l('UPC not valid');

if (!empty($post_action) && $post_action == 'do_check')
{
    $id_lang = (int)Tools::getValue('id_lang');
    $res = array();
    $sql = 'SELECT DISTINCT p.id_product, pl.name, p.upc, ps.id_category_default, ps.active 
            FROM `'._DB_PREFIX_.'product` p
            LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (pl.id_product = p.id_product AND pl.id_lang = '.(int) $id_lang.')
            INNER JOIN '._DB_PREFIX_.'product_shop ps ON (ps.id_product = p.id_product AND ps.id_shop = p.id_shop_default)
            WHERE p.upc != ""
            AND p.upc NOT REGEXP "^[0-9]{12}$"
            ORDER BY p.id_product ASC 
            LIMIT 1500';
    $res = Db::getInstance()->executeS($sql);



    $content = '';
    $content_js = '';
    $results = 'OK';
    if (!empty($res) && count($res) > 0)
    {
        $forJson = [
            'rows' => []
        ];

        foreach($res as $row)
        {
            $forJson['rows'][] = [
                'id' => $row['id_category_default'].'-'.$row['id_product'],
                'data'=> [
                    $row['id_product'],
                    $row['upc'],
                    $row['name'],
                    (!empty($row['active']) ? _l('Yes') : _l('No'))
                ]
            ];
        }

        $results = 'KO';
        ob_start(); ?>
        <script>

            var tbProductBadFormatUPC = dhxlSCExtCheck.tabbar.cells("table_<?php echo $action_name; ?>").attachToolbar();
            tbProductBadFormatUPC.setIconset('awesome');
            var idProductBadFormatUPC = '';
            tbProductBadFormatUPC.addButton("gotocatalog", 0, "", 'fad fa-external-link green', 'fad fa-external-link green');
            tbProductBadFormatUPC.setItemToolTip('gotocatalog','<?php echo _l('Go to the product in catalog.'); ?>');
            tbProductBadFormatUPC.addButton("exportcsv", 0, "", 'fad fa-file-csv green', 'fad fa-file-csv green');
            tbProductBadFormatUPC.setItemToolTip('exportcsv','<?php echo _l('Export grid to clipboard in CSV format for MSExcel with tab delimiter.'); ?>');
            tbProductBadFormatUPC.attachEvent("onClick",
                function(id){
                    if (id=='gotocatalog')
                    {
                        if(idProductBadFormatUPC !== '') {
                            let url = "?page=cat_tree&open_cat_grid="+idProductBadFormatUPC;
                            window.open(url,'_blank');
                        }

                    }
                    if(id=='exportcsv') {
                        displayQuickExportWindow(gridProductBadFormatUPC,1);
                    }
                });

            var gridProductBadFormatUPC = dhxlSCExtCheck.tabbar.cells("table_<?php echo $action_name; ?>").attachGrid();
            gridProductBadFormatUPC.enableSmartRendering(true);
            gridProductBadFormatUPC.enableMultiselect(false);

            gridProductBadFormatUPC.setHeader("ID <?php echo _l('product'); ?>,<?php echo _l('UPC'); ?>,<?php echo _l('Name'); ?>,<?php echo _l('Active'); ?>");
            gridProductBadFormatUPC.setInitWidths("60,100,200,*");
            gridProductBadFormatUPC.setColAlign("left,left,left,left");
            gridProductBadFormatUPC.setColTypes("ro,ro,ro,ro");
            gridProductBadFormatUPC.setColSorting("int,str,str,str");
            gridProductBadFormatUPC.attachHeader("#numeric_filter,#text_filter,#text_filter,#select_filter");
            gridProductBadFormatUPC.init();

            gridProductBadFormatUPC.attachEvent('onRowSelect',function(id){
                idProductBadFormatUPC = id;
            });

            gridProductBadFormatUPC.json = <?php echo json_encode($forJson); ?>;
            gridProductBadFormatUPC.parse(gridProductBadFormatUPC.json, 'json');

            sbProductBadFormatUPC=dhxlSCExtCheck.tabbar.cells("table_<?php echo $action_name; ?>").attachStatusBar();
            function getGridStat_ProductBadFormatUPC(){
                var filteredRows=gridProductBadFormatUPC.getRowsNum();
                var selectedRows=(gridProductBadFormatUPC.getSelectedRowId()?gridProductBadFormatUPC.getSelectedRowId().split(',').length:0);
                sbProductBadFormatUPC.setText('<?php echo count($res).' '._l('Errors'); ?>'+" - <?php echo _l('Filter')._l(':'); ?> "+filteredRows+" - <?php echo _l('Selection')._l(':'); ?> "+selectedRows);
            }
            gridProductBadFormatUPC.attachEvent("onFilterEnd", function(elements){
                getGridStat_ProductBadFormatUPC();
            });
            gridProductBadFormatUPC.attachEvent("onSelectStateChanged", function(id){
                getGridStat_ProductBadFormatUPC();
            });
            getGridStat_ProductBadFormatUPC();
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
