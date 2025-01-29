<?php

// TODO 1 : barre de progression pour l'import
if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
$shippingboService->switchToShopId((int) Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULT')));

$fieldsToImport = Tools::getValue('fields_import');
extract($fieldsToImport);
try
{
    $shippingboService
        ->getImportProcess()
        ->setSegmentType($segment_type)
        ->setProductNameType($product_name)
        ->setFieldsToImport([
            'location' => (bool) $location,
            'width' => (bool) $width,
            'height' => (bool) $height,
            'length' => (bool) $length,
            'weight' => (bool) $weight,
        ])
        ->startImport();
}
catch (Exception $e)
{
    $shippingboService->addError($e);
}

$shippingboService->sendResponse();
