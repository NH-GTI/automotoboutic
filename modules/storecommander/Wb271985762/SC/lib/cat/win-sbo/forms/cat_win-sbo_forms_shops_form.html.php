<?php
if (!defined('STORE_COMMANDER'))
{
	exit;
}
use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();

// shop context
$shop_id = !SCMS?(int)Configuration::get('PS_SHOP_DEFAULT'):(int)Tools::getValue('id_shop');
$shop = (object) Shop::getShop($shop_id);

$shippingboService->switchToShopId($shop->id_shop);

// forms definition

?>
<?php if(SCMS && !$shippingboService->isFirstStart()) { ?>

    <h2 class="form-title"><?php echo ucfirst(_l('Shop') . ' ' . $shop->name); ?></h2>

    <div class="enable_sbo_for_shop dhxform_base">
        <input class="toggle" id="enable_sbo_for_shop_<?php echo $shop->id_shop; ?>" data-id_shop="<?php echo $shop->id_shop; ?>" type="checkbox" <?php echo filter_var($shippingboService->getConfigValue('allowSync'), FILTER_VALIDATE_BOOLEAN)?'checked="checked"':''; ?>/>
        <label for="enable_sbo_for_shop_<?php echo $shop->name; ?>"><?php echo _l('Synchronization enabled'); ?></label>
    </div>


<?php } ?>


<div id="sboGuideSteps" class="form-steps">
    <div class="form-step" id="shops">
        <div class="form-step-content" id="shops_form" style="width:100%;"></div>
		<?php if ($shippingboService->isFirstStart()) { ?>
            <!-- Bouton de validation global when firstStart-->
            <div class="endGrid">
                <button id="completeInitialSetup" class="first-start validate"><?php echo _l('Complete configuration'); ?></button>
            </div>
		<?php } ?>
    </div>
</div>

<script>
    let actualSboAccountId = <?php echo $shippingboService->getSboAccount()->getId() ?>;
	<?php if (!$shippingboService->isFirstStart()) { ?>
    /* gestion activation/désactivation synchro boutique */
    if (SCMS) {
        let enable_form = window.document.querySelector('.enable_sbo_for_shop input');
        toggleFormDisplay(enable_form.checked);
        enable_form.addEventListener("change", (event) => {toggleSync(event)});
    } else
        toggleFormDisplay(true);

	<?php } else { ?>
    toggleFormDisplay(true);
	<?php } ?>

    function toggleFormDisplay(checked){
        if(checked === true){
            displayFormSteps(<?php echo $shop->id_shop; ?>);
        } else {
            window.document.querySelector('#sboGuideSteps').hidden = true;
        }
    }
    async function toggleSync(event){
        toggleFormDisplay(event.target.checked)
        let response = await fetch('index.php?ajax=1&act=cat_win-sbo_shop-settings_update&section=enable_sync&id_shop=<?php echo $shop->id_shop; ?>&value='+event.target.checked);

        let data = await response.json();

        wSboTabBar.tabs('shop-settings')._reloadListOption(<?php echo (int)$shop->id_shop; ?>)

        dhtmlx.message({
            text: data.extra.message,
            type: data.state === true ? 'sc_success' : 'error',
            expire: 7000
        });
    }

    async function displayFormSteps(id_shop){
        window.document.querySelector('#sboGuideSteps').hidden = false;
        let formSteps = window.document.querySelectorAll('#sboGuideSteps > .form-step');
        for (let formStep of formSteps){
            let sboSettingsFormStep = new dhtmlXForm(formStep.id+'_form');
            // si formulaire déja récupéré

            if(formStep.querySelector('#'+formStep.id+'_form').innerHTML !== '')
                continue;
            let response = await fetch('index.php?ajax=1&act=cat_win-sbo_forms_'+formStep.id+'_form.json&id_shop='+id_shop);
            let data = await response.json();
            if(sboSettingsFormStep){
                sboSettingsFormStep.loadStruct(data.extra.message);
                sboSettingsFormStep.attachEvent("onButtonClick", function (name) {
                    switch(name) {
                        case "save_" + formStep.id:
                            if (actualSboAccountId && Number(sboSettingsFormStep.getItemValue('id_account')) !== actualSboAccountId) {
                                if (!confirm('<?php echo _l('Changing Shippingbo Account will remove product selection for this shop. Continue ?', 1); ?>')) {
                                    return;
                                }
                            }

                            sboSettingsFormStep.send("index.php?ajax=1&act=cat_win-sbo_shop-settings_update&section=" + formStep.id + '&id_shop=' + id_shop, "post", function (xml) {
                                let updateResponse = JSON.parse(xml.xmlDoc.response);

                                let type = updateResponse.state === true ? 'sc_success' : 'error';
                                // if (updateResponse.state === true) {
                                //     formStep.classList.add('valid');
                                //     document.getElementById(formStep.id + '_form').classList.add('hide');
                                //     displayFormSteps(id_shop)
                                // }
                                dhtmlx.message({
                                    text: updateResponse.extra.message,
                                    type: type,
                                    expire: 7000
                                });
                                wSboTabBar.tabs('shop-settings')._reloadListOption(id_shop)
                            });
                            break;
                        case 'delete_shop_configuration':
                            dhtmlx.confirm('<?php echo _l('Do you really want to delete this configuration ?'); ?>', function(result) {
                                if(result) {
                                    sboSettingsFormStep.send("index.php?ajax=1&act=cat_win-sbo_shop-settings_update&section=" + name + '&id_shop=' + id_shop, "post", function (xml) {
                                        let response = JSON.parse(xml.xmlDoc.response);

                                        let type = response.state === true ? 'sc_success' : 'error';
                                        if (response.state === true) {
                                            wSboTabBar.tabs('shop-settings')._reloadListOption()
                                        }
                                        dhtmlx.message({
                                            text: response.extra.message,
                                            type: type,
                                            expire: 7000
                                        });
                                    });
                                }
                            })
                            break;
                    }
                });
            }
        }
    }

	<?php if ($shippingboService->isFirstStart()) { ?>
    document.getElementById('completeInitialSetup').addEventListener('click', async function(){
        let formSteps = window.document.querySelectorAll('#sboGuideSteps > .form-step');
        for (let formStep of formSteps){
            let button = formStep.querySelector('button[name="save_'+formStep.id+'"]');
            if(button) {
                button.click();
            }
        }

        $.post("index.php?ajax=1&act=cat_win-sbo_shop-settings_update&section=complete", function () {
            const wSboUrl = 'index.php?ajax=1&act=cat_win-sbo_init&sync=true&id_shop='+wSboTabGetIdShop();
            $.get(wSboUrl,function(data){$('#jsExecute').html(data)});
        });
    });
	<?php } ?>
</script>