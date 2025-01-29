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
$mod = new EcOrdersAndStock();
$paymentMethod = ['CDISCOUNT.FR', 'AMAZON.FR'];

if (Tools::getValue('ecoas_token') != EcOrdersAndStock::getConfigValue('ecoas_token')) {
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    header('Location: ../');
    exit();
}

switch ((int) Tools::getValue('majsel')) {
    case 10:
        echo EcOrdersAndStock::saveParameters(Tools::getValue('ecordersandstock_conf'));
        break;
    case 15:
        echo $mod->sendOrders(true); // Orders with contremarque
        break;
    case 26:
        echo $mod->sendOrders(false); // Orders without contremarque
        break;
    case 55:
        echo $mod->displayInfoRetour(Tools::getValue('message'), Tools::getValue('etat'));
        break;
    case 65:
        echo $mod->getCpanelData(Tools::getValue('prefix'), Tools::getValue('suffix'));
        break;
    case 88:
        echo $mod->getRepriseStateLang(Tools::getValue('id_order'));
        break;
    case 89:
        echo $mod->toggleRepriseStateLang(Tools::getValue('id_order'));
        break;
    case 99:
        $now = date('Y-m-d H:i:s');
        $ts = preg_replace('/[^0-9]/', '', $now);
        $file_save = dirname(__FILE__) . '/files/import/stock_' . $ts . '.txt';
        if (isset($_FILES) &&
            is_array($_FILES) &&
            isset($_FILES['ecoas_stockfile']['error']) &&
            0 >= $_FILES['ecoas_stockfile']['error']) {
            move_uploaded_file($_FILES['ecoas_stockfile']['tmp_name'], $file_save);
            EcOrdersAndStock::jUpdateValue('stock_upload_date', $now);
            echo 1;
        } else {
            throw new Exception(
                'Error ' . $_FILES['ecoas_stockfile']['error'] . ' occurred while transferring the file.'
            );
        }
        break;
    default:
        break;
}
exit();
