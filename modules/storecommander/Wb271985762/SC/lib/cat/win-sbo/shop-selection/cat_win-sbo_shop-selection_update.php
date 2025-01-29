<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Entity\ShippingboAccount;
use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
$shopSelection = Tools::getValue('shop_selection');
$extra = [];

try
{
    $sboAccountFound = $shippingboService->getAllSboAccounts();
    if(!$sboAccountFound) {
        throw new Exception(_l('No account found. %sPlease go back and create one%s.',null, ['<a href="javascript:wSboTabClick(\'initial-setup\');">', '</a>']));
    }

    $sboAccount = new ShippingboAccount((int)$sboAccountFound[0]['id_account']);

    foreach ($shopSelection as $shopId => $isChecked)
    {
        $shippingboService->switchToShopId($shopId);
        if ((int)$isChecked) {
            if(!isset($extra['firstShopSelected'])) {
                $extra['firstShopSelected'] = (int)$shopId;
            }
            $shippingboService->setSboAccount($sboAccount);
            $shippingboService->setConfig($shippingboService->getConfig());
            $shippingboService->setConfig(['allowSync' => true]);
        } else {
            $shippingboService->unlinkSboAccount($sboAccount); ## si pas selectionne on retire
        }
    }
}
catch (Exception $e)
{
    $shippingboService->addError($e);
}

$shippingboService->sendResponse(_l('Shop selection saved'), $extra);
