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

if (!defined('_PS_VERSION_'))
	exit;

/**
 * Function used to update your module from previous versions to the version installed,
 * Don't forget to create one file per version.
 */
function upgrade_module_99_7_3($module)
{
	$sql = 'DROP TABLE `'._DB_PREFIX_.'ndk_customization_field_reference`';
	$result = Db::getInstance()->Execute($sql);

	$sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ndk_customization_field_additional_info` (
		id_ndk_customization_field_additional_info int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
		id_product int(10) not null,
		key_id int(10) not null,
		key_prefix varchar(64) not null,
		key_values VARCHAR(255) not null,
		key_iso VARCHAR(8) not null,
		value VARCHAR(255) not null,
		PRIMARY KEY(`id_ndk_customization_field_additional_info`),
		INDEX (`key_id`,`key_values`,`key_iso`)
	) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
	$result &= Db::getInstance()->Execute($sql);

	return $result;
}
