<?php
if (!defined('STORE_COMMANDER')) { exit; }

$action = Tools::getValue('action', null);
$languages = Language::getLanguages(false, false);
$default_id_lang = Configuration::get('PS_LANG_DEFAULT');

$UkooArrayContent = [];

$productsNotInEP = [];

if (!empty($action))
{
    switch ($action) {
        case 'redirect_bo' :
            $idLastProduct = Tools::getValue('id_product_ps');
            $responseGet = EveryPartsTools::callAPI('products/' . $idLastProduct, 'GET', $UkooArrayContent);
            $BOurl = 'https://' . UKOOPARTS_PANEL_DOMAIN . '/produits/' . $responseGet['response'][0]['id'];
            exit($BOurl);
        case 'insert':
            $product_ids = explode(',',Tools::getValue('product_id'));

            foreach ($product_ids as $product_id) {
                if (EveryPartsTools::CheckProductNotInEveryParts($product_id)) $productsNotInEP[] = $product_id;
                $UkooArrayContent = [
                    'modele_id' => Tools::getValue('model'),
                    'product_id' => $product_id,
                ];
                if (!empty(Tools::getValue('year'))) $UkooArrayContent['year'] = Tools::getValue('year');
                $response = EveryPartsTools::callAPI('compats/store', 'PUT', $UkooArrayContent, true);
            }
            break;
        case 'insert_with_period':
            $product_ids = explode(',',Tools::getValue('product_id'));
            $period = explode(',',Tools::getValue('period'));
            foreach ($product_ids as $product_id) {
                if (EveryPartsTools::CheckProductNotInEveryParts($product_id)) $productsNotInEP[] = $product_id;
                foreach ($period as $year) {
                    $ArrayOneCompat = [
                        'modele_id' => Tools::getValue('model'),
                        'product_id' => $product_id,
                        'year' => $year
                    ];
                    $UkooArrayContent['compats'][] = $ArrayOneCompat;
                }
                $response = EveryPartsTools::callAPI('compats/bulkstore', 'PUT', $UkooArrayContent, true);
            }
            break;
        case 'update':
            $nValue = Tools::getValue('value');
            $idRow = Tools::getValue('id_row');
            $idArray = explode('_', $idRow);
            // suppression
            $UkooArrayContent = ["modele_id" => $idArray[1], "product_id" => $idArray[0]];
            $UkooArrayContent["year"] = (!empty($idArray[2])) ? $idArray[2] : null;
            $responseDelete = EveryPartsTools::callAPI('compats', 'DELETE', $UkooArrayContent, true);

            // insertion
            $UkooArrayContent = [
                'modele_id' => $idArray[1],
                'product_id' => $idArray[0]
            ];
            if ($nValue!="") $UkooArrayContent['year'] = $nValue;
            $response = EveryPartsTools::callAPI('compats/store', 'PUT', $UkooArrayContent, true);
            break;
        case 'compat_delete':
            $idRows = Tools::getValue('compats');
            $compatsArray = explode(',', $idRows);
            $chunks_compats = array_chunk($compatsArray, 1000);
            foreach ($chunks_compats as $chunk) {
                $UkooArrayContent = [];
                foreach ($chunk as $idRow) {
                    $idArray = explode('_', $idRow);
                    $ArrayOneCompat = ["modele_id" => $idArray[1], "product_id" => $idArray[0]];
                    $ArrayOneCompat["year"] = (!empty($idArray[2])) ? $idArray[2] : null;
                    $UkooArrayContent['compats'][] = $ArrayOneCompat;
                }
                $response = EveryPartsTools::callAPI('compats/bulkdestroy', 'DELETE', $UkooArrayContent, true);
            }
            break;
        case 'paste': ## Copy/Paste compats from prop
            $ids = explode(',', Tools::getValue('ids_target'));
            $compats_source = explode(',', Tools::getValue('compat_source'));
            $chunks_cs = array_chunk($compats_source, 1000);
            foreach ($ids as $id_target) {
                if (EveryPartsTools::CheckProductNotInEveryParts($id_target)) $productsNotInEP[] = $id_target;
                foreach ($chunks_cs as $chunk_cs) {
                    $UkooArrayContent = [];
                    foreach ($chunk_cs as $compat_source) {
                        $compat_source = explode('_', $compat_source);
                        if ($id_target != $compat_source[0]) {
                            $ArrayOneCompat = ['modele_id' => $compat_source['1'], 'product_id' => $id_target];
                            if (!empty($compat_source['2'])) $ArrayOneCompat['year'] = $compat_source['2'];
                            $UkooArrayContent['compats'][] = $ArrayOneCompat;
                        }
                    }
                    $response = EveryPartsTools::callAPI('compats/bulkstore', 'PUT', $UkooArrayContent, true);
                }
            }
            break;
        case 'sync_database':
            $ModeleArray = [];
            $idLastProduct = Tools::getValue('id_product_ps');
            $responseGet = EveryPartsTools::callAPI('compats/products/' . $id_productsource, 'GET', $UkooArrayContent);
            if ($responseGet['meta']['code'] == 200) {
                foreach ($responseGet['response'][0] as $compat)
                    $ModeleArray[$compat['modele_id']] = $compat['modele_id'];
                foreach ($ModeleArray as $mod_id)
                    $UkooArrayContent = [
                        'scope' => "ModeleAssociation",
                        'onlyFor' => $mod_id
                    ];
                $response = EveryPartsTools::callAPI('export_to/prestashop', 'PUT', $UkooArrayContent, true);
            }
            break;
        case 'force_sync_product':
            $ids = explode(',', Tools::getValue('selection'));
            foreach ($ids as $id){
                EveryPartsTools::SyncProductInEveryParts($id);
            }
        break;
    }
    if ($response['meta']['code'] == 200) {
        if (!empty($productsNotInEP)) {
            exit(_l('Please use the sync button and redo your action !') . '<br>' . _l('Following products do not exist in EveryParts') . ':' . '<br>' . implode('<br>', $productsNotInEP));
        } else {
            exit('OK');
        }
    }
    elseif ($response['meta']['code'] == 400) {
        exit(_l('product not found').'<br>'._l('Please use the sync button and redo your action !'));
    }
    elseif ($response['meta']['code'] == 500) {
        exit('EveryParts API : Error 500');
    }
    else exit(_l(trim($response['meta']['errorDetails'])));
}
