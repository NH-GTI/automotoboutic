
<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from SARL Ether Création
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the SARL Ether Création is strictly forbidden.
 * In order to obtain a license, please contact us: contact@ethercreation.com
 * ...........................................................................
 * INFORMATION SUR LA LICENCE D'UTILISATION
 *
 * L'utilisation de ce fichier source est soumise a une licence commerciale
 * concedee par la societe Ether Création
 * Toute utilisation, reproduction, modification ou distribution du present
 * fichier source sans contrat de licence ecrit de la part de la SARL Ether Création est
 * expressement interdite.
 * Pour obtenir une licence, veuillez contacter la SARL Ether Création a l'adresse: contact@ethercreation.com
 * ...........................................................................
 *  @package    ecordersandstock
 *  @author     Alec Page
 *  @copyright  Copyright (c) 2010-2018 S.A.R.L Ether Création (http://www.ethercreation.com)
 *  @license    Commercial license
 */
require_once dirname(__FILE__).'/info.class.php';
use ecordersandstock\Info;

class ecordersandstock_funcs extends Module
{
    /**
     * follow link timeout
     */
    CONST FOLLOWLINK_TIMEOUT = 4;
    CONST FOLLOWLINK_LOG = false;

    /**
     * log or not
     */
    CONST YES_LOG = true;

    /**
     * protocol we use
     */
    public $protocol = 'http';

    /**
     * htaccess login
     */
    public $htaccessUser = 'automotoboutic';
    public $htaccessPwd = 'tv9M79Xf';

    public $order_button = true;

    public function __construct(Context $context = null)
    {
        $this->name = 'ecordersandstock';
        $this->tab = 'shipping_logistics';
        $this->version = '1.0.0';
        $this->author = 'Alec PAGE';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Export Commandes et Mise à jour Stock');
        $this->description = $this->l('Export Commandes et Mise à jour Stock');

        $this->confirmUninstall = $this->l('Êtes-vous sûr de vouloir désinstaller le module ?');

        $this->protocol = (((Configuration::get('PS_SSL_ENABLED') == 1) &&
            (Configuration::get('PS_SSL_ENABLED_EVERYWHERE') == 1)) ? 'https://' : 'http://' );

        if (is_null($context)) {
            Context::getContext();
        }
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        self::setConfig('ecoas_token', md5(time()));
        self::setConfig('ecoas_mailorders', '');

         //installation de l'onglet ecordersandstock
        $parent_name = 'AdminParentModules';
        if (version_compare(_PS_VERSION_, '1.6.0.0', '>=')) {
            $parent_name = 'AdminParentModules';
        }
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $parent_name = 'AdminParentModulesSf';
        }
        $id_parent_module_tab = Tab::getIdFromClassName($parent_name);
        $this->installModuleTab(
            'AdminEcOrdersAndStock',
            'Ec Order And Stock',
            (int) $id_parent_module_tab
        );

        $registered_os = $this->regiserOrderStates(array(
        // état transmis
            array(
                'color' => '#0000cc',
                'name' => self::createMultiLangField('Transmis Navision'),
                'ecoas_os_id' => 'tr',
            ),
            array(
                'color' => '#ff8f46',
                'name' => self::createMultiLangField('En instance de production'),
                'ecoas_os_id' => 'pr',
            ),
/*
        // état forcer transmission
            array(
                'color' => '#606060',
                'name' => self::createMultiLangField('Transmettre vers GCPlus'),
                'ecoas_os_id' => 'ftr',
            ),
        // état contrat conforme GCPlus
            array(
                'color' => '#009900',
                'name' => self::createMultiLangField('Contrat accepté conforme par GCPlus'),
                'ecoas_os_id' => 'cfm',
            ),
        // état erreur GCPlus
            array(
                'color' => '#ff0000',
                'name' => self::createMultiLangField('Erreur contrat GCPlus, voir messages'),
                'ecoas_os_id' => 'err',
            ),
*/
        ));
        foreach ($registered_os as $ecoas_os_id => $id_os) {
            self::setConfig('ecoas_os_' . $ecoas_os_id, $id_os);
        }

        return parent::install() &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('actionOrderStatusPostUpdate');
    }

    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');

        // Uninstall Tabs
        $moduleTabs = Tab::getCollectionFromModule($this->name);
        if (!empty($moduleTabs)) {
            foreach ($moduleTabs as $moduleTab) {
                $moduleTab->delete();
            }
        }

        return parent::uninstall();
    }

    public function _getContent()
    {
        $ecoas_token = self::getConfigValue('ecoas_token');
        $baseDir = $this->protocol . Tools::getShopDomain() . $this->_path;
        $cronLink = $baseDir . 'cron.php?ecoas_token=' . $ecoas_token;
        $ordersLinkAMB = $baseDir . 'ajax.php?majsel=26&ecoas_token=' . $ecoas_token;
        $ordersLinkMarketplace = $baseDir . 'ajax.php?majsel=15&ecoas_token=' . $ecoas_token;
        $tasks = array(
            array(
                'id_cpanel' => '1',
                'name' => 'Mise à jour des stocks',
                'link' => $cronLink,
                'prefix' => 'ecoas',
                'suffix' => 'none',
                'position' => '1',
                'active' => '1',
            )
        );

        $config = self::getConfig();
        //$id_lang = Configuration::get('PS_LANG_DEFAULT');

        return array(
            'cronLink' => $cronLink,
            'ordersLinkAMB' => $ordersLinkAMB,
            'ordersLinkMarketplace' => $ordersLinkMarketplace,
            'ecoas_token' => $ecoas_token,
            'baseDir' => $baseDir,
            'tasks' => $tasks,
            'ecoas_mailorders' => (isset($config['ecoas_mailorders']) ? $config['ecoas_mailorders'] : ''),
        );
    }

    private function installModuleTab($tabClass, $tabName, $idTabParent)
    {
        if (Db::getInstance()->getValue(
            'SELECT COUNT(*)
            FROM ' . _DB_PREFIX_ . 'tab
            WHERE class_name LIKE "' . pSQL($tabClass) . '"')) {
            return false;
        }

        $tab = new Tab();

        foreach (Language::getLanguages(false) as $lang) {
            $tab->name[(int) $lang['id_lang']] = $tabName;
        }

        $tab->class_name = $tabClass;
        $tab->module = $this->name;
        $tab->id_parent = (int) $idTabParent;
        $tab->active = 1;
        if (!$tab->save()) {
            return false;
        }

        return true;
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookDisplayBackOfficeHeader($params)
    {
        if (Tools::getValue('module_name') === $this->name && Tools::getValue('controller') === 'AdminModules') {
            //$this->context->controller->addJS('https://code.jquery.com/jquery-1.12.4.min.js');
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            //$this->context->controller->addCSS($this->_path . 'views/css/back.css');
            return;
        }
/*
        $id_order = Tools::getValue('id_order', 0);
        if ($this->order_button && ('AdminOrders' === Context::getContext()->controller->controller_name) && $id_order) {
            $this->context->controller->addJS(($this->_path) . 'views/js/ecoas_reprisebutton.js');
            $this->smarty->assign(
                array(
                    'ecoas_token' => self::getConfigValue('ecoas_token'),
                    'ecoas_id_order' => $id_order
                )
            );

            return $this->display(__FILE__, 'views/templates/admin/ecgcpreprise.tpl');
        }
*/
        return;
    }

    /**
     * get "sent" state
     */
    public static function getRepriseState($id_order)
    {
        if (!self::isOrderKnown($id_order)) {
            return 2;
        }

        return (int) Db::getInstance()->getValue(
            'SELECT 1
            FROM ' . _DB_PREFIX_ . 'ecoas_orders
            WHERE id_order = ' . (int) $id_order . '
            AND file_name IS NULL'
        );
    }

    /**
     * get "sent" state, sending text
     */
    public function getRepriseStateLang($id_order)
    {
        switch (self::getRepriseState($id_order)) {
            case 2:
                return $this->l('Commande non envoyée');
            case 1:
                return $this->l('Commande à envoyer');
            case 0:
                return $this->l('Commande envoyée');
        }
    }

    /**
     * toggle "sent" state
     */
    public static function toggleRepriseState($id_order)
    {
        switch (self::getRepriseState($id_order)) {
            case 2:
                Db::getInstance()->insert(
                    'ecoas_orders',
                    array(
                        'id_order' => (int) $id_order
                    )
                );
                return 1;
            case 1:
                Db::getInstance()->delete(
                    'ecoas_orders',
                    'id_order = ' . (int) $id_order
                );
                return 2;
            case 0:
                Db::getInstance()->execute(
                    'UPDATE ' . _DB_PREFIX_ . 'ecoas_orders
                    SET file_name = NULL
                    WHERE id_order = ' . (int) $id_order
                );
                return 1;
        }
    }

    /**
     * toggle "sent" state, sending text
     */
    public function toggleRepriseStateLang($id_order)
    {
        switch (self::toggleRepriseState($id_order)) {
            case 2:
                return $this->l('Commande non envoyée');
            case 1:
                return $this->l('Commande à envoyer');
            case 0:
                return $this->l('Commande envoyée');
        }
    }

    public static function isOrderKnown($id_order)
    {
        return (bool) Db::getInstance()->getValue(
            'SELECT COUNT(id)
            FROM ' . _DB_PREFIX_ . 'ecoas_orders
            WHERE id_order = ' . (int) $id_order
        );
    }

    /**
     * if order not known, register in the table
     */
    public function hookActionOrderStatusPostUpdate($params)
    {
        if (self::isOrderKnown((int) $params['id_order'])) {
            return;
        }

        Db::getInstance()->insert(
            'ecoas_orders',
            array(
                'id_order' => (int) $params['id_order']
            )
        );

        return;
    }

    public static function saveParameters($config)
    {
        $valid_conf_keys = array(
            'ecoas_mailorders',
        );
        $numeric_conf_keys = array();
        $multiple_conf_keys = array();
        $array_conf_keys = array();
        $ok = true;

        //keys with multiple values
        if (!empty($multiple_conf_keys)) {
            foreach ($multiple_conf_keys as $key) {
                $liste = array();
                $matches = array();
                preg_match_all('/(\?|\&)' . $key . '\=([^\&]+)/', $config, $matches);
                if (empty($matches[2])) {
                    continue;
                }
                foreach ($matches[2] as $val) {
                    $liste[] = urldecode($val);
                }
                if (empty($liste)) {
                    continue;
                }
                self::setConfig($key, Tools::jsonEncode($liste));
            }
        }

        //simple keys
        $configuration = array();
        parse_str($config, $configuration);
        foreach ($configuration as $key => $value) {
            //exclude multiple keys and unknown keys
            if (in_array($key, $multiple_conf_keys) ||
                in_array($key, $array_conf_keys) ||
                !in_array($key, $valid_conf_keys) ||
                'protected' === $value) {
                continue;
            }

            //verify numeric values
            if (in_array($key, $numeric_conf_keys)) {
                $value_corr = str_replace(',', '.', $value);
                if (is_numeric($value_corr)) {
                    $value = $value_corr;
                } else {
                    $ok = false;
                    $value = 0;
                }
            }
            self::setConfig($key, $value);
        }

        //array keys
        if (!empty($array_conf_keys)) {
            foreach ($array_conf_keys as $key) {
                //exclude multiple keys and unknown keys
                if (!isset($configuration[$key])) {
                    continue;
                }

                //verify array values
                $value = is_array($configuration[$key]) ? $configuration[$key] : array();
                self::setConfig($key, Tools::jsonEncode($value));
            }
        }

        if (!$ok) {
            throw new Exception('Numeric value expected');
        }

        return true;
    }

    public function sendOrderDelayed($id_order)
    {
        return Tools::file_get_contents(
            $this->protocol
            . Tools::getShopDomain()
            . __PS_BASE_URI__ . 'modules/'
            . $this->name . '/'
            . 'ecoas_delay_order.php'
            . '?id_order=' . $id_order
            . '&delay=5'
            . '&ecoas_token=' . self::getConfigValue('ecoas_token')
        );
    }

    /**
     * add order to file
     */
    public function sendOrders($isContremarque = false)
    {
        // get some parameters from configuration
        $logger = self::logStart('sendorders');
        $output_dir = dirname(__FILE__).'/files/export/';
        $output_name = 'commandes_' . date('YmdHis') . '.txt';
        $config = self::getConfig();
        $ok_orders = true;
        $msg_not_ok = '';
	$contremarque = [];
	//get list of new orders from table
        $list_orders = Db::getInstance()->executeS(
            'SELECT eo.id_order AS id_order, o.payment AS payment
            FROM ' . _DB_PREFIX_ . 'ecoas_orders AS eo
	    LEFT JOIN ' . _DB_PREFIX_ . 'orders AS o ON o.id_order = eo.id_order
	    WHERE o.current_state <> 6 AND eo.file_name IS NULL'
	);
	
        foreach ($list_orders as $l_order) {
            $order = new Order($l_order['id_order']);
            $details = $order->getOrderDetailList();
            foreach ($details as $detail) {
                if(in_array(substr($detail['product_reference'], 0, 3), ["AMB"])){
                    $contremarque[] = $l_order['id_order'];
                }
            }
        }
        //add each order to file and update table
	foreach ($list_orders as $order) {
		//var_dump(in_array($order['id_order'], $contremarque));
            if($isContremarque && in_array($order['id_order'], $contremarque)) // If we asked for order with contremarque product && the order ID is in the contremarque array
                list($flag, $msg) = $this->sendOrder($order['id_order'], $output_dir . $output_name);
            else if(!$isContremarque && !in_array($order['id_order'], $contremarque))
                list($flag, $msg) = $this->sendOrder($order['id_order'], $output_dir . $output_name);
            //else
              //  list($flag, $msg) = [true, ''];
            
            $ok_orders &= $flag;
            $msg_not_ok .= $msg;
	}
        // send file by email
        if ($ok_orders && !empty($config['ecoas_mailorders'])) {
            //send by mail
            $recipients = explode(';', $config['ecoas_mailorders']);
            foreach ($recipients as $recipient) {
                $target = trim($recipient);
                if (!Validate::isEmail($target)) {
                    continue;
                }
                Mail::Send(
                    (int) Configuration::get('PS_LANG_DEFAULT'),                // langue
                    'commandes',                                                    // nom du fichier template SANS L'EXTENSION
                    Mail::l(
                        'Commandes à intégrer',                                 // sujet à traduire dans les langues du module
                        (int) Configuration::get('PS_LANG_DEFAULT')
                    ),
                    array(                                                      // templatevars personnelles
                        '{date}' => date('d/m/Y à H:i:s'),
                        '{expe}' => $this->name,
						'{document}' => 'https://www.automotoboutic.com/modules/ecordersandstock/files/export/'.$output_name
                    ),
                    $target,                                                    // destinataire mail
                    null,                                                       // destinataire nom
                    Configuration::get('PS_SHOP_EMAIL'),                        // expéditeur
                    Configuration::get('PS_SHOP_NAME'),                         // expéditeur nom
                    array(                                                      // fichier joint
                        'content' => Tools::file_get_contents($output_dir . $output_name),
                        'mime' => 'application/octet-stream',
                        'name' => $output_name
                    ),
                    null,                                                       // Choix SMTP, non traité par le coeur < PS 1.4.6.1
                    dirname(__FILE__) . '/mails/'                                 // répertoire des mails templates
                );
            }
        }

        if (!$ok_orders) {
            $message = 'Les commandes au ' . date('d/m/Y') .' ne seront pas envoyées : ' . $msg_not_ok;
            self::logError($logger, $message);
            return Tools::jsonEncode(array('mc' => 31, 'suxs' => 'nosuccess'));
        } elseif (!$config['ecoas_mailorders']) {
            $message = 'Les commandes au ' . date('d/m/Y') .' ne seront pas envoyées : configurez le module pour un envoi par mail';
            self::logError($logger, $message);
            return Tools::jsonEncode(array('mc' => 31, 'suxs' => 'nosuccess'));
        } else {
            $message = 'Les commandes au ' . date('d/m/Y') .' ont été envoyées par mail à ' . $config['ecoas_mailorders'];
            self::logInfo($logger, $message);
            return Tools::jsonEncode(array('mc' => 30, 'suxs' => 'success'));
        }
    }

    /**
     * add order to file
     */
    public function sendOrder($id_order, $filename)
    {
        // get some parameters from configuration
        $logger = self::logStart('sendorder');
        $config = self::getConfig();

        $order = new Order($id_order);
        $customer = new Customer($order->id_customer);
        $address_default = new Address(Address::getFirstCustomerAddressId($order->id_customer));
        $db = \Db::getInstance();
#         $sql = 'SELECT code_bc
#            FROM nh_customer_update
#            WHERE id_customer = ' . $order->id_customer
#	    ;

#        $glnCode = $db->executeS($sql)['0']['code_bc'];

#	if($glnCode == '' || $glnCode == null){
            $sql = 'SELECT pro_gln as code_bc
            FROM ps_customer
            WHERE id_customer = ' . $order->id_customer;

            $glnCode = $db->executeS($sql)['0']['code_bc'];
 #       }
        $address_delivery = new Address($order->id_address_delivery);
        $address_invoice = new Address($order->id_address_invoice);
        //$numcom = Tools::substr('00000000' . $id_order, -8);
        $dates = Db::getInstance()->getRow(
            'SELECT date_min AS min, date_max AS max
            FROM '._DB_PREFIX_.'delivery_date_history
            WHERE id_order = ' . (int)$id_order
        );
        if (!$dates) {
            $dates = array(
                'min' => $order->date_add,
                'max' => $order->date_add
            );
        }
        $currency = Currency::getDefaultCurrency();
        $default_phone = '0000000000';

        $ok_order = true;
        $warning = false;
        $msg_not_ok = '';
	$msg_warning = '';
	$marketplaces = ['CDISCOUNT.FR', 'AMAZON.FR', 'AMAZON.BE', 'AMAZON.IT', 'JUMPL'];

	$country = new Country($address_delivery->id_country, Configuration::get('PS_LANG_DEFAULT'));
	$countryCode = $country->iso_code;
/*        if ($country->iso_code !== 'FR') {
            $ok_order = false;
            $msg_not_ok .= 'Le pays ' . $country->name . ' n\'est pas autorisé.';
        }
*/
	//$id_order_presta = $id_order;
        $id_command = $id_order;
        $lines = array();
        $tapis_sur_mesure = 0;
        // headline
        $line1 = 'A                  ????????';
        $line1 = self::addField($line1, $id_command, 1, 20, ' ', true);
        $line1 = self::addField($line1, preg_replace('/^([0-9]{2})([0-9]{2})-([0-9]{2})-([0-9]{2}).*/', '$2$3$4', $dates['min']), 22);
        $line1 = self::addField($line1, $id_command, 36, 20, ' ', true);
        $line1 = self::addField($line1, 'DATE DE LIVRAISON :', 87);
        $line1 = self::addField($line1, preg_replace('/^([0-9]{2})([0-9]{2})-([0-9]{2})-([0-9]{2}).*/', '$2$3$4', $dates['min']), 107);
        $line1 = self::addField($line1, 'HEURE MINI :', 114);
        $line1 = self::addField($line1, 'HEURE MAXI :', 132);
        $line1 = self::addField($line1, $currency->iso_code, 165);
	$line1 = self::addField($line1, $id_command, 169, 20, ' ', true);
		if($countryCode != "FR" && !in_array($order->payment, $marketplaces)){             
			$line1 = self::addField($line1, "3012816900110", 190); // Other countries than France         
		} 
		else{             
	 	    switch ($order->payment) {                 
			case 'CDISCOUNT.FR':                     
				$line1 = self::addField($line1, "3012810000020", 190);                     
				break;                  
			case 'AMAZON.FR':                     
				$line1 = self::addField($line1, "3012810000030", 190);                     
				break;                  
			case 'AMAZON.BE':                     
				$line1 = self::addField($line1, "3012810000084", 190);                     
				break;                  
			case 'AMAZON.IT':                     
				$line1 = self::addField($line1, "3012810000050", 190);                     
				break;                  
			case 'JUMPL':                     
				$line1 = self::addField($line1, "3012810000040", 190);                     
				break;                  
			case 'EBAY.FR':                     
				$line1 = self::addField($line1, "3012810000041", 190);                     
				break;                  
			default:                     
				$line1 = self::addField($line1, (isset($customer->pro_gln) ? $customer->pro_gln : '3012816900105'), 190);                     
				break;             
			}
       		}
        $line1 = self::addField($line1, (isset($glnCode) && !is_null($glnCode) ? $glnCode : '3012816900105'), 190);
        $lines[] = $line1;

        //address line1
        $line2 = str_repeat(' ', 307);
        $line2 = self::addField($line2, 'BC', 0);
        $line2 = self::addField($line2, $address_default->firstname . ' ' . $address_default->lastname , 2, 30);
        //$line2 = self::addField($line2, trim($address_default->address1), 37, 20);
        $line2 = self::addField($line2, trim($address_default->address1).' '.trim(string: $address_default->address2), 37, 60);
        //$line2 = self::addField($line2, trim($address_default->address2), 57, 20);
        $line2 = self::addField($line2, $address_default->postcode, 97, 9);
        $line2 = self::addField($line2, $address_default->city, 106, 34);
        $country = new Country($address_default->id_country, Configuration::get('PS_LANG_DEFAULT'));
        $line2 = self::addField($line2, $country->iso_code, 141, 3);
        $phone = self::cleanPhone($address_default->phone);
        $phone = $phone == $default_phone ? false : $phone;
        $phone_mobile = self::cleanPhone($address_default->phone_mobile);
        $phone_mobile = $phone_mobile == $default_phone ? false : $phone_mobile;
        $line2 = self::addField($line2, ($phone_mobile ? $phone_mobile : ($phone ? $phone : '')), 144);
        $line2 = self::addField($line2, $customer->email, 194);
        //one more info : but what is it
        $lines[] = $line2;

        //address line2
        $line3 = str_repeat(' ', 307);
        $line3 = self::addField($line3, 'BL', 0);
        $line3 = self::addField($line3, $address_delivery->firstname . ' ' . $address_delivery->lastname , 2, 30);
        //$line3 = self::addField($line3, trim($address_delivery->address1), 37, 20);
        $line3 = self::addField($line3, trim($address_delivery->address1).' '.trim($address_delivery->address2), 37, 60);
        //$line3 = self::addField($line3, trim($address_delivery->address2), 57, 20);
        $line3 = self::addField($line3, $address_delivery->postcode, 97, 9);
        $line3 = self::addField($line3, $address_delivery->city, 106, 34);
        $country = new Country($address_delivery->id_country, Configuration::get('PS_LANG_DEFAULT'));
        $line3 = self::addField($line3, $country->iso_code, 141, 3);
        $phone = self::cleanPhone($address_delivery->phone);
        $phone = $phone == $default_phone ? false : $phone;
        $phone_mobile = self::cleanPhone($address_delivery->phone_mobile);
        $phone_mobile = $phone_mobile == $default_phone ? false : $phone_mobile;
        $line3 = self::addField($line3, ($phone_mobile ? $phone_mobile : ($phone ? $phone : '')), 144);
        //one more info : but what is it
        $lines[] = $line3;

        //address line3
        $line4 = str_repeat(' ', 307);
        $line4 = self::addField($line4, 'BF', 0);
        $line4 = self::addField($line4, $address_invoice->firstname . ' ' . $address_invoice->lastname , 2, 30);
        //$line4 = self::addField($line4, trim($address_invoice->address1), 37, 20);
        $line4 = self::addField($line4, trim($address_invoice->address1).' '.trim($address_invoice->address2), 37, 60);
        //$line4 = self::addField($line4, trim($address_invoice->address2), 57, 20);
        $line4 = self::addField($line4, $address_invoice->postcode, 97, 9);
        $line4 = self::addField($line4, $address_invoice->city, 106, 34);
        $country = new Country($address_invoice->id_country, Configuration::get('PS_LANG_DEFAULT'));
        $line4 = self::addField($line4, $country->iso_code, 141, 3);
        $phone = self::cleanPhone($address_invoice->phone);
        $phone = $phone == $default_phone ? false : $phone;
        $phone_mobile = self::cleanPhone($address_invoice->phone_mobile);
        $phone_mobile = $phone_mobile == $default_phone ? false : $phone_mobile;
        $line4 = self::addField($line4, ($phone_mobile ? $phone_mobile : ($phone ? $phone : '')), 144);
        //one more info : but what is it
        $lines[] = $line4;
        //product lines
        $details = $order->getOrderDetailList();
        $nLine = $shippingCosts = 0;
        foreach ($details as $detail) {
            
            if($detail['product_ean13'] == '3281690249031' && $detail['product_reference'] == '249030'){ // Si le produit fait office de frais de port (specif Dumortier)
                $shippingCosts = $detail['unit_price_tax_excl'];
                continue;
            }
            //is there at least one custom carpet ?
            $tapis_sur_mesure += preg_match('/^Tapis\ Auto\ Sur\ Mesure\ /i', $detail['product_name']);

            $nLine++;
            $line = 'C'.$nLine;
            $line = self::addField($line, '????????', 5);
            $line = self::addField($line, $detail['product_quantity'], 21, 10, '0', false);
            $line = self::addField($line, '0000000000', 31, 10);
            $unit_price_sign = $detail['unit_price_tax_excl'] < 0 ? '-' : '0';
            $unit_price_value = Tools::substr(round(abs($detail['unit_price_tax_excl']) - ($detail['ecotax']/1.2), 2), 0, 9) ;
            // $unit_price_value = Tools::substr(rtrim(round(abs($detail['unit_price_tax_excl']), 2), '0'), 0, 9);

            // Notice : Add discount to every article

            if ($order->total_discounts_tax_excl>0 && $order->total_discounts_tax_excl == $order->total_shipping_tax_excl) {
                $shippingCosts = 0;
                // $discountPercentage=round((($order->total_products - ($order->total_products - $order->total_discounts_tax_excl))/$order->total_products)*100);
                // $unit_price_value=strval($unit_price_value-(round($unit_price_value*($discountPercentage/100), 2)));

            }
            else{
                $shippingCosts = $order->total_shipping_tax_excl;
            }

            if ((int)abs($detail['unit_price_tax_excl']) != (int)$unit_price_value) {
                $warning = true;
                $msg_warning = 'Attention : commande avec prix ingérable ' . $detail['unit_price_tax_excl'];
                self::setMessage($msg_warning, $id_order);
            }
            $line = self::addField($line, $unit_price_sign, 31, 1);
            $line = self::addField($line, ($detail['product_reference'] == 'Frais de gestion' && $detail['product_id'] == 0)?$unit_price_value/1.2:$unit_price_value, 32, 9, '0', false);
            $line = self::addField($line, $id_command, 203, 20, ' ', true);
            $line = self::addField($line, ($detail['product_reference'] == 'Frais de gestion' && $detail['product_id'] == 0)?'0123456789104':$detail['product_ean13'], 223, 13, ' ', true);
            $lines[] = $line;
            // customized field
            if (!empty($detail['id_customization'])) {
                $raw_custom_detail = Db::getInstance()->getValue(
                    'SELECT value
                    FROM ' . _DB_PREFIX_ . 'customized_data
                    WHERE id_customization = ' . (int)$detail['id_customization']
                );
                if ($raw_custom_detail) {
                    $nLine++;
                    $lines[] = 'M' . html_entity_decode($raw_custom_detail);
                }
            }
        }
        //freight
        if (0 < $order->total_shipping_tax_excl && $shippingCosts !=0 || $shippingCosts > 0) {
            $shippingCosts = ($order->total_discounts_tax_excl == $order->total_shipping_tax_excl)?$shippingCosts:$order->total_shipping_tax_excl; // Si les frais de port sont déjà renseignés à cause d'un produit "Frais de port" (plus haut)
            $nLine++;
            $line = 'C'.$nLine;
            $line = self::addField($line, '????????', 5);
            $line = self::addField($line, '1', 21, 10, '0', false);
            $line = self::addField($line, '0000000000', 31, 10);
            $unit_price_sign = $shippingCosts < 0 ? '-' : '0';
            $unit_price_value = Tools::substr(round(abs($shippingCosts), 2), 0, 9);
            if ((int)abs($shippingCosts) != (int)$unit_price_value) {
                $warning = true;
                $msg_warning = 'Attention : commande avec frais de port ingérables ' . $shippingCosts;
                self::setMessage($msg_warning, $id_order);
            }
            $line = self::addField($line, $unit_price_sign, 31, 1);
            $line = self::addField($line, $unit_price_value, 32, 9, '0', false);
            $line = self::addField($line, $id_command, 203, 20, ' ', true);
            $line = self::addField($line, '0123456789012', 223, 13, ' ', true);
            $lines[] = $line;
        }

        $content_text = implode("\r\n", $lines);
        if (file_exists($filename)) {
            $content_text = "\r\n" . $content_text;
        }
        file_put_contents($filename, mb_convert_encoding($content_text, "CP1252"), FILE_APPEND);
        $db = \Db::getInstance();
        $createDate = date('Y-m-d H:i:s');
        $request = 'UPDATE ps_ecoas_orders SET id_order = '.(int)$id_order.', message = "", status = '.(int) $ok_order.', data = "[]", file_name = "'.pSQL(basename($filename)).'", date = "'.$createDate.'" WHERE id_order = '.(int) $id_order;
        $db->execute($request);

	/*$res= Db::getInstance()->insert(
            'ecoas_orders',
            array(
                'id_order' => (int) $id_order,
                'message' => pSQL($msg_not_ok),
                'status' => (int) $ok_order,
                'data' => pSQL(Tools::tsonEncode(array())),
                'file_name' => pSQL(basename($filename)),
                'date' => date('Y-m-d H:i:s'),
            ),
            false, false,
            Db::REPLACE
        );*/
        if ($tapis_sur_mesure) {
            self::setOrderState('pr', $id_order);
        } else {
            self::setOrderState('tr', $id_order);
        }

        self::setMessage('Commande transmise dans le fichier ' . basename($filename), $id_order);

        return array($ok_order, $msg_not_ok);
    }

    public static function buildLine($values, $formats)
    {
        $i = 0;
        $str = '';
        foreach ($values as $value) {
            if ('BIT' == $formats[$i]) {
                $str .= (int)(bool)$value;
            } elseif ('DF1' == $formats[$i]) {
                if ($value && !preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/', $value)) {
                    return false;
                }
                $str .= $value;
            } elseif (preg_match('/^X[0-9]+$/', $formats[$i])) {
                $n = (int) ltrim($formats[$i], 'X');
                $str .= str_repeat('0', $n - Tools::strlen($value)) . (int) $value;
            } elseif (preg_match('/^C[0-9]+$/', $formats[$i])) {
                $n = (int) ltrim($formats[$i], 'C');
                $str .= Tools::substr($value, 0, $n) . str_repeat(' ', $n - Tools::strlen($value));
            } else {
                return false;
            }
            $i++;
        }

        return $str;
    }

    public static function cleanPhone($number)
    {
        return Tools::substr('0000000000' . preg_replace('/\D/', '', $number), -10);
    }

    public static function addField($string, $field, $pos, $size = null, $pad = ' ', $padright = true)
    {
        if (is_null($size)) {
            $size = Tools::strlen($field);
        }

        $pad = Tools::substr($pad, 0, 1);

        $padright = (bool) $padright;

        $field = Tools::substr($field, 0, $size);

        if (Tools::strlen($field) < $size) {
            if ($padright) {
                $field .= str_repeat($pad, $size - Tools::strlen($field));
            } else {
                $field = str_repeat($pad, $size - Tools::strlen($field)) . $field;
            }

        }

        if (Tools::strlen($string) < $pos) {
            $string .= str_repeat(' ', $pos - Tools::strlen($string));
        }

        $left = Tools::substr($string, 0, $pos);
        $right = '';
        if (Tools::strlen($string) > ($pos + $size)) {
            $right = Tools::substr($string, $pos + $size);
        }

        return $left . $field . $right;
    }

    public function ftpLogin()
    {
        $logger = self::logStart('ftp');

        // get parameters from configuration
        $config = self::getConfig();
        $ftp_address = isset($config['ecoas_ftp_address'])?$config['ecoas_ftp_address']:'';
        $ftp_login = isset($config['ecoas_ftp_login'])?$config['ecoas_ftp_login']:'';
        $ftp_pswd = isset($config['ecoas_ftp_pswd'])?$config['ecoas_ftp_pswd']:'';

        if (($ftp_stream = ftp_connect($ftp_address)) === false) {
            self::logError($logger, 'Connexion impossible au FTP ' . $ftp_address);
            return false;
        }
        if (@ftp_login($ftp_stream, $ftp_login, $ftp_pswd) === false) {
            self::logError($logger, 'Authentification impossible au FTP ' . $ftp_address);
            return false;
        }
        ftp_pasv($ftp_stream, true);

        return $ftp_stream;
    }

    public function ftpSend($filename)
    {
        $logger = self::logStart('ftp');

        if (($ftp_stream = $this->ftpLogin()) === false) {
            return false;
        }

        $fileDir = dirname(__FILE__) . '/files/export/';

        if (ftp_put($ftp_stream, $filename, $fileDir . $filename, FTP_BINARY) === false) {
            self::logError($logger, 'Impossible de poser le fichier ' . $filename);
            return false;
        }

        ftp_close($ftp_stream);

        return true;
    }

    public function ftpGet($filename)
    {
        if (($ftp_stream = $this->ftpLogin()) === false) {
            return false;
        }

        $logger = self::logStart('ftp');
        $fileDir = dirname(__FILE__) . '/files/import/';

        if (($files = ftp_nlist($ftp_stream, '/RETOUR/')) === false) {
            self::logError($logger, 'Impossible de scanner /RETOUR/');
            return false;
        }
        $found = false;
        foreach ($files as $file) {
            if (preg_match('/^' . preg_quote($filename) . '$/', $file)) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            self::logError($logger, 'Fichier ' . $filename . ' introuvable');
            return false;
        }
        if (ftp_get($ftp_stream, $fileDir . $filename, $file, FTP_BINARY) === false) {
            self::logError($logger, 'Impossible de récupérer le fichier ' . $filename);
            return false;
        }

        ftp_close($ftp_stream);

        return true;
    }

    public static function createMultiLangField($field)
    {
        $languages = Language::getLanguages(false);
        $res = array();
        foreach ($languages as $lang) {
            $res[$lang['id_lang']] = $field;
        }

        return $res;
    }

    public static function setMessage($message, $id_order)
    {
        $oMsg = new Message();
        $oMsg->id_order = $id_order;
        $oMsg->message = $message;
        $oMsg->private = 1;

        return $oMsg->save();
    }

    public static function setOrderState($ecoas_os, $id_order)
    {
        Context::getContext()->employee = new Employee(1);
        $logger = self::logStart('setos');
        $registered_os = self::getConfigLike('ecoas_os_%');

        if (false === strstr($ecoas_os, 'ecoas_os_')) {
            $ecoas_os = 'ecoas_os_' . $ecoas_os;
        }

        if (!isset($registered_os[$ecoas_os])) {
            self::logError($logger, 'l\'état ' . $ecoas_os . ' n\'est pas matché');
            return false;
        }

        $new_oh = new OrderHistory();
        $new_oh->id_order = $id_order;
        $new_oh->id_order_state = (int) $registered_os[$ecoas_os];
        $new_oh->date_add = date('Y-m-d H:i:s');
        $new_oh->add();

        return true;
    }

    private function regiserOrderStates($list_os)
    {
        $id_lang = Configuration::get('PS_LANG_DEFAULT');
        $list_existing_os = OrderState::getOrderStates($id_lang);
        $tab_existing_os = array();
        foreach ($list_existing_os as $os) {
            if ($os['module_name'] !== $this->name) {
                continue;
            }
            $tab_existing_os[$os['name']] = $os['id_order_state'];
        }

        $list_os_created = array();
        foreach ($list_os as $os) {
            if (is_array($os['name'])) {
                $default_name = $os['name'][$id_lang];
            } else {
                $default_name = $os['name'];
            }
            if (array_key_exists($default_name, $tab_existing_os)) {
                $list_os_created[$os['ecoas_os_id']] = $tab_existing_os[$default_name];
                continue;
            }
            $new_os = new OrderState();
            $new_os->send_email = 0;
            $new_os->module_name = $this->name;
            $new_os->invoice = 0;
            $new_os->color = $os['color'];
            $new_os->logable = 1;
            $new_os->shipped = 0;
            $new_os->unremovable = 0;
            $new_os->delivery = 0;
            $new_os->hidden = 1;
            $new_os->paid = 0;
            $new_os->pdf_delivery = 0;
            $new_os->pdf_invoice = 0;
            $new_os->deleted = 0;
            $new_os->name = is_array($os['name']) ? $os['name'] : self::createMultiLangField($default_name);
            $new_os->template = array();
            $new_os->add();
            $list_os_created[$os['ecoas_os_id']] = $new_os->id;
            copy(dirname(__FILE__).'/views/img/Dynamics.gif', _PS_ORDER_STATE_IMG_DIR_.$new_os->id.'.gif');
        }

        return $list_os_created;
    }

    public static function isEvtProblem($evt)
    {
        // get config and see if $evt is in
        $ecoas_contpbcodes = self::getConfigValue('ecoas_contpbcodes');
        $tab_livpbcodes = Tools::jsonDecode($ecoas_contpbcodes);
        if (is_null($tab_livpbcodes) || !is_array($tab_livpbcodes)) {
            return false;
        }

        if (in_array($evt, $tab_livpbcodes)) {
            return true;
        }

        return false;
    }

    public static function setConfig($name, $value)
    {
        Db::getInstance()->insert(
            'ecoas_config',
            array(
                'name' => pSQL($name),
                'value' => pSQL($value)
            )
            , false, false, Db::ON_DUPLICATE_KEY
        );
    }

    public static function getConfigValue($name)
    {
        return Db::getInstance()->getValue(
            '
            SELECT value
            FROM '._DB_PREFIX_.'ecoas_config
            WHERE name = "'.pSQL($name).'"'
        );
    }

    public static function getConfig()
    {
        $ret = Db::getInstance()->executeS(
            '
            SELECT *
            FROM '._DB_PREFIX_.'ecoas_config'
        );
        $config = array();
        foreach ($ret as $confLine) {
            $config[$confLine['name']] = $confLine['value'];
        }

        return $config;
    }

    public static function getConfigLike($search)
    {
        $ret = Db::getInstance()->executeS(
            '
            SELECT *
            FROM '._DB_PREFIX_.'ecoas_config
            WHERE name LIKE "'.pSQL($search).'"'
        );
        $config = array();
        foreach ($ret as $confLine) {
            $config[$confLine['name']] = $confLine['value'];
        }

        return $config;
    }

    public static function jGet($name)
    {
        return Db::getInstance()->getValue('
                SELECT value
                FROM ' . _DB_PREFIX_ . 'ecoas_jobs
                WHERE name = "' . pSQL($name) . '"');
    }

    public static function jGetNLike($name)
    {
        return Db::getInstance()->getValue('
                SELECT COUNT(*)
                FROM ' . _DB_PREFIX_ . 'ecoas_jobs
                WHERE name LIKE "' . pSQL($name) . '"');
    }

    public static function jGetLike($name)
    {
        $result = Db::getInstance()->executeS('
                SELECT name, value
                FROM ' . _DB_PREFIX_ . 'ecoas_jobs
                WHERE name LIKE "' . pSQL($name) . '"');
        if ($result) {
            $ret = array();
            foreach ($result as $line) {
                $ret[$line['name']] = $line['value'];
            }

            return $ret;
        }

        return false;
    }

    public static function jUpdateValue($name, $value)
    {
        Db::getInstance()->execute('
                INSERT INTO ' . _DB_PREFIX_ . 'ecoas_jobs
                (name,value)
                VALUES ("' . pSQL($name) . '","' . pSQL($value) . '")
                ON DUPLICATE KEY UPDATE value = VALUES(value);');
    }

    public function getCpanelData($prefix = false, $paramConnecteur = false)
    {
        if (!$prefix || !$paramConnecteur) {
            return;
        }

        // get infos about the cron task(s) prefixed by $prefix
        $prefix = trim($prefix, '_') . '_';

        $jobs_state = self::jGetLike($prefix . '%');
        if (!$jobs_state) {
            return;
        }

        $suffix = '_' . $paramConnecteur;
        $status = array(
            'start_time' => isset($jobs_state[$prefix . 'START_TIME' . $suffix]) ? $jobs_state[$prefix . 'START_TIME' . $suffix] : '',
            'end_time' => isset($jobs_state[$prefix . 'END_TIME' . $suffix]) ? $jobs_state[$prefix . 'END_TIME' . $suffix] : '',
            'state' => isset($jobs_state[$prefix . 'STATE' . $suffix]) ? $jobs_state[$prefix . 'STATE' . $suffix] : '',
            'act' => isset($jobs_state[$prefix . 'ACT' . $suffix]) ? $jobs_state[$prefix . 'ACT' . $suffix] : '',
            'shops_todo' => isset($jobs_state[$prefix . 'SHOPS_TODO' . $suffix]) ? $jobs_state[$prefix . 'SHOPS_TODO' . $suffix] : '',
            'shop' => isset($jobs_state[$prefix . 'SHOP' . $suffix]) ? $jobs_state[$prefix . 'SHOP' . $suffix] : '',
            'stage' => isset($jobs_state[$prefix . 'STAGE' . $suffix]) ? $jobs_state[$prefix . 'STAGE' . $suffix] : '',
            'loops' => isset($jobs_state[$prefix . 'LOOPS' . $suffix]) ? $jobs_state[$prefix . 'LOOPS' . $suffix] : '',
            'progress' => isset($jobs_state[$prefix . 'PROGRESS' . $suffix]) ? $jobs_state[$prefix . 'PROGRESS' . $suffix] : '',
            'progressmax' => isset($jobs_state[$prefix . 'PROGRESSMAX' . $suffix]) ? $jobs_state[$prefix . 'PROGRESSMAX' . $suffix] : '',
            'data' => isset($jobs_state[$prefix . 'DATA' . $suffix]) ? $jobs_state[$prefix . 'DATA' . $suffix] : '',
            'message' => isset($jobs_state[$prefix . 'MESSAGE' . $suffix]) ? $jobs_state[$prefix . 'MESSAGE' . $suffix] : ''
        );

        $this->smarty->assign('cpanelData', $status);

        return $this->display(__FILE__, 'views/templates/admin/cpaneltable.tpl');
    }

    public function displayInfoRetour($message, $etat = '')
    {
        $this->smarty->assign(
            array(
                'message' => $message,
                'etat' => $etat
            )
        );

        return $this->display(dirname(__FILE__), 'views/templates/admin/messages.tpl');
    }

    public static function logStart($family, $truncate = false, $limit = 0)
    {
        $logger = new Info($family, $truncate, $limit);
        $logger->dir = dirname(__FILE__) . '/log/';
        $logger->onlyDB = true; // true if logs must go to DB

        return $logger;
    }

    public static function logDebug($logger, $text)
    {
        if (self::YES_LOG) {
            return $logger->logDebug($text);
        }

        return false;
    }

    public static function logInfo($logger, $text)
    {
        if (self::YES_LOG) {
            return $logger->logInfo($text);
        }

        return false;
    }

    public static function logWarning($logger, $text)
    {
        if (self::YES_LOG) {
            return $logger->logWarning($text);
        }

        return false;
    }

    public static function logError($logger, $text)
    {
        if (self::YES_LOG) {
            return $logger->logError($text);
        }

        return false;
    }

    public static function logTruncate($logger)
    {
        $logger->logTruncate();
    }

    public static function logDelete($logger)
    {
        $logger->logDelete();
    }

    public static function logArchive($logger)
    {
        $logger->logArchive();
    }

    public static function followLink($link, $timeout = self::FOLLOWLINK_TIMEOUT)
    {
        $logger = self::logStart('curl');
        $a_ret = self::goCurl($link, $timeout);
        if (preg_match('/SSL/', $a_ret[0]['err'])) {
            $link = str_replace('https', 'http', $link);
            $a_ret = self::goCurl($link, $timeout);
        }
        while (preg_match('/Connection\ timed\ out\ after/', $a_ret[0]['err'])
            || preg_match('/connect\(\)\ timed\ out\!/', $a_ret[0]['err'])
            || preg_match('/Gateway\ Time\-out/', $a_ret[0]['err'])
            || (504 == $a_ret[0]['infos']['http_code'])
            || preg_match('/name\ lookup\ timed\ out/', $a_ret[0]['err'])
            || preg_match('/Resolving\ timed\ out\ after/', $a_ret[0]['err'])) {
            self::logInfo(
                $logger,
                'Error (' . $a_ret[0]['errno'] . ') "' . $a_ret[0]['err']. '"' . "\n"
                . 'Infos ' . var_export($a_ret[0]['infos'], true) . "\n"
                . 'Relaunched ' . $link);
            $a_ret = self::goCurl($link, $timeout);
        }
/*        while (in_array($a_ret[0]['errno'], array('6', '7'))) { // 22 ? test http
            self::logInfo(
                $logger,
                'Error (' . $a_ret[0]['errno'] . ') "' . $a_ret[0]['err']. '"' . "\n"
                . 'Infos ' . var_export($a_ret[0]['infos'], true) . "\n"
                . 'Relaunched ' . $link);
            $a_ret = self::goCurl($link, $timeout);
        }*/

        if (self::FOLLOWLINK_LOG) {
            self::logInfo($logger, var_export($a_ret, true));
        }

        return $a_ret[1];
    }

    public static function goCurl($link, $timeout = self::FOLLOWLINK_TIMEOUT)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $result = curl_exec($ch);
        $tab_err = array(
            'err' => curl_error($ch),
            'errno' => curl_errno($ch),
            'infos' => curl_getinfo($ch)
        );
        curl_close($ch);

        return array($tab_err, $result);
    }

}
