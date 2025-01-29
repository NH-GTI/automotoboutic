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
 * @version   Release: $Revision: 9.0.2
 *
 * @date      15/11/2024
 * International Registered Trademark & Property of NetReviews SAS
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class InternalConfigManager
{
    protected const SELECT_NAME = 'SELECT name FROM ';

    /**
     * @var int|string|null
     */
    private $idShop;
    /**
     * @var int|string|null
     */
    private $idShopComp;
    /**
     * @var string|null
     */
    private $groupName;

    /**
     * @var array<mixed>|null
     */
    private $multilanguagesShopList;

    /**
     * Undocumented function
     *
     * @param int|string|null $idShop
     * @param string|null $groupName
     * @param int|string|null $idShopComp
     */
    public function __construct($idShop = null, $groupName = null, $idShopComp = null)
    {
        $this->idShop = $idShop;
        $this->idShopComp = $idShopComp;
        $this->multilanguagesShopList = null;
        if (!is_null($groupName)) {
            $this->groupName = $groupName;
        } else {
            $this->groupName = '';
        }
    }

    /**
     * @param string|int|null $idShop
     *
     * @return array<mixed>
     *
     * @throws PrestaShopDatabaseException
     */
    public static function getMultilangshoplist($idShop)
    {
        $multilanguagesShopList = [];
        $idShopFilterSql = (!empty($idShop)) ? ' AND id_shop = ' . (int) $idShop
            : ' AND (id_shop IN (0, 1) OR id_shop IS NULL)';
        $sql = 'SELECT id_shop,value FROM '
            . _DB_PREFIX_ . 'configuration WHERE name LIKE "AV_GROUP_CONF%"' . $idShopFilterSql;
        $rows = Db::getInstance()->ExecuteS($sql);
        if (is_array($rows)) {
            $multilanguagesShopList = [];
            foreach ($rows as $r_element) {
                if ('checked' == Configuration::get('AV_MULTILINGUE', null, null, $r_element['id_shop'])) {
                    // @phpstan-ignore-next-line
                    $multilanguagesShopList[$r_element['id_shop']][]
                        = netreviewsModel::avJsonDecode($r_element['value']);
                }
            }
        }

        return $multilanguagesShopList;
    }

    /**
     * @param int|string $idShop
     * @param string $groupName
     *
     * @return string
     */
    public static function getCurrentShopComp($idShop, $groupName)
    {
        $currentShopComp = '';

        $multilanguagesShopList = self::getMultilangshoplist($idShop);

        if (!empty($multilanguagesShopList) && !empty($groupName)) {
            $langIndex = str_replace('_', '', $groupName);
            $multiName = '';
            if (is_array($multilanguagesShopList)
                && isset($multilanguagesShopList[$idShop][$langIndex][0])) {
                $multiName = $multilanguagesShopList[$idShop][$langIndex][0];
            }
            $currentShopComp = $idShop . '_' . $multiName;
        }

        return $currentShopComp;
    }

    /**
     * @param string $isoLang
     * @param string|int|null $idShop
     *
     * @return string
     */
    public static function getGroupNameByIsoLang($isoLang, $idShop)
    {
        $multisite = Configuration::get('AV_MULTISITE');

        if (1 == Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') && !empty($multisite)) {
            $sql = self::SELECT_NAME . _DB_PREFIX_ . "configuration where name like 'AV_GROUP_CONF_%' And id_shop = '"
                . $idShop . "'";
        } else {
            $sql = self::SELECT_NAME . _DB_PREFIX_ . "configuration where name like 'AV_GROUP_CONF_%'";
        }
        if (($results = Db::getInstance()->ExecuteS($sql)) && is_array($results)) {
            foreach ($results as $row) {
                $data = Configuration::get($row['name'], null, null, $idShop);
                if (is_string($data) && !empty($data)) {
                    $vconf = json_decode($data, true);
                    if ($vconf && is_array($vconf) && in_array($isoLang, $vconf)) {
                        return '_' . Tools::substr($row['name'], 14);
                    }
                }
            }
        }

        return '';
    }

    /**
     * @param string|null $groupName
     * @param string|int|null $idShop
     *
     * @return string
     */
    public static function getIsoLangByGroupName($groupName, $idShop)
    {
        if (is_null($groupName)) {
            $groupName = '';
        }
        $multisite = Configuration::get('AV_MULTISITE');

        if (1 == Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') && !empty($multisite)) {
            $sql = self::SELECT_NAME . _DB_PREFIX_ . "configuration where name = 'AV_GROUP_CONF$groupName' And id_shop = '"
                . $idShop . "'";
        } else {
            $sql = self::SELECT_NAME . _DB_PREFIX_ . "configuration where name = 'AV_GROUP_CONF$groupName'";
        }
        if (($results = Db::getInstance()->ExecuteS($sql)) && is_array($results)) {
            foreach ($results as $row) {
                $data = Configuration::get($row['name'], null, null, $idShop);
                if (is_string($data) && !empty($data)) {
                    $vconf = json_decode($data, true);
                    if ($vconf && is_array($vconf) && isset($vconf[0])) {
                        return $vconf[0];
                    }
                }
            }
        }

        return '';
    }

    /**
     * @param string $websiteId
     * @param int|string|null $idShop
     *
     * @return array<string>
     */
    public static function getGroupNames($websiteId, $idShop)
    {
        $groupNames = [];

        if ('checked' == Configuration::get('AV_MULTILINGUE', null, null, $idShop)) {
            $languages = Language::getLanguages(true);

            foreach ($languages as $lang) {
                if (is_array($lang) && array_key_exists('iso_code', $lang) && is_string($lang['iso_code'])) {
                    $tmpGrp = self::getGroupNameByIsoLang($lang['iso_code'], $idShop);
                    if (!empty($tmpGrp)) {
                        $isGoodWebsite = Configuration::get('AV_IDWEBSITE' . $tmpGrp, null, null, $idShop);
                        if ($isGoodWebsite === $websiteId) {
                            $groupNames[] = $tmpGrp;
                        }
                    }
                }
            }
        } else {
            $groupNames[] = '';
        }

        return $groupNames;
    }

    /**
     * @param string $websiteId
     * @param int|string|null $idShop
     *
     * @return string
     */
    public static function getGroupNameByWebsiteId($websiteId, $idShop)
    {
        $idShopQuery = ' AND id_shop = ' . $idShop;
        if (empty($idShop)) {
            $idShopQuery = ' AND id_shop IS NULL';
        }

        $query = 'SELECT name FROM `' . _DB_PREFIX_ . 'configuration` WHERE `value` = "' . $websiteId . '"' . $idShopQuery . ' ORDER BY name DESC;';
        $groupInfoResult = (array) Db::getInstance()->getRow($query);
        $groupNameAsArray = explode('_', $groupInfoResult['name']);
        $groupName = '';
        if (isset($groupNameAsArray[2]) && (!empty($groupNameAsArray[2]) || $groupNameAsArray[2] === '0')) {
            $groupName = '_' . $groupNameAsArray[2];
        }

        return $groupName;
    }

    /**
     * @return ?array<string,array<string,int|string|null>>>
     */
    public static function getAVShopWithWebsiteId()
    {
        $sql = 'SELECT id_shop, value, name FROM ' . _DB_PREFIX_ . "configuration WHERE name like 'AV_IDWEBSITE%';";

        $result = Db::getInstance()->executeS($sql);
        if (is_array($result)) {
            $ret = [];

            foreach ($result as $row) {
                if (!empty($row['value']) && is_string($row['value'])) {
                    $isMulti = ('checked' == Configuration::get('AV_MULTILINGUE', null, null, $row['id_shop']));
                    if (($isMulti && $row['name'] != 'AV_IDWEBSITE') || (!$isMulti && $row['name'] == 'AV_IDWEBSITE')) {
                        $gnames = self::getGroupNames($row['value'], $row['id_shop']);
                        foreach ($gnames as $gname) {
                            $ret[$row['value']] = [
                                'idShop' => $row['id_shop'],
                                'groupName' => $gname,
                                'idShopComp' => self::getCurrentShopComp($row['id_shop'], $gname),
                            ];
                        }
                    }
                }
            }

            return $ret;
        }

        return null;
    }

    /**
     * @param string $funcName
     *
     * @return string|array<string,string>|null
     */
    public function getConfigFunc($funcName)
    {
        if (isset(InternalConfigLists::getFuncQueryTranslater()[$funcName])) {
            return $this->{InternalConfigLists::getFuncQueryTranslater()[$funcName]}();
        }

        return null;
    }

    /**
     * @return array<string,string|array<string,string>|null>
     */
    public function getConfigFuncs()
    {
        $configFuncs = [];
        foreach (InternalConfigLists::getFuncQueryTranslater() as $key => $_) {
            $configFuncs[$key] = $this->getConfigFunc($key);
        }

        return $configFuncs;
    }

    /**
     * @param array<string,mixed> $inputConfig
     *
     * @return array<string,string>
     */
    public function formatInputConfig($inputConfig)
    {
        $formattedConfig = [];
        foreach ($inputConfig as $key => $value) {
            if (isset(InternalConfigLists::getDbInputTranslater()[$key])) {
                $key = InternalConfigLists::getDbInputTranslater()[$key];
                if (isset(InternalConfigLists::getDbInputTranslaterFunction()[$key])) {
                    $formattedConfig = array_merge($formattedConfig,
                        $this->{InternalConfigLists::getDbInputTranslaterFunction()[$key]}($key, $value));
                } else {
                    $formattedConfig[$key] = $value;
                }
            }
        }

        return $formattedConfig;
    }

    /**
     * @param array<string,string> $inputConfig
     *
     * @return void
     */
    public function upsertDBconfig($inputConfig)
    {
        $currentConfig = $this->getDbConfig();
        $inputConfig = $this->formatInputConfig($inputConfig);
        foreach ($inputConfig as $key => $value) {
            if (!array_key_exists($key, $currentConfig) || $currentConfig[$key] != $inputConfig[$key]) {
                $dbName = $key;
                if (!InternalConfigLists::isNotDbConfigFromGroup($dbName)) {
                    $dbName = $dbName . $this->groupName;
                }
                Configuration::updateValue($dbName, (string) $value,
                    InternalConfigLists::isConfigHtmlValue($key), null, (int) $this->idShop);
            }
        }
    }

    /**
     * @param int $perms
     * @param int $shift
     * @param int $execShift
     *
     * @return string
     */
    private static function getPermWithShifted($perms, $shift, $execShift)
    {
        $execPerm = 0x0200 << $execShift;
        $info = (($perms & ($shift << 2)) ? 'r' : '-');
        $info .= (($perms & ($shift << 1)) ? 'w' : '-');
        if ($perms & $shift) {
            $info .= (($perms & $execPerm) ? 'x' : 's');
        } else {
            $info .= (($perms & $execPerm) ? 'S' : '-');
        }

        return $info;
    }

    /**
     * @return string
     */
    public function getFolderAndFileRightsInfo()
    {
        $perms = fileperms(_PS_MODULE_DIR_ . 'netreviews');
        if (is_int($perms)) {
            if (($perms & 0xC000) == 0xC000) {    // Socket
                $info = 's';
            } elseif (($perms & 0xA000) == 0xA000) { // Symbolic link
                $info = 'l';
            } elseif (($perms & 0x8000) == 0x8000) { // Regular
                $info = '-';
            } elseif (($perms & 0x6000) == 0x6000) { // Block special
                $info = 'b';
            } elseif (($perms & 0x4000) == 0x4000) { // Repository
                $info = 'd';
            } elseif (($perms & 0x2000) == 0x2000) { // Special characters
                $info = 'c';
            } elseif (($perms & 0x1000) == 0x1000) { // pipe FIFO
                $info = 'p';
            } else { // Unknow
                $info = 'u';
            }
            // Others
            $info .= self::getPermWithShifted($perms, 0x40, 2);
            // Group
            $info .= self::getPermWithShifted($perms, 0x8, 1);
            // All
            $info .= self::getPermWithShifted($perms, 0x1, 0);
        } else {
            $info = 'e------';
        }

        return $info;
    }

    /**
     * @return string
     */
    public function getModuleVersion()
    {
        $netreviews = new Netreviews();

        return $netreviews->version;
    }

    /**
     * @return string
     */
    public function getPrestaVersion()
    {
        return _PS_VERSION_;
    }

    /**
     * @return string
     */
    public function getPhpVersion()
    {
        return phpversion();
    }

    /**
     * @param bool $isInput
     *
     * @return array<string,string|null>
     */
    public function getDbConfig($isInput = true)
    {
        if ($isInput) {
            $dataList = InternalConfigLists::getDbInputTranslater();
        } else {
            $dataList = InternalConfigLists::getDbOutputTranslater();
        }

        $config = [];
        foreach ($dataList as $translatedKey => $dbKey) {
            $dbName = $dbKey;
            if (!InternalConfigLists::isNotDbConfigFromGroup($dbName)) {
                $dbName = $dbName . $this->groupName;
            }
            $tmp = Configuration::get($dbName, null, null, $this->idShop);

            if ($tmp === false) {
                $tmp = null;
            }

            $config[$translatedKey] = $tmp;
        }

        return $config;
    }

    /**
     * @return array<string,string>
     */
    public function getOrderStatusList()
    {
        $orderStatusList = OrderState::getOrderStates((int) Configuration::get('PS_LANG_DEFAULT'));
        foreach ($orderStatusList as $key => $orderStatus) {
            $orderStatusList[$key] = [
                'idOrderState' => $orderStatus['id_order_state'],
                'name' => $orderStatus['name'],
            ];
        }

        return $orderStatusList;
    }

    /**
     * @return string|null
     */
    public function getCollectedConsent()
    {
        $ret = Configuration::get('AV_COLLECT_CONSENT' . $this->groupName, null, null, $this->idShop, '');

        if ($ret === false) {
            $ret = null;
        }

        return $ret;
    }

    /**
     * @return string|int|null
     */
    public function getShopId()
    {
        return ($this->idShopComp) ? $this->idShopComp : $this->idShop;
    }

    /**
     * @return array<int|string,string>
     */
    public function getAllConfiguredShops()
    {
        $confWithCurrentIds = [];
        $sql = 'SELECT * FROM ' . _DB_PREFIX_
            . 'configuration WHERE name LIKE "AV_IDWEBSITE%" AND value = "'
            . Configuration::get('AV_IDWEBSITE' . $this->groupName, null, null, $this->idShop) . '"';
        if (($row = Db::getInstance()->ExecuteS($sql)) && is_array($row)) {
            $confWithCurrentIds = $row;
        } else {
            $confWithCurrentIds = [];
        }

        if (!empty($this->multilanguagesShopList)) {
            $configuredShopsInfos = $this->multilanguagesShopList;
        } else {
            $configuredShopsInfos = [];
        }
        if (is_null($this->groupName)) {
            $groupName = '';
        } else {
            $groupName = $this->groupName;
        }
        $langIndex = str_replace('_', '', $groupName);
        $allConfiguredShops = [];

        foreach ($confWithCurrentIds as $value) {
            if (isset($configuredShopsInfos[$value['id_shop']])
                && is_array($configuredShopsInfos[$value['id_shop']])
                && !empty($configuredShopsInfos[$value['id_shop']])
                && array_key_exists($langIndex, $configuredShopsInfos[$value['id_shop']])
                && isset($configuredShopsInfos[$value['id_shop']][$langIndex])
                && is_array($configuredShopsInfos[$value['id_shop']][$langIndex])
                && array_key_exists(0, $configuredShopsInfos[$value['id_shop']][$langIndex])) {
                $allConfiguredShops = [
                    array_key_first($configuredShopsInfos[$value['id_shop']][$langIndex]) => $configuredShopsInfos[$value['id_shop']][$langIndex][0],
                ];
            }
        }

        return $allConfiguredShops;
    }

    /**
     * @return int
     */
    public function getTreatedMultilangshoplist()
    {
        if (empty($this->multilanguagesShopList)) {
            $this->multilanguagesShopList = self::getMultilangshoplist($this->idShop);
        }

        return (!empty($this->multilanguagesShopList)) ? 1 : 0;
    }

    /**
     * @return int
     */
    public function getTotalShops()
    {
        return Shop::getTotalShops();
    }

    /**
     * Handle array to it in semi colon separator
     *
     * @param string $key
     * @param array<int|string,string>|string $data
     *
     * @return array<string,string>
     */
    public static function arrayToSeparedSemicolonString($key, $data)
    {
        if (is_array($data)) {
            return [$key => implode(';', $data)];
        }

        return [$key => $data];
    }

    /**
     * Handle array to json string
     *
     * @param string $key
     * @param array<string>|string $domainMailArray
     *
     * @return array<string,string>
     */
    public static function arrayToJsonArray($key, $domainMailArray)
    {
        if (is_array($domainMailArray)) {
            $encoded = json_encode($domainMailArray);
            if (!is_string($encoded)) {
                $encoded = '[]';
            }

            return [$key => $encoded];
        }

        return [$key => $domainMailArray];
    }

    /**
     * Handle the products widget from api co
     *
     * @param string $key
     * @param string $string
     *
     * @return array<string,string>
     */
    public static function whiteSpaceAndhtmlentities($key, string $string)
    {
        return [$key => htmlentities(str_replace(["\r\n", "\n"], '', $string))];
    }

    /**
     * Handle the products widget from api co
     *
     * @param string $key
     * @param string|int $string
     *
     * @return array<string,string>
     */
    public static function displayReviewsHandler($key, $string)
    {
        if (1 == $string) {
            $displayProductReviews = 'yes';
        } else {
            $displayProductReviews = 'no';
        }

        return [$key => $displayProductReviews];
    }

    /**
     * Handle the products widget from api co
     *
     * @param string $key
     * @param array<array<string,string>> $array
     *
     * @return array<string,string>
     */
    public static function scriptWidgetBrandHandler($key, $array)
    {
        if (isset($array[0]) && count($array[0]) > 0) {
            reset($array[0]);
            $firstKey = key($array[0]);
            $script = $array[0][$firstKey];
        } else {
            $script = '';
        }

        return [$key => htmlentities(str_replace(["\r\n", "\n"], '', $script))];
    }

    /**
     * Handle the products widget from api co
     *
     * @param string $key
     * @param array<string,string> $array
     *
     * @return array<string,string>
     */
    public static function scriptWidgetProductHandler($key, $array)
    {
        $return = [];

        if (isset($array['script'])) {
            $return['AV_RELOADEDSCRIPT'] = htmlentities(str_replace(["\r\n", "\n"], '', $array['script']));
        }

        if (isset($array['tagAverage']) && !empty($array['tagAverage'])) {
            $return['AV_TAGAVERAGE'] = htmlentities(str_replace(["\r\n", "\n"], '', $array['tagAverage']));
        }

        if (isset($array['tagReviews']) && !empty($array['tagReviews'])) {
            $return['AV_TAGREVIEWS'] = htmlentities(str_replace(["\r\n", "\n"], '', $array['tagReviews']));
        }

        return $return;
    }

    /**
     * @return array<mixed>
     */
    public function getAllShopsData()
    {
        if (empty($this->multilanguagesShopList)) {
            $this->multilanguagesShopList = $this->getMultilangshoplist($this->idShop);
        }
        $allShops = Shop::getShops();
        if (!empty($this->multilanguagesShopList)) {
            foreach ($allShops as $key => $oneshop) {
                if (isset($this->multilanguagesShopList[$key])) {
                    $allShops[$key]['multilingual'] = $this->multilanguagesShopList[$key];
                }
            }
        }

        return $allShops;
    }

    /**
     * @return bool
     */
    public function isSkConfigMultilang()
    {
        $idShopQuery = ' AND id_shop = ' . $this->idShop;
        if (empty($this->idShop)) {
            $idShopQuery = ' AND id_shop IS NULL';
        }

        $multilangQuery =
            'SELECT value FROM `' . _DB_PREFIX_ . 'configuration` WHERE `name` = "AV_MULTILINGUE"' . $idShopQuery . ';';
        $multilangResult = Db::getInstance()->executeS($multilangQuery);

        if (!empty($multilangResult[0]['value']) && $multilangResult[0]['value'] === 'checked') {
            return true;
        }

        return false;
    }

    /**
     * @param string $idShop
     *
     * @return array<mixed>|bool|mysqli_result|PDOStatement|resource|null
     */
    public static function hasGenesisConfiguredAccounts($idShop)
    {
        $idShopQuery = ' AND id_shop = ' . $idShop;
        if (empty($idShop)) {
            $idShopQuery = ' AND id_shop IS NULL';
        }

        $query = 'SELECT * FROM `' . _DB_PREFIX_ . 'configuration`
            WHERE (`name` = "AV_RELOADED" OR `name` LIKE "AV_RELOADED\_%") AND (`value` IS NULL OR `value` != 1) ' .
            $idShopQuery;

        return Db::getInstance()->executeS($query);
    }

    /**
     * @param string $idShop
     *
     * @return array<mixed>|bool|mysqli_result|PDOStatement|resource|null
     */
    public static function isReloadedBeforeVersion9($idShop)
    {
        if (!self::hasGenesisConfiguredAccounts($idShop)) {
            $query = 'SELECT * FROM `' . _DB_PREFIX_ . 'configuration`
            WHERE (`name` = "AV_PUSH_ORDERS_ENDPOINT" OR `name` LIKE "AV_PUSH_ORDERS_ENDPOINT\_%")
            AND `value` IS NOT NULL
            AND (id_shop = ' . (int) $idShop . ' OR id_shop IS NULL)
            ';

            $result = Db::getInstance()->executeS($query);

            if (is_array($result) && !empty($result)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int|null $idShop
     *
     * @return int
     *
     * @throws JsonException
     */
    public static function getNbConfiguredLangWebsites($idShop)
    {
        $idShopQuery = ' AND id_shop = ' . $idShop;
        if (empty($idShop)) {
            $idShopQuery = ' AND id_shop IS NULL';
        }

        $groupInfoQuery = 'SELECT * FROM `' . _DB_PREFIX_ . 'configuration` WHERE `name` = "AV_GOUPINFO"' . $idShopQuery;

        $groupInfoResult = (array) Db::getInstance()->executeS($groupInfoQuery);

        $groupInfo = (array) json_decode($groupInfoResult[0]['value'], true, 512, JSON_THROW_ON_ERROR);

        $groupInfo = array_filter($groupInfo, function ($key) use ($groupInfo) {
            return !empty($groupInfo[$key]);
        }, ARRAY_FILTER_USE_KEY);

        if (array_key_exists('root', $groupInfo)) {
            unset($groupInfo['root']);
        }

        return count($groupInfo);
    }

    /**
     * @param string|null $groupName
     *
     * @return void
     */
    public function setGroupName(?string $groupName)
    {
        $this->groupName = $groupName;
    }
}
