<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
$shippingboService->switchToShopId((int) Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULT')));
$sboAccountId = (int) $shippingboService->getConfigValue('id_sbo_account');
$xml = [];
try
{
    $sboType = Tools::getValue('sboType', null);
    $tabId = Tools::getValue('previewTabId', 'awaiting');
    $totalCount = Tools::getValue('totalCount', null);
    $totalCount = $totalCount === 'null' ? null : $totalCount;
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
        'id_sbo',
        'user_ref',
        'type_sbo',
        'statusLabel',
        'title',
        'is_locked',
        'location',
        'width',
        'height',
        'length',
        'weight',
    ];
    // remove StatusLabel column in locked Tab
    if ($tabId === 'locked')
    {
        $columnsToDisplay = array_values(array_diff($columnsToDisplay, ['statusLabel']));
    }
    $baseQuery = $repository->getMissingPsQuery($posStart / Shippingbo::GRID_RESULTS_PER_PAGE);

    switch ($tabId) {
            case 'awaiting':
                $queryWithErrors = $repository->addPsErrorParts($baseQuery);
                $stmt = $pdo->prepare($queryWithErrors);
                $stmt->execute([
                    ':sku_max_length' => Product::$definition['fields']['reference']['size'],
                    ':id_shop' => $shippingboService->getIdShop(),
                    ':id_sbo_account' => $sboAccountId,
                    ':has_error' => false,
                    ':is_locked' => false,
                ]);

                break;
            case 'locked':
                $stmt = $pdo->prepare($baseQuery);
                $stmt->execute([
                    ':id_shop' => $shippingboService->getIdShop(),
                    ':id_sbo_account' => $sboAccountId,
                    ':is_locked' => true,
                ]);
                break;
            default:
                $stmt = $pdo->prepare($repository->addPsErrorParts($baseQuery));
                $stmt->execute([
                    ':sku_max_length' => Product::$definition['fields']['reference']['size'],
                    ':id_shop' => $shippingboService->getIdShop(),
                    ':id_sbo_account' => $sboAccountId,
                    ':has_error' => true,
                    ':is_locked' => false,
                ]);
        }
    if (!$rows = $stmt->fetchAll(PDO::FETCH_ASSOC))
    {
        $rows=[];
    }
    foreach ($rows as $key => $row)
    {
        $row_xml = [];
        $row = processPsRow($row, $columnsToDisplay, $sboType);
        foreach ($row as $columnName => $cell)
        {
            if (in_array($columnName, $columnsToDisplay))
            {
                $row_xml[] = '<cell class="'.$cell['class'].'"><![CDATA['.$cell['value'].']]></cell>';
            }
        }
        $xml[] = '<row id="'.$row['rowId']['value'].'" data-id_shop_relation="'.$row['id_storecom_service_shippingbo_shop_relation']['value'].'">
        <userdata name="id_sbo">'.(int) $row['id_sbo']['value'].'</userdata>'.implode("\r\n\t", $row_xml).'</row>';
    }

    // send header
    if (stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml'))
    {
        header('Content-type: application/xhtml+xml');
    }
    else
    {
        header('Content-type: text/xml');
    }

    // build xml
    echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
}
catch (Exception $e)
{
    $shippingboService->addError($e);
    $shippingboService->sendResponse();
}

?>
<rows pos="<?php echo (int) $posStart; ?>">
    <head>
        <afterInit>
            <call command="attachHeader">
                <param>#numeric_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#numeric_filter,#numeric_filter,#numeric_filter,#numeric_filter</param>
            </call>
        </afterInit>
        <column id="id_sbo" width="70" type="ro" align="left"
                sort="int"><?php echo _l('Shippingbo id'); ?></column>
        <column id="user_ref" width="130" type="ro" align="left"
                sort="str"><?php echo _l('SKU logistic'); ?></column>
        <column id="type_sbo" width="70" type="ro" align="left"
                sort="str"><?php echo _l('Shippingbo type'); ?></column>
        <?php if (in_array('statusLabel', $columnsToDisplay)){ ?>
        <column id="statusLabel" width="200" type="ro" align="left" sort="str"><?php echo _l('Status'); ?></column>
        <?php } ?>
        <column id="name" width="130" type="ro" align="left" sort="str"><?php echo _l('Logistic title'); ?></column>
        <column id="is_locked" type="coro" width="100" align="left"><?php echo _l('Enable Shippingbo synchronization'); ?>
            <option value="<?php echo Sc\Service\Shippingbo\Shippingbo::SBO_PRODUCT_IS_UNLOCKED; ?>"><?php echo _l('Yes'); ?></option>
            <option value="<?php echo Sc\Service\Shippingbo\Shippingbo::SBO_PRODUCT_IS_LOCKED; ?>"><?php echo _l('No'); ?></option>
        </column>
        <column id="location" width="100" type="ro" align="left"
                sort="str"><?php echo _l('Location'); ?></column>
        <column id="width" width="70" type="ro" align="left"
                sort="str"><?php echo _l('Width').' ('._l('mm').')'; ?></column>
        <column id="height" width="70" type="ro" align="left"
                sort="str"><?php echo _l('Height').' ('._l('mm').')'; ?></column>
        <column id="length" width="70" type="ro" align="left"
                sort="str"><?php echo _l('Length').' ('._l('mm').')'; ?></column>
        <column id="weight" width="70" type="ro" align="left"
                sort="str"><?php echo _l('Weight').' ('._l('gram').')'; ?></column>
        <column id="groupLabel" width="70" type="ro" hidden="false"></column>
        <column id="groupPosition" width="70" type="ro" hidden="false"></column>
    </head>
    <?php
    echo implode("\r\n", $xml);
?>
</rows>

<?php
/**
 * @param array<string,mixed> $row
 * @param array<int,string>   $columnsToDisplay
 * @param string              $sboType
 *
 * @return array<string,mixed>
 */
function processPsRow($row, $columnsToDisplay, $sboType)
{
    $statusLabel = _l('No error');
    $statusLabelClass = 'sc_cell_success';
    $refColName = 'user_ref';
    $formattedRow = array_map(function ($v)
    {
        return ['value' => $v, 'class' => ''];
    }, $row);
    foreach ($row as $columnName => $value)
    {
        switch ($columnName){
            case $refColName:
                $skuMaxLength = Product::$definition['fields']['reference']['size'];
                if (strlen($value) > $skuMaxLength)
                {
                    $statusLabel = _l('SKU is too long, Prestashop limit is %s characters', false, [$skuMaxLength]);
                    $statusLabelClass = 'sc_cell_error';
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
            case 'missing_component':
                if ((bool) $value && $statusLabelClass === 'sc_cell_success')
                {
                    $statusLabel = _l('Missing components in Sbo');
                    $statusLabelClass = 'sc_cell_error';
                }
                break;
            case 'duplicate_target_ref':
                if ((bool) $value && $statusLabelClass === 'sc_cell_success')
                {
                    $statusLabel = _l('Several products/combinations use the same reference in Prestashop');
                    $statusLabelClass = 'sc_cell_error';
                }
                break;
            case 'components_with_error':
                if ((bool) $value && $statusLabelClass === 'sc_cell_success')
                {
                    $statusLabel = _l('Products composing this pack have errors');
                    if ($sboType == 'batches')
                    {
                        $statusLabel = _l('The product composing this batch has errors');
                    }
                    $statusLabelClass = 'sc_cell_error';
                }
                break;
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
        if (isset($formattedRow[$refColName]))
        {
            $formattedRow[$refColName]['class'] = $statusLabelClass;
        }
    }

    return $formattedRow;
}
