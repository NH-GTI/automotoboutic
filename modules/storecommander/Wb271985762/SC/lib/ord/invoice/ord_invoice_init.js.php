<?php
if (!defined('STORE_COMMANDER')) { exit; }
if (_r('GRI_ORD_PROPERTIES_GRID_INVOICE')) { ?>
    prop_tb.addListOption('panel', 'orderinvoice', 0, "button", '<?php echo _l('Invoices', 1); ?>', "fad fa-file-invoice");
    allowed_properties_panel[allowed_properties_panel.length] = "orderinvoice";

    prop_tb.addButton("orderinvoice_refresh",1000, "", "fa fa-sync green", "fa fa-sync green");
    prop_tb.setItemToolTip('orderinvoice_refresh','<?php echo _l('Refresh grid', 1); ?>');


    lastOrderInvoiceSelID = 0;

    needinitOrderInvoice = 1;
    function initOrderInvoice(){
        if (needinitOrderInvoice)
        {
            prop_tb._orderInvoiceLayout = dhxLayout.cells('b').attachLayout('1C');
            prop_tb._orderInvoiceLayout.cells('a').hideHeader();
            dhxLayout.cells('b').showHeader();
            prop_tb._orderInvoiceGrid = prop_tb._orderInvoiceLayout.cells('a').attachGrid();
            prop_tb._orderInvoiceGrid.enableMultiselect(true);
            prop_tb._orderInvoiceGrid.setDateFormat("%Y-%m-%d %H:%i:%s","%Y-%m-%d %H:%i:%s");

            // UISettings
            prop_tb._orderInvoiceGrid._uisettings_prefix='ord_invoice';
            prop_tb._orderInvoiceGrid._uisettings_name=prop_tb._orderInvoiceGrid._uisettings_prefix;
          prop_tb._orderInvoiceGrid._first_loading=1;

            // UISettings
            initGridUISettings(prop_tb._orderInvoiceGrid);

            function onEditCellOrderInvoice(stage,rId,cInd,nValue,oValue){
                    if(stage==2)
                    {
                        $.post("index.php?ajax=1&act=ord_invoice_update&action=update&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),
                            {
                                id_order_invoice: Number(rId),
                                col: prop_tb._orderInvoiceGrid.getColumnId(cInd),
                                val: nValue.replace(/#/g,'')
                            },function(data){});
                    }
                    return true;
            }
            prop_tb._orderInvoiceGrid.attachEvent("onEditCell",onEditCellOrderInvoice);

            prop_tb._orderInvoiceGrid.attachEvent("onDhxCalendarCreated",function(calendar){
                calendar.loadUserLanguage("<?php echo $user_lang_iso; ?>");
            });


            prop_tb._orderInvoiceGrid.attachEvent("onRowSelect",function (idorder){
                if (propertiesPanel=='orderinvoice' && !dhxLayout.cells('b').isCollapsed()){
                    if (lastOrderInvoiceSelID != prop_tb._orderInvoiceGrid.getSelectedRowId()){
                        lastOrderInvoiceSelID = prop_tb._orderInvoiceGrid.getSelectedRowId();
                    }
                }
            });


            needinitOrderInvoice=0;
        }
    }


    function setPropertiesPanel_orderInvoice(id){
        if (id=='orderinvoice')
        {
            hidePropTBButtons();
            prop_tb.showItem('orderinvoice_refresh');
            prop_tb.showItem('orderinvoice_add');
            prop_tb.setItemText('panel', '<?php echo _l('Invoices', 1); ?>');
            prop_tb.setItemImage('panel', 'fad fa-file-invoice');
            needinitOrderInvoice = 1;
            initOrderInvoice();
            propertiesPanel='orderinvoice';
            if (lastOrderSelID!=0)
            {
                displayOrderInvoice();
            }
        }
        if (id=='orderinvoice_refresh')
        {
            lastOrderInvoiceSelID = 0;
            displayOrderInvoice();
        }

    }
    prop_tb.attachEvent("onClick", setPropertiesPanel_orderInvoice);


    function displayOrderInvoice()
    {
        prop_tb._orderInvoiceGrid.clearAll(true);
        $.post("index.php?ajax=1&act=ord_invoice_get&id_lang="+SC_ID_LANG,
        {
            id_customer_list: castListId(getListIdFromUserData(ord_grid, 'id_customer'))
        },function(data)
        {
            prop_tb._orderInvoiceGrid.parse(data);
            nb=prop_tb._orderInvoiceGrid.getRowsNum();
            prop_tb._sb.setText('');

          // UISettings
            loadGridUISettings(prop_tb._orderInvoiceGrid);

            // UISettings
            prop_tb._orderInvoiceGrid._first_loading=0;
        });
    }

    let orderinvoice_current_id = 0;
    ord_grid.attachEvent("onRowSelect",function (idproduct){
        if (propertiesPanel=='orderinvoice' && !dhxLayout.cells('b').isCollapsed() && (ord_grid.getSelectedRowId()!==null && orderinvoice_current_id!=idproduct)){
            displayOrderInvoice();
            orderinvoice_current_id=idproduct;
        }
    });

<?php
}
?>