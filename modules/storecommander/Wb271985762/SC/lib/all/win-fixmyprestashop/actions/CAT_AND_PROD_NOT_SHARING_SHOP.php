<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

$post_action = Tools::getValue('action');
if (!empty($post_action) && $post_action == 'do_check')
{
    $id_lang = (int) Tools::getValue('id_lang', Configuration::get('PS_LANG_DEFAULT'));
    $sql = 'SELECT id_category, id_product
        FROM 
        (
        SELECT cp.id_category, cp.id_product, SUM(IF(cs.id_shop = ps.id_shop and cs.id_shop,1,0)) AS compte
        FROM '._DB_PREFIX_.'category_product cp
        LEFT JOIN '._DB_PREFIX_.'category_shop cs ON cp.id_category = cs.id_category 
        LEFT JOIN '._DB_PREFIX_.'product_shop ps ON cp.id_product = ps.id_product 
        GROUP BY cp.id_category, cp.id_product
        ) 
        AS cnt
        WHERE cnt.compte = 0 LIMIT 1500';
    $res = Db::getInstance()->executeS($sql);

    $content = '';
    $content_js = '';
    $results = 'OK';
    if (!empty($res))
    {
        $results = 'KO';
        ob_start(); ?>
        <script>
            var fix_PCNotSharingShop = {
                id: 'CAT_AND_PROD_NOT_SHARING_SHOP',
            }
            fix_PCNotSharingShop.table = 'table_'+fix_PCNotSharingShop.id;
            fix_PCNotSharingShop.toolbar = dhxlSCExtCheck.tabbar.cells(fix_PCNotSharingShop.table).attachToolbar();
            fix_PCNotSharingShop.toolbar.setIconset('awesome');
            fix_PCNotSharingShop.toolbar.loadStruct([
                {id: 'selectall', type: 'button', title: '<?php echo _l('Select all'); ?>', img: 'fa fa-bolt yellow', imgdis: 'fa fa-bolt yellow'},
                {id: 'delete', type: 'button', title: '<?php echo _l('Delete associations'); ?>', img: 'fa fa-minus-circle red', imgdis: 'fa fa-minus-circle red'},
            ])
            fix_PCNotSharingShop.toolbar.attachEvent("onClick", function (id) {
                switch (id) {
                    case 'selectall':
                        fix_PCNotSharingShop.grid.selectAll();
                        getGridStat_PCNotSharingShop();
                        break;
                    case 'delete':
                        // deletePCNotSharingShop()
                        break;
                }
            });
        
            fix_PCNotSharingShop.grid = dhxlSCExtCheck.tabbar.cells(fix_PCNotSharingShop.table).attachGrid();
            fix_PCNotSharingShop.grid.enableSmartRendering(true);
            fix_PCNotSharingShop.grid.enableMultiselect(true);

            fix_PCNotSharingShop.grid._grid_data = {
                head: [
                    {width: 40, align: 'left', type: 'ro', sort: 'int', value: 'ID <?php echo _l('Product'); ?>', filter: '#numeric_filter'},
                    {width: 150, align: 'left', type: 'ro', sort: 'str', value: '<?php echo _l('Product'); ?>', filter: '#text_filter'},
                    {width: 40, align: 'left', type: 'ro', sort: 'int', value: 'ID <?php echo _l('Category'); ?>', filter: '#numeric_filter'},
                    {width: 150, align: 'left', type: 'ro', sort: 'str', value: '<?php echo _l('Category'); ?>', filter: '#text_filter'}
                ],
                rows: []
            };

            <?php
            $rowsJson = [];
        foreach ($res as $row)
        {
            $product = new Product($row['id_product'], false, (int) $id_lang);
            $category = new Category($row['id_category'], (int) $id_lang);
            $rowsJson[] = [
                    'id' => $row['id_product'].'_'.$row['id_category'],
                    'data' => [
                        (int) $row['id_product'],
                        $product->name,
                        (int) $row['id_category'],
                        $category->name,
                    ],
                ];
        } ?>

            fix_PCNotSharingShop.grid._grid_data.rows = <?php echo json_encode($rowsJson); ?>;
            fix_PCNotSharingShop.grid.parse(fix_PCNotSharingShop.grid._grid_data, 'json')
            fix_PCNotSharingShop.grid.attachHeader(fix_PCNotSharingShop.grid._grid_data.head.map(item => item.filter));

            sbPCNotSharingShop=dhxlSCExtCheck.tabbar.cells(fix_PCNotSharingShop.table).attachStatusBar();
            function getGridStat_PCNotSharingShop(){
                var filteredRows=fix_PCNotSharingShop.grid.getRowsNum();
                var selectedRows=(fix_PCNotSharingShop.grid.getSelectedRowId()?fix_PCNotSharingShop.grid.getSelectedRowId().split(',').length:0);
                sbPCNotSharingShop.setText('<?php echo count($res).' '._l('Errors'); ?>'+" - <?php echo _l('Filter')._l(':'); ?> "+filteredRows+" - <?php echo _l('Selection')._l(':'); ?> "+selectedRows);
            }
            fix_PCNotSharingShop.grid.attachEvent("onFilterEnd", function(elements){
                getGridStat_PCNotSharingShop();
            });
            fix_PCNotSharingShop.grid.attachEvent("onSelectStateChanged", function(id){
                getGridStat_PCNotSharingShop();
            });
            getGridStat_PCNotSharingShop();
            
            function deletePCNotSharingShop()
            {
                const rowIds = fix_PCNotSharingShop.grid.getSelectedRowId();
                if(!!rowIds)
                {
                    $.post('index.php?ajax=1&act=all_win-fixmyprestashop_actions&check='+fix_PCNotSharingShop.id+'&id_lang='+SC_ID_LANG, { action: 'delete_association', ids: rowIds}, function(){
                        dhxlSCExtCheck.tabbar.tabs(fix_PCNotSharingShop.table).close();

                        dhxlSCExtCheck.gridChecks.selectRowById(fix_PCNotSharingShop.id);
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
            'title' => _l('Not sharing shop'),
            'contentJs' => $content_js,
    ]);
}
elseif (!empty($post_action) && $post_action == 'delete_association')
{
    $post_ids = Tools::getValue('ids');
    if (!empty($post_ids))
    {
        $ids = explode(',', $post_ids);
        foreach ($ids as $id)
        {
            list($id_product, $id_category) = explode('_', $id);

            $sql = 'DELETE FROM '._DB_PREFIX_.'category_product WHERE id_product = '.(int) $id_product.' AND id_category = '.(int) $id_category;
            $res = dbExecuteForeignKeyOff($sql);
        }
    }
}
