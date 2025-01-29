<?php


if (!defined('STORE_COMMANDER')) {
	exit;
}

use Sc\Service\Shippingbo\Entity\ShippingboAccount;
use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
$shippingboService->switchToShopId((int)Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULT')));

$shippingBoAccount = new ShippingboAccount(Tools::getValue('id_account'));

if (Tools::getValue('id_shop') && SCMS) {

    $formSboAccount = [
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
                    'type' => 'select',
                    'name' => 'id_account',
                    'options' => $shippingboService->getAllSboAccountsForSelect(),
                    'value' => $shippingboService->getSboAccount()->getId(),
                ],
                [
                    'type' => 'button',
                    'name' => 'save_sbo_account',
                    'value' => _l('Save'),
                    'className' => 'save_btn',
                ],
            ],
        ],
    ];
} else {
    $formSboAccount = [
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
                    'type' => 'hidden',
                    'name' => 'id_account',
                    'value' => Tools::getValue('id_account', 0),
                ],
                [
                    'type' => 'input',
                    'name' => 'name',
                    'label' => _l('Name', 1),
                    'value' => $shippingBoAccount->getName(),
                ],
                [
                    'type' => 'label',
                    'name' => 'error_area',
                    'hidden' => true,
                    'label' => '',
                    'className' => 'message error',
                ],
                [
                    'type' => 'input',
                    'name' => 'apiUrl',
                    'label' => _l('API url', 1),
                    'value' => $shippingBoAccount->getApiUrl(),
                ],
                [
                    'type' => 'input',
                    'name' => 'apiUser',
                    'label' => _l('API user', 1),
                    'value' => $shippingBoAccount->getApiUser(),
                ],
                [
                    'type' => 'password',
                    'name' => 'apiToken',
                    'label' => _l('API token', 1),
                    'value' => $shippingBoAccount->getApiToken(),
                ],
                [
                    'type' => 'input',
                    'name' => 'apiVersion',
                    'label' => _l('API version', 1),
                    'value' => $shippingBoAccount->getApiVersion(),
                ],
                [
                    'type' => 'label',
                    'label' => '<a href="'.getScExternalLink('shippingbo_compte_api').'" target="_blank">' . _l('Where do I find this information?') . '</a>',
                    'className' => 'message notice',
                ],
                'delete' => [
                    'type' => 'button',
                    'name' => 'delete_sbo_account',
                    'value' => _l('Delete configuration'),
                    'className' => 'delete_btn',
                ],
                [
                    'type' => 'button',
                    'name' => 'save_sbo_account',
                    'value' => _l('Save'),
                    'className' => 'save_btn',
                ],
            ],
        ],
    ];

    if (count($shippingboService->getAllSboAccounts()) < 2) {
        unset($formSboAccount[1]['list']['delete']);
    }
    $formSboAccount[1]['list'] = array_values($formSboAccount[1]['list']);
}


$shippingboService->sendResponse(json_encode($formSboAccount));

