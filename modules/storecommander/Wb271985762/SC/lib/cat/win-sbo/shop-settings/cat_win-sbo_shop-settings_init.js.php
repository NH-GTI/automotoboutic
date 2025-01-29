<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
$shippingboService->switchToShopId(Tools::getValue('id_shop',Configuration::get('PS_SHOP_DEFAULT')));
if ($shippingboService->getScAgent()->getIdProfile() !== 1){
    exit;
}
if(!$shippingboService->getSboAccount()->getId()) {
    $formStructure = [
        [
            'type' => 'label',
            'name' => 'error_area',
            'hidden' => false,
            'label' => _l('No account found. %sPlease go back and create one%s.', null, ['<a href="javascript:wSboTabClick(\'initial-setup\');">', '</a>']),
            'className' => 'message error',
        ]
    ];
    echo '<script>';
?>

let wSboTabSettings = wSboTabBar.tabs('shop-settings');
let wSboTabSettingsLayout = wSboTabSettings.attachLayout('1C');
wSboTabSettingsLayout.cells('a').hideHeader();

let formStructure = <?php echo json_encode($formStructure); ?>;

let wSboTabSettingsForm = wSboTabSettingsLayout.cells('a').attachForm(formStructure)
<?php
    echo '</script>';
    exit;
}
?>

<?php echo '<script>'; ?>

const wSboTabSettings = wSboTabBar.tabs('shop-settings');
const wSboTabSettingsLayout = wSboTabSettings.attachLayout("2U");

const wSboTabMenuSettings_cell = wSboTabSettingsLayout.cells('a');
wSboTabMenuSettings_cell.setText("<?php echo _l('Shops', 1); ?>");
wSboTabMenuSettings_cell.setWidth(280);
wSboTabMenuSettings_cell.hideArrow();
wSboTabMenuSettings_cell.fixSize(true,true);
wSboTabMenuSettings_cell.cell.classList.add('service');

const SboTabMenuSettingsMenu = wSboTabMenuSettings_cell.attachList({
        drag:false,
        select:true,
        template: function(shop) {
            let validateConfig = Boolean(shop.services.shippingbo.validateConfig);
            let enabled = Boolean(shop.services.shippingbo.isEnabled);
            let shopReady = Boolean(validateConfig&&enabled);
            let shopText = '<?php echo _l('shop not ready'); ?>';
            let classlist = [];
            classlist.push('itemShop')
            if(shopReady) {
                classlist.push('configured')
                shopText = '<?php echo _l('shop ready'); ?>';
            } else {
                classlist.push('not_configured')
            }

            if(classlist.length > 0) {
                classlist = ' class="'+classlist.join(' ')+'"';
            }
            return `<div ${classlist}><span class="shopname">${shop.name}</span><span class="config_state">${shopText}</span></div>`
        },
        css:'service list',
        height:80
    });

SboTabMenuSettingsMenu._loadData = function(defaultShopSelection = 0)
{
    $.post('?ajax=1&act=cat_win-sbo_shop-settings_get',
        {
            defaultShopSelection: defaultShopSelection
        },
        function(response){
            if(response.state) {
                SboTabMenuSettingsMenu.clearAll();
                SboTabMenuSettingsMenu.parse(response.extra.shops, 'json');
                if(Boolean(response.extra.selection)) {
                    SboTabMenuSettingsMenu.select(response.extra.selection)
                }
            }
    })
}
SboTabMenuSettingsMenu._loadData(wSboTabGetIdShop());

wSboTabBar.tabs('shop-settings')._reloadListOption = function(idShop = 0)
{
    SboTabMenuSettingsMenu._loadData(idShop);
}

SboTabMenuSettingsMenu.attachEvent('onAfterSelect', function (id){
    displaySboShopSettingsForm(Number(id));
});

const wSboTabFormSettings_cell = wSboTabSettingsLayout.cells('b');
wSboTabFormSettings_cell.hideHeader();
wSboTabFormSettings_cell.cell.classList.add('service');


async function displaySboShopSettingsForm(id) {
    let selectedShop = SboTabMenuSettingsMenu.get(id);

    // FETCH CONTENT
    // TODO : dans fonction globale/proto
    let url = new URL('?ajax=1&act=cat_win-sbo_forms_shops_form.html', window.location.href);
    let params = new URLSearchParams({
        id_shop: selectedShop.id_shop,
        validateconfig: Boolean(selectedShop.services.shippingbo.validateConfig),
        enabled: Boolean(selectedShop.services.shippingbo.isEnabled)
    })
    params.forEach(function(value,key){
        url.searchParams.set(key,value);
    })
    let response = await fetch(url);
    data = await response.text();
    wSboTabFormSettings_cell.attachHTMLString(data);
}

<?php echo '</script>'; ?>