<?php
if (!defined('STORE_COMMANDER')) { exit; }
echo '<script>'; ?>
dhxlSpecificPrice=wSpecificPrice.attachLayout("2U");

// FILTER TREE
    var dhxlColFilter = dhxlSpecificPrice.cells('a');
    dhxlColFilter.hideHeader();
    dhxlColFilter.setWidth("300");

<?php
 if (SCMS) { ?>
        dhxlLayout_filter = dhxlColFilter.attachLayout("3E");
        
        dhxlPanel_stores = dhxlLayout_filter.cells('a');
        dhxlPanel_categories = dhxlLayout_filter.cells('b');
        dhxlPanel_filters = dhxlLayout_filter.cells('c');
    <?php }
 else
 { ?>
        dhxlLayout_filter = dhxlColFilter.attachLayout("2E");
        
        dhxlPanel_categories = dhxlLayout_filter.cells('a');
        dhxlPanel_filters = dhxlLayout_filter.cells('b');
    <?php } ?>

    // STORES
    <?php if (SCMS) { ?>
        var specificPrice_has_shop_restrictions = false;
        var specificPrice_shopselection = shopselection;
        
        dhxlPanel_stores.setText('<?php echo _l('Stores', 1); ?>');
        
        specificPrice_shoptree=dhxlPanel_stores.attachTree();
        specificPrice_shoptree._name='shoptree_specificPrice';
        specificPrice_shoptree.autoScroll=false;
        specificPrice_shoptree.enableSmartXMLParsing(true);

        displayShopTree_specificPrice();

        function displayShopTree_specificPrice(callback) {
            specificPrice_shoptree.deleteChildItems(0);
            specificPrice_shoptree.load("index.php?ajax=1&act=cat_win-specificprice_shop_get&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),function(){
                    specificPrice_has_shop_restrictions = specificPrice_shoptree.getUserData(0, "specificPrice_has_shop_restrictions");

                    if (specificPrice_shopselection!=null && specificPrice_shopselection!=undefined && specificPrice_shopselection!=0)
                    {
                        specificPrice_shoptree.openItem(specificPrice_shopselection);
                        specificPrice_shoptree.selectItem(specificPrice_shopselection,true);
                    }
                    
                    if(specificPrice_has_shop_restrictions)
                    {
                        selected = specificPrice_shoptree.getSelectedItemId();
                        if(selected==undefined || selected==null || selected=="")
                        {
                            var all = specificPrice_shoptree.getAllSubItems(0);
                            if(all!=undefined && all!=null && all!="")
                            {
                                all = all.split(",");
                                var id_to_select = "";
                                $.each(all, function(index, id) {
                                    if(id.search("G")<0)
                                    {
                                        if(id_to_select=="")
                                            id_to_select = id;
                                    }
                                });
                                specificPrice_shopselection = id_to_select;
                                specificPrice_shoptree.openItem(specificPrice_shopselection);
                                specificPrice_shoptree.selectItem(specificPrice_shopselection,true);
                            }
                        }
                    }

                    if (callback!='') eval(callback);
                    specificPrice_shoptree.openAllItems(0);
                });
        }


        specificPrice_shoptree.attachEvent("onClick",onClickShopTree_specificPrice);
        function onClickShopTree_specificPrice(idshop, param,callback){
            if (idshop[0]=='G'){
                specificPrice_shoptree.clearSelection();
                specificPrice_shoptree.selectItem(specificPrice_shopselection,false);
                return false;
            }
            else if (idshop == 'all'){
                idshop = 0;
                dhxlPanel_categories.setText('<?php echo _l('Categories', 1); ?>');
            }
            else
            {
                dhxlPanel_categories.setText('<?php echo _l('Categories', 1).' '._l('of', 1); ?> '+specificPrice_shoptree.getItemText(idshop));
            }
            if (idshop != specificPrice_shopselection)
                specificPrice_shopselection = idshop;
            
            displayCategories_specificPrice();
            displayFilters_specificPrice();
        }
    <?php } ?>

    // CATEGORIES
        var specificPrice_catselection = null;
        var specificPrice_withSubCateg = 0;
        
        dhxlPanel_categories.setText('<?php echo _l('Categories', 1); ?>');

        var specificPrice_categoriesTB = dhxlPanel_categories.attachToolbar();
          specificPrice_categoriesTB.setIconset('awesome');
        specificPrice_categoriesTB.addButtonTwoState("withSubCateg", 0, "", "fad fa-link green", "fad fa-link green");
        specificPrice_categoriesTB.setItemToolTip('withSubCateg','<?php echo _l('If enabled: display specific prices from all subcategories', 1); ?>');
        specificPrice_categoriesTB.attachEvent("onStateChange", function(id,state){
                if (id=='withSubCateg'){
                    if (state) {
                        specificPrice_withSubCateg=1;
                    }else{
                        specificPrice_withSubCateg=0;
                    }
                    displaySpecificPriceWin();
                }
            });

        specificPrice_cattree=dhxlPanel_categories.attachTree();
        specificPrice_cattree._name='cattree_specificPrice';
        specificPrice_cattree.autoScroll=false;
        specificPrice_cattree.enableSmartXMLParsing(true);
        
        function displayCategories_specificPrice()
        {
            specificPrice_cattree.deleteChildItems(0);
            specificPrice_cattree.load("index.php?ajax=1&act=cat_category_get&id_lang="+SC_ID_LANG+"<?php if (SCMS)
 {
     echo '&id_shop="+specificPrice_shopselection+"';
 } ?>&with_segment=0&"+new Date().getTime(),function(){
                if (specificPrice_catselection!=0)
                {
                    var cat_pos = specificPrice_cattree.getIndexById(specificPrice_catselection);
                    if(cat_pos!=undefined && cat_pos!==false && cat_pos!=null && cat_pos!="")
                    {
                        specificPrice_cattree.openItem(specificPrice_catselection);
                        specificPrice_cattree.selectItem(specificPrice_catselection,true);
                    }
                }
            });
        }
        <?php if (!SCMS){ ?>
            displayCategories_specificPrice();
        <?php } ?>

        specificPrice_cattree.attachEvent("onClick",function(idcategory){
            if (idcategory!=specificPrice_catselection)
            {
                specificPrice_catselection=idcategory;

                displaySpecificPriceWin();
            }
        });

    // FILTERS
    var specificPrice_filterselection = "dat_present,dat_futur,dat_unlimited";
    var specificPrice_filterdate = "<?php echo date('d/m/Y'); ?>";
        
    dhxlPanel_filters.setText('<?php echo _l('Filters', 1); ?>');

    specificPrice_filtertree=dhxlPanel_filters.attachTree();
    specificPrice_filtertree._name='filtertree_specificPrice';
    specificPrice_filtertree.autoScroll=false;
    specificPrice_filtertree.enableSmartXMLParsing(true);
    specificPrice_filtertree.enableCheckBoxes(true);
    specificPrice_filtertree.enableThreeStateCheckboxes(true);

    var specificPrice_filtertreeTB = dhxlPanel_filters.attachToolbar();
      specificPrice_filtertreeTB.setIconset('awesome');
    specificPrice_filtertreeTB.addButton("change_date", 0, "", "fa fa-calendar-alt", "fa fa-calendar-alt");
    specificPrice_filtertreeTB.setItemToolTip('change_date','<?php echo _l('Validate', 1); ?>');
    specificPrice_filtertreeTB.addInput("date", 0, specificPrice_filterdate, "60");
    specificPrice_filtertreeTB.setItemToolTip('date','<?php echo _l('Specific prices on this date', 1); ?>');
    specificPrice_filtertreeTB.addText("date_text", 0, '<?php echo _l('Update date', 1); ?>');
    specificPrice_filtertreeTB.addButton("reset", 0, "", "fa fa-eraser red", "fa fa-eraser red");
    specificPrice_filtertreeTB.setItemToolTip('reset','<?php echo _l('Reset filters', 1); ?>');
    specificPrice_filtertreeTB.attachEvent("onClick", function(id) {
        if (id=='change_date')
        {
            var date_val = specificPrice_filtertreeTB.getValue("date");
            specificPrice_filterdate = date_val;
            specificPrice_filtertree.setItemText("dat_past",'<?php echo _l('Before'); ?> '+date_val);
            specificPrice_filtertree.setItemText("dat_present",'<?php echo _l('On'); ?> '+date_val);
            specificPrice_filtertree.setItemText("dat_futur",'<?php echo _l('After'); ?> '+date_val);

            displaySpecificPriceWin();
        }
        if (id=='reset')
        {
            specificPrice_filterselection = "dat_present,dat_futur,dat_unlimited";
            specificPrice_filterdate = "<?php echo date('d/m/Y'); ?>";
            specificPrice_filtertreeTB.setValue("date", specificPrice_filterdate);
            displayFilters_specificPrice();
            displaySpecificPriceWin();
        }
    });

    
    calendarDateT = new dhtmlXCalendarObject(specificPrice_filtertreeTB.getInput("date"));
    calendarDateT.setDateFormat("%d/%m/%Y");
    calendarDateT.hideTime();
    dhtmlXCalendarObject.prototype.langData["<?php echo $user_lang_iso; ?>"] = lang_calendar;
    calendarDateT.loadUserLanguage("<?php echo $user_lang_iso; ?>");
    specificPrice_filtertreeTB.setValue("date", specificPrice_filterdate);

    function displayFilters_specificPrice(callback)
    {
        specificPrice_filtertree.deleteChildItems(0);
        $.post("index.php?ajax=1&act=cat_win-specificprice_filter_get&id_lang="+SC_ID_LANG+"<?php if (SCMS)
 {
     echo '&id_shop="+specificPrice_shopselection+"';
 } ?>&"+new Date().getTime(),{selection: specificPrice_filterselection, dateT: specificPrice_filterdate},function(data){
            specificPrice_filtertree.parse(data,"xml");

            if (callback!='') eval(callback);
        });
        
    }
    <?php if (!SCMS){ ?>
        displayFilters_specificPrice();
    <?php } ?>

    specificPrice_filtertree.attachEvent("onClick",function(id){
        state=specificPrice_filtertree.isItemChecked(id);
        specificPrice_filtertree.setCheck(id,!state);
        specificPrice_filtertree.clearSelection();
        specificPrice_filterselection = specificPrice_filtertree.getAllChecked();
        displaySpecificPriceWin();
    });
    specificPrice_filtertree.attachEvent("onCheck",function(idfilter, state){
        specificPrice_filterselection = specificPrice_filtertree.getAllChecked();
        displaySpecificPriceWin();
    });
    
// SPECIFIC PRICE GRID
    var specificPrice_displaycombi = 0;

    var dhxlColSpecificPrice = dhxlSpecificPrice.cells('b');
    dhxlColSpecificPrice.hideHeader();

    var specificPrice_gridTB = dhxlColSpecificPrice.attachToolbar();
      specificPrice_gridTB.setIconset('awesome');
    specificPrice_gridTB.addButton('refresh',10,'','fa fa-sync green','fa fa-sync green');
    specificPrice_gridTB.setItemToolTip('refresh','<?php echo _l('Refresh grid', 1); ?>');
    <?php if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) { ?>
    specificPrice_gridTB.addButtonTwoState("combi_yn", 30, "", "fa fa-sitemap", "fa fa-sitemap");
    specificPrice_gridTB.setItemToolTip('combi_yn','<?php echo _l('Display specific prices for combinations', 1); ?>');
    <?php } ?>
    specificPrice_gridTB.addButton("selectall", 40, "", "fa fa-bolt yellow", "fad fa-bolt grey");
    specificPrice_gridTB.setItemToolTip('selectall','<?php echo _l('Select all', 1); ?>');
    specificPrice_gridTB.addButton('delete',50,'','fa fa-minus-circle red','fa fa-minus-circle red');
    specificPrice_gridTB.setItemToolTip('delete','<?php echo _l('Delete selected item', 1); ?>');
    specificPrice_gridTB.addButton('go_to_product',60,'','fa fa-search blue','fa fa-search blue');
    specificPrice_gridTB.setItemToolTip('go_to_product','<?php echo _l('Open in SC catalog', 1); ?>');
    specificPrice_gridTB.addButton("export_grid", 70, "", "fad fa-file-csv green", "fad fa-file-csv green");
    specificPrice_gridTB.setItemToolTip('export_grid','<?php echo _l('Export grid to clipboard in CSV format for MSExcel with tab delimiter.', 1); ?>');
    getTbSettingsButton(specificPrice_gridTB, {'grideditor':<?php echo (int)_r('MEN_TOO_GRIDSSETTINGS'); ?>,'settings':0}, 'win_speprice_', 80);
    specificPrice_gridTB.attachEvent("onClick", function(id) {
        if (id=='refresh')
        {
            displaySpecificPriceWin();
        }
        if (id=='win_speprice_grideditor')
        {
            openWinGridEditor('type_winspeprice');
        }
        if (id=='selectall')
        {
            specificPrice_grid.selectAll();
            getGridSpecificPriceWinStat();
        }
        if (id=='export_grid')
        {
            displayQuickExportWindow(specificPrice_grid, 1);
        }
        if (id=='delete')
        {
            var ids = specificPrice_grid.getSelectedRowId();
            if(ids!=undefined && ids!=null && ids!="" && ids!=0)
            {
                if (confirm('<?php echo _l('Permanently delete the selected special prices ?', 1); ?>'))
                {
                    ids=ids.split(',');
                    $.each(ids, function(num, rId){
                        var params = {
                            name: "cat_win-specificprice_update",
                            row: rId,
                            action: "delete",
                            params: {},
                            callback: "callbackWinSpecificPrice('"+rId+"','delete','"+rId+"');"
                        };                    
                        params.params = JSON.stringify(params.params);
                        addInUpdateQueue(params,specificPrice_grid);
                    });
                }
            }
        }
        if(id=="go_to_product")
        {
            var ids = specificPrice_grid.getSelectedRowId();
            if(ids!=undefined && ids!=null && ids!="" && ids!=0)
            {
                if(ids.search(",")>=0)
                {
                    alert('<?php echo _l('You must select only one line.', 1); ?>');
                }
                else
                {
                    wSpecificPrice.park();
                    
                    idxProduct=specificPrice_grid.getColIndexById('id_product');
                    
                    catselection = specificPrice_grid.getUserData(ids, "id_category_default");
                    lastProductSelID = specificPrice_grid.cells(ids,idxProduct).getValue();
                    <?php if (SCMS) { ?>
                    shopselection = specificPrice_shopselection;
                    displayShopTree('displayTree()');
                    <?php }
 else
 { ?>
                    displayTree('displayProducts();');
                    <?php } ?>
                }
            }
        }
    });
    specificPrice_gridTB.attachEvent("onStateChange", function(id,state){
            if (id=='combi_yn'){
                if (state) {
                    specificPrice_displaycombi=1;
                    specificPrice_grid._uisettings_name=specificPrice_grid._uisettings_prefix+"_with_combi";
                }else{
                    specificPrice_displaycombi=0;
                    specificPrice_grid._uisettings_name=specificPrice_grid._uisettings_prefix+"_without_combi";
                }
                displaySpecificPriceWin();
            }
        });


    specificPrice_grid = dhxlColSpecificPrice.attachGrid();
    specificPrice_grid.setDateFormat("%Y-%m-%d %H:%i:%s","%Y-%m-%d %H:%i:%s");
    specificPrice_grid.enableMultiselect(true);


    specificPrice_grid.attachEvent("onDhxCalendarCreated",function(calendar){
        calendar.setSensitiveRange("2012-01-01 00:00:00",null);
        dhtmlXCalendarObject.prototype.langData["<?php echo $user_lang_iso; ?>"] = lang_calendar;
        calendar.loadUserLanguage("<?php echo $user_lang_iso; ?>");
    });
    
    specificPrice_grid_sb=dhxlColSpecificPrice.attachStatusBar();

    specificPrice_grid.attachEvent("onFilterEnd", function(elements){
        getGridSpecificPriceWinStat();
    });
    specificPrice_grid.attachEvent("onSelectStateChanged", function(id){
        getGridSpecificPriceWinStat();
    });

    function onEditCellWinSpecificPrice(stage,rId,cInd,nValue,oValue)
    {
        if (stage==1 && this.editor && this.editor.obj) this.editor.obj.select();
        <?php sc_ext::readCustomWinSpePriceGridConfigXML('onEditCell'); ?>
        if (nValue!=oValue)
        {
            if(stage==2)
            {

                var idxPriceWTReduction = specificPrice_grid.getColIndexById('price_with_reduction_tax_excl');
                var idxPriceITReduction = specificPrice_grid.getColIndexById('price_with_reduction_tax_incl');
                var idxFixedPrice = specificPrice_grid.getColIndexById('price');
                var idxReductionPrice = specificPrice_grid.getColIndexById('reduction_price');
                var idxReductionPercent = specificPrice_grid.getColIndexById('reduction_percent');
                var idxMarginWTAmountAfter = specificPrice_grid.getColIndexById('margin_wt_amount_after_reduction');
                var idxMarginWTPercentAfter = specificPrice_grid.getColIndexById('margin_wt_percent_after_reduction');
                var idxMarginAfter = specificPrice_grid.getColIndexById('margin_after_reduction');

                var reduction_price = 0;
                var reduction_percent = 0;
                var reduction_fixed_price = "-1";
                var price_with_reduction_tax_excl = 0;
                var price_with_reduction_tax_incl = 0;
                var price = specificPrice_grid.getUserData(rId, "price")*1;
                
                var taxes = specificPrice_grid.getUserData(rId, "taxes")*1;
                var ecotaxe = specificPrice_grid.getUserData(rId, "ecotaxe")*1;

                let allowed_price_reinitialization_columns = [idxReductionPrice,idxReductionPercent,idxPriceWTReduction,idxPriceITReduction];
                
                if(cInd==idxReductionPrice || cInd==idxReductionPercent || cInd == idxFixedPrice) // modification prix fixe ou reduction prix/pourcent
                {
                    if(cInd==idxReductionPrice) {
                        reduction_price = nValue;
                    } else if(cInd==idxReductionPercent) {
                        reduction_percent = nValue;
                    } else if(cInd==idxFixedPrice) {
                        reduction_fixed_price = nValue;
                    }

                    if(cInd==idxReductionPrice)
                    {
                        price_with_reduction_tax_incl = (price*taxes)*1 - reduction_price*1;
                        price_with_reduction_tax_excl = price*1 - (reduction_price*1 / taxes*1);
                    }
                    else if(cInd==idxReductionPercent)
                    {
                        price_with_reduction_tax_incl = ((price*taxes)*1 * (1 - (reduction_percent/100))) + ecotaxe*1;
                        price_with_reduction_tax_excl = price*1 * (1 - (reduction_percent/100));
                    }else if (cInd == idxFixedPrice) {
                        if(reduction_fixed_price > -1){
                            price_with_reduction_tax_incl = (reduction_fixed_price*taxes)*1;
                            price_with_reduction_tax_excl = reduction_fixed_price*1;
                        } else {
                            price_with_reduction_tax_incl = (price*taxes)*1;
                            price_with_reduction_tax_excl = (price)*1;
                        }

                    }
                }
                else if(cInd==idxPriceWTReduction || cInd==idxPriceITReduction) // modification prix final HT ou TTC
                {
                    reduction_price = specificPrice_grid.cells(rId,idxReductionPrice).getValue()*1;
                    var reduction_price_old = reduction_price*1;
                    reduction_percent = specificPrice_grid.cells(rId,idxReductionPercent).getValue()*1;
                    var reduction_percent_old = reduction_percent*1;
                    reduction_fixed_price = specificPrice_grid.cells(rId,idxFixedPrice).getValue();
                    var reduction_fixed_price_old = reduction_fixed_price*1;

                    if(reduction_price>0)
                    {
                        if(cInd==idxPriceWTReduction)
                        {
                            price_with_reduction_tax_excl = nValue*1;            
                            //price_with_reduction_tax_excl = price*1 - (reduction_price*1 / taxes*1);
                            reduction_price = (price*1 - price_with_reduction_tax_excl) *  taxes*1;
                            price_with_reduction_tax_incl = (price*taxes)*1 - reduction_price*1 + ecotaxe*1;
                        }
                        else if(cInd==idxPriceITReduction)
                        {
                            price_with_reduction_tax_incl = nValue*1;
                            //price_with_reduction_tax_incl = (price*taxes)*1 + ecotaxe*1 - reduction_price*1;
                            reduction_price = ((price*taxes)*1 + ecotaxe*1) - price_with_reduction_tax_incl*1;
                            price_with_reduction_tax_excl = price*1 - (reduction_price*1 / taxes*1);
                        }

                        cInd = idxReductionPrice;
                        nValue = reduction_price;
                        oValue = reduction_price_old;
                    }
                    else if(reduction_percent>0)
                    {
                        if(cInd==idxPriceWTReduction)
                        {
                            price_with_reduction_tax_excl = nValue*1;            
                            // price_with_reduction_tax_excl = price*1 * (1 - (reduction_percent/100));
                            reduction_percent = 100 - price_with_reduction_tax_excl*100 / price;
                            price_with_reduction_tax_incl = ((price*taxes)*1 * (1 - (reduction_percent/100))) + ecotaxe*1;            
                        }
                        else if(cInd==idxPriceITReduction)
                        {
                            price_with_reduction_tax_incl = nValue*1;
                            //price_with_reduction_tax_incl = ((price*taxes)*1 * (1 - (reduction_percent/100))) + ecotaxe*1;
                            reduction_percent = 100 - ((price_with_reduction_tax_incl)*100 / (price*taxes)*1) - ecotaxe*1;
                            price_with_reduction_tax_excl = price*1 * (1 - (reduction_percent/100));
                        }

                        cInd = idxReductionPercent;
                        nValue = reduction_percent;
                        oValue = reduction_percent_old;
                    } else if(reduction_fixed_price>0){
                        if(cInd==idxPriceITReduction) {
                            price_with_reduction_tax_incl = nValue*1;
                            price_with_reduction_tax_excl = (nValue*taxes)*1 + ecotaxe*1;

                        } else if(cInd==idxPriceITReduction)
                        {
                            price_with_reduction_tax_excl = nValue*1;
                            price_with_reduction_tax_incl = (nValue*taxes)*1 + ecotaxe*1;
                        }
                        cInd = idxFixedPrice;
                        nValue = reduction_fixed_price;
                        oValue = reduction_fixed_price_old;
                    }

                }

                if (cInd === idxFixedPrice) {
                    for (const cId of [specificPrice_grid.getColIndexById('price_with_reduction_tax_excl'), specificPrice_grid.getColIndexById('price_with_reduction_tax_incl')]) {
                        if (cId !== undefined) {
                            let current_cell = specificPrice_grid.cells(rId, cId);
                            if (reduction_fixed_price > -1) {
                                current_cell.setBgColor('#D7D7D7');
                                current_cell.setDisabled(true);
                            } else {
                                current_cell.setBgColor('');
                                current_cell.setDisabled(false);
                            }
                        }
                    }
                }

                if(allowed_price_reinitialization_columns.includes(cInd)) {
                    specificPrice_grid.cells(rId, idxReductionPrice).setValue(priceFormat(reduction_price));
                    specificPrice_grid.cells(rId, idxReductionPercent).setValue(priceFormat(reduction_percent));
                    specificPrice_grid.cells(rId, idxMarginWTAmountAfter).setValue(priceFormat(0));
                    specificPrice_grid.cells(rId, idxMarginWTPercentAfter).setValue(priceFormat(0));
                    specificPrice_grid.cells(rId, idxMarginAfter).setValue(priceFormat(0));
                    specificPrice_grid.cells(rId, idxPriceWTReduction).setValue(priceFormat(price_with_reduction_tax_excl));
                    specificPrice_grid.cells(rId, idxPriceITReduction).setValue(priceFormat(price_with_reduction_tax_incl));
                    calculMarginSpecificPrice(rId, "margin_after_reduction");
                    calculMarginSpecificPrice(rId, "margin_wt_amount_after_reduction");
                    calculMarginSpecificPrice(rId, "margin_wt_percent_after_reduction");
                }

                <?php sc_ext::readCustomWinSpePriceGridConfigXML('onBeforeUpdate'); ?>
                var params = {
                    name: "cat_win-specificprice_update",
                    row: rId,
                    action: "update",
                    params: {},
                    callback: "callbackWinSpecificPrice('"+rId+"','update','"+rId+"');"
                };
                // COLUMN VALUES
                idxProduct=specificPrice_grid.getColIndexById('id_product');
                params.params["id_product"] = specificPrice_grid.cells(rId,idxProduct).getValue();
                <?php if (SCMS) { ?>
                params.params["id_shop_selected"] = specificPrice_shopselection;
                <?php } ?>
                params.params[specificPrice_grid.getColumnId(cInd)] = specificPrice_grid.cells(rId,cInd).getValue();
                // USER DATA
                params.params = JSON.stringify(params.params);
				addInUpdateQueue(params,specificPrice_grid);
            }
        
            return true;
        }
    }
    specificPrice_grid.attachEvent("onEditCell",onEditCellWinSpecificPrice);


    // CALLBACK FUNCTION
    function callbackWinSpecificPrice(sid,action,tid)
    {
        <?php sc_ext::readCustomWinSpePriceGridConfigXML('onAfterUpdate'); ?>
        if (action=='update')
        {
            specificPrice_grid.setRowTextNormal(sid);
        }
        if(action=='delete')
            specificPrice_grid.deleteRow(sid);
    }
    
    // UISettings
    specificPrice_grid._uisettings_prefix='specificprice_grid';
    specificPrice_grid._uisettings_name=specificPrice_grid._uisettings_prefix+"_without_combi";
    specificPrice_grid._uisettings_limited=true;
       specificPrice_grid._first_loading=1;
       
    // UISettings
    initGridUISettings(specificPrice_grid);

    // Context menu for grid
    clipboardType_WinSpecificprices = null;    
    specificprices_win_cmenu=new dhtmlXMenuObject();
    specificprices_win_cmenu.renderAsContextMenu();
    function onGridWinSpecificpricesContextButtonClick(itemId){
        tabId=specificPrice_grid.contextID.split('_');
        tabId=tabId[0];
        if (itemId=="copy"){
            if (lastColumnRightClicked_WinSpecificprices!=0)
            {
                clipboardValue_WinSpecificprices=specificPrice_grid.cells(tabId,lastColumnRightClicked_WinSpecificprices).getValue();
                specificprices_win_cmenu.setItemText('paste' , '<?php echo _l('Paste'); ?> '+specificPrice_grid.cells(tabId,lastColumnRightClicked_WinSpecificprices).getTitle());
                clipboardType_WinSpecificprices=lastColumnRightClicked_WinSpecificprices;
            }
        }
        if (itemId=="paste"){
            if (lastColumnRightClicked_WinSpecificprices!=0 && clipboardValue_WinSpecificprices!=null && clipboardType_WinSpecificprices==lastColumnRightClicked_WinSpecificprices)
            {
                selection=specificPrice_grid.getSelectedRowId();
                if (selection!='' && selection!=null)
                {
                    selArray=selection.split(',');
                    for(i=0 ; i < selArray.length ; i++)
                    {
                        var oValue = specificPrice_grid.cells(selArray[i],lastColumnRightClicked_WinSpecificprices).getValue();
                        specificPrice_grid.cells(selArray[i],lastColumnRightClicked_WinSpecificprices).setValue(clipboardValue_WinSpecificprices);
                        onEditCellWinSpecificPrice(2,selArray[i],lastColumnRightClicked_WinSpecificprices,clipboardValue_WinSpecificprices,oValue);
                    }
                }
            }
        }
    }
    specificprices_win_cmenu.attachEvent("onClick", onGridWinSpecificpricesContextButtonClick);
    var contextMenuXML='<menu absolutePosition="auto" mode="popup" maxItems="8"  globalCss="contextMenu" globalSecondCss="contextMenu" globalTextCss="contextMenuItem">'+
            '<item text="Object" id="object" enabled="false"/>'+
            '<item text="<?php echo _l('Copy'); ?>" id="copy"/>'+
            '<item text="<?php echo _l('Paste'); ?>" id="paste"/>'+
        '</menu>';
    specificprices_win_cmenu.loadStruct(contextMenuXML);
    specificPrice_grid.enableContextMenu(specificprices_win_cmenu);

    specificPrice_grid.attachEvent("onBeforeContextMenu", function(rowid,colidx,grid){
        var disableOnCols=new Array(
                specificPrice_grid.getColIndexById('id_specific_price'),
                specificPrice_grid.getColIndexById('id_product'),
                specificPrice_grid.getColIndexById('id_product_attribute'),
                specificPrice_grid.getColIndexById('reference'),
                specificPrice_grid.getColIndexById('name'),
                specificPrice_grid.getColIndexById('manufacturer'),
                specificPrice_grid.getColIndexById('supplier'),
                specificPrice_grid.getColIndexById('margin_wt_amount_after_reduction'),
                specificPrice_grid.getColIndexById('margin_wt_percent_after_reduction'),
                specificPrice_grid.getColIndexById('margin_after_reduction'),
                specificPrice_grid.getColIndexById('price_with_reduction_tax_excl'),
                specificPrice_grid.getColIndexById('price_with_reduction_tax_incl'),
                specificPrice_grid.getColIndexById('from_num'),
                specificPrice_grid.getColIndexById('to_num'),
                specificPrice_grid.getColIndexById('id_customer')
                );
        if (in_array(colidx,disableOnCols))
        {
            return false;
        }
        lastColumnRightClicked_WinSpecificprices=colidx;
        specificprices_win_cmenu.setItemText('object', '<?php echo _l('Specific price:'); ?> '+specificPrice_grid.cells(rowid,specificPrice_grid.getColIndexById('id_specific_price')).getTitle());
        if (lastColumnRightClicked_WinSpecificprices==clipboardType_WinSpecificprices)
        {
            specificprices_win_cmenu.setItemEnabled('paste');
        }else{
            specificprices_win_cmenu.setItemDisabled('paste');
        }
        return true;
    });
    
    function displaySpecificPriceWin()
    {
        if(specificPrice_catselection!=undefined && specificPrice_catselection!=null && specificPrice_catselection!="" && specificPrice_catselection!=0)
        {
            oldFiltersSpecificPriceWin=new Array();
            for(var i=0,l=specificPrice_grid.getColumnsNum();i<l;i++)
            {
                if (specificPrice_grid.getFilterElement(i)!=null && specificPrice_grid.getFilterElement(i).value!='')
                    oldFiltersSpecificPriceWin[specificPrice_grid.getColumnId(i)]=specificPrice_grid.getFilterElement(i).value;
            }
            
            specificPrice_grid.clearAll(true);
            $.post("index.php?ajax=1&act=cat_win-specificprice_get&id_lang="+SC_ID_LANG+"<?php echo SCMS ? '&id_shop="+specificPrice_shopselection+"' : ''; ?>&"+new Date().getTime(),{filters: specificPrice_filterselection, dateT: specificPrice_filterdate, category: specificPrice_catselection, combi: specificPrice_displaycombi, withSubCateg: specificPrice_withSubCateg},function(data){
                specificPrice_grid.parse(data);
                specificPrice_grid._rowsNum=specificPrice_grid.getRowsNum();

                marginMatrix_form = specificPrice_grid.getUserData("", "marginMatrix_form");
                specificPrice_grid.forEachRow(function(id){
                    calculMarginSpecificPrice(id, "margin_after_reduction");
                    calculMarginSpecificPrice(id, "margin_wt_amount_after_reduction");
                    calculMarginSpecificPrice(id, "margin_wt_percent_after_reduction");
                });

                for(var i=0;i<specificPrice_grid.getColumnsNum();i++)
                {
                    if (specificPrice_grid.getFilterElement(i)!=null && oldFiltersSpecificPriceWin[specificPrice_grid.getColumnId(i)]!=undefined)
                    {
                        specificPrice_grid.getFilterElement(i).value=oldFiltersSpecificPriceWin[specificPrice_grid.getColumnId(i)];
                    }
                }
                specificPrice_grid.filterByAll();

                let disabled_fields_by_id_speprice = specificPrice_grid.getUserData('','disabled_fields_by_id_speprice');
                if(!!disabled_fields_by_id_speprice) {
                    for(const rId of disabled_fields_by_id_speprice.split(',')) {
                        for(const cId of [specificPrice_grid.getColIndexById('price_with_reduction_tax_excl'),specificPrice_grid.getColIndexById('price_with_reduction_tax_incl')]) {
                            if(cId !== undefined) {
                                let current_cell = specificPrice_grid.cells(rId, cId);
                                current_cell.setBgColor('#D7D7D7');
                                current_cell.setDisabled(true);
                            }
                        }
                    }
                }

                getGridSpecificPriceWinStat();
                
                // UISettings
                loadGridUISettings(specificPrice_grid);
                specificPrice_grid._first_loading=0;
                
                <?php sc_ext::readCustomWinSpePriceGridConfigXML('afterGetRows'); ?>
            });
        }
    }

    function getGridSpecificPriceWinStat(){
        var filteredRows=specificPrice_grid.getRowsNum();
        var selectedRows=(specificPrice_grid.getSelectedRowId()?specificPrice_grid.getSelectedRowId().split(',').length:0);
          specificPrice_grid_sb.setText(specificPrice_grid._rowsNum+' '+(specificPrice_grid._rowsNum>1?'<?php echo _l('prices'); ?>':'<?php echo _l('price'); ?>')+" - <?php echo _l('Filter')._l(':'); ?> "+filteredRows+" - <?php echo _l('Selection')._l(':'); ?> "+selectedRows);
    }

    function calculMarginSpecificPrice(rId, type_col)
    {
        if(type_col=="margin_wt_amount_after_reduction")
        {
            if(specificPrice_grid.getColIndexById(type_col)!=undefined && specificPrice_grid.getUserData(rId, "wholesale_price")!=undefined && specificPrice_grid.getColIndexById('price_with_reduction_tax_excl')!=undefined)
            {
                idxPriceWithoutTaxes=specificPrice_grid.getColIndexById('price_with_reduction_tax_excl');
                idxMargin=specificPrice_grid.getColIndexById(type_col);
                var price = specificPrice_grid.cells(rId,idxPriceWithoutTaxes).getValue();
				if(price>0)
                {
                    var wholesale_price = specificPrice_grid.getUserData(rId, "wholesale_price");
                    var margin = price*1 - wholesale_price*1;
                    specificPrice_grid.cells(rId,idxMargin).setValue(priceFormat6Dec(margin));
                }
            }
        }
        else if(type_col=="margin_wt_percent_after_reduction")
        {
            if(specificPrice_grid.getColIndexById(type_col)!=undefined && specificPrice_grid.getColIndexById('price_with_reduction_tax_excl')!=undefined && specificPrice_grid.getColIndexById('margin_wt_amount_after_reduction')!=undefined)
            {
                idxPriceWithoutTaxes=specificPrice_grid.getColIndexById('price_with_reduction_tax_excl');
                idxMargin=specificPrice_grid.getColIndexById('margin_wt_amount_after_reduction');
                idxPercent=specificPrice_grid.getColIndexById(type_col);

                var price = specificPrice_grid.cells(rId,idxPriceWithoutTaxes).getValue();
                if(price>0)
                {
                    var margin = specificPrice_grid.cells(rId,idxMargin).getValue();
                    var percent = margin * 100 / price;
                    specificPrice_grid.cells(rId,idxPercent).setValue(Math.round(percent));
                }
            }
        }
        else if(type_col=="margin_after_reduction")
        {
            if(specificPrice_grid.getColIndexById(type_col)!=undefined && specificPrice_grid.getUserData(rId, "wholesale_price")!=undefined)
            {
                var formule = marginMatrix_form;
    
                idxPriceIncTaxes=specificPrice_grid.getColIndexById('price_with_reduction_tax_incl');
                idxPriceWithoutTaxes=specificPrice_grid.getColIndexById('price_with_reduction_tax_excl');
                
                idxMargin=specificPrice_grid.getColIndexById(type_col);
                
                var price = specificPrice_grid.cells(rId,idxPriceWithoutTaxes).getValue();
                if(price==null || price=="")
                    price = 0;
                
                formule = formule.replace(/{price}/g,price);
                                            
                var price_inc_tax = specificPrice_grid.cells(rId,idxPriceIncTaxes).getValue();
                if(price_inc_tax==null || price_inc_tax=="")
                    price_inc_tax = 0;    
                formule = formule.replace(/{price_inc_tax}/g,price_inc_tax);
                                
                var wholesale_price = specificPrice_grid.getUserData(rId, "wholesale_price");
                if(wholesale_price==null || wholesale_price=="")
                    wholesale_price = 0;    
                formule = formule.replace(/{wholesale_price}/g,wholesale_price);
                                
                if(wholesale_price>0 && price>0)
                    var margin = eval(formule);
                else
                    var margin = 0;
                specificPrice_grid.cells(rId,idxMargin).setValue(priceFormat6Dec(margin));
            }
        }
    }
<?php echo '</script>'; ?>
