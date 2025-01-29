<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Lib\Service\Locker\Entity\Locker;
use Sc\ScProcess\ScProcess;
use Sc\ScProcess\ScProcessCollection;
use Sc\Service\Shippingbo\Process\CollectData;
use Sc\Service\Shippingbo\Process\ImportData;
use Sc\Service\Shippingbo\Process\MatchData;
use Sc\Service\Shippingbo\Repository\StatsRepository;
use Sc\Service\Shippingbo\Shippingbo;

if (isset($_SERVER['HTTP_ACCEPT']) && strtolower($_SERVER['HTTP_ACCEPT']) === 'text/event-stream')
{
    session_write_close();
    header('Content-Type: text/event-stream');
    header('Content-Encoding: utf8');
    header('Cache-Control: no-cache');
    header('Access-Control-Allow-Origin: *');
    header('X-Accel-Buffering: no'); // désactive le buffer nginx
    ignore_user_abort(false); // Stops PHP from checking for user disconnect
}

$slowProcessTime = 1;
$shippingboService = Shippingbo::getInstance();
$shippingboService->switchToShopId((int) Tools::getValue('id_shop', Configuration::get('PS_SHOP_DEFAULT')));

$processCollection = new ScProcessCollection(Tools::getValue('start_process'), Tools::getValue('start_iteration'));
$locker = $shippingboService->getLocker(Shippingbo::PROCESS_LOCKER);
try {
    $shippingboService->checkSboAccount();

    if(Tools::isSubmit('kill')){
        $info = $locker->getRunningProcessInformation($slowProcessTime);
        if($info['canKill']){
            $locker->release();
            exit;
        } else {
            throw new Exception(_l('Only %s is allowed to kill this process', false [$info['ownerName']]));
        }
    }

    if($locker->getStatus() === Locker::STATUS_LOCKED){
        $processCollection->sendResponse('locked',$locker->getRunningProcessInformation($slowProcessTime));
        exit;
    }

    $locker->lock();


    $shippingboService->getLogger()->debug('[PROCESS INIT] Generate process collection');


    $lastCollectDate = $shippingboService->getCollectProcess()->getStartDate();
    /* ---------------------------------- */
    // [COLLECT] SBO DATA SINCE LAST SYNC
    /* ---------------------------------- */
    /* products */
    $collectProducts = new CollectData($shippingboService);
    $fetchProducts = new ScProcess($collectProducts);
    $fetchProducts->setStepName('fetching products');
    $fetchProducts->setMethod('get')
//                  ->setTotal($collectProducts->getTotalProducts())  // Trying to guess SBO Product number
                  ->setMethodArguments(['products', $lastCollectDate])
    ;

    /* packs */
    $fetchPacks = new ScProcess(new CollectData($shippingboService));
    $fetchPacks->setStepName('fetching packs');
    $fetchPacks->setMethod('get')
    ->setMethodArguments(['packs', $lastCollectDate]);

    /* AdditionalReferences */
    $fetchPackComponents = new ScProcess(new CollectData($shippingboService));
    $fetchPackComponents->setStepName('fetching pack components');
    $fetchPackComponents->setMethod('get')
    ->setMethodArguments(['pack_components', $lastCollectDate]);

    /* pack_components */
    $fetchAdditionalRefs = new ScProcess(new CollectData($shippingboService));
    $fetchAdditionalRefs->setStepName('fetching additionnal references');
    $fetchAdditionalRefs->setMethod('get')
    ->setMethodArguments(['additional_references', $lastCollectDate]);

    /* ---------------------------------- */
    // [MATCH] PS <-> SBO
    /* ---------------------------------- */
    /* remove unwanted relation due to suppression in PS */
    $match = new ScProcess(new MatchData($shippingboService));
    $match->setStepName('matching');
    $match->setMethod('start');

    $generateStats = new ScProcess(new StatsRepository($shippingboService));
    $generateStats->setStepName('Update stats');
    $generateStats->setMethod('getFullStats');


    /* ----------------------- */
    /* RUN */
    /* ----------------------- */
    $processCollection
        ->setLogger($shippingboService->getLogger())
        ->onComplete($shippingboService, 'onSyncComplete')
        ->add($fetchProducts)
        ->add($fetchPacks)
        ->add($fetchPackComponents)
        ->add($fetchAdditionalRefs)
        ->add($match)
        ->add($generateStats)
        ->run()
    ;

}
catch (Throwable $e){
    $processCollection->sendResponse('error', $e);

}
catch (Exception $e) {
    $processCollection->sendResponse('error', $e);
}
finally{
    $locker->release();
    exit;
}




