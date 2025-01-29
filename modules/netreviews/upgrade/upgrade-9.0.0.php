<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    NetReviews SAS <contact@avis-verifies.com>
 * @copyright 2012-2024 NetReviews SAS
 * @license   NetReviews
 *
 * @version   Release: $Revision: 8.1.0
 *
 * @date      22/08/2024
 * International Registered Trademark & Property of NetReviews SAS
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * File: /upgrade/upgrade-9.0.0.php
 */
function upgrade_module_9_0_0($module)
{
    $idShop = $module->currentShopId;

    HookHandler::checkDisplayHomeTriggers(true);

    $internalConfigManager = new InternalConfigManager($idShop);
    $genesisConfiguredAccounts = $internalConfigManager::hasGenesisConfiguredAccounts($idShop);
    $isReloadedBefore9 = $internalConfigManager::isReloadedBeforeVersion9($idShop);

    if (!empty($genesisConfiguredAccounts) || $isReloadedBefore9) {
        $migrationHandler = new MigrationHandler($idShop);
        $migrationHandler->initMigration();
    }

    return true;
}
