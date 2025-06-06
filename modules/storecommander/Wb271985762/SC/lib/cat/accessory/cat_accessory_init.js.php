<?php
if (!defined('STORE_COMMANDER')) { exit; }

 if (_r('GRI_CAT_PROPERTIES_GRID_ACCESSORIES')) { ?>
        prop_tb.addListOption('panel', 'accessories', 7, "button", '<?php echo _l('Accessories', 1); ?>', "fad fa-bags-shopping blue");
        allowed_properties_panel[allowed_properties_panel.length] = "accessories";
    <?php } ?>
    
    prop_tb.addButton('accessory_refresh',1000,'','fa fa-sync green','fa fa-sync green');
    prop_tb.setItemToolTip('accessory_refresh','<?php echo _l('Refresh grid', 1); ?>');
    let accessory_opts = [
        ['reset', 'obj', '<?php echo _l('Reset filters'); ?>', ''],
        ['separator1', 'sep', '', ''],
        ['selected_products', 'obj', '<?php echo _l('View only accessories from selected products'); ?>', ''],
        ['same_category', 'obj', '<?php echo _l('View only used accessories in the same category'); ?>', ''],
        ['all_category_products', 'obj', '<?php echo _l('View products from selected category'); ?>', '']
    ];
    prop_tb.addButtonSelect("accessory_filters", 1000, "", accessory_opts, "fa fa-filter", "fa fa-filter",true,true);
    prop_tb.setItemToolTip('accessory_filters','<?php echo _l('Filter options'); ?>');

    prop_tb.addButton("accessory_goto",1000, "", "fad fa-search", "fad fa-search");
    prop_tb.setItemToolTip('accessory_goto','<?php echo _l('See product'); ?>');
    prop_tb.addButtonTwoState('accessory_loadallproducts',1000,'','fad fa-sync green','fad fa-sync green');
    prop_tb.setItemToolTip('accessory_loadallproducts','<?php echo _l('Load the first 1000 products', 1); ?>');
    prop_tb.addButton('accessory_del',1000,'','fa fa-minus-circle red','fa fa-minus-circle red');
    prop_tb.setItemToolTip('accessory_del','<?php echo _l('Delete selected products from the list', 1); ?>');
    prop_tb.addButton("accessory_add_select",1000, "", "fad fa-link yellow", "fad fa-link yellow");
    prop_tb.setItemToolTip('accessory_add_select','<?php echo _l('Add link between selected accessories and selected products', 1); ?>');
    prop_tb.addButton("accessory_del_select",1000, "", "fad fa-unlink red", "fad fa-unlink red");
    prop_tb.setItemToolTip('accessory_del_select','<?php echo _l('Remove link between selected accessories and selected products', 1); ?>');
    prop_tb.addButton("accessory_selectall",1000, "", 'fa fa-bolt yellow', 'fa fa-bolt yellow');
    prop_tb.setItemToolTip('accessory_selectall','<?php echo _l('Select all'); ?>');
    var display_all_products = (Cookies.get('sc_cat_accessory_display_all_products')==1?1:0);
    prop_tb.setItemState('accessory_loadallproducts' ,display_all_products);
                
    function onEditCellAccessories(stage,rId,cInd,nValue,oValue)
    {
        var inQueue = false;
        var vars = null;
        idxUsed=prop_tb._accessoriesGrid.getColIndexById('used');
        if (cInd == idxUsed){
            if(stage==1)
            {
                inQueue = true;
                vars = {"sub_action":prop_tb._accessoriesGrid.cells(rId,idxUsed).getValue(),"idlist":cat_grid.getSelectedRowId()};
            }
        }
        idxActive=prop_tb._accessoriesGrid.getColIndexById('active');
        if (cInd == idxActive){
            if(stage==2)
            {
                inQueue = true;
                vars = {"sub_action":"active_accessory","value":prop_tb._accessoriesGrid.cells(rId,idxActive).getValue()};
            }
        }
        
        if(inQueue==true)
        {
            addAccessoryInQueue(rId, "update", cInd, vars);
        }
        
        return true;
    }

    clipboardType_Accessories = null;    
    needInitAccessories = 1;
    lastSelectedAccessory = 0;
    var filter_params = "";
    var oldFilters = new Object();
    function initAccessories(){
        if (needInitAccessories)
        {
            prop_tb._accessoriesLayout = dhxLayout.cells('b').attachLayout('1C');
            prop_tb._accessoriesLayout.cells('a').hideHeader();
            dhxLayout.cells('b').showHeader();
            prop_tb._accessoriesGrid = prop_tb._accessoriesLayout.cells('a').attachGrid();
            prop_tb._accessoriesGrid._name='_accessoriesGrid';
              prop_tb._accessoriesGrid.enableDragAndDrop(true);
            prop_tb._accessoriesGrid.enableMultiselect(true);
            prop_tb._accessoriesGrid.enableColumnMove(false);
            prop_tb._accessoriesGrid.enableHeaderMenu('false,false,false,false,false,false,false,false,false,false,false');

            // UISettings
            prop_tb._accessoriesGrid._uisettings_prefix='cat_accessory';
            prop_tb._accessoriesGrid._uisettings_name=prop_tb._accessoriesGrid._uisettings_prefix;
               prop_tb._accessoriesGrid._first_loading=1;
               
            // UISettings
            initGridUISettings(prop_tb._accessoriesGrid);
            
            prop_tb._accessoriesGrid.attachEvent("onEditCell",onEditCellAccessories);
                
            prop_tb._accessoriesGrid.attachEvent("onDrag",function(sId,tId,sObj,tObj,sInd,tInd){
                if (sObj._name=='grid')
                {
                    if (cat_grid.getSelectedRowId())
                    {
                        let idxName=cat_grid.getColIndexById('name');
                        let idxReference=cat_grid.getColIndexById('reference');
                        let idxActive=cat_grid.getColIndexById('active');
                        <?php if (_s('CAT_PROPERTIES_ACCESSORY_IMAGE')) { ?>
                        let idxImage=cat_grid.getColIndexById('image');
                        <?php } ?>
                        selArray=cat_grid.getSelectedRowId().split(',');
                        for(i=0 ; i < selArray.length ; i++)
                            if (!prop_tb._accessoriesGrid.doesRowExist(selArray[i]))
                            {
                                prop_tb._accessoriesGrid._rowsNum++;
                                prop_tb._accessoriesGrid.addRow(
                                    selArray[i],
                                    [
                                        selArray[i],
                                        '0',
                                        cat_grid.cells(selArray[i],idxActive).getValue(),
                                        <?php if (_s('CAT_PROPERTIES_ACCESSORY_IMAGE')) { ?>
                                        cat_grid.cells(selArray[i],idxImage).getValue(),
                                        <?php } ?>
                                        cat_grid.cells(selArray[i],idxReference).getValue(),
                                        cat_grid.cells(selArray[i],idxName).getValue()
                                    ]
                                );
                            }
                        cat_grid.showRow(selArray[i-1]);
                        nb=prop_tb._accessoriesGrid._rowsNum;
                        prop_tb._sb.setText(nb+(nb>1?" <?php echo _l('accessories'); ?>":" <?php echo _l('accessory'); ?>"));
                    }
                }
                return false;
            });
                
            prop_tb._accessoriesGrid.attachEvent("onRowSelect",function(id){
                lastSelectedAccessory = id;
            });

            // Context menu
            accessories_cmenu=new dhtmlXMenuObject();
            accessories_cmenu.renderAsContextMenu();
            function onGridAccessoriesContextButtonClick(itemId){
                tabId=prop_tb._accessoriesGrid.contextID.split('_');
                tabId=tabId[0];
                if (itemId=="copy"){
                    if (lastColumnRightClicked_Accessories!=0)
                    {
                        clipboardValue_Accessories=prop_tb._accessoriesGrid.cells(tabId,lastColumnRightClicked_Accessories).getValue();
                        accessories_cmenu.setItemText('paste' , '<?php echo _l('Paste'); ?> '+prop_tb._accessoriesGrid.cells(tabId,lastColumnRightClicked_Accessories).getTitle());
                        clipboardType_Accessories=lastColumnRightClicked_Accessories;
                    }
                }
                if (itemId=="paste"){
                    if (lastColumnRightClicked_Accessories!=0 && clipboardValue_Accessories!=null && clipboardType_Accessories==lastColumnRightClicked_Accessories)
                    {
                        selection=prop_tb._accessoriesGrid.getSelectedRowId();
                        if (selection!='' && selection!=null)
                        {
                            selArray=selection.split(',');
                            for(i=0 ; i < selArray.length ; i++)
                            {
                                if (prop_tb._accessoriesGrid.getColumnId(lastColumnRightClicked_Accessories).substr(0,5)!='attr_')
                                {
                                    prop_tb._accessoriesGrid.cells(selArray[i],lastColumnRightClicked_Accessories).setValue(clipboardValue_Accessories);
                                    prop_tb._accessoriesGrid.cells(selArray[i],lastColumnRightClicked_Accessories).cell.wasChanged=true;
                                    onEditCellAccessories(2,selArray[i],lastColumnRightClicked_Accessories);
                                }
                            }
                        }
                    }
                }
            }
            accessories_cmenu.attachEvent("onClick", onGridAccessoriesContextButtonClick);
            var contextMenuXML='<menu absolutePosition="auto" mode="popup" maxItems="8"  globalCss="contextMenu" globalSecondCss="contextMenu" globalTextCss="contextMenuItem">'+
                    '<item text="Object" id="object" enabled="false"/>'+
                    '<item text="<?php echo _l('Copy'); ?>" id="copy"/>'+
                    '<item text="<?php echo _l('Paste'); ?>" id="paste"/>'+
                '</menu>';
            accessories_cmenu.loadStruct(contextMenuXML);
            prop_tb._accessoriesGrid.enableContextMenu(accessories_cmenu);

            prop_tb._accessoriesGrid.attachEvent("onBeforeContextMenu", function(rowid,colidx,grid){
                var disableOnCols=new Array(
                        prop_tb._accessoriesGrid.getColIndexById('active')
                        );
                if (in_array(colidx,disableOnCols))
                {
                    return false;
                }
                lastColumnRightClicked_Accessories=colidx;
                accessories_cmenu.setItemText('object', '<?php echo _l('Accessory:'); ?> '+prop_tb._accessoriesGrid.cells(rowid,prop_tb._accessoriesGrid.getColIndexById('name')).getTitle());
                if (lastColumnRightClicked_Accessories==clipboardType_Accessories)
                {
                    accessories_cmenu.setItemEnabled('paste');
                }else{
                    accessories_cmenu.setItemDisabled('paste');
                }
                return true;
            });
            
            accessoriesFilter=0;
            accessoriesFilterByProducts=0;
            productsFilterByCategory=0;
            needInitAccessories=0;
        }
    }



    function setPropertiesPanel_accessories(id){
        if (id=='accessories')
        {
            if(lastProductSelID!=undefined && lastProductSelID!="")
            {
                dhxLayout.cells('b').setText('<?php echo _l('Properties', 1).' '._l('of', 1); ?> '+getSelectedItemValueOrID(cat_grid,lastProductSelID,'name'));
            }
            hidePropTBButtons();
            prop_tb.showItem('accessory_refresh');
            prop_tb.showItem('accessory_filters');
            prop_tb.showItem('accessory_goto');
            prop_tb.showItem('accessory_selectall');
            prop_tb.showItem('accessory_loadallproducts');
            prop_tb.showItem('accessory_del');
            prop_tb.showItem('accessory_add_select');
            prop_tb.showItem('accessory_del_select');
            prop_tb.setItemText('panel', '<?php echo _l('Accessories', 1); ?>');
            prop_tb.setItemImage('panel', 'fad fa-bags-shopping blue');
            needInitAccessories=1; 
            initAccessories();
            propertiesPanel='accessories';
            if (lastProductSelID!=0)
            {
                displayAccessories('',0);
            }
        }
        if (id=='accessory_refresh')
        {
            prop_tb._accessoriesGrid._rowsNum=0;
            prop_tb._accessoriesGrid.clearAll(true);
            displayAccessories('',0);
        }
        if (id=='accessory_del')
        {
            if (prop_tb._accessoriesGrid.getSelectedRowId()!=null && prop_tb._accessoriesGrid.getSelectedRowId()!='' && confirm('<?php echo _l('Are you sure you want to delete the selected items?', 1); ?>'))
            {
                var selection = prop_tb._accessoriesGrid.getSelectedRowId();
                ids=selection.split(',');
                $.each(ids, function(num, rId){
                    var params = {
                        name: "cat_accessory_update_queue",
                        row: rId,
                        action: "delete",
                        params: {},
                        callback: "callbackAccessory('"+rId+"','delete','"+rId+"');"
                    };                    
                    params.params = JSON.stringify(params.params);
                    prop_tb._accessoriesGrid.setRowTextStyle(rId, "text-decoration: line-through;");
                    addInUpdateQueue(params,prop_tb._accessoriesGrid);
                });
            }
        }
        if (id=='accessory_add_select')
        {
            vars = {"sub_action":"addSel","idlist":cat_grid.getSelectedRowId(),"id_accessory":prop_tb._accessoriesGrid.getSelectedRowId()};
            
            var ids = prop_tb._accessoriesGrid.getSelectedRowId();
            var p_ids = new Array();
            if(ids.search(",")>=0)
                p_ids = ids.split(",");
            else
                p_ids[0] = ids;
        
            $.each(p_ids, function(num, p_id){
                addAccessoryInQueue(p_id, "update", null, vars);
            });
        }
        if (id=='accessory_del_select')
        {
            if (cat_grid.getSelectedRowId()!=null && prop_tb._accessoriesGrid.getSelectedRowId()!='' && confirm('<?php echo _l('Are you sure you want to dissociate the selected items?', 1); ?>'))
            {
                vars = {"sub_action":"delSel","idlist":cat_grid.getSelectedRowId(),"id_accessory":prop_tb._accessoriesGrid.getSelectedRowId()};
                var ids = prop_tb._accessoriesGrid.getSelectedRowId();
                var p_ids = new Array();
                if(ids.search(",")>=0)
                    p_ids = ids.split(",");
                else
                    p_ids[0] = ids;
            
                $.each(p_ids, function(num, p_id){
                    addAccessoryInQueue(p_id, "update", null, vars);
                });
            }
        }
        if (id=='accessory_goto')
        {
            accessory_goTo();
        }
        if (id=='accessory_selectall')
        {
            prop_tb._accessoriesGrid.selectAll();
        }
        if (prop_tb.getParentId(id) == 'accessory_filters') {
            switch(id) {
                case 'same_category':
                    accessoriesFilter=1;
                    accessoriesFilterByProducts=0;
                    productsFilterByCategory=0;
                    prop_tb.setItemText('accessory_filters', '<?php echo _l('Selected category'); ?>');
                    break;
                case 'selected_products':
                    accessoriesFilter=0;
                    accessoriesFilterByProducts=1;
                    productsFilterByCategory=0;
                    prop_tb.setItemText('accessory_filters', '<?php echo _l('Selected products'); ?>');
                    break;
                case 'all_category_products':
                    /*force all products from category*/
                    accessoriesFilter=1;
                    accessoriesFilterByProducts=0;
                    productsFilterByCategory=1;
                    prop_tb.setItemText('accessory_filters', '<?php echo _l('Category products'); ?>');
                    break;
                case 'reset':
                    accessoriesFilter=0;
                    accessoriesFilterByProducts=0;
                    productsFilterByCategory=0;
                    filter_params = "";
                    prop_tb.setItemText('accessory_filters', '');
                    break;
            }

            prop_tb.setItemState('accessory_loadallproducts', 0);
            prop_tb.callEvent("onStateChange", ['accessory_loadallproducts', false]);
        }
    }
    prop_tb.attachEvent("onClick", setPropertiesPanel_accessories);

    prop_tb.attachEvent("onStateChange",function(id,state){
        if (id=='accessory_loadallproducts'){
            if (state) {
              display_all_products=1;
              prop_tb.callEvent("onClick", ['reset']);
            }else{
              display_all_products=0;
            }
            Cookies.set('sc_cat_accessory_display_all_products',display_all_products, defaultCookieOptions);
            
            prop_tb._accessoriesGrid._rowsNum=0;
            prop_tb._accessoriesGrid.clearAll(true);
            displayAccessories('');
        }
    });



function displayAccessories(callback)
{
    if (prop_tb._accessoriesGrid._rowsNum > 0 && accessoriesFilterByProducts == 0 && productsFilterByCategory == 0)
    {
        prop_tb._accessoriesGrid.uncheckAll();
        if(cat_grid.getSelectedRowId()!="")
        {
            $.post("index.php?ajax=1&act=cat_accessory_relation_get&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),{"idlist":cat_grid.getSelectedRowId()},function(data){
                    if (data!='')
                    {
                        let idxUsed=prop_tb._accessoriesGrid.getColIndexById('used');
                        selArray=data.split(',');
                        selArray.forEach(function(id_access){
                            if (prop_tb._accessoriesGrid.doesRowExist(id_access)) {
                                prop_tb._accessoriesGrid.cells(id_access,idxUsed).setValue(1);
                            }
                        });
                        prop_tb._accessoriesGrid.filterByAll();

                        prop_tb._accessoriesGrid.enableHeaderMenu('false,false,false,false,false,false,false,false,false,false,false');
                        prop_tb._accessoriesGrid.enableColumnMove(false);
                    }
                });
        }
    }else{
        prop_tb._accessoriesGrid.clearAll(true);
        prop_tb._accessoriesGrid.load("index.php?ajax=1&act=cat_accessory_get&filter_params="+filter_params+(cat_grid.getSelectedRowId()!=null?"&idlist="+cat_grid.getSelectedRowId():"")+(display_all_products==1?"&forceAllProducts=1":"")+"&accessory_filter="+accessoriesFilter+"&accessoriesFilterByProducts="+accessoriesFilterByProducts+"&id_category="+catselection+"&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),function(){
                nb=prop_tb._accessoriesGrid.getRowsNum();
                prop_tb._sb.setText(nb+(nb>1?" <?php echo _l('accessories'); ?>":" <?php echo _l('accessory'); ?>"));
                prop_tb._accessoriesGrid._rowsNum=nb;
                
                // UISettings
                loadGridUISettings(prop_tb._accessoriesGrid);
                
                // UISettings
                prop_tb._accessoriesGrid._first_loading=0;

                // Dynamic filters
                var nb_cols = prop_tb._accessoriesGrid.getColumnsNum();
                if(nb_cols>0)
                {
                    for(var i=0; i<nb_cols; i++)
                    {
                        var colId=prop_tb._accessoriesGrid.getColumnId(i);
                        if((colId =="id" || colId =="reference" || colId =="name"))
                        {
                            var index = prop_tb._accessoriesGrid.getColIndexById(colId);
                            $(prop_tb._accessoriesGrid.getFilterElement(index)).keyup(function(){
                                prop_tb._accessoriesGrid.clearAll();
                                clearTimeout(dynamicFilter_accessories_timeout);
                                dynamicFilter_accessories_timeout = setTimeout(function(){ doDynamicFilter_accessories() }, 500);
                            });
                        }
                    }
                }

                if(filter_params!="")
                {
                    var exps = filter_params.split(",");
                    $(exps).each(function(index,filterbis_field){
                        var tmp = filterbis_field.split("|||");
                        var colname=prop_tb._accessoriesGrid.getColumnId(tmp[0]);
                        var index = prop_tb._accessoriesGrid.getColIndexById(tmp[0]);
                        prop_tb._accessoriesGrid.getFilterElement(index).value=tmp[1];


                    });
                }

                prop_tb._accessoriesGrid.enableHeaderMenu('false,false,false,false,false,false,false,false,false,false,false');
                prop_tb._accessoriesGrid.enableColumnMove(false);


                if (callback!='') eval(callback);
            });
    }
}


    let accessories_current_id = 0;
    cat_grid.attachEvent("onRowSelect",function (idproduct){
        if (propertiesPanel=='accessories' && (cat_grid.getSelectedRowId()!==null && accessories_current_id!=idproduct)){

            displayAccessories('',0);
            accessories_current_id=idproduct;
        }
    });
        
        
<?php if (SCMS) { ?>
    function accessory_goTo()
    {
        if(lastSelectedAccessory!="" && lastSelectedAccessory != 0)
        {
            var shop_id = 0;
            var category_id = 0;
            var product_id = 0;
            var product_attribute_id = 0;

            category_id = prop_tb._accessoriesGrid.getUserData(lastSelectedAccessory, "id_category_default");
            if(category_id==undefined || category_id=="" || category_id==null)
                var category_id = 0;
            
            shop_id = prop_tb._accessoriesGrid.getUserData(lastSelectedAccessory, "id_shop_default");
            if(shop_id==undefined || shop_id=="" || shop_id==null)
                var shop_id = 0;
            
            var product_id = lastSelectedAccessory;

            if(shop_id!=0 && category_id!=0 && product_id!=0)
            {
                lastProductSelID=product_id;
                var action_after = "cat_tree.openItem("+category_id+");cat_tree.selectItem("+category_id+",false);catselection="+category_id+";"+
                "displayProducts('setTimeout(function(){cat_grid.selectRowById("+product_id+");lastProductSelID="+product_id+";},1000);');";

                cat_shoptree.selectItem("all",false);
                cat_shoptree.openItem(shop_id);
                cat_shoptree.selectItem(shop_id,false);
                onClickShopTree(shop_id, null,action_after);
            }
        }
    }
<?php }
 else
 { ?>
    function accessory_goTo()
    {
        if(lastSelectedAccessory!="" && lastSelectedAccessory != 0)
        {
            var category_id = 0;
            var product_id = 0;
            var product_attribute_id = 0;

            category_id = prop_tb._accessoriesGrid.getUserData(lastSelectedAccessory, "id_category_default");
            if(category_id==undefined || category_id=="" || category_id==null)
                var category_id = 0;
            
            var product_id = lastSelectedAccessory;

            if(category_id!=0 && product_id!=0)
            {
                lastProductSelID=product_id;
                cat_tree.openItem(category_id);
                cat_tree.selectItem(category_id,false);
                catselection=category_id;
                displayProducts('setTimeout(function(){cat_grid.selectRowById('+product_id+');lastProductSelID='+product_id+';},1000);');
            }
        }
    }
<?php } ?>

function addAccessoryInQueue(rId, action, cIn, vars)
{
    var params = {
        name: "cat_accessory_update_queue",
        row: rId,
        action: "update",
        params: {},
        callback: "callbackAccessory('"+rId+"','update','"+rId+"','{sub_action}');"
    };
    // COLUMN VALUES
        params.params["id_lang"] = SC_ID_LANG;
        if(vars!=undefined && vars!=null && vars!="" && vars!=0)
        {
            $.each(vars, function(key, value){
                params.params[key] = value;
            });
        }
        if(params.params["sub_action"]!=undefined && params.params["sub_action"]!=null && params.params["sub_action"]!="")
            params.callback = params.callback.replace("'{sub_action}'","'"+params.params["sub_action"]+"'");
        else
            params.callback = params.callback.replace("'{sub_action}'","''");            
    // USER DATA
        if(rId!=undefined && rId!=null && rId!="" && rId!=0)
        {
            if(prop_tb._accessoriesGrid.UserData[rId]!=undefined && prop_tb._accessoriesGrid.UserData[rId]!=null && prop_tb._accessoriesGrid.UserData[rId]!="")
            {
                $.each(prop_tb._accessoriesGrid.UserData[rId].keys, function(i, key){
                    params.params[key] = prop_tb._accessoriesGrid.UserData[rId].values[i];
                });
            }
        }
        if(prop_tb._accessoriesGrid.UserData.gridglobaluserdata.keys!=undefined && prop_tb._accessoriesGrid.UserData.gridglobaluserdata.keys!=null && prop_tb._accessoriesGrid.UserData.gridglobaluserdata.keys!="")
        {
            $.each(prop_tb._accessoriesGrid.UserData.gridglobaluserdata.keys, function(i, key){
                params.params[key] = prop_tb._accessoriesGrid.UserData.gridglobaluserdata.values[i];
            });
        }
    
    params.params = JSON.stringify(params.params);
    addInUpdateQueue(params,prop_tb._accessoriesGrid);
}
        
    // CALLBACK FUNCTION
    function callbackAccessory(sid,action,tid,sub_action)
    {
        if (action=='update')
            prop_tb._accessoriesGrid.setRowTextNormal(sid);
        else if(action=='delete')
            prop_tb._accessoriesGrid.deleteRow(sid);
        
        if(sub_action!=undefined && sub_action!=null && sub_action!="" && sub_action!=0)
        {
            if(sub_action=="addSel" || sub_action=="delSel")
                displayAccessories('',0);
        }
    }

var dynamicFilter_accessories_timeout = null;
function doDynamicFilter_accessories()
{
    var nb_cols = prop_tb._accessoriesGrid.getColumnsNum();
    old_filter_params = filter_params;
    filter_params = "";
    if(nb_cols>0)
    {
        for(var i=0; i<nb_cols; i++)
        {
            var colId=prop_tb._accessoriesGrid.getColumnId(i);
            if((colId =="id" || colId =="reference" || colId =="name"))
            {
                var colValue = prop_tb._accessoriesGrid.getFilterElement(i).value;
                if((colValue!=null && colValue!=""))
                {
                    if(filter_params!="")
                        filter_params = filter_params + ",";
                    filter_params = filter_params + colId+"|||"+colValue;
                }
            }
        }
    }
    if(filter_params!=old_filter_params)
    {
        prop_tb._accessoriesGrid._rowsNum = 0;
        displayAccessories();
    }
}