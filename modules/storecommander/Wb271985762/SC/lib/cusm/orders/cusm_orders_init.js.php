<?php
if (!defined('STORE_COMMANDER')) { exit; }

    if (_r('GRI_CUS_PROPERTIES_GRID_ORDERS'))
    {
        ?>
    prop_tb.addListOption('panel', 'customerorder', 1, "button", '<?php echo _l('Orders and products', 1); ?>', "fa fa-shopping-cart");
    allowed_properties_panel[allowed_properties_panel.length] = "customerorder";

    prop_tb.addButton("customerorder_refresh",1000, "", "fa fa-sync green", "fa fa-sync green");
    prop_tb.setItemToolTip('customerorder_refresh','<?php echo _l('Refresh grid', 1); ?>');
    prop_tb.addButton('customerorder_exportcsv',1000, '', 'fad fa-file-csv green', 'fad fa-file-csv green');
    prop_tb.setItemToolTip('customerorder_exportcsv','<?php echo _l('Export grid to clipboard in CSV format for MSExcel with tab delimiter.', 1); ?>');

    needinitCustomerOrder = 1;
    function initCustomerOrder(){
        if (needinitCustomerOrder)
        {
            prop_tb._customerOrderLayout = dhxLayout.cells('b').attachLayout('2E');
            prop_tb._customerOrderLayout.cells('a').hideHeader();
            dhxLayout.cells('b').showHeader();
            prop_tb._customerOrderGrid = prop_tb._customerOrderLayout.cells('a').attachGrid();
            prop_tb._customerOrderGrid.enableMultiselect(true);
            
            // UISettings
            prop_tb._customerOrderGrid._uisettings_prefix='cus_orders';
            prop_tb._customerOrderGrid._uisettings_name=prop_tb._customerOrderGrid._uisettings_prefix;
               prop_tb._customerOrderGrid._first_loading=1;
               
            // UISettings
            initGridUISettings(prop_tb._customerOrderGrid);
            
            prop_tb._customerOrderGrid.attachEvent("onRowSelect",function (idorder){
                if (propertiesPanel=='customerorder' && !dhxLayout.cells('b').isCollapsed()){
                    displayCustomerOrderProducts();
                }
            });
            
            prop_tb._customerOrderLayout.cells('b').setText('<?php echo _l('Products', 1); ?>');
            prop_tb._customerProductGrid = prop_tb._customerOrderLayout.cells('b').attachGrid();
            
            // UISettings
            prop_tb._customerProductGrid._uisettings_prefix='cus_orders_products';
            prop_tb._customerProductGrid._uisettings_name=prop_tb._customerProductGrid._uisettings_prefix;
               prop_tb._customerProductGrid._first_loading=1;
               
            // UISettings
            initGridUISettings(prop_tb._customerProductGrid);
            
            needinitCustomerOrder=0;
        }
    }


    function setPropertiesPanel_customerorder(id){
        if (id=='customerorder')
        {
            if(lastCustomerSelID!=undefined && lastCustomerSelID!="")
            {
                idxCustomerName=cusm_grid.getColIndexById('customer_name');
            }
            hidePropTBButtons();
            prop_tb.showItem('customerorder_refresh');
            prop_tb.showItem('customerorder_exportcsv');
            prop_tb.setItemText('panel', '<?php echo _l('Orders and products', 1); ?>');
            prop_tb.setItemImage('panel', 'fa fa-shopping-cart');
            needinitCustomerOrder = 1;
            initCustomerOrder();
            propertiesPanel='customerorder';
            if (lastCustomerSelID!=0)
            {
                displayCustomerOrders();
            }
        }
        if (id=='customerorder_refresh')
        {
            displayCustomerOrders();
            prop_tb._customerProductGrid.clearAll(true);
        }
        if (id=='customerorder_exportcsv'){
            displayQuickExportWindow(prop_tb._customerOrderGrid,1);
        }
    }
    prop_tb.attachEvent("onClick", setPropertiesPanel_customerorder);


    function displayCustomerOrders()
    {
        var customers_id = "";
        $.each( cusm_grid.getSelectedRowId().split(','), function( num, rowid ) {
            if(customers_id!="")
                customers_id = customers_id+",";

            customers_id = customers_id+cusm_grid.getUserData(rowid,'id_customer');
        });
        prop_tb._customerOrderGrid.clearAll(true);
        $.post("index.php?ajax=1&act=cus_orders_get&id_lang="+SC_ID_LANG, {id_customer_list:customers_id}, function(data)
        {
            prop_tb._customerOrderGrid.parse(data);
            nb=prop_tb._customerOrderGrid.getRowsNum();
            prop_tb._sb.setText('');
                
                    // UISettings
                    loadGridUISettings(prop_tb._customerOrderGrid);
                    
                    // UISettings
                    prop_tb._customerOrderGrid._first_loading=0;
        });
    }

    function displayCustomerOrderProducts()
    {
        prop_tb._customerProductGrid.clearAll(true);
        $.post("index.php?ajax=1&act=cus_orders_products_get&id_lang="+SC_ID_LANG, {id_order_list:prop_tb._customerOrderGrid.getSelectedRowId()},function(data)
        {
            prop_tb._customerProductGrid.parse(data);
            nb=prop_tb._customerProductGrid.getRowsNum();
            prop_tb._sb.setText('');
                
                    // UISettings
                    loadGridUISettings(prop_tb._customerProductGrid);
                    
                    // UISettings
                    prop_tb._customerProductGrid._first_loading=0;
        });
    }


    let customerorder_current_id = 0;
    cusm_grid.attachEvent("onRowSelect",function (idcustomer){
        if (propertiesPanel=='customerorder' && !dhxLayout.cells('b').isCollapsed() && (cusm_grid.getSelectedRowId()!==null && customerorder_current_id!=idcustomer)){
            prop_tb._customerProductGrid.clearAll(true);
            displayCustomerOrders();
            customerorder_current_id=idcustomer;
        }
    });

<?php
    } // end permission
?>
