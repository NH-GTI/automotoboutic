<?php
if (!defined('STORE_COMMANDER')) { exit; }
 if (_r('GRI_CAT_PROPERTIES_DOWNLOAD_PRODUCT')) { ?>
prop_tb.addListOption('panel', 'productdownload', 20, "button", '<?php echo _l('Downloadable product', 1); ?>', "fad fa-cloud-download-alt");
allowed_properties_panel[allowed_properties_panel.length] = "productdownload";
<?php } ?>

prop_tb.addButton("productdownload_refresh",1000, "", "fa fa-sync green", "fa fa-sync green");
prop_tb.setItemToolTip('productdownload_refresh','<?php echo _l('Refresh grid', 1); ?>');
prop_tb.addButtonTwoState('productdownload_lightNavigation', 1000, "", "fa fa-mouse-pointer", "fa fa-mouse-pointer");
prop_tb.setItemToolTip('productdownload_lightNavigation','<?php echo _l('Light navigation (simple click on grid)', 1); ?>');
prop_tb.addButton("productdownload_add",1000, "", "fa fa-plus-circle green", "fa fa-plus-circle green");
prop_tb.setItemToolTip('productdownload_add','<?php echo _l('Add file', 1); ?>');
prop_tb.addButton("productdownload_delete",1000, "", "fa fa-minus-circle red", "fa fa-minus-circle red");
prop_tb.setItemToolTip('productdownload_delete','<?php echo _l('Delete file', 1); ?>');
prop_tb.addButton("productdownload_edit",1000, "", "fad fa-edit", "fad fa-edit");
prop_tb.setItemToolTip('productdownload_edit','<?php echo _l('Edit file', 1); ?>');
prop_tb.addButton("productdownload_download",1000, "", "fad fa-sign-out fa-flip-horizontal green", "fad fa-sign-out fa-flip-horizontal green");
prop_tb.setItemToolTip('productdownload_download','<?php echo _l('Download file', 1); ?>');

clipboardType_productdownload = null;

needInitProductDownload = 1;
function initProductDownload()
{
    if (needInitProductDownload)
    {
        prop_tb._productdownloadLayout = dhxLayout.cells('b').attachLayout('2E');
        prop_tb._productdownloadLayout.cells('a').hideHeader();
        dhxLayout.cells('b').showHeader();
        prop_tb._productdownloadLayout.cells('b').setText('<?php echo _l('Upload file', 1); ?>');
            prop_tb._productdownloadLayout.cells('b').collapse();

            prop_tb._productdownloadGrid = prop_tb._productdownloadLayout.cells('a').attachGrid();
            prop_tb._productdownloadGrid._name='_productdownloadGrid';
            prop_tb._productdownloadGrid.setImagePath("lib/js/imgs/");
              prop_tb._productdownloadGrid.enableDragAndDrop(false);
            prop_tb._productdownloadGrid.enableMultiselect(true);
            
            prop_tb._productdownloadGrid.attachEvent("onDhxCalendarCreated",function(calendar){
                calendar.setSensitiveRange("2012-01-01",null);
            });
            
            prop_tb._productdownloadGrid.attachEvent("onBeforeSorting", function(ind,type,direction){
                idxExpire=prop_tb._productdownloadGrid.getColIndexById('date_expiration');
                if(ind==idxExpire)
                    prop_tb._productdownloadGrid.setColumnExcellType(ind,"ed");
                return true;
            });
            prop_tb._productdownloadGrid.attachEvent("onAfterSorting", function(ind,type,direction){
                idxExpire=prop_tb._productdownloadGrid.getColIndexById('date_expiration');
                if(ind==idxExpire)
                    prop_tb._productdownloadGrid.setColumnExcellType(ind,"dhxCalendarA");
                return true;
            });

            // UISettings
            prop_tb._productdownloadGrid._uisettings_prefix='cat_productdownload';
            prop_tb._productdownloadGrid._uisettings_name=prop_tb._productdownloadGrid._uisettings_prefix;
               prop_tb._productdownloadGrid._first_loading=1;
               
            // UISettings
            initGridUISettings(prop_tb._productdownloadGrid);

            prop_tb._productdownloadGrid.attachEvent("onEditCell", function(stage, rId, cIn){

        if (stage==1 && this.editor && this.editor.obj) this.editor.obj.select(); 
        return true;
            });
        productdownloadDataProcessorURLBase="index.php?ajax=1&act=cat_productdownload_update&id_product="+lastProductSelID+"&id_lang="+SC_ID_LANG;
        productdownloadDataProcessor = new dataProcessor(productdownloadDataProcessorURLBase);
        productdownloadDataProcessor.enableDataNames(true);
        productdownloadDataProcessor.setTransactionMode("POST");
        productdownloadDataProcessor.enablePartialDataSend(true);
        productdownloadDataProcessor.attachEvent("onAfterUpdate",function(sid,action,tid,xml){
                if (action=='insert')
                    prop_tb._productdownloadGrid.cells(tid,0).setValue(tid);
            });
        productdownloadDataProcessorURLBase="index.php?ajax=1&act=cat_productdownload_update&id_product="+lastProductSelID+"&id_lang="+SC_ID_LANG;
        productdownloadDataProcessor.serverProcessor=productdownloadDataProcessorURLBase;
        productdownloadDataProcessor.init(prop_tb._productdownloadGrid);
        
        needInitProductDownload=0;
// Context menu for product download

        productdownload_cmenu=new dhtmlXMenuObject();
        productdownload_cmenu.renderAsContextMenu();

        function onGridProductDownloadContextButtonClick(itemId)
        {
            tabId=prop_tb._productdownloadGrid.contextID.split('_');
            tabId=tabId[0];

            if (itemId=="copy")
            {
                if (lastColumnRightClicked_productdownload!=0)
            {
                    clipboardValue_productdownload=prop_tb._productdownloadGrid.cells(tabId,lastColumnRightClicked_productdownload).getValue();
                    productdownload_cmenu.setItemText('paste' , '<?php echo _l('Paste'); ?> '+prop_tb._productdownloadGrid.cells(tabId,lastColumnRightClicked_productdownload).getTitle());
                    clipboardType_productdownload=lastColumnRightClicked_productdownload;
                }
            }
            if (itemId=="paste")
            {
                if (lastColumnRightClicked_productdownload!=0 && clipboardValue_productdownload!=null && clipboardType_productdownload==lastColumnRightClicked_productdownload)
                {
                    selection=prop_tb._productdownloadGrid.getSelectedRowId();
                    if (selection!='' && selection!=null)
                    {
                        selArray=selection.split(',');
                        for(i=0 ; i < selArray.length ; i++)
                        {
                            var oValue = prop_tb._productdownloadGrid.cells(selArray[i],lastColumnRightClicked_productdownload).getValue();
                            prop_tb._productdownloadGrid.cells(selArray[i],lastColumnRightClicked_productdownload).setValue(clipboardValue_productdownload);
                            prop_tb._productdownloadGrid.cells(selArray[i],lastColumnRightClicked_productdownload).cell.wasChanged=true;
                            productdownloadDataProcessor.setUpdated(selArray[i],true,"updated");
                        }
                    }
                }
            }
        }
        productdownload_cmenu.attachEvent("onClick", onGridProductDownloadContextButtonClick);
        var contextMenuXML='<menu absolutePosition="auto" mode="popup" maxItems="8"  globalCss="contextMenu" globalSecondCss="contextMenu" globalTextCss="contextMenuItem">'+
            '<item text="<?php echo _l('Copy'); ?>" id="copy"/>'+
            '<item text="<?php echo _l('Paste'); ?>" id="paste"/>'+
            '</menu>';
        productdownload_cmenu.loadStruct(contextMenuXML);
        prop_tb._productdownloadGrid.enableContextMenu(productdownload_cmenu);
        prop_tb._productdownloadGrid.attachEvent("onBeforeContextMenu", function(rowid,colidx,grid){
        var disableOnCols=new Array(
            prop_tb._productdownloadGrid.getColIndexById('id_product_download'),
            prop_tb._productdownloadGrid.getColIndexById('id_product'),
            prop_tb._productdownloadGrid.getColIndexById('reference'),
            prop_tb._productdownloadGrid.getColIndexById('supplier_reference'),
            prop_tb._productdownloadGrid.getColIndexById('name'),
            prop_tb._productdownloadGrid.getColIndexById('display_filename'),
            prop_tb._productdownloadGrid.getColIndexById('date_add'),
            prop_tb._productdownloadGrid.getColIndexById('<?php if (version_compare(_PS_VERSION_, '1.5.0.0', '<'))
{
    echo 'physically_';
} ?>filename')
            );
            if (in_array(colidx,disableOnCols))
            {
                return false;
            }
            lastColumnRightClicked_productdownload=colidx;
            if (lastColumnRightClicked_productdownload==clipboardType_productdownload)
            {
                productdownload_cmenu.setItemEnabled('paste');
            }else{
                productdownload_cmenu.setItemDisabled('paste');
            }
            if (prop_tb._productdownloadGrid.cells(rowid,0).getValue()=='NEW')
            {
                combi_cmenu.setItemDisabled('copy');
            }else{
                combi_cmenu.setItemEnabled('copy');
            }
            return true;
        });
    }
}
function setPropertiesPanel_productdownload(id){
    if (id=='productdownload')
    {
        if(lastProductSelID!=undefined && lastProductSelID!="")
        {
            dhxLayout.cells('b').setText('<?php echo _l('Properties', 1).' '._l('of', 1); ?> '+getSelectedItemValueOrID(cat_grid,lastProductSelID,'name'));
}
hidePropTBButtons();
prop_tb.showItem('productdownload_refresh');
prop_tb.showItem('productdownload_lightNavigation');
prop_tb.showItem('productdownload_add');
prop_tb.showItem('productdownload_delete');
prop_tb.showItem('productdownload_edit');
prop_tb.showItem('productdownload_download');
prop_tb.setItemText('panel', '<?php echo _l('Downloadable product', 1); ?>');
    prop_tb.setItemImage('panel', 'fad fa-cloud-download-alt');
    needInitProductDownload = 1;
    initProductDownload();
    propertiesPanel='productdownload';
    if (lastProductSelID!=0)
    {
        displayProductDownload();
    }
}
if (id=='productdownload_refresh')
{
    displayProductDownload();
}
if (id=='productdownload_add') {
    let ids = cat_grid.getSelectedRowId();
    if (!ids) {
        dhtmlx.message({text: '<?php echo _l('To add a downloadable file, you must select one product only.', 1); ?>', type: 'error', expire: 10000});
        return false;
    }

    let nb = prop_tb._productdownloadGrid.getRowsNum();
    if (nb > 0) {
        dhtmlx.message({text: '<?php echo _l('This product already has a downloadable file.', 1); ?>', type: 'error', expire: 10000});
        return false;
    }

    prop_tb._productdownloadLayout.cells('b').expand();
    prop_tb._productdownloadLayout.cells('b').attachURL("index.php?ajax=1&act=cat_productdownload_upload", null, {
        id_product: ids
    });
}
if (id=='productdownload_edit')
{
    let id_product_download = prop_tb._productdownloadGrid.getSelectedRowId();
    let ids_split = id_product_download.split(",");
    if (ids_split.length > 1) {
        dhtmlx.message({text: '<?php echo _l('To edit a document, you must select one row only.', 1); ?>', type: 'error', expire: 10000});
        return false;
    }

    prop_tb._productdownloadLayout.cells('b').expand();
    let idxFilename = prop_tb._productdownloadGrid.getColIndexById('display_filename');
    let name = prop_tb._productdownloadGrid.cells(id_product_download, idxFilename).getValue();
    prop_tb._productdownloadLayout.cells('b').setText('<?php echo _l('Edit document file', 1); ?> "' + name + '"');
    prop_tb._productdownloadLayout.cells('b').attachURL("index.php?ajax=1&act=cat_productdownload_upload", null, {
        id_product_download : id_product_download
    });
}
if (id=='productdownload_delete')
{
    if (prop_tb._productdownloadGrid.getSelectedRowId()==null)
    {
        alert('<?php echo _l('Please select an item', 1); ?>');
    }else{
    if (confirm('<?php echo _l('Are you sure you want to delete the selected items?', 1); ?>'))
        {
            prop_tb._productdownloadGrid.deleteSelectedRows();
        }
    }
}
if (id=='productdownload_download')
{
    if (prop_tb._productdownloadGrid.getSelectedRowId()==null)
    {
        alert('<?php echo _l('Please select an item', 1); ?>');
    }else{
        idxName=prop_tb._productdownloadGrid.getColIndexById('display_filename');
        idxFilename=prop_tb._productdownloadGrid.getColIndexById('<?php if (version_compare(_PS_VERSION_, '1.5.0.0', '<'))
{
    echo 'physically_';
} ?>filename');
        window.open("index.php?ajax=1&act=cat_productdownload_getfile&name="+prop_tb._productdownloadGrid.cells(prop_tb._productdownloadGrid.getSelectedRowId(),idxName).getValue()+"&file="+prop_tb._productdownloadGrid.cells(prop_tb._productdownloadGrid.getSelectedRowId(),idxFilename).getValue());
    }
}
    }
    prop_tb.attachEvent("onClick", setPropertiesPanel_productdownload);

    prop_tb.attachEvent("onStateChange",function(id,state){
        if (id=='productdownload_lightNavigation')
        {
            if (state)
            {
                prop_tb._productdownloadGrid.enableLightMouseNavigation(true);
            }else{
                prop_tb._productdownloadGrid.enableLightMouseNavigation(false);
            }
        }
    });

    function displayProductDownload()
    {
        prop_tb._productdownloadGrid.clearAll(true);
        var tempIdList = (cat_grid.getSelectedRowId()!=null?cat_grid.getSelectedRowId():"");
        $.post("index.php?ajax=1&act=cat_productdownload_get&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),{'idlist': tempIdList},function(data)
        {
            prop_tb._productdownloadGrid.parse(data);
            nb=prop_tb._productdownloadGrid.getRowsNum();
                prop_tb._productdownloadGrid._rowsNum=nb;
                
            // UISettings
                loadGridUISettings(prop_tb._productdownloadGrid);
                prop_tb._productdownloadGrid._first_loading=0;
        });
    }

    let productdownload_current_id = 0;
    cat_grid.attachEvent("onRowSelect",function (idproduct){
        if (propertiesPanel=='productdownload' && (cat_grid.getSelectedRowId()!==null && productdownload_current_id!=idproduct)){

            displayProductDownload();
            prop_tb._productdownloadLayout.cells('b').collapse();
            productdownload_current_id=idproduct;
        }
    });