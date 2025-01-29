<?php

use Sc\Service\Shippingbo\Shippingbo;
use Sc\Service\Shippingbo\Repository\Prestashop\SegmentRepository;

if (!defined('STORE_COMMANDER'))
{
    exit;
}
$pdo = Db::getInstance()->getLink();
$successMessage = _l('Done');
$response = ['state' => true, 'extra' => ['code' => 200, 'message' => $successMessage]];
/** @var Shippingbo $shippingboService */
$shippingboService = Shippingbo::getInstance();
$shippingboService->switchToShopId((int) Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULT')));
$options = [];
try
{
    switch(Tools::getValue('action')) {
        case 'is_locked':
            if(Tools::isSubmit('id_sbo') && (int)Tools::getValue('id_sbo') > 0){
                $sboShopRelations = $shippingboService->getShopRelationRepository()->getAllByIdSbo(Tools::getValue('id_sbo', 0));
            } elseif((Tools::isSubmit('id_product')) && (int)Tools::getValue('id_product') > 0) {
                $sboShopRelations = $shippingboService->getShopRelationRepository()->getAllByProductId(Tools::getValue('id_product', 0));
            } else {
                throw new Exception('bad request : missing parameters');
            }
            foreach($sboShopRelations as $sboShopRelation){
                $sboShopRelation
                    ->setIsLocked((bool) Tools::getValue('value'))
                    ->save();
            }
            break;
        case 'reference':
            if (Tools::getValue('id_product_attribute') != 0)
            {
                $combination = new Combination((int) Tools::getValue('id_product_attribute'), null, $shippingboService->getIdShop());
                $combination->id_product = Tools::getValue('id_product', 0);
                $combination->reference = Tools::getValue('value', 0);
                if (!$combination->minimal_quantity)
                {
                    $combination->minimal_quantity = 1;
                }
                $combination->save();
            }
            else
            {
                $product = new Product(Tools::getValue('id_product', 0), false, $shippingboService->getScAgent()->getIdLang(), [$shippingboService->getIdShop()]);
                $product->reference = Tools::getValue('value', 0);
                if (!$product->price)
                {
                    $product->price = 0;
                }
                $product->save();
            }
            break;
        case 'export_to_segment':
            $productList = Tools::getValue('segment_item_list');
            $segmentName = Tools::getValue('segment_name');
            if(!$productList || !$segmentName){
                throw new Exception(_l('Invalid param'));
            }

            $idSegmentParent = (int)SegmentRepository::getRootSegmentId();
            if(!$idSegmentParent) {
                throw new Exception('parent segment not found');
            }
            $segment = new ScSegment();
            $segment->id_parent = $idSegmentParent;
            $segment->name = (string) $segmentName;
            $segment->type = 'manual';
            $segment->access = '-catalog-';
            if (!$segment->add()) {
                throw new Exception(_l('Unable to save segment'));
            }

            $idSegment = (int) $segment->id;
            $sql = 'INSERT INTO '._DB_PREFIX_.'sc_segment_element (`id_segment`, `id_element`, `type_element`) VALUES ';
            $valueSql = [];
            foreach (explode(',', $productList) as $id_product)
            {
                $valueSql[] = '('.(int)$idSegment.', '.(int)$id_product.', "product")';
            }
            $sql .= implode(', ', $valueSql);

            if(!Db::getInstance()->execute($sql)) {
                throw new Exception(_l('Unable to add data to final table. Please contact our support.').' '.Db::getInstance()->getMsgError());
            }
            $options = ['callback' => ['functionName' => 'refreshCatTree']];
            $successMessage = _l('Segment and items saved');
            break;
        default:
    }
}
catch (Exception $e)
{
    $shippingboService->addError($e);
}
finally
{
    $shippingboService->sendResponse($successMessage, $options);
}
