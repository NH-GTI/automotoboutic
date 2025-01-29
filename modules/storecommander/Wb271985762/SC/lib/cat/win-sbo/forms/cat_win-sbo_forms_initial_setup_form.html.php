<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}
use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();

// shop context
//$shop_id = !SCMS?(int)Configuration::get('PS_SHOP_DEFAULT'):(int)Tools::getValue('id_shop');
$shop_id = (int)Configuration::get('PS_SHOP_DEFAULT');
$shop = (object) Shop::getShop($shop_id);

$shippingboService->switchToShopId($shop->id_shop);

// forms definition
$formStepsDefinition = [
    'initial_setup' => ['title' => _l('Configuration')],
];
?>

<div id="sboGuideSteps" class="form-steps">
    <?php $forceInvalid = false; ?>
    <?php foreach ($formStepsDefinition as $id => $formStep) { ?>
            <?php $forceInvalid = isset($formStep['valid'])?(bool) $formStep['valid']:false; ?>
            <div class="form-step<?php echo (!$forceInvalid) ? ' valid' : ''; ?>" id="<?php echo $id; ?>">
                <div class="form-step-content" id="<?php echo $id; ?>_form"></div>
            </div>
    <?php } ?>
</div>

<script>

    toggleFormDisplay(true);

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

        // console.log(event.target.dataset);
        wSboTabClick('shop-settings', {...event.target.dataset});

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
                    if (name === "save_"+formStep.id) {
                        sboSettingsFormStep.send("index.php?ajax=1&act=cat_win-sbo_initial-setup_update&id_shop="+id_shop, "post", function (xml) {
                            let updateResponse = JSON.parse(xml.xmlDoc.response);
                            if (updateResponse.state === true) {
                                sboSettingsFormStep.hideItem('error_area');
                                dhtmlx.message({
                                    text: updateResponse.extra.message,
                                    type: 'sc_success',
                                    expire: 7000
                                });

                                if(formStep.id === 'initial_setup' && SCMS) {
                                    wSboTabClick('shop-selection')
                                    return false;
                                }
                                const wSboUrl = 'index.php?ajax=1&act=cat_win-sbo_init&sync=true';
                                $.get(wSboUrl,function(data){$('#jsExecute').html(data)});
                            } else {
                                sboSettingsFormStep.setItemLabel('error_area', updateResponse.extra.message);
                                sboSettingsFormStep.showItem('error_area');
                                document.querySelectorAll('.message.error')[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        });
                    }
                });
            }
        }
    }


</script>