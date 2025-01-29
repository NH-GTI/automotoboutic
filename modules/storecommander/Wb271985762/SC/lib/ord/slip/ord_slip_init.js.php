<?php
if (!defined('STORE_COMMANDER')) { exit; }
if (_r('GRI_ORD_PROPERTIES_GRID_SLIP')) { ?>
    prop_tb.addListOption('panel', 'orderslip', 0, "button", '<?php echo _l('Slips', 1); ?>', "fad fa-file-invoice-dollar");
    allowed_properties_panel[allowed_properties_panel.length] = "orderslip";

    prop_tb.addButton("orderslip_refresh",1000, "", "fa fa-sync green", "fa fa-sync green");
    prop_tb.setItemToolTip('orderslip_refresh','<?php echo _l('Refresh grid', 1); ?>');

    prop_tb.addButton("orderslip_exportcsv",1000, "", "fad fa-file-csv green", "fad fa-file-csv green");
    prop_tb.setItemToolTip('orderslip_exportcsv','<?php echo _l('Export grid to clipboard in CSV format for MSExcel with tab delimiter.'); ?>');

    lastOrderSlipSelID = 0;

    needinitOrderSlip = 1;
    function initOrderSlip(){
        if (needinitOrderSlip)
        {
            prop_tb._orderSlipLayout = dhxLayout.cells('b').attachLayout('2E');
            prop_tb._orderSlipLayout.cells('a').hideHeader();
            dhxLayout.cells('b').showHeader();
            prop_tb._orderSlipGrid = prop_tb._orderSlipLayout.cells('a').attachGrid();
            prop_tb._orderSlipGrid.enableMultiselect(true);
            
            // UISettings
            prop_tb._orderSlipGrid._uisettings_prefix='ord_slip';
            prop_tb._orderSlipGrid._uisettings_name=prop_tb._orderSlipGrid._uisettings_prefix;
          prop_tb._orderSlipGrid._first_loading=1;
               
            // UISettings
            initGridUISettings(prop_tb._orderSlipGrid);

            function onEditCellOrderSlip(stage,rId,cInd,nValue,oValue){
                    if(stage==2)
                    {
                        $.post('index.php?ajax=1&act=ord_slip_update&action=update',
                        {
                            id_lang: SC_ID_LANG,
                            id_order_slip: Number(rId),
                            col: prop_tb._orderSlipGrid.getColumnId(cInd),
                            val: nValue.replace(/#/g, '')
                        }, function () {
                        });
                    }
                    return true;
            }
            prop_tb._orderSlipGrid.attachEvent("onEditCell",onEditCellOrderSlip);


            prop_tb._orderSlipGrid.attachEvent("onRowSelect",function (idorder){
                if (propertiesPanel=='orderslip' && !dhxLayout.cells('b').isCollapsed()){
                    if (lastOrderSlipSelID != prop_tb._orderSlipGrid.getSelectedRowId()){
                        lastOrderSlipSelID = prop_tb._orderSlipGrid.getSelectedRowId();
                        displayOrderSlipDetail();
                    }
                }
            });
            
            prop_tb._orderSlipLayout.cells('b').setText('<?php echo _l('Products', 1); ?>');
            prop_tb._slipDetailGrid = prop_tb._orderSlipLayout.cells('b').attachGrid();
            
            // UISettings
            prop_tb._slipDetailGrid._uisettings_prefix='ord_slip_detail';
            prop_tb._slipDetailGrid._uisettings_name=prop_tb._slipDetailGrid._uisettings_prefix;
               prop_tb._slipDetailGrid._first_loading=1;
               
            // UISettings
            initGridUISettings(prop_tb._slipDetailGrid);


            function onEditCellOrderSlipDetail(stage,rId,cInd,nValue,oValue){
                    if(stage==2)
                    {
                        $.post('index.php?ajax=1&act=ord_slip_detail_update&action=update',
                        {
                            id_lang: SC_ID_LANG,
                            id_order_slip__id_order_detail: Number(rId),
                            col: prop_tb._slipDetailGrid.getColumnId(cInd),
                            val: nValue.replace(/#/g,'')
                        },function(){});
                    }
                    return true;
            }
            prop_tb._slipDetailGrid.attachEvent("onEditCell",onEditCellOrderSlipDetail);

            needinitOrderSlip=0;
        }
    }


    function setPropertiesPanel_orderSlip(id){
        if (id=='orderslip')
        {
            hidePropTBButtons();
            prop_tb.showItem('orderslip_refresh');
            prop_tb.showItem('orderslip_exportcsv');
            prop_tb.setItemText('panel', '<?php echo _l('Slips', 1); ?>');
            prop_tb.setItemImage('panel', 'fad fa-file-invoice-dollar');
            needinitOrderSlip = 1;
            initOrderSlip();
            propertiesPanel='orderslip';
            if (lastOrderSelID!=0)
            {
                displayOrderSlip();
            }
        }
        if (id=='orderslip_refresh')
        {
            lastOrderSlipSelID = 0;
            displayOrderSlip();
            prop_tb._slipDetailGrid.clearAll(true);
        }
        if (id=='orderslip_exportcsv'){
            displayQuickExportWindow(prop_tb._orderSlipGrid,1);
        }
    }
    prop_tb.attachEvent("onClick", setPropertiesPanel_orderSlip);


    function displayOrderSlip()
    {
        prop_tb._orderSlipGrid.clearAll(true);
        $.post('index.php?ajax=1&act=ord_slip_get',
        {
            id_lang: SC_ID_LANG,
            id_customer_list: castListId(getListIdFromUserData(ord_grid, 'id_customer'))
        },function(data)
        {
            prop_tb._orderSlipGrid.parse(data);
            nb=prop_tb._orderSlipGrid.getRowsNum();
            prop_tb._sb.setText('');
                
          // UISettings
            loadGridUISettings(prop_tb._orderSlipGrid);
                    
            // UISettings
            prop_tb._orderSlipGrid._first_loading=0;
        });
    }

    function displayOrderSlipDetail()
    {
        prop_tb._slipDetailGrid.clearAll(true);
        $.post("index.php?ajax=1&act=ord_slip_detail_get&id_lang="+SC_ID_LANG,{'id_order_slip':prop_tb._orderSlipGrid.getSelectedRowId()},function(data)
        {
            prop_tb._slipDetailGrid.parse(data);
            nb=prop_tb._slipDetailGrid.getRowsNum();
            prop_tb._sb.setText('');
                
          // UISettings
            loadGridUISettings(prop_tb._slipDetailGrid);
                    
            // UISettings
            prop_tb._slipDetailGrid._first_loading=0;
        });
    }


    let orderslip_current_id = 0;
    ord_grid.attachEvent("onRowSelect",function (idproduct){
        if (propertiesPanel=='orderslip' && !dhxLayout.cells('b').isCollapsed() && (ord_grid.getSelectedRowId()!==null && orderslip_current_id!=idproduct)){
            displayOrderSlip();
            orderslip_current_id=idproduct;
        }
    });

<?php
    } // end permission
?>