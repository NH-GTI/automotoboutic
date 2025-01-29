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

class InternalConfigLists
{
    /**
     * @var array<string,string>
     */
    private static $dbOutputTranslater;

    /**
     * @var array<string,string>
     */
    private static $dbInputTranslater;

    /**
     * @var array<string,string>
     */
    private static $dbInputTranslaterFunction;

    /**
     * @var array<string,string>
     */
    private static $funcQueryTranslater;

    /**
     * @var array<string,bool>
     */
    private static $isConfigHtmlValueTab;

    /**
     * @var array<string>
     */
    private static $isNotDbConfigFromGroup;

    /**
     * List of db name that are not from a group
     *
     * @return array<string>
     */
    public static function getNotDbConfigFromGroup()
    {
        if (empty(self::$isNotDbConfigFromGroup)) {
            self::$isNotDbConfigFromGroup = [
                'AV_LIMIT_LOST_ORDERS',
                'AV_MULTISITE',
                'AV_GOUPINFO',
                'AV_PRODUCTUNIGINFO',
                'AV_EXTRA_OPTION',
                'AV_TABSHOW',
                'AV_STARSHOMESHOW',
                'AV_DISPLAYSTARPLIST',
            ];
        }

        return self::$isNotDbConfigFromGroup;
    }

    /**
     * Check if the name should contains a group id in the end in db
     *
     * @param string $name
     *
     * @return bool
     */
    public static function isNotDbConfigFromGroup($name)
    {
        return in_array($name, self::getNotDbConfigFromGroup());
    }

    /**
     * List of translation the database field name to the output api Connectors field name
     *
     * @return array<string,string>
     */
    public static function getDbOutputTranslater()
    {
        if (empty(self::$dbOutputTranslater)) {
            self::$dbOutputTranslater = [
                'processInit' => 'AV_PROCESSINIT',
                'moduleInstallationDate' => 'AV_LIMIT_LOST_ORDERS',
                'configurationByGroup' => 'AV_GROUP_CONF',
                'isMultiStore' => 'AV_MULTISITE',
                'configurationGroupInfo' => 'AV_GOUPINFO',
                'uniqueGoogleShoppingInfo' => 'AV_PRODUCTUNIGINFO',
                'extraOptionLocation' => 'AV_EXTRA_OPTION',
                'displayProductReviewsTab' => 'AV_TABSHOW',
                'displayProductReviewsHomePage' => 'AV_STARSHOMESHOW',
                'displayProductReviewsCategoryPage' => 'AV_DISPLAYSTARPLIST',
            ];
        }

        return self::$dbOutputTranslater;
    }

    /**
     * Translate the inputs field (from Nrapi or api-connectors)
     * to the database field name
     *
     * @return array<string,string>
     */
    public static function getDbInputTranslater()
    {
        if (empty(self::$dbInputTranslater)) {
            self::$dbInputTranslater = [
                'init_reviews_process' => 'AV_PROCESSINIT',
                'processInit' => 'AV_PROCESSINIT',
                'moduleInstallationDate' => 'AV_LIMIT_LOST_ORDERS',
                'configurationByGroup' => 'AV_GROUP_CONF',
                'isMultiStore' => 'AV_MULTISITE',
                'configurationGroupInfo' => 'AV_GOUPINFO',
                'uniqueGoogleShoppingInfo' => 'AV_PRODUCTUNIGINFO',
                'extraOptionLocation' => 'AV_EXTRA_OPTION',
                'displayProductReviewsTab' => 'AV_TABSHOW',
                'displayProductReviewsHomePage' => 'AV_STARSHOMESHOW',
                'displayProductReviewsCategoryPage' => 'AV_DISPLAYSTARPLIST',
                'get_product_reviews' => 'AV_GETPRODREVIEWS',
                'hasProductReviewCollection' => 'AV_GETPRODREVIEWS',
                'display_product_reviews' => 'AV_DISPLAYPRODREVIEWS',
                'displayReviews' => 'AV_DISPLAYPRODREVIEWS',
                'display_fixe_widget' => 'AV_SCRIPTFIXE_ALLOWED',
                'position_fixe_widget' => 'AV_SCRIPTFIXE_POSITION',
                'display_float_widget' => 'AV_SCRIPTFLOAT_ALLOWED',
                'url_certificat' => 'AV_URLCERTIFICAT',
                'code_lang' => 'AV_CODE_LANG',
                'collect_consent' => 'AV_COLLECT_CONSENT',
                'delay' => 'AV_DELAY',
                'brandDelay' => 'AV_DELAY',
                'delay_product' => 'AV_DELAY_PRODUIT',
                'productDelay' => 'AV_DELAY_PRODUIT',
                'id_order_status_choosen' => 'AV_ORDERSTATESCHOOSEN',
                'choosenStatus' => 'AV_ORDERSTATESCHOOSEN',
                'forbidden_mail_extension' => 'AV_FORBIDDEN_EMAIL',
                'blacklistedExtensions' => 'AV_FORBIDDEN_EMAIL',
                'script_fixe_widget' => 'AV_SCRIPTFIXE',
                'script_float_widget' => 'AV_SCRIPTFLOAT',
                'scriptWidgetBrand' => 'AV_SCRIPTFLOAT',
                'reloaded' => 'AV_RELOADED',
                'activateCollect' => 'AV_ACTIVATE_COLLECT',
                'clientid' => 'AV_CLIENTID',
                'secretapi' => 'AV_SECRETAPI',
                'countryCode' => 'AV_PLATEFORME',
                'Date_Installation_Module' => 'AV_LIMIT_LOST_ORDERS',
                'idWebsite' => 'AV_IDWEBSITE',
                'AV_RELOADED' => 'AV_RELOADED',
                'AV_ACTIVATE_COLLECT' => 'AV_ACTIVATE_COLLECT',
                'AV_CLIENTID' => 'AV_CLIENTID',
                'AV_SECRETAPI' => 'AV_SECRETAPI',
                'Delay' => 'AV_DELAY',
                'Delay_by_status' => 'AV_DELAY_BYSTATUS',
                'Delay_product' => 'AV_DELAY_PRODUIT',
                'Initialisation_du_Processus' => 'AV_PROCESSINIT',
                'Statut_choisi' => 'AV_ORDERSTATESCHOOSEN',
                'Recuperation_Avis_Produits' => 'AV_GETPRODREVIEWS',
                'Affiche_Avis_Produits' => 'AV_DISPLAYPRODREVIEWS',
                'Affichage_Widget_Flottant' => 'AV_SCRIPTFLOAT_ALLOWED',
                'Script_Widget_Flottant' => 'AV_SCRIPTFLOAT',
                'Affichage_Widget_Fixe' => 'AV_SCRIPTFIXE_ALLOWED',
                'Position_Widget_Fixe' => 'AV_SCRIPTFIXE_POSITION',
                'Script_Widget_Fixe' => 'AV_SCRIPTFIXE',
                'Emails_Interdits' => 'AV_FORBIDDEN_EMAIL',
                'Collected_consent' => 'AV_COLLECT_CONSENT',
                'collected_consent' => 'AV_COLLECT_CONSENT',
                'AV_SCRIPTTAGJS' => 'AV_SCRIPTTAGJS',
                'AV_AVERAGETAGJS' => 'AV_AVERAGETAGJS',
                'AV_REVIEWSTAGJS' => 'AV_REVIEWSTAGJS',
                'scriptWidgetProduct' => 'TMP_scriptWidgetProduct',
                'pushOrdersEndpoint' => 'AV_PUSH_ORDERS_ENDPOINT',
                'firstRetroactive' => 'AV_FIRST_RETROACTIVE',
                'retroactiveOrdersEndpoint' => 'AV_RETROACTIVE_ORDERS_ENDPOINT',
            ];
        }

        return self::$dbInputTranslater;
    }

    /**
     * The parser that will be used to translate the value for the database
     *
     * @return array<string,string>
     */
    public static function getDbInputTranslaterFunction()
    {
        if (empty(self::$funcQueryTranslater)) {
            self::$funcQueryTranslater = [
                'AV_FORBIDDEN_EMAIL' => 'arrayToJsonArray',
                'AV_ORDERSTATESCHOOSEN' => 'arrayToSeparedSemicolonString',
                'AV_SCRIPTFLOAT' => 'scriptWidgetBrandHandler',
                'TMP_scriptWidgetProduct' => 'scriptWidgetProductHandler',
                'AV_DISPLAYPRODREVIEWS' => 'displayReviewsHandler',
            ];
        }

        return self::$funcQueryTranslater;
    }

    /**
     * List The db fields that are html values
     *
     * @return array<string,bool>
     */
    public static function getIsConfigHtmlValueTab()
    {
        if (empty(self::$isConfigHtmlValueTab)) {
            self::$isConfigHtmlValueTab = [
                'AV_SCRIPTFIXE' => true,
                'AV_SCRIPTFLOAT' => true,
            ];
        }

        return self::$isConfigHtmlValueTab;
    }

    /**
     * Return the dictionary of the InternalConfig Manager function
     * to call to send the value API Connector
     *
     * @return array<string,string>
     */
    public static function getFuncQueryTranslater()
    {
        if (empty(self::$dbInputTranslaterFunction)) {
            self::$dbInputTranslaterFunction = [
                'eCommerceVersion' => 'getPrestaVersion',
                'moduleVersion' => 'getModuleVersion',
                'multiStoreNumber' => 'getTotalShops',
                'multilingualMode' => 'getTreatedMultilangshoplist',
                'multilingualListIsoLang' => 'getAllConfiguredShops',
                'websiteList' => 'getAllShopsData',
                'configuredShopId' => 'getShopId',
                'orderStatusList' => 'getOrderStatusList',
                'moduleFolderPermission' => 'getFolderAndFileRightsInfo',
                'collectedConsent' => 'getCollectedConsent',
                'phpVersion' => 'getPhpVersion',
            ];
        }

        return self::$dbInputTranslaterFunction;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public static function isConfigHtmlValue($key)
    {
        return isset(self::getIsConfigHtmlValueTab()[$key])
            && self::getIsConfigHtmlValueTab()[$key];
    }
}
