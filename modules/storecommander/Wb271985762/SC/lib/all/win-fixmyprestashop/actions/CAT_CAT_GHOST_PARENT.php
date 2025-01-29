<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

$post_action = Tools::getValue('action');
if (!empty($post_action) && $post_action == 'do_check')
{
    $subSql = (new DbQuery())
        ->select('cc.`id_category`')
        ->from('category', 'cc')
    ;
    $sql = (new DbQuery())
        ->select('c.`id_category`')
        ->select('c.`id_parent`')
        ->select('cl.`name`')
        ->select('COUNT(*) AS nbUsed')
        ->from('category', 'c')
        ->leftJoin('category_product', 'cp', 'cp.`id_category` = c.`id_category`')
        ->leftJoin('category_lang', 'cl', 'cl.`id_category` = c.`id_category` AND cl.`id_lang` ='.(int) Configuration::get('PS_LANG_DEFAULT').' AND cl.`id_shop` = '.(int) SCI::getSelectedShop())
        ->where('c.`id_category` != 0')
        ->where('c.`id_parent` != 0')
        ->where('c.`id_parent` NOT IN ('.$subSql->build().')')
        ->groupBy('c.`id_category`')
        ->limit(1500)
    ;
    $res = Db::getInstance()->executeS($sql);

    $content = '';
    $content_js = '';
    $results = 'OK';
    if (!empty($res) && count($res) > 0)
    {
        $forJson = [
            'rows' => [],
        ];
        foreach ($res as $row)
        {
            $forJson['rows'][] = [
                'id' => $row['id_category'],
                'data' => [
                    $row['id_category'],
                    $row['id_parent'],
                    $row['name'],
                    ((int) $row['nbUsed'] > 0 ? _l('Yes') : _l('No')),
                ],
            ];
        }

        $results = 'KO';
        ob_start(); ?>
        <script>
    
            var tbChangeCategory = dhxlSCExtCheck.tabbar.cells("table_CAT_CAT_GHOST_PARENT").attachToolbar();
            tbChangeCategory.setIconset('awesome');
            tbChangeCategory.addButton("selectall", 0, "", 'fa fa-bolt yellow', 'fa fa-bolt yellow');
            tbChangeCategory.setItemToolTip('selectall','<?php echo _l('Select all'); ?>');
            tbChangeCategory.addButton("change", 0, "", 'fa fa-plus-circle green', 'fa fa-plus-circle green');
            tbChangeCategory.setItemToolTip('change','<?php echo _l('Associate to default category shop', 1); ?>');
            tbChangeCategory.attachEvent("onClick",
                function(id){
                    if (id=='selectall')
                    {
                        gridChangeCategory.selectAll();
                        getGridStat_ChangeCategory();
                    }
                    if (id=='change')
                    {
                        addChangeCategory()
                    }
                });
        
            var gridChangeCategory = dhxlSCExtCheck.tabbar.cells("table_CAT_CAT_GHOST_PARENT").attachGrid();
            gridChangeCategory.enableSmartRendering(true);
            gridChangeCategory.enableMultiselect(true);
    
            gridChangeCategory.setHeader("ID,<?php echo _l('ID ghost parent'); ?>,<?php echo _l('Name'); ?>,<?php echo _l('Used?'); ?>");
            gridChangeCategory.setInitWidths("100,110,110,50");
            gridChangeCategory.setColAlign("left,left,left,left");
            gridChangeCategory.setColTypes("ro,ro,ro,ro");
            gridChangeCategory.setColSorting("int,int,str,str");
            gridChangeCategory.attachHeader("#numeric_filter,#numeric_filter,#text_filter,#select_filter");
            gridChangeCategory.init();

            gridChangeCategory._json = <?php echo json_encode($forJson); ?>;

            gridChangeCategory.parse(gridChangeCategory._json, 'json');

            sbChangeCategory=dhxlSCExtCheck.tabbar.cells("table_CAT_CAT_GHOST_PARENT").attachStatusBar();
            function getGridStat_ChangeCategory(){
                var filteredRows=gridChangeCategory.getRowsNum();
                var selectedRows=(gridChangeCategory.getSelectedRowId()?gridChangeCategory.getSelectedRowId().split(',').length:0);
                sbChangeCategory.setText('<?php echo count($res).' '._l('Errors'); ?>'+" - <?php echo _l('Filter')._l(':'); ?> "+filteredRows+" - <?php echo _l('Selection')._l(':'); ?> "+selectedRows);
            }
            gridChangeCategory.attachEvent("onFilterEnd", function(elements){
                getGridStat_ChangeCategory();
            });
            gridChangeCategory.attachEvent("onSelectStateChanged", function(id){
                getGridStat_ChangeCategory();
            });
            getGridStat_ChangeCategory();

            function addChangeCategory()
            {
                var selectedChangeCategorys = gridChangeCategory.getSelectedRowId();
                if(selectedChangeCategorys==null || selectedChangeCategorys=="")
                    selectedChangeCategorys = 0;
                if(selectedChangeCategorys!="0")
                {
                    $.post("index.php?ajax=1&act=all_win-fixmyprestashop_actions&check=CAT_CAT_GHOST_PARENT&id_lang="+SC_ID_LANG, { "action": "change_categories", "ids": selectedChangeCategorys}, function(data){
                        dhxlSCExtCheck.tabbar.tabs("table_CAT_CAT_GHOST_PARENT").close();

                         dhxlSCExtCheck.gridChecks.selectRowById('CAT_CAT_GHOST_PARENT');
                         doCheck(false);
                    });
                }
            }
        </script>
        <?php $content_js = ob_get_clean();
    }
    echo json_encode([
            'results' => $results,
            'contentType' => 'grid',
            'content' => $content,
            'title' => _l('Parent category'),
            'contentJs' => $content_js,
    ]);
}
elseif (!empty($post_action) && $post_action == 'change_categories')
{
    $post_ids = Tools::getValue('ids');
    if (!empty($post_ids))
    {
        $ids = explode(',', $post_ids);
        foreach ($ids as $id)
        {
            $sql = (new DbQuery())
                ->select('s.id_category')
                ->from('category', 'c')
                ->rightJoin('shop', 's', 'c.id_shop_default = s.id_shop')
                ->where('c.id_category = '.(int) $id)
            ;
            $idCategory = Db::getInstance()->getValue($sql);
            if ($idCategory)
            {
                $category_id = $idCategory;
            } else {
                $category_id = SCI::getConfigurationValue('PS_HOME_CATEGORY');
            }

            $sql = 'UPDATE `'._DB_PREFIX_.'category` 
                    SET active = 0, 
                    id_parent = '.(int) $category_id.' 
                    WHERE id_category = '.(int) $id;
            dbExecuteForeignKeyOff($sql);
        }
    }
}
