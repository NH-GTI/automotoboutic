<?php if (!defined('STORE_COMMANDER'))
{
    exit;
}
use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
$syncedShopIds = $shippingboService->getSyncedShopIds();
$idShop = SCMS?Tools::getValue('id_shop',$syncedShopIds[0]):\Configuration::get('PS_SHOP_DEFAULT');
$shippingboService->switchToShopId($idShop);

$sync = Tools::getValue('sync', 'false');

$activePreviewTabId = Tools::getValue('previewTabId', null);
$activePreviewPlatform = Tools::getValue('platform', null);
$activePreviewSboType = Tools::getValue('sboType', null);
?>

<?php echo '<script>'; ?>

const idShop = <?php echo $idShop; ?>;
const sboIdEmployee = <?php echo SC_Agent::getInstance()->id_employee; ?>;
const tabId = '<?php echo $activePreviewTabId; ?>';
const platform = '<?php echo $activePreviewPlatform; ?>';
const sboType = '<?php echo $activePreviewSboType; ?>';
const wSboTabDashboard = wSboTabBar.tabs('dashboard');
const wSboTabDashboardGlobalLayout = wSboTabDashboard.attachLayout("1C");
const wSboTabDashboardMainLayout = wSboTabDashboardGlobalLayout.cells('a').attachLayout("2U");

/**
 * Stats
 */
const wSboTabDashboardSync_cell = wSboTabDashboardMainLayout.cells('a');
wSboTabDashboardSync_cell.hideHeader();
wSboTabDashboardSync_cell.setWidth(880);
wSboTabDashboardSync_cell.setMinWidth(880);
wSboTabDashboardSync_cell.fixSize(true, true);
wSboTabDashboardSync_cell.cell.classList.add('service');
wSboTabDashboardSync_cell.attachURL('index.php?ajax=1&act=cat_win-sbo_dashboard_get&id_shop='+idShop+'&sync=<?php echo $sync; ?>&previewTabId=<?php echo $activePreviewTabId;?>', true);

/**
 * Preview
 */
var wSboTabDashboardPreview_cell = null;
wSboTabDashboardPreview_cell = wSboTabDashboardMainLayout.cells('b');
wSboTabDashboardPreview_cell.hideHeader();
wSboTabDashboardPreview_cell.attachHTMLString('<p class="message"><?php echo _l('No preview to display'); ?></p>');
if(tabId && platform && sboType && idShop){
    wSboPreviewOpenClick('grid', platform, sboType, tabId, idShop)
}


/* EVENTS */
wSboTabDashboardMainLayout.attachEvent("onContentLoaded", function () {

    // preview content toggles 'active' class on blocks
    const activePreviewPlatform = '<?php echo $activePreviewPlatform; ?>';
    const activePreviewSboType = '<?php echo $activePreviewSboType; ?>';
    if(activePreviewPlatform !== '' && activePreviewSboType !== '' && window.document.querySelector('.platform.'+activePreviewPlatform+' .'+activePreviewSboType)){
        window.document.querySelector('.platform.'+activePreviewPlatform+' .'+activePreviewSboType).classList.add('active');
    }

    // SYNC PROCESS
    let progressDomSelector = '.sbo-dashboard .process';
    let url = "index.php?ajax=1&act=cat_win-sbo_process&id_shop="+idShop;
    // getting info of last sse interruption

    // refresh button event
    let refresh = document.querySelector(progressDomSelector+' .refresh');
    if(refresh){
        refresh.addEventListener("click", function(){startAsyncProcess(url,progressDomSelector)});
    }
    // tab load
    if (new URLSearchParams(wSboTabDashboardSync_cell.conf.url_data.url).get('sync') === 'true') {
        startAsyncProcess(url,progressDomSelector);
    }

});


function startAsyncProcess(url,progressDomSelector) {
    //let progressElement = document.querySelector(progressDomSelector);
    let progressElement = new SboProgressBar(progressDomSelector);
    window.document.querySelector('.sbo-dashboard').classList.add('sync_in_progress') ;
    if(wSbo._evtSource){
        wSbo._evtSource.close();
    }
    wSbo._evtSource = new EventSource(url);

    // 1. écoute des événements envoyés sur le canal "sbo_sync"
    wSbo._evtSource.addEventListener("process_started", function (event) {
        // avancement progress bar
        let data = JSON.parse(event.data);
        let positive = Math.round(data.stepProgress);
        let negative = 100-positive;
        progressElement.positive.style.width = positive+'%';
        progressElement.positiveText.textContent = positive+'%';
        progressElement.negative.style.width =negative+'%';
        progressElement.negativeText.textContent = positive+'%';
        progressElement.text.textContent = data.message;
    });
    // 2. écoute événement sur le canal "done"
    wSbo._evtSource.addEventListener("done", function () {
        // init progress bar
        progressElement.classList.remove('in_progress');
        progressElement.classList.add('success');
        progressElement.stepName.textContent = '';
        progressElement.positive.style.width = '0';
        progressElement.positiveText.textContent = '0%';
        progressElement.negative.style.width ='100%';
        progressElement.negativeText.textContent = '0%';
        progressElement.text.textContent = "<?php echo _l('Synchronization complete', 1); ?>";
        // close eventSource
        wSbo._evtSource.close();
        // reactivate interactions on dashboard
        window.document.querySelector('.sbo-dashboard').classList.remove('sync_in_progress') ;
        if(wSboTabBar.getActiveTab() === 'dashboard'){
            wSboTabClick('dashboard', {'id_shop': idShop});
        }
    });
    // 3. ecoute evennement sur le canal error
    wSbo._evtSource.addEventListener("error", function (event) {
        // init progress bar
        progressElement.classList.remove('in_progress');
        progressElement.classList.add('error');
        progressElement.text.textContent = "<?php echo _l('Error', 1); ?>";
        // close eventSource
        wSbo._evtSource.close();
        // reactivate interactions on dashboard
        window.document.querySelector('.sbo-dashboard').classList.remove('sync_in_progress') ;
        dhtmlx.message({
            text: event.data,
            type: 'error',
            expire: -1
        });
    });

    // 4. ecoute evennement sur le canal locked
    wSbo._evtSource.addEventListener("locked", function (event) {
        // init progress bar
        progressElement.classList.remove('in_progress');
        let response = JSON.parse(event.data);
        let message = "<?php echo _l("Process already running");?>";
        if(response.canKill){
            message+=' <a id="kill_sbo_process" href="#"><?php echo _l('Kill process');?></a>';
        }
        progressElement.text.innerHTML = message;
        enableTerminateSboProcess(response);
        // close eventSource
        wSbo._evtSource.close();

        // reactivate interactions on dashboard
        window.document.querySelector('.sbo-dashboard').classList.remove('sync_in_progress') ;
        dhtmlx.message({
            text: response.message,
            type: 'warning',
            expire: -1
        });
    });

}


function SboProgressBar(wrapperSelector) {
    let progressElement = document.querySelector(wrapperSelector);
    progressElement.classList.remove('success');
    progressElement.classList.add('in_progress');
    if(progressElement.state !== true){ // check whether node has progress properties, so create it
        progressElement.icon =  progressElement.querySelector('.progress .icon');
        progressElement.stepName =  progressElement.querySelector('.progress .stepName');
        progressElement.text =  progressElement.querySelector('.progress .text');
        progressElement.positive =  progressElement.querySelector('.progress .positive');
        progressElement.positiveText =  progressElement.positive.querySelector('span');
        progressElement.negative =  progressElement.querySelector('.progress .negative');
        progressElement.negativeText =  progressElement.negative.querySelector('span');
        progressElement.state = true;
    }
    //progressElement.text.textContent = '';
    progressElement.text.textContent = progressElement.dataset.starttext;
    return progressElement;
}

function wSboPreviewOpenClick(previewId, platform, sboType, previewTabId, idShop) {
    let act = '';
    if (previewId === 'import') {
        act = 'cat_win-sbo_import_init';
    } else if (previewId === 'grid') {
        act = 'cat_win-sbo_preview_'+platform+'_init&tabId=dashboard&sboType='+sboType+'&platform='+platform+'&previewTabId='+previewTabId;
    }
    if (act !== '') {
        wSboTabDashboardPreview_cell.attachURL('index.php?act=' + act + '&id_lang=' + SC_ID_LANG + '&ajax=1&id_shop='+idShop, true);
    }
}

// DOWNLOAD LINKS
function wSboDownloadClick(downloadId, shopId) {
    let sboType = null;
    switch (downloadId) {
        case 'products':
            sboType = 'product';
            break;
        case 'batches':
            sboType = 'batch';
            break;
        case 'packs':
            sboType = 'pack';
            break;
        case 'pack_components':
            sboType = 'pack_component';
            break;
    }
    let params = new URLSearchParams({
        'act': 'cat_win-sbo_common_download',
        'id_lang': SC_ID_LANG,
        'ajax': 1,
        'sboType': sboType,
        'id_shop': shopId
    });
    window.open('index.php?'+params.toString());
}
function callTerminateProcess(response) {
    $.post("index.php?ajax=1&act=cat_win-sbo_process&"+new Date().getTime(),
        {'kill': true},
        function(event)
        {
            if(response.canKill){
                dhtmlx.message({
                    text: '<?php echo _l('Process killed'); ?>',
                });
                wSboTabClick('dashboard');
            } else {
                dhtmlx.message({
                    text: response.message,
                    type: 'error',
                    expire: -1
                });
            }

        });
}
function enableTerminateSboProcess(response){
    let killLink = document.querySelector('#kill_sbo_process');
    if(killLink){
        killLink.addEventListener('click', e => {
            e.preventDefault();
            callTerminateProcess(response);
        })
    }

}

<?php echo '</script>'; ?>