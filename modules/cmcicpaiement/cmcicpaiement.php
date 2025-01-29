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
class CMCICPaiement extends PaymentModule
{
    protected $server = 'https://p.monetico-services.com/test/';
    protected $cmcic_board_link = 'https://www.cmcicpaiement.fr/fr/identification/identification.html';

    /** @var bool */
    public $bootstrap;

    /** @var bool */
    public $isPsVersionUpperThan16;

    /** @var bool */
    public $isPsVersionLowerThan16;

    /** @var bool */
    public $isPsVersion17;

    public function __construct()
    {
        $this->name = 'cmcicpaiement';
        $this->version = '4.2.2';
        $this->bootstrap = true;
        $this->author = 'PrestaShop';
        $this->module_key = 'f36c83d7812c3ebcd0de45ea33d32699';
        $this->tab = 'payments_gateways';

        parent::__construct();

        $this->isPsVersionUpperThan16 = version_compare(_PS_VERSION_, '1.6', '>');
        $this->isPsVersionLowerThan16 = version_compare(_PS_VERSION_, '1.6', '<=');
        $this->isPsVersion17 = version_compare(_PS_VERSION_, '1.7', '>');
        $this->displayName = $this->l('CM-CIC P@iement');
        $this->description = '';
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        $this->updatePaymentPortalUrl();

        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'cmcic_notification_event` (' .
            '`event_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,' .
            '`cart_reference` INT(10) UNSIGNED DEFAULT NULL,' .
            '`code-retour` VARCHAR(15) DEFAULT NULL,' .
            '`created_at` DATETIME DEFAULT NULL,' .
            'PRIMARY KEY (`event_id`)' .
            ') DEFAULT CHARSET=utf8;';

        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        return parent::install()
            && (true === $this->isPsVersion17) ? $this->registerHook('paymentOptions') : $this->registerHook('payment')
            && $this->registerHook('displayOrderConfirmation');
    }

    public function postProcess()
    {
        $return = array(
            'code' => 0,
            'error' => 0,
            'step_name' => '',
        );

        // Step 1
        if (Tools::isSubmit('submitBankInformations')) {
            $return['step_name'] = 'Bank informations';

            if (!preg_match('#^[a-zA-Z0-9]{40}$#', trim(Tools::getValue('CMCIC_KEY')))
            || !preg_match('#^[0-9]{7}$#', trim(Tools::getValue('CMCIC_TPE')))
            || !preg_match('#^[a-zA-Z0-9_-]+$#', trim(Tools::getValue('CMCIC_COMPANY_CODE')))) {
                $return['error'] = -1;
            }

            Configuration::updateValue('CMCIC_KEY', trim(Tools::getValue('CMCIC_KEY')));
            Configuration::updateValue('CMCIC_TPE', trim(Tools::getValue('CMCIC_TPE')));
            Configuration::updateValue('CMCIC_COMPANY_CODE', trim(Tools::getValue('CMCIC_COMPANY_CODE')));
            Configuration::updateValue('CMCIC_ENVIRONMENT', (int) Tools::getValue('CMCIC_ENVIRONMENT'));
            $this->updatePaymentPortalUrl();
            $return['code'] = 1;

            return $return;
        }

        // Step 3
        if (Tools::isSubmit('submitCMCICOptions')) {
            $email_list = trim(Tools::getValue('CMCIC_EMAIL_NOTIFICATION'));
            $return['step_name'] = 'CM-CIC options';

            if (!empty($email_list)) {
                if (Tools::substr($email_list, -1) == ',') {
                    $email_list = Tools::substr($email_list, 0, -1);
                }

                $email_list = str_replace(' ', '', $email_list);
                $email_array = explode(',', $email_list);
                foreach ($email_array as $email) {
                    if (!Validate::isEmail($email)) {
                        $return['error'] = -3;
                    }
                }
                if ($return['error'] != -3) {
                    Configuration::updateValue('CMCIC_EMAIL_NOTIFICATION', $email_list);
                }
            }
            Configuration::updateValue('CMCIC_ERROR_BEHAVIOR', (int) Tools::getValue('CMCIC_ERROR_BEHAVIOR'));

            if (Tools::getValue('CMCIC_LOGO_HOME')) {
                $this->registerHook('displayHome');
            } elseif ($this->isRegisteredInHook('displayHome')) {
                $this->unregisterHook('displayHome');
            }

            if (Tools::getValue('CMCIC_LOGO_RIGHT_COLUMN')) {
                $this->registerHook('displayRightColumn');
            } elseif ($this->isRegisteredInHook('displayRightColumn')) {
                $this->unregisterHook('displayRightColumn');
            }

            if (Tools::getValue('CMCIC_LOGO_LEFT_COLUMN')) {
                $this->registerHook('displayLeftColumn');
            } elseif ($this->isRegisteredInHook('displayLeftColumn')) {
                $this->unregisterHook('displayLeftColumn');
            }

            $return['code'] = 3;

            return $return;
        }

        return $return;
    }

    public function getContent()
    {
        $return_post_process = $this->postProcess();
        $is_submit = $return_post_process['code'];
        $this->loadAsset();

        $this->context->smarty->assign(array(
            'is_submit' => $is_submit,
            'form_uri' => $this->context->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name,
            'module_active' => (int) $this->active,
            'key' => pSQL(Configuration::get('CMCIC_KEY')),
            'tpe' => pSQL(Configuration::get('CMCIC_TPE')),
            'company_code' => pSQL(Configuration::get('CMCIC_COMPANY_CODE')),
            'environment' => (int) Configuration::get('CMCIC_ENVIRONMENT'),
            'url_ok' => $this->context->link->getPageLink('order-confirmation'),
            'url_ko' => $this->context->link->getPageLink('order'),
            'url_validation' => $this->context->link->getModuleLink($this->name, 'validation'),
            'behavior' => (int) Configuration::get('CMCIC_ERROR_BEHAVIOR'),
            'notification' => pSQL(Configuration::get('CMCIC_EMAIL_NOTIFICATION')),
            'module_name' => $this->name,
            'module_version' => $this->version,
            'cmcic_board_link' => $this->cmcic_board_link,
            'lang_select' => $this->getLang(),
            'module_display' => $this->displayName,
            'debug_mode' => (int) _PS_MODE_DEV_,
            'multishop' => (int) Shop::isFeatureActive(),
            'ps_version' => $this->isPsVersionUpperThan16,
            'guide_link' => 'docs/CM-CIC_documentation_utilisateur_FR.pdf',
            'tracking_url' => '?utm_source=back-office&utm_medium=module&utm_campaign=back-office-' . $this->context->language->iso_code . '&utm_content=' . $this->name,
            'tracking_url_install' => '?utm_source=modulePS&utm_medium=installation&utm_campaign=cmcic',
            'cmcic_config_error' => (int) $return_post_process['error'],
            'step_name' => pSQL($return_post_process['step_name']),
            'logo_home' => (int) $this->isRegisteredInHook('displayHome'),
            'logo_left_column' => (int) $this->isRegisteredInHook('displayLeftColumn'),
            'logo_right_column' => (int) $this->isRegisteredInHook('displayRightColumn'),
        ));

        return $this->display(__FILE__, 'views/templates/admin/configuration.tpl');
    }

    public function includeConf()
    {
        if (false === Validate::isLoadedObject($this->context->cart)) {
            $this->context->cart = new Cart((int) $this->context->cookie->__get('id_cart'));
        }

        define('CMCIC_VERSION', '3.0');
        define(
            'CMCIC_URLOK',
            $this->context->link->getPageLink(
                'order-confirmation',
                null,
                null,
                array(
                    'id_cart' => (int) $this->context->cart->id,
                    'id_module' => (int) $this->id,
                    'key' => $this->context->customer->secure_key,
                )
            ));
        define('CMCIC_URLKO', $this->context->link->getPageLink('order'));
        define('CMCIC_SERVER', Configuration::get('CMCIC_SERVER'));
        define('CMCIC_KEY', Configuration::get('CMCIC_KEY'));
        define('CMCIC_COMPANY_CODE', Configuration::get('CMCIC_COMPANY_CODE'));
        define('CMCIC_ERROR_BEHAVIOR', Configuration::get('CMCIC_ERROR_BEHAVIOR'));
        define('CMCIC_EMAIL_NOTIFICATION', Configuration::get('CMCIC_EMAIL_NOTIFICATION'));
        define('CMCIC_TPE', Configuration::get('CMCIC_TPE'));

        require_once dirname(__FILE__) . '/CmCicTpe.inc.php';
    }

    /**
     * @return string
     */
    public function hookDisplayOrderConfirmation(array $params)
    {
        /** @var Order $order */
        $order = (isset($params['objOrder'])) ? $params['objOrder'] : $params['order'];

        if ($order->module !== $this->name) {
            return '';
        }

        $this->context->smarty->assign(array(
            'status' => $order->hasBeenPaid() ? 'ok' : 'failed',
            'id_order' => $order->id,
            'shop_name' => $this->context->shop->name,
            'contact' => $this->context->link->getPageLink('contact'),
        ));

        return $this->display(__FILE__, 'views/templates/hook/hookorderconfirmation.tpl');
    }

    public function hookDisplayHome()
    {
        $this->context->smarty->assign('img_url', $this->getPathUri() . 'views/img/logo_home_small.png');

        return $this->display(__FILE__, 'views/templates/hook/hookhome.tpl');
    }

    public function hookDisplayRightColumn()
    {
        $this->context->smarty->assign('img_url', $this->getPathUri() . 'views/img/logo_column.png');

        return $this->display(__FILE__, 'views/templates/hook/hookcolumn.tpl');
    }

    public function hookDisplayLeftColumn()
    {
        $this->context->smarty->assign('img_url', $this->getPathUri() . 'views/img/logo_column.png');

        return $this->display(__FILE__, 'views/templates/hook/hookcolumn.tpl');
    }

    /**
     * Used only in PrestaShop 1.5 to display link for translate module.
     *
     * @return array
     */
    private function getLang()
    {
        $languages = array();

        foreach (Language::getLanguages() as $language) {
            preg_match('/(.*)\((.*)\)/m', $language['name'], $matches);
            $languages[$language['iso_code']] = array(
                'title' => isset($matches[1]) ? $matches[1] : $language['name'],
                'subtitle' => isset($matches[2]) ? $matches[2] : '',
            );
        }

        return $languages;
    }

    /**
     * @return array
     */
    private function getFormData()
    {
        $this->includeConf();
        $data = array();

        // CMCIC server only understands "EN" for english language
        $data['cmcic'] = new CmCicTpe(Tools::strtoupper($this->context->language->iso_code));
        if (Tools::strtoupper($this->context->language->iso_code) === 'GB') {
            $data['cmcic'] = new CmCicTpe('EN');
        }

        $data['hmac'] = new CmCicHmac($data['cmcic']);
        $data['cmcic_date'] = date('d/m/Y:H:i:s');
        $data['cmcic_amount'] = $this->context->cart->getOrderTotal();
        $data['cmcic_alias'] = '';
        $data['cmcic_currency'] = Tools::strtoupper($this->context->currency->iso_code);
        $data['cmcic_reference'] = $this->context->cart->id . rand(10, 99);
        $data['cmcic_email'] = $this->context->customer->email;
        $data['cmcic_textelibre'] = '[' . $this->context->customer->id . '] ' . $this->context->customer->email;

        $shipping = new Address($this->context->cart->id_address_delivery);
        $billing = new Address($this->context->cart->id_address_invoice);
        $customer = new Customer($this->context->cart->id_customer);
        $countryship = new Country($shipping->id_country);
        $countrybill = new Country($billing->id_country);

        // Contextual information related to the order : JSON, UTF-8, hexadecimally encoded
        // cart details, shipping and delivery addresses, technical context
        $rawContexteCommand = [
            'billing' => [
                'firstName' => $this->truncate($billing->firstname, 45),
                'lastName' => $this->truncate($billing->lastname, 45),
                'addressLine1' => $this->truncate($billing->address1, 50),
                'city' => $this->truncate($billing->city, 50),
                'postalCode' => $this->truncate($billing->postcode, 10),
                'country' => $countrybill->iso_code,
            ],
            'shipping' => [
                'firstName' => $this->truncate($shipping->firstname, 45),
                'lastName' => $this->truncate($shipping->lastname, 45),
                'addressLine1' => $this->truncate($shipping->address1, 50),
                'city' => $this->truncate($shipping->city, 50),
                'postalCode' => $this->truncate($shipping->postcode, 10),
                'country' => $countryship->iso_code,
                'email' => $this->truncate($customer->email, 100),
                'matchBillingAddress' => $this->context->cart->id_address_invoice === $this->context->cart->id_address_delivery,
            ],
            'client' => [
                'email' => $this->truncate($customer->email, 100),
            ],
        ];

        if (!empty($customer->birthday) && Validate::isDate($customer->birthday)) {
            $rawContexteCommand['client']['birthdate'] = $customer->birthday;
        }

        $rawContexteCommand = json_encode($rawContexteCommand);
        $utf8ContexteCommande = utf8_encode($rawContexteCommand);
        $sContexteCommande = base64_encode($utf8ContexteCommande);

        $tpe = $data['cmcic']->s_numero;
        $date = $data['cmcic_date'];
        $mail = $data['cmcic_email'];
        $montant = $this->context->cart->getOrderTotal();
        $currency = Tools::strtoupper($this->context->currency->iso_code);
        $reference = $data['cmcic_reference'];
        $societe = $data['cmcic']->s_code_societe;
        $libre = '[' . $customer->id . '] ' . $customer->email;
        $nok = $data['cmcic']->s_url_ko;
        $ok = $data['cmcic']->s_url_ok;
        $version = $data['cmcic']->s_version;
        $data['cmcic_contexte'] = $sContexteCommande;
        $lgue = $data['cmcic']->s_langue;

        $phase1go_fields = implode(
            '*',
            [
                "TPE={$tpe}",
                "contexte_commande=$sContexteCommande",
                "date=$date",
                "lgue=$lgue",
                "mail=$mail",
                "montant=$montant{$currency}",
                "reference=$reference",
                "societe={$societe}",
                "texte-libre=$libre",
                "url_retour=$nok",
                "url_retour_err=$nok",
                "url_retour_ok=$ok",
                "version={$version}",
            ]
        );

        $data['hmac_cipher'] = $data['hmac']->computeHmac($phase1go_fields);

        return $data;
    }

    /**
     * @param string $str
     * @param int $limit
     *
     * @return false|string the extracted part of string or false on failure
     */
    private function truncate($str, $limit)
    {
        if (empty($str)) {
            return $str;
        }

        return substr($str, 0, $limit);
    }

    /**
     * @return bool
     */
    public function checkCurrency(Cart $cart)
    {
        $currency_order = new Currency($cart->id_currency);
        $currencies_module = $this->getCurrency($cart->id_currency);
        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency']) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function hookPaymentOptions(array $params)
    {
        if (!$this->active) {
            return array();
        }

        if (!$this->checkCurrency($params['cart'])) {
            return array();
        }

        return array($this->getExternalPaymentOption());
    }

    /**
     * @return string
     */
    public function hookPayment()
    {
        $data = $this->getFormData();
        $this->context->smarty->assign('cmcic', $data['cmcic']);
        $this->context->smarty->assign('cmcic_date', $data['cmcic_date']);
        $this->context->smarty->assign('cmcic_montant', $data['cmcic_amount'] . $data['cmcic_currency']);
        $this->context->smarty->assign('cmcic_reference', $data['cmcic_reference']);
        $this->context->smarty->assign('cmcic_textelibre', $data['cmcic_textelibre']);
        $this->context->smarty->assign('cmcic_email', $data['cmcic_email']);
        $this->context->smarty->assign('hmac', $data['hmac_cipher']);
        $this->context->smarty->assign('cmcicpaiement_form', 'cmcicpaiement_form1');
        $this->context->smarty->assign('cmcic_picture', 'views/img/cmcicpaiement_paiement.png');
        $this->context->smarty->assign('cmcic_text', $this->l('Pay by credit card with CM-CIC paiement'));
        $this->context->smarty->assign('contexte_commande', $data['cmcic_contexte']);

        return $this->display(__FILE__, 'views/templates/hook/hookpayment.tpl');
    }

    /**
     * @return PrestaShop\PrestaShop\Core\Payment\PaymentOption
     */
    private function getExternalPaymentOption()
    {
        $data = $this->getFormData();
        $externalOption = new PrestaShop\PrestaShop\Core\Payment\PaymentOption();

        $externalOption->setCallToActionText($this->l('Pay by credit card with CM-CIC paiement'))
            ->setAction($data['cmcic']->s_url_paiement)
            ->setInputs([
                'version' => [
                    'name' => 'version', 'type' => 'hidden', 'value' => $data['cmcic']->s_version,
                ],
                'TPE' => [
                    'name' => 'TPE', 'type' => 'hidden', 'value' => $data['cmcic']->s_numero,
                ],
                'date' => [
                    'name' => 'date', 'type' => 'hidden', 'value' => $data['cmcic_date'],
                ],
                'montant' => [
                    'name' => 'montant', 'type' => 'hidden', 'value' => $data['cmcic_amount'] . $data['cmcic_currency'],
                ],
                'reference' => [
                    'name' => 'reference', 'type' => 'hidden', 'value' => $data['cmcic_reference'],
                ],
                'MAC' => [
                    'name' => 'MAC', 'type' => 'hidden', 'value' => $data['hmac_cipher'],
                ],
                'url_retour' => [
                    'name' => 'url_retour', 'type' => 'hidden', 'value' => $data['cmcic']->s_url_ko,
                ],
                'url_retour_ok' => [
                    'name' => 'url_retour_ok', 'type' => 'hidden', 'value' => $data['cmcic']->s_url_ok,
                ],
                'url_retour_err' => [
                    'name' => 'url_retour_err', 'type' => 'hidden', 'value' => $data['cmcic']->s_url_ko,
                ],
                'lgue' => [
                    'name' => 'lgue', 'type' => 'hidden', 'value' => $data['cmcic']->s_langue,
                ],
                'societe' => [
                    'name' => 'societe', 'type' => 'hidden', 'value' => $data['cmcic']->s_code_societe,
                ],
                'texte-libre' => [
                    'name' => 'texte-libre', 'type' => 'hidden', 'value' => $data['cmcic_textelibre'],
                ],
                'mail' => [
                    'name' => 'mail', 'type' => 'hidden', 'value' => $data['cmcic_email'],
                ],
                'contexte_commande' => [
                    'name' => 'contexte_commande', 'type' => 'hidden', 'value' => $data['cmcic_contexte'],
                ],
            ])
            ->setLogo(Media::getMediaPath(_PS_MODULE_DIR_ . $this->name . '/views/img/cmcicpaiement_paiement.png'));

        return $externalOption;
    }

    /**
     * Loads asset resources
     */
    private function loadAsset()
    {
        $this->context->controller->addCSS(
            array(
                $this->getPathUri() . 'views/css/font-awesome.min.css',
                $this->getPathUri() . 'views/css/bootstrap-select.min.css',
                $this->getPathUri() . 'views/css/bootstrap-responsive.min.css',
                $this->getPathUri() . 'views/css/' . $this->name . '.css',
            ),
            'all'
        );
        $this->context->controller->addJS(array(
            $this->getPathUri() . 'views/js/bootstrap-select.min.js',
            $this->getPathUri() . 'views/js/' . $this->name . '.js',
        ));

        if (true === $this->isPsVersionLowerThan16) {
            $this->context->controller->addCSS(
                array(
                    $this->getPathUri() . 'views/css/bootstrap.min.css',
                    $this->getPathUri() . 'views/css/bootstrap.extend.css',
                    $this->getPathUri() . 'views/css/font-awesome.min.css',
                ),
                'all'
            );
            $this->context->controller->addJS($this->getPathUri() . 'views/js/bootstrap.min.js');
        }
    }

    /**
     * @param string $order_message
     */
    public function sendErrorEmail($order_message)
    {
        $cmcic_email_notification = Configuration::get('CMCIC_EMAIL_NOTIFICATION');

        $email_array = explode(',', $cmcic_email_notification);
        foreach ($email_array as $email) {
            if (Validate::isEmail($email)) {
                Mail::Send(
                    (int) Configuration::get('PS_LANG_DEFAULT'),
                    'notification',
                    $this->l('CM-CIC notification'),
                    array('message' => 'CM-CIC payment error' . str_ireplace('', 'n', $order_message)),
                    $email,
                    null,
                    null,
                    null,
                    null,
                    null,
                    dirname(__FILE__) . '/mails/'
                );
            }
        }
    }

    /**
     * @param string $cart_reference
     * @param string $codeRetour
     *
     * @return bool
     */
    public function logNotificationRequest($cart_reference, $codeRetour)
    {
        return Db::getInstance()->insert('cmcic_notification_event', array(
            'cart_reference' => pSQL($cart_reference),
            'code-retour' => pSQL($codeRetour),
            'created_at' => date('Y-m-d H:i:s'),
        ));
    }

    /**
     * If there already is a payment for this cart reference we return TRUE, else we return false
     *
     * @param string $cart_reference
     * @param string $codeRetour
     *
     * @return bool
     */
    public function isDuplicate($cart_reference, $codeRetour)
    {
        return (bool) Db::getInstance()->getValue('
            SELECT 1
            FROM `' . _DB_PREFIX_ . 'cmcic_notification_event`
            WHERE `cart_reference` = "' . pSQL($cart_reference) . '"
            AND `code-retour` = "' . pSQL($codeRetour) . '"
        ');
    }

    /**
     * computeHmacSource
     *
     * @param array $source
     * @param object $oEpt
     *
     * @return string
     */
    public function computeHmacSource($source, $oEpt)
    {
        $anomalies = null;
        if (array_key_exists('version', $source)) {
            $anomalies .= $source['version'] == $oEpt->sVersion ? ':version' : null;
        }

        // sole field to exclude from the MAC computation
        if (array_key_exists('MAC', $source)) {
            unset($source['MAC']);
        } else {
            $anomalies .= ':MAC';
        }

        if ($anomalies != null) {
            return 'anomaly_detected' . $anomalies;
        }

        // order by key is mandatory
        ksort($source);
        // map entries to "key=value" to match the target format
        array_walk($source, function (&$a, $b) {
            $a = "$b=$a";
        });
        // join all entries using asterisk as separator
        return implode('*', $source);
    }

    /**
     * Update the payment portal URL depending of the current environment
     *
     * @return bool
     */
    public function updatePaymentPortalUrl()
    {
        $currentEnvIsProduction = (int) Configuration::get('CMCIC_ENVIRONMENT');

        if ($currentEnvIsProduction === 1) {
            return Configuration::updateValue('CMCIC_SERVER', str_replace('test/', '', $this->server));
        }

        return Configuration::updateValue('CMCIC_SERVER', $this->server);
    }
}
