<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();

$defaultShopSelection = Tools::getValue('defaultShopSelection');

$allShopsWithSboConfig = $shippingboService->getShopsWithConfig();

$shopsAssociated = $shippingboService->getSboAccount()->getShopIds();


if (!empty($allShopsWithSboConfig)) {

    foreach ($allShopsWithSboConfig as $key => &$shop) {
        if ($shippingboService->isFirstStart() && !in_array($shop['id_shop'], $shopsAssociated)) {
            unset($allShopsWithSboConfig[$key]);
        }
        $shop['id'] = (int)$shop['id_shop'];
    }

    ## condition après, car il faut selectionner uniquement un shop disponible
    if (!$defaultShopSelection) {
        $defaultShopSelection = (int)$allShopsWithSboConfig[0]['id_shop'];
    }
}

$shippingboService->sendResponse('', [
    'selection' => $defaultShopSelection,
    'shops' => array_values($allShopsWithSboConfig),
]);