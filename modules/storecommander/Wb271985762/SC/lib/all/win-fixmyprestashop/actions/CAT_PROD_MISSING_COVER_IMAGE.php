<?php
if (!defined('STORE_COMMANDER')) { exit; }

$post_action = Tools::getValue('action');
if (!empty($post_action) && $post_action == 'do_check')
{
    $products = array();

    $sql = new DbQuery();
    $sql->select('ims.id_product')
        ->select('ims.id_shop')
        ->select('pl.name')
        ->select('s.name as name_shop')
        ->from('image_shop', 'ims')
        ->leftJoin('product_lang', 'pl', 'pl.id_product = ims.id_product AND pl.id_shop = ims.id_shop AND pl.id_lang='.(int) SCI::getConfigurationValue('PS_LANG_DEFAULT'))
        ->leftJoin('shop', 's', 's.id_shop = ims.id_shop')
        ->where('ims.id_product > 0')
        ->groupBy('ims.id_product')
        ->groupBy('ims.id_shop')
        ->having('COALESCE(SUM(ims.cover), 0) < COUNT(DISTINCT(ims.id_shop))')
        ->limit(1500);
    $products = Db::getInstance()->executeS($sql);

    $content = '';
    $content_js = '';
    $results = 'OK';
    if (!empty($products) && count($products) > 0)
    {

        $forJson = [
            'rows' => []
        ];
        foreach($products as $row)
        {
            $forJson['rows'][] = [
                'id' => $row['id_product'].'-'.$row['id_shop'],
                'data'=> [
                    $row['id_product'],
                    $row['name'],
                    $row['name_shop']
                ]
            ];
        }

        $results = 'KO';
        ob_start(); ?>
        <script>
    
            var tbMissingCoverImage = dhxlSCExtCheck.tabbar.cells("table_CAT_PROD_MISSING_COVER_IMAGE").attachToolbar();
            tbMissingCoverImage.setIconset('awesome');
            tbMissingCoverImage.addButton("selectall", 0, "", 'fa fa-bolt yellow', 'fa fa-bolt yellow');
            tbMissingCoverImage.setItemToolTip('selectall','<?php echo _l('Select all'); ?>');
            tbMissingCoverImage.addButton("put_cover", 0, "", 'fa fa-plus-circle green', 'fa fa-plus-circle green');
            tbMissingCoverImage.setItemToolTip('put_cover','<?php echo _l('Put first image on cover'); ?>');
            tbMissingCoverImage.attachEvent("onClick",
                function(id){
                    if (id=='selectall')
                    {
                        gridMissingCoverImage.selectAll();
                        getGridStat_MissingCoverImage();
                    }
                    if (id=='put_cover')
                    {
                        addMissingCoverImageMS()
                    }
                });
        
            var gridMissingCoverImage = dhxlSCExtCheck.tabbar.cells("table_CAT_PROD_MISSING_COVER_IMAGE").attachGrid();
            gridMissingCoverImage.enableSmartRendering(true);
            gridMissingCoverImage.enableMultiselect(true);
    
            gridMissingCoverImage.setHeader("ID,<?php echo _l('Name').','._l('Shop'); ?>");
            gridMissingCoverImage.setInitWidths("100,*,200");
            gridMissingCoverImage.setColAlign("left,left,left");
            gridMissingCoverImage.setColTypes("ro,ro,ro");
            gridMissingCoverImage.setColSorting("int,str,str");
            gridMissingCoverImage.attachHeader("#numeric_filter,#text_filter,#text_filter");
            gridMissingCoverImage.init();
    
            gridMissingCoverImage.json = <?php echo json_encode($forJson); ?>;
            gridMissingCoverImage.parse(gridMissingCoverImage.json, 'json');

            sbMissingCoverImage=dhxlSCExtCheck.tabbar.cells("table_CAT_PROD_MISSING_COVER_IMAGE").attachStatusBar();
            function getGridStat_MissingCoverImage(){
                var filteredRows=gridMissingCoverImage.getRowsNum();
                var selectedRows=(gridMissingCoverImage.getSelectedRowId()?gridMissingCoverImage.getSelectedRowId().split(',').length:0);
                sbMissingCoverImage.setText('<?php echo count($products).' '._l('Errors'); ?>'+" - <?php echo _l('Filter')._l(':'); ?> "+filteredRows+" - <?php echo _l('Selection')._l(':'); ?> "+selectedRows);
            }
            gridMissingCoverImage.attachEvent("onFilterEnd", function(elements){
                getGridStat_MissingCoverImage();
            });
            gridMissingCoverImage.attachEvent("onSelectStateChanged", function(id){
                getGridStat_MissingCoverImage();
            });
            getGridStat_MissingCoverImage();

            function addMissingCoverImageMS()
            {
                var selectedMissingCoverImages = gridMissingCoverImage.getSelectedRowId();
                if(selectedMissingCoverImages==null || selectedMissingCoverImages=="")
                    selectedMissingCoverImages = 0;
                if(selectedMissingCoverImages!="0")
                {
                    $.post("index.php?ajax=1&act=all_win-fixmyprestashop_actions&check=CAT_PROD_MISSING_COVER_IMAGE&id_lang="+SC_ID_LANG, { "action": "image_cover_ms", "ids": selectedMissingCoverImages}, function(data){
                        dhxlSCExtCheck.tabbar.tabs("table_CAT_PROD_MISSING_COVER_IMAGE").close();

                         dhxlSCExtCheck.gridChecks.selectRowById('CAT_PROD_MISSING_COVER_IMAGE');
                         doCheck(false);
                    });
                }
            }
        </script>
        <?php
        $content_js = ob_get_clean();
    }
    echo json_encode(array(
            'results' => $results,
            'contentType' => 'grid',
            'content' => $content,
            'title' => _l('Image cover'),
            'contentJs' => $content_js,
    ));
}
elseif (!empty($post_action) && $post_action == 'image_cover')
{
    $post_ids = Tools::getValue('ids');
    if (!empty($post_ids))
    {
        $ids = explode(',', $post_ids);
        foreach ($ids as $id)
        {
            $sql = 'SELECT id_image
                    FROM '._DB_PREFIX_."image
                    WHERE id_product = '".(int) $id."'
                    ORDER BY position ASC
                    LIMIT 1";
            $image_first = Db::getInstance()->executeS($sql);
            if (!empty($image_first[0]['id_image']))
            {
                $sql = 'UPDATE '._DB_PREFIX_."image SET cover = '1' WHERE id_image = '".(int) $image_first[0]['id_image']."'";
                $res = dbExecuteForeignKeyOff($sql);
            }
        }
    }
}
elseif (!empty($post_action) && $post_action == 'image_cover_ms')
{
    $post_ids = Tools::getValue('ids');
    if (!empty($post_ids))
    {
        $ids = explode(',', $post_ids);
        foreach ($ids as $id)
        {
            list($id_product, $id_shop) = explode('_', $id);

            $sql = 'SELECT ims.id_image, ims.id_shop
                    FROM '._DB_PREFIX_.'image_shop ims
                        INNER JOIN '._DB_PREFIX_."image i ON (i.id_image = ims.id_image)
                    WHERE i.id_product = '".(int) $id_product."'
                        AND ims.id_shop = '".(int) $id_shop."'
                    ORDER BY i.position ASC
                    LIMIT 1";
            $image_first = Db::getInstance()->executeS($sql);
            if (!empty($image_first[0]['id_image']) && !empty($image_first[0]['id_shop']))
            {
                $sql = 'UPDATE '._DB_PREFIX_."image_shop SET cover = '1' WHERE id_image = '".(int) $image_first[0]['id_image']."' AND id_shop = '".(int) $image_first[0]['id_shop']."'";
                $res = dbExecuteForeignKeyOff($sql);
            }
        }
    }
}
