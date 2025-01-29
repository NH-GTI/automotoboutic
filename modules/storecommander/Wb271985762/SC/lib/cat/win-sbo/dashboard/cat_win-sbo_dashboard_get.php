<?php if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
$shippingboService->switchToShopId((int) Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULT')));

//$startSync = filter_var(Tools::getValue('sync', false), FILTER_VALIDATE_BOOLEAN);
$startSync = false;

$sc_agent = SC_Agent::getInstance();
$pdo = Db::getInstance()->getLink();
$stats = $shippingboService->getStatsRepository()->getFullStats();

$lastCollectText = ucfirst(_l('first start'));
$lastSyncedAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $shippingboService->getLastSyncedAt(), new DateTimeZone(SCI::getConfigurationValue('PS_TIMEZONE')));

$localizedDateTime = '';
if ($lastSyncedAt)
{
    $lastCollectText = _l('Last data analysis', 1).'&nbsp;: ';
    $localizedDateTime = $shippingboService->getLocaleDate($lastSyncedAt, 'dd/MM/yyyy H:mm');
    $lastCollectText .= $localizedDateTime;
}

?>

<div class="<?php echo ($startSync) ? 'sync' : ''; ?>">

    <div class="service html_content sbo-dashboard <?php echo ($startSync) ? 'sync_in_progress' : ''; ?>">
        <div class="sync_infos">
            <h2><?php echo $lastCollectText; ?></h2>

            <div class="process" data-starttext="<?php echo _l('Starting'); ?>...">
                <button class="refresh secondary"><?php echo _l('Analyze Shippingbo data'); ?></button>
                <div class="progress">
                    <?php include SC_DIR.'shared/ScProcess/templates/progressBarTemplate.html.php'; ?>
                    <span class="icon"></span>
                    <span class="stepName"></span>
                    <span class="text"></span>
                </div>
            </div>

        </div>

        <?php include 'templates/shopSelection.html.php'; ?>

        <div class="platforms">

            <?php $targetPlatform = 'ps'; ?>
            <div class="platform <?php echo $targetPlatform; ?>">
                <div class="platform-header">
                    <h2><?php echo ucfirst(_l('shippingbo')).' ⇒ '.ucfirst(_l('prestashop')); ?></h2>
                    <p class="intro">
                        <button class="dhxform_btn dhxform_btn_txt single secondary sbo_import" data-shopid="<?php echo $shippingboService->getIdShop(); ?>">
                            <i class="fal fa-file-import"></i>
                            <?php echo _l('Import into Prestashop'); ?>
                        </button>
                    </p>
                </div>

                <div class="details">
                    <?php
                    $sboType = 'products';
                    include 'templates/block.html.php';
                    $sboType = 'batches';
                    include 'templates/block.html.php';
                    $sboType = 'packs';
                    include 'templates/block.html.php';
                    ?>
                </div>
            </div>
            <?php $targetPlatform = 'sbo'; ?>
            <div class="platform <?php echo $targetPlatform; ?>">
                <div class="platform-header">
                    <h2><?php echo ucfirst(_l('prestashop')).' ⇒ '.ucfirst(_l('shippingbo')); ?></h2>
                    <p class="intro">
                        <?php echo _l('Update data by importing files into <a href="#" class="%s">Shippingbo</a>', 0, ['sbo_open']); ?>
                    </p>
                </div>

                <div class="details">
                    <?php
                    $sboType = 'products';
                    include 'templates/block.html.php';
                    $sboType = 'batches';
                    include 'templates/block.html.php';
                    $sboType = 'packs';
                    include 'templates/block.html.php';
                    ?>
                </div>
            </div>

        </div>

        <script>

            $('.service:not(.sync_in_progress) .sbo_import').on('click', function () {
                if($(this).closest('.service.syncing').length === 0){
                    parent.window.wSboPreviewOpenClick('import',null,null,null,this.dataset.shopid);
                }
            })
            $('.service:not(.sync_in_progress) .sbo_open').on('click', function () {
                window.open('<?php echo Shippingbo::LINK_PRODUCT_URL; ?>',)
            })

            $('.service .sbo_open_view').on('click', function (e) {
                e.stopPropagation();
                if(!this.closest('.service.sync_in_progress')){
                    /*gestion classe active sur blocks */
                    for (const child of document.querySelectorAll('.details > div')) {
                        child.classList.remove('active');
                    }
                    this.closest('div').classList.add('active');

                    /* open preview */
                    parent.window.wSboPreviewOpenClick(this.dataset.preview, this.dataset.platform, this.dataset.sbotype, this.dataset.tabid, this.dataset.shopid);
                }
            })

            $('.service:not(.sync_in_progress) button.sbo_download').on('click', function () {
                parent.window.wSboDownloadClick(this.dataset.download, this.dataset.shopid);
            })


        </script>
    </div>
