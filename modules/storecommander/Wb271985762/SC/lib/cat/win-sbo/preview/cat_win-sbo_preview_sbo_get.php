<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
$shippingboService->switchToShopId((int) Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULT')));
$exportFields = json_decode($shippingboService->getConfigValue('defaultDataExport'), true);
$unitConversion = json_decode($shippingboService->getConfigValue('unitConversion'), true);
//$sboAccountId = (int) $shippingboService->getConfigValue('id_sbo_account');
try
{
    $id_lang = (int) Tools::getValue('id_lang');
    $sboType = Tools::getValue('sboType', null);
    $tabId = Tools::getValue('previewTabId', 'awaiting');
    $totalCount = (int) Tools::getValue('totalCount', 0);

    $posStart = (int) Tools::getValue('posStart', 0);

    $pdo = Db::getInstance()->getLink();

    switch ($sboType) {
        case 'packs':
            $repository = $shippingboService->getPackRepository();
            break;
        case 'batches':
            $repository = $shippingboService->getBatchRepository();
            break;
        default:
            $repository = $shippingboService->getProductRepository();
    }

    $columnsToDisplay = [
        'name',
        'id_product',
        'id_product_attribute',
        'active',
        'is_locked',
        'reference',
        'location',
        'statusLabel',
        'type_sbo',
    ];
    // remove StatusLabel column in locked Tab
    if ($tabId === 'locked')
    {
        $columnsToDisplay = array_values(array_diff($columnsToDisplay, ['statusLabel']));
    }

    $baseQuery = $repository->getMissingSboQuery(true, $posStart / Shippingbo::GRID_RESULTS_PER_PAGE);

    $results = [];
    $xml = [];
    switch ($tabId) {
            case 'awaiting':
                $stmt = $pdo->prepare($repository->addSboErrorParts($baseQuery));
                $stmt->execute([
                    ':id_lang' => $shippingboService->getScAgent()->getIdLang(),
                    ':id_shop' => $shippingboService->getIdShop(),
                    ':id_sbo_account' => $shippingboService->getSboAccount()->getId(),
                    ':is_locked' => false,
                    ':has_error' => false,
                ]);
                break;
            case 'locked':
                $stmt = $pdo->prepare($baseQuery);
                $stmt->execute([
                    ':id_lang' => $shippingboService->getScAgent()->getIdLang(),
                    ':id_shop' => $shippingboService->getIdShop(),
                    ':is_locked' => true,
                ]);
                break;
            default:
                $stmt = $pdo->prepare($repository->addSboErrorParts($baseQuery));
                $stmt->execute([
                    ':id_lang' => $shippingboService->getScAgent()->getIdLang(),
                    ':id_shop' => $shippingboService->getIdShop(),
                    ':id_sbo_account' => $shippingboService->getSboAccount()->getId(),
                    ':is_locked' => false,
                    ':has_error' => true,
                ]);
        }
    if (!$rows = $stmt->fetchAll(PDO::FETCH_ASSOC))
    {
        $rows = [];
    }
    foreach ($rows as $key => $row)
    {
        $row_xml = [];
        $row_xml[] = '<userdata name="id_product">'.(int) $row['id_product'].'</userdata>';
        $row = processSboRow($row, $columnsToDisplay, $sboType, $exportFields, $unitConversion);
        foreach ($row as $columnName => $cell)
        {
            if (in_array($columnName, $columnsToDisplay))
            {
                if ($columnName === 'active')
                {
                    $cell['value'] = (int) $cell['value'] > 0 ? _l('Yes') : _l('No');
                }
                $row_xml[] = '<cell class="'.$cell['class'].'"><![CDATA['.$cell['value'].']]></cell>';
            }
        }
        $xml[] = '<row id="'.$row['rowId']['value'].'" data-id_shop_relation="'.$row['id_storecom_service_shippingbo_shop_relation']['value'].'">'.implode("\r\n\t", $row_xml).'</row>';
    }

    if (stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml'))
    {
        header('Content-type: application/xhtml+xml');
    }
    else
    {
        header('Content-type: text/xml');
    }
    echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
}
catch (\Exception $e)
{
    $shippingboService->getLogger()->error($e->getMessage());
    $shippingboService->sendResponse('error', [$e->getMessage()]);
}

    ?>

<rows pos="<?php echo (int) $posStart; ?>">
    <?php if ($posStart === 0){ ?>
    <head>
        <beforeInit>
            <call command="attachHeader"><param><![CDATA[#text_filter,#numeric_filter,#numeric_filter,#select_filter,#select_filter,#text_filter,#select_filter]]></param></call>
        </beforeInit>
        <column id="name" width="280" type="ro" align="left" sort="str"><?php echo _l('Name'); ?></column>
        <column id="id_product" width="60" type="coro" align="left" sort="int"><?php echo _l('id prod.'); ?></column>
        <column id="id_product_attribute" width="60" type="ro" align="left" sort="str"><?php echo _l('id prod. attr.'); ?></column>
        <column id="active" width="70" type="ro" align="left"><?php echo _l('Active'); ?></column>
        <column id="is_locked" type="coro" width="100" align="left"><?php echo _l('Enable Shippingbo synchronization'); ?>
            <option value="<?php echo Sc\Service\Shippingbo\Shippingbo::SBO_PRODUCT_IS_UNLOCKED; ?>"><?php echo _l('Yes'); ?></option>
            <option value="<?php echo Sc\Service\Shippingbo\Shippingbo::SBO_PRODUCT_IS_LOCKED; ?>"><?php echo _l('No'); ?></option>
            </column>
        <column id="reference" width="200" type="ed" align="left" sort="str"><?php echo _l('PrestaShop reference'); ?></column>
        <column id="location" width="100" type="ro" align="left"
                sort="str"><?php echo _l('Location'); ?></column>
        <?php if (in_array('statusLabel', $columnsToDisplay)){ ?>
        <column id="statusLabel" width="200" type="ro" align="left" sort="str"><?php echo _l('Status'); ?></column>
        <?php } ?>
        <column id="groupPosition" width="70" type="ro" hidden="true"></column>
        <column id="type_sbo" width="70" type="ro" hidden="true"></column>
    </head>
    <?php } ?>
    <?php echo implode("\r\n", $xml); ?>
</rows>

<?php
/**
 * @param array<string,mixed> $row
 * @param array<int,string>   $columnsToDisplay
 * @param string              $sboType
 * @param array<string,mixed> $exportFields
 * @param array<string,mixed> $unitConversion
 *
 * @return array<string,mixed>
 */
function processSboRow($row, $columnsToDisplay, $sboType, $exportFields, $unitConversion)
{
    $statusLabel = _l('No error');
    $statusLabelClass = 'sc_cell_success';
    $refColName = 'reference';
    $formattedRow = array_map(function ($v)
    {
        return ['value' => $v, 'class' => ''];
    }, $row);
    foreach ($row as $columnName => $value)
    {
        switch ($columnName){
            case $refColName:
                if ($value === '' && $statusLabelClass === 'sc_cell_success')
                {
                    $statusLabel = _l('Reference is required');
                    $statusLabelClass = 'sc_cell_error';
                }
                if ((bool) $formattedRow['duplicate_target_ref']['value'] && $statusLabelClass === 'sc_cell_success')
                {
                    $statusLabel = _l('Reference is duplicated');
                    $statusLabelClass = 'sc_cell_error';
                }
                break;
            case 'width':
            case 'height':
            case 'weight':
            case 'length':
                if ((bool) $exportFields[$columnName] && $value > 0)
                {
                    $convertedValue = $value * $unitConversion[$columnName];
                    $unit = ($columnName === 'weight') ? 'g' : 'mm';
                    if (($convertedValue - (int) $convertedValue) > 0 && $statusLabelClass === 'sc_cell_success')
                    {
                        $statusLabel = _l('%s value '.$convertedValue.$unit.' must be an integer', false, [$columnName]);
                        $statusLabelClass = 'sc_cell_error';
                    }
                }
                break;

            case 'locked_component':
                if ((bool) $value && $statusLabelClass === 'sc_cell_success')
                {
                    $statusLabel = _l('One or more products composing this pack is locked');
                    if ($sboType == 'batches')
                    {
                        $statusLabel = _l('The product composing this batch is locked');
                    }

                    $statusLabelClass = 'sc_cell_error';
                }
                break;
//            case 'component_has_error':
//                if((bool)$value && $statusLabelClass === 'sc_cell_success'){
//                    $statusLabel = _l("One or more products composing this element contains error", null);
//                    $statusLabelClass  = 'sc_cell_error';
//                };
//                break;
            case 'is_locked':
                if ((bool) $value)
                {
                    $formattedRow[$columnName]['class'] = 'sc_cell_info';
                }
                break;
            default:
        }
    }
    $formattedRow = array_merge(array_flip($columnsToDisplay), $formattedRow);
    if (array_key_exists('statusLabel', $formattedRow))
    {
        $formattedRow['statusLabel'] = [
            'value' => $statusLabel,
            'class' => $statusLabelClass,
        ];
        $formattedRow[$refColName]['class'] = $statusLabelClass;
    }

    return $formattedRow;
}
 ?>
