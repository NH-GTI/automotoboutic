<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}
use Sc\Service\Shippingbo\Shippingbo;

if (SCMS) {
    $shippingboService = Shippingbo::getInstance();
    $shop = (object) Shop::getShop((int)Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULT')));
    $shippingboService->switchToShopId($shop->id_shop);
?>


    <h2 class="form-title"><?php echo ucfirst(_l('Shop') . ' ' . $shop->name); ?></h2>

    <div id="start_shop_configuration">
        <?php echo _l('Start shop configuration'); ?>

        <div class="shop_configuration_choice">
            <a href="" class="btn" id="select_shop"><?php echo _l('Copy configuration from anather shop'); ?></a>
            <span><?php echo _l('or'); ?></span>
            <a id="shops" href="" class="btn" data-id_shop="<?php echo $shop->id_shop; ?>" data-format="html" data-validateconfig="false" data-enabled="true"
                ><?php echo _l('Configure manually'); ?></a>
        </div>
    </div>

    <script>

        document.querySelectorAll('#start_shop_configuration a').forEach((link) => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                // select shop to copy
                if(event.target.id === 'select_shop') {
                    const sboSelectShop = dhxWins.createWindow('wSboSelectShop', $(window).width() / 2 - 250, 50, 500, $(window).height() - 100);
                    sboSelectShop.setText('select shop');
                    displaySboShopSelection(sboSelectShop);
                    sboSelectShop.attachEvent('onClose', function (win) {
                        win.hide();
                        return false;
                    });
                } else {// display shop config form
                    displaySboShopSettingsForm(event);
                }
            })

        })

        async function displaySboShopSelection(sboSelectShop){
            let url = new URL('?ajax=1&act=cat_win-sbo_shop-settings_shops_get.html', window.location.href);
            url.searchParams.set('id_shop', <?php echo $shippingboService->getIdShop(); ?>);
            let response = await fetch(url);
            let data = false;
            data = await response.text();
            sboSelectShop.attachHTMLString(data);
        }


    </script>

<?php } ?>