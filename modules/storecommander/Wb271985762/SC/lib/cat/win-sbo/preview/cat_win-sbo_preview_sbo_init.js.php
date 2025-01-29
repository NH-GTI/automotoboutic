<?php
use Sc\Service\Shippingbo\Shippingbo;

if (!defined('STORE_COMMANDER'))
{
    exit;
}

$previewTabId = Tools::getValue('previewTabId', null);
$sboType = Tools::getValue('sboType', null);
$shippingboService = Shippingbo::getInstance();
$idShop = (int) Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULT'));
$shippingboService->switchToShopId($idShop);
$labels = $shippingboService->getStatusLabels('sbo');
$stats = $shippingboService->getStatsRepository()->getAll($shippingboService->getConfigValue('id_sbo_account'));

if (!$previewTabId or $previewTabId === 'undefined')
{
    $previewTabId = ($stats['sbo'][$sboType]['error'] > 0) ? 'error' : 'awaiting';
}

?>

<?php echo '<script>'; ?>
const idShop = <?php echo $idShop ?>;
const stats = JSON.parse('<?php echo json_encode($stats); ?>');
const sboType = '<?php echo $sboType; ?>';
const resultsPerPage = '<?php echo $shippingboService->getGridResultsPerPage(); ?>';
const previewTabId = '<?php echo $previewTabId; ?>';

// ------------------------------------------------------------------------
// LAYOUT
// ------------------------------------------------------------------------
const wSboTabPreviewLayout = wSboTabDashboardPreview_cell.attachLayout("1C");
const wSboPanelPreviewSboData = wSboTabPreviewLayout.cells('a');
wSboPanelPreviewSboData.cell.classList.add('service', 'sbo_preview');


// TABBAR
var wSboPreviewTabConfig = {
    tabs: [
        {
            id: "error",
            text: '<span class="status status_error"><?php echo _l('%s '.$labels['error'], 1, [(int) $stats['sbo'][$sboType]['error']]); ?></span>',
            active: <?php echo $previewTabId === 'error' ? 'true' : 'false'; ?>,
            enabled: true,
            close: false
        },
        {
            id: "awaiting",
            text: '<span class="status status_<?php echo $stats['sbo'][$sboType]['awaiting'] === 0 ? 'success' : 'awaiting'; ?>"><?php echo _l('%s '.$labels['awaiting'], 1, [(int) $stats['sbo'][$sboType]['awaiting']]); ?></span>',
            active: <?php echo $previewTabId === 'awaiting' ? 'true' : 'false'; ?>,
            enabled: true,
            close: false
        },
        {
            id: "locked",
            text: '<span class="status status_locked"><?php echo _l('%s '.$labels['locked'], 1, [(int) $stats['sbo'][$sboType]['locked']]); ?></span>',
            active: <?php echo $previewTabId === 'locked' ? 'true' : 'false'; ?>,
            enabled: true,
            close: false
        }
    ]
};
wSboPanelPreviewSboData._tabbar = wSboPanelPreviewSboData.attachTabbar(wSboPreviewTabConfig);
wSboPanelPreviewSboData.setText('<span class="hdr_ps">PrestaShop</span> ⇒ <span class="hdr_sbo">Shippingbo</span> : <?php echo ucfirst(_l($sboType)); ?>');
wSboPanelPreviewSboData.showHeader(true);

// TOOLBAR
wSboPanelPreviewSboData._toolbar = wSboPanelPreviewSboData.attachToolbar();
wSboPanelPreviewSboData._toolbar.setIconset('awesome');
wSboPanelPreviewSboData._toolbar.loadStruct([
    {
        id: 'refresh',
        type: 'button',
        title: '<?php echo _l('Refresh grid', true); ?>',
        img: 'fa fa-sync green',
        imgdis: 'fa fa-sync green'
    },
    {
        id: 'select_all',
        type: 'button',
        title: '<?php echo _l('Select all', true); ?>',
        img: 'fa fa-bolt',
        imgdis: 'fa fa-bolt'
    },
    {
        id: 'lock',
        type: 'button',
        title: '<?php echo _l('Disable Shippingbo Synchronization for selection', true); ?>',
        img: 'fad fa-lock-alt',
        imgdis: 'fad fa-lock-alt'
    },
    {
        id: 'unlock',
        type: 'button',
        title: '<?php echo _l('Enable Shippingbo Synchronization for selection', true); ?>',
        img: 'fad fa-lock-open-alt',
        imgdis: 'fad fa-lock-open-alt'
    },
    {
        id: 'export_to_segment',
        type: 'button',
        title: '<?php echo _l('Add the result lines to a new manual segment', true); ?>',
        img: 'fad fa-chart-pie blue',
        imgdis: 'fad fa-chart-pie blue',
        disabled: true
    }
])
wSboPanelPreviewSboData._refresh = function(){
     /* dashboard stats refresh */
    wSboTabClick(wSboTabBar.getActiveTab(), {"sync": false,'platform': 'sbo', 'sboType': sboType,'id_shop':idShop, 'previewTabId': wSboPanelPreviewSboData._tabbar.getActiveTab() });
}

// ------------------------------------------------------------------------
// EVENTS
// ------------------------------------------------------------------------


/* affichage grid en fonction de l'onglet */
wSboPanelPreviewSboData._tabbar._displayGrid = function (tabId) {
    if (tabId == undefined) tabId='error';
    const currentTabContent = wSboPanelPreviewSboData._tabbar.tabs(tabId);
    let loadUrl = 'index.php?ajax=1&act=cat_win-sbo_preview_sbo_get';
    if(currentTabContent.dataObj === undefined){
        currentTabContent._grid = currentTabContent.attachGrid({
            image_path:'lib/js/imgs/',
            multiselect: true,
            smart_rendering: true,
            header_menu: true
        });
        currentTabContent._grid.enableExcelKeyMap(true);

        ajaxPostCalling(currentTabContent, currentTabContent._grid, loadUrl, {
            ajax: 1,
            id_lang: SC_ID_LANG,
            sboType: sboType,
            previewTabId: tabId,
            id_shop: <?php echo $shippingboService->getIdShop(); ?>,
            totalCount: stats['sbo'][sboType][tabId]
        }, function (data) {
            if(data.state === false){
                dhtmlx.message({
                    text: data.extra.message,
                    type: 'error',
                    expire: 7000
                });
            } else {
                currentTabContent._grid.parse(data);
                if(tabId === 'error' &&  wSboPanelPreviewSboData._tabbar.tabs(tabId)._grid.getRowsNum() > 0) {
                    //let idxState = currentTabContent._grid.getColIndexById('statusLabel');
                    //currentTabContent._grid.groupBy(idxState);
                    wSboPanelPreviewSboData._toolbar.enableItem('export_to_segment');
                } else {
                    wSboPanelPreviewSboData._toolbar.disableItem('export_to_segment');
                }
            }
        });
    }
    currentTabContent.dataObj.attachEvent("onEditCell", function (stage,rId,cInd,nValue,oValue) {
        let idxIdProduct=this.getColIndexById('id_product');
        let idxIdProductAttribute=this.getColIndexById('id_product_attribute');
        let idxIsLocked=this.getColIndexById('is_locked');
        let idxActive=this.getColIndexById('active');
        let idxRef=this.getColIndexById('reference');
        if (stage==2 && nValue !== oValue)
        {
            let action = '';
            if (cInd === idxIsLocked) {
                action = 'is_locked';
            }
            if (cInd === idxRef) {
                action = 'reference';
            }
            if (cInd === idxActive) {
                action = 'active';
            }
            $.post("index.php?ajax=1&act=cat_win-sbo_common_update&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),
                {
                    'id_product': this.cells(rId,idxIdProduct).getValue(),
                    'id_product_attribute': this.cells(rId,idxIdProductAttribute).getValue(),
                    'value': nValue,
                    'action': action,
                    'id_shop': idShop
                },
                function(response)
                {
                    if(response.state === false){
                        dhtmlx.message({
                            text: response.extra.message,
                            type: 'error',
                            expire: 7000
                        });
                    }
                    if(typeof wSboPanelPreviewSboData._refresh === 'function')
                        wSboPanelPreviewSboData._refresh();

                });
        }
        return true;
    });

};

//TOOLBAR
/* actions toolbar */
wSboPanelPreviewSboData._toolbar.attachEvent("onClick", function (buttonId) {
    switch (buttonId) {
        case 'refresh':
            wSboPanelPreviewSboData._tabbar._displayGrid(wSboPanelPreviewSboData._tabbar.getActiveTab());
            break;
        case 'select_all':
            wSboPanelPreviewSboData._tabbar.tabs(wSboPanelPreviewSboData._tabbar.getActiveTab())._grid.selectAll();
            break;
        case 'lock':
            $.each(wSboPanelPreviewSboData._tabbar.tabs(wSboPanelPreviewSboData._tabbar.getActiveTab())._grid.getSelectedRowId().split(','), function(num, pId){
                var vars = {"property":"is_locked","value": 1,"rowId":pId};
                addMissingSboProductsInQueue("", "update", "", vars);
            });
            break;
        case 'unlock':
            $.each(wSboPanelPreviewSboData._tabbar.tabs(wSboPanelPreviewSboData._tabbar.getActiveTab())._grid.getSelectedRowId().split(','), function(num, pId){
                var vars = {"property":"is_locked", "value": 0,"rowId":pId};
                addMissingSboProductsInQueue("", "update", "", vars);
            });
            break;
        case 'export_to_segment':
            let segment_name = prompt('<?php echo _l('Name of your new segment', 1); ?>', '<?php echo _l('%s with error', 1, [_l($sboType)]).' '. date('Ymd'); ?>');
            if(!Boolean(segment_name))
            {
                break;
            }

            let allRows = wSboPanelPreviewSboData._tabbar.tabs(wSboPanelPreviewSboData._tabbar.getActiveTab())._grid.getAllRowIds().split(',');
            let finalIdLIst = [];

            for (const rowId of allRows) {
                finalIdLIst.push(wSboPanelPreviewSboData._tabbar.tabs(wSboPanelPreviewSboData._tabbar.getActiveTab())._grid.getUserData(rowId, 'id_product'));
            }
            finalIdLIst = [...new Set(finalIdLIst)]; // delete duplicate entry
            if (finalIdLIst.length <= 0) {
                dhtmlx.message({text: "<?php echo _l('No data to save'); ?>", type: 'error', expire: 5000});
                break;
            }

            $.post('index.php?ajax=1&act=cat_win-sbo_common_update&id_lang='+SC_ID_LANG,
            {
                action: buttonId,
                id_shop: idShop,
                segment_name: segment_name,
                segment_item_list: finalIdLIst.join(',')
            },
            function(response)
            {
                let type = Boolean(response.state) ? 'success' :  'error';
                let callback = response.extra.callback;
                if(callback && callback.functionName){
                    if(typeof callback.functionName === 'function' || typeof callback.functionName === 'string') {
                        executeFunctionByName(callback.functionName, window, callback.params);
                    }
                }
                dhtmlx.message({
                    text: response.extra.message,
                    type: type,
                    expire: 7000
                });
            });
            break;
    }
});

//TABBAR
/* actions onglets */
wSboPanelPreviewSboData._tabbar.attachEvent("onTabClick", function (tabId) {
    wSboPanelPreviewSboData._tabbar._displayGrid(tabId);
    return false;
});


// ------------------------------------------------------------------------
// INIT
// ------------------------------------------------------------------------
wSboPanelPreviewSboData._tabbar._displayGrid(wSboPanelPreviewSboData._tabbar.getActiveTab());


// ------------------------------------------------------------------------
// FUNCTIONS
// ------------------------------------------------------------------------
function addMissingSboProductsInQueue(rId, action, cIn, vars)
{
    wSboPanelPreviewSboData._tabbar.tabs(wSboPanelPreviewSboData._tabbar.getActiveTab()).progressOn();
    var params = {
        name: "cat_win-sbo_common_update_queue",
        row: rId,
        id_shop: idShop,
        sboType: sboType,
        action: "update",
        params: {},
        callback: "callbackSboMissingProducts('"+rId+"','update','"+rId+"');"
    };
    // COLUMN VALUES
    params.params["id_lang"] = SC_ID_LANG;
    if(vars!=undefined && vars!=null && vars!="" && vars!=0)
    {
        $.each(vars, function(key, value){
            params.params[key] = value;
        });
    }
    // USER DATA
    params.params = JSON.stringify(params.params);
    addInUpdateQueue(params,wSboPanelPreviewSboData._tabbar.tabs(wSboPanelPreviewSboData._tabbar.getActiveTab()).dataObj);
}
//
// CALLBACK FUNCTION
function callbackSboMissingProducts(sid,action)
{
    if (action=='update')
    {
        var tabContent = wSboPanelPreviewSboData._tabbar.tabs(wSboPanelPreviewSboData._tabbar.getActiveTab());
        tabContent.dataObj.setRowTextNormal(sid);
        if(updateQueue.length === 0){
            wSboPanelPreviewSboData._refresh();
        }

    }
}


<?php echo '</script>'; ?>