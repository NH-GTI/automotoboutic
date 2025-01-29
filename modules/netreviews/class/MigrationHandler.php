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

class MigrationHandler
{
    /**
     * @var int
     */
    private $idShop;

    /**
     * @param int $idShop
     */
    public function __construct($idShop)
    {
        $this->idShop = $idShop;
    }

    /**
     * initMigration to api-connectors
     *
     * @return void
     */
    public function initMigration()
    {
        LogsHandler::addLog('Init migration');

        $config = $this->getCurrentConfigForMigration($this->idShop);

        LogsHandler::addLog('Migration config:' . var_export($config, true));

        if (empty($config)) {
            return;
        }

        $apiGenesisCaller = new ApiGenesisCaller();
        $response = $apiGenesisCaller->callMigrationEndpoint($config);
        LogsHandler::addLog('Response from Genesis endpoint: ' . var_export($response, true));

        if (empty($apiGenesisCaller->getErrors()) && !empty($response) && is_array($response)) {
            foreach ($response as $item) {
                if (is_array($item)) {
                    $migrationStatus = $item['migrationStatus'];

                    if ($migrationStatus > 400) {
                        LogsHandler::addLog('Migration status FAILED: ' . $migrationStatus);
                        continue;
                    }

                    $websiteIdSettings = (isset($item['websiteId']['websiteId']) ? $item['websiteId']['websiteId'] : $item['websiteId']);
                    $connectorApiKey = (isset($item['websiteId']['connectorApiKey']) ? $item['websiteId']['connectorApiKey'] : $item['websiteSolution']['websiteId']['connectorApiKey']);

                    $internalConfig = new InternalConfigManager($this->idShop);
                    $groupName = $internalConfig::getGroupNameByWebsiteId($websiteIdSettings, $this->idShop);

                    $internalConfig->setGroupName($groupName);
                    $internalConfig->upsertDBconfig($item['settings']);
                    Configuration::updateValue('AV_RELOADED' . $groupName, '1', false, null, $this->idShop);
                    Configuration::updateValue('AV_CLESECRETE' . $groupName, $connectorApiKey, false, null, $this->idShop);
                    HookHandler::checkDisplayHomeTriggers(true);

                    LogsHandler::addLog('Config updated after migration: ' . var_export($item['settings'], true));
                }
            }
        } else {
            LogsHandler::addLog(
                'Errors on migration to Api-connectors: ' .
                var_export($apiGenesisCaller->getErrors(), true)
            );
        }

        LogsHandler::addLog('End migration');
    }

    /**
     * @param int|null $idShop
     *
     * @return array<mixed>
     */
    private function getCurrentConfigForMigration(?int $idShop)
    {
        $internalConfigManager = new InternalConfigManager($idShop);
        $isMultilang = $internalConfigManager->isSkConfigMultilang();

        return $this->getConfigToMigrate($idShop, $isMultilang);
    }

    /**
     * @param int|null $idShop
     * @param bool $isMultilang
     *
     * @return array<mixed>
     */
    public function getConfigToMigrate(?int $idShop, bool $isMultilang = false)
    {
        $config = [];

        $moduleVersion = (new Netreviews())->version;

        if ($isMultilang) {
            $multilangConfigToMigrate = $this->getMultilangConfigForMigration($idShop);
            foreach ($multilangConfigToMigrate as $websiteLang => $langConfigToMigrate) {
                if (is_array($langConfigToMigrate)
                    && (
                        (array_key_exists('AV_RELOADED_' . $websiteLang, $langConfigToMigrate)
                        && $langConfigToMigrate['AV_RELOADED_' . $websiteLang] !== '1')
                        || (!isset($langConfigToMigrate['AV_PUSH_ORDERS_ENDPOINT_' . $websiteLang])
                        || empty($langConfigToMigrate['AV_PUSH_ORDERS_ENDPOINT_' . $websiteLang]))
                    )
                ) {
                    $config[$langConfigToMigrate['AV_IDWEBSITE_' . $websiteLang]] = [
                        'moduleVersion' => $moduleVersion,
                        'urlCertificate' => $langConfigToMigrate['AV_URLCERTIFICAT_' . $websiteLang],
                        'signature' => hash(
                            'sha256',
                            'migration' . $langConfigToMigrate['AV_IDWEBSITE_' . $websiteLang] .
                            $langConfigToMigrate['AV_CLESECRETE_' . $websiteLang]
                        ),
                    ];
                }
            }
        } else {
            $idShopFilterSql = ($idShop !== null) ? ' AND id_shop = ' . $idShop : '';
            $query = 'SELECT * FROM `' . _DB_PREFIX_ . 'configuration` WHERE (`name` LIKE "AV_RELOADED%" OR `name` = "AV_IDWEBSITE" OR `name` = "AV_CLESECRETE" OR `name` = "AV_URLCERTIFICAT" OR `name` = "AV_PUSH_ORDERS_ENDPOINT")' . $idShopFilterSql . ';';
            $result = (array) Db::getInstance()->executeS($query);
            $configResult = array_column($result, 'value', 'name');

            if ($configResult['AV_RELOADED'] !== '1' || (!isset($configResult['AV_PUSH_ORDERS_ENDPOINT']))) {
                $config[$configResult['AV_IDWEBSITE']] = [
                    'moduleVersion' => $moduleVersion,
                    'urlCertificate' => $configResult['AV_URLCERTIFICAT'],
                    'signature' => hash(
                        'sha256',
                        'migration' . $configResult['AV_IDWEBSITE'] . $configResult['AV_CLESECRETE']
                    ),
                ];
            }
        }

        return $config;
    }

    /**
     * @param int $idShop
     *
     * @return array<mixed>
     */
    public function getMultilangConfigForMigration($idShop)
    {
        $idShopFilterSql = ($idShop !== null) ? ' AND id_shop = ' . $idShop : '';
        $multiLangConfigQuery = 'SELECT * FROM `' . _DB_PREFIX_ . 'configuration` WHERE (`name` LIKE "AV_RELOADED\_%" OR `name` LIKE "AV_IDWEBSITE\_%" OR `name` LIKE "AV_CLESECRETE\_%" OR `name` LIKE "AV_URLCERTIFICAT\_%" OR `name` LIKE "AV_PUSH_ORDERS_ENDPOINT\_%")' . $idShopFilterSql . ';';
        $multiLangConfigResult = (array) Db::getInstance()->executeS($multiLangConfigQuery);

        $config = array_column($multiLangConfigResult, 'value', 'name');
        $allConfig = [];
        foreach ($config as $key => $value) {
            $idLang = substr((string) strrchr((string) $key, '_'), 1);
            $allConfig[$idLang] = [
                'AV_RELOADED_' . $idLang => $config['AV_RELOADED_' . $idLang],
                'AV_IDWEBSITE_' . $idLang => $config['AV_IDWEBSITE_' . $idLang],
                'AV_CLESECRETE_' . $idLang => $config['AV_CLESECRETE_' . $idLang],
                'AV_URLCERTIFICAT_' . $idLang => $config['AV_URLCERTIFICAT_' . $idLang],
                'AV_PUSH_ORDERS_ENDPOINT_' . $idLang => $config['AV_PUSH_ORDERS_ENDPOINT_' . $idLang],
            ];
        }

        return $allConfig;
    }
}
