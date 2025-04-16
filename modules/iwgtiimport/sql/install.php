<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$sql = array (
'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.IWSTDImportTools::getModuleName().'_message` (
    `id_message` int(11) NOT NULL AUTO_INCREMENT,
    `created` timestamp,
    `message` varchar(8192),
    `code` tinyint(2),
    `id_import` int(11),
    `id_header` int(11),
    `id_detail` int(11),
    `id_keyvalue` int(11),
    PRIMARY KEY  (`id_message`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',
    
'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.IWSTDImportTools::getModuleName().'_import` (
    `id_import` int(11) NOT NULL AUTO_INCREMENT,
    `created` timestamp,
    `source` varchar(64),
    `status` tinyint(2),
    PRIMARY KEY  (`id_import`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',

'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.IWSTDImportTools::getModuleName().'_header` (
    `id_header` int(11) NOT NULL AUTO_INCREMENT,
    `id_import` int(11) NOT NULL,
    `input_name` varchar(128),
    `name` varchar(128),
    `status` tinyint(2),
    PRIMARY KEY  (`id_header`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',

'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.IWSTDImportTools::getModuleName().'_detail` (
    `id_detail` int(11) NOT NULL AUTO_INCREMENT,
    `id_header` int(11) NOT NULL,
    `status` tinyint(2),
    PRIMARY KEY  (`id_detail`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',

'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.IWSTDImportTools::getModuleName().'_keyvalue` (
    `id_keyvalue` int(11) NOT NULL AUTO_INCREMENT,
    `id_detail` int(11) NOT NULL,
    `key` varchar(128),
    `value` varchar(8192),
    `status` tinyint(2),
    PRIMARY KEY  (`id_keyvalue`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',

'ALTER TABLE `'._DB_PREFIX_.IWSTDImportTools::getModuleName().'_keyvalue` ADD INDEX `key_index` (`key`)',
);

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
