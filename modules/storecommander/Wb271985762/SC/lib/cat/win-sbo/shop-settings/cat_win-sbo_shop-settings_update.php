<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Repository\Prestashop\SegmentRepository;
use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
$shop = (object) Shop::getShop(Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULT')));

if (!SCMS)
{
//    $shippingboService->setConfig(['importToShop' => Configuration::get('PS_SHOP_DEFAULT')]);
    $shop = (object) Shop::getShop(Configuration::get('PS_SHOP_DEFAULT'));
}


$shippingboService->switchToShopId($shop->id_shop);

$section = Tools::getValue('section', null);

try
{
    switch($section) {
        case 'enable_sync':
            $enableSync = filter_var(Tools::getValue('value', false), FILTER_VALIDATE_BOOLEAN);
            if($shippingboService->setConfig(['allowSync' => $enableSync])){
            if($enableSync){
                $message = _l('Shop synchronization enabled');

            } else {
                $message = _l('Shop synchronization disabled');
            }
            if(!$shippingboService->getConfigValue('lastSyncedAt')){
                $shippingboService->setConfig(['lastSyncedAt' => null]);
            }
                $shippingboService->sendResponse($message, ['shopSyncNow' => $enableSync]);

            }
            break;
        case 'shops':
            if($shippingboService->getSboAccount()->getId() && $shippingboService->getSboAccount()->getId() != Tools::getValue('id_account')){
                $shippingboService->removeSboConfiguration($shippingboService->getIdShop());
                $shippingboService->getShopRelationRepository()->removeAllRelations($shippingboService->getIdShop());
            }
            $shippingboService->setConfig([
                'id_sbo_account' => Tools::getValue('id_account'),
            ]);

            // conversion unit
            $unitCoefficientConfig = Tools::getValue('coeff', null);
            if (!$unitCoefficientConfig) {
                throw new Exception(_l('Missing conversion unit settings'));
            }

            $params = [
                'unitConversion' => json_encode($unitCoefficientConfig),
            ];
            $shippingboService->setConfig($params);

            // import
            $params = [
                'defaultDataImport' => json_encode(Tools::getValue('fields_import', $shippingboService->getDefaultDataImport())),
            ];
            $shippingboService->setConfig($params);

            // export
            $params = [
                'defaultDataExport' => json_encode(Tools::getValue('fields_export', $shippingboService->getDefaultDataExport())),
            ];

            $shippingboService->setConfig($params);

            // Validation
            if ($shippingboService->checkConfig('unitConversion') && $shippingboService->checkConfig('defaultDataImport') && $shippingboService->checkConfig('defaultDataExport')) {
                $shippingboService->sendResponse(_l('Configuration saved'));
            }
            break;
        case 'complete':
            if ($shippingboService->checkConfig('unitConversion') && $shippingboService->checkConfig('defaultDataImport') && $shippingboService->checkConfig('defaultDataExport')) {
                $shippingboService->setFirstStart(false);
                $shippingboService->sendResponse(_l('Initial setup completed'));
            }
            break;
        case 'delete_shop_configuration';
            $shippingboService->removeSboConfiguration($shippingboService->getIdShop());
            $shippingboService->getShopRelationRepository()->removeAllRelations($shippingboService->getIdShop());
            $shippingboService->sendResponse(_l('Configuration deleted'));
            break;
    }
}
catch (Exception $e)
{
    $shippingboService->addError($e);
    $shippingboService->sendResponse();
}
