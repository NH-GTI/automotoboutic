<?php
if (!defined('STORE_COMMANDER')) { exit; }

if (SCMS) { ?>
// INITIALISATION TOOLBAR
cus_group_prop_tb.addListOption('cus_group_prop_subproperties', 'cus_group_shopshare', 1, "button", '<?php echo _l('Multistore sharing manager', 1); ?>', "fa fa-sitemap white");

cus_group_prop_tb.attachEvent("onClick", function(id){
    if(id=="cus_group_shopshare")
    {
        hideGroupManagementSubpropertiesItems();
        cus_group_prop_tb.setItemText('cus_group_prop_subproperties', '<?php echo _l('Multistore sharing manager', 1); ?>');
        cus_group_prop_tb.setItemImage('cus_group_prop_subproperties', 'fa fa-sitemap white');
        actual_groupmanagement_subproperties = "cus_group_shopshare";
        initGroupManagementPropShopshare();
    }
});
                
wGroupManagement.gridGroups.attachEvent("onRowSelect", function(id,ind){
    if(actual_groupmanagement_subproperties == "cus_group_shopshare"){
        getGroupManagementPropShopshare();
    }
});
        
cus_group_prop_tb.addButton('cus_group_shopshare_refresh',100,'','fa fa-sync green','fa fa-sync green');
cus_group_prop_tb.setItemToolTip('cus_group_shopshare_refresh','<?php echo _l('Refresh grid', 1); ?>');
cus_group_prop_tb.addButton("cus_group_shopshare_add_select", 100, "", "fad fa-link yellow", "fad fa-link yellow");
cus_group_prop_tb.setItemToolTip('cus_group_shopshare_add_select','<?php echo _l('Associate all selected groups to all selected shops', 1); ?>');
cus_group_prop_tb.addButton("cus_group_shopshare_del_select", 100, "", "fad fa-unlink red", "fad fa-unlink red");
cus_group_prop_tb.setItemToolTip('cus_group_shopshare_del_select','<?php echo _l('Dissociate all selected groups from all selected shops', 1); ?>');
cus_group_prop_tb.addButton("select_all_shops", 100, "", "fa fa-bolt yellow", "fa fa-bolt yellow");
cus_group_prop_tb.setItemToolTip('select_all_shops','<?php echo _l('Select all shops'); ?>');
hideGroupManagementSubpropertiesItems();

cus_group_prop_tb.attachEvent("onClick", function(id){
    if (id=='cus_group_shopshare_refresh')
    {
        getGroupManagementPropShopshare();
    }
    if (id=='select_all_shops')
    {
        cus_group_shopshare_grid.selectAll();
    }
    if (id=='cus_group_shopshare_add_select')
    {
        $.post("index.php?ajax=1&act=cus_win-groupmanagement_shopshare_update&action=mass_present&value=true&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),{"idlist":wGroupManagement.gridGroups.getSelectedRowId(),"id_shop":cus_group_shopshare_grid.getSelectedRowId()},function(data){
            getGroupManagementPropShopshare();
        });
    }
    if (id=='cus_group_shopshare_del_select')
    {
        $.post("index.php?ajax=1&act=cus_win-groupmanagement_shopshare_update&action=mass_present&value=false&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),{"idlist":wGroupManagement.gridGroups.getSelectedRowId(),"id_shop":cus_group_shopshare_grid.getSelectedRowId()},function(data){
             getGroupManagementPropShopshare();
        });
    }
});

// FUNCTIONS
var cus_group_shopshare = null;
var clipboardType_CatPropShopshare = null;
function initGroupManagementPropShopshare()
{
    cus_group_prop_tb.showItem('cus_group_shopshare_refresh');
    cus_group_prop_tb.showItem('cus_group_shopshare_add_select');
    cus_group_prop_tb.showItem('cus_group_shopshare_del_select');
    cus_group_prop_tb.showItem('select_all_shops');

    cus_group_shopshare = groups_properties_panel.attachLayout("1C");
    groups_properties_panel.showHeader();
    
    // GRID
        cus_group_shopshare.cells('a').hideHeader();
        
        cus_group_shopshare_grid = cus_group_shopshare.cells('a').attachGrid();
        cus_group_shopshare_grid.setImagePath("lib/js/imgs/");
          cus_group_shopshare_grid.enableDragAndDrop(false);
        cus_group_shopshare_grid.enableMultiselect(true);
    
        // UISettings
        cus_group_shopshare_grid._uisettings_prefix='cus_group_shopshare_grid';
        cus_group_shopshare_grid._uisettings_name=cus_group_shopshare_grid._uisettings_prefix;
        cus_group_shopshare_grid._first_loading=1;
                   
        // UISettings
        initGridUISettings(cus_group_shopshare_grid);
        
        getGroupManagementPropShopshare();
        
        cus_group_shopshare_grid.attachEvent("onEditCell",function(stage,rId,cInd,nValue,oValue)
        {
            if(stage==1)
            {
                idxPresent=cus_group_shopshare_grid.getColIndexById('present');
            
                var action = "";
                if(cInd==idxPresent)
                    action = "present";
                
                if(action!="")
                {
                    var value = cus_group_shopshare_grid.cells(rId,cInd).isChecked();
                    $.post("index.php?ajax=1&act=cus_win-groupmanagement_shopshare_update&id_shop="+rId+"&action="+action+"&value="+value+"&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),{"idlist":wGroupManagement.gridGroups.getSelectedRowId()},function(data){
                        getGroupManagementPropShopshare();
                    });
                }
            }
            return true;
        });
}

function getGroupManagementPropShopshare()
{
    cus_group_shopshare_grid.clearAll(true);
        var tempIdList = (wGroupManagement.gridGroups.getSelectedRowId()!=null?wGroupManagement.gridGroups.getSelectedRowId():"");
        $.post("index.php?ajax=1&act=cus_win-groupmanagement_shopshare_get&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),{'idlist': tempIdList},function(data)
        {
            cus_group_shopshare_grid.parse(data);
                
            // UISettings
                loadGridUISettings(cus_group_shopshare_grid);
                cus_group_shopshare_grid._first_loading=0;
                
                idxPresent=cus_group_shopshare_grid.getColIndexById('present');
                
                cus_group_shopshare_grid.forEachRow(function(id){
                    cus_group_shopshare_grid.cells(id,idxPresent).setDisabled(false);
               });
        });
}
<?php } ?>
