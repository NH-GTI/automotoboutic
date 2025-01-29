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

if (!class_exists('netreviewsModel')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/netreviewsModel.php';
}
if (!class_exists('Base32')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/class/Base32.php';
}
if (!class_exists('ConfSkeepers')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/class/ConfSkeepers.php';
}
if (!class_exists('ConfSkeepersProd')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/class/ConfSkeepersProd.php';
}

if (!class_exists('ConfSkeepersLocal')
    && file_exists(_PS_MODULE_DIR_ . 'netreviews/class/ConfSkeepersLocal.php')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/class/ConfSkeepersLocal.php';
}
if (!class_exists('HookHandler')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/class/HookHandler.php';
}
if (!class_exists('InternalConfigLists')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/class/InternalConfigLists.php';
}
if (!class_exists('LogsHandler')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/class/LogsHandler.php';
}
if (!class_exists('MigrationHandler')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/class/MigrationHandler.php';
}
if (!class_exists('InternalConfigManager')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/class/InternalConfigManager.php';
}
if (!class_exists('GlobalConfigManager')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/class/GlobalConfigManager.php';
}
if (!class_exists('CurlCapsule')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/class/CurlCapsule.php';
}
if (!class_exists('AccessToken')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/class/AccessToken.php';
}
if (!class_exists('ApiConnectorsCaller')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/class/ApiConnectorsCaller.php';
}
if (!class_exists('ApiGenesisCaller')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/class/ApiGenesisCaller.php';
}
if (!class_exists('OrderPurchaseFormatter')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/class/OrderPurchaseFormatter.php';
}
if (!class_exists('OrdersPrestashopGetter')) {
    require_once _PS_MODULE_DIR_ . 'netreviews/class/OrdersPrestashopGetter.php';
}

class Netreviews extends Module
{
    public $secure_key;
    public $currentShopId;
    public $isoLang;
    public $idLang;
    public $groupName;
    public $html = '';
    public $selectName = 'SELECT name FROM ';
    public $allMultishopsReviews = false;
    public $allLanguagesReviews = false;
    public $name;
    public $tab;
    public $version;
    public $author;
    public $need_instance;
    public $bootstrap;
    public $displayName;
    public $description;
    public $module_key;
    public $confirmUninstall;
    public $ps_versions_compliancy;
    public $dropTableIfExist;
    public $createTableIfNotExist;
    public $smartyString;
    public $dateFormat;
    protected $nrModel;
    protected $fgUrl;

    public function __construct()
    {
        $this->fgDomaine = ConfSkeepers::getInstance()->getEnv('SKEEPERS_SSO');
        $this->fgUrl = ConfSkeepers::getInstance()->getEnv('SKEEPERS_SSO_TOKEN');
        $this->name = 'netreviews';
        $this->tab = 'advertising_marketing';
        $this->version = '9.0.2';
        $this->author = 'NetReviews';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => '8.99.99',
        ];

        parent::__construct();

        $this->displayName = $this->l('Verified Reviews');
        $this->description = $this->l(
            'Collect, manage and publish reviews on your products and establishments
        but also on your brand in a few clicks thanks to Verified Reviews.
            Bet on customer reviews to increase your e-reputation and increase your income.'
        );
        $this->secure_key = Tools::hash($this->name);
        $this->module_key = 'd63d28acbac0a249ec17b6394ac5a841';

        $isInstalled = self::isEnabled($this->name);
        if ($isInstalled) {
            $this->idLang = (int) Configuration::get('PS_LANG_DEFAULT');
            $this->isoLang = pSQL(Language::getIsoById($this->idLang));
        }
        $this->confirmUninstall = sprintf(
            $this->l('Are you sure you want to uninstall %s'),
            $this->displayName . ' module?'
        );

        $this->currentShopId = null;
        if (Shop::isFeatureActive()) {
            $this->currentShopId = $this->context->shop->getContextShopID();
        }

        if ('checked' == Configuration::get('AV_MULTILINGUE', null, null, $this->currentShopId)) {
            $this->idLang = $this->context->language->id;
            $this->isoLang = pSQL(Language::getIsoById($this->idLang));
            $this->groupName = $this->getIdConfigurationGroup($this->isoLang);
        }

        $this->dropTableIfExist = 'DROP TABLE IF EXISTS ';
        $this->createTableIfNotExist = 'CREATE TABLE IF NOT EXISTS ';
        $this->smartyString = 'string: ';
        $this->dateFormat = 'd/m/Y';
        $this->fgUrl = 'https://auth.skeepers.io/am/oauth2/alpha/access_token';

        $nrModel = new netreviewsModel();
        $this->nrModel = $nrModel;
    }

    public function install($keep = true)
    {
        if ($keep) {
            if (!($query = $this->createTables()) && isset(Context::getContext()->controller)) {
                $controller = Context::getContext()->controller;
                $controller->errors[] = $this->l('SQL ERROR : Query can\'t be executed. Maybe, check SQL user permissions.');
            }

            $this->setDefaultConfigValues();
        }

        if (false === parent::install() || !$this->registerHooks()) {
            return false;
        }

        return true;
    }

    public function uninstall($keep = true)
    {
        $sql = $this->selectName . _DB_PREFIX_ . "configuration where name like 'AV_%'";
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                Configuration::deleteByName($row['name']);
            }
        }

        $this->deleteConfigValues();

        if (!parent::uninstall() || ($keep && !$this->deleteTables()) || !$this->registerHooks()) {
            return false;
        }

        return true;
    }

    private function registerHooks()
    {
        $hooks = [
            'displayHeader',
            'displayFooter',
            'displayProductPriceBlock',
            'CategorystarsNetreviews',
            'displayProductAdditionalInfo',
            'displayProductExtraContent',
            'displayPaymentTop',
            'ExtraNetreviews',
            'TabcontentNetreviews',
            'actionOrderStatusPostUpdate',
            'actionValidateOrder',
            HookHandler::DISPLAY_HOME_HOOK,
        ];

        foreach ($hooks as $hook) {
            if (!$this->registerHook($hook)) {
                return false;
            }
        }

        return true;
    }

    private function deleteConfigValues()
    {
        $sql = $this->selectName . _DB_PREFIX_ . "configuration where name like 'AV_%'";
        $results = Db::getInstance()->ExecuteS($sql);
        foreach ($results as $row) {
            Configuration::deleteByName($row['name']);
        }
    }

    public function createTables()
    {
        $sql = [];
        $sql[] = $this->dropTableIfExist . _DB_PREFIX_ . 'av_products_reviews;';
        $sql[] = $this->dropTableIfExist . _DB_PREFIX_ . 'av_products_average;';
        $sql[] = $this->dropTableIfExist . _DB_PREFIX_ . 'av_orders;';
        $sql[] = $this->createTableIfNotExist . _DB_PREFIX_ . 'av_products_reviews (
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
        $sql[] = $this->createTableIfNotExist . _DB_PREFIX_ . 'av_products_average (
                      `id_product_av` varchar(36) NOT NULL,
                      `ref_product` varchar(20) NOT NULL,
                      `rate` varchar(5) NOT NULL,
                      `nb_reviews` int(10) NOT NULL,
                      `horodate_update` text NOT NULL,
                      `iso_lang` varchar(5) DEFAULT "0",
                      `id_shop` int(2) DEFAULT 0,
                      PRIMARY KEY (`ref_product`,`iso_lang`,`id_shop`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
        $sql[] = $this->createTableIfNotExist . _DB_PREFIX_ . 'av_orders (
                      `id_order` int(11) NOT NULL,
                      `id_shop` int(2) DEFAULT 0,
                      `flag_get` int(2) DEFAULT NULL,
                      `horodate_get` varchar(25) DEFAULT NULL,
                      `id_order_state` int(5) DEFAULT NULL,
                      `iso_lang` varchar(5) DEFAULT "0",
                      `horodate_now` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                      PRIMARY KEY (`id_order`,`iso_lang`,`id_shop`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        foreach ($sql as $query) {
            if (false === Db::getInstance()->execute($query)) {
                return $query;
            }
        }
    }

    public function reset()
    {
        if (!$this->uninstall(false)) {
            return false;
        }
        if (!$this->install(false)) {
            return false;
        }

        return true;
    }

    public function deleteTables()
    {
        return Db::getInstance()->execute(
            '
            DROP TABLE IF EXISTS
        `' . _DB_PREFIX_ . 'av_orders`,
        `' . _DB_PREFIX_ . 'av_products_reviews`,
        `' . _DB_PREFIX_ . 'av_products_average`'
        );
    }

    /**
     * Load the configuration form.
     */
    public function getContent()
    {
        $this->addFiles('avisverifies-admin', 'css');

        if (!empty($_POST)) {
            $this->postProcess();
        }

        $this->checkThatSpecificShopHasBeenChosen();

        $languages = Language::getLanguages(true);

        $configuredCredentials = $this->checkMultistoreMultilangConfig($languages);

        $variablesForBOTpl = $this->getVariablesForBackOfficeTpl($configuredCredentials, $languages);

        $this->smartyAssign($variablesForBOTpl);

        $tpl = 'avisverifies-backoffice';
        $this->html .= $this->displayTemplate($tpl);

        return $this->html;
    }

    protected function setDefaultConfigValues()
    {
        $shopId = $this->currentShopId;
        $this->updateDefaultValue($shopId);

        if (Shop::isFeatureActive()) {
            $shopIds = Shop::getCompleteListOfShopsID();

            if (is_array($shopIds) && !empty($shopIds)) {
                foreach ($shopIds as $shopId) {
                    if ($shopId !== $this->currentShopId) {
                        $this->updateDefaultValue($shopId);
                    }
                }
            }
        }
    }

    private function updateDefaultValue($shopId)
    {
        Configuration::updateValue('AV_IDWEBSITE', '', false, null, $shopId);
        Configuration::updateValue('AV_CLESECRETE', '', false, null, $shopId);
        Configuration::updateValue('AV_LIGHTWIDGET', '1', false, null, $shopId);
        Configuration::updateValue('AV_MULTILINGUE', '0', false, null, $shopId);
        Configuration::updateValue('AV_MULTISITE', '', false, null, $shopId);
        Configuration::updateValue('AV_PROCESSINIT', 'onorderstatuschange', false, null, $shopId);
        Configuration::updateValue('AV_ORDERSTATESCHOOSEN', '', false, null, $shopId);
        Configuration::updateValue('AV_RELOADED', '', false, null, $shopId);
        Configuration::updateValue('AV_ACTIVATE_COLLECT', false, false, null, $shopId);
        Configuration::updateValue('AV_CLIENTID', '', false, null, $shopId);
        Configuration::updateValue('AV_SECRETAPI', '', false, null, $shopId);
        Configuration::updateValue('AV_DELAY', '0', false, null, $shopId);
        Configuration::updateValue('AV_DELAY_PRODUIT', '0', false, null, $shopId);
        Configuration::updateValue('AV_GETPRODREVIEWS', 'yes', false, null, $shopId);
        Configuration::updateValue('AV_DISPLAYPRODREVIEWS', '', false, null, $shopId);
        Configuration::updateValue('AV_FIRST_RETROACTIVE', '0', false, null, $shopId);
        Configuration::updateValue(
            'AV_CSVFILENAME',
            'Export_NetReviews_01-01-1970-default.csv',
            false,
            null,
            $shopId
        );
        Configuration::updateValue(
            'AV_CSVRETROACTIVEFILENAME',
            'Export_NetReviews_Retroactive_01-01-1970-default.csv',
            false,
            null,
            $shopId
        );
        Configuration::updateValue('AV_SCRIPTFLOAT', '', false, null, $shopId);
        Configuration::updateValue('AV_SCRIPTFLOAT_ALLOWED', '', false, null, $shopId);
        Configuration::updateValue('AV_SCRIPTFIXE', '', false, null, $shopId);
        Configuration::updateValue('AV_SCRIPTFIXE_ALLOWED', '', false, null, $shopId);
        Configuration::updateValue('AV_GOUPINFO', '', false, null, $shopId);
        Configuration::updateValue('AV_URLCERTIFICAT', '', false, null, $shopId);
        Configuration::updateValue('AV_CODE_LANG', '', false, null, $shopId);
        Configuration::updateValue('AV_SNIPPETSITETYPE', '1', false, null, $shopId);
        Configuration::updateValue('AV_DISPLAYSNIPPETSITE', '1', false, null, $shopId);
        Configuration::updateValue('AV_RICHSNIPPETSWEBSITE', '1', false, null, $shopId);
        Configuration::updateValue('AV_NBOFREVIEWS', '5', false, null, $shopId);
        Configuration::updateValue('AV_STARCOLOR', 'FFCD00', false, null, $shopId);
        Configuration::updateValue('AV_PRODUCTUNIGINFO', '', false, null, $shopId);
        Configuration::updateValue('AV_NBOPRODUCTS', '', false, null, $shopId);
        Configuration::updateValue('AV_EXTRA_OPTION', '2', false, null, $shopId);
        Configuration::updateValue(
            'AV_DISPLAYSTARPLIST',
            '0',
            false,
            null,
            $shopId
        );
        Configuration::updateValue('AV_TEMPLATE', '1', false, null, $shopId);
        Configuration::updateValue('AV_TABSHOW', '1', false, null, $shopId);
        Configuration::updateValue('AV_FORMAT_IMAGE', '', false, null, $shopId);
        Configuration::updateValue('AV_TABNEWNAME', '', false, null, $shopId);
        Configuration::updateValue('AV_STARSHOMESHOW', '1', false, null, $shopId);
        Configuration::updateValue('AV_NRESPONSIVE', '0', false, null, $shopId);
        Configuration::updateValue('AV_HELPFULHIDE', '', false, null, $shopId);
        Configuration::updateValue('AV_MEDIAHIDE', '', false, null, $shopId);
        Configuration::updateValue(
            'AV_LIMIT_LOST_ORDERS',
            date('Y-m-d'),
            false,
            null,
            $shopId
        );
        Configuration::updateValue('AV_RELOADED', '', false, null, $shopId);
        Configuration::updateValue('AV_RELOADEDSCRIPT', '', false, null, $shopId);
        Configuration::updateValue('AV_TAGAVERAGE', '', false, null, $shopId);
        Configuration::updateValue('AV_TAGREVIEWS', '', false, null, $shopId);

        $forbiddenExtensions = 'marketplace.amazon.com;marketplace.amazon.fr;rueducommerce.com;laredoute.com;zalando.fr;priceminister.com;cdiscount.com;sc.rueducommerce.com;marketplace.fnac.com;clemarche.com;noreplylziflux.fr;marketplace.amazon.it;natureetdecouvertes.com;mp.laredoute-marketplace.fr;monechelle.com;galeries_lafayette.com;noreply-iziflux.com;marketplace.amazon.co.uk;anonyme.com;darty.fr;auchan.fr;marketplace.amazon.es;manomano.fr;noreplyiziflux.fr;alert-shopping-flux.com;macway.fr;downtownstock.com;roure.net;message.manomano.com;notification.mirakl.net';
        Configuration::updateValue('AV_FORBIDDEN_EMAIL', $forbiddenExtensions, false, null, $shopId);
        Configuration::updateValue('AV_ORDER_LIMIT', 1500, false, null, $shopId);
    }

    protected function getVariablesForBackOfficeTpl($credentials, $languages)
    {
        $displayConfiguration = $this->getDisplayConfiguration();

        $currentIdwebsite = $credentials['currentIdwebsite'];
        $currentConnectorKey = $credentials['currentConnectorKey'];
        $currentReloadedStatus = $credentials['currentReloadedStatus'];
        $currentFirstRetroactive = $credentials['currentFirstRetroactive'];

        $orderStatusList = OrderState::getOrderStates((int) Configuration::get('PS_LANG_DEFAULT'));

        $urlBack = ($this->context->link->getAdminLink('AdminModules') .
            '&configure=' . $this->name . '&tab_module=' .
            $this->tab . '&conf=4&module_name=' . $this->name);

        $hookList = $this->nrModel->listRegisteredHooks($this->id, $this->currentShopId);

        $installationModuleDate = date('Y-m-d', strtotime(Configuration::get('AV_LIMIT_LOST_ORDERS')));
        $today = date('Y-m-d');

        $nbReviews = $this->nrModel->getTotalReviews();
        $nbReviewsAverage = $this->nrModel->getTotalReviewsAverage();
        $nbOrders = $this->nrModel->getTotalOrders();

        $isMultilang = Configuration::get(
            'AV_MULTILINGUE',
            null,
            null,
            $this->currentShopId
        );

        $isReloaded = Configuration::get(
            'AV_RELOADED' . $this->groupName,
            null,
            null,
            $this->currentShopId
        );

        $starsFile = $displayConfiguration['starsFile'];
        $starsDir = _PS_ROOT_DIR_ . '/modules/netreviews/views/templates/hook/sub/' . $starsFile;

        $demoRate = 4.5;
        $averageRatePercenter = [];
        $averageRatePercenter['floor'] = floor($demoRate) - 1;
        $averageRatePercenter['decimals'] = ($demoRate - floor($demoRate)) * 20;

        $retroactive = (Configuration::get('AV_FIRST_RETROACTIVE' . $this->groupName, null, null, $this->currentShopId) == '1' ? 1 : 0);
        $clientId = Configuration::get('AV_CLIENTID' . $this->groupName, null, null, $this->currentShopId);
        if ($retroactive == 0 && ($isReloaded != 1 || empty($clientId))) {
            $retroactive = 1;
        }

        $reloadedDomain = ConfSkeepers::getInstance()->getEnv('RELOADED_FRONT');

        return [
            'base_url' => __PS_BASE_URI__,
            'current_lightwidget_checked' => $displayConfiguration['widgetLight'],
            'current_multilingue_checked' => $isMultilang,
            'current_starproductlist_checked' => $displayConfiguration['displayReviewsCategoryPages'],
            'current_template_checked' => $displayConfiguration['genesisTemplate'],
            'current_snippets_website_global_checked' => $displayConfiguration['snippetsType'],
            'current_snippets_site_checked' => $displayConfiguration['avSnippetsActive'],
            'richSnippetsWebsite_checked' => $displayConfiguration['websiteSnippets'],
            'avisverifies_nb_reviews' => $displayConfiguration['genesisNbReviews'],
            'avisverifies_stars_custom_color' => $displayConfiguration['customizedStarColor'],
            'productuniqueginfo_checked' => $displayConfiguration['productInfoGtin'],
            'customized_star_color' => $displayConfiguration['customizedStarColor'],
            'avisverifies_nb_products' => $displayConfiguration['genesisNbReviews'],
            'avisverifies_extra_option' => $displayConfiguration['extraOption'],
            'current_nresponsive_checked' => $displayConfiguration['responsiveDisplayGenesis'],
            'current_hidehelpful_checked' => $displayConfiguration['genesisHideHelpful'],
            'current_hidemedia_checked' => $displayConfiguration['genesisHideMedia'],
            'avisverifies_rename_tag' => $displayConfiguration['renameTab'],
            'tabshow_checked' => $displayConfiguration['tabShow'],
            'stars_image' => $displayConfiguration['useStarFormatImage'],
            'starshome_checked' => $displayConfiguration['homepageStars'],
            'av_reloaded' => $isReloaded,
            'currentIdwebsite' => $currentIdwebsite,
            'currentConnectorKey' => $currentConnectorKey,
            'currentReloadedStatus' => $currentReloadedStatus,
            'currentFirstRetroactive' => $currentFirstRetroactive,
            'version' => $this->version,
            'version_ps' => _PS_VERSION_,
            'orderStatusList' => $orderStatusList,
            'languages' => $languages,
            'debug_nb_reviews' => $nbReviews['nb_reviews'],
            'debug_nb_reviews_average' => $nbReviewsAverage['nb_reviews_average'],
            'debug_nb_orders_flagged' => $nbOrders['flagged'],
            'debug_nb_orders_not_flagged' => $nbOrders['not_flagged'],
            'debug_nb_orders_all' => $nbOrders['all'],
            'av_path' => $this->_path,
            'reloaded_domain' => $reloadedDomain,
            'shop_name' => Configuration::get('PS_SHOP_NAME'),
            'url_back' => $urlBack,
            'stars_dir' => $starsDir,
            'hook_list' => $hookList,
            'installationModuleDate' => $installationModuleDate,
            'today' => $today,
            'average_rate_percent' => $averageRatePercenter,
            'av_rate_percent_int' => ($demoRate) ? round($demoRate * 20) : 100,
            'av_first_retroactive' => $retroactive,
            'url_ajax_retroactive' => $this->context->link->getModuleLink('netreviews', 'AjaxRetroactiveOrder'),
            'retroactive' => 'order',
            'idShop' => $this->groupName,
            'groupName' => $this->currentShopId,
            'max_iteration' => 4,
        ];
    }

    protected function checkThatSpecificShopHasBeenChosen()
    {
        if (1 == Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')
            && (Shop::CONTEXT_ALL == Shop::getContext()
                || Shop::CONTEXT_GROUP == Shop::getContext())) {
            $this->html .= $this->displayError(
                $this->l('Multistore feature is enabled. Please choose above the store to configure.')
            );

            return $this->html;
        }
    }

    protected function checkMultistoreMultilangConfig($languages)
    {
        $currentIdwebsite = [];
        $currentConnectorKey = [];
        $currentReloadedStatus = [];
        $currentFirstRetroactive = [];

        $isMultishop = Shop::isFeatureActive();
        if ($isMultishop && Shop::getTotalShops() > 1 && (Configuration::hasKey(
            'AV_IDWEBSITE',
            null,
            null,
            null
        ) || Configuration::hasKey('AV_GOUPINFO', null, null, null)
        )
        ) {
            $multisite = Configuration::get('AV_MULTISITE', null, null, null);
            $idWebsiteShop1 = Configuration::get('AV_IDWEBSITE', null, null, null);
            $secretKeyShop1 = Configuration::get('AV_CLESECRETE', null, null, null);
            $groupInfoShop1 = Configuration::get('AV_GOUPINFO', null, null, null);
            $firstRetroactiveShop1 = Configuration::get('AV_FIRST_RETROACTIVE', null, null, null);

            Configuration::deleteByName('AV_IDWEBSITE');
            Configuration::updateValue('AV_IDWEBSITE', $idWebsiteShop1, false, $this->currentShopId);
            Configuration::deleteByName('AV_CLESECRETE');
            Configuration::updateValue('AV_CLESECRETE', $secretKeyShop1, false, $this->currentShopId);
            Configuration::deleteByName('AV_GOUPINFO');
            Configuration::updateValue('AV_GOUPINFO', $groupInfoShop1, false, $this->currentShopId);
            Configuration::deleteByName('AV_MULTISITE');
            Configuration::updateValue('AV_MULTISITE', $multisite, false, $this->currentShopId);
            Configuration::deleteByName('AV_FIRST_RETROACTIVE');
            Configuration::updateValue('AV_FIRST_RETROACTIVE', $firstRetroactiveShop1, false, $this->currentShopId);
        }

        $currentIdwebsite['root'] = Configuration::get(
            'AV_IDWEBSITE',
            null,
            null,
            $this->currentShopId
        );
        $currentConnectorKey['root'] = Configuration::get(
            'AV_CLESECRETE',
            null,
            null,
            $this->currentShopId
        );
        $currentReloadedStatus['root'] = Configuration::get(
            'AV_RELOADED',
            null,
            null,
            $this->currentShopId
        );
        $currentFirstRetroactive['root'] = Configuration::get(
            'AV_FIRST_RETROACTIVE',
            null,
            null,
            $this->currentShopId
        );

        foreach ($languages as $lang) {
            $result = $this->updateMultilangConfigValues(
                $currentIdwebsite,
                $currentConnectorKey,
                $currentReloadedStatus,
                $currentFirstRetroactive,
                $lang
            );
            $currentIdwebsite = $result['currentIdwebsite'];
            $currentConnectorKey = $result['currentConnectorKey'];
            $currentReloadedStatus = $result['currentReloadedStatus'];
            $currentFirstRetroactive = $result['currentFirstRetroactive'];
        }

        // update av_groupinfo
        Configuration::updateValue(
            'AV_GOUPINFO',
            netreviewsModel::avJsonEncode($currentIdwebsite),
            false,
            null,
            $this->currentShopId
        );

        $checkResult = $this->checkCredentialsAreNotUsedForTwoDifferentShops(
            $currentIdwebsite,
            $currentConnectorKey
        );
        $currentIdwebsite = $checkResult['currentIdwebsite'];
        $currentConnectorKey = $checkResult['currentConnectorKey'];

        return [
            'currentIdwebsite' => $currentIdwebsite,
            'currentConnectorKey' => $currentConnectorKey,
            'currentReloadedStatus' => $currentReloadedStatus,
            'currentFirstRetroactive' => $currentFirstRetroactive,
        ];
    }

    protected function updateMultilangConfigValues($websiteId, $connectorKey, $reloadedStatus, $firstRetroactive, $lang)
    {
        $websiteId[$lang['iso_code']] = '';
        $connectorKey[$lang['iso_code']] = '';
        $reloadedStatus[$lang['iso_code']] = '';
        $firstRetroactive[$lang['iso_code']] = '1';

        $languageGroupName = $this->getIdConfigurationGroup($lang['iso_code']);

        if (!Configuration::get('AV_FIRST_RETROACTIVE' . $languageGroupName, null, null, $this->currentShopId)) {
            Configuration::updateValue(
                'AV_FIRST_RETROACTIVE' . $languageGroupName,
                '',
                false,
                null,
                $this->currentShopId
            );
        }
        // done on purpose ;)
        if ($languageGroupName) {
            $websiteId[$lang['iso_code']] = Configuration::get(
                'AV_IDWEBSITE' . $languageGroupName,
                null,
                null,
                $this->currentShopId
            );
            $connectorKey[$lang['iso_code']] = Configuration::get(
                'AV_CLESECRETE' . $languageGroupName,
                null,
                null,
                $this->currentShopId
            );
            $reloadedStatus[$lang['iso_code']] = Configuration::get(
                'AV_RELOADED' . $languageGroupName,
                null,
                null,
                $this->currentShopId
            );
            $firstRetroactive[$lang['iso_code']] = Configuration::get(
                'AV_FIRST_RETROACTIVE' . $languageGroupName,
                null,
                null,
                $this->currentShopId
            );
            $clientIdTmp = Configuration::get(
                'AV_CLIENTID' . $languageGroupName,
                null,
                null,
                $this->currentShopId
            );
            $urlTmp = Configuration::get(
                'AV_RETROACTIVE_ORDERS_ENDPOINT' . $languageGroupName,
                null,
                null,
                $this->currentShopId
            );

            $firstRetroactive[$lang['iso_code']] =
                ($firstRetroactive[$lang['iso_code']] != 1 && !empty($reloadedStatus[$lang['iso_code']])
                && !empty($clientIdTmp) && !empty($urlTmp)) ? '0' : '1';
        }

        return [
            'currentIdwebsite' => $websiteId,
            'currentConnectorKey' => $connectorKey,
            'currentReloadedStatus' => $reloadedStatus,
            'currentFirstRetroactive' => $firstRetroactive,
        ];
    }

    protected function checkCredentialsAreNotUsedForTwoDifferentShops($websiteId, $connectorKey)
    {
        $keycheck = netreviewsModel::getMultiShopValues('AV_GOUPINFO');
        $keycheckResult = [];
        $keycheck = netreviewsModel::avJsonEncode(array_map('json_decode', $keycheck));
        $keycheck = json_decode($keycheck, true);

        foreach ($keycheck as $value_keycheck) {
            $keycheckResult = $this->addCheckedValue($value_keycheck, $keycheckResult);
        }

        $doubleKeyFound = false;
        if ((isset($keycheckResult['lang']) && (count($keycheckResult['lang']) !== count(
            array_unique($keycheckResult['lang'])
        ))) || (isset($keycheckResult['root']) && (count($keycheckResult['root']) !== count(
            array_unique($keycheckResult['root'])
        )))) {
            $doubleKeyFound = true;
        }

        if (!$doubleKeyFound) {
            return [
                'currentIdwebsite' => $websiteId,
                'currentConnectorKey' => $connectorKey,
            ];
        }

        $this->html .= $this->displayError(
            $this->l(
                'Each idWebsite and key can be used only once,
                    pleack check your current informations'
            )
        );
        Configuration::deleteFromContext('AV_GOUPINFO');
        Configuration::deleteFromContext('AV_IDWEBSITE');
        Configuration::deleteFromContext('AV_CLESECRETE');
        $websiteId['root'] = Configuration::get(
            'AV_IDWEBSITE',
            null,
            null,
            $this->currentShopId
        );
        $websiteId['root'] = Configuration::get(
            'AV_CLESECRETE',
            null,
            null,
            $this->currentShopId
        );

        return [
            'currentIdwebsite' => $websiteId,
            'currentConnectorKey' => $connectorKey,
        ];
    }

    protected function addCheckedValue($value, $keycheckResult)
    {
        if ($value) {
            foreach ($value as $lang_check => $value) {
                if ('root' != $lang_check && '' != $value) {
                    $keycheckResult['lang'][] = $value;
                } elseif ('root' == $lang_check && '' != $value) {
                    $keycheckResult['root'][] = $value;
                }
            }
        }

        return $keycheckResult;
    }

    /**
     * Save configuration form.
     */
    protected function postProcess()
    {
        if (Tools::isSubmit('submit_configuration')) {
            $this->updateGeneralConfig();
        }

        if (Tools::isSubmit('submit_export')) {
            $this->generateExportCsv();
        }

        if (Tools::isSubmit('submit_export_retroactive')) {
            $this->generateExportRetroactiveCsv();
        }

        if (Tools::isSubmit('submit_advanced')) {
            $this->updateAdvancedConfig();
        }

        if (Tools::isSubmit('submit_addhooklist')) {
            $this->forceRegisterHooks();
        }

        if (Tools::isSubmit('submit_purge')) {
            $this->executeOrdersPurge();
        }

        if (Tools::isSubmit('submit_generateLostOrders_period')
            || Tools::isSubmit('submit_generateLostOrders_all')) {
            $this->generateLostOrders();
        }

        if (Tools::isSubmit('sync_configuration')) {
            HookHandler::checkDisplayHomeTriggers(true);
        }
    }

    protected function updateGeneralConfig()
    {
        $idWebsiteCurrent = trim(Tools::getValue('avisverifies_idwebsite'));
        $cleSecreteCurrent = trim(Tools::getValue('avisverifies_clesecrete'));

        Configuration::updateValue(
            'AV_MULTILINGUE',
            Tools::getValue('avisverifies_multilingue'),
            false,
            null,
            $this->currentShopId
        );
        Configuration::updateValue('AV_IDWEBSITE', $idWebsiteCurrent, false, null, $this->currentShopId);
        Configuration::updateValue('AV_CLESECRETE', $cleSecreteCurrent, false, null, $this->currentShopId);
        Configuration::updateValue('AV_MULTISITE', $this->currentShopId, false, null, $this->currentShopId);

        if ('checked' == Configuration::get('AV_MULTILINGUE', null, null, $this->currentShopId)) {
            $this->saveMultilangConfig();
        }

        HookHandler::checkDisplayHomeTriggers(true);

        $internalConfigManager = new InternalConfigManager($this->currentShopId);
        $genesisConfiguredAccounts = $internalConfigManager::hasGenesisConfiguredAccounts($this->currentShopId);
        $isReloadedBefore9 = $internalConfigManager::isReloadedBeforeVersion9($this->currentShopId);

        if (!empty($genesisConfiguredAccounts) || $isReloadedBefore9) {
            $migrationHandler = new MigrationHandler($this->currentShopId);
            $migrationHandler->initMigration();
        }
    }

    protected function saveMultilangConfig()
    {
        if (is_null($this->currentShopId)) {
            $idShopQuery = ' AND id_shop IS NULL';
        } else {
            $idShopQuery = ' AND id_shop = "' . $this->currentShopId . '"';
        }

        $sql = $this->selectName . _DB_PREFIX_ . "configuration
                where (name like 'AV_GROUP_CONF_%'
                OR name like 'AV_IDWEBSITE_%'
                OR name like 'AV_CLESECRETE_%')
                " . $idShopQuery;
        $idshopConf = true;
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                Configuration::deleteFromContext($row['name']);
            }
        } else {
            $idshopConf = false; // if multishop but only one shop enabled or non multishop
            $sqlWithoutIdShop = $this->selectName . _DB_PREFIX_ . "configuration
                            where (name like 'AV_GROUP_CONF_%'
                            OR name like 'AV_IDWEBSITE_%'
                            OR name like 'AV_CLESECRETE_%')";

            if ($results = Db::getInstance()->ExecuteS($sqlWithoutIdShop)) {
                foreach ($results as $row) {
                    Configuration::deleteFromContext($row['name']);
                }
            }
        }
        Configuration::updateValue('AV_MULTISITE', $idshopConf);
        // in case that it's not multishop while configurated as multishop
        $languages = Language::getLanguages(true);
        if (!empty($languages)) {
            $this->setIdConfigurationGroup($languages, $idshopConf);
        }
    }

    protected function generateExportCsv()
    {
        try {
            $headerColums = 'id_order;reference;order_amount;email;firstname;lastname;date_order;payment_method;carrer;delay;id_product;category;description;ean13;upc;mpn;brand;product_url;image_product_url;order_state_id;order_state;iso_lang;id_shop' . "\r\n";

            $returnExport = $this->nrModel->export($headerColums, $this->currentShopId);
            if (file_exists($returnExport[2])) {
                $this->html .= $this->displayConfirmation(
                    sprintf(
                        $this->l('%s orders have been exported.'),
                        $returnExport[1]
                    ) . '<a href="../modules/netreviews/Export_NetReviews_' . $returnExport[0] . '">' .
                    $this->l('Click here to download the file') . '</a>'
                );
            } else {
                $this->html .= $this->displayError(
                    $this->l(
                        'Writing on the server is not allowed.
                        Please assign write permissions to the folder netreviews'
                    ) . $returnExport[2]
                );
            }
        } catch (Exception $e) {
            $this->html .= $this->displayError($e->getMessage());
        }
    }

    protected function generateExportRetroactiveCsv()
    {
        try {
            $headerColums = 'channel;purchase_date;purchase_price;purchase_reference;first_name;last_name;email;phone;hide_personal_data;language;delay;delay_product;name;product_ref;category;brand;product_price;ean;sku;upc;isbn;jan;mpn;product_url;image_url;flagged' . "\r\n";

            $returnExport = $this->nrModel->exportRetroactive($headerColums, $this->currentShopId);
            if (file_exists($returnExport[2])) {
                $this->html .= $this->displayConfirmation(
                    sprintf(
                        $this->l('%s orders have been exported.'),
                        $returnExport[1]
                    ) . '<a href="../modules/netreviews/Export_NetReviews_Retroactive_' . $returnExport[0] . '">' .
                    $this->l('Click here to download the file') . '</a>'
                );
            } else {
                $this->html .= $this->displayError(
                    $this->l(
                        'Writing on the server is not allowed.
                        Please assign write permissions to the folder netreviews'
                    ) . $returnExport[2]
                );
            }
        } catch (Exception $e) {
            $this->html .= $this->displayError($e->getMessage());
        }
    }

    protected function updateAdvancedConfig()
    {
        $keys = [
            'avisverifies_lightwidget' => 'AV_LIGHTWIDGET',
            'netreviews_snippets_site' => 'AV_DISPLAYSNIPPETSITE',
            'netreviews_snippets_website_global' => 'AV_SNIPPETSITETYPE',
            'avisverifies_checkRichSnippetsWebsite_show' => 'AV_RICHSNIPPETSWEBSITE',
            'avisverifies_nb_reviews' => 'AV_NBOFREVIEWS',
            'avisverifies_stars_custom_color' => 'AV_STARCOLOR',
            'avisverifies_productuniqueginfo' => 'AV_PRODUCTUNIGINFO',
            'avisverifies_nb_products' => 'AV_NBOPRODUCTS',
            'avisverifies_rename_tag' => 'AV_TABNEWNAME',
            'avisverifies_extra_option' => 'AV_EXTRA_OPTION',
            'avisverifies_star_productlist' => 'AV_DISPLAYSTARPLIST',
            'avisverifies_template' => 'AV_TEMPLATE',
            'avisverifies_tab_show' => 'AV_TABSHOW',
            'avisverifies_stars_image' => 'AV_FORMAT_IMAGE',
            'avisverifies_starshome_show' => 'AV_STARSHOMESHOW',
            'avisverifies_hidehelpful' => 'AV_HELPFULHIDE',
            'avisverifies_hidemedia' => 'AV_MEDIAHIDE',
            'avisverifies_nresponsive' => 'AV_NRESPONSIVE',
        ];

        foreach ($keys as $index => $key) {
            if (false !== ($value = Tools::getValue($index))) {
                Configuration::updateValue(
                    $key,
                    $value,
                    false,
                    null,
                    $this->currentShopId
                );
            }
        }
    }

    protected function forceRegisterHooks()
    {
        if (!$this->registerHook('displayHeader')
            || !$this->registerHook('displayFooter')
            || !$this->registerHook('displayProductPriceBlock')
            || !$this->registerHook('CategorystarsNetreviews')
            || !$this->registerHook('displayProductAdditionalInfo')
            || !$this->registerHook('displayProductExtraContent')
            || !$this->registerHook('displayPaymentTop')
            || !$this->registerHook('ExtraNetreviews')
            || !$this->registerHook('TabcontentNetreviews')
            || !$this->registerHook('actionOrderStatusPostUpdate')
            || !$this->registerHook('actionValidateOrder')
            || !$this->registerHook(HookHandler::DISPLAY_HOME_HOOK)
        ) {
            return false;
        }

        return true;
    }

    protected function executeOrdersPurge()
    {
        $queryIdShop = '';
        if (1 == Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')) {
            $queryIdShop = ' AND oav.id_shop = ' . (int) $this->currentShopId;
        }

        $query = 'SELECT oav.id_order, o.date_add as date_order,o.id_customer
                        FROM ' . _DB_PREFIX_ . 'av_orders oav
                        LEFT JOIN ' . _DB_PREFIX_ . 'orders o
                        ON oav.id_order = o.id_order
                        LEFT JOIN ' . _DB_PREFIX_ . 'order_history oh
                        ON oh.id_order = o.id_order
                        WHERE (oav.flag_get IS NULL OR oav.flag_get = 0)'
            . $queryIdShop;

        $ordersList = Db::getInstance()->ExecuteS($query);
        if (!empty($ordersList)) {
            foreach ($ordersList as $order) { /* Set orders as getted */
                Db::getInstance()->Execute(
                    'UPDATE ' . _DB_PREFIX_ . 'av_orders
                                                SET horodate_get = "' . time() . '", flag_get = 1
                                                WHERE id_order = ' . (int) $order['id_order']
                );
            }
            $this->html .= $this->displayConfirmation(
                sprintf(
                    $this->l('The orders has been purged for %s'),
                    $this->context->shop->name
                )
            );
        } else {
            $this->html .= $this->displayError(
                sprintf(
                    $this->l('No orders to purged for %s'),
                    $this->context->shop->name
                )
            );
        }
    }

    protected function generateLostOrders()
    {
        if (!empty(Tools::getValue('submit_generateLostOrders_end'))) {
            $datesForQuery = $this->getDatesWhenEndDateIsInformed(pSql(Tools::getValue('submit_generateLostOrders_end')));
        } else {
            $datesForQuery = $this->getDatesWhenNoEndDate();
        }

        $queryIdShop = '';
        if (1 == Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')) {
            $queryIdShop = ' AND o.id_shop = ' . (int) $this->currentShopId;
        }

        // Récupère toutes les commandes créées depuis l'installation du module qui ne sont pas dans notre table
        $query = 'SELECT o.id_order, o.date_add as date_order, o.id_shop, lg.iso_code
                        FROM ' . _DB_PREFIX_ . 'orders o
                        LEFT JOIN ' . _DB_PREFIX_ . 'lang lg
                            ON o.id_lang = lg.id_lang
                        LEFT JOIN ' . _DB_PREFIX_ . 'av_orders oav
                            ON o.id_order = oav.id_order
                        WHERE o.id_order NOT IN (SELECT oav.id_order FROM ' . _DB_PREFIX_ . 'av_orders oav )' .
            $datesForQuery . $queryIdShop;

        $ordersList = Db::getInstance()->ExecuteS($query);
        $i = 0;

        $shopName = $this->context->shop->name;

        if (!empty($ordersList)) {
            foreach ($ordersList as $order) {
                $qryOrderInsert = '
                    INSERT INTO ' . _DB_PREFIX_ . 'av_orders (id_order, id_shop, iso_lang, flag_get,
                    horodate_now) VALUES (' . $order['id_order'] . ',"' . $order['id_shop'] . '","' .
                    $order['iso_code'] . '", 0 ,"' . pSQL($order['date_order']) . '")
                        ';
                $result = Db::getInstance()->Execute($qryOrderInsert);
                if ($result) {
                    ++$i;
                } else {
                    $this->html .= $this->displayError(
                        sprintf($this->l('There was a problem. Check MySQL errors'))
                    );

                    return false;
                }
            }
            $this->html .= $this->displayConfirmation(
                sprintf($this->l($i . ' orders have been added to be collected for %s'), $shopName)
            );
        } else {
            $this->html .= $this->displayError(sprintf($this->l('No orders to add for %s'), $shopName));
        }
    }

    private function getDatesWhenEndDateIsInformed($endDate)
    {
        $date = (new DateTime($endDate))->add(new DateInterval('P1D'));
        $endDate = $date->format('Y-m-d');

        if (!empty(Tools::getValue('submit_generateLostOrders_start'))) {
            $startDate = pSQL(Tools::getValue('submit_generateLostOrders_start'));
        } else {
            $startDate = Configuration::get('AV_LIMIT_LOST_ORDERS');
        }

        return ' AND o.date_add >= "' . $startDate .
            '" AND o.date_add < "' . $endDate . '"';
    }

    private function getDatesWhenNoEndDate()
    {
        if (1 == Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')) {
            $idShop = (int) $this->currentShopId;
            if (!Configuration::hasKey('AV_LIMIT_LOST_ORDERS', null, null, $idShop)) {
                Configuration::updateValue(
                    'AV_LIMIT_LOST_ORDERS',
                    date('Y-m-d'),
                    false,
                    null,
                    $idShop
                );
                $startDate = Configuration::get('AV_LIMIT_LOST_ORDERS');
            } else {
                $startDate = Configuration::get('AV_LIMIT_LOST_ORDERS');
            }
        } else {
            if (!Configuration::hasKey('AV_LIMIT_LOST_ORDERS')) {
                Configuration::updateValue('AV_LIMIT_LOST_ORDERS', date('Y-m-d'));
            }
            $startDate = Configuration::get('AV_LIMIT_LOST_ORDERS');
        }

        return ' AND o.date_add > "' . $startDate . '"';
    }

    protected function addFiles($filename, $type)
    {
        if ('css' == $type) {
            $this->context->controller->addCSS($this->_path . 'views/css/' . $filename . '.css', 'all');
        } elseif ('js' == $type) {
            $this->context->controller->addJS($this->_path . 'views/js/' . $filename . '.js', true);
        }
    }

    protected function getIdConfigurationGroup($isoLang = null)
    {
        $multisite = Configuration::get('AV_MULTISITE');

        if (1 == Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') && !empty($multisite)) {
            $sql = $this->selectName . _DB_PREFIX_ . "configuration where name like 'AV_GROUP_CONF_%' And id_shop = '"
                . $this->currentShopId . "'";
        } else {
            $sql = $this->selectName . _DB_PREFIX_ . "configuration where name like 'AV_GROUP_CONF_%'";
        }
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                $vconf = json_decode(Configuration::get($row['name'], null, null, $this->currentShopId), true);
                if ($vconf && in_array($isoLang, $vconf)) {
                    return '_' . Tools::substr($row['name'], 14);
                }
            }
        }
    }

    protected function setIdConfigurationGroup($languages = null, $idShopConf = true)
    {
        if (empty($languages)) {
            return;
        }

        reset($languages);
        $idCurrentLang = key($languages);
        $lang = $languages[$idCurrentLang];

        $currentWebsiteId = trim(Tools::getValue('avisverifies_idwebsite_' . $lang['iso_code']));
        $currentSecretKey = trim(Tools::getValue('avisverifies_clesecrete_' . $lang['iso_code']));

        if (empty($currentWebsiteId) || empty($currentSecretKey)) { // no credentials for this lang
            unset($languages[$idCurrentLang]);

            return $this->setIdConfigurationGroup($languages, $idShopConf);
        }

        if (1 == Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') && $idShopConf) {
            $addedCondition = 'And id_shop = ' . (int) $this->currentShopId;
        } else {
            $addedCondition = '';
        }

        $sql = $this->selectName . _DB_PREFIX_ . "configuration
            WHERE value = '" . pSQL($currentWebsiteId) . "'
            AND name like 'AV_IDWEBSITE_" . $idCurrentLang . "' " . $addedCondition;

        $row = Db::getInstance()->getRow($sql);

        if ($row
            && (Configuration::get(
                'AV_CLESECRETE_' . Tools::substr($row['name'], 13),
                null,
                null,
                $this->currentShopId
            ) != $currentSecretKey)
        ) {
            $this->context->controller->errors[] = sprintf(
                $this->l(
                    'PARAM ERROR:
                please check your multilingual configuration for
                the id_website "%s" at language "%s"'
                ),
                $currentWebsiteId,
                $lang['name']
            );
            unset($languages[$idCurrentLang]);

            return $this->setIdConfigurationGroup($languages, $idShopConf);
        }

        $group = [];
        array_push($group, $lang['iso_code']);
        unset($languages[$idCurrentLang]);

        foreach ($languages as $lang1) {
            if ($currentWebsiteId == Tools::getValue('avisverifies_idwebsite_' . $lang1['iso_code'])
                && $currentSecretKey == Tools::getValue('avisverifies_clesecrete_' . $lang1['iso_code'])) {
                array_push($group, $lang1['iso_code']);

                $this->context->controller->errors[] = sprintf(
                    $this->l(
                        'PARAM ERROR:
                    please check your multilingual configuration for
                    the id_website "%s" at language "%s"'
                    ),
                    $currentWebsiteId,
                    $lang['name']
                );

                return $this->setIdConfigurationGroup($languages, $idShopConf);
            }
        }

        // Create PS configuration variable
        if ($idShopConf) {
            $idshop = $this->context->shop->getContextShopID();
        } else {
            $idshop = $this->currentShopId;
        }
        if (!Configuration::get('AV_IDWEBSITE_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_IDWEBSITE_' . $idCurrentLang, $currentWebsiteId, false, null, $idshop);
        }

        if (!Configuration::get('AV_CLESECRETE_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_CLESECRETE_' . $idCurrentLang, $currentSecretKey, false, null, $idshop);
        }

        if (!Configuration::get('AV_GROUP_CONF_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue(
                'AV_GROUP_CONF_' . $idCurrentLang,
                netreviewsModel::avJsonEncode($group),
                false,
                null,
                $idshop
            );
        }

        if (!Configuration::get('AV_PROCESSINIT_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_PROCESSINIT_' . $idCurrentLang, 'onorderstatuschange', false, null, $idshop);
        }

        if (!Configuration::get('AV_ORDERSTATESCHOOSEN_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_ORDERSTATESCHOOSEN_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (Configuration::get('AV_RELOADED_' . $idCurrentLang, null, null, $idshop) === false) {
            Configuration::updateValue('AV_RELOADED_' . $idCurrentLang, '', false, null, $idshop);
        }
        if (!Configuration::get('AV_ACTIVATE_COLLECT_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_ACTIVATE_COLLECT_' . $idCurrentLang, '', false, null, $idshop);
        }
        if (!Configuration::get('AV_CLIENTID_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_CLIENTID_' . $idCurrentLang, '', false, null, $idshop);
        }
        if (!Configuration::get('AV_SECRETAPI_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_SECRETAPI_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_DELAY_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_DELAY_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_DELAY_PRODUIT_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_DELAY_PRODUIT_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_GETPRODREVIEWS_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_GETPRODREVIEWS_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_DISPLAYPRODREVIEWS_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_DISPLAYPRODREVIEWS_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_SCRIPTFLOAT_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_SCRIPTFLOAT_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_SCRIPTFLOAT_ALLOWED_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_SCRIPTFLOAT_ALLOWED_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_SCRIPTFIXE_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_SCRIPTFIXE_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_SCRIPTFIXE_ALLOWED_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_SCRIPTFIXE_ALLOWED_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_SCRIPTFIXE_POSITION' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_SCRIPTFIXE_POSITION' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_URLCERTIFICAT_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_URLCERTIFICAT_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_FORBIDDEN_EMAIL_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_FORBIDDEN_EMAIL_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_CODE_LANG_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_CODE_LANG_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_COLLECT_CONSENT_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_COLLECT_CONSENT_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_RELOADEDSCRIPT_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_RELOADEDSCRIPT_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_TAGAVERAGE_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_TAGAVERAGE_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_TAGREVIEWS_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_TAGREVIEWS_' . $idCurrentLang, '', false, null, $idshop);
        }

        if (!Configuration::get('AV_RELOADED_' . $idCurrentLang, null, null, $idshop)) {
            Configuration::updateValue('AV_RELOADED_' . $idCurrentLang, '', false, null, $idshop);
        }

        return $this->setIdConfigurationGroup($languages, $idShopConf);
    }

    protected function smartyAssign($smartyArray)
    {
        return $this->context->smarty->assign($smartyArray);
    }

    protected function displayTemplate($tpl)
    {
        return $this->display(__FILE__, "views/templates/hook/$tpl.tpl");
    }

    public function hookDisplayHeader($params)
    {
        HookHandler::hookTriggers(HookHandler::DISPLAY_HEADER_HOOK);
        $this->includeJsCssFiles();

        return $this->insertFloattingWidget();
    }

    private function includeJsCssFiles()
    {
        $isProductWidgetReloaded = false;
        $avTemplate = Configuration::get('AV_TEMPLATE', null, null, $this->currentShopId);
        $css = 'avisverifies-template1';
        if ($avTemplate && '2' == $avTemplate) {
            $css = 'avisverifies-template2';
        }

        if ($this->isReloaded()) {
            $isProductWidgetReloaded = true;
            // TagJS or Product Widget css specifically for category page
            if (property_exists($this->context->controller, 'php_self')) {
                if (in_array($this->context->controller->php_self, ['index', 'category', 'product'])) {
                    $this->addFiles('category-reloaded', 'css');
                }
                if (in_array($this->context->controller->php_self, ['product'])) {
                    $this->addFiles('avisverifies-reloaded-tpl', 'js');
                }
            }
        }

        // home page, product page, categoy page - TagJS or Product Widget script pages do not add module css/js
        if ($isProductWidgetReloaded) {
            if (property_exists($this->context->controller, 'php_self')
                && !in_array($this->context->controller->php_self, ['index', 'product', 'category'])
            ) {
                $this->addFiles($css, 'css');
                $this->addFiles('avisverifies-tpl', 'js');
            }
        } else {
            $this->addFiles($css, 'css');
            $this->addFiles('avisverifies-tpl', 'js');
        }
    }

    private function insertFloattingWidget()
    {
        $isReloaded = Configuration::get(
            'AV_RELOADED',
            null,
            null,
            $this->currentShopId
        );
        $avisverifiesScriptfloatAllowed = Configuration::get(
            'AV_SCRIPTFLOAT_ALLOWED',
            null,
            null,
            $this->currentShopId
        );
        $avScriptflottant = Configuration::get('AV_SCRIPTFLOAT', null, null, $this->currentShopId);

        if ('checked' === Configuration::get('AV_MULTILINGUE', null, null, $this->currentShopId)) {
            $avisverifiesScriptfloatAllowed = null;
            $avScriptflottant = null;
            if (null !== $this->groupName) {
                $avisverifiesScriptfloatAllowed = Configuration::get(
                    'AV_SCRIPTFLOAT_ALLOWED' . $this->groupName,
                    null,
                    null,
                    $this->currentShopId
                );
                $avScriptflottant = Configuration::get(
                    'AV_SCRIPTFLOAT' . $this->groupName,
                    null,
                    null,
                    $this->currentShopId
                );
            }
        }

        $widgetFlottantCode = '';
        if ((true != strpos(Tools::strtolower($avScriptflottant), 'null') || Tools::strlen($avScriptflottant) > 10)
            && ('yes' === $avisverifiesScriptfloatAllowed || $isReloaded === '1')) {
            $widgetFlottantCode .= "\n" . Tools::stripslashes(html_entity_decode($avScriptflottant));
        }

        return $widgetFlottantCode;
    }

    public function storeKeysConfigured()
    {
        $avMultilingue = ('checked' === Configuration::get('AV_MULTILINGUE', null, null, $this->currentShopId));
        $idWebsite = Configuration::get('AV_IDWEBSITE' . $this->groupName, null, null, $this->currentShopId);
        $secureKey = Configuration::get('AV_CLESECRETE' . $this->groupName, null, null, $this->currentShopId);
        // Step 1: Test that the store/storefront(multilangue) - hasKeys aka idWebsite & secretKey
        $hasKeys = false;
        if ($avMultilingue) {
            $hasKeys = !empty($this->groupName); // $this->groupName will be null if multilangue and
        // no idWebsite & secretKey registered on the storefront
        } else {
            $hasKeys = !empty($idWebsite) && !empty($secureKey);
        }

        return $hasKeys;
    }

    public function hookDisplayHome($params)
    {
        HookHandler::hookTriggers(HookHandler::DISPLAY_HOME_HOOK);
    }

    /**
     * Integration stars on category page used 3 hooks,
     * hookCategorystarsNetreviews  (parent hook)
     * hookDisplayProductPriceBlock @params[type]=before_price.
     */
    public function hookCategorystarsNetreviews($params)
    {
        $displayConfiguration = $this->getDisplayConfiguration();
        $productData = $this->getProductData($params['product']);
        $link = $productData['link'];
        $productId = $productData['productId'];
        $displayInProductsList = $displayConfiguration['displayReviewsCategoryPages'];

        if ((property_exists($this->context->controller, 'php_self')
                && 'index' == $this->context->controller->php_self
                && 0 == $displayConfiguration['showStarsHomepage'])
            || (property_exists($this->context->controller, 'php_self')
                && 'category' == $this->context->controller->php_self
                && 0 == $displayInProductsList)
            || (!isset($productId) || empty($productId))) {
            return null;
        }

        if ($this->isReloaded()) {
            return $this->categoryTagStars($productId);
        }

        $statProductCategory = $this->getStatsForCategoryPages($productId);
        if (isset($statProductCategory['nb_reviews']) && $statProductCategory['nb_reviews'] > 0) {
            $averageRatePercent = [];
            $averageRatePercent['floor'] = floor((int) $statProductCategory['rate']) - 1;
            $averageRatePercent['decimals'] =
                ($statProductCategory['rate'] - floor((int) $statProductCategory['rate'])) * 20;
            $avRatePercentInt = $statProductCategory['rate'] ? round($statProductCategory['rate'] * 20) : 100;

            $this->smartyAssign([
                'av_nb_reviews' => $statProductCategory['nb_reviews'],
                'av_rate' => $statProductCategory['rate'],
                'average_rate_percent' => $averageRatePercent,
                'av_rate_percent_int' => $avRatePercentInt,
                'link_product' => ($link ? $link : ''),
                'use_star_format_image' => $displayConfiguration['useStarFormatImage'],
                'average_rate' => round($statProductCategory['rate'], 1),
                'customized_star_color' => $displayConfiguration['customizedStarColor'],
            ]);

            $tpl = 'avisverifies-categorystars';

            return $this->displayTemplate($tpl);
        }
    }

    private function getProductData($product)
    {
        $link = '';
        if (is_object($product)) {
            $productId = (int) $product->id_product;
            if (isset($product->link)) {
                $link = $product->link;
            }
        } else {
            $productId = (int) $product['id_product'];
            if (isset($product['link'])) {
                $link = $product['link'];
            }
        }

        return [
            'link' => $link,
            'productId' => $productId,
        ];
    }

    /**
     * display category stars Reloaded.
     */
    public function categoryTagStars($idProduct)
    {
        $key = 'AV_DISPLAYSTARPLIST';

        if (property_exists($this->context->controller, 'php_self')
            && 'index' == $this->context->controller->php_self) {
            $key = 'AV_STARSHOMESHOW';
        }

        $avTagAverage = Configuration::get('AV_TAGAVERAGE' . $this->groupName, null, null, $this->currentShopId);
        $avTagAverage = Tools::stripslashes(html_entity_decode($avTagAverage));

        $avScript = Configuration::get('AV_RELOADEDSCRIPT' . $this->groupName, null, null, $this->currentShopId);
        $avisverifiesDisplayStars = Configuration::get($key, null, null, $this->currentShopId);

        // Please note TagJS or Product Widget requires following variables, AverageTagJS, ScriptTagJS
        if (!empty($avTagAverage) && !empty($avScript) && !empty($avisverifiesDisplayStars)) {
            $this->context->smarty->assign('product_id', $idProduct);
            try {
                // https://www.smarty.net/forums/viewtopic.php?p=94650&sid=81185d158408ef1d636d6a7dcc20c55c
                $avTagAverage = $this->context->smarty->fetch($this->smartyString . $avTagAverage);

                return "\n" . $avTagAverage;
            } catch (Exception $e) {
                // can not display stars
            }
        } // Else : then store / storefront is active for TagJS or Product Widget but has either chosen

        // no to display reviews or is missing configuration variables (Tag or Script or both)
        return null;
    }

    public function hookDisplayProductPriceBlock($params)
    {
        if (isset($params['type']) && ('before_price' == $params['type'])) {
            return $this->hookCategorystarsNetreviews($params);
        }
    }

    public function hookDisplayPaymentTop($params)
    {
        if ('checked' == Configuration::get('AV_MULTILINGUE', null, null, $this->currentShopId)) {
            if (Configuration::hasKey('AV_COLLECT_CONSENT', null, null, $this->currentShopId)) {
                Configuration::deleteByName('AV_COLLECT_CONSENT');
            }
            if (Configuration::hasKey('AV_CONSENT_ANSWER_NO', null, null, $this->currentShopId)) {
                Configuration::deleteByName('AV_CONSENT_ANSWER_NO');
            }
            $key = 'AV_COLLECT_CONSENT' . $this->groupName;
        } else {
            $key = 'AV_COLLECT_CONSENT';
        }

        $idCustomer = $this->context->cart->id_customer;

        if (Configuration::hasKey($key, null, null, $this->currentShopId)) {
            $collectConsent = Configuration::get($key, null, null, $this->currentShopId);
            $url = $this->context->link->getModuleLink('netreviews', 'ajax');
            if ('yes' === $collectConsent) {
                $tpl = 'avisverifies-checkbox-consent';
                $this->smartyAssign([
                    'url' => $url,
                    'idShop' => $this->currentShopId,
                    'groupName' => $this->groupName,
                    'idCustomer' => $idCustomer,
                    'prestashopVersion' => _PS_VERSION_,
                ]);

                return $this->displayTemplate($tpl);
            }
        }
    }

    /**
     * Integration of TagJS or Product Widget or Product Widget
     * List of Reviews.
     */
    public function displayReviewsReloaded($product, $langId, $tab_version)
    {
        $displayConfiguration = $this->getDisplayConfiguration();
        $avTagReviews = Tools::stripslashes(html_entity_decode($displayConfiguration['avReviewsTag']));
        $displayProductReviews = ('yes' == $displayConfiguration['displayProductReviews']);

        $productData = $this->getProductDetails($product, $langId);

        if (empty($avTagReviews) || empty($displayConfiguration['avTagJsScript']) || !$displayProductReviews) {
            // Then store / storefront is active for TagJS but has either chosen no to display reviews or
            // is missing configuration variables (Tag or Script or both)
            return null;
        }

        $this->context->smarty->assign('product_id', $product->id);
        $this->context->smarty->assign('product_price', $productData['price']);
        $this->context->smarty->assign('product_name', $productData['name']);
        $this->context->smarty->assign('product_url', $productData['productUrlPage']);
        $this->context->smarty->assign('url_image', $productData['imageUrl']);
        $this->context->smarty->assign('product_currency', $productData['currency']);

        try {
            // https://www.smarty.net/forums/viewtopic.php?p=94650&sid=81185d158408ef1d636d6a7dcc20c55c
            $content = $this->context->smarty->fetch($this->smartyString . $avTagReviews);
            $attributeAv = ['id' => 'netreviews_tab', 'class' => 'netreviews_tab'];
            $newTitle = Configuration::get('AV_TABNEWNAME', null, null, $this->currentShopId);
            $titleAsString = $this->l('verified reviews');
            $title = (!empty($newTitle)) ? $newTitle : $titleAsString;
            if ($tab_version) {
                return $this->getExtraContent($attributeAv, $title, $content);
            } else {
                return $content;
            }
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Utilised in hookTabcontentNetreviews.
     */
    public function getExtraContent($attributeAv, $title, $content)
    {
        $extraContent = (new PrestaShop\PrestaShop\Core\Product\ProductExtraContent());
        $extraContent->setAttr($attributeAv);
        $extraContent->setTitle($title);
        $extraContent->setContent($content);
        $array[] = $extraContent;

        return $array;
    }

    /**
     * Modifications in $reviews need to be duplicated in ajax-load.php
     * Display reviews on the product page, used 3 hooks
     * hookTabcontentNetreviews (parent hook).
     *
     * hookDisplayProductExtraContent (version > 1.7)
     */
    public function hookTabcontentNetreviews($params)
    {
        // if it's not product page, the reviews won't display
        if (!$this->isProductPage()) {
            return null;
        }

        if (!isset($params['product']->use_tabconent[0])) {
            $tab_version = false;
        } else {
            $tab_version = $params['product']->use_tabconent[0];
        }

        $displayConfiguration = $this->getDisplayConfiguration();
        $displayProductReviews = $displayConfiguration['displayProductReviews'];
        $avisverifiesNbReviews = (int) $displayConfiguration['genesisNbReviews'];
        $avisverifiesNbReviews = (!empty($avisverifiesNbReviews)) ? $avisverifiesNbReviews : 5;
        $localWebsiteId = Configuration::get(
            'AV_IDWEBSITE' . $this->groupName,
            null,
            null,
            $this->currentShopId
        );

        $langId = $this->getLangId();
        $productId = (int) Tools::getValue('id_product');
        $productStats = (!isset($this->stats_product) || empty($this->stats_product)) ?
            $this->nrModel->getStatsProduct($productId, $this->groupName, $this->currentShopId) : $this->stats_product;
        $product = new Product($productId, false, $langId);

        if ($this->isReloaded() && $this->checkProductHasReview($productId)) {
            return $this->displayReviewsReloaded($product, $langId, $tab_version);
        }

        $languageGroup = $this->groupName;
        $shopIdGroup = $this->currentShopId;
        if ($this->allMultishopsReviews || $this->allLanguagesReviews) { // override rate & nb_reviews
            $productStats = $this->overrideStatsAllMultishopAllMultilang($productId, $displayProductReviews);
            $languageGroup = $productStats['languageGroup'];
            $shopIdGroup = $productStats['shopIdGroup'];
        }

        if (!empty($productStats['nb_reviews']) && 'yes' == $displayProductReviews) {
            $reviewsList = []; // Create array with all reviews data

            $reviewsCount = $productStats['nb_reviews'];

            if ($avisverifiesNbReviews < 1) {
                $avisverifiesNbReviews = 10;
            }

            $reviewsMaxPages = floor($reviewsCount / $avisverifiesNbReviews) +
                ($reviewsCount % $avisverifiesNbReviews > 0 ? 1 : 0);

            $allReviews = $this->nrModel->getProductReviews(
                $productId,
                $languageGroup,
                $shopIdGroup,
                0
            );

            $reviewsStats = $this->getReviewsStatsAndPercents($allReviews, $reviewsCount);

            $reviewsDisplayedOnLoadingPage = array_slice($allReviews, 0, $avisverifiesNbReviews);

            foreach ($reviewsDisplayedOnLoadingPage as $review) {
                $myReview = $this->getReviewDetails($review);
                array_push($reviewsList, $myReview);
            }

            $customizedStarColor = $displayConfiguration['customizedStarColor'];
            $nrResponsive = $displayConfiguration['responsiveDisplayGenesis'];
            $hideHelpful = $displayConfiguration['genesisHideHelpful'];
            $hideMedia = $displayConfiguration['genesisHideMedia'];
            $certificateUrl = $displayConfiguration['certificateUrl'];
            $platformUrl = explode('/', $certificateUrl);
            $platform = Tools::substr($platformUrl[2], 4);
            $avHelpfulURL = 'https://www.' . $platform . '/index.php?action=act_api_product_reviews_helpful';
            $urlCgv = 'https://www.' . $platform . '/index.php?page=mod_conditions_utilisation';
            $averageRatePercent = [];
            $averageRatePercent['floor'] = floor($productStats['rate']) - 1;
            $averageRatePercent['decimals'] = ($productStats['rate'] - floor($productStats['rate'])) * 20;
            $logolang = $this->context->language->iso_code;
            $languagesPackAv = ['de', 'en', 'es', 'fr', 'gb', 'it', 'pt'];
            $logolang = (in_array($logolang, $languagesPackAv)) ? $logolang : 'en';
            $useStarFormatImage = Configuration::get('AV_FORMAT_IMAGE', null, null, $this->currentShopId);
            $useImage = false;
            $starsFile = 'avisverifies-stars-image.tpl';
            $oldLang = true;
            $useImage = true;
            if ('1' != $useStarFormatImage) {
                $starsFile = 'avisverifies-stars-font.tpl';
                $oldLang = false;
            }
            $avAjaxTranslation = $this->getAjaxTranslation();
            $avTemplate = Configuration::get('AV_TEMPLATE', null, null, $this->currentShopId);

            if ($avTemplate && '2' == $avTemplate) {
                $ajaxDir = netreviewsModel::tplFileExist('sub/ajax-load-tab-content-design-new.tpl');
                $designTemplateName = 'avisverifies-tab-content-design-new.tpl';
            } else {
                $ajaxDir = netreviewsModel::tplFileExist('ajax-load-tab-content.tpl');
                $designTemplateName = 'avisverifies-tab-content-design-classic.tpl';
            }
            $starsDir = netreviewsModel::tplFileExist('sub/' . $starsFile);
            $designDir = netreviewsModel::tplFileExist('sub/' . $designTemplateName);
            $this->smartyAssign([
                'modules_dir' => _MODULE_DIR_,
                'current_url' => $_SERVER['REQUEST_URI'],
                'av_idwebsite' => $localWebsiteId,
                'avHelpfulURL' => $avHelpfulURL,
                'url_cgv' => $urlCgv,
                'version_ps' => _PS_VERSION_,
                'ajax_dir' => $ajaxDir,
                'stars_dir' => $starsDir,
                'design_dir' => $designDir,
                'use_image' => $useImage,
                'id_shop' => $this->currentShopId,
                'nom_group' => (!empty($this->groupName)) ? $this->groupName : null,
                'reviews' => $reviewsList,
                'count_reviews' => $reviewsCount,
                'average_rate' => round($productStats['rate'], 1),
                'av_rate_percent_int' => (float) $productStats['rate'] * 20,
                'average_rate_percent' => $averageRatePercent,
                'reviews_rate_portion_persontage' => $reviewsStats['reviewsRatePortionPercentage'],
                'url_certificat' => $certificateUrl,
                'reviews_max_pages' => ($reviewsMaxPages) ? (int) $reviewsMaxPages : '',
                'reviews_rate_portion' => $reviewsStats['reviewsRatePortion'],
                'nrResponsive' => $nrResponsive,
                'hidehelpful' => $hideHelpful,
                'hidemedia' => $hideMedia,
                'current_page' => 1,
                'av_ajax_translation' => $avAjaxTranslation,
                'old_lang' => $oldLang, // old version language variable translations
                'logo_lang' => $logolang,
                'customized_star_color' => $customizedStarColor,
                'product_id' => $productId,
                'percentageRecommendingProduct' => $reviewsStats['percentageRecommendingProduct'],
            ]);

            $tpl = 'avisverifies-tab-content';
            $attributeAv = ['id' => 'netreviews_tab', 'class' => 'netreviews_tab'];
            $newTitle = Configuration::get('AV_TABNEWNAME', null, null, $this->currentShopId);
            $titleAsString = $this->l('verified reviews') . '(' . $productStats['nb_reviews'] . ')';
            $title = (!empty($newTitle)) ? $newTitle : $titleAsString;
            $content = $this->displayTemplate($tpl);
            $result = $content;
            if ($tab_version) {
                $result = $this->getExtraContent($attributeAv, $title, $content);
            }
        } else {
            $result = null;
        }

        return $result;
    }

    private function overrideStatsAllMultishopAllMultilang($productId, $displayProductReviews)
    {
        $languageGroup = ($this->allLanguagesReviews) ? null : $this->groupName;
        $shopIdGroup = ($this->allLanguagesReviews) ? null : $this->currentShopId;
        $reviews = $this->nrModel->getProductReviews(
            $productId,
            $languageGroup,
            $shopIdGroup,
            0
        );
        $nbReviews = count($reviews);
        if ($nbReviews < 1 || 'yes' != $displayProductReviews) {
            return null;
        }
        $sumRate = 0;
        foreach ($reviews as $review) {
            $sumRate += $review['rate'];
        }

        return [
            'rate' => $sumRate / $nbReviews,
            'nb_reviews' => $nbReviews,
            'languageGroup' => $languageGroup,
            'shopIdGroup' => $shopIdGroup,
        ];
    }

    private function isProductPage()
    {
        $isProductPage = true;
        if (property_exists($this->context->controller, 'php_self')
            && 'product' != $this->context->controller->php_self) {
            $isProductPage = false;
        }

        return $isProductPage;
    }

    private function getReviewDetails($review)
    {
        $localWebsiteId = Configuration::get(
            'AV_IDWEBSITE' . $this->groupName,
            null,
            null,
            $this->currentShopId
        );
        $localSecureKey = Configuration::get(
            'AV_CLESECRETE' . $this->groupName,
            null,
            null,
            $this->currentShopId
        );
        $shopName = Configuration::get('PS_SHOP_NAME');

        // Create variable for template engine
        $myReview['ref_produit'] = $review['ref_product'];
        $myReview['id_product_av'] = $review['id_product_av'];
        $myReview['sign'] = sha1($localWebsiteId . $review['id_product_av'] . $localSecureKey);
        if (!isset($review['helpful']) && !isset($review['helpless'])) {
            Db::getInstance()->Execute(
                'ALTER TABLE `' . _DB_PREFIX_ . 'av_products_reviews`
                   ADD `helpful` int(7) DEFAULT 0,
                   ADD `helpless` int(7) DEFAULT 0'
            );
        } else {
            $myReview['helpful'] = $review['helpful'];
            $myReview['helpless'] = $review['helpless'];
        }
        $myReview['rate'] = $review['rate'];
        $myReview['rate_percent'] = $review['rate'] * 20;
        $myReview['avis'] = html_entity_decode(urldecode($review['review']));
        // review date
        if ('10' == Tools::strlen($review['horodate'])) {
            $date = new DateTime();
            $date->setTimestamp($review['horodate']);
            $myReview['horodate'] = $date->format($this->dateFormat);
        } else {
            $myReview['horodate'] = date($this->dateFormat, strtotime($review['horodate']));
        }
        // order date
        if (isset($review['horodate_order']) && !empty($review['horodate_order'])) {
            $review['horodate_order'] = str_replace('"', '', $review['horodate_order']);
            $myReview['horodate_order'] = date($this->dateFormat, strtotime($review['horodate_order']));
        } else {
            $myReview['horodate_order'] = $myReview['horodate'];
        }
        // in case imported reviews which have lack of this info
        if (!isset($review['horodate']) || empty($review['horodate'])) {
            $myReview['horodate'] = $myReview['horodate_order'];
        }

        $customerData = $this->getReviewCustomerName($review['customer_name']);
        $myReview['customer_name'] = $customerData['customer_name'];
        $myReview['customer_name_anonymous'] = $customerData['customer_name_anonymous'];

        $myReview['discussion'] = [];
        $jsonDiscussion = json_decode(netreviewsModel::acDecodeBase64($review['discussion']), true);
        $jsonDiscussion = (array) $jsonDiscussion;
        if ($jsonDiscussion) {
            $myReview['discussion'] = $this->getReviewDiscussionDetails(
                $jsonDiscussion,
                $shopName,
                $myReview['customer_name']
            );
        }

        // Media infos
        $myReview['media_content'] = [];
        if (isset($review['media_full'])) {
            $myReview['media_content'] = $this->getReviewMediaContent($review['media_full']);
        }

        return $myReview;
    }

    private function getReviewsStatsAndPercents($reviews, $nbReviews)
    {
        $reviewsRatePortionPercentage = [];
        $reviewsRatePortionKeys = [1, 2, 3, 4, 5];
        $reviewsRatePortion = array_fill_keys($reviewsRatePortionKeys, 0);

        foreach ($reviews as $review) {
            switch ($review['rate']) {
                case '1':
                    ++$reviewsRatePortion[1];
                    break;
                case '2':
                    ++$reviewsRatePortion[2];
                    break;
                case '3':
                    ++$reviewsRatePortion[3];
                    break;
                case '4':
                    ++$reviewsRatePortion[4];
                    break;
                case '5':
                    ++$reviewsRatePortion[5];
                    break;
                default:
                    break;
            }
        }

        foreach ($reviewsRatePortion as $r_rate => $r_num) {
            $reviewsRatePortionPercentage[$r_rate] = round(($r_num / $nbReviews) * 100, 0);
        }
        $reviewsHigherThan3 = $reviewsRatePortion[5] + $reviewsRatePortion[4] + $reviewsRatePortion[3];
        $percentageRecommendingProduct = round(($reviewsHigherThan3 / $nbReviews) * 100, 0);

        return [
            'percentageRecommendingProduct' => $percentageRecommendingProduct,
            'reviewsRatePortionPercentage' => $reviewsRatePortionPercentage,
            'reviewsRatePortion' => $reviewsRatePortion,
        ];
    }

    private function getReviewCustomerName($reviewCustomerName)
    {
        // renverser le nom et le prénom
        $customerName = explode(' ', urldecode($reviewCustomerName));
        $customerName = array_values(array_filter($customerName));
        $customerName = array_diff($customerName, ['.']);
        $isAnonymous = ('Anonymous' == $customerName[1]) ? true : false;
        $customerName = array_reverse($customerName);
        $customerName = implode(' ', $customerName);

        return [
            'customer_name' => $customerName,
            'customer_name_anonymous' => $isAnonymous,
        ];
    }

    private function getReviewDiscussionDetails($jsonDiscussion, $shopName, $customerName)
    {
        $reviewDiscussion = [];
        foreach ($jsonDiscussion as $k_discussion => $eachDiscussion) {
            $eachDiscussion = (array) $eachDiscussion;
            $reviewDiscussion[$k_discussion] = [];
            if ('10' == Tools::strlen($eachDiscussion['horodate'])) {
                $date = new DateTime();
                $date->setTimestamp($eachDiscussion['horodate']);
                $reviewDiscussion[$k_discussion]['horodate'] = $date->format($this->dateFormat);
            } else {
                $reviewDiscussion[$k_discussion]['horodate'] =
                    date($this->dateFormat, strtotime($eachDiscussion['horodate']));
            }
            $reviewDiscussion[$k_discussion]['commentaire'] =
                urldecode($eachDiscussion['commentaire']);

            if ('ecommercant' == $eachDiscussion['origine']) {
                $reviewDiscussion[$k_discussion]['origine'] = $shopName;
            } elseif ('internaute' == $eachDiscussion['origine']) {
                $reviewDiscussion[$k_discussion]['origine'] = $customerName;
            } else {
                $reviewDiscussion[$k_discussion]['origine'] = $this->l('Moderator');
            }
        }

        return $reviewDiscussion;
    }

    private function getReviewMediaContent($reviewMediaFull)
    {
        $reviewMedia = [];
        $reviewImagesResult = (array) netreviewsModel::avJsonDecode(
            html_entity_decode($reviewMediaFull)
        );
        if (!empty($reviewImagesResult)) {
            foreach ($reviewImagesResult as $k_media => $each_media) {
                $reviewMedia[$k_media] = (array) $each_media;
            }
        }

        return $reviewMedia;
    }

    private function getAjaxTranslation()
    {
        $avAjaxTranslation = [];
        $avAjaxTranslation['a'] = $this->l('published');
        $avAjaxTranslation['b'] = $this->l('the');
        $avAjaxTranslation['c'] = $this->l('following an order made on');
        $avAjaxTranslation['d'] = $this->l('Comment from');
        $avAjaxTranslation['e'] = $this->l('Show exchanges');
        $avAjaxTranslation['f'] = $this->l('Hide exchanges');
        $avAjaxTranslation['g'] = $this->l('Did you find this helpful?');
        $avAjaxTranslation['h'] = $this->l('Yes');
        $avAjaxTranslation['i'] = $this->l('No');
        $avAjaxTranslation['j'] = $this->l('More reviews...');

        return $avAjaxTranslation;
    }

    public function checkProductHasReview($idProduct)
    {
        // By default we show the tab - if product api is down, it will not prevent reviews from being displayed
        $productHasReviews = true;
        $script = Configuration::get('AV_RELOADEDSCRIPT' . $this->groupName, null, null, $this->currentShopId);
        $idWebsite = Configuration::get('AV_IDWEBSITE' . $this->groupName, null, null, $this->currentShopId);

        if (strpos($script, 'widgets.rr.skeepers')) {
            $environment = $this->extractEnvironmentFromScript($script);
            $url = "https://cl-ppr.rr.skeepers.$environment/v2/" . $idWebsite . '/' . Base32::encode($idProduct, false) . '.json';
            $urlsFiles = $this->getRatingPPR($url);
            if (empty($urlsFiles)
                || !is_array($urlsFiles)
                || count($urlsFiles) == 0) {
                $productHasReviews = false;
            } else {
                $productHasReviews = true;
            }
        } elseif (strpos($script, 'cl.avis-verifies.com')) {
            $paramsCurl = [
                'query' => 'average',
                'idWebsite' => $idWebsite,
                'product' => $idProduct,
                'plateforme' => Configuration::get('AV_PLATEFORME' . $this->groupName, null, null, $this->currentShopId),
            ];
            $url = 'https://awsapis3.netreviews.eu/product?' . http_build_query($paramsCurl);
            try {
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Host: awsapis3.netreviews.eu',
                ]);
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
                curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                $response = curl_exec($curl);
                $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                $curlErrno = curl_errno($curl);
                $curlError = curl_error($curl);
                if (false === $response) {
                    LogsHandler::addLog(
                        'CURL ProductApi responseCode (' . $responseCode . ') ERROR(' . $curlErrno . '): ' .
                        print_r($curlError, true)
                    );
                }

                $responseContent = json_decode($response, true);
                if (empty($responseContent)) {
                    $productHasReviews = false;
                }
            } catch (Exception $e) {
                LogsHandler::addLog('Error on call to API Product ' . $e->getMessage());
            }
        }

        return $productHasReviews;
    }

    public function hookDisplayProductExtraContent($params)
    {
        $params['product']->use_tabconent[] = true;

        return $this->hookTabcontentNetreviews($params);
    }

    /**
     * Integration of Reloaded Product Widget
     * Average.
     */
    public function displayAverageReloaded($product)
    {
        $displayProductReviews = Configuration::get(
            'AV_DISPLAYPRODREVIEWS' . $this->groupName,
            null,
            null,
            $this->currentShopId
        );
        $avTagAverage = Configuration::get('AV_TAGAVERAGE' . $this->groupName, null, null, $this->currentShopId);
        $avScript = Configuration::get('AV_RELOADEDSCRIPT' . $this->groupName, null, null, $this->currentShopId);
        $avTagAverage = Tools::stripslashes(html_entity_decode($avTagAverage));
        $displayProductReviews = ('yes' == $displayProductReviews);
        $productId = $product->id;

        // Please note TagJS requires following variables, AverageTagJS, ScriptTagJS, AV_DISPLAYPRODREVIEWS
        if (!empty($avTagAverage) && !empty($avScript) && $displayProductReviews) {
            $this->context->smarty->assign('product_id', $productId);
            try {
                // https://www.smarty.net/forums/viewtopic.php?p=94650&sid=81185d158408ef1d636d6a7dcc20c55c
                $avTagAverage = $this->context->smarty->fetch($this->smartyString . $avTagAverage);

                return "\n" . $avTagAverage;
            } catch (Exception $e) {
                return null;
            }
        }

        // Then store / storefront is active for TagJS but has either chosen no to display reviews or
        // is missing configuration variables (Tag or Script or both)
        return null;
    }

    /**
     * Integration of widget product
     * hookExtraRight
     * hookExtraLeft
     * hookExtraNetreviews.
     *
     * AV_LIGHTWIDGET :
     *  1 : simple stars
     *  2 : widget by defaut
     *  3 : widget badge
     */
    public function hookExtraNetreviews()
    {
        $displayConfiguration = $this->getDisplayConfiguration();

        if ($displayConfiguration['displayProductReviews'] !== 'yes') {
            return null;
        }

        $productId = (int) Tools::getValue('id_product');
        $langId = $this->getLangId();
        $product = new Product($productId, false, $langId);

        if ($this->isReloaded()) {
            return $this->displayAverageReloaded($product);
        }

        $reviews = $this->getStatsReviews(
            $productId,
            $displayConfiguration['avSnippetsActive']
        );

        if (isset($reviews['reviewsStats']['nb_reviews']) && $reviews['reviewsStats']['nb_reviews'] > 0) {
            $detailsReviews = $reviews['reviewsDetails'];

            $rate = $reviews['reviewsStats']['rate'];
            $nbReviews = $reviews['reviewsStats']['nb_reviews'];

            $averageRatePercent = [];
            $averageRatePercent['floor'] = floor($rate) - 1;
            $averageRatePercent['decimals'] = ($rate - floor($rate)) * 20;

            $productData = $this->getProductDetails($product, $langId);

            $microdataTemplatePath = _PS_ROOT_DIR_ .
                '/modules/netreviews/views/templates/hook/rich_snippets_microdata.tpl';
            $jsonTemplatePath = _PS_ROOT_DIR_ . '/modules/netreviews/views/templates/hook/rich_snippets_json.tpl';
            $starsDirPath = _PS_ROOT_DIR_ . '/modules/netreviews/views/templates/hook/sub/' .
                $displayConfiguration['starsFile'];

            $this->smartyAssign([
                'modules_dir' => _MODULE_DIR_,
                'base_url' => __PS_BASE_URI__,
                'version_ps' => _PS_VERSION_,
                'stars_dir' => $starsDirPath,
                'av_nb_reviews' => $nbReviews,
                'av_rate' => round($rate, 1),
                'average_rate_percent' => $averageRatePercent,
                'av_rate_percent_int' => ($rate) ? round($rate * 20) : 100,
                'average_rate' => round($rate, 1),
                'product_id' => $productId,
                'product_name' => !empty($productData['name']) ? $productData['name'] : 'product name',
                'product_description' => !empty($productData['description']) ? $productData['description'] : false,
                'product_url' => !empty($productData['productUrlPage']) ? $productData['productUrlPage'] : false,
                'url_image' => !empty($productData['imageUrl']) ? $productData['imageUrl'] : false,
                'product_price' => !empty($productData['price']) ? $productData['price'] : 0,
                'product_currency' => !empty($productData['currency']) ? $productData['currency'] : 'EUR',
                'sku' => !empty($productData['sku']) ? $productData['sku'] : false,
                'mpn' => !empty($productData['mpn']) ? $productData['mpn'] : false,
                'gtin_upc' => !empty($productData['gtinUpc']) ? $productData['gtinUpc'] : false,
                'gtin_ean' => !empty($productData['gtinEan']) ? $productData['gtinEan'] : false,
                'brand_name' => !empty($productData['brandName']) ? $productData['brandName'] : false,
                'widgetlight' => $displayConfiguration['widgetLight'],
                'snippets_complete' => $displayConfiguration['snippetsComplete'],
                'snippets_active' => $displayConfiguration['avSnippetsActive'],
                'snippets_type' => $displayConfiguration['snippetsType'],
                'rich_snippets_microdata' => $microdataTemplatePath,
                'rich_snippets_json' => $jsonTemplatePath,
                'detailsReviews' => !empty($detailsReviews) ? $detailsReviews : false,
                'use_star_format_image' => $displayConfiguration['useStarFormatImage'],
                'customized_star_color' => $displayConfiguration['customizedStarColor'],
            ]);

            $tpl = 'avisverifies-extraright';

            return $this->displayTemplate($tpl);
        }
    }

    private function isReloaded()
    {
        if ($this->storeKeysConfigured()
            && Configuration::get('AV_RELOADED' . $this->groupName, null, null, $this->currentShopId)
        ) {
            return true;
        }

        return false;
    }

    private function isAllMultishopMultilangReviews()
    {
        $isAllMultishopMultilangReviews = false;
        if ($this->allMultishopsReviews && $this->allLanguagesReviews) {
            $isAllMultishopMultilangReviews = true;
        }

        return $isAllMultishopMultilangReviews;
    }

    private function getStatsReviews($productId, $activeRS)
    {
        if (!$this->isAllMultishopMultilangReviews()) {
            $reviews = $this->getDetailedStats($productId, $activeRS);
        } else {
            $languageGroup = ($this->allLanguagesReviews) ? null : $this->groupName;
            $shopIdGroup = ($this->allMultishopsReviews) ? null : $this->currentShopId;
            $reviews = $this->nrModel->getProductReviews(
                $productId,
                $languageGroup,
                $shopIdGroup,
                0,
                1,
                'horodate_DESC',
                0,
                false
            );
            $nbReviews = count($reviews);
            if ($nbReviews < 1) {
                return null;
            }
            $sumRate = 0;
            foreach ($reviews as $review) {
                $sumRate += $review['rate'];
            }
            $reviews['rate'] = $sumRate / $nbReviews;
        }

        return $reviews;
    }

    private function getDetailedStats($productId, $activeRS)
    {
        $reviews = $this->nrModel->getStatsProduct($productId, $this->groupName, $this->currentShopId);

        $nbReviews = !empty($reviews['nb_reviews']) ? $reviews['nb_reviews'] : 0;
        if ($nbReviews < 1) {
            return null;
        }

        $detailsReviews = [];
        if (isset($activeRS)) {
            $detailsReviews = $this->nrModel->getProductReviews($productId, $this->groupName, $this->currentShopId);
            if (!empty($detailsReviews)) {
                foreach ($detailsReviews as $k => $review) {
                    $detailsReviews[$k]['review'] = urldecode($review['review']);
                    $detailsReviews[$k]['customer_name'] = urldecode($review['customer_name']);
                }
            }
        }

        return [
            'reviewsStats' => $reviews,
            'reviewsDetails' => $detailsReviews,
        ];
    }

    private function getProductDetails($product, $langId)
    {
        $productDescription = strip_tags($product->description_short);
        $urlPage = netreviewsModel::getUrlProduct($product->id, $langId, null, $this->currentShopId);
        $id_image = $product->getCover($product->id);
        $idImage = (isset($id_image['id_image']) && !empty($id_image['id_image'])) ? $id_image['id_image'] : '';
        $urlImage = netreviewsModel::getUrlProduct($product->id, $langId, $idImage);
        $sku = $product->reference;
        $mpn = $product->supplier_reference;
        $gtinUpc = (isset($product->upc) && !empty($product->upc)) ? $product->upc : '';
        $gtinEan = $product->ean13;
        $brandName = '';
        $manufacturer = new Manufacturer($product->id_manufacturer, (int) $this->idLang);
        $brandName = $manufacturer->name;
        $productPrice = $product->getPrice(true, null, 2);
        $currency = $this->context->currency->iso_code;

        if (is_array($product->name)) {
            if (isset($product->name[$langId])) {
                $productName = $product->name[$langId];
            } else {
                $productName = array_values($product->name)[0];
            }
        } else {
            $productName = $product->name;
        }

        return [
            'description' => $productDescription,
            'productUrlPage' => $urlPage,
            'imageUrl' => $urlImage,
            'sku' => $sku,
            'mpn' => $mpn,
            'gtinUpc' => $gtinUpc,
            'gtinEan' => $gtinEan,
            'brandName' => $brandName,
            'price' => $productPrice,
            'currency' => $currency,
            'name' => $productName,
        ];
    }

    private function getDisplayConfiguration()
    {
        $widgetLight = Configuration::get('AV_LIGHTWIDGET', null, null, $this->currentShopId);
        $avSnippetsActive = Configuration::get('AV_DISPLAYSNIPPETSITE', null, null, $this->currentShopId);
        $snippetsType = Configuration::get('AV_SNIPPETSITETYPE', null, null, $this->currentShopId);
        $websiteSnippets = Configuration::get('AV_RICHSNIPPETSWEBSITE', null, null, $this->currentShopId);
        if ('1' == $websiteSnippets) {
            $snippetsComplete = '0';
        } else {
            $snippetsComplete = '1';
        }

        $displayProductReviews = Configuration::get(
            'AV_DISPLAYPRODREVIEWS' . $this->groupName,
            null,
            null,
            $this->currentShopId
        );

        $customizedStarColor = (Configuration::get(
            'AV_STARCOLOR',
            null,
            null,
            $this->currentShopId
        )) ? Configuration::get(
            'AV_STARCOLOR',
            null,
            null,
            $this->currentShopId
        ) : 'FFCD00'; // default #FFCD00

        $useStarFormatImage = Configuration::get('AV_FORMAT_IMAGE', null, null, $this->currentShopId);

        $starsFile = 'avisverifies-stars-image.tpl';
        if ('1' != $useStarFormatImage) {
            $starsFile = 'avisverifies-stars-font.tpl';
        }

        $avReviewsTagjs = Configuration::get('AV_TAGREVIEWS' . $this->groupName, null, null, $this->currentShopId);
        $avScript = Configuration::get('AV_RELOADEDSCRIPT' . $this->groupName, null, null, $this->currentShopId);

        $showStarsHome = Configuration::get('AV_STARSHOMESHOW', null, null, $this->currentShopId);

        $displayReviewsCategoryPages = Configuration::get('AV_DISPLAYSTARPLIST', null, null, $this->currentShopId);

        $genesisTemplate = Configuration::get('AV_TEMPLATE', null, null, $this->currentShopId);

        $genesisNbReviews = Configuration::get('AV_NBOFREVIEWS', null, null, $this->currentShopId);

        $productInfoGtin = Configuration::get('AV_PRODUCTUNIGINFO', null, null, $this->currentShopId);

        $homepageStars = Configuration::get('AV_STARSHOMESHOW', null, null, $this->currentShopId);

        $extraOption = Configuration::get('AV_EXTRA_OPTION', null, null, $this->currentShopId);

        $responsiveDisplay = Configuration::get('AV_NRESPONSIVE', null, null, $this->currentShopId);

        $hideHelpful = Configuration::get('AV_HELPFULHIDE', null, null, $this->currentShopId);

        $hideMedia = Configuration::get('AV_MEDIAHIDE', null, null, $this->currentShopId);

        $renameTab = Configuration::get('AV_TABNEWNAME', null, null, $this->currentShopId);

        $tabShow = Configuration::get('AV_TABSHOW', null, null, $this->currentShopId);

        $avCertificateUrl = Configuration::get(
            'AV_URLCERTIFICAT' . $this->groupName,
            null,
            null,
            $this->currentShopId
        );

        return [
            'widgetLight' => $widgetLight,
            'avSnippetsActive' => $avSnippetsActive,
            'snippetsType' => $snippetsType,
            'websiteSnippets' => $websiteSnippets,
            'snippetsComplete' => $snippetsComplete,
            'displayProductReviews' => $displayProductReviews,
            'customizedStarColor' => $customizedStarColor,
            'useStarFormatImage' => $useStarFormatImage,
            'starsFile' => $starsFile,
            'avReviewsTag' => $avReviewsTagjs,
            'avTagJsScript' => $avScript,
            'showStarsHomepage' => $showStarsHome,
            'displayReviewsCategoryPages' => $displayReviewsCategoryPages,
            'genesisTemplate' => $genesisTemplate,
            'genesisNbReviews' => $genesisNbReviews,
            'productInfoGtin' => $productInfoGtin,
            'homepageStars' => $homepageStars,
            'extraOption' => $extraOption,
            'responsiveDisplayGenesis' => $responsiveDisplay,
            'genesisHideHelpful' => $hideHelpful,
            'genesisHideMedia' => $hideMedia,
            'renameTab' => $renameTab,
            'tabShow' => $tabShow,
            'certificateUrl' => $avCertificateUrl,
        ];
    }

    public function hookDisplayProductAdditionalInfo($params)
    {
        return $this->executeExtraNetreviewsHook();
    }

    private function executeExtraNetreviewsHook()
    {
        $avExtraOption = Configuration::get('AV_EXTRA_OPTION', null, null, $this->currentShopId);
        if ('2' == $avExtraOption) {
            return $this->hookExtraNetreviews();
        }

        return null;
    }

    /**
     * Allow to integrate our Product Widget JS script
     */
    public function hookDisplayFooter($params)
    {
        HookHandler::hookTriggers(HookHandler::DISPLAY_FOOTER_HOOK);
        if ($this->isReloaded() && (property_exists($this->context->controller, 'php_self')
                && in_array($this->context->controller->php_self, ['index', 'product', 'category']))
        ) {
            return $this->getReloadedScript();
        }

        return null;
    }

    /**
     * Return TagJS or Product Widget Script.
     */
    public function getReloadedScript()
    {
        $displayProductReviews = Configuration::get(
            'AV_DISPLAYPRODREVIEWS' . $this->groupName,
            null,
            null,
            $this->currentShopId
        );
        $avScript = Configuration::get('AV_RELOADEDSCRIPT' . $this->groupName, null, null, $this->currentShopId);
        $avScript = Tools::stripslashes(html_entity_decode($avScript));
        $displayProductReviews = ('yes' == $displayProductReviews);
        // Please note TagJS or Product Widget requires following variables, ScriptTagJS, AV_DISPLAYPRODREVIEWS
        if (!empty($avScript) && $displayProductReviews) {
            return "\n" . $avScript;
        }

        // Then store / storefront is active for TagJS or Product Widget but has either chosen no to display reviews or
        // is missing configuration variables (Tag or Script or both)
        return null;
    }

    /**
     * @return int
     */
    private function getLangId()
    {
        return (isset($this->context->language->id) && !empty($this->context->language->id)) ?
            $this->context->language->id : 1;
    }

    private function getStatsForCategoryPages($productId)
    {
        $statsProduct = (!isset($this->stats_product) || empty($this->stats_product)) ?
            $this->nrModel->getStatsProduct($productId, $this->groupName, $this->currentShopId) :
            $this->stats_product;

        if ($this->allMultishopsReviews || $this->allLanguagesReviews) { // override rate & nb_reviews
            $languageGroup = ($this->allLanguagesReviews) ? null : $this->groupName;
            $shopIdGroup = ($this->allMultishopsReviews) ? null : $this->currentShopId;
            $reviews = $this->nrModel->getProductReviews(
                $productId,
                $languageGroup,
                $shopIdGroup,
                0,
                1,
                'horodate_DESC',
                0,
                false
            );
            $numReviews = count($reviews);
            if ($numReviews < 1) {
                return null;
            }
            $sumRate = 0;
            foreach ($reviews as $review) {
                $sumRate += $review['rate'];
            }
            $statsProduct['rate'] = $sumRate / $numReviews;
            $statsProduct['nb_reviews'] = $numReviews;
        }

        if (!isset($statsProduct['nb_reviews']) || 0 == $statsProduct['nb_reviews']) {
            return null;
        }

        return $statsProduct;
    }

    public function hookActionValidateOrder($params)
    {
        $websiteId = Configuration::get(
            'AV_IDWEBSITE' . $this->groupName,
            null,
            null,
            $this->currentShopId
        );
        $secretKey = Configuration::get(
            'AV_CLESECRETE' . $this->groupName,
            null,
            null,
            $this->currentShopId
        );
        $clientId = Configuration::get(
            'AV_CLIENTID' . $this->groupName,
            null,
            null,
            $this->currentShopId
        );
        $secretApi = Configuration::get(
            'AV_SECRETAPI' . $this->groupName,
            null,
            null,
            $this->currentShopId
        );

        if (!empty($websiteId) && (!empty($secretKey) || (!empty($clientId) && !empty($secretApi)))) {
            $order = $params['order'];
            $orderId = $order->id;

            if (!empty($order->id_shop)) {
                $this->nrModel->shopId = $order->id_shop;
            }

            if (isset($orderId) && !empty($orderId)) {
                $this->nrModel->orderId = (int) $orderId;
                $this->nrModel->isoLang = pSQL(Language::getIsoById($this->idLang));
                $this->nrModel->saveOrderToRequest();
            }
        }

        return null;
    }

    /**
     * This code is added for having possiblities of double check
     * if not all orders are registered.
     */
    public function hookActionOrderStatusPostUpdate($params)
    {
        $this->setAvOrderContext($params['id_order']);

        $avReloaded = Configuration::get('AV_RELOADED' . $this->groupName, null, null, $this->currentShopId);

        HookHandler::hookTriggers(HookHandler::ACTION_ORDER_STATUS_POST_UPDATE_HOOK);

        if (1 == $avReloaded) {
            $statusList = Configuration::get(
                'AV_ORDERSTATESCHOOSEN' . $this->groupName,
                null,
                null,
                $this->currentShopId
            );

            $statusListArray = explode(';', $statusList);
            if (in_array($params['newOrderStatus']->id, $statusListArray)) {
                $this->sendOrders();
            }
        }

        $websiteId = Configuration::get('AV_IDWEBSITE' . $this->groupName, null, null, $this->currentShopId);
        $secretKey = Configuration::get('AV_CLESECRETE' . $this->groupName, null, null, $this->currentShopId);

        if (!empty($websiteId) && !empty($secretKey)) {
            $orderId = (int) $params['id_order'];
            if (isset($params['cart']->id_shop) && !empty($params['cart']->id_shop)) {
                $this->nrModel->shopId = $params['cart']->id_shop;
            }
            if (isset($orderId) && !empty($orderId)) {
                $this->nrModel->orderId = (int) $orderId;
                $this->nrModel->isoLang = pSQL(Language::getIsoById($this->idLang));
                $this->nrModel->saveOrderToRequest();
            }
        }

        return null;
    }

    /**
     * When order state is updated from BO we need to update the groupName based on order id lang
     *
     * @param $orderId
     *
     * @return void
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    protected function setAvOrderContext($orderId)
    {
        if ('checked' == Configuration::get('AV_MULTILINGUE', null, null, $this->currentShopId)) {
            $order = new Order($orderId);
            $this->idLang = $order->id_lang;
            $this->isoLang = pSQL(Language::getIsoById($this->idLang));
            $this->groupName = $this->getIdConfigurationGroup($this->isoLang);
        }
    }

    /**
     * Function for "HOOK post status order updated"
     *
     * @return void
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    protected function sendOrders()
    {
        $ordersList = $this->getOrdersToSend();
        if (empty($ordersList)) {
            $ordersList = [];
        }
        $nbOrders = count($ordersList);
        $finalOrdersList = [];

        if (!empty($ordersList)) {
            foreach ($ordersList as $order) {
                $formattedOrder = $this->getOrderData($order);
                if (!$formattedOrder) {
                    continue;
                }
                $finalOrdersList[] = $formattedOrder;
            }
            if (!empty($finalOrdersList)) {
                $this->sendOrdersToApiConnector($finalOrdersList, $nbOrders);
            }
        }

        $this->purgeAvOrdersTable();
    }

    protected function getOrdersToSend()
    {
        $db = Db::getInstance();
        $query = $this->nrModel->getQueryToGetOrders($this->currentShopId, $this->groupName, 'reloaded');

        return $db->ExecuteS($query);
    }

    public static function getPurchaseEventType($allowedProducts, $avActivateCollect)
    {
        $valuesToAllowProductsInOrder = ['yes', '1'];
        $valuesActivateCollect = ['yes', '1'];

        if (in_array($allowedProducts, $valuesToAllowProductsInOrder, true)) {
            if (in_array($avActivateCollect, $valuesActivateCollect, true)) {
                return 'BRAND_AND_PRODUCT';
            } else {
                return 'PRODUCT_ONLY';
            }
        } elseif (in_array($avActivateCollect, $valuesActivateCollect, true)) {
            return 'PURCHASE_ONLY';
        } else {
            return 'NOTHING';
        }
    }

    protected function getOrderData($order)
    {
        if ($this->nrModel->isOrderWithoutConsent($this->currentShopId, $this->groupName, $order['id_order'])) {
            return false;
        }

        $extensions = Configuration::get(
            'AV_FORBIDDEN_EMAIL' . $this->groupName,
            null,
            null,
            $this->currentShopId
        );
        $extensions = str_replace(['["', '"', ']'], '', $extensions);
        $forbiddenEmailsExtensions = explode(',', $extensions);

        $orderCustomer = new Customer($order['id_customer']);
        $customerEmailExtension = explode('@', $orderCustomer->email);

        if (in_array($customerEmailExtension[1], $forbiddenEmailsExtensions)) {
            LogsHandler::addLog(
                'Flag order - forbidden email extensions for Order n°' . $order['id_order']
                . ' Email:' . $orderCustomer->email
            );
            $this->nrModel->flagOrder(0, $order['id_order']);

            return false;
        }

        $allowedProducts = Configuration::get(
            'AV_GETPRODREVIEWS' . $this->groupName,
            null,
            null,
            $this->currentShopId
        );

        $avActivateCollect = Configuration::get(
            'AV_ACTIVATE_COLLECT' . $this->groupName,
            null,
            null,
            $this->currentShopId
        );

        $purchaseEventType = self::getPurchaseEventType($allowedProducts, $avActivateCollect);

        $delay = Configuration::get('AV_DELAY' . $this->groupName, null, null, $this->currentShopId);
        $delayProduct = Configuration::get('AV_DELAY_PRODUIT' . $this->groupName, null, null, $this->currentShopId);
        $websiteId = Configuration::get('AV_IDWEBSITE' . $this->groupName, null, null, $this->currentShopId);

        $arrayOrder = [
            'purchase_date' => $order['date_order'],
            'purchase_reference' => $order['id_order'],
            'price' => is_numeric($order['total_paid']) ? (float) $order['total_paid'] : $order['total_paid'],
            'consumer' => [
                'hide_personal_data' => false,
                'first_name' => $orderCustomer->firstname,
                'last_name' => $orderCustomer->lastname,
                'email' => $orderCustomer->email,
            ],
            'solicitation_parameters' => [
                'delay' => is_numeric($delay) ? (int) $delay : 0,
                'delay_product' => is_numeric($delayProduct) ? (int) $delayProduct : 0,
                'purchase_event_type' => $purchaseEventType,
            ],
            'products' => [],
            'sales_channel' => [
                'channel' => 'online',
                'website_id' => $websiteId,
            ],
        ];

        $valuesToAllowProductsInOrder = ['yes', '1'];
        if (!empty($allowedProducts) && in_array($allowedProducts, $valuesToAllowProductsInOrder)) {
            $arrayOrder['products'] = $this->getOrderProducts($order);
        }

        return $arrayOrder;
    }

    protected function getOrderProducts($order)
    {
        // 15 - Permet de rendre optionel la demande d'avis pour les id produit contenu dans ce tableau.
        $productException = [];
        $arrayProducts = [];
        $i = 0;

        $orderDetails = new Order($order['id_order']);
        $productDelay = $this->getProductDelay($order['id_shop']);
        $shopName = Configuration::get('PS_SHOP_NAME');

        $productsOrder = $orderDetails->getProducts();

        foreach ($productsOrder as $productElement) {
            if (!in_array($productElement['product_id'], $productException)) {
                $productData = $this->nrModel->getProductData(
                    $productElement,
                    $order['id_lang'],
                    $order['id_shop']
                );

                $productRefArray = $this->getProductReferences($productElement['product_id'], $productData);
                $product = [
                    'product_ref' => $productRefArray,
                    'name' => $productElement['product_name'],
                    'brand' => (isset($productData['brandName']) && !empty($productData['brandName'])) ?
                        $productData['brandName'] :
                        $shopName,
                    'not_received' => false,
                    'price' => $productElement['product_price'],
                    'product_url' => netreviewsModel::getUrlProduct(
                        $productElement['product_id'],
                        $order['id_lang'],
                        null,
                        $order['id_shop']
                    ),
                    'image_url' => netreviewsModel::getUrlProduct(
                        $productElement['product_id'],
                        $order['id_lang'],
                        $productData['titleImageId']
                    ),
                    'delay' => $productDelay,
                    'category' => $productData['category'],
                ];

                if ($this->addProduct($order['id_shop'], $i)) {
                    array_push($arrayProducts, $product);
                }
                unset($product);
            }
            ++$i;
        }

        return $arrayProducts;
    }

    protected function addProduct($idShop, $nbProductAlreadyAdded)
    {
        $addProduct = true;

        $maxProducts = (int) Configuration::get(
            'AV_NBOPRODUCTS' . $this->groupName,
            null,
            null,
            $idShop
        );

        if (isset($maxProducts) && !empty($maxProducts) && $nbProductAlreadyAdded >= $maxProducts) {
            $addProduct = false;
        }

        return $addProduct;
    }

    protected function getProductReferences($productId, $productData)
    {
        $productRefArray = [
            'reference' => $productId,
        ];
        if (!empty($productData['ean13'])) {
            $productRefArray = array_merge($productRefArray, ['ean' => $productData['ean13']]);
        }
        if (!empty($productData['sku'])) {
            $productRefArray = array_merge($productRefArray, ['sku' => $productData['sku']]);
        }
        if (!empty($productData['upc'])) {
            $productRefArray = array_merge($productRefArray, ['upc' => $productData['upc']]);
        }
        if (!empty($productData['mpn'])) {
            $productRefArray = array_merge($productRefArray, ['mpn' => $productData['mpn']]);
        }

        return $productRefArray;
    }

    protected function getProductDelay($idShop)
    {
        $productDelay = Configuration::get(
            'AV_DELAY_PRODUIT' . $this->groupName,
            null,
            null,
            $idShop
        );

        return (is_null($productDelay) ? 0 : is_numeric($productDelay)) ? (int) $productDelay : 0;
    }

    protected function purgeAvOrdersTable()
    {
        Db::getinstance()->Execute(
            'DELETE FROM ' . _DB_PREFIX_ . 'av_orders WHERE
            horodate_now < DATE_SUB(NOW(), INTERVAL 6 MONTH)'
        );
    }

    protected function sendOrdersToApiConnector($orders, $nbOrders)
    {
        $avWebsiteId = Configuration::get('AV_IDWEBSITE' . $this->groupName, null, null, $this->currentShopId);
        $apiCaller = new AccessToken($this->currentShopId, $this->groupName);
        $token = $apiCaller->getDecodedAccessToken();
        if (!empty($token)) {
            $apiConnectorResponse = $this->callToApiConnector(
                $avWebsiteId,
                $token,
                $orders
            );

            $successHttpCodes = [202, 400];
            if (in_array($apiConnectorResponse['code'], $successHttpCodes)) {
                foreach ($orders as $element) {
                    $this->flagOrder($element['purchase_reference'], $apiConnectorResponse['code']);
                }
            } else {
                LogsHandler::addLog('Error code : ' . $apiConnectorResponse['code']);
            }
        }
    }

    protected function flagOrder($orderRef, $responseHttpCode)
    {
        $this->nrModel->flagOrder(0, $orderRef);
        if ($responseHttpCode === 400) {
            LogsHandler::addLog('Error 400 for order ref ' . $orderRef);
        }
    }

    protected function callToApiConnector($websiteId, $token, $orders)
    {
        $urlApiConnector = Configuration::get('AV_PUSH_ORDERS_ENDPOINT' . $this->groupName, null, null, $this->currentShopId);
        $clientId = Configuration::get('AV_CLIENTID' . $this->groupName, null, null, $this->currentShopId);
        try {
            $headers = [
                'Content-Type: application/json',
                'client-id: ' . $clientId,
                'Authorization: Bearer ' . $token,
            ];
            $orders = json_encode($orders, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $option = [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $orders,
            ];
            $curlCapsule = new CurlCapsule($urlApiConnector, $option, $headers);
            $curlCapsule->sendRequest();
            $response = $curlCapsule->getResponse();
            $ordersJsonData = json_decode($orders, true);
            foreach ($ordersJsonData as $element) {
                $ordersReference[] = $element['purchase_reference'];
            }
            if (false === $response) {
                $curlError = $curlCapsule->getErrors();
                LogsHandler::addLog(
                    'CURL callPostApiConnector - idWebsite ' . $websiteId . ' - idShop ' .
                    $this->currentShopId . ' - responseCode (' . $response['code'] . ') for order references (' . print_r(
                        $ordersReference,
                        true
                    ) . ') ERROR(' . $curlError . ')'
                );
            }

            return $response;
        } catch (Exception $e) {
            LogsHandler::addLog(
                'Error in function callApiPostPurchaseEventsBulkInsert - idWebsite ' . $websiteId .
                ' - idShop ' . $this->currentShopId . ' - responseCode (' . $e->getMessage() . ')'
            );
        }
    }

    protected function getRatingPPR($url)
    {
        $responseContent = false;
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $data = curl_exec($ch);
            if (!empty($data)) {
                $responseContent = json_decode($data, true);
            }
        } catch (Exception $e) {
            LogsHandler::addLog('Error on getting rating file : ' . $e->getMessage(), 'CURL Error');
        }

        return $responseContent;
    }

    protected function extractEnvironmentFromScript($script)
    {
        $environment = 'io';
        $prefix = 'widgets.rr.skeepers.';
        $suffix = '/product';

        $startPos = strpos($script, $prefix) + strlen($prefix);
        $endPos = strpos($script, $suffix, $startPos);

        if ($startPos !== false && $endPos !== false) {
            $environment = substr($script, $startPos, $endPos - $startPos);
        }

        return $environment;
    }
}
