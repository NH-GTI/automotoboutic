<?php
if (!defined('STORE_COMMANDER')) { exit; }

if (SCAS) { ?>
    
    <?php if (_r('ACT_CAT_ADVANCED_STOCK_MANAGEMENT')) { ?>
        prop_tb.addListOption('panel', 'warehouseshare', 14, "button", '<?php echo _l('Warehouses', 1); ?>', "fa fa-building");
        allowed_properties_panel[allowed_properties_panel.length] = "warehouseshare";
    <?php } ?>

    prop_tb.addButton("warehouseshare_refresh",1000, "", "fa fa-sync green", "fa fa-sync green");
    prop_tb.setItemToolTip('warehouseshare_refresh','<?php echo _l('Refresh grid', 1); ?>');
    prop_tb.addButton("warehouseshare_add_select",1000, "", "fad fa-link yellow", "fad fa-link yellow");
    prop_tb.setItemToolTip('warehouseshare_add_select','<?php echo _l('Add all selected products to all selected warehouses', 1); ?>');
    prop_tb.addButton("warehouseshare_del_select",1000, "", "fad fa-unlink red", "fad fa-unlink red");
    prop_tb.setItemToolTip('warehouseshare_del_select','<?php echo _l('Delete all selected products from all selected warehouses', 1); ?>');
    
    var with_combi = 0;
    
    var opts = [
                    ['warehouseshare_settings_0', 'obj', '<?php echo _l('Associate only products in Advanced stocks', 1); ?>', ''],
                    ['warehouseshare_settings_1', 'obj', '<?php echo _l('Activate Advanced stocks and Associate', 1); ?>', ''],
                    ['warehouseshare_settings_2', 'obj', '<?php echo _l('Activate Advanced stocks + manual mgmt and Associate', 1); ?>', '']
                ];
    <?php
    $option_list_selected = _s('CAT_ADVANCEDSTOCKS_WAREHOUSESHARE_DEFAULT_TYPE');

    $option_text = _l('Action: Associate only AS', 1);
    if (empty($option_list_selected))
    {
        $option_text = _l('Action: Associate only AS', 1);
    }
    elseif ($option_list_selected == 1)
    {
        $option_text = _l('Action: Activate AS & Associate', 1);
    }
    elseif ($option_list_selected == 2)
    {
        $option_text = _l('Action: Activate AS + MM & Associate', 1);
    }

    $option_list_selected = 'warehouseshare_settings_'.$option_list_selected;
    ?>
    prop_tb.addButtonSelect("warehouseshare_settings", 1000, '<?php echo $option_text; ?>', opts, "fa fa-cog", "fa fa-cog",false,true);
    prop_tb.setItemToolTip('warehouseshare_settings','<?php echo _l('Advanced stocks settings', 1); ?>');
    prop_tb.setListOptionSelected("warehouseshare_settings", "<?php echo $option_list_selected; ?>");
    
    prop_tb.addButton("help_warehouseshare_settings",1000, "", "fad fa-info-circle blue", "fad fa-info-circle blue");
    prop_tb.setItemToolTip('help_warehouseshare_settings','<?php echo _l('Show help for Advanced stocks default activation', 1); ?>');
    
    needInitWarehouseshare = 1;
    function initWarehouseshare()
    {
        if (needInitWarehouseshare)
        {
            prop_tb._warehouseshareLayout = dhxLayout.cells('b').attachLayout('1C');
            prop_tb._warehouseshareLayout.cells('a').hideHeader();
            dhxLayout.cells('b').showHeader();

            prop_tb._warehouseshareGrid = prop_tb._warehouseshareLayout.cells('a').attachGrid();
            prop_tb._warehouseshareGrid._name='_warehouseshareGrid';
            prop_tb._warehouseshareGrid.setImagePath("lib/js/imgs/");
              prop_tb._warehouseshareGrid.enableDragAndDrop(false);
            prop_tb._warehouseshareGrid.enableMultiselect(true);
            
            // UISettings
            prop_tb._warehouseshareGrid._uisettings_prefix='cat_warehouseshare';
            prop_tb._warehouseshareGrid._uisettings_name=prop_tb._warehouseshareGrid._uisettings_prefix;
               prop_tb._warehouseshareGrid._first_loading=1;
               
            // UISettings
            initGridUISettings(prop_tb._warehouseshareGrid);

            $.post("index.php?ajax=1&act=cat_warehouseshare_settings_get&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),function(data){
                prop_tb.setListOptionSelected("warehouseshare_settings", "warehouseshare_settings_"+data);
                
                if(data==0)
                    prop_tb.setItemText("warehouseshare_settings", '<?php echo _l('Action: Associate only AS', 1); ?>');
                else if(data==1)
                    prop_tb.setItemText("warehouseshare_settings", '<?php echo _l('Action: Activate AS & Associate', 1); ?>');
                else if(data==2)
                    prop_tb.setItemText("warehouseshare_settings", '<?php echo _l('Action: Activate AS + MM & Associate', 1); ?>');
            });
            
            prop_tb._warehouseshareGrid.attachEvent("onEditCell",function(stage,rId,cInd,nValue,oValue)
            {
                if(stage==1)
                {
                    idxPresent=prop_tb._warehouseshareGrid.getColIndexById('present');
                
                    var action = "";
                    if(cInd==idxPresent)
                        action = "present";
                    
                    if(action=="present")
                    {
                        var value = prop_tb._warehouseshareGrid.cells(rId,cInd).isChecked();
                        $.post("index.php?ajax=1&act=cat_warehouseshare_update&id_warehouse="+rId+"&action="+action+"&value="+value+"&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),{"idlist":cat_grid.getSelectedRowId()},function(data){
                            displayWarehouseshare();
                            writeRehresh();
                        });
                    }
                    
                }
                else if(stage==2)
                {
                    idxLocation=prop_tb._warehouseshareGrid.getColIndexById('location');
                    if(idxLocation!=undefined && idxLocation!=null )
                    {
                        idxPresent=prop_tb._warehouseshareGrid.getColIndexById('present');
                        var action = "";
                        if(cInd==idxLocation)
                            action = "location";
                        
                        if(action=="location")
                        {
                            var value = prop_tb._warehouseshareGrid.cells(rId,cInd).getValue();
                            $.post("index.php?ajax=1&act=cat_warehouseshare_update&id_warehouse="+rId+"&action="+action+"&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),{"value":value,"idlist":cat_grid.getSelectedRowId()},function(data){
                                if(!prop_tb._warehouseshareGrid.cells(rId,idxPresent).isChecked())
                                    displayWarehouseshare();
                            });
                        }
                    }
                }
                return true;
            });
            
            
            prop_tb._warehouseshareGrid.attachEvent("onRowDblClicked", function(rId,cInd){
                idxASM=cat_grid.getColIndexById('advanced_stock_management');
                
                idxQty=prop_tb._warehouseshareGrid.getColIndexById('quantity');
                idxPresent=prop_tb._warehouseshareGrid.getColIndexById('present');
        
                if(cInd==idxQty && cat_grid.getSelectedRowId().split(",").length==1)
                {
                    if(cat_grid.getUserData(cat_grid.getSelectedRowId(),"type_advanced_stock_management")=="2" && prop_tb._warehouseshareGrid.cells(rId,idxPresent).isChecked())
                    {
                        if (!dhxWins.isWindow("wStockMvt"))
                        {
                            wStockMvt = dhxWins.createWindow("wStockMvt", ($(window).width()/2-200), 200, 430, 580);
                            wStockMvt.setText("<?php echo _l('Create a new stock movement'); ?>");
                            wStockMvt.show();
                            $.post("index.php?ajax=1&act=cat_win-stockmvt_choose_init&id_warehouse="+rId+"&id_lang="+SC_ID_LANG,{"id_product":cat_grid.getSelectedRowId()},function(data){
                                    $('#jsExecute').html(data);
                                });
                        }else{
                            wStockMvt.setDimension(430, 580);
                            wStockMvt.show();
                            $.get("index.php?ajax=1&act=cat_win-stockmvt_choose_init&id_warehouse="+rId+"&id_lang="+SC_ID_LANG,{"id_product":cat_grid.getSelectedRowId()},function(data){
                                    $('#jsExecute').html(data);
                                });
                        }
                        
                        return false;
                    }
                }
                return true;
            });
            
            needInitWarehouseshare=0;
        }
    }

    function setPropertiesPanel_warehouseshare(id){
        if (id=='warehouseshare')
        {
            if(lastProductSelID!=undefined && lastProductSelID!="")
            {
                dhxLayout.cells('b').setText('<?php echo _l('Properties', 1).' '._l('of', 1); ?> '+getSelectedItemValueOrID(cat_grid,lastProductSelID,'name'));
            }
            hidePropTBButtons();
            prop_tb.showItem('warehouseshare_refresh');
            prop_tb.showItem('warehouseshare_add_select');
            prop_tb.showItem('warehouseshare_del_select');
            prop_tb.showItem('help_warehouseshare_settings');
            prop_tb.showItem('warehouseshare_settings');
            prop_tb.setItemText('panel', '<?php echo _l('Warehouses', 1); ?>');
            prop_tb.setItemImage('panel', 'fa fa-building');
            needInitWarehouseshare = 1;
            initWarehouseshare();
            propertiesPanel='warehouseshare';
            if (lastProductSelID!=0)
            {
                displayWarehouseshare();
            }
        }
        if (id=='warehouseshare_add_select')
        {
            if(prop_tb._warehouseshareGrid.getSelectedRowId()!="" && prop_tb._warehouseshareGrid.getSelectedRowId()!=null)
            {
                $.post("index.php?ajax=1&act=cat_warehouseshare_update&action=mass_present&value=true&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),{"idlist":cat_grid.getSelectedRowId(), "id_warehouse":prop_tb._warehouseshareGrid.getSelectedRowId()},function(data){
                    displayWarehouseshare();
                    writeRehresh();
                });
            }
        }
        if (id=='warehouseshare_del_select')
        {
            if(prop_tb._warehouseshareGrid.getSelectedRowId()!="" && prop_tb._warehouseshareGrid.getSelectedRowId()!=null)
            {
                $.post("index.php?ajax=1&act=cat_warehouseshare_update&action=mass_present&value=false&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),{"idlist":cat_grid.getSelectedRowId(), "id_warehouse":prop_tb._warehouseshareGrid.getSelectedRowId()},function(data){
                    displayWarehouseshare();
                    writeRehresh();
                });
            }
        }
        if (id=='warehouseshare_refresh')
        {
            displayWarehouseshare();
        }
        if (id=='warehouseshare_settings_0')
        {
            prop_tb.setItemText("warehouseshare_settings", '<?php echo _l('Action: Associate only AS', 1); ?>');
            $.post("index.php?ajax=1&act=cat_warehouseshare_settings_update&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),{"setting_value":"0"},function(data){displayWarehouseshare();});
        }
        if (id=='warehouseshare_settings_1')
        {
            prop_tb.setItemText("warehouseshare_settings", '<?php echo _l('Action: Activate AS & Associate', 1); ?>');
            $.post("index.php?ajax=1&act=cat_warehouseshare_settings_update&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),{"setting_value":"1"},function(data){displayWarehouseshare();});
        }
        if (id=='warehouseshare_settings_2')
        {
            prop_tb.setItemText("warehouseshare_settings", '<?php echo _l('Action: Activate AS + MM & Associate', 1); ?>');
            $.post("index.php?ajax=1&act=cat_warehouseshare_settings_update&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),{"setting_value":"2"},function(data){displayWarehouseshare();});
        }
        if (id=='help_warehouseshare_settings')
        {
            displayHelpWindow('grid','cat_win-help_warehouse_settings_xml',650,260,"<?php echo _l('Help for Advanced stocks default activation'); ?>");
        }
        
    }
    prop_tb.attachEvent("onClick", setPropertiesPanel_warehouseshare);
    
    function displayWarehouseshare()
    {
        prop_tb._warehouseshareGrid.clearAll(true);
        var tempIdList = (cat_grid.getSelectedRowId()!=null?cat_grid.getSelectedRowId():"");
        $.post("index.php?ajax=1&act=cat_warehouseshare_get&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),{'idlist': tempIdList,'with_combi':with_combi},function(data)
        {
            prop_tb._warehouseshareGrid.parse(data);
            nb=prop_tb._warehouseshareGrid.getRowsNum();
            prop_tb._sb.setText(nb+(nb>1?" <?php echo _l('warehouses'); ?>":" <?php echo _l('warehouse'); ?>"));
            prop_tb._warehouseshareGrid._rowsNum=nb;
            
           // UISettings
            loadGridUISettings(prop_tb._warehouseshareGrid);
            prop_tb._warehouseshareGrid._first_loading=0;
            
            idxPresent=prop_tb._warehouseshareGrid.getColIndexById('present');
            
            var has_combi = prop_tb._warehouseshareGrid.getUserData("","has_combi");
            if(has_combi=="1")
                dhtmlx.message({text:'<?php echo _l('Some of the selected products possess combinations', 1); ?>',type:'error',expire:10000});
            
            var not_activated = prop_tb._warehouseshareGrid.getUserData("","not_activated");
            if(not_activated=="1")
                dhtmlx.message({text:'<?php echo _l('Some of the selected products do not have the Advanced Stock Management option activated.', 1); ?><br/><?php echo _l('Read help more explanations.', 1); ?>',type:'error',expire:10000});
        });
    }


    let warehouseshare_current_id = 0;
    cat_grid.attachEvent("onRowSelect",function (idproduct){
        if (propertiesPanel=='warehouseshare' && (cat_grid.getSelectedRowId()!==null && warehouseshare_current_id!=idproduct)){
            displayWarehouseshare();
            warehouseshare_current_id=idproduct;
        }
    });
    
    function writeRehresh()
    {
        idxASM=cat_grid.getColIndexById('advanced_stock_management');
        idxQty=cat_grid.getColIndexById('quantity');
        idxQtyUpdate=cat_grid.getColIndexById('quantityupdate');
        idxQtyUse=cat_grid.getColIndexById('quantity_usable');
        idxQtyPhy=cat_grid.getColIndexById('quantity_physical');
        idxQtyRea=cat_grid.getColIndexById('quantity_real');
        
        var ids = cat_grid.getSelectedRowId().split(',');
        $.each(ids, function(num, rId) {
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
        });
    }
    
<?php } ?>