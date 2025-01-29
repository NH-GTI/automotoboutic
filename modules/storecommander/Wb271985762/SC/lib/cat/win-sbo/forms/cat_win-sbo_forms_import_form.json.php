<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}
use Sc\Service\Shippingbo\Process\ImportData;
use Sc\Service\Shippingbo\Repository\Prestashop\SegmentRepository;
use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
$shippingboService->switchToShopId((int) Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULT')));

$defaultDataImport = (array) json_decode($shippingboService->getConfigValue('defaultDataImport'));

if (!$defaultDataImport['segment_type'])
{
    $defaultDataImport['segment_type'] = SegmentRepository::TYPE_PENDING;
}
if (!$defaultDataImport['product_name'])
{
    $defaultDataImport['product_name'] = ImportData::PRODUCT_NAME_TYPE_SKU;
}
$process = Tools::getIsset('process');

$formImport = [
    [
        'type' => 'settings',
        'position' => 'label-right',
    ],
    [
        'type' => 'fieldset',
        'className' => 'form_content_import_export',
        'list' => [
            [
                'type' => 'radio',
                'name' => 'fields_import[segment_type]',
                'value' => SegmentRepository::TYPE_PENDING,
                'label' => _l("Segment 'Shippingbo/Pending products'"),
                'checked' => $defaultDataImport['segment_type'] === SegmentRepository::TYPE_PENDING,
            ],
            [
                'type' => 'radio',
                'name' => 'fields_import[segment_type]',
                'value' => SegmentRepository::TYPE_DATE,
                'label' => _l("New segment 'Shippingbo/products from YYMMDD HHMMSS'"),
                'checked' => $defaultDataImport['segment_type'] === SegmentRepository::TYPE_DATE,
            ],
            [
                'type' => 'label',
                'label' => _l('New product names'),
                'className' => 'section',
            ],
            [
                'type' => 'radio',
                'name' => 'fields_import[product_name]',
                'value' => ImportData::PRODUCT_NAME_TYPE_SKU,
                'label' => "'"._l('product').'/'._l('batch').'/'._l('pack')."' + "._l('Logistic SKU'),
                'checked' => $defaultDataImport['product_name'] === ImportData::PRODUCT_NAME_TYPE_SKU,
            ],
            [
                'type' => 'radio',
                'name' => 'fields_import[product_name]',
                'value' => ImportData::PRODUCT_NAME_TYPE_TITLE,
                'label' => _l('Logistic title'),
                'checked' => $defaultDataImport['product_name'] === ImportData::PRODUCT_NAME_TYPE_TITLE,
            ],
            [
                'type' => 'label',
                'label' => _l('Fields to import'),
                'className' => 'section',
            ],
            [
                'type' => 'checkbox',
                'name' => 'fields_import[location]',
                'label' => _l('Location'),
                'checked' => (bool) $defaultDataImport['location'],
            ],
            [
                'type' => 'checkbox',
                'name' => 'fields_import[width]',
                'label' => _l('Width'),
                'checked' => (bool) $defaultDataImport['width'],
            ],
            [
                'type' => 'checkbox',
                'name' => 'fields_import[height]',
                'label' => _l('Height'),
                'checked' => (bool) $defaultDataImport['height'],
            ],
            [
                'type' => 'checkbox',
                'name' => 'fields_import[length]',
                'label' => _l('Length'),
                'checked' => (bool) $defaultDataImport['length'],
            ],
            [
                'type' => 'checkbox',
                'name' => 'fields_import[weight]',
                'label' => _l('Weight'),
                'checked' => (bool) $defaultDataImport['weight'],
            ],
        ],
    ],
    [
        'type' => 'label',
        'label' => '<a href="'.getScExternalLink('shippingbo_import_settings').'" target="_blank">'._l('How does import work ?').'</a>',
        'className' => 'message notice',
    ],
    [
        'type' => 'button',
        'name' => $process ? 'startImport' : 'save_import',
        'className' => 'save_btn',
        'value' => $process ? _l('Import Shippingbo data to Prestashop') : _l('Save'),
    ],
];

$shippingboService->sendResponse(json_encode($formImport));

?>