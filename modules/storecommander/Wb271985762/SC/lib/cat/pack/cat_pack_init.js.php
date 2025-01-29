<?php
if (!defined('STORE_COMMANDER')) { exit; }

$propertyLabel =  _l('Pack', 1);

if (Sc\Service\Service::exists('shippingbo'))
{
    $propertyLabel =ucfirst(_l('packs', 1)).' '._l('and').' '._l('batches', 1);
}

if (_r('GRI_CAT_PROPERTIES_PRODUCT_PACK') && version_compare(_PS_VERSION_, '1.6.1.12', '>=')) { ?>
    prop_tb.addListOption('panel', 'pack', 15, "button", '<?php echo $propertyLabel; ?>', "fa fa-cube");
    prop_tb.addButton("set_in_ps",1000, "", "fa fa-prestashop", "fa fa-prestashop");
    prop_tb.setItemToolTip('set_in_ps','<?php echo _l('Configure the pack in PrestaShop'); ?>');
    getTbSettingsButton(prop_tb, {'grideditor':<?php echo (int)_r('MEN_TOO_GRIDSSETTINGS'); ?>,'settings':0}, 'prop_pack_',1000);
    allowed_properties_panel[allowed_properties_panel.length] = "pack";

    needInitpack = 1;
    var lastPdtPackSelectedId = null;
    function initpack()
    {
        if (needInitpack)
        {
            lastPdtPackSelectedId = null;
            prop_tb._packLayout = dhxLayout.cells('b').attachLayout('2E');
            dhxLayout.cells('b').showHeader();

            // PRODUCTS
            prop_tb._packProduct = prop_tb._packLayout.cells('a');
            prop_tb._packProduct.setText('<?php echo _l('Products', 1); ?>');

            prop_tb._packProduct_tb = prop_tb._packProduct.attachToolbar();
            prop_tb._packProduct_tb.setIconset('awesome');
            prop_tb._packProduct_tb.addButton("pack_refresh", 1000, "", "fa fa-sync green", "fa fa-sync green");
            prop_tb._packProduct_tb.setItemToolTip('pack_refresh','<?php echo _l('Refresh grid', 1); ?>');
            prop_tb._packProduct_tb.addButton("pack_pdt_delete", 1000, "", "fa fa-minus-circle red", "fa fa-minus-circle red");
            prop_tb._packProduct_tb.setItemToolTip('pack_pdt_delete','<?php echo _l('Remove the selected product from the pack', 1); ?>');
            prop_tb._packProduct_tb.addButton("exportcsv", 1000, "", "fad fa-file-csv green", "fad fa-file-csv green");
            prop_tb._packProduct_tb.setItemToolTip('exportcsv','<?php echo _l('Export grid to clipboard in CSV format for MSExcel with tab delimiter.'); ?>');
            prop_tb._packProduct_tb.addInput("search",1000,'#searchPdtForPack',200);
            prop_tb._packProduct_tb.setItemToolTip('search','<?php echo _l('Search a product/combination to add in pack'); ?>');
            prop_tb._packProduct_tb.attachEvent("onClick", function(id){
                switch(id){
                    case 'pack_refresh':
                        displayPackProduct();
                        break;
                    case 'pack_pdt_delete':
                        if(lastProductSelID!=undefined && lastProductSelID!=null && lastProductSelID!="" && lastProductSelID!=0)
                        {
                            if(lastPdtPackSelectedId!=undefined && lastPdtPackSelectedId!=null && lastPdtPackSelectedId!="" && lastPdtPackSelectedId!=0)
                            {
                                if (confirm('<?php echo _l('Do you want to remove this product from this pack?', 1); ?>')){
                                    $.post("index.php?ajax=1&act=cat_pack_update&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),
                                    {
                                        'id_pack': lastProductSelID,
                                        'id_product': lastPdtPackSelectedId,
                                        'action': 'delete'
                                    },
                                    function(data)
                                    {
                                        refreshMainGridSelectedData(cat_grid);
                                        displayPackProduct();
                                    });
                                }
                            }
                        }
                        break;
                    case 'exportcsv':
                        displayQuickExportWindow(prop_tb._packProductGrid,1);
                        break;
                }
            });

            var inputSearch = $("input[value='#searchPdtForPack']");
            inputSearch.val("");
            inputSearch.attr("placeholder",'<?php echo _l('Search a product/combination to add in pack'); ?>');
            inputSearch.autocomplete({
                minLength: 1,
                classes: {
                    "ui-autocomplete": "autocomplete_window_export packLayer"
                },
                open: function(event, ui)
                {

                    let itemOffsset= inputSearch.offset();
                    $('.autocomplete_window_export.packLayer').offset({top: itemOffsset.top+26, left:itemOffsset.left});
                },
                source: function (request, response) {
                    $.ajax({
                        url: 'index.php?ajax=1&act=cat_pack_search',
                        dataType: "json",
                        data: {
                            q: request.term
                        },
                        success: function (data) {
                            const formattedData = [];
                            data.forEach(function (item) {
                                formattedData.push({
                                    data: {
                                        id_product: item.id_product,
                                        id_product_attribute: item.id_product_attribute,
                                        pname: item.pname
                                    },
                                    value: item.pname
                                });
                            });
                            response(formattedData);
                        }
                    });
                },
                select: function (event, ui) {
                    if(lastProductSelID!=undefined && lastProductSelID!=null && lastProductSelID!="" && lastProductSelID!=0)
                    {
                        $.post("index.php?ajax=1&act=cat_pack_update&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),
                        {
                            'id_pack': lastProductSelID,
                            'id_product': ui.item.data.id_product,
                            'id_product_attribute': ui.item.id_product_attribute,
                            'action': 'insert'
                        },
                        function()
                        {
                            refreshMainGridSelectedData(cat_grid);
                            displayPackProduct();
                        });
                    }
                    return false;
                }
            });

            prop_tb._packProductGrid = prop_tb._packProduct.attachGrid();
            prop_tb._packProductGrid._name='_packProductGrid';
            prop_tb._packProductGrid.enableDragAndDrop(false);
            prop_tb._packProductGrid.enableMultiselect(false);
            prop_tb._packProductGrid.enableColSpan(true);


            // UISettings
            prop_tb._packProductGrid._uisettings_prefix='cat_pack';
            prop_tb._packProductGrid._uisettings_name=prop_tb._packProductGrid._uisettings_prefix;
            prop_tb._packProductGrid._first_loading=1;

            // UISettings
            initGridUISettings(prop_tb._packProductGrid);

            prop_tb._packProductGrid.attachEvent("onEditCell",function(stage,rId,cInd,nValue,oValue)
            {
                if(lastProductSelID!=undefined && lastProductSelID!=null && lastProductSelID!="" && lastProductSelID!=0)
                {
                    idxQuantity=prop_tb._packProductGrid.getColIndexById('quantity');
                    if(stage==2 && idxQuantity==cInd)
                    {
                        var action = "quantity";
                        var value = nValue;

                        $.post("index.php?ajax=1&act=cat_pack_update&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),
                            {
                                'id_pack': lastProductSelID,
                                'id_product': rId,
                                'id_product_attribute': 0,
                                'value': value,
                                'action': action
                            },
                            function(data)
                            {
                                refreshMainGridSelectedData(cat_grid);
                            });
                    }
                }
                return true;
            });

            prop_tb._packProductGrid.attachEvent("onRowSelect",function (idpdt){
                before = lastPdtPackSelectedId;
                lastPdtPackSelectedId = idpdt;
                if (before != lastPdtPackSelectedId){
                    displayPackCombi();
                }
            });

            // COMBI
            prop_tb._PackCombi = prop_tb._packLayout.cells('b');
            prop_tb._PackCombi.setText('<?php echo _l('Combinations', 1); ?>');

            prop_tb._PackCombi_tb = prop_tb._PackCombi.attachToolbar();
             prop_tb._PackCombi_tb.setIconset('awesome');
            prop_tb._PackCombi_tb.addButton("pack_combi_refresh", 100, "", "fa fa-sync green", "fa fa-sync green");
            prop_tb._PackCombi_tb.setItemToolTip('pack_combi_refresh','<?php echo _l('Refresh grid', 1); ?>');
            prop_tb._PackCombi_tb.addButton("exportcsv", 100, "", "fad fa-file-csv green", "fad fa-file-csv green");
            prop_tb._PackCombi_tb.setItemToolTip('exportcsv','<?php echo _l('Export grid to clipboard in CSV format for MSExcel with tab delimiter.'); ?>');
            prop_tb._PackCombi_tb.attachEvent("onClick", function(id){
                if (id=='pack_combi_refresh')
                {
                    displayPackCombi();
                }
                else if (id=='exportcsv'){
                    displayQuickExportWindow(prop_tb._PackCombiGrid,1);
                }
            });

            prop_tb._PackCombiGrid = prop_tb._PackCombi.attachGrid();
            prop_tb._PackCombiGrid._name='_PackCombiGrid';
            prop_tb._PackCombiGrid.enableDragAndDrop(false);
            prop_tb._PackCombiGrid.enableMultiselect(false);

            prop_tb._PackCombiGrid.attachEvent("onEditCell",function(stage,rId,cInd,nValue,oValue)
            {
                if(lastProductSelID!=undefined && lastProductSelID!=null && lastProductSelID!="" && lastProductSelID!=0)
                {
                    if(lastPdtPackSelectedId!=undefined && lastPdtPackSelectedId!=null && lastPdtPackSelectedId!="" && lastPdtPackSelectedId!=0)
                    {
                        idxPresent=prop_tb._PackCombiGrid.getColIndexById('present');
                        idxQuantity=prop_tb._PackCombiGrid.getColIndexById('quantity');
                        if(stage==1 && idxPresent==cInd)
                        {
                            var action = "combi_present";
                            var value = prop_tb._PackCombiGrid.cells(rId,cInd).isChecked();

                            $.post("index.php?ajax=1&act=cat_pack_update&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),
                            {
                                'id_pack': lastProductSelID,
                                'id_product': lastPdtPackSelectedId,
                                'id_product_attribute': rId,
                                'value': value,
                                'action': action
                            },
                            function(data)
                            {
                                displayPackCombi();
                            });
                        }
                        else if(stage==2 && idxQuantity==cInd)
                        {
                            var action = "quantity";
                            var value = nValue;

                            $.post("index.php?ajax=1&act=cat_pack_update&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),
                            {
                                'id_pack': lastProductSelID,
                                'id_product': lastPdtPackSelectedId,
                                'id_product_attribute': rId,
                                'value': value,
                                'action': action
                            },
                            function(data)
                            {});
                        }
                    }
                }
                return true;
            });

            // UISettings
            prop_tb._PackCombiGrid._uisettings_prefix='cat_pack_combi';
            prop_tb._PackCombiGrid._uisettings_name=prop_tb._PackCombiGrid._uisettings_prefix;
            prop_tb._PackCombiGrid._first_loading=1;

            // UISettings
            initGridUISettings(prop_tb._PackCombiGrid);

            needInitpack=0;
        }
    }

    function setPropertiesPanel_pack(id){
        switch(id){
            case 'pack':
                if(lastProductSelID!=undefined && lastProductSelID!="")
                {
                    dhxLayout.cells('b').setText('<?php echo _l('Properties', 1).' '._l('of', 1); ?> '+getSelectedItemValueOrID(cat_grid,lastProductSelID,'name'));
                }
                hidePropTBButtons();
                prop_tb.setItemText('panel', '<?php echo $propertyLabel; ?>');
                prop_tb.setItemImage('panel', 'fa fa-cube');
                prop_tb.showItem('set_in_ps');
                prop_tb.showItem('prop_pack_settings_menu');
                needInitpack = 1;
                initpack();
                propertiesPanel='pack';
                if (lastProductSelID!=0)
                {
                    displayPackProduct();
                }
                break;
            case 'prop_pack_grideditor':
                openWinGridEditor('type_proppackproduct');
                break;
            case 'set_in_ps':
                if(lastProductSelID!=undefined && lastProductSelID!=null && lastProductSelID!="" && lastProductSelID!=0) {
                    if (!dhxWins.isWindow("wSetpack"))
                    {
                        wSetpack = dhxWins.createWindow("wSetpack", 50, 50, <?php echo version_compare(_PS_VERSION_, '1.7.0.0', '>=') ? 1460 : 1000; ?>, $(window).height()-75);
                        wSetpack.setText('<?php echo _l('Configure the pack in PrestaShop', 1); ?>');
                        let adminUrl = '';
                        <?php if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) { ?>
                            let set_shop_context_url = '';
                            if (shopselection !== null && shopselection !== undefined && shopselection !== 0) {
                                set_shop_context_url = "setShopContext=s-"+shopselection;
                            }
                            adminUrl = "<?php echo SC_PS_PATH_ADMIN_REL.'index.php?controller='.SC_MODULE_ADMIN_CONTROLLER_NAME.'&REDIRECTADMIN=1&subaction=AdminProducts&urlParams[id_product]="+lastProductSelID+"&token='.$sc_agent->getPSToken(SC_MODULE_ADMIN_CONTROLLER_NAME).'&"+set_shop_context_url'; ?>;
                        <?php }
else
{ ?>
                            adminUrl = "<?php echo SC_PS_PATH_ADMIN_REL.'index.php?'.(version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? 'controller=AdminProducts' : 'tab=AdminCatalog').'&id_product="+lastProductSelID+"&id_lang="+SC_ID_LANG+"&adminlang=1&token='.$sc_agent->getPSToken((version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? 'AdminProducts' : 'AdminCatalog')); ?>";
                        <?php } ?>
                        wSetpack.attachURL(adminUrl);
                        pushOneUsage('pack_init-bo-link-adminproducts','cat');
                        wSetpack.attachEvent("onClose", function(win){
                            refreshMainGridSelectedData(cat_grid);
                            displayPackProduct();
                            return true;
                        });
                    }
                }
                break;
        }
    }
    prop_tb.attachEvent("onClick", setPropertiesPanel_pack);

    function displayPackProduct()
    {
        if(lastProductSelID!=undefined && lastProductSelID!=null && lastProductSelID!="" && lastProductSelID!=0)
        {
            let has_combination = Number(cat_grid.getUserData(lastProductSelID,"has_combination"));
            let params = {
                'id_pack': lastProductSelID,
                'has_combination': has_combination
            };

            <?php if(\Sc\Service\Service::exists('shippingbo')) {?>
            let sbo_params = {
                'id_sbo': cat_grid.getUserData(lastProductSelID, "id_sbo"),
                'id_sbo_source': cat_grid.getUserData(lastProductSelID, "id_sbo_source"),
                'type_sbo': cat_grid.getUserData(lastProductSelID, "type_sbo")
            }
            params = {
                ...params,
                ...sbo_params
            }
            <?php } ?>

            prop_tb._packProductGrid.clearAll(true);
            $.post("index.php?ajax=1&act=cat_pack_get&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),params,function(data)
            {
                prop_tb._packProductGrid.parse(data);
                nb=prop_tb._packProductGrid.getRowsNum();
                prop_tb._packProductGrid._rowsNum=nb;

                if(prop_tb._packProductGrid.doesRowExist(0)){
                    prop_tb._packProduct_tb.hideItem('search');
                    prop_tb._packProduct_tb.hideItem('pack_pdt_delete');
                } else {
                    prop_tb._packProduct_tb.showItem('search');
                    prop_tb._packProduct_tb.showItem('pack_pdt_delete');
                }

                // UISettings
                loadGridUISettings(prop_tb._packProductGrid);
                prop_tb._packProductGrid._first_loading=0;
            });
        }
    }

    function displayPackCombi()
    {
        prop_tb._PackCombiGrid.clearAll(true);
        if(lastProductSelID!=undefined && lastProductSelID!=null && lastProductSelID!="" && lastProductSelID!=0)
        {
            if(lastPdtPackSelectedId!=undefined && lastPdtPackSelectedId!=null && lastPdtPackSelectedId!="" && lastPdtPackSelectedId!=0)
            {
                $.post("index.php?ajax=1&act=cat_pack_combi_get&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),{'id_pack': lastProductSelID, 'id_product':lastPdtPackSelectedId},function(data)
                {
                    prop_tb._PackCombiGrid.parse(data);
                    nb=prop_tb._PackCombiGrid.getRowsNum();
                    prop_tb._PackCombiGrid._rowsNum=nb;

                    // UISettings
                    loadGridUISettings(prop_tb._PackCombiGrid);
                    prop_tb._PackCombiGrid._first_loading=0;
                });
            }
        }
    }

    let pack_current_id = 0;
    cat_grid.attachEvent("onRowSelect",function (idproduct){
        if (propertiesPanel=='pack' && (cat_grid.getSelectedRowId()!==null && pack_current_id!=idproduct)){
            displayPackProduct();
            lastPdtPackSelectedId=0;
            displayPackCombi();
            pack_current_id=idproduct;
        }
    });


<?php }
