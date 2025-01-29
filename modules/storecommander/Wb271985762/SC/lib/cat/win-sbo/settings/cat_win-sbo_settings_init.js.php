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
$defaultIdShop = Configuration::get('PS_SHOP_DEFAULT');

?>

<?php echo '<script>'; ?>

const ps_default_shop = '<?php echo $defaultIdShop;?>';
const wSboTabSettings = wSboTabBar.tabs('settings');
const wSboTabSettingsLayout = wSboTabSettings.attachLayout("2U");


const wSboTabMenuSettings_cell = wSboTabSettingsLayout.cells('a');
wSboTabMenuSettings_cell.setText("<?php echo _l('Settings', 1); ?>");
wSboTabMenuSettings_cell.setWidth(280);
wSboTabMenuSettings_cell.hideHeader();
wSboTabMenuSettings_cell.fixSize(true,true);
wSboTabMenuSettings_cell.collapsable = false;
wSboTabMenuSettings_cell.cell.classList.add('service');

const sboAdvancedParamsMenu = wSboTabMenuSettings_cell.attachList({
        drag:false,
        select:true,
        template: function(account) {
            return `<div><span class="accountname">${account.name}</span></div>`
        },
        css:'service list',
        height:80
    });
const optionList = [
    {
        id: 'advanced_db',
        name: '<?php echo _l('Database', 1); ?>'
    },
    {
        id: 'advanced_logs',
        name: '<?php echo _l('Debug', 1); ?>'
    }
];
sboAdvancedParamsMenu.parse(optionList, 'json');


sboAdvancedParamsMenu.select(optionList[0]['id']);
displaySboSettingsForm(optionList[0]['id']);


sboAdvancedParamsMenu.attachEvent('onAfterSelect', function (id){
    wSboTabFormSettings_cell.progressOn();
    displaySboSettingsForm(id);
    wSboTabFormSettings_cell.progressOff();
});

const wSboTabFormSettings_cell = wSboTabSettingsLayout.cells('b');
wSboTabFormSettings_cell.hideHeader();
wSboTabFormSettings_cell.cell.classList.add('service');


async function displaySboSettingsForm(sectionId) {
    let format = 'json';
    // FETCH CONTENT
    // TODO : dans fonction globale/proto
    let url = new URL('?ajax=1&act=cat_win-sbo_forms_'+sectionId+'_form.'+format, window.location.href);
    let reponse = await fetch(url);
    let data = false;
    let content = false;
    if(format === 'html'){
        data = await reponse.text();
        wSboTabFormSettings_cell.attachHTMLString(data);
    } else {
        data = await reponse.json();
        content = JSON.parse(data.extra.message);
        const wSboSettingsLayout_form = wSboTabFormSettings_cell.attachForm(content);
        
        wSboSettingsLayout_form.attachEvent("onBeforeChange", function (name) {
            if(name === 'safe_remove_shop_relations'){
                if(wSboSettingsLayout_form.getItemValue(name)){
                    wSboSettingsLayout_form.setItemValue('remove_all_shop_relations', 0)
                    wSboSettingsLayout_form.disableItem('remove_all_shop_relations')
                } else {
                    wSboSettingsLayout_form.enableItem('remove_all_shop_relations')
                }
            }
            return true;
        })
        //wSboSettingsLayout_form.adjustParentSize();
        wSboSettingsLayout_form.attachEvent("onButtonClick", function (name) {
            let submit = name === "save_"+sectionId;
            if(name.includes('advanced_db')){
                sectionId = name;
                submit = true;
                if(wSboSettingsLayout_form.getItemValue('remove_all_shop_relations')){
                    if (!confirm('<?php echo _l('Deleting the product selection is irreversible and can lead data loss. Continue ?', 1); ?>')) {
                        return;
                    }
                }
                if(wSboSettingsLayout_form.getItemValue('clear_sbo_service')){
                    let message = '';
                    <?php if (SCMS) { ?>
                        message = '<?php echo _l('Deleting Shippingbo parameters will remove all synchronization data gathered for this account(although saved on already imported products in PrestaShop). Continue?', 1); ?>';
                    <?php }else{ ?>
                        message = '<?php echo _l('Deleting Shippingbo parameters will remove all synchronization data gathered for this account and linked shops (although saved on already imported products in PrestaShop). Continue?', 1); ?>';
                    <?php } ?>

                    if (!confirm(message)) {
                        return;
                    }
                }

                if(wSboSettingsLayout_form.getItemValue('reset_connector')){
                    if (!confirm('<?php echo _l('Resetting connector is irreversible and can lead data loss. Continue ?', 1); ?>')) {
                        return;
                    }
                }

            }

            if (submit) {
                wSboTabFormSettings_cell.progressOn();
                wSboSettingsLayout_form.send("index.php?ajax=1&act=cat_win-sbo_settings_update&section="+sectionId, "post", function (xml) {
                    wSboTabFormSettings_cell.progressOff();
                    let response = JSON.parse(xml.xmlDoc.response);
                    let type = response.state === true ? 'sc_success' : 'error';
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
                    displayTree();
                });
            }
        });
    }
    document.querySelectorAll('.collapsible > fieldset > legend ').forEach(legend => {
        legend.addEventListener("click", function() {
            let target = this.nextElementSibling;
            let collapsibleWrapper = legend.parentElement.parentElement;
            if (window.getComputedStyle(target).display === 'block') {
                collapsibleWrapper.classList.add('collapsed');
                return;
            }
            collapsibleWrapper.classList.remove('collapsed');
        })
    })
}

<?php echo '</script>'; ?>