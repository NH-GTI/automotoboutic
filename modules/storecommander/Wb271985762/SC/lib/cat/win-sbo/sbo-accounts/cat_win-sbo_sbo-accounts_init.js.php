<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}
?>
<?php echo '<script>'; ?>

const wSboTabSettings = wSboTabBar.tabs('sbo-accounts');
let wSboTabSettingsLayout = wSboTabSettings.attachLayout("2U");


const wSboTabMenuSettings_cell = wSboTabSettingsLayout.cells('a');
wSboTabMenuSettings_cell.setText("<?php echo _l('Shippingbo accounts', 1); ?>");
wSboTabMenuSettings_cell.setWidth(280);
wSboTabMenuSettings_cell.hideArrow();
wSboTabMenuSettings_cell.fixSize(true,true);
wSboTabMenuSettings_cell.cell.classList.add('service');

const sboAccountMenu = wSboTabMenuSettings_cell.attachList({
    drag:false,
    select:true,
    template: function(account) {
        if(account.id === 'add') {
            return `<div class="dhxform_btn" role="link" tabindex="0" dir="ltr">
                        <div class="dhxform_btn_txt">${account.name}</div>
                    </div>`
        }
        let classlist = [];
        classlist.push('itemAccount')
        if(classlist.length > 0) {
            classlist = ' class="'+classlist.join(' ')+'"';
        }
        return `<div ${classlist}><span class="accountname">${account.name}</span></div>`
    },
    css:'service list sboaccount',
    height:80
});

sboAccountMenu._loadData = function(selection = 0)
{
    $.post('?ajax=1&act=cat_win-sbo_sbo-accounts_get',
        {
            defaultSelection: selection,
            id_shop: wSboTabGetIdShop()
        },
        function(response){
            if(response.state) {
                sboAccountMenu.clearAll();
                sboAccountMenu.parse(response.extra.accounts, 'json');
                if(Boolean(response.extra.selection)) {
                    sboAccountMenu.select(response.extra.selection)
                }
            }
    })
}
sboAccountMenu._loadData();


sboAccountMenu.attachEvent('onBeforeSelect', function (id){
    if(id === 'add') {
        displaySboAccountsForm(id);
        return false;
    }
    return true;
});
sboAccountMenu.attachEvent('onAfterSelect', function (id){
    wSboTabFormSettings_cell.progressOn();
    displaySboAccountsForm(Number(id));
    wSboTabFormSettings_cell.progressOff();
});

const wSboTabFormSettings_cell = wSboTabSettingsLayout.cells('b');
wSboTabFormSettings_cell.hideHeader();
wSboTabFormSettings_cell.cell.classList.add('service');


async function displaySboAccountsForm(id) {

    let selectedAccount = sboAccountMenu.get(id);
    let linkParams = {}
    if(id !== 'add') {
        linkParams['id_account'] = selectedAccount.id_account
    }

    // FETCH CONTENT
    // TODO : dans fonction globale/proto
    let url = new URL('?ajax=1&act=cat_win-sbo_forms_sbo_account_form.json', window.location.href);
    let params = new URLSearchParams(linkParams);
    params.forEach(function(value,key){
        url.searchParams.set(key,value);
    })
    let response = await fetch(url);
    let data = false;
    let content = false;

    data = await response.json();
    content = JSON.parse(data.extra.message);
    const wSboSettingsLayout_form = wSboTabFormSettings_cell.attachForm(content);
    wSboSettingsLayout_form.cont.classList.add('withDeleteButton')


    function handleFormErrorArea(name,jsonResponse,form){
        let callback = jsonResponse.extra.callback;
        if(callback && callback.functionName){
            if(typeof callback.functionName === 'function' || typeof callback.functionName === 'string') {
                executeFunctionByName(callback.functionName, window, callback.params);
            }
        }

        if (jsonResponse.state == true) {
            form.hideItem('error_area');
            dhtmlx.message({
                text: jsonResponse.extra.message,
                type: 'sc_success',
                expire: 7000
            });


            return true;
        }

        form.setItemLabel('error_area', jsonResponse.extra.message);
        form.showItem('error_area');
        return false;
    }


    wSboSettingsLayout_form.attachEvent("onButtonClick", function (name, command) {
        if (name === "save_sbo_account") {
            wSboTabFormSettings_cell.progressOn();
            wSboSettingsLayout_form.send("index.php?ajax=1&act=cat_win-sbo_sbo-accounts_update&action=save", "post", function (xml) {
                wSboTabFormSettings_cell.progressOff();
                let jsonResponse = JSON.parse(xml.xmlDoc.response);
                handleFormErrorArea(name,jsonResponse,wSboSettingsLayout_form);
            });
        }
        if (name === "delete_sbo_account") {
            if (confirm('Are you sure ?')) {
                wSboTabFormSettings_cell.progressOn();
                wSboSettingsLayout_form.send("index.php?ajax=1&act=cat_win-sbo_sbo-accounts_update&action=delete", "post", function (xml) {
                    wSboTabFormSettings_cell.progressOff();
                    let jsonResponse = JSON.parse(xml.xmlDoc.response);
                    handleFormErrorArea(name,jsonResponse,wSboSettingsLayout_form);
                });
            }
        }
    });
}

<?php echo '</script>'; ?>