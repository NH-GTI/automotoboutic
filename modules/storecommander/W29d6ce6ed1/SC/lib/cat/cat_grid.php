<?php
if (!defined('STORE_COMMANDER')) { exit; }
?>
<script type="text/javascript">
    cat_grid=cat_productPanel.attachGrid();
    cat_grid._name='grid';

    loadingtime = 0;

    <?php if (version_compare(_PS_VERSION_, '1.6.0.0', '>=') && _s('CAT_ADVANCEDSTOCK_DEFAULT') == 1 && SCI::getConfigurationValue('PS_FORCE_ASM_NEW_PRODUCT') == 1 && _s('CAT_NOTICE_DEFAULT_CONFIG_ADVANCED_STOCK')) { ?>
        dhtmlx.message({text:'<?php echo _l('Caution: The option located in Prestashop > Products > New products use advanced stock management is set to Yes but in SC the preference Default type for Advanced Stock Management is set to disabled. To stop this alert: SC  > Tools > Settings > Alert.', 1); ?><br/><a href="javascript:disableThisNotice(\'CAT_NOTICE_DEFAULT_CONFIG_ADVANCED_STOCK\');"><?php echo _l('Disable this notice', 1); ?></a>',type:'error',expire:15000});
    <?php } ?>

    // UISettings
    cat_grid._uisettings_prefix='cat_grid_';
    cat_grid._uisettings_name=cat_grid._uisettings_prefix;
    cat_grid._uisettings_limited=true;
       cat_grid._first_loading=1;

    cat_grid.enableDragAndDrop(true);
    cat_grid.setDragBehavior('child');
    cat_grid.enableSmartRendering(true);
    <?php if (_s('APP_DISABLED_COLUMN_MOVE')) { ?>
    cat_grid.enableColumnMove(false);
    <?php } ?>
<?php
    if (!_s('CAT_PROD_GRID_DISABLE_IMAGE'))
    {
        ?>
    cat_grid.setAwaitedRowHeight(<?php echo getGridImageHeight(); ?>);
<?php
    }
    if (_s('CAT_PROD_GRID_TABULATION'))
    {
        ?>
cat_grid._key_events.k9_0_0=function(){
    cat_grid.editStop();
    cat_grid.selectCell(cat_grid.getRowIndex(cat_grid.getSelectedRowId())+1,cat_grid.getSelectedCellIndex(),true,false,true,true);
};
<?php
    }
?>
    cat_grid_tb=cat_productPanel.attachToolbar();
    cat_grid_tb.setIconset('awesome');


    var opts = [['cols123', 'obj', '<?php echo _l('Columns'); ?> 1 + 2 + 3', ''],
        ['cols12', 'obj', '<?php echo _l('Columns'); ?> 1 + 2', ''],
        ['cols23', 'obj', '<?php echo _l('Columns'); ?> 2 + 3', ''],
        ['cols234', 'obj', '<?php echo _l('Columns'); ?> 2 + 3 + 4', '']
    ];
    cat_grid_tb.addButtonSelect("layout", 100, "", opts, "fad fa-browser blue", "fad fa-browser blue",false,true);

    var gridnames=new Object();
    <?php if (_r('GRI_CAT_VIEW_GRID_LIGHT')) { ?>gridnames['grid_light']='<?php echo _l('Light view', 1); ?>';<?php } ?>
    <?php if (_r('GRI_CAT_VIEW_GRID_LARGE')) { ?>gridnames['grid_large']='<?php echo _l('Large view', 1); ?>';<?php } ?>
    <?php if (_r('GRI_CAT_VIEW_GRID_DELIVERY')) { ?>gridnames['grid_delivery']='<?php echo _l('Delivery', 1); ?>';<?php } ?>
    <?php if (_r('GRI_CAT_VIEW_GRID_PRICE')) { ?>gridnames['grid_price']='<?php echo _l('Prices', 1); ?>';<?php } ?>
    <?php if (_r('GRI_CAT_VIEW_GRID_DISCOUNT')) { ?>gridnames['grid_discount']='<?php echo _l('Discounts', 1); ?>';<?php } ?>
    <?php if (_r('GRI_CAT_VIEW_GRID_SEO')) { ?>gridnames['grid_seo']='<?php echo _l('SEO', 1); ?>';<?php } ?>
    <?php if (_r('GRI_CAT_VIEW_GRID_REFERENCE')) { ?>gridnames['grid_reference']='<?php echo _l('References', 1); ?>';<?php } ?>
    <?php if (_r('GRI_CAT_VIEW_GRID_DESCRIPTION') && (int) (_s('CAT_PROD_GRID_DESCRIPTION'))) { ?>gridnames['grid_description']='<?php echo _l('Descriptions', 1); ?>';<?php } ?>
    <?php if (_r('GRI_CAT_VIEW_GRID_DISCOUNT')) { ?>gridnames['grid_discount_2']='<?php echo _l('Discounts and margins', 1); ?>';<?php } ?>
    <?php if (_r('GRI_CAT_VIEW_GRID_PACK') && version_compare(_PS_VERSION_, '1.6.1.14', '>=')) { ?>gridnames['grid_pack']='<?php echo _l('Pack', 1); ?>';<?php } ?>
    <?php if (_s('APP_FEEDBIZ') && defined('SC_FeedBiz_ACTIVE') && SC_FeedBiz_ACTIVE == 1 && SCI::moduleIsInstalled('feedbiz')) { ?>
    gridnames['grid_feedbiz_option']='<?php echo _l('Feed.Biz product options', 1); ?>';
    <?php
            $market_place_allowed = SCI::getFeedBizAllowedMarketPlace();
            foreach ($market_place_allowed as $marketplace => $value)
            {
                if (!empty($value))
                {
                    echo "gridnames['grid_feedbiz_".$marketplace."_option']='"._l('Feed.Biz '.$marketplace.' options', 1)."';";
                }
            }
     }
    sc_ext::readCustomGridsConfigXML('gridnames');
    ?>
    var opts = new Array();
    $.each(gridnames, function(index, value) {
        opts[opts.length] = new Array(index, 'obj', value, '');
    });
    if (opts.length > 25)
        $('div.dhx_toolbar_poly_dhx_skyblue').addClass('dhx_toolbar_poly_dhx_skyblue_SCROLLBAR');
    gridView = (in_array('<?php echo _s('CAT_PROD_GRID_DEFAULT'); ?>',Object.keys(gridnames))?'<?php echo _s('CAT_PROD_GRID_DEFAULT'); ?>':opts[0][0]);
    // UISettings
    cat_grid._uisettings_name=cat_grid._uisettings_prefix+gridView;
    cat_grid_tb.addButtonSelect("gridview", 100, "<?php echo _l('Light view'); ?>", opts, "fad fa-ruler-triangle", "fad fa-ruler-triangle",false,true);
    cat_grid_tb.setItemToolTip('gridview','<?php echo _l('Grid view settings'); ?>');

    <?php
    $tmp = array();
    $clang = _l('Language');
    $optlang = '';
    foreach ($languages as $lang)
    {
        if ($lang['id_lang'] == $sc_agent->id_lang)
        {
            $clang = $lang['iso_code'];
            $optlang = 'cat_lang_'.$lang['iso_code'];
        }
        $tmp[] = "['cat_lang_".$lang['iso_code']."', 'obj', '".$lang['name']."', '']";
    }
    if (count($tmp) > 1)
    {
    echo 'var opts = ['.join(',', $tmp).'];'; ?>
    cat_grid_tb.addButtonSelect('lang',100,'<?php echo $clang; ?>',opts,'fad fa-flag blue','fad fa-flag blue',false,true);
    cat_grid_tb.setItemToolTip('lang','<?php echo _l('Select catalog language'); ?>');
    cat_grid_tb.setListOptionSelected('lang', '<?php echo $optlang; ?>');
    <?php
    }
    ?>
    var opts = [['filters_reset', 'obj', '<?php echo _l('Reset filters'); ?>', ''],
        ['separator1', 'sep', '', ''],
        ['filters_cols_show', 'obj', '<?php echo _l('Show all columns'); ?>', ''],
        ['filters_cols_hide', 'obj', '<?php echo _l('Hide all columns'); ?>', '']
    ];
    cat_grid_tb.addButtonSelect("filters",100, "", opts, "fa fa-filter", "fa fa-filter",false,true);
    cat_grid_tb.setItemToolTip('filters','<?php echo _l('Filter options'); ?>');
    cat_grid_tb.addButton("refresh",100, "", "fa fa-sync green", "fa fa-sync green");
    cat_grid_tb.setItemToolTip('refresh','<?php echo _l('Refresh grid'); ?>');
    cat_grid_tb.addButtonTwoState('lightNavigation',100, "", "fa fa-mouse-pointer", "fa fa-mouse-pointer");
    cat_grid_tb.setItemToolTip('lightNavigation','<?php echo _l('Light navigation (simple click on grid)'); ?>');
    <?php if (_r('ACT_CAT_ADD_PRODUCT_COMBI')) { ?>
        cat_grid_tb.addButton("add",100, "", "fa fa-plus-circle green", "fa fa-plus-circle green");
        cat_grid_tb.setItemToolTip('add','<?php echo _l('Create new product'); ?>');
        cat_grid_tb.addButton("add_ps",100, "", "fa fa-prestashop", "fa fa-prestashop");
        cat_grid_tb.setItemToolTip('add_ps','<?php echo _l('Create new product with the PrestaShop form'); ?>');
        <?php if (_r('ACT_CAT_DUPLICATE_PRODUCTS')) { ?>
            cat_grid_tb.addButton("duplicate",100, "", "fad fa-copy blue", "fad fa-copy blue");
            cat_grid_tb.setItemToolTip('duplicate', '<?php echo _l('Duplicate 1 to').' '._s('CAT_PROD_DUPLICATE').' '._l('products'); ?>');
        <?php } ?>
    <?php } ?>
    <?php if(version_compare(_PS_VERSION_, '1.7.2.0', '>=')){ ?>
        cat_grid_tb.addButton("refresh_physical_stocks",100, "", "fad fa-retweet-alt green", "fad fa-retweet-alt green");
        cat_grid_tb.setItemToolTip('refresh_physical_stocks','<?php echo _l('Refresh stocks'); ?>');
    <?php } ?>
    <?php if (_r('ACT_CAT_DELETE_PRODUCT_COMBI')) { ?>
        cat_grid_tb.addButton("delete",100, "", "fa fa-minus-circle red", "fa fa-minus-circle red");
        cat_grid_tb.setItemToolTip('delete','<?php echo _l('This will permanently delete the selected products everywhere in the shop.'); ?>');
    <?php } ?>
    cat_grid_tb.addButton("selectall",100, "", "fa fa-bolt yellow", "fad fa-bolt grey");
    cat_grid_tb.setItemToolTip('selectall','<?php echo _l('Select all products'); ?>');
    cat_grid_tb.addButtonTwoState("copytocateg",100, "", "fad fa-folder-plus green", "fad fa-folder-plus green");
    cat_grid_tb.setItemToolTip('copytocateg','<?php echo _l('If enabled: link products in the target category when you drag and drop products. Not enabled: move products'); ?>');
    cat_grid_tb.addButton("call_context_menu",100, "", "fa fa-euro-sign yellow", "fa fa-euro-sign yellow");
    cat_grid_tb.setItemToolTip('call_context_menu','<?php echo _l('Show context menu to mass edit', 1); ?>');
    <?php if (_r('ACT_CAT_BARCODE')) { ?>
        cat_grid_tb.addButton("barcode", 100, "", "fad fa-scanner yellow", "fad fa-scanner yellow");
        cat_grid_tb.setItemToolTip('barcode','<?php echo _l('Update quantity with barcode'); ?>');
    <?php } ?>
    <?php if (_r('ACT_CAT_FAST_EXPORT')) { ?>
        cat_grid_tb.addButton("exportcsv", 100, "", "fad fa-file-csv green", "fad fa-file-csv green");
        cat_grid_tb.setItemToolTip('exportcsv','<?php echo _l('Export grid to clipboard in CSV format for MSExcel with tab delimiter.'); ?>');
    <?php } ?>
    cat_grid_tb.addButton("setposition", 100, "", "fa fa-list-ol green", "fa fa-list-ol grey");
    cat_grid_tb.setItemToolTip('setposition','<?php echo _l('Save product positions in the grid as category positions'); ?>');
    cat_grid_tb.addButton("shuffleposition", 100, "", "fa fa-random", "fa fa-random");
    cat_grid_tb.setItemToolTip('shuffleposition','<?php echo _l('Shuffle positions', 1); ?>');
    cat_grid_tb.addButton("print", 100, "", "fad fa-print", "fad fa-print");
    cat_grid_tb.setItemToolTip('print','<?php echo _l('Print grid', 1); ?>');
    getTbSettingsButton(cat_grid_tb, {'grideditor':1,'settings':<?php echo (int)_r('MEN_TOO_SETTINGS'); ?>}, '',100);
    <?php if (SCAS) { ?>
        cat_grid_tb.addButton("help_scas", 1200, "", "fad fa-info-circle blue", "fad fa-info-circle blue");
        cat_grid_tb.setItemToolTip('help_scas','<?php echo _l('Show help for color codes used for quantities/warehouses management', 1); ?>');
    <?php } ?>
    cat_grid_tb.addButton("help", 1200, "", "fad fa-question-circle blue", "fad fa-question-circle blue");
    cat_grid_tb.setItemToolTip('help','<?php echo _l('Help'); ?>');

    var using_tb_context_menu = 0;

    function gridToolBarOnClick(id){
            if (id.substr(0,5)=='grid_'){
                <?php echo $pdt_toolbar_js_action; ?>
            }
            if (id=='help'){
                <?php echo "window.open('".getScExternalLink('support_catalog')."');"; ?>
            }
            if (id=='grideditor'){
                openWinGridEditor('type_products');
            }
            if (id=='settings'){
                openSettingsWindow('Catalog','Product');
            }
            if (id=='filters_reset')
            {
                for(var i=0,l=cat_grid.getColumnsNum();i<l;i++)
                {
                    if (cat_grid.getFilterElement(i)!=null) cat_grid.getFilterElement(i).value="";
                }
                cat_grid.filterByAll();
                cat_grid_tb.setListOptionSelected('filters','');
            }
            if (id=='filters_cols_show')
            {
                for(i=0,l=cat_grid.getColumnsNum() ; i < l ; i++)
                {
                    cat_grid.setColumnHidden(i,false);
                }
                cat_grid_tb.setListOptionSelected('filters','');
            }
            if (id=='filters_cols_hide')
            {
                idxProductID=cat_grid.getColIndexById('id');
                idxProductName=cat_grid.getColIndexById('name');
                idxProductReference=cat_grid.getColIndexById('reference');
                for(i=0 , l=cat_grid.getColumnsNum(); i < l ; i++)
                {
                    if (i!=idxProductID && i!=idxProductName && i!=idxProductReference)
                    {
                        cat_grid.setColumnHidden(i,true);
                    }else{
                        cat_grid.setColumnHidden(i,false);
                    }
                }
                cat_grid_tb.setListOptionSelected('filters','');
            }
            if(id=='refresh_physical_stocks'){
                $.post("index.php?ajax=1&act=cat_product_update&action=stocks&"+new Date().getTime(),{},function(){
                    displayProducts();
                });
            }
            flagLang=false; // changelang ; lang modified?
<?php
    $tmp = array();
    $clang = _l('Language');
    foreach ($languages as $lang)
    {
        echo '
            if (id==\'cat_lang_'.$lang['iso_code'].'\')
            {
                SC_ID_LANG='.$lang['id_lang'].';
                cat_grid_tb.setItemText(\'lang\',\''.$lang['iso_code'].'\');
                flagLang=true;
            }
';
    }
?>
            if (flagLang){
                if(cat.cells('a').isCollapsed())
                {
                    displayProducts();
                }
                else
                {
                    if(SCMS)
                        displayTree('');
                    else
                        displayTree('displayProducts()');

                    if (propertiesPanel=='images') {
                        displayImages();
                    }
                }
            }
            if (id=='refresh'){
                displayProducts();
            }
            if (id=='add'){
                if (catselection==0){
                    alert('<?php echo _l('You need to select a category.', 1); ?>');
                }else{
                    var newId = new Date().getTime();
                    newRow=new Array('','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
                    newRow=newRow.slice(0,cat_grid.getColumnsNum()-1);
                    idxID=cat_grid.getColIndexById('id');
                    idxName=cat_grid.getColIndexById('name');
                    idxActive=cat_grid.getColIndexById('active');
                    idxLinkRewrite=cat_grid.getColIndexById('link_rewrite');
                    idxReference=cat_grid.getColIndexById('reference');
                    idxSupplierReference=cat_grid.getColIndexById('supplier_reference');
                    idxQty=cat_grid.getColIndexById('quantity');
                    idxImage=cat_grid.getColIndexById('image');
                    idxManufacturer=cat_grid.getColIndexById('id_manufacturer');
                    idxSupplier=cat_grid.getColIndexById('id_supplier');
                    idxTax=cat_grid.getColIndexById('id_tax_rules_group');
                    newRow[idxID]=newId;
                    newRow[idxName]='new';
                    if (idxActive) newRow[idxActive]='<?php echo _s('CAT_PROD_CREA_ACTIVE'); ?>';
                    if (idxLinkRewrite) newRow[idxLinkRewrite]='product';
                    if (idxReference) newRow[idxReference]='<?php echo _s('CAT_PROD_CREA_REF'); ?>';
                    if (idxSupplierReference) newRow[idxSupplierReference]='<?php echo _s('CAT_PROD_CREA_SUPREF'); ?>';
                    if (idxManufacturer) newRow[idxManufacturer]='<?php echo _s('CAT_PROD_CREA_MANUFACTURER'); ?>';
                    if (idxSupplier) newRow[idxSupplier]='<?php echo _s('CAT_PROD_CREA_SUPPLIER'); ?>';
                    if (idxQty) newRow[idxQty]='<?php echo _s('CAT_PROD_CREA_QTY'); ?>';
                    if (idxTax) newRow[idxTax]='<?php echo _s('CAT_PROD_CREA_TAX') ? _s('CAT_PROD_CREA_TAX') : '-'; ?>';
<?php
                    if (file_exists(SC_PS_PATH_REL.'img/p/'.$user_lang_iso.'-default-'._s('CAT_PROD_GRID_IMAGE_SIZE').'.jpg'))
                    {
                        $defaultimg = '<img src="'.SC_PS_PATH_REL.'img/p/'.$user_lang_iso.'-default-'._s('CAT_PROD_GRID_IMAGE_SIZE').'.jpg"/>';
                    }
                    else
                    {
                        $defaultimg = '<i class="fad fa-file-image" aria-hidden="true" title="--"></i>';
                    }
?>
                    if (idxImage) newRow[idxImage]='<?php echo '-'.$defaultimg.'-'; ?>';

                    // INSERT
                    cat_grid.addRow(newId,newRow);
                    cat_grid.setRowHidden(newId, true);

                    var params = {
                        name: "cat_product_update_queue",
                        row: newId,
                        action: "insert",
                        params: {callback: "callbackProductUpdate('"+newId+"','insert','{newid}',{data});"}
                    };
                    // COLUMN VALUES
                    cat_grid.forEachCell(newId,function(cellObj,ind){
                        params.params[cat_grid.getColumnId(ind)] = cat_grid.cells(newId,ind).getValue();
                    });
                    params.params['id_lang'] = SC_ID_LANG;
                    if(segselection!=undefined && segselection!=null && segselection!="" && segselection!=0)
                        params.params['id_segment'] = segselection;
                    else if(catselection!=undefined && catselection!=null && catselection!="" && catselection!=0)
                        params.params['id_category'] = catselection;
                    // USER DATA
                    $.each(cat_grid.UserData.gridglobaluserdata.keys, function(i, key){
                        params.params[key] = cat_grid.UserData.gridglobaluserdata.values[i];
                    });

                    sendInsert(params,cat_productPanel);
                }
            }
            if (id=='add_ps'){
                if (catselection==0){
                    alert('<?php echo _l('You need to select a category before creating a product', 1); ?>');
                }else{
                    if (!dhxWins.isWindow("wNewProduct"))
                    {
                        <?php if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) { ?>
                        wNewProduct = dhxWins.createWindow("wNewProduct", 50, 50, 1460, $(window).height()-75);
                        <?php }
else
{ ?>
                        wNewProduct = dhxWins.createWindow("wNewProduct", 50, 50, 1000, $(window).height()-75);
                        <?php } ?>
                        wNewProduct.setText('<?php echo _l('Create the new product and close this window to refresh the grid', 1); ?>');
                        <?php if (version_compare(_PS_VERSION_, '1.7.0.0', '>='))
{
    $url = SC_PS_PATH_ADMIN_REL.'index.php?controller='.SC_MODULE_ADMIN_CONTROLLER_NAME.'&REDIRECTADMIN=1&subaction=addproduct&token='.$sc_agent->getPSToken(SC_MODULE_ADMIN_CONTROLLER_NAME); ?>
                        wNewProduct.attachURL("<?php echo $url; ?>");
                        <?php
}
    else
    { ?>
                        wNewProduct.attachURL("<?php echo SC_PS_PATH_ADMIN_REL; ?>index.php?tab=<?php echo version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? 'AdminProducts' : 'AdminCatalog'; ?>&addproduct&id_category="+catselection+"&token=<?php echo $sc_agent->getPSToken('AdminCatalog'); ?>");
                        <?php } ?>
                        pushOneUsage('grid-bo-link-adminproducts_addproduct','cat');
                        wNewProduct.attachEvent("onClose", function(win){
                                    displayProducts();
                                    return true;
                                });
                    }
                }
            }
            if (id=='duplicate'){
                if (catselection==0){
                    alert('<?php echo _l('You need to select a category before creating a product', 1); ?>');
                }else{
                    if (1)
                    {
                        if (cat_grid.getSelectedRowId()==null)
                        {
                            alert('<?php echo _l('Please select 1 to', 1).' '._s('CAT_PROD_DUPLICATE').' '._l('products', 1); ?>');
                        }else{
                            var arrSelRow=cat_grid.getSelectedRowId().split(',');
                            var nbSelRow=arrSelRow.length;
                            if (nbSelRow > <?php echo _s('CAT_PROD_DUPLICATE'); ?>) {
                                alert('<?php echo _l('Please select 1 to', 1).' '._s('CAT_PROD_DUPLICATE').' '._l('products', 1); ?>');
                                return false;
                            }
                            if (confirm('<?php echo _l('Do you want to copy images?', 1); ?>')){
                                url1='<?php echo SC_PS_PATH_ADMIN_REL; ?>index.php?tab=<?php echo version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? 'adminproducts' : 'AdminCatalog'; ?>&id_product=';
                                url2='&duplicateproduct&token=<?php echo $sc_agent->getPSToken('AdminCatalog'); ?>';
                                <?php if (SCMS && version_compare(_PS_VERSION_, '1.6.0.0', '>=')) { ?>
                                    if(shopselection!=undefined && shopselection!=null && shopselection!=0)
                                        url2 = url2 +'&setShopContext=s-'+shopselection;
                                <?php } ?>
                            }else{
                                url1='<?php echo SC_PS_PATH_ADMIN_REL; ?>index.php?tab=<?php echo version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? 'adminproducts' : 'AdminCatalog'; ?>&id_product=';
                                url2='&duplicateproduct&noimage=1&token=<?php echo $sc_agent->getPSToken('AdminCatalog'); ?>';
                                <?php if (SCMS && version_compare(_PS_VERSION_, '1.6.0.0', '>=')) { ?>
                                    if(shopselection!=undefined && shopselection!=null && shopselection!=0)
                                        url2 = url2 +'&setShopContext=s-'+shopselection;
                                <?php } ?>
                            }
                            for(i = 1 ; i <= nbSelRow ; i++)
                            {
                                var id_product = arrSelRow[i-1];
                                <?php if (version_compare(_PS_VERSION_, '1.5.0.0', '>=') && version_compare(_PS_VERSION_, '1.6.0.0', '<')) { ?>
                                $.post('index.php?ajax=1&act=cat_product_inshop',{'id_product':id_product},function(data){
                                    if (data!=undefined && data!='' && data!=null && data>0)
                                    {
                                        var id_product = data;
                                        <?php } ?>
                                        wDuplicateProduct=Array();
                                        wDuplicateProduct[i] = dhxWins.createWindow("wDuplicateProduct"+i, 50+i*15, 50+i*15, 1000, $(window).height()-75);
                                        wDuplicateProduct[i].setText('<?php echo _l('You can close this window when you get the confirmation of the duplication, don\'t forget to refresh the grid!', 1); ?>');
                                        wDuplicateProduct[i].attachURL(url1+id_product+url2);
                                        pausecomp(800);
                                        <?php if (version_compare(_PS_VERSION_, '1.5.0.0', '>=') && version_compare(_PS_VERSION_, '1.6.0.0', '<')) { ?>
                                    }
                                    else
                                    {
                                        var msg = '<?php echo _l('This product cannot be duplicated because of a Prestashop malfunction.', 1).'<br/><br/>'._l('The product must exist in the default store to be duplicated.', 1); ?>';
                                        dhtmlx.message({text:msg,type:'error',expire:10000});
                                    }
                                });
                                <?php } ?>
                            }
                        }
                    }
                }
            }
            if (id=="delete")
            {
                if (confirm('<?php echo _l('Permanently delete the selected products everywhere in the shop.', 1); ?>'))
                {
                    selection=cat_grid.getSelectedRowId();
                    ids=selection.split(',');
                    $.each(ids, function(num, rId)
                    {
                        var params =
                        {
                            name: "cat_product_update_queue",
                            row: rId,
                            action: "delete",
                            params: {},
                            callback: "callbackProductUpdate('"+rId+"','delete','"+rId+"');"
                        };
                        params.params = JSON.stringify(params.params);
                        cat_grid.setRowTextStyle(rId, "text-decoration: line-through;");
                        addInUpdateQueue(params,cat_grid);
                    });
                }
            }
            if (id=='selectall'){
              cat_grid.enableSmartRendering(false);
              cat_grid.selectAll();
              getGridStat();
            }
            if (id=='exportcsv'){
                displayQuickExportWindow(cat_grid,1,null,['image']);
            }
            if (id=='print'){
                cat_grid.printView();
            }
            if (id=='setposition'){
                <?php if (SCMS) { ?>
                    if(shopselection==0){
                        dhtmlx.message({text:'<?php echo _l('Products positions cannot be set when \'All shops\' is selected', 1); ?>',type:'error'});
                        return false;
                    }
                <?php } ?>
                idxPosition=cat_grid.getColIndexById('position');
                if (idxPosition && cat_grid.getRowsNum()>0 && catselection!=0)
                {
                    var positions='';
                    var idx=0;
                    cat_grid.forEachRow(function(id){
                            positions+=id+','+cat_grid.getRowIndex(id)+';';
                            idx++;
                        });
                    addProductInQueue(0, "position", null, {'positions':positions});
                }
            }
            if (id=='shuffleposition'){
                if (confirm('<?php echo _l('Are you sure you want to randomize products positions ?', 1); ?>','')){
                    if (cat_grid.getRowsNum()>0)
                    {
                        var positionsString='';
                        var positionsArray =new Array();
                        cat_grid.forEachRow(function(id){
                            positionsArray[cat_grid.getRowIndex(id)]=id;
                        });

                        positionsShuffledArray = shuffle(positionsArray);
                        positionsString = positionsShuffledArray.map(function(key,value){
                            return key+','+value;
                        }).join(';');

                        $.post("index.php?ajax=1&act=cat_productsort_update&action=position&"+new Date().getTime(),{ id_category: catselection, positions: positionsString },function(){
                            displayProducts();                });
                    }
                }
            }
            <?php if (SCAS) { ?>
            if (id=='help_scas'){
                displayHelpWindow('grid','cat_win-help_scas_xml',600,190,"<?php echo _l('Help for quantities / warehouses management'); ?>");
            }
            <?php } ?>
            if (id=='cols123')
            {
                cat.cells("a").expand();
                cat.cells("a").setWidth(300);
                cat.cells("b").expand();
                dhxLayout.cells('b').expand();
                dhxLayout.cells('b').setWidth(500);
            }
            if (id=='cols12')
            {
                cat.cells("a").expand();
                cat.cells("a").setWidth($(document).width()/3);
                cat.cells("b").expand();
                dhxLayout.cells('b').collapse();
            }
            if (id=='cols23')
            {
                if(catselection==0 || catselection==undefined || catselection=="" || catselection==null)
                {
                    catselection="2";
                    cat_tree.openItem(catselection);
                    cat_tree.selectItem(catselection);
                    displayProducts();
                }

                cat.cells("a").collapse();
                cat.cells("b").expand();
                cat.cells("b").setWidth($(document).width()/2);
                dhxLayout.cells('b').expand();
                dhxLayout.cells('b').setWidth($(document).width()/2);
            }
            if (id=='cols234')
            {
                if(is_prop_4columns && prop_4column_layout!=undefined && prop_4column_layout!=null && prop_4column_layout!=0 && prop_4column_layout!="")
                {
                    if(catselection==0 || catselection==undefined || catselection=="" || catselection==null)
                    {
                        catselection="2";
                        cat_tree.openItem(catselection);
                        cat_tree.selectItem(catselection);
                        displayProducts();
                    }

                    var w = $(document).width()/3;
                    cat.cells("a").collapse();
                    cat.cells("b").expand();
                    cat.cells("b").setWidth(w);
                    dhxLayout.cells('b').expand();
                    dhxLayout.cells('b').setWidth(w+w);
                    prop_4column_layout.cells('b').expand();
                    prop_4column_layout.cells('b').setWidth(w);
                }
                else
                {
                    dhtmlx.message({text:'<?php echo _l('There is no 4th column for this property', 1); ?>',type:'error'});
                }
            }
        if(id=='call_context_menu'){
            let icon_off_top = parseInt($('.dhx_toolbar_material.dhxtoolbar_icons_18 div.dhx_toolbar_btn > i.fa.fa-euro-sign.yellow').offset().top+24);
            let icon_off_left = parseInt($('.dhx_toolbar_material.dhxtoolbar_icons_18 div.dhx_toolbar_btn > i.fa.fa-euro-sign.yellow').offset().left);
            selection=cat_grid.getSelectedRowId();
            if(selection !== 'null' && selection !== '' && selection !== null) {
                using_tb_context_menu = 1;
                window.setTimeout(function () {
                    cat_cmenu.hideItem('object');
                    cat_cmenu.hideItem('goshop');
                    <?php if (_r('ACT_CAT_CONTEXTMENU_EDIT_BO')) { ?>
                    cat_cmenu.hideItem('gopsbo');
                    <?php } ?>
                    cat_cmenu.hideItem('update_yes');
                    cat_cmenu.hideItem('update_no');
                    cat_cmenu.hideItem('delete_from_manual_segment');
                    cat_cmenu.hideItem('copy');
                    cat_cmenu.hideItem('paste');
                    cat_cmenu.hideItem('massupdate_quantity');
                    cat_cmenu.hideItem('massupdate_quantitya');
                    cat_cmenu.hideItem('massupdate_round_price');
                    cat_cmenu.showContextMenu(icon_off_left, icon_off_top);
                }, 1);
            } else {
                alert('<?php echo _l('Please select products', 1); ?>');
            }
        }
            if(id=='barcode')
            {
                if (!dhxWins.isWindow("wBarCodeImporter"))
                {
                    wBarCodeImporter = dhxWins.createWindow("wBarCodeImporter", 50, 50, 900, $(window).height()-100);
                    wBarCodeImporter.center();
                    wBarCodeImporter.setText("<?php echo _l('Update quantity with barcode'); ?>");
                    $.get("index.php?ajax=1&act=cat_win-barcode_init&id_lang="+SC_ID_LANG,function(data){
                        $('#jsExecute').html(data);
                    });
                    wBarCodeImporter.attachEvent("onClose", function(win){
                        wBarCodeImporter.hide();
                        return false;
                    });
                }else{
                    wBarCodeImporter.show();
                }
            }
        }
    cat_grid_tb.attachEvent("onClick",gridToolBarOnClick);
    cat_grid_tb.attachEvent("onStateChange", function(id,state){
            if (id=='lightNavigation'){
                if (state) {
                    cat_grid.enableLightMouseNavigation(true);
                    lightMouseNavigation=1;
                }else{
                    cat_grid.enableLightMouseNavigation(false);
                    lightMouseNavigation=0;
                }
            }
            if (id=='copytocateg'){
                if (state) {
                  copytocateg=true;
                }else{
                  copytocateg=false;
                }
            }
        });

    cat_grid.setImagePath('lib/js/imgs/');
    cat_grid.setDateFormat("%Y-%m-%d %H:%i:%s","%Y-%m-%d %H:%i:%s");
    cat_grid.enableMultiselect(true);
    cat_grid_sb=cat_productPanel.attachStatusBar();
    gridToolBarOnClick(gridView);


    cat_grid.attachEvent("onMouseOver", function(rId,cInd){
        if(cat_grid.getColType(cInd) == 'wysiwyg') {
            return false;
        }
        return true;
    });

    cat_grid.attachEvent("onRowDblClicked", function(rId,cInd){
        if(cat_grid.getUserData(rId, 'isAdvancedPack') !== null
            && [cat_grid.getColIndexById('price'),
                cat_grid.getColIndexById('price_inc_tax'),
                cat_grid.getColIndexById('ecotax'),
                cat_grid.getColIndexById('id_tax_rules_group'),
                cat_grid.getColIndexById('unit_price_ratio'),
                cat_grid.getColIndexById('unit_price_inc_tax')].indexOf(cInd) > -1
        ) {
            dhtmlx.message({text:'<?php echo _l('This field cannot be modified because it is an Advanced Pack product.', true); ?>', type:'error'});
            return false;
        }

        if(cat_grid.getColType(cInd) == 'wysiwyg') {
            openWysiwygWindow(cat_grid,rId, cInd);
            return false;
        }
    <?php if (SCAS && _r('ACT_CAT_ADVANCED_STOCK_MANAGEMENT')) { ?>
        idxQtyMvt=cat_grid.getColIndexById('quantityupdate');
        idxASM=cat_grid.getColIndexById('advanced_stock_management');
        idxQtyPhy=cat_grid.getColIndexById('quantity_physical');
        idxQtyUse=cat_grid.getColIndexById('quantity_usable');
        idxQtyRea=cat_grid.getColIndexById('quantity_real');

        if(cInd==idxQtyMvt || cInd==idxQtyPhy || cInd==idxQtyUse || cInd==idxQtyRea)
        {
            if(cat_grid.cells(rId,idxASM).getValue()=="2" && cat_grid.cells(rId,idxQtyMvt).getAttribute('bgColor')=="#d7f7bf")
            {
                if (!dhxWins.isWindow("wStockMvt"))
                {
                    wStockMvt = dhxWins.createWindow("wStockMvt", ($(window).width()/2-215), 50, 430, 745);
                    wStockMvt.setText("<?php echo _l('Create a new stock movement'); ?>");
                    wStockMvt.show();
                    $.get("index.php?ajax=1&act=cat_win-stockmvt_choose_init&id_product="+rId+"&id_lang="+SC_ID_LANG,function(data){
                            $('#jsExecute').html(data);
                        });
                }else{
                    wStockMvt.setDimension(430, 745);
                    wStockMvt.show();
                    $.get("index.php?ajax=1&act=cat_win-stockmvt_choose_init&id_product="+rId+"&id_lang="+SC_ID_LANG,function(data){
                            $('#jsExecute').html(data);
                        });
                }

                return false;
            }
        }

    <?php } ?>
        return true;
    });

    cat_grid.attachEvent("onBeforeDrag",function(idsource){
        <?php if (SCMS) { ?> if(shopselection==0){ dhtmlx.message({text:'<?php echo _l('When \'All shops\' is selected, associate products to categories using the Categories grid in Properties.', 1); ?>',type:'error'}); return false; }<?php } ?>
            if (cat_grid.getSelectedRowId()==null) draggedProduct=idsource;
            if (cat_tree._dragBehavior!="child")
            {
                cat_tree.setDragBehavior("child");
                cat_tree._dragBehavior="child";
            }
            return true;
        });
    cat_grid.attachEvent("onDragIn",function(idsource,idtarget,sourceobject,targetobject){
        <?php if (SCMS) { ?> if(shopselection==0) return false; <?php } ?>
            if (sourceobject._name=="grid") return true;
            return false;
        });
    cat_grid.rowToDragElement=function(id){
          var text="";
          idxName=cat_grid.getColIndexById('name');
          if (cat_grid.getSelectedRowId()!=null)
          {
            var dragged=cat_grid.getSelectedRowId().split(',');
            if (dragged.length > 1){ // multi
                for (var i=0; i < dragged.length; i++)
                {
                    text += cat_grid.cells(dragged[i],idxName).getValue() + "<br/>";
                }
            }else{ // single
                text += cat_grid.cells(dragged,idxName).getValue() + "<br/>";
            }
          }else{ // single
                text += cat_grid.cells(draggedProduct,idxName).getValue() + "<br/>";
          }
          return text;
        }
    // multiedition context menu
    cat_grid.attachEvent("onBeforeContextMenu", function(rowid,colidx,grid)
    {
            // show item hidden by toolbar
            using_tb_context_menu = 0;
            cat_cmenu.showItem('object');
            cat_cmenu.showItem('goshop');
            <?php if (_r('ACT_CAT_CONTEXTMENU_EDIT_BO')) { ?>
            cat_cmenu.showItem('gopsbo');
            <?php } ?>
            cat_cmenu.showItem('update_yes');
            cat_cmenu.showItem('update_no');
            cat_cmenu.showItem('copy');
            cat_cmenu.showItem('paste');
            cat_cmenu.showItem('massupdate_quantity');
            cat_cmenu.showItem('massupdate_quantitya');
            cat_cmenu.showItem('massupdate_round_price');
            cat_cmenu.showItem('massupdate_specificprices');

            let current_segment_is_manual = parseInt(cat_grid.getUserData("", "manual_segment"));
            if(current_segment_is_manual === 1){
                cat_cmenu.setItemEnabled('delete_from_manual_segment');
            } else {
                cat_cmenu.setItemDisabled('delete_from_manual_segment');
            }

            lastColumnRightClicked=colidx;
            idxMsgAvailableNow=cat_grid.getColIndexById('available_now');
            idxMsgAvailableLater=cat_grid.getColIndexById('available_later');
            idxReductionPrice=cat_grid.getColIndexById('reduction_price');
            idxReductionPercent=cat_grid.getColIndexById('reduction_percent');
            idxReductionFrom=cat_grid.getColIndexById('reduction_from');
            idxReductionTo=cat_grid.getColIndexById('reduction_to');
            idxPrice=cat_grid.getColIndexById('price');

            idxMargin=cat_grid.getColIndexById('margin');
            idxPriceInTax=cat_grid.getColIndexById('price_inc_tax');
            idxWholesalePrice=cat_grid.getColIndexById('wholesale_price');

            cat_cmenu.setItemText('object', '<?php echo _l('Product:'); ?> '+getSelectedItemValueOrID(cat_grid,rowid,'name'));
            // paste function
            <?php if (_r('ACT_CAT_CONTEXTMENU_COPY_PASTE')) { ?>
            if (lastColumnRightClicked==clipboardType)
            {
                cat_cmenu.setItemEnabled('paste');
            }else{
                cat_cmenu.setItemDisabled('paste');
            }
            var colType=cat_grid.getColType(colidx);
            if (colType=='ro')
            {
                cat_cmenu.setItemDisabled('copy');
                cat_cmenu.setItemDisabled('paste');
            }else{
                cat_cmenu.setItemEnabled('copy');
            }

            if(colidx==idxMargin){
                cat_cmenu.setItemDisabled('copy');
            }
            <?php } ?>
            <?php if (_r('ACT_CAT_CONTEXTMENU_MASS_UPDATE_PRODUCT')) { ?>
            if(colidx==idxPrice || colidx==idxPriceInTax || colidx==idxWholesalePrice)
            {
                cat_cmenu.setItemEnabled('massupdate_round_price');
                cat_cmenu.setItemEnabled('massupdate_round_price_1');
                cat_cmenu.setItemEnabled('massupdate_round_price_2');
                cat_cmenu.setItemEnabled('massupdate_round_price_3');
                cat_cmenu.setItemEnabled('massupdate_round_price_4');
                cat_cmenu.setItemEnabled('massupdate_round_price_5');
                cat_cmenu.setItemEnabled('massupdate_round_price_6');
                cat_cmenu.setItemEnabled('massupdate_round_price_7');
                cat_cmenu.setItemEnabled('massupdate_round_price_8');
                cat_cmenu.setItemEnabled('massupdate_round_price_9');
                cat_cmenu.setItemEnabled('massupdate_round_price_help');

                cat_cmenu.setItemEnabled('massupdate_round_price_combi');
                cat_cmenu.setItemEnabled('massupdate_round_price_combi_1');
                cat_cmenu.setItemEnabled('massupdate_round_price_combi_2');
                cat_cmenu.setItemEnabled('massupdate_round_price_combi_3');
                cat_cmenu.setItemEnabled('massupdate_round_price_combi_4');
                cat_cmenu.setItemEnabled('massupdate_round_price_combi_5');
                cat_cmenu.setItemEnabled('massupdate_round_price_combi_6');
                cat_cmenu.setItemEnabled('massupdate_round_price_combi_7');
                cat_cmenu.setItemEnabled('massupdate_round_price_combi_8');
                cat_cmenu.setItemEnabled('massupdate_round_price_combi_9');
                cat_cmenu.setItemEnabled('massupdate_round_price_combi_help');
                var title = "<?php echo _l('price'); ?>";
                if(colidx==idxPrice)
                    title = "<?php echo _l('Price excl. Tax'); ?>";
                else if(colidx==idxPriceInTax)
                    title = "<?php echo _l('Price incl. Tax'); ?>";
                else if(colidx==idxWholesalePrice)
                    title = "<?php echo _l('Wholesale price'); ?>";
                cat_cmenu.setItemText('massupdate_round_price', '<?php echo _l('Rounding up the', 1); ?> '+title);
                cat_cmenu.setItemText('massupdate_round_price_combi', '<?php echo _l('Rounding up the', 1); ?> '+title);
            }
            else
            {
                cat_cmenu.setItemDisabled('massupdate_round_price');
                cat_cmenu.setItemDisabled('massupdate_round_price_1');
                cat_cmenu.setItemDisabled('massupdate_round_price_2');
                cat_cmenu.setItemDisabled('massupdate_round_price_3');
                cat_cmenu.setItemDisabled('massupdate_round_price_4');
                cat_cmenu.setItemDisabled('massupdate_round_price_5');
                cat_cmenu.setItemDisabled('massupdate_round_price_6');
                cat_cmenu.setItemDisabled('massupdate_round_price_7');
                cat_cmenu.setItemDisabled('massupdate_round_price_8');
                cat_cmenu.setItemDisabled('massupdate_round_price_9');
                cat_cmenu.setItemDisabled('massupdate_round_price_help');

                cat_cmenu.setItemDisabled('massupdate_round_price_combi');
                cat_cmenu.setItemDisabled('massupdate_round_price_combi_1');
                cat_cmenu.setItemDisabled('massupdate_round_price_combi_2');
                cat_cmenu.setItemDisabled('massupdate_round_price_combi_3');
                cat_cmenu.setItemDisabled('massupdate_round_price_combi_4');
                cat_cmenu.setItemDisabled('massupdate_round_price_combi_5');
                cat_cmenu.setItemDisabled('massupdate_round_price_combi_6');
                cat_cmenu.setItemDisabled('massupdate_round_price_combi_7');
                cat_cmenu.setItemDisabled('massupdate_round_price_combi_8');
                cat_cmenu.setItemDisabled('massupdate_round_price_combi_9');
                cat_cmenu.setItemDisabled('massupdate_round_price_combi_help');
                cat_cmenu.setItemText('massupdate_round_price', '<?php echo _l('Rounding the price up', 1); ?>');
                cat_cmenu.setItemText('massupdate_round_price_combi', '<?php echo _l('Rounding the price up', 1); ?>');
            }
            <?php } ?>

            var show_update_yesno = false;

            if(cat_grid.getColType(colidx)=="coro")
            {
                var nb = 0;
                var is_yesno = true;
                cat_grid.forEachRow(function(rId){
                    nb++;
                    if(nb<=2 && is_yesno==true)
                    {
                        var title = cat_grid.cells(rId,colidx).getTitle();
                        if(title!='<?php echo _l('Yes', 1); ?>' && title!='<?php echo _l('No', 1); ?>')
                        {
                            is_yesno = false;
                        }
                    }
                });
                if(nb<2)
                    is_yesno = false;
                if(is_yesno == true)
                    show_update_yesno = true;
            }
            if(show_update_yesno==true)
            {
                cat_cmenu.setItemEnabled('update_yes');
                cat_cmenu.setItemEnabled('update_no');
            }
            else
            {
                cat_cmenu.setItemDisabled('update_yes');
                cat_cmenu.setItemDisabled('update_no');
            }

            <?php if (SCMS && _r('ACT_CAT_CONTEXTMENU_SEE_FO')) { ?>
            if(shopUrls[shopselection] != undefined && shopUrls[shopselection] != "" && shopUrls[shopselection] != null)
            {
                cat_cmenu.setItemEnabled('goshop');
            }else{
                cat_cmenu.setItemDisabled('goshop');
            }
            <?php } ?>
            return true;
        });

    var marginMatrix_form = "";
    function calculMarginProduct(rId, type_col)
    {
        if(type_col==undefined || type_col==null || type_col=="")
            type_col = "margin";

        if(type_col=="margin_wt_amount_after_reduction")
        {
            if(cat_grid.getColIndexById(type_col)!=undefined && cat_grid.getColIndexById('wholesale_price')!=undefined && cat_grid.getColIndexById('price_wt_with_reduction')!=undefined)
            {
                idxPriceWithoutTaxes=cat_grid.getColIndexById('price_wt_with_reduction');
                idxWholeSalePrice=cat_grid.getColIndexById('wholesale_price');
                idxMargin=cat_grid.getColIndexById(type_col);

                var price = cat_grid.cells(rId,idxPriceWithoutTaxes).getValue();
                if(price>0)
                {
                    var wholesale_price = cat_grid.cells(rId,idxWholeSalePrice).getValue();
                    var margin = price*1 - wholesale_price*1;
                    cat_grid.cells(rId,idxMargin).setValue(priceFormat6Dec(margin));
                }
            }
        }
        else if(type_col=="margin_wt_percent_after_reduction")
        {
            if(cat_grid.getColIndexById(type_col)!=undefined && cat_grid.getColIndexById('price_wt_with_reduction')!=undefined && cat_grid.getColIndexById('margin_wt_amount_after_reduction')!=undefined)
            {
                idxPriceWithoutTaxes=cat_grid.getColIndexById('price_wt_with_reduction');
                idxMargin=cat_grid.getColIndexById('margin_wt_amount_after_reduction');
                idxPercent=cat_grid.getColIndexById(type_col);

                var price = cat_grid.cells(rId,idxPriceWithoutTaxes).getValue();
                if(price>0)
                {
                    var margin = cat_grid.cells(rId,idxMargin).getValue();
                    var percent = margin * 100 / price;
                    cat_grid.cells(rId,idxPercent).setValue(Math.round(percent));
                }
            }
        }
        else
        {
            if(cat_grid.getColIndexById(type_col)!=undefined && cat_grid.getColIndexById('wholesale_price')!=undefined)
            {
                var formule = marginMatrix_form;

                if(type_col=="margin")
                {
                    idxPriceIncTaxes=cat_grid.getColIndexById('price_inc_tax');
                    idxPriceWithoutTaxes=cat_grid.getColIndexById('price');
                }
                else if(type_col=="margin_after_reduction")
                {
                    idxPriceIncTaxes=cat_grid.getColIndexById('price_it_with_reduction');
                    idxPriceWithoutTaxes=cat_grid.getColIndexById('price_wt_with_reduction');
                }
                idxWholeSalePrice=cat_grid.getColIndexById('wholesale_price');

                idxMargin=cat_grid.getColIndexById(type_col);

                var price = cat_grid.cells(rId,idxPriceWithoutTaxes).getValue();
                if(price==null || price=="")
                    price = 0;
                formule = formule.replaceAll("{price}",price);

                var price_inc_tax = cat_grid.cells(rId,idxPriceIncTaxes).getValue();
                if(price_inc_tax==null || price_inc_tax=="")
                    price_inc_tax = 0;
                formule = formule.replaceAll("{price_inc_tax}",price_inc_tax);

                var wholesale_price = cat_grid.cells(rId,idxWholeSalePrice).getValue();
                if(wholesale_price==null || wholesale_price=="")
                    wholesale_price = 0;
                formule = formule.replaceAll("{wholesale_price}",wholesale_price);

                if(wholesale_price>0 && price>0)
                    var margin = eval(formule);
                else
                    var margin = 0;
                cat_grid.cells(rId,idxMargin).setValue(priceFormat6Dec(margin));

                <?php if (_s('CAT_PROD_GRID_MARGIN_COLOR') != '') { ?>
                if (idxMargin && type_col=="margin")
                {
                    var rules=('<?php echo str_replace("'", '', _s('CAT_PROD_GRID_MARGIN_COLOR')); ?>').split(';');
                    for(var i=(rules.length-1) ; i >= 0 ; i--){
                        var rule=rules[i].split(':');
                        if ( Number(cat_grid.cells(rId,idxMargin).getValue()) < Number(rule[0])){
                            cat_grid.cells(rId,idxMargin).setBgColor(rule[1]);
                            cat_grid.cells(rId,idxMargin).setTextColor('#FFFFFF');
                        }
                    }
                }
                <?php } ?>
            }
        }
    }

    function onEditCell(stage,rId,cInd,nValue,oValue){
        var coltype=cat_grid.getColType(cInd);
        if (stage==1 && this.editor && this.editor.obj && coltype!='txt' && coltype!='txttxt') this.editor.obj.select();
        lastEditedCell=cInd;

        idxPackStockType=cat_grid.getColIndexById('pack_stock_type');
        idxCacheIsPack=cat_grid.getColIndexById('cache_is_pack');
        if(idxPackStockType!=undefined && idxPackStockType!=null && idxPackStockType!="" && cInd==idxPackStockType)
        {
            if(idxCacheIsPack!=undefined && idxCacheIsPack!=null && idxCacheIsPack!="")
            {
                var cache_is_pack = cat_grid.cells(rId,idxCacheIsPack).getValue()*1;
                if(cache_is_pack==0)
                    return false;
            }
        }

        if(cInd==idxCacheIsPack && Number(cat_grid.getUserData(rId,"has_combination")) === 1) {
            return false;
        }

        if (nValue!=oValue){
            cat_grid.setRowColor(rId,'BlanchedAlmond');
            idxProductName=cat_grid.getColIndexById('name');
            idxQty=cat_grid.getColIndexById('quantity');
            idxQtyUpdate=cat_grid.getColIndexById('quantityupdate');
            idxPriceWithoutTaxes=cat_grid.getColIndexById('price');
            idxPriceIncTaxes=cat_grid.getColIndexById('price_inc_tax');
            idxEcotax=cat_grid.getColIndexById('ecotax');
            idxWholeSalePrice=cat_grid.getColIndexById('wholesale_price');
            idxVAT=cat_grid.getColIndexById(tax_identifier);
            idxActive=cat_grid.getColIndexById('active');
            idxMsgAvailableNow=cat_grid.getColIndexById('available_now');
            idxMsgAvailableLater=cat_grid.getColIndexById('available_later');
            idxReductionPrice=cat_grid.getColIndexById('reduction_price');
            idxReductionPercent=cat_grid.getColIndexById('reduction_percent');
            idxReductionFrom=cat_grid.getColIndexById('reduction_from');
            idxReductionTo=cat_grid.getColIndexById('reduction_to');
            idxMetaTitle=cat_grid.getColIndexById('meta_title');
            idxMetaDescription=cat_grid.getColIndexById('meta_description');
            idxMetaKeywords=cat_grid.getColIndexById('meta_keywords');
            idxLinkRewrite=cat_grid.getColIndexById('link_rewrite');
            idxMargin=cat_grid.getColIndexById('margin');
            idxWeight=cat_grid.getColIndexById('weight');
            idxUnitPrice=cat_grid.getColIndexById('unit_price_ratio');
            idxUnitPriceIncTax=cat_grid.getColIndexById('unit_price_inc_tax');
            <?php if (version_compare(_PS_VERSION_, '1.7.5.0', '>=')) {?>
                idxSoftReservedQty = cat_grid.getColIndexById('soft_qty_reserved');
                idxSoftPhysicalQty = cat_grid.getColIndexById('soft_qty_physical');
            <?php } ?>
            if (cInd == idxMetaTitle){
                cat_grid.cells(rId,idxMetaTitle).setValue(cat_grid.cells(rId,idxMetaTitle).getValue().substr(0,<?php echo _s('CAT_META_TITLE_SIZE'); ?>));
            }

            if (cInd == idxUnitPrice){
                nValue = noComma(nValue);
                var vat = tax_values[cat_grid.cells(rId,idxVAT).getTitle()]*1;
                var upit = nValue * vat;
                cat_grid.cells(rId,idxUnitPrice).setValue(nValue);
                cat_grid.cells(rId,idxUnitPriceIncTax).setValue(priceFormat6Dec(upit));

            }
            if (cInd == idxWeight){
                nValue = noComma(nValue);
                cat_grid.cells(rId,idxWeight).setValue(nValue);
            }
            if (cInd == idxMetaDescription){
                cat_grid.cells(rId,idxMetaDescription).setValue(cat_grid.cells(rId,idxMetaDescription).getValue().substr(0,<?php echo _s('CAT_META_DESC_SIZE'); ?>));
            }
            if (cInd == idxMetaKeywords){
                cat_grid.cells(rId,idxMetaKeywords).setValue(cat_grid.cells(rId,idxMetaKeywords).getValue().substr(0,<?php echo _s('CAT_META_KEYWORDS_SIZE'); ?>));
            }
            if (cInd == idxLinkRewrite){
                <?php
                $accented = Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');
                if ($accented == 1) {    ?>
                    cat_grid.cells(rId,idxLinkRewrite).setValue(getAccentedLinkRewriteFromString(cat_grid.cells(rId,idxLinkRewrite).getValue().substr(0,<?php echo _s('CAT_LINK_REWRITE_SIZE'); ?>)));
                <?php }
                else
                { ?>
                    cat_grid.cells(rId,idxLinkRewrite).setValue(getLinkRewriteFromString(cat_grid.cells(rId,idxLinkRewrite).getValue().substr(0,<?php echo _s('CAT_LINK_REWRITE_SIZE'); ?>),SC_ID_LANG));
                <?php } ?>
            }
            if (cInd == idxQtyUpdate){ //Quantity update
                var qty = cat_grid.cells(rId,idxQty).getValue()*1;
                var qtyToAdd = nValue*1;
                cat_grid.cells(rId,idxQty).setValue(qty+qtyToAdd);
                <?php if (version_compare(_PS_VERSION_, '1.7.5.0', '>=')) {?>
                if(idxSoftPhysicalQty !== undefined && idxSoftReservedQty !== undefined) {
                    let actualReserved = cat_grid.cells(rId, idxSoftReservedQty).getValue() * 1;
                    let newQty = cat_grid.cells(rId, idxQty).getValue() * 1;
                    cat_grid.cells(rId, idxSoftPhysicalQty).setValue(newQty + actualReserved);
                }
                <?php } ?>
            }
            <?php if (version_compare(_PS_VERSION_, '1.7.5.0', '>=')) {?>
            if (cInd == idxQty && idxSoftPhysicalQty !== undefined && idxSoftReservedQty !== undefined){
                let actualReserved = cat_grid.cells(rId,idxSoftReservedQty).getValue()*1;
                let newQty = cat_grid.cells(rId,idxQty).getValue()*1;
                cat_grid.cells(rId,idxSoftPhysicalQty).setValue(newQty+actualReserved);
            }
            <?php } ?>
            if ((cInd == idxPriceWithoutTaxes)){ //Price
                var vat = tax_values[cat_grid.cells(rId,idxVAT).getTitle()]*1;
                var pwt = noComma(nValue);
                var eco = 0;
                if (idxEcotax && EcotaxTaxRate!=-1)
                    eco = cat_grid.cells(rId,idxEcotax).getValue()*1;
                var pit = vat * pwt <?php if (_s('CAT_PROD_ECOTAXINCLUDED')){?> + eco <?php } ?>;
                cat_grid.setUserData(rId, 'tax', vat);
                if (idxWholeSalePrice)
                {
                    var wholesaleprice = cat_grid.cells(rId,idxWholeSalePrice).getValue();
<?php
    if (_s('CAT_NOTICE_WHOLESALEPRICEHIGHER'))
    {
        ?>

                    if (wholesaleprice > pwt)
                        dhtmlx.message({text:'<?php echo addslashes(_l('Alert: wholesale price higher than sell price!')); ?><br/><a href="javascript:disableThisNotice(\'CAT_NOTICE_WHOLESALEPRICEHIGHER\');"><?php echo _l('Disable this notice', 1); ?></a>',type:'error'});
<?php
    }
?>
                }
                cat_grid.cells(rId,idxPriceWithoutTaxes).setValue(priceFormat6Dec(pwt));
                cat_grid.cells(rId,idxPriceIncTaxes).setValue(priceFormat6Dec(pit));
                cat_grid.setUserData(rId, 'price_inc_tax', pit);
                calculMarginProduct(rId);
                calculMarginProduct(rId, "margin_after_reduction");
                calculMarginProduct(rId, "margin_wt_amount_after_reduction");
                calculMarginProduct(rId, "margin_wt_percent_after_reduction");
            }
            if ((cInd ==idxVAT )){ //VAT
                var vat = tax_values[cat_grid.cells(rId,idxVAT).getTitle()]*1;
                var pwt = noComma(cat_grid.cells(rId,idxPriceWithoutTaxes).getValue());
                var eco = 0;
                if (idxEcotax)
                    eco = cat_grid.cells(rId,idxEcotax).getValue()*1;
                var pit = vat * pwt + eco;
                if (idxWholeSalePrice)
                {
                    var wholesaleprice = cat_grid.cells(rId,idxWholeSalePrice).getValue();
<?php
    if (_s('CAT_NOTICE_WHOLESALEPRICEHIGHER'))
    {
        ?>
                    if (wholesaleprice > pwt)
                        dhtmlx.message({text:'<?php echo addslashes(_l('Alert: wholesale price higher than sell price!')); ?><br/><a href="javascript:disableThisNotice(\'CAT_NOTICE_WHOLESALEPRICEHIGHER\');"><?php echo _l('Disable this notice', 1); ?></a>',type:'error'});
<?php
    }
?>
                }
                cat_grid.cells(rId,idxPriceWithoutTaxes).setValue(priceFormat6Dec(pwt));
                cat_grid.cells(rId,idxPriceIncTaxes).setValue(priceFormat6Dec(pit));
                cat_grid.setUserData(rId, 'price_inc_tax', pit);
                cat_grid.setUserData(rId, 'tax', vat);
                calculMarginProduct(rId);
                calculMarginProduct(rId, "margin_after_reduction");
                calculMarginProduct(rId, "margin_wt_amount_after_reduction");
                calculMarginProduct(rId, "margin_wt_percent_after_reduction");
            }
            if ((cInd == idxPriceIncTaxes)){ //Price including taxes
                var vat = tax_values[cat_grid.cells(rId,idxVAT).getTitle()]*1;
                var pit = noComma(nValue);
                cat_grid.cells(rId,idxPriceIncTaxes).setValue(pit);
                var eco = 0;
                if (idxEcotax)
                    eco = cat_grid.cells(rId,idxEcotax).getValue()*1;
                var newpwt = (pit <?php if (_s('CAT_PROD_ECOTAXINCLUDED')){?> - eco <?php } ?> ) / vat;
                if (idxWholeSalePrice)
                {
                    var wholesaleprice = cat_grid.cells(rId,idxWholeSalePrice).getValue()*1;
<?php
    if (_s('CAT_NOTICE_WHOLESALEPRICEHIGHER'))
    {
        ?>
                    if (wholesaleprice > newpwt)
                        dhtmlx.message({text:'<?php echo addslashes(_l('Alert: wholesale price higher than sell price!')); ?><br/><a href="javascript:disableThisNotice(\'CAT_NOTICE_WHOLESALEPRICEHIGHER\');"><?php echo _l('Disable this notice', 1); ?></a>',type:'error'});
<?php
    }
?>
                }
                cat_grid.setUserData(rId, 'tax', vat);
                if (idxEcotax)
                    cat_grid.setUserData(rId, 'ecotax', eco);
                cat_grid.cells(rId,idxPriceIncTaxes).setValue(priceFormat6Dec(pit));
                cat_grid.setUserData(rId, 'price_inc_tax', pit);
                cat_grid.cells(rId,idxPriceWithoutTaxes).setValue(priceFormat6Dec(newpwt));
                calculMarginProduct(rId);
                calculMarginProduct(rId, "margin_after_reduction");
                calculMarginProduct(rId, "margin_wt_amount_after_reduction");
                calculMarginProduct(rId, "margin_wt_percent_after_reduction");
            }
            if ((cInd == idxUnitPriceIncTax)) {
                var vat = tax_values[cat_grid.cells(rId,idxVAT).getTitle()]*1;
                var upit = noComma(cat_grid.cells(rId,idxUnitPriceIncTax).getValue());
                var upet = upit / vat;
                cat_grid.cells(rId,idxUnitPrice).setValue(priceFormat6Dec(upet));
                cat_grid.setUserData(rId, 'unity_price_excl_tax', upet);
            }
            if ((cInd == idxEcotax)){ //EcoTax
                var vat = tax_values[cat_grid.cells(rId,idxVAT).getTitle()]*1;
                var pwt = noComma(cat_grid.cells(rId,idxPriceWithoutTaxes).getValue());
                var eco = noComma(nValue);
                var pit = noComma(cat_grid.cells(rId,idxPriceIncTaxes).getValue());
                var newpwt = (pit <?php if (_s('CAT_PROD_ECOTAXINCLUDED')){?> - eco <?php } ?>) / vat;
                cat_grid.setUserData(rId, 'tax', vat);
                cat_grid.setUserData(rId, 'price_inc_tax', pit);
                cat_grid.setUserData(rId, 'ecotax', priceFormat6Dec(nValue));
                cat_grid.cells(rId,idxEcotax).setValue(priceFormat6Dec(nValue));
                cat_grid.cells(rId,idxPriceWithoutTaxes).setValue(priceFormat6Dec(newpwt));
                calculMarginProduct(rId);
                calculMarginProduct(rId, "margin_after_reduction");
                calculMarginProduct(rId, "margin_wt_amount_after_reduction");
                calculMarginProduct(rId, "margin_wt_percent_after_reduction");
            }
            if (cInd == idxWholeSalePrice){ //Wholesale price
                var pwt = cat_grid.cells(rId,idxPriceWithoutTaxes).getValue()*1;
                var wholesaleprice = noComma(nValue);
                cat_grid.cells(rId,idxWholeSalePrice).setValue(priceFormat<?php echo _s('CAT_PROD_WHOLESALEPRICE4DEC') ? '4Dec' : ''; ?>(wholesaleprice));
<?php
    if (_s('CAT_NOTICE_WHOLESALEPRICEHIGHER'))
    {
        ?>
                if (wholesaleprice > pwt)
                        dhtmlx.message({text:'<?php echo addslashes(_l('Alert: wholesale price higher than sell price!')); ?><br/><a href="javascript:disableThisNotice(\'CAT_NOTICE_WHOLESALEPRICEHIGHER\');"><?php echo _l('Disable this notice', 1); ?></a>',type:'error'});
<?php
    }
?>
                calculMarginProduct(rId);
                calculMarginProduct(rId, "margin_after_reduction");
                calculMarginProduct(rId, "margin_wt_amount_after_reduction");
                calculMarginProduct(rId, "margin_wt_percent_after_reduction");
            }
            if (cInd == idxActive){ //Active update
                if (nValue==0){
                    cat_grid.cells(rId,idxProductName).setBgColor('#D7D7D7');
                }else{
                    cat_grid.cells(rId,idxProductName).setBgColor(cat_grid.cells(rId,0).getAttribute('bgColor'));
                }
            }
            if (cInd == idxProductName){ //Active update
                <?php if (version_compare(_PS_VERSION_, '1.6.0.0', '>=') && !_s('CAT_SEO_NAME_TO_URL') && _s('CAT_NOTICE_UPDATE_PRODUCT_URL_REWRITE')) { ?>
                    dhtmlx.message({text:'<?php echo _l('Caution: The option located in Prestashop > Products > Force update of friendly URL is set to NO: friendly url will not be saved automatically. To stop this alert: SC  > Tools > Settings > Alert.', 1); ?><br/><a href="javascript:disableThisNotice(\'CAT_NOTICE_UPDATE_PRODUCT_URL_REWRITE\');"><?php echo _l('Disable this notice', 1); ?></a>',type:'error',expire:15000});
                <?php } ?>
            }
            <?php if (SCAS) { ?>
                idxASM=cat_grid.getColIndexById('advanced_stock_management');
                idxQtyUse=cat_grid.getColIndexById('quantity_usable');
                idxQtyPhy=cat_grid.getColIndexById('quantity_physical');
                idxQtyRea=cat_grid.getColIndexById('quantity_real');
                if (cInd == idxASM){
                    cat_grid.setCellExcellType(rId,idxQty,"ro");
                    cat_grid.setCellExcellType(rId,idxQtyUpdate,"ro");
                    cat_grid.setCellExcellType(rId,idxQtyUse,"ro");
                    cat_grid.setCellExcellType(rId,idxQtyPhy,"ro");
                    cat_grid.setCellExcellType(rId,idxQtyRea,"ro");

                    cat_grid.cells(rId,idxQty).setValue('<?php echo _l('Refresh', 1); ?>');
                    cat_grid.cells(rId,idxQtyUpdate).setValue('<?php echo _l('Refresh', 1); ?>');
                    cat_grid.cells(rId,idxQtyUse).setValue('<?php echo _l('Refresh', 1); ?>');
                    cat_grid.cells(rId,idxQtyPhy).setValue('<?php echo _l('Refresh', 1); ?>');
                    cat_grid.cells(rId,idxQtyRea).setValue('<?php echo _l('Refresh', 1); ?>');

                    cat_grid.setUserData(rId,"type_advanced_stock_management", cat_grid.cells(rId,idxASM).getValue());
                }
            <?php } ?>
        }
        <?php sc_ext::readCustomGridsConfigXML('onEditCell'); ?>
        if (nValue!=oValue){
            var id = rId;
            <?php
                sc_ext::readCustomGridsConfigXML('onBeforeUpdate');
            ?>

            addProductInQueue(rId, "update", cInd);
            return true;
        }
        idxManufacturers=cat_grid.getColIndexById('id_manufacturer');
        idxSuppliers=cat_grid.getColIndexById('id_supplier');
        idxSupplierReference=cat_grid.getColIndexById('supplier_reference');
        idxVAT=cat_grid.getColIndexById(tax_identifier);
        idxMsgAvailableNow=cat_grid.getColIndexById('available_now');
        idxMsgAvailableLater=cat_grid.getColIndexById('available_later');
<?php
    if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
    {
        ?>
        if (stage==1 && cInd ==idxSupplierReference && cat_grid.cells(rId,idxSuppliers).getValue()==0)
        {
            dhtmlx.message({text:'<?php echo addslashes(_l('A supplier needs to be associated to the product to set a supplier\'s reference to the product')); ?>',type:'error'});
        }
<?php
    }
?>
    if(stage==1 && (cInd ==idxManufacturers || cInd ==idxSuppliers || cInd ==idxVAT || cInd ==idxMsgAvailableNow || cInd ==idxMsgAvailableLater))
    {
            var editor = this.editor;
            var pos = this.getPosition(editor.cell);
            var y = document.body.offsetHeight-pos[1];
            if(y < editor.list.offsetHeight)
                editor.list.style.top = (pos[1] - editor.list.offsetHeight)+'px';
    }
    }
    cat_grid.attachEvent("onEditCell",onEditCell);
    cat_grid.attachEvent("onDhxCalendarCreated",function(calendar){
            dhtmlXCalendarObject.prototype.langData["<?php echo $user_lang_iso; ?>"] = lang_calendar;
            calendar.loadUserLanguage("<?php echo $user_lang_iso; ?>");
        });

    // Context menu for Grid
    cat_cmenu=new dhtmlXMenuObject();
    cat_cmenu.renderAsContextMenu();
    function onGridCatContextButtonClick(itemId){
        if(using_tb_context_menu !== 1) {
            tabId = cat_grid.contextID.split('_');
            tabId = tabId[0];
        }

        if (itemId=="gopsbo"){
            wModifyProduct = dhxWins.createWindow("wModifyProduct", 50, 50, 1000, $(window).height()-75);
            wModifyProduct.setText('<?php echo _l('Modify the product and close this window to refresh the grid', 1); ?>');
            let set_shop_context_url = '';
            <?php if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) { ?>
                if (shopselection !== null && shopselection !== undefined && shopselection !== 0) {
                    set_shop_context_url = "setShopContext=s-"+shopselection;
                }
            <?php } ?>
            wModifyProduct.attachURL("<?php echo SC_PS_PATH_ADMIN_REL; ?>index.php?<?php echo version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? 'controller=AdminProducts' : 'tab=AdminCatalog'; ?>&updateproduct&id_product="+tabId+"&id_lang="+SC_ID_LANG+"&adminlang=1&token=<?php echo $sc_agent->getPSToken((version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? 'AdminProducts' : 'AdminCatalog')); ?>&"+set_shop_context_url);
            wModifyProduct.attachEvent("onClose", function(win){
                        displayProducts();
                        return true;
                    });
        }
        if (itemId=="goshop"){
            var sel=cat_grid.getSelectedRowId();
            if (sel)
            {
                var tabId=sel.split(',');
                var k=1;
                for (var i=0;i<tabId.length;i++)
                {
                    if (k > <?php echo _s('CAT_PROD_OPEN_URL'); ?>) break;
                    idxActive=cat_grid.getColIndexById('active');
                    <?php if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) { ?>
                    if (idxActive) {
                        if (cat_grid.cells(tabId[i], idxActive).getValue() == 0) {
                            <?php $preview_url = '&adtoken='.$sc_agent->getPSToken('AdminCatalog').'&id_employee='.$sc_agent->id_employee.(version_compare(_PS_VERSION_, '1.7.0.0', '>=') ? '&preview=1' : ''); ?>
                            var previewUrl = "<?php echo $preview_url; ?>";
                        } else {
                            var previewUrl = 0;
                        }
                    }
                    <?php }
else
{ ?>
                    if (idxActive)
                        if (cat_grid.cells(tabId[i],idxActive).getValue()==0)
                            continue;
                    <?php } ?>
<?php
    if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
    {
        if (SCMS)
        {
            ?>
                    if (previewUrl != 0) {
                        if(shopUrls[shopselection] != undefined && shopUrls[shopselection] != "" && shopUrls[shopselection] != null)
                            window.open(shopUrls[shopselection]+'index.php?id_product='+tabId[i]+'&controller=product&id_lang='+SC_ID_LANG+previewUrl);
                    } else {
                        if (shopUrls[shopselection] != undefined && shopUrls[shopselection] != "" && shopUrls[shopselection] != null)
                            window.open(shopUrls[shopselection] + 'index.php?id_product=' + tabId[i] + '&controller=product&id_lang=' + SC_ID_LANG);
                    }
<?php
        }
        else
        {
            ?>
                    if (previewUrl != 0) {
                        window.open('<?php echo SC_PS_PATH_REL; ?>index.php?id_product=' + tabId[i] + '&controller=product&id_lang=' + SC_ID_LANG+previewUrl);
                    } else {
                        window.open('<?php echo SC_PS_PATH_REL; ?>index.php?id_product=' + tabId[i] + '&controller=product&id_lang=' + SC_ID_LANG);
                    }
<?php
        }
    }
    else
    {
        ?>
                    window.open('<?php echo SC_PS_PATH_REL; ?>product.php?id_product=' + tabId[i]);
<?php
    }
?>
                    k++;
                }
            }else{
                var tabId=cat_grid.contextID.split('_');
<?php
    if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
    {
        if (SCMS)
        {
            ?>
                if(shopUrls[shopselection] != undefined && shopUrls[shopselection] != "" && shopUrls[shopselection] != null)
                    window.open(shopUrls[shopselection]+'index.php?id_product='+tabId[0]+'&controller=product');
<?php
        }
        else
        {
            ?>
                    window.open('<?php echo SC_PS_PATH_REL; ?>index.php?id_product='+tabId[0]+'&controller=product');
<?php
        }
    }
    else
    {
        ?>
                    window.open('<?php echo SC_PS_PATH_REL; ?>product.php?id_product='+tabId[0]);
<?php
    }
?>
            }
        }
        if (itemId=="copy"){
            if (lastColumnRightClicked!=0)
            {
                clipboardValue=cat_grid.cells(tabId,lastColumnRightClicked).getValue();
                cat_cmenu.setItemText('paste' , '<?php echo _l('Paste'); ?> '+cat_grid.cells(tabId,lastColumnRightClicked).getTitle().substr(0,30)+'...');
                clipboardType=lastColumnRightClicked;
            }
        }
        if (itemId=="paste"){
            if (lastColumnRightClicked!=0 && clipboardValue!=null && clipboardType==lastColumnRightClicked)
            {
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    idxQty=cat_grid.getColIndexById('quantity');
                    selArray=selection.split(',');
                    for(i=0 ; i < selArray.length ; i++)
                    {
                        <?php if (SCAS) { ?>
                        var type_advanced_stock_management =  cat_grid.getUserData(selArray[i],"type_advanced_stock_management");
                        if(
                            (idxQty==lastColumnRightClicked && (type_advanced_stock_management==1 || type_advanced_stock_management==3))
                            ||
                            idxQty!=lastColumnRightClicked
                        )
                        {
                        <?php } ?>
                        if(
                            (idxQty==lastColumnRightClicked && (cat_grid.cells(selArray[i],lastColumnRightClicked).getAttribute('bgColor')!="#D7D7D7" && cat_grid.cells(selArray[i],lastColumnRightClicked).getAttribute('bgColor')!="#d7d7d7"))
                            ||
                            idxQty!=lastColumnRightClicked
                        )
                        {
                            cat_grid.cells(selArray[i],lastColumnRightClicked).setValue(clipboardValue);
                            cat_grid.cells(selArray[i],lastColumnRightClicked).cell.wasChanged=true;
                            onEditCell(null,selArray[i],lastColumnRightClicked,clipboardValue,null);
                        }
                        <?php if (SCAS) { ?>
                        }
                        <?php } ?>
                    }
                }
            }
        }

        if (itemId=="update_yes"){
            selection=cat_grid.getSelectedRowId();
            if (selection!='' && selection!=null)
            {
                selArray=selection.split(',');
                for(i=0 ; i < selArray.length ; i++)
                {
                    cat_grid.cells(selArray[i],lastColumnRightClicked).setValue("1");
                    cat_grid.cells(selArray[i],lastColumnRightClicked).cell.wasChanged=true;
                    onEditCell(null,selArray[i],lastColumnRightClicked,"1",null);
                }
            }
        }
        if (itemId=="update_no"){
            selection=cat_grid.getSelectedRowId();
            if (selection!='' && selection!=null)
            {
                selArray=selection.split(',');
                for(i=0 ; i < selArray.length ; i++)
                {
                    cat_grid.cells(selArray[i],lastColumnRightClicked).setValue("0");
                    cat_grid.cells(selArray[i],lastColumnRightClicked).cell.wasChanged=true;
                    onEditCell(null,selArray[i],lastColumnRightClicked,"0",null);
                }
            }
        }

        if (itemId=="massupdate_price"){
            todo=prompt('<?php echo _l('Modify sell price exc. tax, possible values: -10.50%, +5.0, -5.25,...', 1); ?>','');
            if (todo!='' && todo!=null){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null) {
                    var params = {"field": "price", "todo": todo};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_pricetax"){
            todo=prompt('<?php echo _l('Modify sell price inc. tax, possible values: -10.50%, +5.0, -5.25,...', 1); ?>','');
            if (todo!='' && todo!=null){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null){
                    var params = {"field": "pricetax", "todo": todo};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_wholesaleprice"){
            todo=prompt('<?php echo _l('Modify wholesale price, possible values: -10.50%, +5.0, -5.25,...', 1); ?>','');
            if (todo!='' && todo!=null){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null){
                    var params = {"field": "wholesaleprice", "todo": todo};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_quantity"){
            todo=prompt('<?php echo _l('Modify quantity, possible values: +5, -5,...', 1); ?>','');
            if (todo!='' && todo!=null){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null) {
                    var params = {"field": "quantity", "id_lang": SC_ID_LANG, "todo": todo};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_margin"){
            todo=prompt('<?php echo _l('Apply margin (use the math formula choosen in Settings for modify sale price):', 1); ?>','');
            if (todo!='' && todo!=null){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null){
                    var params = {"field": "margin","todo": todo};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_margin_combi"){
            todo=prompt('<?php echo _l('Apply margin (use the math formula choosen in Settings for modify sale price):', 1); ?>','');
            if (todo!='' && todo!=null){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null){
                    var params = {"field": "margin_combi","todo": todo};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_combi_price"){
            todo=prompt('<?php echo _l('Modify sell price exc. tax, possible values: -10.50%, +5.0, -5.25,...', 1); ?>','');
            if (todo!='' && todo!=null){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null){
                    var params = {"field": "combi_price","todo": todo};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_combi_pricetax"){
            todo=prompt('<?php echo _l('Modify sell price inc. tax, possible values: -10.50%, +5.0, -5.25,...', 1); ?>','');
            if (todo!='' && todo!=null){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null) {
                    var params = {"field": "combi_pricetax","todo": todo};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_combi_wholesaleprice"){
            todo=prompt('<?php echo _l('Modify wholesale price, possible values: -10.50%, +5.0, -5.25,...', 1); ?>','');
            if (todo!='' && todo!=null){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null){
                    var params = {"field": "combi_wholesaleprice", "todo": todo};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_combi_quantity"){
            todo=prompt('<?php echo _l('Modify quantity, possible values: +5, -5, 5.25,...', 1); ?>','');
            if (todo!='' && todo!=null){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null){
                    var params = {"field": "combi_quantity","todo": todo};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_defaultcombitocheapest"){
            if (confirm('<?php echo _l('This will set the cheapest combination as default combination for each seleted product. Continue?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var params = {"field": "defaultcombination","todo": "cheapest"};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_defaultcombitoinstockandcheapest"){
            if (confirm('<?php echo _l('This will set the cheapest combination in stock as default combination for each seleted product. Continue?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var params = {"field": "defaultcombination","todo": "instockandcheapest"};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_1"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round","todo": "1", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_2"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round","todo": "2", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_3"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round","todo": "3", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_4"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round","todo": "4", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_5"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round","todo": "5", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_6"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round","todo": "6", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_7"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round","todo": "7", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_8"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round","todo": "8", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_9"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round","todo": "9", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_10"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round","todo": "10", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_help"){
            window.open('<?php echo getScExternalLink('support_massupdate_round_price'); ?>','_blank');
        }
        if (itemId=="massupdate_round_price_combi_1"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round_combi","todo": "1", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_combi_2"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round_combi","todo": "2", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_combi_3"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round_combi","todo": "3", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_combi_4"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round_combi","todo": "4", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_combi_5"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round_combi","todo": "5", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_combi_6"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round_combi","todo": "6", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_combi_7"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round_combi","todo": "7", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_combi_8"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round_combi","todo": "8", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_combi_9"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round_combi","todo": "9", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_combi_10"){
            if (confirm('<?php echo _l('Are you sure that you want to round all these prices up?', 1); ?>')){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    var column=cat_grid.getColumnId(lastColumnRightClicked);
                    var params = {"field": "mass_round_combi","todo": "10", "column": column};
                    massUpdateInQueue(selection, params);
                }
            }
        }
        if (itemId=="massupdate_round_price_combi_help"){
            window.open('<?php echo getScExternalLink('support_massupdate_round_price'); ?>','_blank');
        }
        if (itemId=="massupdate_defaultcombiprice"){
            selection=cat_grid.getSelectedRowId();
            $.post('index.php?ajax=1&act=cat_rebuildproductprice',{'product_ids':selection},function(data){
                if(data == 'Ok') {
                    displayProducts('cat_grid.selectRowById(' + lastProductSelID + ',true,true,true);');
                }
            });
        }
        if(itemId==="delete_from_manual_segment"){
            if (confirm('<?php echo _l('Do you really want to remove data from current segment?', 1); ?>')){
                $.post("index.php?ajax=1&act=all_win-segmentation_element_update",{action:'delete_from_id_element',ids:cat_grid.getSelectedRowId(),segmentId: segselection},function(){
                    displayProducts();
                });
            }
        }
        if (itemId=="massupdate_specificprices"){
            todo=prompt('<?php echo _l('Modify fixed specific prices, ex%s %s', 1, array(_l(':'),'+5.0, -5.25, +5%...')); ?>','');
            if (todo!='' && todo!=null){
                selection=cat_grid.getSelectedRowId();
                if (selection!='' && selection!=null){
                    var params = {"field": "edit_specificprices", "todo": todo};
                    specificPricesMassUpdateInQueue(selection, params);
                }
            }
        }
    }

    function massUpdateInQueue(selection, params)
    {
        var ids = selection.split(',');

        $.each(ids, function(num, pId){
            params['idlist'] = pId;
            if (num === ids.length-1) {
                params['lastP'] = 1;
            }
            var vars = params;
            addMassUpdateInQueue(pId, "update", null, vars);
        });
    }

    function addMassUpdateInQueue(rId, action, cIn, vars)
    {
        var params = {
            name: "cat_product_massupdate_queue",
            row: rId,
            action: action,
            params: {},
            callback: "callbackMassUpdate('"+rId+"','"+action+"','"+rId+"',{data},{'lastP': '"+vars.lastP+"', 'field':'"+vars.field+"'});"
        };
        // COLUMN VALUES
        params.params["id_lang"] = SC_ID_LANG;
        if(vars!=undefined && vars!=null && vars!="" && vars!=0)
        {
            $.each(vars, function(key, value){
                params.params[key] = value;
            });
        }

        params.params = JSON.stringify(params.params);
        addInUpdateQueue(params,cat_grid);
    }

    // CALLBACK FUNCTION
    function callbackMassUpdate(sid,action,tid,xml,vars)
    {
        if (action=='update')
        {
            if (parseInt(vars.lastP) === 1) {
                displayProducts('cat_grid.selectRowById('+lastProductSelID+',true,true,true);');
            }
        }
    }

    cat_cmenu.attachEvent("onClick", onGridCatContextButtonClick);
    var contextMenuXML='<menu absolutePosition="auto" mode="popup" maxItems="8"  globalCss="contextMenu" globalSecondCss="contextMenu" globalTextCss="contextMenuItem">'+
        '<item text="Object" id="object" enabled="false"/>'+
        <?php if (_r('ACT_CAT_CONTEXTMENU_SEE_FO')) { ?>
        '<item text="<?php echo _l('See on shop'); ?>" id="goshop"/>'+
        <?php }
         if (_r('ACT_CAT_CONTEXTMENU_EDIT_BO')) { ?>
        '<item text="<?php echo _l('Edit in PrestaShop BackOffice'); ?>" id="gopsbo"/>'+
        <?php }
         if (_r('ACT_CAT_CONTEXTMENU_COPY_PASTE')) { ?>
        '<item text="<?php echo _l('Copy'); ?>" id="copy"/>'+
        '<item text="<?php echo _l('Paste'); ?>" id="paste"/>'+
        <?php } ?>
        '<item text="<?php echo _l('Update:'); ?> <?php echo _l('Yes'); ?>" id="update_yes"/>'+
        '<item text="<?php echo _l('Update:'); ?> <?php echo _l('No'); ?>" id="update_no"/>'+
        '<item text="<?php echo _l('Remove from manual segment'); ?>" id="delete_from_manual_segment"/>'+
        <?php if (_r('ACT_CAT_CONTEXTMENU_MASS_UPDATE_PRODUCT')) { ?>
        '<item text="<?php echo _l('Mass update'); ?>" id="massupdate">'+
            '<item text="<?php echo _l('Sell price exc. tax').' - '._l('Products'); ?>..." id="massupdate_price"/>'+
            '<item text="<?php echo _l('Sell price inc. tax').' - '._l('Products'); ?>..." id="massupdate_pricetax"/>'+
            '<item text="<?php echo _l('Wholesale price', 1).' - '._l('Products'); ?>..." id="massupdate_wholesaleprice"/>'+
            '<item text="<?php echo _l('Quantity').' - '._l('Products'); ?>...<?php if (SCAS)
         {
             echo '('._l('Not Advanced stock').')';
         } ?>" id="massupdate_quantity"/>'+
            '<item text="<?php echo _l('Margin'); ?>..." id="massupdate_margin"/>'+
            '<item text="<?php echo _l('Combinations'); ?>..." id="massupdate_quantitya">'+
                '<item text="<?php echo _l('Sell price exc. tax').' - '._l('Combinations'); ?>..." id="massupdate_combi_price"/>'+
                '<item text="<?php echo _l('Sell price inc. tax').' - '._l('Combinations'); ?>..." id="massupdate_combi_pricetax"/>'+
                '<item text="<?php echo _l('Wholesale price', 1).' - '._l('Combinations'); ?>..." id="massupdate_combi_wholesaleprice"/>'+
                '<item text="<?php echo _l('Quantity').' - '._l('Combinations'); ?>...<?php if (SCAS)
         {
             echo '('._l('Not Advanced stock').')';
         } ?>" id="massupdate_combi_quantity"/>'+
                '<item text="<?php echo _l('Margin'); ?>..." id="massupdate_margin_combi"/>'+
                '<item text="<?php echo _l('Set the cheapest combination as default combination', 1); ?>" id="massupdate_defaultcombitocheapest"/>'+
                '<item text="<?php echo _l('Set the cheapest combination in stock as default combination', 1); ?>" id="massupdate_defaultcombitoinstockandcheapest"/>'+
                '<item text="<?php echo _l('Rounding the price up', 1); ?>" id="massupdate_round_price_combi">'+
                    '<item text="<?php echo _l('X,00'); ?>" id="massupdate_round_price_combi_1"/>'+
                    '<item text="<?php echo _l('X,X0'); ?>" id="massupdate_round_price_combi_2"/>'+
                    '<item text="<?php echo _l('X,X0'); ?> <?php echo _l('or'); ?> <?php echo _l('X,X5'); ?>" id="massupdate_round_price_combi_3"/>'+
                    '<item text="<?php echo _l('X,00'); ?> <?php echo _l('or'); ?> <?php echo _l('X,50'); ?>" id="massupdate_round_price_combi_4"/>'+
                    '<item text="<?php echo _l('X,49'); ?> <?php echo _l('or'); ?> <?php echo _l('X,99'); ?>" id="massupdate_round_price_combi_7"/>'+
                    '<item text="<?php echo _l('X,90'); ?>" id="massupdate_round_price_combi_5"/>'+
                    '<item text="<?php echo _l('X,99'); ?>" id="massupdate_round_price_combi_6"/>'+
                    '<item text="<?php echo _l('X9'); ?>" id="massupdate_round_price_combi_8"/>'+
                    '<item text="<?php echo _l('X99'); ?>" id="massupdate_round_price_combi_9"/>'+
                    '<item text="<?php echo _l('X,95'); ?> <?php echo _l('or'); ?> <?php echo _l('X,05'); ?>" id="massupdate_round_price_combi_10"/>'+
                    '<item text="<?php echo _l('Help'); ?>" id="massupdate_round_price_combi_help" img="fad fa-question-circle blue" imgdis="fad fa-question-circle blue"/>'+
                '</item>'+
            '</item>'+
            '<item text="<?php echo _l('Set product price (excluded tax) from its default combination price'); ?>..." id="massupdate_defaultcombiprice"/>'+
            '<item text="<?php echo _l('Rounding the price up', 1); ?>" id="massupdate_round_price">'+
                '<item text="<?php echo _l('X,00'); ?>" id="massupdate_round_price_1"/>'+
                '<item text="<?php echo _l('X,X0'); ?>" id="massupdate_round_price_2"/>'+
                '<item text="<?php echo _l('X,X0'); ?> <?php echo _l('or'); ?> <?php echo _l('X,X5'); ?>" id="massupdate_round_price_3"/>'+
                '<item text="<?php echo _l('X,00'); ?> <?php echo _l('or'); ?> <?php echo _l('X,50'); ?>" id="massupdate_round_price_4"/>'+
                '<item text="<?php echo _l('X,49'); ?> <?php echo _l('or'); ?> <?php echo _l('X,99'); ?>" id="massupdate_round_price_7"/>'+
                '<item text="<?php echo _l('X,90'); ?>" id="massupdate_round_price_5"/>'+
                '<item text="<?php echo _l('X,99'); ?>" id="massupdate_round_price_6"/>'+
                '<item text="<?php echo _l('X9'); ?>" id="massupdate_round_price_8"/>'+
                '<item text="<?php echo _l('X99'); ?>" id="massupdate_round_price_9"/>'+
                '<item text="<?php echo _l('X,95'); ?> <?php echo _l('or'); ?> <?php echo _l('X,05'); ?>" id="massupdate_round_price_10"/>'+
                '<item text="<?php echo _l('Help'); ?>" id="massupdate_round_price_help" img="fad fa-question-circle blue" imgdis="fad fa-question-circle blue"/>'+
            '</item>'+
        '<item text="<?php echo _l('Specific prices',1).': '._l('Fixed prices', 1); ?>..." id="massupdate_specificprices"/>'+
        '</item>'+
        <?php } ?>
    '</menu>';
    cat_cmenu.setIconset('awesome');
    cat_cmenu.loadStruct(contextMenuXML);
    cat_grid.enableContextMenu(cat_cmenu);

    //#####################################
    //############ Events
    //#####################################

    // Click on a product
    function doOnRowSelected(idproduct){
        if (!dhxLayout.cells('b').isCollapsed() && lastProductSelID!=idproduct)
        {
            lastProductSelID=idproduct;
            idxProductName=cat_grid.getColIndexById('name');
            let countSelection = cat_grid.getSelectedRowId().split(',').length;
            let propTitle = '<?php echo _l('Properties', 1).' '._l('of', 1); ?> '+getSelectedItemValueOrID(cat_grid,lastProductSelID,'name');
            if(countSelection>1) {
                propTitle = '<?php echo _l('Properties', 1).' '._l('for', 1); ?> '+ countSelection + ' <?php echo _l('products'); ?>';
            }

            if (propertiesPanel!='descriptions'){
                dhxLayout.cells('b').setText(propTitle);
            }
<?php
    echo eval('?>'.$pluginProductProperties['doOnProductRowSelected'].'<?php ');
?>
        }
    }

    cat_grid.attachEvent("onRowSelect",doOnRowSelected);

    // UISettings
    initGridUISettings(cat_grid);

    cat_grid.attachEvent("onColumnHidden",function(indx,state){
        idxImg=cat_grid.getColIndexById('image');
        if (idxImg && !state){
            cat_grid.setAwaitedRowHeight(<?php echo getGridImageHeight(); ?>);
        }else{
            cat_grid.setAwaitedRowHeight(30);
        }
    });
cat_grid.attachEvent("onFilterEnd", function(elements){
        getGridStat();
    });
cat_grid.attachEvent("onSelectStateChanged", function(id){
        getGridStat();
    });

cat_grid.attachEvent("onDhxCalendarCreated",function(calendar){
    calendar.setSensitiveRange("2012-01-01",null);
});

cat_grid.attachEvent("onScroll",function(){
    marginMatrix_form = cat_grid.getUserData("", "marginMatrix_form");
    cat_grid.forEachRow(function(id){
          calculMarginProduct(id);
          calculMarginProduct(id, "margin_after_reduction");
          calculMarginProduct(id, "margin_wt_amount_after_reduction");
          calculMarginProduct(id, "margin_wt_percent_after_reduction");
    });
});

function displayProducts(callback)
{
    if (catselection!=undefined && catselection!=null && catselection!="" && catselection!=0)
    {
        oldFilters=new Array();
        for(var i=0,l=cat_grid.getColumnsNum();i<l;i++)
        {
            if (cat_grid.getFilterElement(i)!=null && cat_grid.getFilterElement(i).value!='')
                oldFilters[cat_grid.getColumnId(i)]=cat_grid.getFilterElement(i).value;

        }
        cat_grid.editStop(true);
        cat_grid.clearAll(true);
        cat_grid_sb.setText('');
        oldGridView=gridView;
           firstProductsLoading=0;
        cat_grid_sb.setText('<?php echo _l('Loading in progress, please wait...', 1); ?>');

        var params_supp = "";
        <?php if (SCSG) { ?>
        if(isNaN(catselection)==true)
            var is_segment = catselection.search("seg_");
        else
            var is_segment = 0;
        if(is_segment==0)
        {
            <?php echo SegmentHook::hook('productBeforeLoadXML'); ?>
        }
        <?php } ?>
        loadingtime = new Date().getTime();
        let loadUrl = "index.php?ajax=1&act=cat_product_get&tree_mode="+tree_mode+"&productsfrom="+displayProductsFrom+"&idc="+catselection+"&view="+gridView+params_supp+"&id_lang="+SC_ID_LANG+"&"+new Date().getTime();
        ajaxPostCalling(cat_productPanel, cat_grid, loadUrl,function(data)
        {
            cat_grid.parse(data);
            cat_grid._rowsNum=cat_grid.getRowsNum();

            var limit_smartrendering = 0;
            if(cat_grid.getUserData("", "LIMIT_SMARTRENDERING")!=undefined && cat_grid.getUserData("", "LIMIT_SMARTRENDERING")!=0 && cat_grid.getUserData("", "LIMIT_SMARTRENDERING")!=null)
                limit_smartrendering = cat_grid.getUserData("", "LIMIT_SMARTRENDERING");

            if(limit_smartrendering!=0 && cat_grid._rowsNum > limit_smartrendering)
                cat_grid.enableSmartRendering(true);
            else
                cat_grid.enableSmartRendering(false);

            idxQty=cat_grid.getColIndexById('quantity');
            idxQtyUpdate=cat_grid.getColIndexById('quantityupdate');
            idxPriceWithoutTaxes=cat_grid.getColIndexById('price');
            idxPriceIncTaxes=cat_grid.getColIndexById('price_inc_tax');
            idxVAT=cat_grid.getColIndexById(tax_identifier);
            idxManufacturers=cat_grid.getColIndexById('id_manufacturer');
            idxSuppliers=cat_grid.getColIndexById('id_supplier');
            idxMsgAvailableNow=cat_grid.getColIndexById('available_now');
            idxMsgAvailableLater=cat_grid.getColIndexById('available_later');
            idxReductionPrice=cat_grid.getColIndexById('reduction_price');
            idxReductionPercent=cat_grid.getColIndexById('reduction_percent');
            idxReductionFrom=cat_grid.getColIndexById('reduction_from');
            idxReductionTo=cat_grid.getColIndexById('reduction_to');
            idxOnSale=cat_grid.getColIndexById('on_sale');
            idxOutOfStock=cat_grid.getColIndexById('out_of_stock');
            idxActive=cat_grid.getColIndexById('active');
            idxPosition=cat_grid.getColIndexById('position');
            idxMargin=cat_grid.getColIndexById('margin');
            idxName=cat_grid.getColIndexById('name');
            if(idxName!==false)
            {
                cat_grid.setCustomSorting(function(a,b,ord,a_id,b_id){
                    a = sanitizeString(replaceAccentCharacters(latinise(cat_grid.cells(a_id,idxName).getTitle()).toLowerCase()));
                    b = sanitizeString(replaceAccentCharacters(latinise(cat_grid.cells(b_id,idxName).getTitle()).toLowerCase()));
                    return ord=="asc"?(a>b?1:-1):(a>b?-1:1);
                }, idxName);
            }
            if(idxManufacturers!==false)
            {
                cat_grid.setCustomSorting(function(a,b,ord,a_id,b_id){
                    a = latinise(cat_grid.cells(a_id,idxManufacturers).getTitle()).toLowerCase();
                    b = latinise(cat_grid.cells(b_id,idxManufacturers).getTitle()).toLowerCase();
                    return ord=="asc"?(a>b?1:-1):(a>b?-1:1);
                }, idxManufacturers);
            }
            if(idxSuppliers!==false)
            {
                cat_grid.setCustomSorting(function(a,b,ord,a_id,b_id){
                    a = latinise(cat_grid.cells(a_id,idxSuppliers).getTitle()).toLowerCase();
                    b = latinise(cat_grid.cells(b_id,idxSuppliers).getTitle()).toLowerCase();
                    return ord=="asc"?(a>b?1:-1):(a>b?-1:1);
                }, idxSuppliers);
            }
            if (idxPosition && displayProductsFrom=='all' && tree_mode=='single')
            {
                cat_grid_tb.enableItem('setposition');
            }else{
                cat_grid_tb.disableItem('setposition');
            }
            lastEditedCell=0;
            lastColumnRightClicked=0;
            for(var i=0;i<cat_grid.getColumnsNum();i++)
            {
                if (cat_grid.getFilterElement(i)!=null && oldFilters[cat_grid.getColumnId(i)]!=undefined)
                {
                    cat_grid.getFilterElement(i).value=oldFilters[cat_grid.getColumnId(i)];
                }
            }
            cat_grid.filterByAll();

            // UISettings
            loadGridUISettings(cat_grid);
            getGridStat();
            var loadingtimedisplay = ( new Date().getTime() - loadingtime ) / 1000;
            $('#layoutstatusloadingtime').html(" - T: "+loadingtimedisplay+"s");

            if (!cat_grid.doesRowExist(lastProductSelID))
            {
                lastProductSelID=0;
            }else{
                cat_grid.selectRowById(lastProductSelID);
            }

            marginMatrix_form = cat_grid.getUserData("", "marginMatrix_form");
            cat_grid.forEachRow(function(id){
                  calculMarginProduct(id);
                  calculMarginProduct(id, "margin_after_reduction");
                  calculMarginProduct(id, "margin_wt_amount_after_reduction");
                  calculMarginProduct(id, "margin_wt_percent_after_reduction");
            });

            <?php sc_ext::readCustomGridsConfigXML('afterGetRows'); ?>

            // UISettings
            cat_grid._first_loading=0;

            <?php if (_s('APP_DISABLED_COLUMN_MOVE')) { ?>
            cat_grid.enableColumnMove(false);
            <?php } ?>

            if(open_cat_grid == true)
            {
                if (callback==undefined || callback==null || callback=='')
                    callback = ' ';
                if(open_cat_id_attr!=null && open_cat_id_attr!=0)
                    callback = callback + 'displayCombinationPanel();id_product_attributeToSelect='+Number(open_cat_id_attr)+';';
                callback = callback + 'lastProductSelID=0;cat_grid.selectRowById('+open_cat_id_product+',false,true,true);';
                open_cat_grid = false;
            }

               if (callback!='') eval(callback);
        });
    }
}
function getGridStat(){
    let filteredRows=cat_grid.getRowsNum();
    let selectedRows=(cat_grid.getSelectedRowId()?cat_grid.getSelectedRowId().split(',').length:0);
    let nb_rows = (cat_grid._rowsNum!==undefined?cat_grid._rowsNum:0);
    cat_grid_sb.setText(nb_rows+' '+(nb_rows>1?'<?php echo _l('products'); ?>':'<?php echo _l('product'); ?>')+(tree_mode=='all'?' <?php echo _l('in this category and all subcategories'); ?>':' <?php echo _l('in this category'); ?>')+" - <?php echo _l('Filter')._l(':'); ?> "+filteredRows+" - <?php echo _l('Selection')._l(':'); ?> "+selectedRows);
}
function getProductsNum(){
    var i=0;
    cat_grid.forEachRow(function(id){ i++ });
    return i;
}

cat_grid.attachEvent("onBeforeSorting", function(ind,type,direction){
    idxDateAdd=cat_grid.getColIndexById('date_add');
    idxDateUpd=cat_grid.getColIndexById('date_upd');
    idxReductionFrom=cat_grid.getColIndexById('reduction_from');
    idxReductionTo=cat_grid.getColIndexById('reduction_to');
    if(ind==idxDateAdd || ind==idxDateUpd || ind==idxReductionFrom || ind==idxReductionTo)
        cat_grid.setColumnExcellType(ind,"ed");
    return true;
});
cat_grid.attachEvent("onAfterSorting", function(ind,type,direction){
    idxDateAdd=cat_grid.getColIndexById('date_add');
    idxDateUpd=cat_grid.getColIndexById('date_upd');
    idxReductionFrom=cat_grid.getColIndexById('reduction_from');
    idxReductionTo=cat_grid.getColIndexById('reduction_to');
    if(ind==idxDateAdd || ind==idxDateUpd || ind==idxReductionFrom || ind==idxReductionTo)
        cat_grid.setColumnExcellType(ind,"dhxCalendarA");
    return true;
});

function addProductInQueue(rId, action, cIn, vars)
{
    if(cat_grid.getColumnId(cIn) === 'name'){
        let name = cat_grid.cellById(rId,cIn).getValue();
        const specialChars = '\\^<>;=#{}\\!';
        const regex = new RegExp('['+specialChars+']','gim');
        if (regex.test(name)){
            dhtmlx.message({text:"<?php echo _l("Field '%s' cannot contains special chars", false, array(_l('name'))); ?> ("+specialChars.replace('\\','')+")",type:'error',expire:15000});
            return true;
        }
    }

    var params = {
        name: "cat_product_update_queue",
        row: rId,
        action: action,
        params: {},
        callback: "callbackProductUpdate('"+rId+"','"+action+"','"+rId+"',{data});"
    };
    // COLUMN VALUES
        if(cIn!=undefined && cIn!="" && cIn!=null && cIn!=0)
            params.params[cat_grid.getColumnId(cIn)] = cat_grid.cells(rId,cIn).getValue();
        params.params['id_lang'] = SC_ID_LANG;
        if(segselection!=undefined && segselection!=null && segselection!="" && segselection!=0)
            params.params['id_segment'] = segselection;
        else if(catselection!=undefined && catselection!=null && catselection!="" && catselection!=0)
            params.params['id_category'] = catselection;
        if(vars!=undefined && vars!=null && vars!="" && vars!=0)
        {
            $.each(vars, function(key, value){
                params.params[key] = value;
            });
        }
    // USER DATA
        if(rId!=undefined && rId!=null && rId!="" && rId!=0)
        {
            $.each(cat_grid.UserData[rId].keys, function(i, key){
                params.params[key] = cat_grid.UserData[rId].values[i];
            });
        }
        $.each(cat_grid.UserData.gridglobaluserdata.keys, function(i, key){
            params.params[key] = cat_grid.UserData.gridglobaluserdata.values[i];
        });
    /*
     * FOR EXTENSIONS
     */

    params.params = JSON.stringify(params.params);
    addInUpdateQueue(params,cat_grid);
}

    function updateCombinationsGridValues(sid) {
        let idxCombinations = cat_grid.getColIndexById('combinations');
        if(idxCombinations) {
            cat_grid.cells(sid, idxCombinations).setValue('combinations_' + sid);
        }
    }

    function updateMultipleFeaturesGroupsGridValues(sid) {
        let idxFeatures = cat_grid.getColIndexById('features');
        if(idxFeatures) {
            cat_grid.cells(sid, idxFeatures).setValue('features_' + sid);
        }
    }

    function updateCategoriesGridValues(sid) {
        let idxCategories = cat_grid.getColIndexById('categories');
        if(idxCategories) {
            cat_grid.cells(sid, idxCategories).setValue('categories_' + sid);
        }
    }

    function callbackProductUpdate(sid,action,tid,xml)
{
    <?php
        sc_ext::readCustomGridsConfigXML('onAfterUpdate');
    ?>
    if(xml!=undefined && xml!=null && xml!="" && xml!=0)
    {
        var dbQty = xml.newQuantity;
        if (dbQty!='')
        {
            idxQty=cat_grid.getColIndexById('quantity');
            if (idxQty!=null)
                cat_grid.cells(sid,idxQty).setValue(dbQty);
        }
        var id_specific_price = xml.id_specific_price;
        if (id_specific_price!='')
        {
            cat_grid.setUserData(sid,"id_specific_price", id_specific_price);
        }

        // refresh des cells de la grid si besoin
        var doRefreshGridColCombinations = xml.doRefreshGridColCombinations;
        if(doRefreshGridColCombinations == 1){
            updateCombinationsGridValues(sid);
        }
        var doRefreshGridColCategories = xml.doRefreshGridColCategories;
        if(doRefreshGridColCategories == 1){
            updateCategoriesGridValues(sid);
        }
        var doRefreshGridColMultipleFeatures = xml.doRefreshGridColMultipleFeatures;
        if(doRefreshGridColMultipleFeatures == 1){
            updateMultipleFeaturesGroupsGridValues(sid);
        }

        //refresh panel
        var doUpdateCombinations = xml.doUpdateCombinationsOption;
        if (doUpdateCombinations==1)
        {
            if(propertiesPanel=='combinations'){
                displayCombinations();
            }
        }
        // affichage erreur
        var displayErrorMessage = xml.displayErrorMessage;
        if (displayErrorMessage)
        {
            dhtmlx.message({text:displayErrorMessage,type:'error',expire:15000});
        }
        var refresh_cat = xml.refresh_cat;
        if (refresh_cat=='1')
        {
            if(propertiesPanel=='categories') {
                displayCategories('',true);
            }
        }
        var refresh_multipleFeatures = xml.refresh_multipleFeatures;
        if (refresh_multipleFeatures=='1')
        {
            if(propertiesPanel=='multiplefeatures') {
                displayMultipleFeaturesGroups();
            }
        }
    }

    if (action=='insert')
    {
        idxProductID=cat_grid.getColIndexById('id');
        cat_grid.cells(sid,idxProductID).setValue(tid);
        cat_grid.changeRowId(sid,tid);
        cat_grid.setRowHidden(tid, false);
        cat_grid.showRow(tid);
        cat_productPanel.progressOff();
    }
    else if (action=='update')
        cat_grid.setRowTextNormal(sid);
    else if(action=='delete')
        cat_grid.deleteRow(sid);
    else if(action=='position')
    {
        idxPosition=cat_grid.getColIndexById('position');
        displayProducts('cat_grid.sortRows('+idxPosition+', "int", "asc");');
    }
};

function specificPricesMassUpdateInQueue(selection, params)
{
    var ids = selection.split(',');
    $.each(ids, function(num, pId){
        var vars = params;
        addSpecificPricesMassUpdateInQueue(pId, "update", null, vars);
    });
}
function addSpecificPricesMassUpdateInQueue(rId, action, cIn, vars)
{   let callback = "displaySpecificPrices();";
    if(typeof prop_tb._specificpricesGrid === 'undefined'){
        callback = '';
    }
    var params = {
        name: "cat_product_massupdate_queue",
        rowId: rId,
        action: action,
        params: {},
        callback: callback
    };
    // COLUMN VALUES
    params.params["id_lang"] = SC_ID_LANG;
    if(vars!=undefined && vars!=null && vars!="" && vars!=0)
    {
        $.each(vars, function(key, value){
            params.params[key] = value;
        });
    }

    params.params = JSON.stringify(params.params);
    addInUpdateQueue(params,cat_grid);
}
<?php
    if (_s('CAT_PROD_GRID_DRAG2CAT_DEFAULT') == 'copy')
    {
        echo "cat_grid_tb.setItemState('copytocateg', true);\n";
        echo "copytocateg=true;\n";
    }
?>

</script>
