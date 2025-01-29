<?php
$sboTypes = $stats[$targetPlatform][$sboType];
$statusToDisplay = ['error','awaiting','locked'];

$statusByPriority = ($sboTypes['awaiting'] != 0) ? 'awaiting' : '';
$statusByPriority = ($sboTypes['error'] != 0) ? 'error' : $statusByPriority;

$labels = $shippingboService->getStatusLabels($targetPlatform);
$synchronizedCount =($stats[$targetPlatform][$sboType]['all']-$stats[$targetPlatform][$sboType]['missing']);
?>
<div class="<?php echo $sboType ;?>">
<h3 class="<?php echo $statusByPriority; ?>">
        <?php echo ucfirst(_l($sboType)).' '. $stats['synced'][$sboType].'/'.(int)$stats[$targetPlatform][$sboType]['all']; ?>
    <div class="help" role="tooltip" data-arrow="left">
        <div class="help-content">
            <ul>
                <li>
                    <?php echo _l($sboType).' '._l($labels['synchronized'])?>:
                    <strong><?php echo $synchronizedCount; ?></strong>
                </li>
                <li>
                    <?php echo _l($sboType). ' '._l('awaiting');?>:
                    <strong><?php echo (int)$stats[$targetPlatform][$sboType]['all']-$synchronizedCount;?></strong>
                </li>
<!--                <li>-->
<!--                    --><?php //echo _l($sboType).' '._l($labels['unlocked']);?><!--:-->
<!--                    <strong>--><?php //echo $stats[$targetPlatform][$sboType]['all'];?><!--</strong>-->
<!--                </li>-->
<!--                <li>-->
<!--                    --><?php //echo _l($sboType).' '._l($labels['locked']);?><!--:-->
<!--                    <strong>--><?php //echo $stats[$targetPlatform][$sboType]['locked'];?><!--</strong>-->
<!--                </li>-->
            </ul>
        </div>
    </div>
</h3>

    <ul class="actions">
<?php foreach($statusToDisplay as $status) {
    include 'status_block.html.php';
} ?>
    </ul>
</div>
