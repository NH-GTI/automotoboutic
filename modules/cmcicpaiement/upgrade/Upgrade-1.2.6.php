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
function upgrade_module_1_2_6($module)
{
    return (bool) Configuration::updateValue('CMCIC_SERVER', Configuration::get('CMCIC_SERVEUR'))
        && (bool) Configuration::updateValue('CMCIC_KEY', Configuration::get('CMCIC_CLE'))
        && (bool) Configuration::updateValue('CMCIC_COMPANY_CODE', Configuration::get('CMCIC_CODESOCIETE'))
        && (bool) Configuration::updateValue('CMCIC_ERROR_BEHAVIOR', Configuration::get('CMCIC_ERROR'))
        && (bool) Configuration::updateValue('CMCIC_EMAIL_NOTIFICATION', Configuration::get('CMCIC_EMAILERR'));
}
