<?php if ($stats[$targetPlatform][$sboType][$status] != 0) {?>

        <li class="<?php echo $status; ?>">
        <a href="#" class="sbo_open_view"
           data-preview="grid" data-sboType="<?php echo $sboType; ?>" data-platform="<?php echo $targetPlatform; ?>" data-shopid="<?php echo $shippingboService->getIdShop(); ?>"  data-tabid="<?php echo $status; ?>">
            <?php echo _l('%s '.$labels[$status], null, [$stats[$targetPlatform][$sboType][$status]]); ?>
        </a>

    <?php if ($status === 'awaiting' && $targetPlatform === 'sbo') { ?>
        <ul class="downloads">
        <li class="download">
            <button class="dhxform_btn dhxform_btn_txt single secondary sbo_download"
                    data-download="<?php echo $sboType; ?>" data-shopid="<?php echo $shippingboService->getIdShop(); ?>"
                    title="<?php echo _l('CSV containing %s ready for Shippingbo import', null, [$sboType]); ?>">
                <i class="fal fa-download"></i>
                <?php echo _l('Download '.$sboType); ?>
            </button>
            <?php if ($sboType === 'packs' && $targetPlatform === 'sbo') { ?>
                <button class="dhxform_btn dhxform_btn_txt single secondary sbo_download"
                        data-download="pack_components" data-shopid="<?php echo $shippingboService->getIdShop(); ?>"
                title="<?php echo _l('CSV containing %s ready for Shippingbo import', null, [_l('pack components')]); ?>">
                    <i class="fal fa-download"></i>
                    <?php echo ucfirst(_l('download pack components')); ?>
                </button>
            <?php } ?>
        </li>
        </ul>
    <?php } ?>
    </li>
<?php } ?>

