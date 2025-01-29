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

require_once dirname(__FILE__) . '/../../../config/config.inc.php';
require_once dirname(__FILE__) . '/../ecordersandstock.php';


$ecoas = new EcOrdersAndStock();

// pass the function public for the time using
$registered_os = $ecoas->regiserOrderStates(
    array(
        // état transmis
        array(
            'color' => '#0000cc',
            'name' => EcOrdersAndStock::createMultiLangField('Transmis Navision'),
            'ecoas_os_id' => 'tr',
        ),
        //état "En instance de production"
        array(
            'color' => '#ff8f46',
            'name' => EcOrdersAndStock::createMultiLangField('En instance de production'),
            'ecoas_os_id' => 'pr',
        ),
    )
);

foreach ($registered_os as $ecoas_os_id => $id_os) {
    EcOrdersAndStock::setConfig('ecoas_os_' . $ecoas_os_id, $id_os);
}