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
$labels = $shippingboService->getStatusLabels('ps');
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
const wSboPanelPreviewPsData = wSboTabPreviewLayout.cells('a');
wSboPanelPreviewPsData.cell.classList.add('service', 'sbo_preview');


// TABBAR
var wSboPreviewTabConfig = {
    tabs: [
        {
            id: "error",
            text: '<span class="status status_error"><?php echo _l('%s '.$labels['error'], 1, [(int) $stats['ps'][$sboType]['error']]); ?></span>',
            active: <?php echo $previewTabId === 'error' ? 'true' : 'false'; ?>,
            enabled: true,
            close: false
        },
        {
            id: "awaiting",
            text: '<span class="status status_<?php echo $stats['ps'][$sboType]['awaiting'] === 0 ? 'success' : 'awaiting'; ?>"><?php echo _l('%s '.$labels['awaiting'], 1, [(int) $stats['ps'][$sboType]['awaiting']]); ?></span>',
            active: <?php echo $previewTabId === 'awaiting' ? 'true' : 'false'; ?>,
            enabled: true,
            close: false
        },
        {
            id: "locked",
            text: '<span class="status status_locked"><?php echo _l('%s '.$labels['locked'], 1, [(int) $stats['ps'][$sboType]['locked']]); ?></span>',
            active: <?php echo $previewTabId === 'locked' ? 'true' : 'false'; ?>,
            enabled: true,
            close: false
        }
    ]
};
wSboPanelPreviewPsData._tabbar = wSboPanelPreviewPsData.attachTabbar(wSboPreviewTabConfig);
wSboPanelPreviewPsData.setText('<span class="hdr_sbo">Shippingbo</span> ⇒ <span class="hdr_ps">PrestaShop</span> :'+sboType);
wSboPanelPreviewPsData.showHeader(true);

// TOOLBAR
wSboPanelPreviewPsData._toolbar = wSboPanelPreviewPsData.attachToolbar();
wSboPanelPreviewPsData._toolbar.setIconset('awesome');
wSboPanelPreviewPsData._toolbar.loadStruct([
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
        id: 'sbo_product_link',
        type: 'button',
        title: '<?php echo _l('Open selected product in Shippingbo', true); ?>',
        img: 'fa fa-external-link',
        imgdis: 'fa fa-external-link'
    }
])
wSboPanelPreviewPsData._refresh = function(){
    /* dashboard stats refresh */
    wSboTabClick(wSboTabBar.getActiveTab(), {"sync": false,'platform': 'ps', 'sboType': sboType,'id_shop':idShop, 'previewTabId': wSboPanelPreviewPsData._tabbar.getActiveTab() });
}

// ------------------------------------------------------------------------
// EVENTS
// ------------------------------------------------------------------------


/* affichage grid en fonction de l'onglet */
wSboPanelPreviewPsData._tabbar._displayGrid = function (tabId) {
    if (tabId == undefined) tabId='error';
    const currentTabContent = wSboPanelPreviewPsData._tabbar.tabs(tabId);
    let loadUrl = 'index.php?ajax=1&act=cat_win-sbo_preview_ps_get';
    if(currentTabContent.dataObj === undefined){
        currentTabContent._grid = currentTabContent.attachGrid({
            image_path:'lib/js/imgs/',
            multiselect: true,
            smart_rendering: true,
            header_menu: true
        });

        if(currentTabContent._grid.getSelectedRowId() === null){
            wSboPanelPreviewPsData._toolbar.disableItem('sbo_product_link');
        }
        /* activation/desactivation lient vers SBO dans TOOLBAR */
        currentTabContent.dataObj.attachEvent("onRowSelect",function(id){
            wSboPanelPreviewPsData._toolbar.enableItem('sbo_product_link');
        });

        ajaxPostCalling(currentTabContent, currentTabContent._grid, loadUrl, {

            ajax: 1,
            id_lang: SC_ID_LANG,
            sboType: sboType,
            previewTabId: tabId,
            id_shop: idShop,
            totalCount: stats['ps'][sboType][tabId]
        }, function (data) {
            if(data.state === false){
                dhtmlx.message({
                    text: data.extra.message,
                    type: 'error',
                    expire: 7000
                });
            } else {
                currentTabContent._grid.parse(data);
                //if(tabId === 'error'){
                //    let idxState = currentTabContent._grid.getColIndexById('statusLabel');
                //    currentTabContent._grid.groupBy(idxState);
                //}
            }
        });

    }
    currentTabContent.dataObj.attachEvent("onEditCell", function (stage,rId,cInd,nValue,oValue) {
        let idxIdSboProduct=this.getColIndexById('id_sbo');
        let idxIsLocked=this.getColIndexById('is_locked');
        if (stage==2 && nValue !== oValue)
        {
            let action = '';
            if (cInd === idxIsLocked) {
                action = 'is_locked';
            }
            $.post("index.php?ajax=1&act=cat_win-sbo_common_update&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),
                {
                    'id_sbo': this.cells(rId,idxIdSboProduct).getValue(),
                    'id_shop': idShop,
                    'value': nValue,
                    'action': action
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

                    if(typeof wSboPanelPreviewPsData._refresh === 'function')
                        wSboPanelPreviewPsData._refresh();

                });
        }
        return true;
    });


};

//TOOLBAR
/* actions toolbar */
wSboPanelPreviewPsData._toolbar.attachEvent("onClick", function (buttonId) {
    switch (buttonId) {
        case 'refresh':
            wSboPanelPreviewPsData._tabbar._displayGrid(wSboPanelPreviewPsData._tabbar.getActiveTab());
            break;
        case 'select_all':
            wSboPanelPreviewPsData._tabbar.tabs(wSboPanelPreviewPsData._tabbar.getActiveTab())._grid.selectAll();
            break;
        case 'lock':
            $.each(wSboPanelPreviewPsData._tabbar.tabs(wSboPanelPreviewPsData._tabbar.getActiveTab())._grid.getSelectedRowId().split(','), function(num, pId){
                var vars = {"property":"is_locked","value": 1,"rowId":pId};
                addMissingSboProductsInQueue(pId, "update", "", vars);
            });
            break;
        case 'unlock':
            $.each(wSboPanelPreviewPsData._tabbar.tabs(wSboPanelPreviewPsData._tabbar.getActiveTab())._grid.getSelectedRowId().split(','), function(num, pId){
                var vars = {"property":"is_locked", "value": 0,"rowId":pId};
                addMissingSboProductsInQueue(pId, "update", "", vars);
            });
            break;
        case 'sbo_product_link':
            let sboLinkPattern = '<?php echo Shippingbo::LINK_PRODUCT_URL_PATTERN; ?>';
            var grid = wSboPanelPreviewPsData._tabbar.tabs(wSboPanelPreviewPsData._tabbar.getActiveTab())._grid;
            let sboLink = sboLinkPattern.replace('{sbo_id_product}', grid.getUserData(grid.getSelectedRowId(), "id_sbo"));
            window.open(sboLink);
            break;
    }
});

//TABBAR
/* actions onglets */
wSboPanelPreviewPsData._tabbar.attachEvent("onTabClick", function (tabId) {
    wSboPanelPreviewPsData._tabbar._displayGrid(tabId);
    return false;
});

// ------------------------------------------------------------------------
// INIT
// ------------------------------------------------------------------------
wSboPanelPreviewPsData._tabbar._displayGrid(wSboPanelPreviewPsData._tabbar.getActiveTab());


// ------------------------------------------------------------------------
// FUNCTIONS
// ------------------------------------------------------------------------
function addMissingSboProductsInQueue(rId, action, cIn, vars)
{
    let currentTab = wSboPanelPreviewPsData._tabbar.tabs(wSboPanelPreviewPsData._tabbar.getActiveTab());
    currentTab.progressOn();
    let idxSboType = currentTab._grid.getColIndexById('type_sbo');
    var params = {
        name: "cat_win-sbo_common_update_queue",
        row: rId,
        id_shop: idShop,
        sboType: currentTab._grid.cells(rId, idxSboType).getValue(),
        action: "update",
        params: {},
        callback: "callbackPsMissingProducts('"+rId+"','update','"+rId+"');"
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
    addInUpdateQueue(params,wSboPanelPreviewPsData._tabbar.tabs(wSboPanelPreviewPsData._tabbar.getActiveTab()).dataObj);
}
// CALLBACK FUNCTION
function callbackPsMissingProducts(sid,action)
{
    if (action=='update')
    {
        var tabContent = wSboPanelPreviewPsData._tabbar.tabs(wSboPanelPreviewPsData._tabbar.getActiveTab());
        tabContent.dataObj.setRowTextNormal(sid);
        if(updateQueue.length === 0){
            wSboPanelPreviewPsData._refresh();
        }

    }
}
<?php echo '</script>'; ?>