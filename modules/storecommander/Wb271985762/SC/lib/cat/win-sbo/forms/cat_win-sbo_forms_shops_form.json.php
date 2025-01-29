<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Process\ImportData;
use Sc\Service\Shippingbo\Repository\Prestashop\SegmentRepository;
use Sc\Service\Shippingbo\Shippingbo;

// COMMON
$shippingboService = Shippingbo::getInstance();
$shippingboService->switchToShopId((int) Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULTS')));
$shippingBoAccount = $shippingboService->getSboAccount();
// IMPORT
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

// EXPORT
$defaultDataExport = (array) json_decode($shippingboService->getConfigValue('defaultDataExport'));

// UNIT CONVERSION
$defaultUnits = json_decode($shippingboService->getConfigValue('unitConversion'), true);

$formAll = [
    'init' => [
        'type' => 'settings',
        'position' => 'label-left',
        'labelAlign' => 'left',
    ],
    // SBO ACCOUNT MS
    'sbo_account' => [
        'type' => 'fieldset',
        'label' => _l('%s Access', false, ['Shippingbo']),
        'className' => 'sbo_settings_forms formApi',
        'list' => [
            [
                'type' => 'select',
                'name' => 'id_account',
                'options' => $shippingboService->getAllSboAccountsForSelect(),
                'value' => $shippingboService->getSboAccount()->getId(),
            ],
        ],
    ],
    // IMPORT
    'import' => [
        'type' => 'fieldset',
        'name' => 'import_section',
        'label' => _l('Import settings').' : '.'Shippingbo &rarr; Prestashop',
        'id' => 'import',
        'className' => 'formImport',
        'list' => [
            [
                'type' => 'settings',
                'position' => 'label-right',
            ],
            [
                'type' => 'block',
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
                'label' => '<a  href="'.getScExternalLink('shippingbo_import_settings').'" target="_blank">'._l('How does import work ?').'</a>',
                'className' => 'message notice',
            ],
        ],
    ],
    // EXPORT
    'export' => [
        'type' => 'fieldset',
        'name' => 'export_section',
        'label' => _l('Export settings').' : '.'Prestashop &rarr; Shippingbo',
        'id' => 'export',
        'className' => 'formExport',
        'list' => [
            [
                'type' => 'settings',
                'position' => 'label-right',
                'labelAlign' => 'left',
            ],
            [
                'type' => 'block',
                'className' => 'form_content_import_export',
                'list' => [
                    [
                        'type' => 'block',
                        'className' => 'sbo_settings_forms',
                        'list' => getShippingboExportFields($defaultDataExport),
                    ],
                ],
            ],
            [
                'type' => 'label',
                'label' => '<a href="'.getScExternalLink('shippingbo_export_settings').'" target="_blank">'._l('How does export work?').'</a>',
                'className' => 'message notice',
            ],
        ],
    ],
    // UNIT CONVERSION
    'unit' => [
        'type' => 'fieldset',
        'name' => 'unit_conversion_section',
        'label' => _l('Unit conversion'),
        'id' => 'unit_conversion',
        'className' => 'formUnitConversion',
        'list' => [
            [
                'type' => 'input',
                'label' => _l('Weight coefficient').' ('.'1'.Configuration::get('PS_WEIGHT_UNIT', null, null, Configuration::get('PS_SHOP_DEFAULT')).'=<b>X</b>g'.')',
                'name' => 'coeff[weight]',
                'value' => $defaultUnits['weight'] ?: 1000,
            ],
            [
                'type' => 'input',
                'label' => _l('Dimension coefficient').' ('.'1'.Configuration::get('PS_DIMENSION_UNIT', null, null, Configuration::get('PS_SHOP_DEFAULT')).'=<b>X</b>mm'.')',
                'name' => 'coeff[dimension]',
                'value' => $defaultUnits['dimension'] ?: 10,
            ],
        ],
    ],
    'save' => [
        'type' => 'block',
        'id' => 'end_form',
        'className' => 'endForm withDeleteButton',
        'list' => [
            'delete' => [
                'type' => 'button',
                'name' => 'delete_shop_configuration',
                'value' => _l('Delete configuration'),
                'className' => 'delete_btn',
            ],
            [
                'type' => 'button',
                'name' => 'save_shops',
                'className' => 'save_btn',
                'value' => _l('Save'),
            ],
        ],
    ],
];

if (empty((int) $shippingboService->getConfigValue('id_sbo_account')))
{
    unset($formAll['save']['list']['delete']);
}

$formAll['save']['list'] = array_values($formAll['save']['list']);

function getShippingboExportFields($defaultDataExport)
{
    $fields = [];
    foreach ($defaultDataExport as $name => $value)
    {
        $fields[] = [
            'type' => 'checkbox',
            'name' => 'fields_export['.$name.']',
            'label' => ucfirst(_l($name)),
            'checked' => (bool) $defaultDataExport[$name],
            'disabled' => $name === 'userRef',
        ];
    }

    return $fields;
}

$shippingboService->sendResponse(json_encode(array_values($formAll)));
