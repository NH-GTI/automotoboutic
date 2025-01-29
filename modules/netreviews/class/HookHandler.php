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

class HookHandler
{
    public const DISPLAY_HOME_HOOK = 'displayHome';
    public const DISPLAY_HEADER_HOOK = 'displayHeader';
    public const DISPLAY_FOOTER_HOOK = 'displayFooter';
    public const ACTION_ORDER_STATUS_POST_UPDATE_HOOK = 'actionOrderStatusPostUpdate';

    /**
     * @var array<string,string>
     */
    private static $hookFuncs = [
        self::DISPLAY_HOME_HOOK => 'checkDisplayHomeTriggers',
        self::DISPLAY_HEADER_HOOK => 'checkDisplayHomeTriggers',
        self::DISPLAY_FOOTER_HOOK => 'checkDisplayHomeTriggers',
        self::ACTION_ORDER_STATUS_POST_UPDATE_HOOK => 'checkDisplayHomeTriggers',
    ];

    /**
     * @return array<string,string>
     */
    public static function getHookFuncs()
    {
        return self::$hookFuncs;
    }

    /**
     * trigger a hook by his name
     *
     * @param string $hookName
     *
     * @return void
     */
    public static function hookTriggers($hookName)
    {
        $callable = [__CLASS__, self::getHookFuncs()[$hookName]];
        if (array_key_exists($hookName, self::getHookFuncs())
            && is_callable($callable)) {
            call_user_func($callable);
        }
    }

    /**
     * Run a update config if it's one hour since the last update
     *
     * @param bool $forceUpdate
     *
     * @return void
     */
    public static function checkDisplayHomeTriggers($forceUpdate = false)
    {
        $shopByWebsites = InternalConfigManager::getAVShopWithWebsiteId();
        if (is_array($shopByWebsites)) {
            foreach ($shopByWebsites as $websiteId => $websiteData) {
                if (is_array($websiteData)
                    && array_key_exists('idShop', $websiteData)
                    && array_key_exists('idShopComp', $websiteData)
                    && array_key_exists('groupName', $websiteData)) {
                    $nextUpdateTime = Configuration::get('AV_CHECK_CONFIG' . $websiteData['groupName'], null, null, $websiteData['idShop'], 0);
                    if (empty($nextUpdateTime)) {
                        $nextUpdateTime = 0;
                    } else {
                        $nextUpdateTime = (int) $nextUpdateTime + 3600;
                    }
                    if ($nextUpdateTime <= time() || $forceUpdate) {
                        $isCheckSuccess = self::checkConfig(
                            $websiteData['idShop'],
                            $websiteData['idShopComp'],
                            (string) $websiteData['groupName'],
                            $websiteId
                        );
                        if ($isCheckSuccess) {
                            Configuration::updateValue('AV_CHECK_CONFIG' . $websiteData['groupName'], time(),
                                false, null, (int) $websiteData['idShop']);
                        }
                    }
                }
            }
        }
    }

    /**
     * Update the configuration and return the result
     *
     * @param string|int|null $idShop
     * @param string|int|null $idShopComp
     * @param string $groupName
     * @param string $websiteId
     *
     * @return bool
     */
    public static function checkConfig($idShop, $idShopComp, $groupName, $websiteId)
    {
        $configManager = new GlobalConfigManager($idShop, $groupName, $idShopComp, $websiteId);
        $ret = $configManager->getApiConnectorsDataAndUpdate();

        return $ret;
    }
}
