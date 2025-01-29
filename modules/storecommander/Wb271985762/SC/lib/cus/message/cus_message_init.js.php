<?php
if (!defined('STORE_COMMANDER')) { exit; }

if (_r('GRI_CUS_PROPERTIES_GRID_MESSAGE')) { ?>
    prop_tb.addListOption('panel', 'message', 3, "button", '<?php echo _l('Messages', 1); ?>', "fad fa-comments");
    allowed_properties_panel[allowed_properties_panel.length] = "message";

    prop_tb.addButton("message_refresh",1000, "", "fa fa-sync green", "fa fa-sync green");
    prop_tb.setItemToolTip('message_refresh','<?php echo _l('Refresh grid', 1); ?>');
    prop_tb.addButton("send_mail",1000, "", "fad fa-paper-plane green", "fad fa-paper-plane green");
    prop_tb.setItemToolTip('send_mail','<?php echo _l('Send mail to customer'); ?>');


    needinitmessage = 1;
    function initmessage(){
        if (needinitmessage)
        {
            prop_tb._messageLayout = dhxLayout.cells('b').attachLayout('1C');
            prop_tb._messageLayout.cells('a').hideHeader();
            dhxLayout.cells('b').showHeader();
            prop_tb._messageGrid = prop_tb._messageLayout.cells('a').attachGrid();

            // UISettings
            prop_tb._messageGrid._uisettings_prefix='cus_message';
            prop_tb._messageGrid._uisettings_name=prop_tb._messageGrid._uisettings_prefix;
               prop_tb._messageGrid._first_loading=1;

            // UISettings
            initGridUISettings(prop_tb._messageGrid);

            needinitmessage=0;
        }
    }


    function setPropertiesPanel_message(id){
        if (id=='message')
        {
            if(lastCustomerSelID!=undefined && lastCustomerSelID!="")
            {
                idxLastname=cus_grid.getColIndexById('lastname');
                idxFirstname=cus_grid.getColIndexById('firstname');
                dhxLayout.cells('b').setText('<?php echo _l('Properties', 1).' '._l('of', 1); ?> '+cus_grid.cells(lastCustomerSelID,idxFirstname).getValue()+" "+cus_grid.cells(lastCustomerSelID,idxLastname).getValue());
            }
            hidePropTBButtons();
            prop_tb.showItem('message_refresh');
            prop_tb.showItem('send_mail');
            prop_tb.setItemText('panel', '<?php echo _l('Message', 1); ?>');
            prop_tb.setItemImage('panel', 'fad fa-comments');
            needinitmessage = 1;
            initmessage();
            propertiesPanel='message';
            if (lastCustomerSelID!=0)
            {
                displayMessage();
            }
        }
        if (id=='message_refresh')
        {
            displayMessage();
        }
        if (id=='send_mail')
        {
            if(dhxWins.window("wSendMail")){
                dhxWins.window("wSendMail").unload;
            }
            const params = {
                id_shop: shopselection,
                id_lang: SC_ID_LANG,
                selectedIds: String(cus_grid.getSelectedRowId())
            };
            wSendMail = dhxWins.createWindow("wSendMail", 0, 0, 800, $(window).height() - 20);
            wSendMail.center();
            wSendMail.setText('<?php echo _l('Send an email', 1); ?>');
            $.post("index.php?ajax=1&act=all_win-mail_init",params, function
                (data) {
                $('#jsExecute').html(data);
            });
        }

    }
    prop_tb.attachEvent("onClick", setPropertiesPanel_message);


    function displayMessage()
    {
        prop_tb._messageGrid.clearAll(true);
        $.post('index.php?ajax=1&act=cus_message_get',
        {
            id_customer: Number(cus_grid.getUserData(lastCustomerSelID, 'id_customer'))
        },function(response)
        {
            prop_tb._messageGrid.parse(response)
            nb=prop_tb._messageGrid.getRowsNum();
            prop_tb._sb.setText('');

            // UISettings
            loadGridUISettings(prop_tb._messageGrid);

            // UISettings
            prop_tb._messageGrid._first_loading=0;
        });
    }



    let message_current_id = 0;
    cus_grid.attachEvent("onRowSelect",function (idcustomer){
        if (propertiesPanel=='message' && !dhxLayout.cells('b').isCollapsed() && (cus_grid.getSelectedRowId()!==null && message_current_id!=idcustomer)){
            displayMessage();
            message_current_id=idcustomer;
        }
    });

<?php
    } // end permission
?>
