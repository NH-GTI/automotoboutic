<?php
if (!defined('STORE_COMMANDER')) { exit; }
?>
<script>

    // Create interface
    var dhxLayout = new dhtmlXLayoutObject(document.body, "2U");
    dhxLayout.cells('a').setText('<?php echo _l('Catalog', 1).' '.addslashes(Configuration::get('PS_SHOP_NAME')); ?>');
    dhxLayout.cells('b').setText('<?php echo _l('Properties', 1); ?>');
    var start_manufacturer_size_prop = getParamUISettings('start_manufacturer_size_prop');
    if(start_manufacturer_size_prop==null || start_manufacturer_size_prop<=0 || start_manufacturer_size_prop=="")
        start_manufacturer_size_prop = 400;
    dhxLayout.cells('b').setWidth(start_manufacturer_size_prop);
    dhxLayout.attachEvent("onPanelResizeFinish", function(){
        saveParamUISettings('start_manufacturer_size_prop', dhxLayout.cells('b').getWidth())
    });
    var dhxLayoutStatus = dhxLayout.attachStatusBar();
    layoutStatusText = '<div id="layoutstatusqueue" style="float: right; color: #ff0000; font-weight: bold;">'+loader_gif+' <span></span></div>'+"<?php echo SC_COPYRIGHT.' '.(SC_DEMO ? '- Demonstration' : '- '._l('License').' '.SCLIMREF.' - '.$SC_SHOP_MANUFACTURER_COUNT.' '._l('manufacturer')).' - Version '.SC_VERSION.(SC_BETA ? ' BETA' : '').(SC_GRIDSEDITOR_INSTALLED ? ' GE'.(SC_GRIDSEDITOR_PRO_INSTALLED ? 'P' : '') : '').' (PS '._PS_VERSION_.(defined('_PS_CACHE_ENABLED_') && _PS_CACHE_ENABLED_ ? ' (cache)' : '').' - PHP '.sc_phpversion().') '.$NOTEPAD_BUTTON.' <span id=\"layoutstatusloadingtime\"></span>'; ?>";
    dhxLayoutStatus.setText(layoutStatusText);

    <?php createMenu(); ?>
    manufacturerselection=0;
    shopselection=Cookies.get('sc_shop_selected')*1;
    shop_list=Cookies.get('sc_shop_list');
    last_manufacturerID=0;
    propertiesPanel='<?php echo _s('MAN_MANUF_PROP_GRID_DEFAULT'); ?>';
    tree_mode='single';
    displayManufacturersFrom='all';
    copytocateg=false;
    dragdropcache='';
    draggedManufacturer=0;
    clipboardValue=null;
    clipboardType=null;

    <?php //#####################################
    //############ Categories toolbar
    //#####################################
    ?>

    gridView='<?php echo _s('MAN_MANUF_GRID_DEFAULT'); ?>';
    oldGridView='';
    <?php
    echo SCI::getShopUrlArrayJs();
    if (SCMS)
    {
        ?>


    man = new dhtmlXLayoutObject(dhxLayout.cells("a"), "2U");
    <?php
    if (SCMS)
    {
        ?>
        man_firstcolcontent = man.cells("a").attachLayout("1C");
        man_storePanel = man_firstcolcontent.cells('a');

        man_manufacturerPanel = man.cells('b');


        <?php //#####################################
        //############ Boutiques Tree
        //#####################################
        ?>
        var has_shop_restrictions = false;

        man.cells("a").setText('<?php echo _l('Stores', 1); ?>');
        man.cells("a").showHeader();
        man_storePanel.hideHeader();
        var start_manufacturer_size_store = getParamUISettings('start_manufacturer_size_store');
        if(start_manufacturer_size_store==null || start_manufacturer_size_store<=0 || start_manufacturer_size_store=="")
            start_manufacturer_size_store = 250;
        man_storePanel.setWidth(start_manufacturer_size_store);
        man_firstcolcontent.attachEvent("onPanelResizeFinish", function(names){
            $.each(names, function(num, name){
                if(name=="a")
                    saveParamUISettings('start_manufacturer_size_store', man_storePanel.getWidth())
            });
        });
        man_shoptree=man_storePanel.attachTree();
        man_shoptree._name='shoptree';
        man_shoptree.autoScroll=false;
        man_shoptree.enableSmartXMLParsing(true);

        var man_ShoptreeTB = man_storePanel.attachToolbar();
          man_ShoptreeTB.setIconset('awesome');
        man_ShoptreeTB.addButton("help", 0, "", "fad fa-question-circle blue", "fad fa-question-circle blue");
        man_ShoptreeTB.setItemToolTip('help','<?php echo _l('Help'); ?>');
        man_ShoptreeTB.attachEvent("onClick", function(id) {
            if (id=='help')
            {
                var display = "";
                var update = "";
                if(shopselection>0)
                {
                    display = man_shoptree.getItemText(shopselection);
                }
                else if(shopselection==0)
                {
                    display = man_shoptree.getItemText("all");
                }

                var all_checked = Cookies.get('sc_shop_list').split(",");
                $.each(all_checked, function(index, id) {
                    if(id!="all" && id.search("G")<0)
                    {
                        if(update!="")
                            update += ", ";
                        update += man_shoptree.getItemText(id);
                    }
                });

                var msg = '<strong><?php echo addslashes(_l('Display:')); ?></strong> '+display+'<br/><br/><strong><?php echo addslashes(_l('Update:')); ?></strong> '+update;
                dhtmlx.message({text:msg,type:'info',expire:10000});
            }
        });


        displayShopTree();

        function checkWhenSelection(idshop)
        {
            let allShops_item_found = man_shoptree.getIndexById('all');
            if(allShops_item_found !== null) {
                var children = man_shoptree.getAllSubItems("all").split(",");
            } else {
                var children = man_shoptree.getAllChildless().split(",");
            }
            if ((idshop == 'all' || idshop==0) && has_shop_restrictions==0)
            {
                man_shoptree.setCheck("all",1);
                $.each(children, function(index, id) {
                    man_shoptree.setCheck(id,1);
                    man_shoptree.disableCheckbox(id,1);
                });
            }
            else
            {
                $.each(children, function(index, id) {
                    man_shoptree.disableCheckbox(id,0);
                });
                if(idshop>0)
                {
                    man_shoptree.setCheck(idshop,1);
                    man_shoptree.disableCheckbox(idshop,1);
                }
            }
        }
        function deSelectParents(idshop)
        {
            if(man_shoptree.getParentId(idshop)!="")
            {
                var parent_id = man_shoptree.getParentId(idshop);
                man_shoptree.setCheck(parent_id,0);

                deSelectParents(parent_id);
            }
        }
        function saveCheckSelection()
        {
            var checked = man_shoptree.getAllChecked();
            if(shopselection=="all" || shopselection=="0")
            {
                let allShops_item_found = man_shoptree.getIndexById('all');
                if(allShops_item_found !== null) {
                    checked = man_shoptree.getAllSubItems("all");
                } else {
                    checked = man_shoptree.getAllChildless();
                }
            }
            var all_checked = checked.split(",");
            var cookie_checked = "";
            $.each(all_checked, function(index, id) {
                if(id!="all" && id.search("G")<0)
                {
                    if(cookie_checked!="")
                        cookie_checked += ",";
                    cookie_checked += id;
                }
            });
            if(shopselection!=undefined && shopselection!="")
            {
                if(cookie_checked!="")
                    cookie_checked += ",";
                cookie_checked += shopselection;
            }
            Cookies.set('sc_shop_list',cookie_checked, defaultCookieOptions);
        }
        function displayShopTree(callback) {
            man_shoptree.deleteChildItems(0);
            man_shoptree.load("index.php?ajax=1&act=man_shop_get&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),function(){
                has_shop_restrictions = man_shoptree.getUserData(0, "has_shop_restrictions");

                if(shopselection!=null && shopselection!=undefined)
                    checkWhenSelection(shopselection);
                if(shop_list!=null && shop_list!="")
                {
                    var selected = shop_list.split(",");
                    $.each(selected, function(index, id) {
                        man_shoptree.setCheck(id,1);
                    });
                }
                if (shopselection!=null && shopselection!=undefined && shopselection!=0)
                {
                    man_shoptree.openItem(shopselection);
                    man_shoptree.selectItem(shopselection,true);
                }

                if(has_shop_restrictions)
                {
                    selected = man_shoptree.getSelectedItemId();
                    if(selected==undefined || selected==null || selected=="")
                    {
                        var all = man_shoptree.getAllSubItems(0);
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
                            shopselection = id_to_select;
                            man_shoptree.openItem(shopselection);
                            man_shoptree.selectItem(shopselection,true);
                            Cookies.set('sc_shop_selected',shopselection, defaultCookieOptions);
                        }
                    }
                }

                if (callback!='') eval(callback);
                man_shoptree.openAllItems(0);
            });
        }
        man_shoptree.attachEvent("onClick",onClickShopTree);
        function onClickShopTree(idshop, param,callback){
            if (idshop[0]=='G'){
                man_shoptree.clearSelection();
                man_shoptree.selectItem(shopselection,false);
                return false;
            }
            if (idshop == 'all'){
                idshop = 0;
            }
            checkWhenSelection(idshop);
            if (idshop != shopselection)
            {
                if(shopselection!=0 && idshop!=0 && idshop[0]!='G')
                    man_shoptree.setCheck(shopselection,0);
                else if(shopselection==0 && idshop!=0 && idshop[0]!='G')
                {
                    if(has_shop_restrictions==0)
                    {
                        var children = man_shoptree.getAllSubItems("all").split(",");
                        man_shoptree.setCheck("all",0);
                        $.each(children, function(index, id) {
                            if(id!=idshop)
                                man_shoptree.setCheck(id,0);
                        });
                    }
                    else
                    {
                        var children = man_shoptree.getAllSubItems(0).split(",");
                        man_shoptree.setCheck("all",0);
                        $.each(children, function(index, id) {
                            if(id!=idshop)
                                man_shoptree.setCheck(id,0);
                        });
                    }

                }
                shopselection = idshop;
                Cookies.set('sc_shop_selected',shopselection, defaultCookieOptions);
            }
            else
            {
                var callback_refresh = "";
                if(callback!=undefined && callback!=null && callback!="")
                    callback_refresh = callback_refresh + callback;
            }
            saveCheckSelection();
        }

        man_shoptree.attachEvent("onCheck",function(idshop, state){
            if(idshop=="all")
            {
                var children = man_shoptree.getAllSubItems("all").split(",");
                $.each(children, function(index, id) {
                    man_shoptree.setCheck(id,state);
                });
            }
            else if(idshop.search("G")>=0)
            {
                var children = man_shoptree.getAllSubItems(idshop).split(",");
                $.each(children, function(index, id) {
                    man_shoptree.setCheck(id,state);
                });
            }
            else
            {
                deSelectParents(idshop);
            }
            saveCheckSelection();
        });

        <?php //#####################################
        //############ Context menu
        //#####################################
        ?>
        var drag_disabled_for_sort = true; // for disable the drag  in tree after  sort and  "sort and save"
        man_shop_cmenu_tree=new dhtmlXMenuObject();
        man_shop_cmenu_tree.renderAsContextMenu();
        function onTreeContextButtonClickForShop(itemId){
            if (itemId=="goshop"){
                tabId=man_shoptree.contextID;
                var manCatActive=(man_shoptree.getItemImage(tabId,0,false)=='catalog.png'?0:1);
                if (manCatActive==1){
                    return false;
                }
                <?php
                if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
                {
                    if (SCMS)
                    {
                        ?>
                        if(shopUrls[tabId] != undefined && shopUrls[tabId] != "" && shopUrls[tabId] != null)
                            window.open(shopUrls[tabId]);
                    <?php
                    }
                    else
                    { ?>
                        window.open('<?php echo SC_PS_PATH_REL; ?>');
                    <?php }
                }
        else
        {
            ?>
                    window.open('<?php echo SC_PS_PATH_REL; ?>');
                <?php
        } ?>
            }
        }
        man_shop_cmenu_tree.attachEvent("onClick", onTreeContextButtonClickForShop);

        var contextMenuXML='<menu absolutePosition="auto" mode="popup" maxItems="8"  globalCss="contextMenu" globalSecondCss="contextMenu" globalTextCss="contextMenuItem">'+
            '<item text="Object" id="object" enabled="false"/>'+
            '<item text="<?php echo _l('See shop'); ?>" id="goshop"/>'+
            '</menu>';
        man_shop_cmenu_tree.loadStruct(contextMenuXML);
        man_shoptree.enableContextMenu(man_shop_cmenu_tree);

        man_shoptree.attachEvent("onBeforeContextMenu", function(itemId){

            var display_id = itemId;
            var display_text = '<?php echo _l('Shop:'); ?> ';
            if(itemId=="all")
            {
                return false;
            }
            else if(itemId.search("G")>=0)
            {
                var display_id = itemId.replace("G","");
                var display_text = '';
            }

            man_shop_cmenu_tree.setItemText('object', 'ID'+display_id+': '+display_text+man_shoptree.getItemText(itemId));

            <?php if (SCMS) { ?>
            if(shopUrls[itemId] != undefined && shopUrls[itemId] != "" && shopUrls[itemId] != null)
            {
                man_shop_cmenu_tree.setItemEnabled('goshop');
            }else{
                man_shop_cmenu_tree.setItemDisabled('goshop');
            }
            <?php } ?>

            return true;
        });
        <?php
    }
        else
        {
            ?>
        man = new dhtmlXLayoutObject(dhxLayout.cells("a"), "2U");
        man_firstcolcontent = man_categoryPanel = man.cells('a');
        man_manufacturerPanel = man.cells('b');
        <?php
        } ?>
    <?php
    }
    else
    { ?>
    man = new dhtmlXLayoutObject(dhxLayout.cells("a"), "1C");
    man_manufacturerPanel = man.cells('a');
    <?php
    }
    ?>

</script>
