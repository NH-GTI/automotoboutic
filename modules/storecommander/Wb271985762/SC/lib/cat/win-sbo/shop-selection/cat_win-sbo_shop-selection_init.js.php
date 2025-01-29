<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
if ($shippingboService->getScAgent()->getIdProfile() !== 1){
    exit;
}

echo '<script>';
?>

const sboAccounts = <?php echo json_encode($shippingboService->getAllSboAccounts()); ?>;
const wSboTabShopSelection = wSboTabBar.tabs('shop-selection');
let wSboTabShopSelectionLayout = wSboTabShopSelection.attachLayout('1C');

const wSboTabShopSelectionMainCell = wSboTabShopSelectionLayout.cells('a');
wSboTabShopSelectionMainCell.hideHeader()

$.ajax({
    url: 'index.php?ajax=1&act=cat_win-sbo_forms_shop_selection_form.json',
    type: 'GET',
    success: function (data) {
        const wSboShopSelectionForm = wSboTabShopSelectionMainCell.attachForm(JSON.parse(data.extra.message));
        wSboShopSelectionForm.attachEvent('onButtonClick', function (name, command) {
            if (name === 'save_selection_shop') {
                wSboTabShopSelectionMainCell.progressOn();
                disableTabs();
                wSboShopSelectionForm.send('index.php?ajax=1&act=cat_win-sbo_shop-selection_update', 'post',
                    function (xml) {
                        // refresh active tab grid
                        wSboTabShopSelectionMainCell.progressOff();
                        enableTabs();
                        let response = JSON.parse(xml.xmlDoc.response);
                        if(response.state) {
                            wSboShopSelectionForm.hideItem('error_area');
                            if(Boolean(response.extra.firstShopSelected)) {
                                wSboTabSetIdShop(response.extra.firstShopSelected)
                            }
                            wSboTabClick('shop-settings')
                        } else {
                            wSboShopSelectionForm.setItemLabel('error_area', response.extra.message);
                            wSboShopSelectionForm.showItem('error_area');
                        }
                    }
                )
            }
        });
    }
});

<?php echo '</script>'; ?>