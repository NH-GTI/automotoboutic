<?php if(SCMS){ ?>
    <?php
    /* @var \Sc\Service\Shippingbo\Shippingbo $shippingboService */
    $shopsConfigs = $shippingboService->getShopsWithConfig();
    $shippingboService->switchToShopId(Tools::getValue('id_shop',Configuration::get('PS_SHOP_DEFAULT')));
    $currentShopConfig = $shopsConfigs[$shippingboService->getIdShop()]['services'][$shippingboService->getServiceName()];
    ?>

    <div class="custom-select">
        <div class="select-button <?php echo ($currentShopConfig['validateConfig']&& $currentShopConfig['isEnabled'])?'configured':'not_configured'; ?>" role="combobox" aria-labelledby="select button" aria-haspopup="listbox" aria-expanded="false" aria-controls="select-dropdown">
            <span class="selected" data-value="<?php echo $shippingboService->getIdShop() ?>">
                <?php echo $shopsConfigs[$shippingboService->getIdShop()]['name'] ?>
            </span>
            <span class="arrow"></span>
        </div>
        <ul role="listbox" id="sbo_shop_id" class="select-dropdown">
            <?php foreach ($shopsConfigs as $shop){ ?>
                <?php $shopService = $shop['services'][$shippingboService->getServiceName()]; ?>
                <?php if(!$shopService['validateConfig'] or !$shopService['isEnabled']) continue; ?>
                <li role="option" data-value="<?php echo $shop['id_shop']; ?>" data-validateConfig="<?php echo $shopService['validateConfig']; ?>" data-isEnabled="<?php echo $shopService['isEnabled']; ?>" class="<?php echo ($shopService['validateConfig']&& $shopService['isEnabled'])?'configured':'not_configured'; ?>">
                    <label for="shop_<?php echo $shop['id_shop']; ?>"><?php echo $shop['name']; ?></label>
<!--                    <span class="config_state">-->
<!--                        --><?php //echo ($shopService['validateConfig']&& $shopService['isEnabled'])?_l('shop ready'):_l('shop not ready'); ?>
<!--                    </span>-->
                </li>
            <?php } ?>
        </ul>
    </div>

    <script>

        const customSelect = document.querySelector(".custom-select");
        const selectBtn = document.querySelector(".select-button");

        // add a click event to select button
        selectBtn.addEventListener("click", () => {
            // add/remove active class on the container element
            customSelect.classList.toggle("active");
            // update the aria-expanded attribute based on the current state
            selectBtn.setAttribute(
                "aria-expanded",
                selectBtn.getAttribute("aria-expanded") === "true" ? "false" : "true"
            );
        });

        // set default shop value
        wSboTabSetIdShop(document.querySelector(".custom-select .selected").dataset.value)

        const optionsList = document.querySelectorAll(".select-dropdown li");
        optionsList.forEach((option) => {
            function handler(e) {
                let liNode = e.target.closest('li');
                if(liNode.dataset.value === document.querySelector(".custom-select .selected").dataset.value && liNode.dataset.isEnabled && liNode.dataset.validateConfig){
                    customSelect.classList.toggle("active");
                    selectBtn.setAttribute("aria-expanded","false");
                    return false;
                }
                // Click Events
                if (
                    (e.type === "click" && e.clientX !== 0 && e.clientY !== 0)
                    || e.key === "Enter"
                ) {

                    let tabId = 'dashboard';
                    if(liNode.classList.contains('not_configured')){
                        tabId = 'shop-settings';
                    }

                    let selectedShopId = e.target.parentNode.dataset.value
                    wSboTabSetIdShop(selectedShopId)
                    wSboTabClick(tabId, {'id_shop': selectedShopId});
                }
            }

            option.addEventListener("keyup", handler);
            option.addEventListener("click", handler);
        });

    </script>
<?php } ?>