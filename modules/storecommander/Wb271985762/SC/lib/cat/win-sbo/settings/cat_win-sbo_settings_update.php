<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Repository\Prestashop\SegmentRepository;
use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
$section = Tools::getValue('section', null);

$id_shop = Tools::getValue('id_shop', (int) Configuration::get('PS_SHOP_DEFAULT'));
$id_shop === 0?null:$id_shop;

$id_sbo_account = Tools::getValue('id_sbo_account', null);
$id_sbo_account === 0?null:$id_sbo_account;


$successMessage = null;
$returnExtra = [];
try
{
    switch($section){

        case 'logs':
            // TODO 2 : a déplacer au niveau du service
            $params = [
                'logFilesToKeep' => (int)Tools::getValue('logFilesToKeep', 10),
//                'debugLevel' => (int)Tools::getValue('debugLevel', LOG_DEBUG),
            ];


            $shippingboService->setConfig($params);
            if ($shippingboService->checkConfig('logFilesToKeep')) {
                $successMessage = _l('Logs settings saved');
            }
            break;

        case 'save_advanced_db_shop':

            // SHOP
            if (Tools::getValue('safe_remove_shop_relations', null))
            {

                if(Tools::getValue('remove_all_shop_relations', null)){
                    $shippingboService->getLogger()->debug('Removing all relation information for shop #'.$id_shop);
                    $shippingboService->getShopRelationRepository()->removeAllRelations($id_shop);
                    $successMessage = _l('All relation information removed for shop #%s',null, [$id_shop]);

                } else {
                    $shippingboService->getLogger()->debug('Clearing Sbo relation for shop #'.$id_shop);
                    $shippingboService->getShopRelationRepository()->removeRelations($id_shop);
                    $successMessage = _l('Sbo relation cleared for shop #%s',null, [$id_shop]);

                }
            }

            if(Tools::getValue('clear_sbo_segment')){
                $shippingboService->getLogger()->debug('Clearing Sbo segments and removing related products for shop #'.$id_shop);
                SegmentRepository::clearSboSegment($id_shop);
                $successMessage = _l('Sbo segments and related products removed for shop #%s',null, [$id_shop]);
                $returnExtra = ['code' => 205, 'callback' => ['functionName' => 'refreshCatTree']];
            }
            break;
        case 'save_advanced_db_account':
            // SBO ACOUNT
            if (Tools::getValue('clear_sbo_buffer', null))
            {

                $shippingboService->getLogger()->debug('Clearing Sbo buffer tables #'.$id_sbo_account);
                $shippingboService->resetLastSyncedAtForSboAccount($id_sbo_account);
                $shippingboService->getProductRepository()->clear($id_sbo_account);
                $shippingboService->getBatchRepository()->clear($id_sbo_account);
                $shippingboService->getPackRepository()->clear($id_sbo_account);
                $successMessage = _l('Sbo buffer tables cleared for Sbo account #%s', null, [$id_sbo_account]);

            }

            if(Tools::getValue('clear_sbo_service', null)){
                $shippingboService->getLogger()->debug('Clearing Sbo service #'.$id_sbo_account);
                $shippingboService->getShopRelationRepository()->removeAllRelationsForSboAccountId($id_sbo_account);
                $shippingboService->removeSboAccount($id_sbo_account);
                $shippingboService->getProductRepository()->clear($id_sbo_account);
                $shippingboService->getBatchRepository()->clear($id_sbo_account);
                $shippingboService->getPackRepository()->clear($id_sbo_account);
                $shippingboService->removeSboConfiguration($id_shop);

                $successMessage = _l('Sbo service cleared for Sbo account #%s', null, [$id_sbo_account]);
                $shippingboService->setFirstStart(true);
                if(!$id_sbo_account){
                $shippingboService->sendResponse(_l('To configure the Shippingbo service, please open the Shippingbo Management window'), [
                    'callback' => [
                        'functionName' => 'dhxMenu.callEvent',
                        'params' => [
                            'onClick', ['cat_sbo'],
                        ],
                    ],
                    'code' => 205,
                ]);
                }

            }
            break;
        case 'save_advanced_db_connector':
            // SBO ACOUNT
            if (Tools::getValue('reset_connector', null))
            {
                $shippingboService->getLogger()->debug('Reset Sbo connector');
                $shippingboService->dropTables();
                $shippingboService->removeSboConfiguration();
                foreach($shippingboService->getLocker() as $locker){
                    $shippingboService->removeLocker($locker);
                }
                $successMessage = _l('Sbo connector have been reset');
                $shippingboService->setFirstStart(true);

                $shippingboService->sendResponse(_l('To configure the Shippingbo service, please open the Shippingbo Management window'), [
                    'callback' => [
                        'functionName' => 'dhxMenu.callEvent',
                        'params' => [
                            'onClick', ['cat_sbo'],
                        ],
                    ],
                    'code' => 205,
                ]);

            }

            break;
    }

}
catch (Exception $e)
{
    $shippingboService->addError($e);
}

$shippingboService->sendResponse($successMessage,$returnExtra);
