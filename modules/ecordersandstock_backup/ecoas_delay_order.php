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
 *  @package    ecordersandstock
 *  @author     Alec Page
 *  @copyright  Copyright (c) 2010-2018 S.A.R.L Ether Création (http://www.ethercreation.com)
 *  @license    Commercial license
 */

require_once dirname(__FILE__) . '/../../config/config.inc.php';
require_once dirname(__FILE__) . '/ecordersandstock.php';
ignore_user_abort(true);

$paramHelp = Tools::getValue('help', null);
if (!is_null($paramHelp)) {
    $help = array(
        'ecoas_token' => array(
            'fr' => 'Token du module. Obligatoire.',
            'en' => 'Module\'s token. Required.'
            ),
        'id_order' => array(
            'fr' => 'Comande à traiter. Obligatoire.',
            'en' => 'Order to treat. Required.'
            ),
        'delay' => array(
            'fr' => 'Temps avant traitement de la commande. Obligatoire.',
            'en' => 'Delay before treating. Required.'
            ),
    );
    exit(Tools::jsonEncode($help));
}

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

//$logger = EcOrdersAndStock::logStart('delayorder');

// parameters
$paramNbCron = Tools::getValue('nbC', null);
$nbCron = is_null($paramNbCron) ? 0 : (int) $paramNbCron;

$paramOrder = Tools::getValue('id_order', null);
$id_order = $paramOrder;

$paramDelay = Tools::getValue('delay', null);
$delay = is_null($paramDelay) ? 0 : (int) $paramDelay;

$ps_base_uri = (((Configuration::get('PS_SSL_ENABLED') == 1) && (Configuration::get('PS_SSL_ENABLED_EVERYWHERE') == 1)) ? 'https://' : 'http://' ) .
        Tools::getShopDomain() . __PS_BASE_URI__;
$ts = preg_replace('/0\.([0-9]{6}).*? ([0-9]+)/', '$2$1', microtime());
$selfUri = $ps_base_uri . str_replace(_PS_ROOT_DIR_ . '/', '', __FILE__) . '?ecoas_token=' . $token . '&ts=' . $ts;
$ecoas_ajax_uri = $ps_base_uri . str_replace(_PS_ROOT_DIR_ . '/', '', __DIR__) . '/ajax.php?ecoas_token=' . $token . '&ts=' . $ts;

/*
EcOrdersAndStock::logInfo(
    $logger,
    'ecoas_delay_order '
    . $nbCron . ','
    . $id_order . ','
    . $delay
);
*/

if ($nbCron) {
    sleep(5);
    $delay -= 5;
} else {
    $delay -= 1;
}

if (0 > $delay) {
//    EcOrdersAndStock::logInfo($logger, $ecoas_ajax_uri . '&majsel=26&idc=' . $id_order);
    EcOrdersAndStock::followLink($ecoas_ajax_uri . '&majsel=26&idc=' . $id_order);
} else {
//    EcOrdersAndStock::logInfo($logger, $selfUri . '&nbC=' . ($nbCron + 1) . '&delay=' . $delay . '&id_order=' . $id_order);
    EcOrdersAndStock::followLink($selfUri . '&nbC=' . ($nbCron + 1) . '&delay=' . $delay . '&id_order=' . $id_order);
}

exit('bye');
