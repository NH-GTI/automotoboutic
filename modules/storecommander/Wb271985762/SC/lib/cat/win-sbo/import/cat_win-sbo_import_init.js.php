<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
$shippingboService->switchToShopId((int) Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULT')));
$defaultDataImport = json_decode($shippingboService->getConfigValue('defaultDataImport'));

// TODO 2 : afficher progression import
// TODO 2 : afficher resultat import
$lastCollectDate = $shippingboService->getCollectProcess()->getStartDate();

$lastCollect = $lastCollectDate ? '('._l('last update', 1).' '.$shippingboService->getLocaleDate($lastCollectDate, 'yyyy-MM-dd H:mm:ss').')' : false;

?>

<?php echo '<script>'; ?>
const wSboPanelImportLayout = wSboTabDashboardPreview_cell.attachLayout("1C");
/**
 * Shippingbo
 */
const wSboImportLayout_cell = wSboPanelImportLayout.cells('a');
wSboImportLayout_cell.setText("<?php echo _l('Import into Prestashop', 1); ?>");
wSboImportLayout_cell.cell.classList.add('service');


$.ajax({
    'url': 'index.php?ajax=1&act=cat_win-sbo_forms_import_form.json&process&id_shop=<?php echo $shippingboService->getIdShop(); ?>',
    'type': 'GET',
    'success': function (data) {
        wSboTabSyncLayout_form = wSboImportLayout_cell.attachForm(JSON.parse(data.extra.message));
        wSboTabSyncLayout_form.attachEvent("onButtonClick", function (name, command) {
            if (name === "startImport") {
                wSboImportLayout_cell.progressOn();
                disableTabs();
                wSboTabSyncLayout_form.send("index.php?ajax=1&act=cat_win-sbo_process_import&id_shop=<?php echo $shippingboService->getIdShop(); ?>", "post",
                    function (xml) {
                        // refresh active tab grid
                        wSboImportLayout_cell.progressOff();
                        let response = JSON.parse(xml.xmlDoc.response);
                    let type = response.state === true ? 'sc_success' : 'error';
                    wSboImportLayout_cell.attachHTMLString('<p class="message success"><?php echo _l('Success', 1); ?></p>');
                        enableTabs();
                        wSboTabClick(wSboTabBar.getActiveTab(),{ 'id_shop':<?php echo $shippingboService->getIdShop(); ?> });
                        displayTree();
                    }
                )
            }
        });
    }
});

<?php echo '</script>'; ?>

