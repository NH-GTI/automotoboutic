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
 * @version   Release: $Revision: 9.0.0
 *
 * @date      22/08/2024
 * International Registered Trademark & Property of NetReviews SAS
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class ConfSkeepersLocal extends ConfSkeepersProd
{
    /**
     * Variable to store the env variables
     *
     * @var array<string,bool|int|string|null>
     */
    public static $envValues = [
        'SKEEPERS_SSO' => 'auth.skeepers.io',
        'SKEEPERS_SSO_TOKEN' => 'https://auth.skeepers.io/am/oauth2/alpha/access_token',
        'SKEEPERS_PURCHASE_BULK' => 'https://api.skeepers.io/purchase-event/websites/%s/purchase_events/bulk_insert',
        'RELOADED_CONNECTORS' => 'api.skeepers.io',
        'RELOADED_FRONT' => 'app.netreviews.eu',
        'SKEEPERS_API_CONNECTORS' => 'https://connectors.cxr.skeepers.io/',
        'SKEEPERS_GET_TOKEN' => true,
        'SKEEPERS_API_GENESIS' => 'https://www.avis-verifies.com/api/2.0/',
        'RETROACTIVE_ORDER_COUNT_LIMIT' => 10000,
    ];

    /**
     * @return array<string,bool|int|string|null>
     */
    public static function getAllEnVar()
    {
        return static::$envValues;
    }
}
