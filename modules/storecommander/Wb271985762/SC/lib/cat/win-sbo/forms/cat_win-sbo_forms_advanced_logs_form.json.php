<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
$shippingboService->switchToShopId((int) Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULT')));
try {
    $shippingBoConfig = $shippingboService->getConfig();
} catch(Exception $e){
    $shippingBoConfig = [];
}


$formLogs = [
    [
        'type' => 'settings',
        'position' => 'label-left',
        'labelAlign' => 'left',
    ],
    [
        'type' => 'block',
        'className' => 'sbo_settings_forms',
        'list' => [
                [
                    'type' => 'input',
                    'name' => 'logFilesToKeep',
                    'label' => _l('Log keep threshold (days)', 1),
                    'value' => $shippingBoConfig['logFilesToKeep']['value'],
                ],

                [
                    'type' => 'button',
                    'name' => 'save_logs',
                    'value' => _l('Save'),
                    'className' => 'save_btn',
                ],
            ],
    ],
];
$shippingboService->sendResponse(json_encode($formLogs));
