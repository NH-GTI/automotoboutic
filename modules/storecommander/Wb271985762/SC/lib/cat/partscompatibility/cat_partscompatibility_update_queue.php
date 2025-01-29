<?php
if (!defined('STORE_COMMANDER')) { exit; }

error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);

## Copy/Paste compats from cat_product_grid (view references)
if (isset($_POST['parts_compatibilities']) && substr(Tools::getValue('parts_compatibilities'), 0, 22) == 'parts_compatibilities_')
{
    $prefixlen = strlen('parts_compatibilities_');
    $id_productsource = (int) substr(Tools::getValue('parts_compatibilities'), $prefixlen, strlen(Tools::getValue('parts_compatibilities')));

    if (!EveryPartsTools::CheckProductNotInEveryParts($id_product)) {
        if ($id_productsource != $id_product) {

            $response = EveryPartsTools::callAPI('compats/products/' . $id_productsource, 'GET', []);
            if ($response['meta']['code'] == 200) {
                $compatibilities = $response['response'][0];
                $chunks = array_chunk($compatibilities, 1000);

                foreach ($chunks as $chunk) {
                    $bulkInsertArray = [];

                    foreach ($chunk as $compat) {
                        $ArrayOneCompat = ['modele_id' => $compat['modele_id'], 'product_id' => $id_product];
                        if (!empty($compat['year'])) $ArrayOneCompat['year'] = $compat['year'];

                        $bulkInsertArray['compats'][] = $ArrayOneCompat;
                    }
                    $responseInsert = EveryPartsTools::callAPI('compats/bulkstore', 'PUT', $bulkInsertArray, true);
                }
            }
        }
    }
    else {
        //exit(_l('Please use the sync button and redo your action !'));
        $return_datas['displayErrorMessage'] = _l('This product does not exist in EveryParts').' : '.$id_product.'<br>'._l('Please use the sync button and redo your action !');
    }
}


