<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}
use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
if ($shippingboService->getScAgent()->getIdProfile() !== 1)
{
    exit;
}

$defaultSelection = (int) Tools::getValue('defaultSelection');

$allAccounts = $shippingboService->getAllSboAccounts();

if(!$defaultSelection) {
    $accountIdSelectionForCurrentShop = $shippingboService->getSboAccountsIdByShopId((int) Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULT')));
    if(!empty($accountIdSelectionForCurrentShop)) {
        $accountIdSelectionForCurrentShop = array_column($accountIdSelectionForCurrentShop, 'value');
        $defaultSelection = (int)$accountIdSelectionForCurrentShop[0];
    }
}

if (!empty($allAccounts))
{
    if (!$defaultSelection)
    {
        $defaultSelection = (int) $allAccounts[0]['id_account'];
    }

    ## id required for builindg list
    foreach ($allAccounts as &$account)
    {
        $account['id'] = (int) $account['id_account'];
    }
}

## add option to add configuration
$allAccounts[] = [
    'id' => 'add',
    'id_account' => 'add',
    'name' => ucfirst(_l('add account')),
];

$shippingboService->sendResponse('', [
    'selection' => $defaultSelection,
    'accounts' => $allAccounts,
]);
