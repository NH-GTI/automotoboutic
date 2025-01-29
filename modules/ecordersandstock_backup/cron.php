<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from SARL Ether Création
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the SARL Ether Création is strictly forbidden.
 * In order to obtain a license, please contact us: contact@ethercreation.com
 * ...........................................................................
 * INFORMATION SUR LA LICENCE D'UTILISATION
 *
 * L'utilisation de ce fichier source est soumise a une licence commerciale
 * concedee par la societe Ether Création
 * Toute utilisation, reproduction, modification ou distribution du present
 * fichier source sans contrat de licence ecrit de la part de la SARL Ether Création est
 * expressement interdite.
 * Pour obtenir une licence, veuillez contacter la SARL Ether Création a l'adresse: contact@ethercreation.com
 * ...........................................................................
 *  @package     ecordersandstock
 *  @author      Alec Page
 *  @copyright   Copyright (c) 2010-2018 S.A.R.L Ether Création (http://www.ethercreation.com)
 *  @license     Commercial license
 */

require_once dirname(__FILE__) . '/../../config/config.inc.php';
require_once dirname(__FILE__) . '/ecordersandstock.php';
require_once dirname(__FILE__) . '/bigjson.php';
$mod = new EcOrdersAndStock();
ignore_user_abort(true);
set_time_limit(0);

$token = Tools::getValue('ecoas_token', '1');
if ($token != EcOrdersAndStock::getConfigValue('ecoas_token')) {
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    header('Location: ../');
    exit();
}

$prefix = 'ecoas_';
$logger = EcOrdersAndStock::logStart('ecoascron');

//set_error_handler("exception_error_handler");

// parameters
$connecteur = 'none';
$suffix = '_'.$connecteur;

$paramNbCron = Tools::getValue('nbC', null);
$nbCron = is_null($paramNbCron) ? 0 : (int) $paramNbCron;

$paramJobID = Tools::getValue('jid', null);

$paramAct = Tools::getValue('act', null);
$action = (EcOrdersAndStock::jGet($prefix.'ACT'.$suffix) === 'die') ? 'die' : (is_null($paramAct) ? 'go' : $paramAct);

$paramSpy = Tools::getValue('spy', null);
$spy = is_null($paramSpy) ? false : true;

$paramSpy2 = Tools::getValue('spytwo', null);
$spy2 = is_null($paramSpy2) ? false : true;
$who = $spy ? ($spy2 ? 'spy2' : 'spy') : 'normal';

$paramKill = Tools::getValue('kill', null);
$kill = is_null($paramKill) ? false : true;

// constants
$listStages = array(
    '1' => 'STARTED',
    '2' => 'PREPAREFILE',
    '3' => 'UPDATESTOCK',
);
$base_uri = $mod->protocol
    . (!empty($mod->htaccessUser) ? $mod->htaccessUser . ':' . $mod->htaccessPwd . '@' : '')
    . Tools::getShopDomain() . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', __DIR__ . '/')
    . basename(__FILE__);
$ts = preg_replace('/0\.([0-9]{6}).*? ([0-9]+)/', '$2$1', microtime());
$params = '?ecoas_token=' . $token . '&ts=' . $ts . '&jid=' . (is_null($paramJobID) ? $ts : $paramJobID);
$cron_uri = $base_uri . $params;
$default_timeout = 4;
$stopTime = time() + 10;

/*
EcOrdersAndStock::logInfo(
    $logger,
    $who
    . ' entered, parameters '
    . ', n' . $nbCron
    . ', a' . $action
    . ', s' . (int) $spy
    . ', s' . (int) $spy2
    . ', k' . (int) $kill
);
*/

// kill
if ($kill) {
    if ($connecteur) {
        if (EcOrdersAndStock::jGet($prefix.'STATE'.$suffix) != 'done') {
            EcOrdersAndStock::jUpdateValue($prefix.'ACT'.$suffix, 'die');
        }
    }
    exit('die');
}

// espion
if ($spy) {
    sleep(15);
    $state = EcOrdersAndStock::jGet($prefix.'STATE'.$suffix);
    $progress = EcOrdersAndStock::jGet($prefix.'PROGRESS'.$suffix);
    if ($nbCron == $progress) {
        if ($spy2) {
            if ($state != 'done') {
                EcOrdersAndStock::jUpdateValue($prefix.'STATE'.$suffix, 'still');
            }
        } else {
            EcOrdersAndStock::followLink($cron_uri . '&spy=1&spytwo=1&nbC=' . $progress, $default_timeout);
        }
    } else {
        EcOrdersAndStock::followLink($cron_uri . '&spy=1&nbC=' . $progress, $default_timeout);
    }
    exit('bond');
}

// abandon ou initialisation
$etat = EcOrdersAndStock::jGet($prefix.'STATE'.$suffix);
$starting = (bool) $token & (bool) $connecteur & is_null($paramSpy) & is_null($paramNbCron) & is_null($paramKill) & is_null($paramAct);
if (!$starting && $action === 'die') {
// abandon demandé par un kill
    EcOrdersAndStock::jUpdateValue($prefix.'STATE'.$suffix, 'done');
    EcOrdersAndStock::jUpdateValue($prefix.'END_TIME'.$suffix, date('Y-m-d H:i:s'));
    EcOrdersAndStock::jUpdateValue($prefix.'ACT'.$suffix, 'go');
    exit('die');
}

if ($starting && $etat === 'running') {
// tentative de double lancement à éviter
    $progress = EcOrdersAndStock::jGet($prefix.'PROGRESS'.$suffix);
// envoi d'espion pour déjouer un plantage de serveur pendant une mise à jour
    EcOrdersAndStock::followLink($cron_uri . '&spy=1&nbC=' . (int) $progress, $default_timeout);
    exit('no');
}

if (!$starting && $etat === 'still') {
// un espion a pensé à tort qu'on était planté mais on est là !
    EcOrdersAndStock::jUpdateValue($prefix.'STATE'.$suffix, 'running');
    EcOrdersAndStock::followLink($cron_uri . '&spy=1&nbC=' . $nbCron, $default_timeout);
}
if ($starting) {
// initialisation du process
    EcOrdersAndStock::jUpdateValue($prefix.'START_TIME'.$suffix, date('Y-m-d H:i:s'));
    EcOrdersAndStock::jUpdateValue($prefix.'END_TIME'.$suffix, '');
    EcOrdersAndStock::jUpdateValue($prefix.'STAGE'.$suffix, $listStages['1']);
    EcOrdersAndStock::jUpdateValue($prefix.'PROGRESS'.$suffix, 0);
    EcOrdersAndStock::jUpdateValue($prefix.'PROGRESSMAX'.$suffix, 0);
    EcOrdersAndStock::jUpdateValue($prefix.'LOOPS'.$suffix, 0);
    EcOrdersAndStock::jUpdateValue($prefix.'STATE'.$suffix, 'running');
    EcOrdersAndStock::jUpdateValue($prefix.'ACT'.$suffix, 'go');
// lancement de l'espion
    EcOrdersAndStock::followLink($cron_uri . '&spy=1&nbC=0', $default_timeout);
} else {
    EcOrdersAndStock::jUpdateValue($prefix.'PROGRESS'.$suffix, $nbCron);
}
$stage = EcOrdersAndStock::jGet($prefix.'STAGE'.$suffix);


// gestion des reprises, ruptures, fin
if ($action === 'next') {
    $action = 'go';
    EcOrdersAndStock::jUpdateValue($prefix.'ACT'.$suffix, 'go');

    $numStage = array_search($stage, $listStages, true);
    $keys = array_keys($listStages);
    $next = $nextKey = false;
    foreach ($keys as $key) {
        if ($next) {
            $nextKey = $key;
            break;
        }
        if ($numStage == $key) {
            $next = true;
        }
    }

    if ($nextKey) {
        $stage = $listStages[$nextKey];
        $nbCron = 0;
    } else {
        EcOrdersAndStock::jUpdateValue($prefix.'STATE'.$suffix, 'done');
        EcOrdersAndStock::jUpdateValue($prefix.'END_TIME'.$suffix, date('Y-m-d H:i:s'));

        exit('done');
    }
    EcOrdersAndStock::jUpdateValue($prefix.'STAGE'.$suffix, $stage);
    EcOrdersAndStock::jUpdateValue($prefix.'LOOPS'.$suffix, 0);
    EcOrdersAndStock::jUpdateValue($prefix.'PROGRESS'.$suffix, 0);
}

/*
EcOrdersAndStock::logInfo(
    $logger,
    $who
    . ' is here to do '
    . $stage
);
*/

// aiguillage
switch ((int) array_search($stage, $listStages, true)) {
    case 1:
        $reps = stage1($stage);
        if ($reps === true) {
            EcOrdersAndStock::jUpdateValue($prefix.'ACT'.$suffix, 'next');
            EcOrdersAndStock::followLink($cron_uri . '&nbC=0&act=next', $default_timeout);
        } elseif (is_numeric($reps)) {
            EcOrdersAndStock::jUpdateValue($prefix.'LOOPS'.$suffix, EcOrdersAndStock::jGet($prefix.'LOOPS'.$suffix) + 1);
            EcOrdersAndStock::followLink($cron_uri . '&nbC=' . $reps, $default_timeout);
        }
        break;
    case 2:
        $reps = stage2($stopTime, $connecteur, $nbCron, $logger, $prefix);
        if ($reps === true) {
            EcOrdersAndStock::jUpdateValue($prefix.'PROGRESSMAX'.$suffix, 0);
            EcOrdersAndStock::jUpdateValue($prefix.'ACT'.$suffix, 'next');
            EcOrdersAndStock::followLink($cron_uri . '&nbC=0&act=next', $default_timeout);
        } elseif (is_numeric($reps)) {
            EcOrdersAndStock::jUpdateValue($prefix.'LOOPS'.$suffix, EcOrdersAndStock::jGet($prefix.'LOOPS'.$suffix) + 1);
            EcOrdersAndStock::followLink($cron_uri . '&nbC=' . $reps, $default_timeout);
        }
        break;
    case 3:
        $reps = stage3($stopTime, $connecteur, $nbCron, $logger, $prefix);
        if ($reps === true) {
            EcOrdersAndStock::jUpdateValue($prefix.'PROGRESSMAX'.$suffix, 0);
            EcOrdersAndStock::jUpdateValue($prefix.'ACT'.$suffix, 'next');
            EcOrdersAndStock::followLink($cron_uri . '&nbC=0&act=next', $default_timeout);
        } elseif (is_numeric($reps)) {
            EcOrdersAndStock::jUpdateValue($prefix.'LOOPS'.$suffix, EcOrdersAndStock::jGet($prefix.'LOOPS'.$suffix) + 1);
            EcOrdersAndStock::followLink($cron_uri . '&nbC=' . $reps, $default_timeout);
        }
        break;
    default:
        exit('done');
}

if ($reps !== true && (!is_numeric($reps))) {
    EcOrdersAndStock::logError(
        $logger, $who . ', ' . $connecteur . ', stage ' . $stage . ', ' . var_export($reps, true)
    );
}

exit('bye');

function stage1($stage)
{
    echo $stage.' ';

    return true;
}

function stage2($stopTime, $connecteur, $nbCron, $logger, $prefix)
{
    // get last uploaded stock file
    $import_dir = dirname(__FILE__) . '/files/import/';
    $files_dir = dirname(__FILE__) . '/files/';
    $last_upload = EcOrdersAndStock::jGet('stock_upload_date');
    $last_ts = preg_replace('/[^0-9]/', '', $last_upload);
    $last_file = $import_dir . 'stock_' . $last_ts . '.txt';
    $work_file = $files_dir . 'stock';
    if (file_exists($work_file . '.txt')) {
        unlink($work_file . '.txt');
    }
    if (file_exists($work_file . '.json')) {
        unlink($work_file . '.json');
    }

    // build bigjson
    $handle = fopen($last_file, 'rb');
    $tab_offsets = array(0);
    $n = 0;
    $nerr = 0;
    $buffer = '';
    while (($line = fgets($handle)) !== false) {
        $matches = array();
        if (!preg_match('/.*?([0-9]+).*?([0-9-]+).*?/', $line, $matches)) {
            $nerr++;
            continue;
        }
        $n++;
        $item = array(
            'ean13' => $matches[1],
            'qt' => $matches[2],
        );
        $ijson = Tools::jsonEncode($item);
        $buffer .= $ijson;
        if (strlen($buffer) > 81920) {
            file_put_contents($work_file . '.txt', $buffer, FILE_APPEND);
            $buffer = '';
        }
        $tab_offsets[] = strlen($ijson);
    }
    if (!empty($buffer)) {
        file_put_contents($work_file . '.txt', $buffer, FILE_APPEND);
    }
    file_put_contents($work_file . '.json', Tools::jsonEncode($tab_offsets));
    fclose($handle);

    return true;
}

function stage3($stopTime, $connecteur, $nbCron, $logger, $prefix)
{
    // update stock
    $id_shop = Configuration::get('PS_SHOP_DEFAULT');
    Shop::setContext(Shop::CONTEXT_SHOP, (int) $id_shop);
    Context::getContext()->employee = new Employee(1);
    Context::getContext()->shop->id = (int) $id_shop;
    $stock = new bigjson('stock', dirname(__FILE__) . '/files/');
    $stock->deleteIndex();

    $products = Db::getInstance()->executeS(
        'SELECT id_product, ean13
        FROM ' . _DB_PREFIX_ . 'product
        WHERE 1
        ORDER BY id_product
        LIMIT ' . (int) $nbCron . ', 500'
    );
    if (!$products) {
        return true;
    }

    foreach ($products as $product) {
        $nbCron++;

        //EcOrdersAndStock::logDebug($logger, 'searching '.$product['ean13'].' in pid '.$product['id_product']);
        if (empty($product['ean13'])) {
            //EcOrdersAndStock::logDebug($logger, 'no ean13 in pid '.$product['id_product']);
            continue;
        }
        $idbj = $stock->searchOne('ean13', $product['ean13']);
        if (false === $idbj) {
            //EcOrdersAndStock::logDebug($logger, $product['ean13'].' not found in file');
            continue;
        }

        $bj = $stock->get($idbj);

        try {
            $qt = StockAvailable::getQuantityAvailableByProduct((int) $product['id_product'], 0, (int) $id_shop);
            if ($qt != $bj['qt']) {
                //EcOrdersAndStock::logDebug($logger, 'updating pid '.$product['id_product']);
                StockAvailable::setQuantity((int) $product['id_product'], 0, (int) $bj['qt'], (int) $id_shop, false);
            }
        } catch (Exception $e) {
            EcOrdersAndStock::logWarning(
                $logger,
                'Erreur lors de la MAJ stock PID ' . $product['id_product'] . ' : ' . $e->getMessage()
            );
        }

        if (0 === ($nbCron % 10)) {
            EcOrdersAndStock::jUpdateValue($prefix.'PROGRESS_'.$connecteur, $nbCron);
        }
    }

    return $nbCron;
}


function exception_error_handler($severity, $message, $file, $line)
{
    if (!(error_reporting() & $severity)) {
// Ce code d'erreur n'est pas inclu dans error_reporting
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}
