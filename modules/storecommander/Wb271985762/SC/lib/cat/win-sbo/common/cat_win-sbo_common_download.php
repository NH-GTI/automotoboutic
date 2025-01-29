<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Shippingbo;

$pdo = Db::getInstance()->getLink();
$shippingboService = Shippingbo::getInstance();
$shippingboService->switchToShopId((int) Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULT')));

try
{
    $shopsUnitConversion = json_decode($shippingboService->getConfigValue('unitConversion'), true);
    $date = $shippingboService->getLocaleDate(new DateTimeImmutable('now'), 'yyyy_MM_dd_H_mm_ss');
    $sboType = Tools::getValue('sboType', null);

    switch ($sboType) {
        case Shippingbo::SBO_PRODUCT_TYPE_PACK_COMPONENT:
            $repository = $shippingboService->getPackRepository();
            $filename = _l('pack components');
            break;
        case Shippingbo::SBO_PRODUCT_TYPE_PACK:
            $repository = $shippingboService->getPackRepository();
            $filename = _l('packs');
            break;
        case Shippingbo::SBO_PRODUCT_TYPE_BATCH:
            $repository = $shippingboService->getBatchRepository();
            $filename = _l('batches');
            break;
        default:
            $repository = $shippingboService->getProductRepository();
            $filename = _l('products');
    }
    $filename = str_replace(' ', '_', $filename);

    if ($sboType === Shippingbo::SBO_PRODUCT_TYPE_PACK_COMPONENT)
    {
        $columns = $repository->getExportComponentsColumns();
        $query = $repository->getMissingComponentsSboQuery();
        $sboDiffStatement = $pdo->prepare($query);

        $sboDiffStatement->execute([
            ':id_shop' => $shippingboService->getIdShop(),
        ]);
    }
    else
    {
        $columns = $repository->getExportColumns();
        $baseQuery = $repository->getMissingSboQuery(true);
        $sboDiffStatement = $pdo->prepare($repository->addSboErrorParts($baseQuery));
        $params = [
            ':id_lang' => $shippingboService->getScAgent()->getIdLang(),
            ':id_shop' => $shippingboService->getIdShop(),
            ':is_locked' => false,
            ':has_error' => false,
        ];
        $sboDiffStatement->execute($params);
    }

    $filteredColumns = array_filter($columns);

    $fp = fopen('php://output', 'wb');
    if (!$fp)
    {
        throw new \Exception('Unable to read php output');
    }
    fputcsv($fp, $filteredColumns);
    if (!($lines = $sboDiffStatement->fetchAll(PDO::FETCH_ASSOC)))
    {
        throw new \Exception('Unable to read php output');
    }
    foreach ($lines as $line)
    {
        $values = [];
        $columnsIndexes = array_flip($filteredColumns);
        if (empty($columnsIndexes))
        {
            exit('No fields to export, please verify your export configuration in Settings');
        }
        foreach ($filteredColumns as $colName)
        {
            $index = isset($columnsIndexes[$colName]) ? $columnsIndexes[$colName] : false;
            switch ($colName) {
                case 'product_id': // batch
                    $values[$index] = $line['id_component_sbo'] ?: _l('Product is missing, please try first to import missing products file to Shippingbo');
                    break;
                case 'matched_quantity': // batch
                    $values[$index] = $line['quantity'];
                    break;
                case 'order_item_value': // batch
                    $values[$index] = $line['reference'];
                    break;
                case 'userRef': // product/pack
                    $values[$index] = $line['reference'];
                    break;
                case 'ean13': // product/pack
                    $values[$index] = $line['ean13'];
                    break;
                case 'title': // product/pack
                    $name = $line['name'];
                    if ($line['combination_name'])
                    {
                        $name .= ' - '.$line['combination_name'];
                    }
                    $values[$index] = $name;
                    break;
                case 'location': // product
                    $values[$index] = $line['location'];
                    break;
                case 'pictureUrl': // product/pack
                    $values[$index] = $line['id_image'] ? Tools::getShopDomainSsl(true).__PS_BASE_URI__.'img/p/'.getImgPath($line['id_product'], $line['id_image'], $size = '', $format = 'jpg') : '';
                    break;
                case 'weight': // product
                    $values[$index] = $line['weight'] * $shopsUnitConversion['weight'];
                    break;
                case 'height': // product
                    $values[$index] = $line['height'] * $shopsUnitConversion['dimension'];
                    break;
                case 'length': // product
                    $values[$index] = $line['length'] * $shopsUnitConversion['dimension'];
                    break;
                case 'width': // product
                    $values[$index] = $line['width'] * $shopsUnitConversion['dimension'];
                    break;
                case 'pack_product_ref': // pack
                    $values[$index] = $line['pack_product_ref'];
                    break;
                case 'component_product_ref': // pack
                    $values[$index] = $line['component_product_ref'];
                    break;
                case 'quantity': // pack
                    $values[$index] = $line['quantity'];
                    break;
                default:
                    $values[$index] = '';
            }
        }

        fputcsv($fp, array_values($values));
    }
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="missing_sbo_'.$filename.'_'.$date.'.csv"');

    fclose($fp);
}
catch (Exception $e)
{
    $shippingboService->sendResponse($e->getMessage());
}
