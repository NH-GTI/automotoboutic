<?php

if (!defined('STORE_COMMANDER'))
{
	exit;
}

use Sc\Service\Shippingbo\Repository\Prestashop\SegmentRepository;
use Sc\Service\Shippingbo\Entity\ShippingboAccount;
use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
$shop = (object) Shop::getShop(Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULTS')));

if (!SCMS)
{
//    $shippingboService->setConfig(['importToShop' => Configuration::get('PS_SHOP_DEFAULT')]);
	$shop = (object) Shop::getShop(Configuration::get('PS_SHOP_DEFAULT'));
}

$shippingboService->switchToShopId($shop->id_shop);

$successMessage = null;

// ACCOUNT

try {
	$sboAccount = new ShippingboAccount(Tools::getValue('id_account', null));
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

	$params = [
		'id_sbo_account' => json_encode((int)$sboAccount->getId()),
	];
	$shippingboService->setConfig($params);

    if(!SCMS) {
        // IMPORT
        $params = [
            'defaultDataImport' => json_encode(Tools::getValue('fields_import', $shippingboService->getDefaultDataImport())),
        ];

        $shippingboService->setConfig($params);

        // EXPORT
        $params = [
            'defaultDataExport' => json_encode(Tools::getValue('fields_export', $shippingboService->getDefaultDataExport())),
        ];

        $shippingboService->setConfig($params);

        // UNIT CONVERSION
        $unitCoefficientConfig = Tools::getValue('coeff', null);
        if (!$unitCoefficientConfig) {
            throw new Exception(_l('Missing conversion unit settings'));
        }

        $params = [
            'unitConversion' => json_encode($unitCoefficientConfig),
        ];

        $shippingboService->setConfig($params);
    }

	// Validation
	if (!SCMS) {
		$shippingboService->checkInitialConfiguration();
		$shippingboService->setFirstStart(false);
		$successMessage = _l('Configuration saved');
	}
	else {
		$shippingboService->checkSboAccount();
		$successMessage = _l('Sbo account saved');
	}
}
catch (Exception $e)
{
    $shippingboService->addError($e,false);
}
finally {
	$shippingboService->sendResponse($successMessage);
}
