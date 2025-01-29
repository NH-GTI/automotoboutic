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

class GlobalConfigManager
{
    /**
     * @var int|string|null
     */
    private $idShop;
    /**
     * @var int|string|null
     */
    private $idShopComp;
    /**
     * @var string
     */
    private $groupName;
    /**
     * @var string
     */
    private $websiteId;

    /**
     * Error array with status code and message.
     *
     * @var array<array<int, string>>
     */
    private $errors;

    /**
     * @var InternalConfigManager
     */
    private $internalConfig;
    /**
     * @var ApiConnectorsCaller
     */
    private $apiConnectors;

    /**
     * @param int|string|null $idShop
     * @param string $groupName
     * @param int|string|null $idShopComp
     * @param string $websiteId
     */
    public function __construct($idShop, $groupName, $idShopComp, $websiteId)
    {
        $this->errors = [];
        $this->idShop = $idShop;
        $this->idShopComp = $idShopComp;
        $this->groupName = $groupName;
        $this->websiteId = $websiteId;

        $this->internalConfig = new InternalConfigManager($this->idShop, $this->groupName, $this->idShopComp);
        $this->apiConnectors = new ApiConnectorsCaller($this->idShop, $this->groupName, $this->websiteId);
    }

    /**
     * @return bool
     */
    public function getApiConnectorsDataAndUpdate()
    {
        $remoteConfigData = $this->internalConfig->getDbConfig(false);
        if (!is_array($remoteConfigData)) {
            $remoteConfigData = [];
        }
        $remoteConfigData = array_merge($remoteConfigData, $this->internalConfig->getConfigFuncs());

        $apiConf = $this->apiConnectors->getApiConf($remoteConfigData);
        if (!empty($apiConf) && isset($apiConf['settings'])
            && is_array($apiConf['settings'])) {
            $this->internalConfig->upsertDBconfig($apiConf['settings']);
        } else {
            $this->errors = $this->apiConnectors->getErrors();

            return false;
        }

        return true;
    }

    /**
     * @return array<array<int, string>>
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
