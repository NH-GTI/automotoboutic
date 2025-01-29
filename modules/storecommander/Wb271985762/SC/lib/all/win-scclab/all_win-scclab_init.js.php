<?php
if (!defined('STORE_COMMANDER')) { exit; }
echo '<style>
    .dhxform_base
    {
        display: flex;
        flex-direction: column;
        row-gap: 10px;
    }
    .fs_legend
    {
        text-decoration: underline;
        font-weight: bold !important;;
    }
    
</style>';
?>

<?php echo '<script>'; ?>

wScclab.setText('SCC Lab');

$.post("index.php?ajax=1&act=all_win-scclab_auth", {},function(res) {
    if (res.success)
    { // AUTHENTICATION OK
        dhtmlx.message({text:'<?php echo _l('Authentication OK'); ?> !',type:'success',expire:3000});

        // RECUPERATION DATA API
        var id_scc_prefix_formated = res.data_shop.data_id_scc_prefix.padStart(4, '0');
        var url_shop = res.data_shop.data_shop_url;
        var id_shop = (typeof res.data_shop.data_shop_id === "undefined" ) ? "1" : res.data_shop.data_shop_id;

        //var shop_default = "<?php echo Configuration::get('PS_SHOP_DEFAULT'); ?>";
        //var id_shop = (typeof res.data_shop.data_shop_id === "undefined" ) ? shop_default : res.data_shop.data_shop_id;

        dhxSCCLabLayout=wScclab.attachLayout("3U");

        var cellSCCLab1 = dhxSCCLabLayout.cells('a');
        cellSCCLab1.setText("<?php echo _l('Create').' '._l('E-carte').' '._l('for').' '; ?>"+url_shop);

        var cellSCCLab2 = dhxSCCLabLayout.cells('b');
        //cellSCCLab2.setText("<?php echo _l('Prestashop').' '._l('Objects'); ?>");
        cellSCCLab2.setText("");
        cellSCCLab2.collapse();

        var cellSCCLab3 = dhxSCCLabLayout.cells('c');
        cellSCCLab3.setText("<?php echo _l('E-carte'); ?>");

        var tbSCCLab = cellSCCLab3.attachToolbar();
        tbSCCLab.setIconset('awesome');
        tbSCCLab.addButton("exportcsv", 0, "", "fad fa-file-csv green", "fad fa-file-csv green");
        tbSCCLab.setItemToolTip("exportcsv",'<?php echo _l('Export grid to clipboard in CSV format for MSExcel with tab delimiter.'); ?>');
        tbSCCLab.addButton("exportcsvsemicolon", 0, "", "fa fa-file-csv", "fa fa-file-csv");
        tbSCCLab.setItemToolTip("exportcsvsemicolon",'<?php echo _l('Export grid to clipboard in CSV format with semicolon as delimiter.'); ?>');
        tbSCCLab.addButton("refresh", 100, "", "fa fa-sync green", "fa fa-sync green");
        tbSCCLab.setItemToolTip('refresh','<?php echo _l('Refresh grid', 1); ?>');
        tbSCCLab.attachEvent("onClick", function (id)
        {
            if(id=='exportcsv')
            {
                displayQuickExportWindow(gridSCCLab,1);
                wQuickExportWindow.bringToTop();
                wQuickExportWindow.maximize();
            }
            if(id=='exportcsvsemicolon')
            {
                displayQuickExportWindow(gridSCCLab,1,null,null,false,";");
                wQuickExportWindow.bringToTop();
                wQuickExportWindow.maximize();
            }
            if(id=='refresh') DisplayCartRules();
        });

        var gridSCCLab=cellSCCLab3.attachGrid();

        gridSCCLab.enableSmartRendering(true);
        gridSCCLab.enableMultiselect(true);

        gridSCCLab.setHeader("ID <?php echo _l('Cart rule'); ?>,<?php echo _l('Code'); ?>,<?php echo _l('Amount'); ?>,<?php echo _l('Quantity'); ?>,<?php echo _l('Date from'); ?>,<?php echo _l('Date to'); ?>,ID <?php echo _l('shop'); ?>,ID SCC <?php echo _l('customer'); ?>");
        gridSCCLab.setInitWidths("100,400,100,100,250,250,0,0");
        gridSCCLab.setColAlign("right,left,right,right,left,left,left,left");
        gridSCCLab.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro");
        gridSCCLab.setColSorting("int,str,int,int,str,str,int,str");
        gridSCCLab.attachHeader("#numeric_filter,#text_filter,#select_filter,#select_filter,#text_filter,#text_filter,,");
        gridSCCLab.init();

        var CurrentlyProcessingCartRules=false;
        var CurrentlyProcessingPSEntities=false;

        // FUNCTIONS FORM 1 ###############################################################################
        function DisplayCartRules ()
        {
            $.post("index.php?ajax=1&act=all_win-scclab_get_cart_rules&"+new Date().getTime(),{'cr_prefix':id_scc_prefix_formated,'cr_idshop':id_shop},function(data) {
                if (data != '') {
                    gridSCCLab.parse(data);
                    var array_avail = JSON.parse(gridSCCLab.getUserData(0,'available'));
                    (array_avail[20]===undefined) ? $('[name=scc_nb_gift_cards_20_qty_avail]').val('0') : $('[name=scc_nb_gift_cards_20_qty_avail]').val(array_avail[20]);
                    (array_avail[30]===undefined) ? $('[name=scc_nb_gift_cards_30_qty_avail]').val('0') : $('[name=scc_nb_gift_cards_30_qty_avail]').val(array_avail[30]);
                    (array_avail[50]===undefined) ? $('[name=scc_nb_gift_cards_50_qty_avail]').val('0') : $('[name=scc_nb_gift_cards_50_qty_avail]').val(array_avail[50]);
                    (array_avail[50]===undefined) ? $('[name=scc_nb_gift_cards_50_qty_avail]').val('0') : $('[name=scc_nb_gift_cards_50_qty_avail]').val(array_avail[50]);
                    (array_avail[100]===undefined) ? $('[name=scc_nb_gift_cards_100_qty_avail]').val('0') : $('[name=scc_nb_gift_cards_100_qty_avail]').val(array_avail[100]);

                    var nb = gridSCCLab.getRowsNum();
                    var ud_total = JSON.parse(gridSCCLab.getUserData(0,'total'));
                    var tot = (ud_total[0]['total'] == null) ? '0' : ud_total[0]['total'];
                    cellSCCLab3.setText("<?php echo _l('E-carte').' - '._l('Quantity').' : '; ?>"+nb+" - <?php echo _l('Total amount').' : '; ?>"+tot+' €');
                }
            });
        }

        function CalculateTotals ()
        {
            $('[name=scc_nb_gift_cards_20_total]').val( Number($('[name=scc_nb_gift_cards_20_amount]').val()) * Number($('[name=scc_nb_gift_cards_20_qty]').val()) );
            $('[name=scc_nb_gift_cards_30_total]').val( Number($('[name=scc_nb_gift_cards_30_amount]').val()) * Number($('[name=scc_nb_gift_cards_30_qty]').val()) );
            $('[name=scc_nb_gift_cards_50_total]').val( Number($('[name=scc_nb_gift_cards_50_amount]').val()) * Number($('[name=scc_nb_gift_cards_50_qty]').val()) );
            $('[name=scc_nb_gift_cards_100_total]').val( Number($('[name=scc_nb_gift_cards_100_amount]').val()) * Number($('[name=scc_nb_gift_cards_100_qty]').val()) );
        }

        function AfterCartRulesGeneration ()
        {
            DisplayCartRules();
            currentlyProcessing=false;
            $('.btnSubmitGenerationCartRules')[0].firstChild.firstChild.innerHTML='<?php echo _l('Generate').' '._l('e-Cards'); ?>';
            $('.btnSubmitGenerationCartRules')[0].firstChild.firstChild.classList.remove("fa");
            $('.btnSubmitGenerationCartRules')[0].firstChild.firstChild.classList.remove("fa-spin");
            $('.btnSubmitGenerationCartRules')[0].firstChild.firstChild.classList.remove("fa-spinner");
        }

        async function sendAjaxRequestToGenerateCartRules(nbr20, nbr30, nbr50, nbr100, scc_prefix, id_shop, url_shop)
        {
            return new Promise((resolve, reject) =>
            {
                fetch("index.php?ajax=1&act=all_win-scclab_create_cart_rules",{
                    method: "POST",
                    body: JSON.stringify({
                        cr_qty_20: nbr20,
                        cr_qty_30: nbr30,
                        cr_qty_50: nbr50,
                        cr_qty_100: nbr100,
                        cr_id_scc: scc_prefix,
                        cr_id_shop: id_shop,
                        cr_url_shop: url_shop
                    }),
                    headers: {
                        "Content-type": "application/json; charset=UTF-8"
                    }
                })
                .then((response) => {
                    resolve('all cartrules created');
                    return response.json();
                }).then((data) =>
                {
                    if(data['error']=='mail_not_sent')
                    {
                        dhtmlx.message({
                            text: '<?php echo _l('Mail not sent ! Please send a manual CSV export.'); ?> !',
                            type: 'error',
                            expire: 5000
                        });
                    }
                });
            })
        }

        // FUNCTIONS FORM 2 ###############################################################################
        function DisplayPSEntitiesIds(data)
        {
            $('[name=scc_id_category]').val(data['data_id_category']!=null ? data['data_id_category'] : 'XX');
            $('[name=scc_id_product_20]').val(data['data_id_product_20']!=null ? data['data_id_product_20'] : 'XX');
            $('[name=scc_id_product_30]').val(data['data_id_product_30']!=null ? data['data_id_product_30'] : 'XX');
            $('[name=scc_id_product_50]').val(data['data_id_product_50']!=null ? data['data_id_product_50'] : 'XX');
            $('[name=scc_id_product_100]').val(data['data_id_product_100']!=null ? data['data_id_product_100'] : 'XX');
            $('[name=scc_id_customer]').val(data['data_id_customer']!=null ? data['data_id_customer'] : 'XX');
            $('[name=scc_id_address]').val(data['data_id_address']!=null ? data['data_id_address'] : 'XX');
        }

        function GetAndDisplayPSEntitiesIds(token)
        {
            $.post("index.php?ajax=1&act=all_win-scclab_get_ps_entities_ids&"+new Date().getTime(),{'token':token },function(r) {
                if (r != '')
                {
                    const result = JSON.parse(r);
                    DisplayPSEntitiesIds(result.data_shop);
                }
            });
        }

        function AfterPSEntitiesGeneration ()
        {
            //DisplayCartRules();
            CurrentlyProcessingPSEntities=false;
            $('.btnSubmitGenerationPSEntities')[0].firstChild.firstChild.innerHTML='<?php echo _l('Generate').' '._l('e-Cards'); ?>';
            $('.btnSubmitGenerationPSEntities')[0].firstChild.firstChild.classList.remove("fa");
            $('.btnSubmitGenerationPSEntities')[0].firstChild.firstChild.classList.remove("fa-spin");
            $('.btnSubmitGenerationPSEntities')[0].firstChild.firstChild.classList.remove("fa-spinner");
        }

        async function sendAjaxRequestToGeneratePSEntities(token, data_shop, data_dealers)
        {
            return new Promise((resolve, reject) =>
            {
                fetch("index.php?ajax=1&act=all_win-scclab_create_ps_entities",{
                    method: "POST",
                    body: JSON.stringify({
                        data_shop: data_shop,
                        data_dealers: data_dealers,
                    }),
                    headers: {
                        "Content-type": "application/json; charset=UTF-8"
                    }
                }).then(response => {
                    const data = response.json();
                    return data;
                }).then((data) => {
                    if(data.hasOwnProperty('error'))
                    {
                        dhtmlx.message({
                            text: '<?php echo _l('Error creating PS entities.'); ?> !',
                            type: 'error',
                            expire: 5000
                        });
                        reject(new Error("Error creating PS entities."));
                    }
                    else
                    {
                        dhtmlx.message({
                            text: '<?php echo _l('All PrestaShop entities created.'); ?> !',
                            type: 'success',
                            expire: 5000
                        });
                        GetAndDisplayPSEntitiesIds(token);
                        resolve("All PrestaShop entities created.");
                        GetAndDisplayPSEntitiesIds(res.token);
                    }
                })
                .catch((error) => {
                    reject(error);
                });
            });
        }

        // FORM CELL1 ###############################################################################
        Form1Structure = [
            {type: "fieldset",  name: "quantity", width:"80", offsetTop:10, offsetLeft:40, list:[
                    {type: "label", label: "<?php echo _l('Quantity'); ?>", labelWidth: "auto", labelHeight: "30"},
                    {type:"input", id:"scc_nb_gift_cards_20_qty", name:"scc_nb_gift_cards_20_qty", value:"45", width:60},
                    {type:"input", id:"scc_nb_gift_cards_30_qty", name:"scc_nb_gift_cards_30_qty", value:"20", width:60},
                    {type:"input", id:"scc_nb_gift_cards_50_qty", name:"scc_nb_gift_cards_50_qty", value:"10", width:60},
                    {type:"input", id:"scc_nb_gift_cards_100_qty", name:"scc_nb_gift_cards_100_qty", value:"10", width:60}
                ]}, { type:"newcolumn" },
            {type: "fieldset",  name: "amount", width:"150", offsetTop:10, list:[
                    {type: "label", label: "<?php echo _l('Amount'); ?>", labelWidth: "auto", labelHeight: "30", offsetLeft:"20"},
                    {type:"input", id:"scc_nb_gift_cards_20_amount", name:"scc_nb_gift_cards_20_amount", value:"20", disabled:true, width:60, style:"text-align: right", label: "€", labelWidth: "20", position:"label-right"},
                    {type:"input", id:"scc_nb_gift_cards_30_amount", name:"scc_nb_gift_cards_30_amount", value:"30", disabled:true, width:60, style:"text-align: right", label: "€", labelWidth: "20", position:"label-right"},
                    {type:"input", id:"scc_nb_gift_cards_50_amount", name:"scc_nb_gift_cards_50_amount", value:"50", disabled:true, width:60, style:"text-align: right", label: "€", labelWidth: "20", position:"label-right"},
                    {type:"input", id:"scc_nb_gift_cards_100_amount", name:"scc_nb_gift_cards_100_amount", value:"100", disabled:true, width:60, style:"text-align: right", label: "€", labelWidth: "20", position:"label-right"}
                ]}, { type:"newcolumn" },
            {type: "fieldset",  name: "total", width:"180", offsetTop:10, list:[
                    {type: "label", label: "<?php echo _l('Total'); ?>", labelWidth: "auto", labelHeight: "30", offsetLeft:"20"},
                    {type:"input", id:"scc_nb_gift_cards_20_total", name:"scc_nb_gift_cards_20_total", value:"0", style:"text-align: center", disabled:true, width:80, label: "€", labelWidth: "20", position:"label-right"},
                    {type:"input", id:"scc_nb_gift_cards_30_total", name:"scc_nb_gift_cards_30_total", value:"0", style:"text-align: center", disabled:true, width:80, label: "€", labelWidth: "20", position:"label-right"},
                    {type:"input", id:"scc_nb_gift_cards_50_total", name:"scc_nb_gift_cards_50_total", value:"0", style:"text-align: center", disabled:true, width:80, label: "€", labelWidth: "20", position:"label-right"},
                    {type:"input", id:"scc_nb_gift_cards_100_total", name:"scc_nb_gift_cards_100_total", value:"0", style:"text-align: center", disabled:true, width:80, label: "€", labelWidth: "20", position:"label-right"}
                ]}, { type:"newcolumn" },
            {type: "fieldset",  name: "available", width:"180", offsetTop:10, list:[
                    {type: "label", label: "<?php echo _l('Quantity usable on the shop'); ?>", labelWidth: "auto", labelHeight: "30"},
                    {type:"input", id:"scc_nb_gift_cards_20_qty_avail", name:"scc_nb_gift_cards_20_qty_avail", value:"0", style:"text-align: center", disabled:true, width:60},
                    {type:"input", id:"scc_nb_gift_cards_30_qty_avail", name:"scc_nb_gift_cards_30_qty_avail", value:"0", style:"text-align: center", disabled:true, width:60},
                    {type:"input", id:"scc_nb_gift_cards_50_qty_avail", name:"scc_nb_gift_cards_50_qty_avail", value:"0", style:"text-align: center", disabled:true, width:60},
                    {type:"input", id:"scc_nb_gift_cards_100_qty_avail", name:"scc_nb_gift_cards_100_qty_avail", value:"0", style:"text-align: center", disabled:true, width:60}
                ]},
            {type:"button", id:"scc_ask_gift_cards_submit", name:"scc_ask_gift_cards_submit", value:"<?php echo _l('Generate').' '._l('e-Cards'); ?>", className:"btnSubmitGenerationCartRules"}
        ];
        dhxLabForm1 = cellSCCLab1.attachForm(Form1Structure);

        // FORM CELL2 ###############################################################################
        Form2Structure = [
            {type:"input", id:"scc_id_category", name:"scc_id_category", value:"", width:60, label: "<?php echo _l('Category'); ?>", labelWidth: 100, offsetLeft:100, offsetTop:40, disabled:true},
            {type:"input", id:"scc_id_product_20", name:"scc_id_product_20", value:"", width:60, label: "<?php echo _l('Product'); ?> 20", labelWidth: 100, offsetLeft:100, disabled:true},
            {type:"input", id:"scc_id_product_30", name:"scc_id_product_30", value:"", width:60, label: "<?php echo _l('Product'); ?> 30", labelWidth: 100, offsetLeft:100, disabled:true},
            {type:"input", id:"scc_id_product_50", name:"scc_id_product_50", value:"", width:60, label: "<?php echo _l('Product'); ?> 50", labelWidth: 100, offsetLeft:100, disabled:true},
            {type:"input", id:"scc_id_product_100", name:"scc_id_product_100", value:"", width:60, label: "<?php echo _l('Product'); ?> 40", labelWidth: 100, offsetLeft:100, disabled:true},
            { type:"newcolumn" },
            {type:"input", id:"scc_id_customer", name:"scc_id_customer", value:"", width:60, label: "<?php echo _l('Customer'); ?>", labelWidth: 100, offsetLeft:100, offsetTop:40,disabled:true},
            {type:"input", id:"scc_id_address", name:"scc_id_address", value:"", width:60, label: "<?php echo _l('Address'); ?>", labelWidth: 100, offsetLeft:100, disabled:true},
            { type:"newcolumn" },
            {type:"button", id:"scc_ask_ps_entities_submit", name:"scc_ask_ps_entities_submit", offsetLeft:100, offsetTop:100, value:"<?php echo _l('Generate').' '._l('entities PS'); ?>", className:"btnSubmitGenerationPSEntities"}
        ];
        // dhxLabForm2 = cellSCCLab2.attachForm(Form2Structure);

        // DEFAULT FUNCTIONS CALLS
        DisplayCartRules();
        CalculateTotals();
        DisplayPSEntitiesIds(res.data_shop);
        // GetAndDisplayPSEntitiesIds(res.token);

        // EVENTS MANAGEMENT FORM1
        dhxLabForm1.attachEvent("onChange", function (id) {
            if (id=="scc_nb_gift_cards_20_qty" || id=="scc_nb_gift_cards_30_qty" || id=="scc_nb_gift_cards_50_qty" || id=="scc_nb_gift_cards_100_qty") CalculateTotals();
        });

        dhxLabForm1.attachEvent("onButtonClick", function (id)
        {
            if(id=='scc_ask_gift_cards_submit' && !CurrentlyProcessingCartRules) {

                // BLOQUER SUBMIT BUTTON
                CurrentlyProcessingCartRules = true;
                $('.btnSubmitGenerationCartRules')[0].firstChild.firstChild.innerHTML='';
                $('.btnSubmitGenerationCartRules')[0].firstChild.firstChild.classList.add("fa");
                $('.btnSubmitGenerationCartRules')[0].firstChild.firstChild.classList.add("fa-spinner");
                $('.btnSubmitGenerationCartRules')[0].firstChild.firstChild.classList.add("fa-spin");

                var nbr_gift_card_20 = Number($('[name=scc_nb_gift_cards_20_qty]').val());
                var nbr_gift_card_30 = Number($('[name=scc_nb_gift_cards_30_qty]').val());
                var nbr_gift_card_50 = Number($('[name=scc_nb_gift_cards_50_qty]').val());
                var nbr_gift_card_100 = Number($('[name=scc_nb_gift_cards_100_qty]').val());
                if (Number.isInteger(nbr_gift_card_20) && Number.isInteger(nbr_gift_card_30) && Number.isInteger(nbr_gift_card_50) && Number.isInteger(nbr_gift_card_100))
                {
                    // CREATION DE TOUS LES BONS 20€, 30€, 50€, 100€
                    sendAjaxRequestToGenerateCartRules(nbr_gift_card_20, nbr_gift_card_30, nbr_gift_card_50, nbr_gift_card_100, id_scc_prefix_formated, id_shop, url_shop)
                        .then(AfterCartRulesGeneration);
                }
                else
                {
                    dhtmlx.message({text:'<?php echo _l('Format error (integers needed)'); ?> !',type:'error',expire:3000});
                }
            }
        })

        // EVENTS MANAGEMENT FORM2
        /*dhxLabForm2.attachEvent("onButtonClick", function (id)
        {
            if(id=='scc_ask_ps_entities_submit' && !CurrentlyProcessingPSEntities) {
                // BLOQUER SUBMIT BUTTON
                CurrentlyProcessingPSEntities = true;
                $('.btnSubmitGenerationPSEntities')[0].firstChild.firstChild.innerHTML='';
                $('.btnSubmitGenerationPSEntities')[0].firstChild.firstChild.classList.add("fa");
                $('.btnSubmitGenerationPSEntities')[0].firstChild.firstChild.classList.add("fa-spinner");
                $('.btnSubmitGenerationPSEntities')[0].firstChild.firstChild.classList.add("fa-spin");

                if (res.data_shop['data_id_category'] == null || res.data_shop['data_id_product_20'] == null || res.data_shop['data_id_product_30'] == null || res.data_shop['data_id_product_50'] == null || res.data_shop['data_id_product_100'] == null || res.data_shop['data_id_customer'] == null || res.data_shop['data_id_address'] == null)
                {
                    // CREATION DES PS ENTITIES SI AU MOINS UNE MANQUANTE
                    sendAjaxRequestToGeneratePSEntities(res.token, res.data_shop, res.data_dealers)
                        .then(AfterPSEntitiesGeneration);
                }
                else
                {
                    dhtmlx.message({text:'<?php echo _l('All PS Entities already created !'); ?> !',type:'error',expire:3000});
                    AfterPSEntitiesGeneration();
                }
            }
        })*/
    }
    else
    {
        dhtmlx.message({text:'<?php echo _l('Authentication failed'); ?> !',type:'error',expire:3000});
    }
},'json')
<?php echo '</script>'; ?>