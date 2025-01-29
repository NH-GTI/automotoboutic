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
 *  @copyright  Copyright (c) 2010-2016 S.A.R.L Ether Création (http://www.ethercreation.com)
 *  @license    Commercial license
 */

$sql = array();
$sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'ecoas_jobs (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(128) NOT NULL,
    value varchar(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
$sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'ecoas_config (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(128) NOT NULL,
    value text NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
$sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'ecoas_orders (
    id int(11) NOT NULL AUTO_INCREMENT,
    id_order int(11) NOT NULL,
    status varchar(15) NOT NULL,
    message text NOT NULL,
    data text,
    file_name varchar(32),
    date datetime NOT NULL,
    locked int(1) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE id_order (id_order),
    INDEX file_name (file_name),
    INDEX status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
