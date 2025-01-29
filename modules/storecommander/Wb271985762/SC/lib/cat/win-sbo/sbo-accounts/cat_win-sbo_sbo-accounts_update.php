<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Entity\ShippingboAccount;
use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();

$action = Tools::getValue('action');
try
{
    $sboAccount = new ShippingboAccount(Tools::getValue('id_account', null));
    switch ($action) {
        case 'save':

            $sboAccount
                ->setApiUrl(Tools::getValue('apiUrl'))
                ->setName(Tools::getValue('name'))
                ->setApiUser(Tools::getValue('apiUser'))
                ->setApiToken(Tools::getValue('apiToken'))
                ->setApiVersion(Tools::getValue('apiVersion'))
                ->setCreatedAt(date('Y-m-d H:i:s'))
                ->setUpdatedAt(date('Y-m-d H:i:s'))
            ;
            if($sboAccount->valid()){
                $sboAccount->save();
            }
            $successMessage = _l('API settings saved');
            break;
        case 'delete':
            if (count($shippingboService->getAllSboAccounts()) === 1) {
                throw new Exception('Unable to delete Shippingbo account, you must have at least one configured !');
            }
            if ($sboAccount->delete())
            {
                $successMessage = _l('API settings deleted');
            }
            break;
    }
}
catch (Exception $e)
{
    $shippingboService->addError($e, false);
}
finally {
    $shippingboService->sendResponse($successMessage);
}
