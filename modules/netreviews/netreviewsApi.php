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

require_once '../../config/config.inc.php';
require_once '../../init.php';
include_once 'netreviews.php';
require_once 'netreviewsModel.php';

$postData = $_POST;

/* Check data received - Exit if no data received */
if (empty($postData)) {
    $response = [];
    $response['debug'] = 'No POST DATA received';
    $response['return'] = 2;
    echo '#netreviews-start#' . netreviewsModel::acEncodeBase64(
        netreviewsModel::avJsonEncode($response)
    ) . '#netreviews-end#';
    exit;
}

/* Check module state | EXIT if error returned */
$is_active_var = isActiveModule($postData);
if (1 != $is_active_var['return']) {
    echo '#netreviews-start#' . netreviewsModel::acEncodeBase64(
        netreviewsModel::avJsonEncode($is_active_var)
    ) . '#netreviews-end#';
    exit;
}
/* Check module customer identification | EXIT if error returned */
$check_security_var = checkSecurityData($postData);
if (1 != $check_security_var['return']) {
    echo '#netreviews-start#' . netreviewsModel::acEncodeBase64(
        netreviewsModel::avJsonEncode($check_security_var)
    ) . '#netreviews-end#';
    exit;
}
/* ############ START ############ */
/* Switch between each query allowed and sent by NetReviews */
$to_reply = '';
switch ($postData['query']) {
    case 'isActiveModule':
        $to_reply = isActiveModule($postData);
        break;
    case 'setModuleConfiguration':
        $to_reply = setModuleConfiguration($postData);
        break;
    case 'getModuleAndSiteConfiguration':
        $to_reply = getModuleAndSiteConfiguration($postData);
        break;
    case 'setProductsReviews':
        $to_reply = setProductsReviews($postData);
        break;
    case 'getOrders':
        $to_reply = getOrders($postData);
        break;
    case 'setFlag':
        $to_reply = setFlag($postData);
        break;
    case 'truncateTables':
        $to_reply = truncateTables();
        break;
    case 'getLatestOrders':
        $to_reply = getLatestOrders($postData);
        break;
    case 'getProductReviewsStats':
        $to_reply = getProductReviewsStats($postData);
        break;
    default:
        break;
}

/* Displaying functions returns to NetReviews */
echo '#netreviews-start#' . netreviewsModel::acEncodeBase64(
    netreviewsModel::avJsonEncode($to_reply)
) . '#netreviews-end#';

/**
 * Check ID Api Customer
 * Every sent query depends on the return result of this function.
 *
 * @param array<string> $postData : sent parameters
 *
 * @return array<mixed> $response : error code + error
 */
function checkSecurityData(&$postData)
{
    $response = [];
    $unsMsg = iniInfo($postData);
    $idShop = getCurrentShop($unsMsg);
    $groupName = getGroupname($idShop, $unsMsg);

    if (empty($unsMsg)) {
        $response['debug'] = 'empty message';
        $response['return'] = 2;
        $response['query'] = 'checkSecurityData';

        return $response;
    }

    $localWebsiteId = Configuration::get('AV_IDWEBSITE' . $groupName, null, null, $idShop);
    $localSecureKey = Configuration::get('AV_CLESECRETE' . $groupName, null, null, $idShop);

    $response['query'] = 'checkSecurityData';

    if (!$localWebsiteId || !$localSecureKey) {
        $response['debug'] = 'Customer IDs are not specified on the module';
        $response['message'] = 'Customer IDs are not specified on the module';
        $response['return'] = 3;
    } elseif ($unsMsg['idWebsite'] != $localWebsiteId) {
        $response['message'] = 'Wrong ID Website';
        $response['debug'] = 'Wrong ID Website';
        $response['return'] = 4;
    } elseif (sha1($postData['query'] . $localWebsiteId . $localSecureKey) != $unsMsg['sign']) {
        $response['message'] = 'The signature is incorrect';
        $response['debug'] = 'The signature is incorrect';
        $response['return'] = 5;
    } else {
        $response['message'] = 'Identifiants Client Ok';
        $response['debug'] = 'Identifiants Client Ok';
        $response['return'] = 1;
        $response['sign'] = sha1($postData['query'] . $localWebsiteId . $localSecureKey);
    }

    return $response;
}

/**
 * Check if module is installed and enabled.
 *
 * @param array<string> $postData : sent parameters
 *
 * @return array<mixed> $response
 */
function isActiveModule(&$postData)
{
    $response = [];
    $active = false;
    $unsMsg = iniInfo($postData);
    $idShop = !empty($unsMsg['id_shop']) ? (int) $unsMsg['id_shop'] : null;
    if (null == $idShop) {
        $idShopFromPrestashop = getCurrentShop($unsMsg);
        if (null !== $idShopFromPrestashop) {
            $idShop = (int) $idShopFromPrestashop;
        }
    }
    $groupName = getGroupname($idShop, $unsMsg);
    $idShopComp = getCurrentShopComp($idShop, $groupName);

    if (!empty($idShop)) {
        $idModule = Db::getInstance()->getValue('SELECT id_module FROM ' . _DB_PREFIX_ .
                                                'module WHERE name = \'netreviews\'');
        if (Db::getInstance()->getValue('SELECT id_module
                                            FROM ' . _DB_PREFIX_ . 'module_shop
                                            WHERE id_module = ' . (int) $idModule . '
                                            AND id_shop = ' . (int) $idShop)) {
            $active = true;
        }
    } else {
        if (1 == Module::isEnabled('netreviews')) {
            $active = true;
        }
    }
    if (!$active) {
        $response['debug'] = 'Module disabled';
        $response['return'] = 2; // Module disabled
        $response['query'] = 'isActiveModule';
    } else {
        $response['debug'] = 'Module installed and enabled';
        $response['sign'] = sha1(
            $postData['query'] .
            Configuration::get('AV_IDWEBSITE' . $groupName, null, null, $idShop) .
            Configuration::get('AV_CLESECRETE' . $groupName, null, null, $idShop)
        );

        $response['id_shop'] = ($idShopComp) ? $idShopComp : $idShop;
        $response['return'] = 1; // Module OK
        $response['query'] = $postData['query'];
    }

    return $response;
}

/**
 * @param array<string> $postData
 *
 * @return array<mixed>
 */
function setModuleConfiguration(&$postData)
{
    $response = [];
    $unsMsg = iniInfo($postData);
    $idShop = getCurrentShop($unsMsg);
    $groupName = getGroupname($idShop, $unsMsg);
    $idShopComp = getCurrentShopComp($idShop, $groupName);

    if (empty($unsMsg)) {
        $response['debug'] = "Aucune données reçues par le site dans $_POST[message]";
        $response['message'] = "Aucune données reçues par le site dans $_POST[message]";
        $response['query'] = $postData['query'];
        $response['return'] = 2;
        $response['sign'] = sha1(
            $postData['query'] .
            Configuration::get('AV_IDWEBSITE' . $groupName, null, null, $idShop) .
            Configuration::get('AV_CLESECRETE' . $groupName, null, null, $idShop)
        );

        return $response;
    }

    $matchingKeys = getMatchingConfigKeys($unsMsg);

    foreach ($unsMsg as $index => $value) {
        updateValue($index, $matchingKeys, $groupName, $idShop, $unsMsg);
    }

    $response['sign'] = sha1(
        $postData['query'] .
        Configuration::get('AV_IDWEBSITE' . $groupName, null, null, $idShop) .
        Configuration::get('AV_CLESECRETE' . $groupName, null, null, $idShop)
    );
    $response['message'] = getModuleAndSiteInfos($idShop, $idShopComp, $groupName);
    $response['debug'] = 'La configuration du site a été mise à jour';
    $response['return'] = 1;
    $response['query'] = $postData['query'];

    return $response;
}

/**
 * @param array<mixed> $unsMsg
 *
 * @return array<int|string|array<string>>
 */
function getMatchingConfigKeys($unsMsg)
{
    // Handle depending on Genesis or Reloaded
    $displayProductReviews = 'no';
    if (isset($unsMsg['displayReviews'])) {
        $displayProductReviews = 'no';
        if (1 == $unsMsg['displayReviews']) {
            $displayProductReviews = 'yes';
        }
    }

    $getProductReviews = 'no';
    if (isset($unsMsg['hasProductReviewCollection'])) {
        $getProductReviews = 'no';
        if (1 == $unsMsg['hasProductReviewCollection']) {
            $getProductReviews = 'yes';
        }
    }

    $scriptFixeWidget = '';
    if (isset($unsMsg['script_fixe_widget'])) {
        $scriptFixeWidget = htmlentities(str_replace(["\r\n", "\n"], '', $unsMsg['script_fixe_widget']));
    }

    $scriptFloatWidget = '';
    if (isset($unsMsg['script_float_widget'])) {
        $scriptFloatWidget = htmlentities(str_replace(["\r\n", "\n"], '', $unsMsg['script_float_widget']));
    }

    $scriptWidgetBrand = '';
    if (isset($unsMsg['scriptWidgetBrand'])) {
        $scriptWidgetBrand = htmlentities(str_replace(["\r\n", "\n"], '', $unsMsg['scriptWidgetBrand']));
    }

    $choosenOrders = (isset($unsMsg['id_order_status_choosen']) ?
        $unsMsg['id_order_status_choosen'] :
        (isset($unsMsg['choosenStatus']) ? $unsMsg['choosenStatus'] : '')
    );
    $orderstatechoosen = (is_array($choosenOrders)) ?
        implode(';', $choosenOrders) :
        $choosenOrders;

    $forbiddenEmails = (isset($unsMsg['forbidden_mail_extension']) ?
        $unsMsg['forbidden_mail_extension'] :
        (isset($unsMsg['blacklistedExtensions']) ? $unsMsg['blacklistedExtensions'] : '')
    );
    $forbiddenEmailExtensions = (is_array($forbiddenEmails)) ?
        implode(';', $forbiddenEmails) :
        $forbiddenEmails;

    return [
        'init_reviews_process' => [
            'ps_key' => 'AV_PROCESSINIT',
        ],
        'get_product_reviews' => [
            'ps_key' => 'AV_GETPRODREVIEWS',
        ],
        'hasProductReviewCollection' => [
            'ps_key' => 'AV_GETPRODREVIEWS',
            'ps_value' => $getProductReviews,
        ],
        'display_product_reviews' => [
            'ps_key' => 'AV_DISPLAYPRODREVIEWS',
        ],
        'displayReviews' => [
            'ps_key' => 'AV_DISPLAYPRODREVIEWS',
            'ps_value' => $displayProductReviews,
        ],
        'display_fixe_widget' => [
            'ps_key' => 'AV_SCRIPTFIXE_ALLOWED',
        ],
        'position_fixe_widget' => [
            'ps_key' => 'AV_SCRIPTFIXE_POSITION',
        ],
        'display_float_widget' => [
            'ps_key' => 'AV_SCRIPTFLOAT_ALLOWED',
        ],
        'url_certificat' => [
            'ps_key' => 'AV_URLCERTIFICAT',
        ],
        'code_lang' => [
            'ps_key' => 'AV_CODE_LANG',
        ],
        'collect_consent' => [
            'ps_key' => 'AV_COLLECT_CONSENT',
        ],
        'delay' => [
            'ps_key' => 'AV_DELAY',
        ],
        'brandDelay' => [
            'ps_key' => 'AV_DELAY',
        ],
        'Delay_by_status' => [
            'ps_key' => 'AV_DELAY_BYSTATUS',
        ],
        'delay_product' => [
            'ps_key' => 'AV_DELAY_PRODUIT',
        ],
        'productDelay' => [
            'ps_key' => 'AV_DELAY_PRODUIT',
        ],
        'id_order_status_choosen' => [
            'ps_key' => 'AV_ORDERSTATESCHOOSEN',
            'ps_value' => $orderstatechoosen,
        ],
        'choosenStatus' => [
            'ps_key' => 'AV_ORDERSTATESCHOOSEN',
            'ps_value' => $orderstatechoosen,
        ],
        'forbidden_mail_extension' => [
            'ps_key' => 'AV_FORBIDDEN_EMAIL',
            'ps_value' => $forbiddenEmailExtensions,
        ],
        'blacklistedExtensions' => [
            'ps_key' => 'AV_FORBIDDEN_EMAIL',
            'ps_value' => $forbiddenEmailExtensions,
        ],
        'script_fixe_widget' => [
            'ps_key' => 'AV_SCRIPTFIXE',
            'ps_value' => $scriptFixeWidget,
            'ps_html' => true,
        ],
        'script_float_widget' => [
            'ps_key' => 'AV_SCRIPTFLOAT',
            'ps_value' => $scriptFloatWidget,
            'ps_html' => true,
        ],
        'scriptWidgetBrand' => [
            'ps_key' => 'AV_SCRIPTFLOAT',
            'ps_value' => $scriptWidgetBrand,
            'ps_html' => true,
        ],
        'reloaded' => [
            'ps_key' => 'AV_RELOADED',
        ],
        'activateCollect' => [
            'ps_key' => 'AV_ACTIVATE_COLLECT',
        ],
        'clientid' => [
            'ps_key' => 'AV_CLIENTID',
        ],
        'secretapi' => [
            'ps_key' => 'AV_SECRETAPI',
        ],
        'countryCode' => [
            'ps_key' => 'AV_PLATEFORME',
        ],
        'orderLimit' => [
            'ps_key' => 'AV_ORDER_LIMIT',
        ],
    ];
}

/**
 * @param string $index
 * @param array<int|string|array<string|array<string>>> $matchingKeys
 * @param string $groupName
 * @param string|int $idShop
 * @param array<mixed> $unsMsg
 *
 * @return void
 */
function updateValue($index, $matchingKeys, $groupName, $idShop, $unsMsg)
{
    if (array_key_exists($index, $matchingKeys)) {
        $key = $matchingKeys[$index]['ps_key'] . $groupName;
        $value = isset($matchingKeys[$index]['ps_value']) ? $matchingKeys[$index]['ps_value'] : $unsMsg[$index];
        $html = isset($matchingKeys[$index]['ps_html']) ? true : false;
        Configuration::updateValue(
            $key,
            $value,
            $html,
            null,
            $idShop
        );
    }
    if ('scriptWidgetProduct' == $index) {
        if (empty($unsMsg['scriptWidgetProduct'])) {
            Configuration::updateValue('AV_RELOADEDSCRIPT' . $groupName, '', false, null, $idShop);
            Configuration::updateValue('AV_TAGAVERAGE' . $groupName, '', false, null, $idShop);
            Configuration::updateValue('AV_TAGREVIEWS' . $groupName, '', false, null, $idShop);
        } else {
            $productWidgetKeys = [
                'AV_RELOADEDSCRIPT' => 'script',
                'AV_TAGAVERAGE' => 'tagAverage',
                'AV_TAGREVIEWS' => 'tagReviews',
            ];
            foreach ($productWidgetKeys as $index => $value) {
                if ('tagAverage' == $value || 'tagReviews' == $value) {
                    Configuration::updateValue(
                        $index . $groupName,
                        htmlentities(str_replace(["\r\n", "\n"], '', json_decode($unsMsg['scriptWidgetProduct'][$value]))),
                        true,
                        null,
                        $idShop
                    );
                } else {
                    Configuration::updateValue(
                        $index . $groupName,
                        htmlentities(str_replace(["\r\n", "\n"], '', $unsMsg['scriptWidgetProduct'][$value])),
                        true,
                        null,
                        $idShop
                    );
                }
            }
        }
    }
}

/**
 * Get module and site configuration.
 *
 * @param array<mixed> $postData : sent parameters
 *
 * @return array<mixed> $response : array to debug info
 */
function getModuleAndSiteConfiguration(&$postData)
{
    $response = [];
    $unsMsg = iniInfo($postData);
    $idShop = getCurrentShop($unsMsg);
    $groupName = getGroupname($idShop, $unsMsg);
    $idShopComp = getCurrentShopComp($idShop, $groupName);

    $response['message'] = getModuleAndSiteInfos($idShop, $idShopComp, $groupName);
    $response['id_shop'] = ($idShopComp) ? $idShopComp : $idShop;
    $response['sign'] = sha1(
        $postData['query'] .
        Configuration::get('AV_IDWEBSITE' . $groupName, null, null, $idShop) .
        Configuration::get('AV_CLESECRETE' . $groupName, null, null, $idShop)
    );
    $response['query'] = $unsMsg['query'];
    $response['return'] = 1;
    if (empty($response['message'])) {
        $response['return'] = 2;
    }

    return $response;
}

/**
 * Get module and site infos
 * Private function, do not use it. This function is called in setModuleConfiguration and getModuleConfiguration.
 *
 * @param string|int|null $idShop
 * @param string|int|null $idShopComp
 * @param string|null $groupName
 *
 * @return array<mixed> with info data
 *
 * @throws PrestaShopDatabaseException
 */
function getModuleAndSiteInfos($idShop = null, $idShopComp = null, $groupName = null)
{
    $moduleVersion = new Netreviews();
    $moduleVersion = $moduleVersion->version;
    $orderStatusList = getOrderStatusList();
    $info = getFolderAndFileRightsInfo();
    $explodeSecretKey = explode('-', Configuration::get('AV_CLESECRETE' . $groupName, null, null, $idShop));

    $return = [
        'Version_PS' => _PS_VERSION_,
        'Version_PHP' => phpversion(),
        'Version_Module' => $moduleVersion,
        'Date_Installation_Module' => Configuration::get('AV_LIMIT_LOST_ORDERS', null, null, $idShop),
        'idWebsite' => Configuration::get('AV_IDWEBSITE' . $groupName, null, null, $idShop),
        'AV_RELOADED' => Configuration::get('AV_RELOADED' . $groupName, null, null, $idShop),
        'AV_ACTIVATE_COLLECT' => Configuration::get('AV_ACTIVATE_COLLECT' . $groupName, null, null, $idShop),
        'AV_CLIENTID' => Configuration::get('AV_CLIENTID' . $groupName, null, null, $idShop),
        'AV_SECRETAPI' => Configuration::get('AV_SECRETAPI' . $groupName, null, null, $idShop),
        'Nb_Multiboutique' => '',
        'Mode_multilingue' => 0,
        'list_iso_lang_multilingue' => '',
        'Websites' => '',
        'Current_shop_id' => ($idShopComp) ? $idShopComp : $idShop,
        'Cle_Secrete' => $explodeSecretKey[0] . '-xxxx-xxxx-' . $explodeSecretKey[3],
        'Delay' => Configuration::get('AV_DELAY' . $groupName, null, null, $idShop),
        'Delay_by_status' => Configuration::get('AV_DELAY_BYSTATUS' . $groupName, null, null, $idShop),
        'Delay_product' => Configuration::get('AV_DELAY_PRODUIT' . $groupName, null, null, $idShop),
        'Initialisation_du_Processus' => Configuration::get('AV_PROCESSINIT' . $groupName, null, null, $idShop),
        'Statut_choisi' => Configuration::get('AV_ORDERSTATESCHOOSEN' . $groupName, null, null, $idShop),
        'Recuperation_Avis_Produits' => Configuration::get('AV_GETPRODREVIEWS' . $groupName, null, null, $idShop),
        'Affiche_Avis_Produits' => Configuration::get('AV_DISPLAYPRODREVIEWS' . $groupName, null, null, $idShop),
        'Affichage_Widget_Flottant' => Configuration::get('AV_SCRIPTFLOAT_ALLOWED' . $groupName, null, null, $idShop),
        'Script_Widget_Flottant' => Configuration::get('AV_SCRIPTFLOAT' . $groupName, null, null, $idShop),
        'Affichage_Widget_Fixe' => Configuration::get('AV_SCRIPTFIXE_ALLOWED' . $groupName, null, null, $idShop),
        'Position_Widget_Fixe' => Configuration::get('AV_SCRIPTFIXE_POSITION' . $groupName, null, null, $idShop),
        'Script_Widget_Fixe' => Configuration::get('AV_SCRIPTFIXE' . $groupName, null, null, $idShop),
        'Emails_Interdits' => Configuration::get('AV_FORBIDDEN_EMAIL' . $groupName, null, null, $idShop),
        'Liste_des_statuts' => $orderStatusList,
        'Droit_du_dossier_AV' => $info,
        'Date_Recuperation_Config' => date('Y-m-d H:i:s'),
        'Collected_consent' => Configuration::get('AV_COLLECT_CONSENT' . $groupName, null, null, $idShop) ?
            Configuration::get('AV_COLLECT_CONSENT' . $groupName, null, null, $idShop) : '',
        'AV_SCRIPTTAGJS' => Configuration::get('AV_SCRIPTTAGJS' . $groupName, null, null, $idShop),
        'AV_AVERAGETAGJS' => Configuration::get('AV_AVERAGETAGJS' . $groupName, null, null, $idShop),
        'AV_REVIEWSTAGJS' => Configuration::get('AV_REVIEWSTAGJS' . $groupName, null, null, $idShop),
    ];

    $multilanguagesShopList = getMultilangshoplist($idShop);
    $return['Mode_multilingue'] = (!empty($multilanguagesShopList)) ? 1 : 0;

    $return['list_iso_lang_multilingue'] = getAllConfiguredShops($groupName, $idShop, $multilanguagesShopList);

    if (1 == Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')) {
        $return['Nb_Multiboutique'] = Shop::getTotalShops();
        $return['Websites'] = getAllShopsData($multilanguagesShopList);
    }

    return $return;
}

/**
 * @return array<mixed>
 */
function getOrderStatusList()
{
    $orderStatusList = OrderState::getOrderStates((int) Configuration::get('PS_LANG_DEFAULT'));
    $allowedKeys = ['id_order_state', 'name', 'id_lang'];
    foreach ($orderStatusList as $key => $orderStatus) {
        $orderStatusList[$key] = array_intersect_key($orderStatus, array_flip($allowedKeys));
    }

    return $orderStatusList;
}

/**
 * @return string
 */
function getFolderAndFileRightsInfo()
{
    $perms = fileperms(_PS_MODULE_DIR_ . 'netreviews');
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
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x') : (($perms & 0x0800) ? 'S' : '-'));
    // Group
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x') : (($perms & 0x0400) ? 'S' : '-'));
    // All
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x') : (($perms & 0x0200) ? 'T' : '-'));

    return $info;
}

/**
 * @param string $groupName
 * @param int $idShop
 * @param $multilanguagesShopList
 *
 * @return array<mixed>
 *
 * @throws PrestaShopDatabaseException
 */
function getAllConfiguredShops($groupName, $idShop, $multilanguagesShopList)
{
    $confWithCurrentIds = [];
    $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'configuration WHERE name LIKE "AV_IDWEBSITE%" AND value =
    "' . Configuration::get('AV_IDWEBSITE' . $groupName, null, null, $idShop) . '"';
    if ($row = Db::getInstance()->ExecuteS($sql)) {
        $confWithCurrentIds = $row;
    }

    $configuredShopsInfos = $multilanguagesShopList;
    $langIndex = str_replace('_', '', $groupName);
    $allConfiguredShops = [];

    foreach ($confWithCurrentIds as $value) {
        if (isset($configuredShopsInfos[$value['id_shop']]) && !empty($configuredShopsInfos[$value['id_shop']])) {
            $allConfiguredShops[$value['id_shop']] = $configuredShopsInfos[$value['id_shop']][$langIndex];
        }
    }

    return $allConfiguredShops;
}

/**
 * @param array<mixed> $multilanguagesShopList
 *
 * @return array<mixed>
 */
function getAllShopsData($multilanguagesShopList)
{
    $allShops = Shop::getShops();
    if (!empty($multilanguagesShopList)) {
        foreach ($allShops as $key => $oneshop) {
            if (isset($multilanguagesShopList[$oneshop['id_shop']])) {
                $allShops[$key]['multilingual'] = $multilanguagesShopList[$oneshop['id_shop']];
            }
        }
    }

    return $allShops;
}

/**
 * @param int|string|null $idShop
 * @param array<mixed> $unsMsg
 *
 * @return string
 */
function getGroupname($idShop, $unsMsg)
{
    $groupName = '';
    if ('checked' == Configuration::get('AV_MULTILINGUE', null, null, $idShop)) {
        $idShopFilterSql = (!empty($idShop)) ? 'AND id_shop = ' . (int) $idShop : '';
        $sql = 'SELECT name
        FROM ' . _DB_PREFIX_ . "configuration
        WHERE value = '" . pSQL($unsMsg['idWebsite']) . "'
        AND name like 'AV_IDWEBSITE_%'" . $idShopFilterSql;

        $row = Db::getInstance()->getRow($sql);
        if ($row) {
            $groupName = '_' . Tools::substr($row['name'], 13);
        }
    }

    return $groupName;
}

/**
 * @param array<mixed> $unsMsg
 *
 * @return string|int
 */
function getCurrentShop($unsMsg)
{
    $sql = 'SELECT id_shop
        FROM ' . _DB_PREFIX_ . "configuration
        WHERE value = '" . pSQL($unsMsg['idWebsite']) . "'
        AND name like 'AV_IDWEBSITE%' ";
    $row = Db::getInstance()->getRow($sql);
    if (!empty($row) && isset($row['id_shop'])) {
        return $row['id_shop'];
    }

    return '';
}

/**
 * @param int|string $idShop
 * @param string $groupName
 *
 * @return string
 */
function getCurrentShopComp($idShop, $groupName)
{
    $currentShopComp = '';

    $multilanguagesShopList = getMultilangshoplist($idShop);

    if (!empty($multilanguagesShopList) && !empty($groupName)) {
        $langIndex = str_replace('_', '', $groupName);
        if (isset($multilanguagesShopList[$idShop][$langIndex][0])) {
            $currentShopComp = $idShop . '_' . $multilanguagesShopList[$idShop][$langIndex][0];
        }
    }

    return $currentShopComp;
}

/**
 * @param string|int $idShop
 *
 * @return array<mixed>
 *
 * @throws PrestaShopDatabaseException
 */
function getMultilangshoplist($idShop)
{
    $multilanguagesShopList = [];
    $idShopFilterSql = (!empty($idShop)) ? '
     AND id_shop = ' . (int) $idShop : '
     AND (id_shop IN (0, 1) OR id_shop IS NULL)';
    $sql = 'SELECT id_shop,value FROM ' . _DB_PREFIX_ . 'configuration WHERE name LIKE
    "AV_GROUP_CONF%"' . $idShopFilterSql;
    $rows = Db::getInstance()->ExecuteS($sql);
    if (is_array($rows)) {
        $multilanguagesShopList = [];
        foreach ($rows as $r_element) {
            if ('checked' == Configuration::get('AV_MULTILINGUE', null, null, $r_element['id_shop'])) {
                $multilanguagesShopList[$r_element['id_shop']][] = netreviewsModel::avJsonDecode($r_element['value']);
            }
        }
    }

    return $multilanguagesShopList;
}

/**
 * @param array<string> $postData
 *
 * @return array<int|string|array<string>>
 */
function iniInfo($postData)
{
    $getMessage = json_decode(netreviewsModel::acDecodeBase64($postData['message']), true);
    $getMessageDecode = json_decode(netreviewsModel::acDecodeBase64SetP($postData['message']), true);

    return $getMessage ? $getMessage : $getMessageDecode;
}

/**
 * @param string $message
 * @param int $severity
 *
 * @return void
 */
function addLog($message, $severity = 2)
{
    $now = new DateTime();
    $now->setTimezone(new DateTimeZone('Europe/Paris'));
    $dateTime = $now->format('Y-m-d H:i:s');
    $fp = fopen(dirname(__FILE__) . '/logs.txt', 'a+'); // path: prestashop\modules\netreviews
    if ($fp) {
        fwrite($fp, $dateTime . ' [Netreviews - ' . $severity . '] ' . Tools::safeOutput($message) . PHP_EOL . PHP_EOL);
        fclose($fp);
    }
}

/**
 * Product reviews update.
 *
 * @param $postData : sent parameters
 */
function setProductsReviews(&$postData)
{
    $response = [];
    $microtimeDeb = microtime();
    $unsMsg = iniInfo($postData);
    $reviews = (!empty($unsMsg['data'])) ? json_decode($unsMsg['data'], true) : null;

    // $id_shop = getCurrentShop($unsMsg);
    $multisite = Configuration::get('AV_MULTISITE');

    if (!empty($multisite) && isset($unsMsg['id_shop'])) {
        $idShop = $unsMsg['id_shop'];
    } else {
        $idShop = null;
    }

    if (!is_numeric($idShop)) {
        $decomposeIdShop = explode('_', $idShop);
        $idShop = $decomposeIdShop[0];
    }

    $groupName = getGroupname($idShop, $unsMsg);

    $avGroupName = 'AV_GROUP_CONF' . $groupName;

    $idShopFilterSql = (!empty($idShop)) ? ' AND id_shop = ' . (int) $idShop :
        ' AND (id_shop IN (0, 1) OR id_shop IS NULL)';
    $sql = '
    SELECT value
    FROM ' . _DB_PREFIX_ . 'configuration
    WHERE name = "' . pSQL($avGroupName) . '"' . $idShopFilterSql;
    if ($row = Db::getInstance()->getRow($sql)) {
        $listIsoLangMultilingue = netreviewsModel::avJsonDecode($row['value']);
        $isoLang = '"' . pSQL($listIsoLangMultilingue[0]) . '"';
    } else {
        $isoLang = '0';
    }

    // add horodate_order if colcumn dosen't exsit
    $orderDateAdded = Db::getInstance()->getRow(
        'SELECT * FROM ' . _DB_PREFIX_ . 'av_products_reviews'
    );
    if (is_array($orderDateAdded) && !array_key_exists('horodate_order', $orderDateAdded)) {
        Db::getInstance()->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'av_products_reviews`
            ADD `horodate_order` TEXT NOT NULL AFTER `horodate`');
    } elseif (is_array($orderDateAdded)
        && !array_key_exists('helpful', $orderDateAdded)
        && !array_key_exists('helpless', $orderDateAdded)
    ) {
        Db::getInstance()->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'av_products_reviews`
            ADD `helpful` int(7) DEFAULT 0,
            ADD `helpless` int(7) DEFAULT 0');
    } elseif (is_array($orderDateAdded) && !array_key_exists('media_full', $orderDateAdded)) {
        Db::getInstance()->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'av_products_reviews`
            ADD `media_full` TEXT');
    }

    $response['message']['nb_new'] = isset($reviews['NEW']) ? changeProductReviews(
        'NEW',
        $reviews['NEW'],
        $isoLang,
        $idShop
    ) : 0;
    $response['message']['nb_update'] = isset($reviews['UPDATE']) ?
        changeProductReviews('UPDATE', $reviews['UPDATE'], $isoLang, $idShop) : 0;
    $response['message']['nb_delete'] = isset($reviews['DELETE']) ?
        changeProductReviews('DELETE', $reviews['DELETE'], $isoLang, $idShop) : 0;
    $response['message']['nb_average'] = isset($reviews['AVG']) ?
        changeProductAverage($reviews['AVG'], $isoLang, $idShop) : 0;

    $microtimeFin = microtime();
    $response['return'] = 1;
    $response['sign'] = sha1(
        $postData['query'] .
        Configuration::get('AV_IDWEBSITE' . $groupName, null, null, $idShop) .
        Configuration::get('AV_CLESECRETE' . $groupName, null, null, $idShop)
    );
    $response['query'] = $postData['query'];
    $response['message']['microtime'] = (float) $microtimeFin - (float) $microtimeDeb;
    // ****************** Check Received Number of Reviews vs Saved Number of Reviews *****************
    $savedModule = $response['message']['nb_new'] + $response['message']['nb_update'] + $response['message']['nb_delete']
        + $response['message']['nb_average'];
    $receivedNew = isset($reviews['NEW']) ? count($reviews['NEW']) : 0;
    $receivedUpdate = isset($reviews['UPDATE']) ? count($reviews['UPDATE']) : 0;
    $receivedDelete = isset($reviews['DELETE']) ? count($reviews['DELETE']) : 0;
    $receivedAvg = isset($reviews['AVG']) ? count($reviews['AVG']) : 0;
    if ($savedModule != ($receivedNew + $receivedUpdate + $receivedDelete + $receivedAvg)) {
        $response['debug'][] = 'An error occured. Mismatch between number
         of reviews received and the number of reviews saved in DB';
    }

    // ******************** End Check ******************************************************************
    return $response;
}

// Update the average for each product
function changeProductAverage(&$averages, $isoLang, $idShop)
{
    $count = 0;
    foreach ($averages as $average) {
        Db::getInstance()->Execute('REPLACE INTO ' . _DB_PREFIX_ . 'av_products_average
                                    (id_product_av, ref_product, rate, nb_reviews,
                                    horodate_update,iso_lang,id_shop)
                                    VALUES (\'' . pSQL($average['idProduit']) . '\',
                                    \'' . pSQL($average['refProduit']) . '\',
                                    \'' . round((float) $average['averageProduit'], 2) . '\',
                                    \'' . (int) $average['nbAvisProduit'] . '\',
                                    \'' . time() . '\',
                                    ' . $isoLang . ',
                                    ' . (int) $idShop . '
                                    )');
        ++$count;
    }

    return $count;
}

// Update Product Reviews with new reviews, changed reviews or deleted reviews
function changeProductReviews($type, &$reviews, $isoLang, $idShop)
{
    $count = 0;
    if (('NEW' == $type) || ('UPDATE' == $type)) {
        foreach ($reviews as $review) {
            $name = '';
            if (isset($review['name'][0])) {
                $name = $review['name'][0];
            }

            if (isset($review['moderation'])) {
                $moderation = $review['moderation'];
            } else {
                $moderation = [];
            }
            Db::getInstance()->Execute('REPLACE INTO ' . _DB_PREFIX_ . 'av_products_reviews
            (id_product_av, ref_product, rate, review, horodate, customer_name,horodate_order,
            discussion,helpful,helpless,media_full,iso_lang,id_shop)
            VALUES (\'' . pSQL($review['idProduit']) . '\',
                    \'' . (int) $review['refProduit'] . '\',
                    \'' . round((float) $review['rate'], 2) . '\',
                    \'' . pSQL($review['avis']) . '\',
                    \'' . pSQL($review['horodateAvis']) . '\',
                    \'' . pSQL(Tools::ucfirst($name) . '. ' . Tools::ucfirst($review['prenom'])) . '\',
                    \'' . pSQL($review['horodateCommande']) . '\',
                    \'' . pSQL(netreviewsModel::acEncodeBase64(netreviewsModel::avJsonEncode($moderation))) . '\',
                    \'' . pSQL($review['count_helpful_yes']) . '\',
                    \'' . pSQL($review['count_helpful_no']) . '\',
                    \'' . pSQL(urldecode(netreviewsModel::acDecodeBase64($review['media_full']))) . '\',
                    ' . $isoLang . ',
                    ' . (int) $idShop . '
                    )');
            ++$count;
        }
    } elseif ('DELETE' == $type) {
        foreach ($reviews as $review) {
            if ('checked' == Configuration::get('AV_MULTILINGUE', null, null, $idShop)) {
                Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . 'av_products_reviews
                                                WHERE id_product_av = \'' . pSQL($review['idProduit']) . '\'
                                                AND ref_product = \'' . (int) $review['refProduit'] . '\'
                                                AND iso_lang = ' . $isoLang . '
                                                AND id_shop = ' . (int) $idShop);
            } else {
                Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . 'av_products_reviews
                                                WHERE id_product_av = \'' . pSQL($review['idProduit']) . '\'
                                                AND ref_product = \'' . (int) $review['refProduit'] . '\'
                                                AND id_shop = ' . (int) $idShop);
            }
            ++$count;
        }
    }

    return $count;
}

function getOrders(&$postData)
{
    $nrModel = new netreviewsModel();
    $response = [];
    $postMessage = iniInfo($postData);
    $idShop = getCurrentShop($postMessage);
    $groupName = getGroupname($idShop, $postMessage);
    $messageLang = null;
    if (isset($postMessage['iso_lang'])) {
        $messageLang = $postMessage['iso_lang'];
    }
    $processChoosen = Configuration::get('AV_PROCESSINIT' . $groupName, null, null, $idShop);
    $forbiddenEmailsExtensions = getForbiddenEmailExtensions($idShop, $groupName);
    $productDelay = Configuration::get('AV_DELAY_PRODUIT' . $groupName, null, null, $idShop);
    $brandDelay = Configuration::get('AV_DELAY' . $groupName, null, null, $idShop);

    $ordersList = getOrdersList($idShop, $groupName, $messageLang);

    $response['debug']['query'] = $nrModel->getQueryToGetOrders($idShop, $groupName, 'genesis', $messageLang);
    $response['debug']['mode'] = '[' . $processChoosen . '] ' . count($ordersList) . ' orders matching with
        configuration';

    $finalOrdersList = [];

    if (!empty($ordersList)) {
        foreach ($ordersList as $order) {
            if ($nrModel->isOrderWithoutConsent($idShop, $groupName, $order['id_order'])) {
                continue;
            }

            $orderCustomer = new Customer($order['id_customer']);
            $customerEmailExtension = explode('@', $orderCustomer->email);

            if (in_array($customerEmailExtension[1], $forbiddenEmailsExtensions)) {
                $response['message']['Emails_Interdits'][] = 'Commande n°' .
                    $order['id_order'] . ' Email:' . $orderCustomer->email;
                continue;
            }

            $finalOrdersList[$order['id_order']] = getOrderData($idShop, $groupName, $order);
            $nrModel->flagOrder($postMessage['no_flag'], $order['id_order']);
        }
    }

    $nbOrdersPurge = purgeAvOrdersTable();

    $response['return'] = 1;
    $response['query'] = $postMessage['query'];
    $response['message']['nb_orders'] = count($finalOrdersList);
    $response['message']['list_orders'] = $finalOrdersList;
    $response['debug']['force'] = $postMessage['force'];
    $response['debug']['no_flag'] = $postMessage['no_flag'];
    $response['debug']['purge'] = '[purge] ' . $nbOrdersPurge . ' commandes purgées';
    $response['message']['delay_product'] = $productDelay;
    $response['message']['delay'] = $brandDelay;
    $response['sign'] = sha1(
        $postMessage['query'] .
        Configuration::get('AV_IDWEBSITE' . $groupName, null, null, $idShop) .
        Configuration::get('AV_CLESECRETE' . $groupName, null, null, $idShop)
    );

    return $response;
}

/**
 * @return false|string|null
 */
function purgeAvOrdersTable()
{
    $nbOrdersPurge = Db::getInstance()->getValue(
        'SELECT count(id_order) FROM ' . _DB_PREFIX_ . 'av_orders
        WHERE horodate_now < DATE_SUB(NOW(), INTERVAL 6 MONTH)'
    );
    Db::getinstance()->Execute(
        'DELETE FROM ' . _DB_PREFIX_ . 'av_orders WHERE horodate_now < DATE_SUB(NOW(), INTERVAL 6 MONTH)'
    );

    return $nbOrdersPurge;
}

function getOrdersList($idShop, $groupName, $messageLang = null)
{
    $nrModel = new netreviewsModel();
    $query = $nrModel->getQueryToGetOrders($idShop, $groupName, 'genesis', $messageLang);

    return Db::getInstance()->ExecuteS($query);
}

function getForbiddenEmailExtensions($idShop, $groupName)
{
    $forbiddenMailExtensions = explode(
        ';',
        Configuration::get('AV_FORBIDDEN_EMAIL' . $groupName, null, null, $idShop)
    );

    return array_map('trim', $forbiddenMailExtensions);
}

function getOrderData($idShop, $groupName, $order)
{
    $productException = []; // rendre optionel la demande d'avis pour les id produit contenu dans ce tableau - 15

    $orderDetail = new Order($order['id_order']);
    $orderCustomer = new Customer($order['id_customer']);

    $orderStatusList = OrderState::getOrderStates((int) Configuration::get('PS_LANG_DEFAULT'));
    $orderStatusIndice = [];
    foreach ((array) $orderStatusList as $value) {
        $orderStatusIndice[$value['id_order_state']] = $value['name'];
    }

    $allowedProducts = Configuration::get('AV_GETPRODREVIEWS' . $groupName, null, null, $idShop);

    $specificDelay = null;
    $specificProductDelay = null;
    $productDelay = (int) Configuration::get(
        'AV_DELAY_PRODUIT' . $groupName,
        null,
        null,
        $order['id_shop']
    );
    if (!empty($productDelay) && !empty($specificDelay)) {
        $specificProductDelay = $specificDelay + $productDelay;
    }
    $orderReference = (isset($orderDetail->reference)
        && !empty($orderDetail->reference)) ? $orderDetail->reference : '';
    $carrier = new Carrier((int) $orderDetail->id_carrier);
    $numState = $order['state_order'];
    $arrayOrder = [
        'id_order' => $order['id_order'],
        'reference' => $orderReference,
        'payment' => $orderDetail->payment,
        'carrier' => $carrier->name,
        'id_lang' => $order['id_lang'],
        'iso_lang' => pSQL(Language::getIsoById($order['id_lang'])),
        'id_shop' => $order['id_shop'],
        'amount_order' => $order['total_paid'],
        'id_customer' => $order['id_customer'],
        'state_order' => $orderStatusIndice[$numState] . '(' . $numState . ')',
        'state_order_id' => $numState, //  Status number
        'date_order' => strtotime($order['date_order']),
        'date_last_status_change' => $order['date_last_status'],
        'date_order_formatted' => $order['date_order'],
        'firstname_customer' => $orderCustomer->firstname,
        'lastname_customer' => $orderCustomer->lastname,
        'email_customer' => $orderCustomer->email,
        'delay_commande_specifique' => $specificDelay,
        'products' => [],
    ];
    //  Add products to array
    if (!empty($allowedProducts) && 'yes' == $allowedProducts) {
        $productsOrder = $orderDetail->getProducts();
        $arrayProducts = [];
        $i = 0;
        $maxProduct = (int) Configuration::get(
            'AV_NBOPRODUCTS' . $groupName,
            null,
            null,
            $order['id_shop']
        );
        $shopName = Configuration::get('PS_SHOP_NAME');
        foreach ($productsOrder as $productElement) {
            $nrModel = new netreviewsModel();

            if (!in_array($productElement['product_id'], $productException)) {
                $productData = $nrModel->getProductData($productElement, $order['id_lang'], $order['id_shop']);

                $product = [
                    'id_product' => $productElement['product_id'],
                    'name_product' => $productElement['product_name'],
                    'SKU' => $productData['sku'],
                    'GTIN_EAN' => $productData['ean13'],
                    'GTIN_UPC' => $productData['upc'],
                    'MPN' => $productData['mpn'],
                    'brand_name' => (isset($productData['brandName']) && !empty($productData['brandName'])) ?
                        $productData['brandName'] : $shopName,
                    'category' => $productData['category'],
                    'url_image' => $nrModel::getUrlProduct(
                        $productElement['product_id'],
                        $order['id_lang'],
                        $nrModel->getProductTitleImageId($productElement)
                    ),
                    'url' => $nrModel::getUrlProduct($productElement['product_id'], $order['id_lang'], null, $order['id_shop']),
                    'delay_produit_specifique' => $specificProductDelay,
                    'product_price_unity' => $productElement['product_price'],
                ];
                // limit product reviews
                if (isset($maxProduct) && !empty($maxProduct)) {
                    if ($maxProduct > 0 && $i < $maxProduct) {
                        array_push($arrayProducts, $product);
                    }
                } else {
                    array_push($arrayProducts, $product);
                }
                unset($product);
            }
            ++$i;
        }
        $arrayOrder['products'] = $arrayProducts;
        unset($arrayProducts);
    }

    return $arrayOrder;
}

/**
 * set flag on orders in av_orders according to parameters sent in $postData
 *
 * @param $postData : sent parameters
 *
 * @return $response : array to debug info
 */
function setFlag(&$postData)
{
    $unsMsg = iniInfo($postData);
    $data = initializeDataForSetFlagAndGenerateOrders($unsMsg);

    if (!$data) {
        return [
            'return' => 3,
            'id_shop' => $unsMsg['id_shop'],
            'message' => 'S\'il vous plaît, ne pas oublier de remplir toutes les conditions',
        ];
    }

    if (!isset($data['orders_list']) || empty($data['orders_list'])) {
        return [
            'return' => 1,
            'id_shop' => $data['id_shop'],
            'debug' => $data['query'],
            'message' => 'Pas de commandes dans votre back-office',
        ];
    }

    $whereLimit = isset($unsMsg['limit']) ? $unsMsg['limit'] : '';
    $ordersList = $data['orders_list'];
    $countOrdersInTableFlagged = 0;

    foreach ($ordersList as $order) {
        $queryOrder = 'SELECT id_order, flag_get FROM ' . _DB_PREFIX_ . 'av_orders WHERE id_order = ' .
            $order['id_order'] . ' AND (flag_get != ' . (int) $unsMsg['setFlag'] . ' OR flag_get IS NULL)';
        $orderToUpdate = Db::getInstance()->getRow($queryOrder, false);

        if (!empty($orderToUpdate) && ($orderToUpdate['flag_get'] != $unsMsg['setFlag'])) {
            Db::getInstance()->Execute('UPDATE ' . _DB_PREFIX_ . 'av_orders SET flag_get = "' .
                                       (int) $unsMsg['setFlag'] . '", horodate_get = "' . time() . '"
                WHERE id_order = ' . (int) $order['id_order']);
            ++$countOrdersInTableFlagged;
            $data['log'] .= ' <br> ' . $countOrdersInTableFlagged . '. ' . $order['date_add'] . ' #' .
                $order['id_order'] . ' is updated';
            if ($countOrdersInTableFlagged == $whereLimit) {
                break;
            }
        }
    }

    $returnMessage = 'Aucune commande à flaguer.';
    if ($countOrdersInTableFlagged > 0) {
        $returnMessage = $countOrdersInTableFlagged . ' commande(s) flaguée(s) à ' . $unsMsg['setFlag'];
        $returnMessage .= $data['log'];
    }

    return [
        'return' => 1,
        'id_shop' => $data['id_shop'],
        'debug' => $data['query'],
        'message' => $returnMessage,
    ];
}

/**
 * Generate array of the shop orders to be analyzed in setFlag and generateLostOrders.
 *
 * @param $message : parameters sent by the platform
 *
 * @return $response : array with list of orders and others infos
 */
function initializeDataForSetFlagAndGenerateOrders($message)
{
    $setFlagAuthorizedValues = ['0', '1'];
    if (empty($message['datePeriod']) || !in_array($message['setFlag'], $setFlagAuthorizedValues)) {
        return false;
    }

    $config = getSetFlagConfig($message);

    $query = 'SELECT o.id_order, lg.iso_code, o.date_add, o.id_shop FROM ' . _DB_PREFIX_ . 'orders o LEFT JOIN ' .
        _DB_PREFIX_ . 'lang lg ON o.id_lang = lg.id_lang' .
        $config['whereShopId'] . $config['whereLangId'] . $config['whereTimestamp'];

    $ordersList = Db::getInstance()->ExecuteS($query);

    return [
        'multisite' => $config['multisite'],
        'id_shop' => $config['shopId'],
        'id_lang' => $config['langId'],
        'log' => '',
        'query' => $query,
        'orders_list' => $ordersList,
    ];
}

function getSetFlagConfig($message)
{
    $multisite = Configuration::get('AV_MULTISITE');
    $shopId = (!empty($multisite)) ? $message['id_shop'] : '';
    $langId = '';
    if (!is_numeric($shopId)) {
        $decomposeShopId = explode('_', $shopId);
        if (3 == count($decomposeShopId)) {
            $shopId = $decomposeShopId[0];
            $langId = $decomposeShopId[1];
        } elseif (2 == count($decomposeShopId)) {
            $shopId = $decomposeShopId[0];
            $langId = ('all' != $decomposeShopId[1]) ? $decomposeShopId[1] : '';
        }
    }
    $whereShopId = (!empty($message['id_shop'])) ? ' WHERE o.id_shop = ' . (int) $message['id_shop'] : ' WHERE TRUE';
    $whereLangId = (!empty($message['id_lang'])) ? ' AND lg.iso_code = "' . $message['id_lang'] . '"' : '';

    $startDate = (!empty($message['startDate'])) ? $message['startDate'] : '1970-01-01';
    $endDate = (!empty($message['endDate'])) ? $message['endDate'] : date('Y-m-d');
    $sqlDuration = ' AND (select DATE_FORMAT(o.date_add, "%Y-%m-%d")) BETWEEN "' . pSQL($startDate) . '" AND "'
        . pSQL($endDate) . '"';
    $whereTimestamp = ('allOrders' == $message['datePeriod']) ? '' : $sqlDuration;

    return [
        'multisite' => $multisite,
        'shopId' => $shopId,
        'langId' => $langId,
        'whereShopId' => $whereShopId,
        'whereLangId' => $whereLangId,
        'whereTimestamp' => $whereTimestamp,
    ];
}

/**
 * truncate content on tables av_products_reviews et av_products_average.
 *
 * @param $post_data : sent parameters
 *
 * @return $response : array to debug info
 */
function truncateTables()
{
    $response = [];
    $query = [];
    $query[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'av_products_reviews;';
    $query[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'av_products_average;';
    $query[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'av_products_reviews (
                  `id_product_av` varchar(36) NOT NULL,
                  `ref_product` varchar(20) NOT NULL,
                  `rate` varchar(5) NOT NULL,
                  `review` text NOT NULL,
                  `customer_name` varchar(30) NOT NULL,
                  `horodate` text NOT NULL,
                  `horodate_order` text NOT NULL,
                  `discussion` text NULL,
                  `helpful` int(7) DEFAULT 0,
                  `helpless` int(7) DEFAULT 0,
                  `media_full` text NULL,
                  `iso_lang` varchar(5) DEFAULT "0",
                  `id_shop` int(2) DEFAULT 0,
                  PRIMARY KEY (`id_product_av`,`iso_lang`,`id_shop`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
    $query[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'av_products_average (
                  `id_product_av` varchar(36) NOT NULL,
                  `ref_product` varchar(20) NOT NULL,
                  `rate` varchar(5) NOT NULL,
                  `nb_reviews` int(10) NOT NULL,
                  `horodate_update` text NOT NULL,
                  `iso_lang` varchar(5) DEFAULT "0",
                  `id_shop` int(2) DEFAULT 0,
                  PRIMARY KEY (`ref_product`,`iso_lang`,`id_shop`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
    $response['return'] = 1;
    $response['debug'] = 'Tables truncated';
    $response['message'] = 'Tables truncated';
    $response['query'] = $query;

    foreach ($query as $sql) {
        if (!Db::getInstance()->Execute($sql)) {
            $response['return'] = 2;
            $response['debug'] = 'Tables not truncated';
            $response['message'] = 'Tables not truncated';
        }
    }

    return $response;
}

function getLatestOrders(&$postData)
{
    $reponse = [];
    $postMessage = iniInfo($postData);
    $retroactiveCollectConfig = [
        'startDate' => $postMessage['startDate'],
        'endDate' => $postMessage['endDate'],
        'orderStatusChoosen' => $postMessage['orderStatus'],
    ];
    $idShop = getCurrentShop($postMessage);
    $groupName = getGroupname($idShop, $postMessage);

    $ordersList = getOrdersByRangeDate($idShop, $groupName, $retroactiveCollectConfig);

    $ordersNb = count($ordersList);
    $reponse['debug'] = $ordersNb . ' commande(s) récupérée(s).';
    $finalOrdersList = [];
    $nbFinalOrdersList = 0;

    if ($ordersNb > 0) {
        $extensions = Configuration::get(
            'AV_FORBIDDEN_EMAIL' . $groupName,
            null,
            null,
            $idShop
        );
        $extensions = str_replace(['["', '"', ']'], '', $extensions);
        $forbiddenEmailsExtensions = explode(',', $extensions);
        $orderStatusIndice = getOrderStatusIndice();

        foreach ($ordersList as $order) {
            if ($nbFinalOrdersList <= 300) {
                $orderCustomer = new Customer($order['id_customer']);
                $customerEmailExtension = explode('@', $orderCustomer->email);

                $orderDetails = new Order($order['id_order']);

                if (in_array($customerEmailExtension[1], $forbiddenEmailsExtensions)) {
                    $reponse['message']['Emails_Interdits'][] = 'Commande n°' . $order['id_order'] .
                        ' Email:' . $orderCustomer->email;
                    continue;
                }

                $orderReference = getOrderReference($orderDetails);
                $carrier = new Carrier($orderDetails->id_carrier);
                $numState = $order['state_order'];
                $arrayOrder = [
                    'id_order' => $order['id_order'],
                    'reference' => $orderReference,
                    'payment' => $orderDetails->payment,
                    'carrier' => $carrier->name,
                    'id_lang' => $order['id_lang'],
                    'iso_lang' => pSQL(Language::getIsoById($order['id_lang'])),
                    'id_shop' => $order['id_shop'],
                    'amount_order' => $order['total_paid'],
                    'id_customer' => $order['id_customer'],
                    'state_order' => $orderStatusIndice[$numState] . '(' . $numState . ')', //  Status added here
                    'state_order_id' => $numState, //  Status number
                    'date_order' => strtotime($order['date_order']), // date timestamp in orders table
                    'date_last_status_change' => $order['date_last_status'],
                    'date_order_formatted' => $order['date_order'], // date in orders table formatted
                    'firstname_customer' => $orderCustomer->firstname,
                    'lastname_customer' => $orderCustomer->lastname,
                    'email_customer' => $orderCustomer->email,
                ];

                $arrayOrder['products'] = addProductsToOrder(
                    $orderDetails,
                    $order['id_shop'],
                    $groupName,
                    $order['id_lang']
                );

                $finalOrdersList[] = $arrayOrder;
                ++$nbFinalOrdersList;

                flagOrderInRetroactiveCollect($order['id_order']);
            }
        }
    }

    $reponse['return'] = 1;
    $reponse['query'] = $postMessage['query'];
    $reponse['message']['nb_orders'] = count($finalOrdersList) . ' collected orders';
    $reponse['message']['list_orders'] = $finalOrdersList;
    $reponse['sign'] = sha1(
        $postMessage['query'] .
        Configuration::get('AV_IDWEBSITE' . $groupName, null, null, $idShop) .
        Configuration::get('AV_CLESECRETE' . $groupName, null, null, $idShop)
    );

    return $reponse;
}

function getProductReviewsStats(&$postData)
{
    $response = [
        'average in av_products_average table' => '',
        'nb reviews in av_products_average table' => '',
        'nb reviews in av_products_reviews table' => '',
    ];

    $unsMsg = iniInfo($postData);
    $idShop = getCurrentShop($unsMsg);
    $groupName = getGroupname($idShop, $unsMsg);
    $refProduct = (int) $unsMsg['refProduct'];
    $queryIdShop = (!empty($idShop)) ? ' AND id_shop = ' . (int) $idShop : '';
    $queryIsoLang = getIDLang($idShop, $groupName);
    $sql = 'SELECT rate, nb_reviews  FROM ' . _DB_PREFIX_ . 'av_products_average WHERE ref_product = ' . $refProduct . $queryIdShop . $queryIsoLang;
    if ($row = Db::getInstance()->getRow($sql)) {
        $response['average in av_products_average table'] = $row['rate'];
        $response['nb reviews in av_products_average table'] = $row['nb_reviews'];
    }
    $sql = 'SELECT Count(*) as count  FROM ' . _DB_PREFIX_ . 'av_products_reviews WHERE ref_product = ' . $refProduct . $queryIdShop . $queryIsoLang;
    if ($row = Db::getInstance()->getRow($sql)) {
        $response['nb reviews in av_products_reviews table'] = $row['count'];
    }
    $response['average in av_products_average table'] = !empty($response['average in av_products_average table']) ? $response['average in av_products_average table'] : 'Ref Produit not found';
    $response['nb reviews in av_products_average table'] = !empty($response['nb reviews in av_products_average table']) ? $response['nb reviews in av_products_average table'] : 'Ref Produit not found';
    $response['nb reviews in av_products_reviews table'] = !empty($response['nb reviews in av_products_reviews table']) ? $response['nb reviews in av_products_reviews table'] : 'Ref Produit not found';

    return $response;
}

function getIDLang($idShop, $groupName)
{
    $idShopFilterSql = (!empty($idShop)) ? ' AND id_shop = ' . (int) $idShop : '';
    $queryIsoLang = '';

    if ('checked' == Configuration::get('AV_MULTILINGUE', null, null, $idShop)) {
        $sql = 'SELECT value FROM ' . _DB_PREFIX_ . 'configuration WHERE name = "AV_GROUP_CONF' .
            pSQL($groupName) . '"' . $idShopFilterSql;
        if ($row = Db::getInstance()->getRow($sql)) {
            $listIsoLangMultilingue[] = str_replace(['["', '"', ']'], '', $row['value']);
        }
        $codeIsos = '';
        foreach ($listIsoLangMultilingue as $codeIso) {
            $codeIsos .= "'" . $codeIso . "',";
        }
        $codeIsos = Tools::substr($codeIsos, 0, -1);
        $queryIsoLang .= ' AND iso_lang in (' . $codeIsos . ')';
    }

    return $queryIsoLang;
}

function flagOrderInRetroactiveCollect($orderId)
{
    // Check if order is in db and flag it to 1
    $queryOrderInAvOrderTable = 'SELECT id_order FROM ' . _DB_PREFIX_ . 'av_orders
                    WHERE id_order = ' . (int) $orderId;
    if (Db::getInstance()->getRow($queryOrderInAvOrderTable, false)) {
        Db::getInstance()->Execute(
            'UPDATE ' . _DB_PREFIX_ . 'av_orders
                        SET horodate_get = "' . time() . '", flag_get = 1
                        WHERE id_order = ' . (int) $orderId
        );
    }
}

function getOrderStatusIndice()
{
    $orderStatusList = OrderState::getOrderStates((int) Configuration::get('PS_LANG_DEFAULT'));
    $orderStatusIndice = [];
    foreach ($orderStatusList as $value) {
        $orderStatusIndice[$value['id_order_state']] = $value['name'];
    }

    return $orderStatusIndice;
}

function getOrderReference($order)
{
    return (isset($order->reference) && !empty($order->reference)) ? $order->reference : '';
}

function getOrdersByRangeDate($idShop, $groupName, $parameters)
{
    $idShopFilterSql = (!empty($idShop)) ? ' AND id_shop = ' . (int) $idShop : '';
    $queryIdShop = (!empty($idShop)) ? ' AND o.id_shop = ' . (int) $idShop : '';
    $orderStatusChoosen = $parameters['orderStatusChoosen'];
    $startDate = $parameters['startDate'];
    $endDate = $parameters['endDate'];
    $orderStatusChoosen = str_replace(';', ',', $orderStatusChoosen);
    $queryStatus = ' AND o.current_state IN (' . pSQL($orderStatusChoosen) .
        ') AND oh.id_order_state = o.current_state';

    $queryIsoLang = '';
    if ('checked' == Configuration::get('AV_MULTILINGUE', null, null, $idShop)) {
        $sql = 'SELECT value FROM ' . _DB_PREFIX_ . 'configuration WHERE name = "AV_GROUP_CONF' .
            pSQL($groupName) . '"' . $idShopFilterSql;
        if ($row = Db::getInstance()->getRow($sql)) {
            $listIsoLangMultilingue[] = str_replace(['["', '"', ']'], '', $row['value']);
        }
        $idsLang = '';
        foreach ($listIsoLangMultilingue as $codeIso) {
            $orderLang = new Language();
            $idLang = $orderLang->getIdByIso(Tools::strtolower($codeIso));
            $idsLang .= "'" . (int) $idLang . "',";
        }
        $idsLang = Tools::substr($idsLang, 0, -1);
        $queryIsoLang .= ' AND o.id_lang in (' . $idsLang . ')';
    }

    $query = 'SELECT o.id_order, o.reference, o.module, o.date_add as date_order, oh.date_add as date_last_status,
        o.id_customer, o.total_paid, o.id_lang, o.id_shop, oh.id_order_state, o.current_state as state_order
        FROM ' . _DB_PREFIX_ . 'orders o
        LEFT JOIN ' . _DB_PREFIX_ . "order_history oh ON oh.id_order = o.id_order
        WHERE o.date_add BETWEEN '$startDate' AND '$endDate'
        " . $queryStatus . $queryIdShop . $queryIsoLang;

    return Db::getInstance()->ExecuteS($query);
}

function addProductsToOrder($orderDetails, $idShop, $groupName, $idLang)
{
    $arrayProducts = [];
    $allowedProducts = Configuration::get('AV_GETPRODREVIEWS' . $groupName, null, null, $idShop);

    $nbProducts = 0;
    if (!empty($allowedProducts) && 'yes' == $allowedProducts) {
        $productsOrder = $orderDetails->getProducts();

        foreach ($productsOrder as $nb => $productElement) {
            $nrModel = new netreviewsModel();
            $productData = $nrModel->getProductData(
                $productElement,
                $idLang,
                $idShop
            );

            $product = [
                'id_product' => $productElement['product_id'],
                'name_product' => $productElement['product_name'],
                'SKU' => $productData['sku'],
                'GTIN_EAN' => $productData['ean13'],
                'GTIN_UPC' => $productData['upc'],
                'MPN' => $productData['mpn'],
                'brand_name' => $productData['brandName'],
                'category' => $productData['category'],
                'url_image' => netreviewsModel::getUrlProduct(
                    $productElement['product_id'],
                    $idLang,
                    $productData['titleImageId']
                ),
                'url' => netreviewsModel::getUrlProduct(
                    $productElement['product_id'],
                    $idLang,
                    null,
                    $idShop
                ),
                'product_price_unity' => $productElement['product_price'],
            ];

            if (addProduct($idShop, $groupName, $nbProducts)) {
                array_push($arrayProducts, $product);
            } else {
                break;
            }

            ++$nbProducts;
            unset($product);
        }
    }

    return $arrayProducts;
}

function addProduct($idShop, $groupName, $nbProductAlreadyAdded)
{
    $addProduct = true;

    $maxProducts = (int) Configuration::get(
        'AV_NBOPRODUCTS' . $groupName,
        null,
        null,
        $idShop
    );

    if (isset($maxProducts) && !empty($maxProducts) && $nbProductAlreadyAdded >= $maxProducts) {
        $addProduct = false;
    }

    return $addProduct;
}
