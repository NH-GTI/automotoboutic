<?php
/**
 * 2007-2015 PrestaShop
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2014 PrestaShop SA
 * @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
 * International Registered Trademark & Property of PrestaShop SA
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * @param CMCICPaiement $module
 *
 * @return bool
 */
function upgrade_module_4_0_0($module)
{
    $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'cmcic_notification_event` ' .
            'MODIFY `event_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, ' .
            'MODIFY `cart_reference` INT(10) UNSIGNED DEFAULT NULL';

    return Db::getInstance()->execute($sql);
}
