<?php
/**
*
* @author John Lemon
* @copyright  2018 Lemon Interactive
* @license   One time purchase Licence (You can modify or resell the product but just one time per licence)
* @version 1.0
* @category back_office_features
* Registered Trademark & Property of lemon-interactive.fr
**/

if (!defined('_PS_VERSION_')) {
    exit;
}

class export extends Module
{
    const DIR_MODULE = 'export';

    public function __construct()
    {
        $this->name = 'export';
        $this->tab = 'back_office_features';
        $this->version = '0.1';
        $this->author = 'Lemon Interactive';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Export CSV & PDF');
        $this->description = $this->l('Export des commandes CSV et PDF');
    }

    public function install()
    {

        $token = uniqid(rand(), true);
        Configuration::updateValue('EXPORT_TOKEN', $token);

        if (!parent::install() ||
            !$this->installTab('AdminExport', 'Ordre de fabrication', 'AdminParentOrders') ||
            !$this->registerHook('backOfficeHeader')
           ) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        Configuration::deleteByName('EXPORT_TOKEN');
        return parent::uninstall();
    }

    public function hookBackOfficeHeader(){
        $this->context->controller->addCSS($this->_path.'views/css/export.css', 'all');
    }

    /*
    * Add link to menu admin in back office
    */
    public function installTab($class_name, $name, $parent_class = false)  {
        $tab = new Tab();
        $tab->active = 1;

        // Define the title of your tab that will be displayed in BO
        $tab->name = array();
         
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $name;
        }

        // Name of your admin controller 
        $tab->class_name = $class_name;

        // Id of the controller where the tab will be attached
        // If you want to attach it to the root, it will be id 0 (I'll explain it below)
        if ($parent_class) {
            $tab->id_parent = (int) Tab::getIdFromClassName($parent_class);
        } else {
            $tab->id_parent = 0;
        }

        // Name of your module, if you're not working in a module, just ignore it, it will be set to null in DB
        $tab->module = $this->name;

        return $tab->add();
    }

    /*
    * Form
    */
    protected function renderForm()
    {
        $id_lang = Context::getContext()->language->id;

        $uri = _PS_BASE_URL_.__PS_BASE_URI__;
        $url_module = $uri."modules/".self::DIR_MODULE."/";
        $token = Configuration::get('EXPORT_TOKEN');

        // Url module BO
        $url_submit = AdminController::$currentIndex.'&configure='.self::DIR_MODULE.'&token='.Tools::getAdminTokenLite('AdminModules');

        /*
        * Get all status
        */
        $statuses = OrderState::getOrderStates($id_lang);

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
        SELECT COUNT(*) as nbOrders, (
            SELECT oh.id_order_state
            FROM '._DB_PREFIX_.'order_history oh
            WHERE oh.id_order = o.id_order
            ORDER BY oh.date_add DESC, oh.id_order_history DESC
            LIMIT 1
        ) id_order_state
        FROM '._DB_PREFIX_.'orders o
        GROUP BY id_order_state');
        $statusStats = array();
        foreach ($result as $row)
            $statusStats[$row['id_order_state']] = $row['nbOrders'];

        $date_today = date('Y-m-d');

        $this->context->smarty->assign('token', $token);
        $this->context->smarty->assign('url_module', $url_module);
        $this->context->smarty->assign('url_submit', $url_submit);
        $this->context->smarty->assign('today', $date_today);
        $this->context->smarty->assign('statuses', $statuses);
        $this->context->smarty->assign('statusStats', $statusStats);

        return $this->display(__FILE__, 'views/templates/admin/index.tpl');
    }


    /*
    * Display content for admin module
    */
    public function getContent()
    {
        $this->_html = '';

        /**
        * If values have been submitted in the form, process.
        */
        if (Tools::isSubmit('submitPrint') || Tools::isSubmit('submitCSV') || Tools::isSubmit('submitStatus') ) {
            $this->postValidation();
            if (!count($this->_postErrors)) {
                $this->postProcess();
            } else {
                foreach ($this->_postErrors as $err) {
                    $this->_html .= $this->displayError($err);
                }
            }
        }

        $this->_html .= $this->renderForm();

        return $this->_html;
    }


    /*
    * Check form
    */
    public function postProcess()
    {
        if ($this->postValidation() == false) {
            return false;
        }

        if (Tools::isSubmit('submitPrint')) {
            $orders = $this->getOrdersIdByDate(Tools::getValue('date_from'), Tools::getValue('date_to'));
            if (sizeof($orders))
                export::preparationPDF($orders);

            $this->_errors[] = $this->l('No order found for this period');
        }
        elseif (Tools::isSubmit('submitCSV')) {
            $orders = $this->getOrdersIdByDate(Tools::getValue('date_from'), Tools::getValue('date_to'));
            if (sizeof($orders))
                export::exportOrders($orders);

            $this->_errors[] = $this->l('No order found for this period');
        }
        elseif (Tools::isSubmit('submitStatus'))
        {
            $allOrders = array();
            foreach (Tools::getValue('id_order_state') as $id_order_state){
                $sql = new DbQuery();
                $sql->select('id_order')
                    ->from('orders')
                    ->where('current_state = '.(int)$id_order_state);

                $order_ids = Db::getInstance()->executeS($sql);

                if (!empty($order_ids)){
                    $allOrders = array_column(array_merge($allOrders, $order_ids), 'id_order');

                    export::preparationPDF($allOrders);
                }
            }

            $this->_errors[] = $this->l('No order found for this period');
        }
    }

    /*
    * POST Validation
    */
    protected function postValidation()
    {
        $this->_postErrors = array();

        if (Tools::isSubmit('submitPrint')) {
            if (!Validate::isDate(Tools::getValue('date_from'))
                || !Validate::isDate(Tools::getValue('date_to'))
            ) {
                $this->_postErrors[] = $this->l('Date not valid.');
            }
        }
        elseif (Tools::isSubmit('submitCSV')) {
            if (!Validate::isDate(Tools::getValue('date_from'))
                || !Validate::isDate(Tools::getValue('date_to'))
            ) {
                $this->_postErrors[] = $this->l('Date not valid.');
            }
        }
        elseif (Tools::isSubmit('submitStatus'))
        {
            if (!is_array($statusArray = Tools::getValue('id_order_state')) OR !count($statusArray))
                $this->_postErrors[] = $this->l('Invalid order status');
        }

        if (count($this->_postErrors)) {
            return false;
        }
        return true;
    }


    /*
    * Generate PDF
    */
    public static function preparationPDF($orders){
        if(empty($orders)) exit();
        $new_array_order = array();
        
        // Check if have customized product data
        foreach ($orders as $order_id) :
            $order = new Order((int)$order_id);
 
            $order_details = $order->getProducts();
            
            $customizedDatas = Product::getAllCustomizedDatas((int)($order->id_cart), null, false);
            // Get id_customization cart
            $id_customization = Db::getInstance()->getValue("
                SELECT c.id_customization FROM "._DB_PREFIX_."customization c
                WHERE c.id_cart = ". (int)$order->id_cart );
                
                // Add custom information to $order_details
                foreach($order_details AS &$order_detail)
                {
                    if (isset($customizedDatas[$order_detail['product_id']][$order_detail['product_attribute_id']]))
                    {
                        foreach($customizedDatas[$order_detail['product_id']][$order_detail['product_attribute_id']] as $customizedData) :
                            $customizationGroup = $customizedData[$id_customization]['datas'];
                            foreach ($customizationGroup as $customizationFieldId => $customization) {
                                if ($customization[0]['type'] == Product::CUSTOMIZE_TEXTFIELD) {
                                    $values = $customization[0]['value'];
                                    $values = explode(' / ', $values);
                                    $marque = $values[0];
                                    $modele = $values[1];
                                    $gamme  = $values[2];
                                    $config = $values[3];
                                    $couleur = $values[4];
                                    $date = $values[5];
                                    $code_gabarit = str_replace("~~", "", $values[6]);
                
                                    if (empty($marque) || empty($modele)) continue;
                
                                    // Display only orders with custom data
                                    $new_array_order[] = $order_id;
                                }
                            }
                        endforeach;
                    }
                }
        endforeach;

        include __DIR__.'/classes/HTMLTemplatePreparation.php';
        $pdf = new PDF($new_array_order, 'Preparation', Context::getContext()->smarty);
        $pdf->render();
    }


    /*
    * Generate export orders
    */
    public static function exportOrders($orders){

        if(empty($orders)) exit();

        $i = 1;
        $datas = array();
        $gammes_couleurs = array();

        foreach ($orders as $order_id)
        {

            $orderObj = new Order((int)$order_id);
            if (Validate::isLoadedObject($orderObj))
            {
                $products = $orderObj->getProducts();
                $customizedDatas = Product::getAllCustomizedDatas((int)($orderObj->id_cart), null, false);
    
                // $customizedDatas = Product::getAllCustomizedDatas((int)($order->id_cart), null, false);
                // var_dump($products);
                // Get id_customization cart
                $id_customization = Db::getInstance()->getValue("
                SELECT c.id_customization FROM "._DB_PREFIX_."customization c
                WHERE c.id_cart = ". (int)$orderObj->id_cart );
                foreach ($products as $product) {
                    // var_dump($product);
                    if (isset($customizedDatas[$product['product_id']][$product['product_attribute_id']])) {
                        foreach ($customizedDatas[$product['product_id']][$product['product_attribute_id']] as $customizedData) {
                            $customizationGroup = $customizedData[$product['id_customization']]['datas'];
                            $nb_images = 0;
                
                            // Check for file customizations
                            foreach ($customizationGroup as $customizationFieldId => $customization) {
                                if ($customization[0]['type'] == Product::CUSTOMIZE_FILE) {
                                    $nb_images++;
                                }
                            }
                
                            // Check for text field customizations
                            foreach ($customizationGroup as $customizationFieldId => $customization) {
                                if ($customization[0]['type'] == Product::CUSTOMIZE_TEXTFIELD) {
                                    $values = utf8_decode($customization[0]['value']);
                                    $values = str_replace('&egrave;', 'è', $values);
                
                                    $values = explode(' / ', $values);
                                    $marque = $values[0];
                                    $modele = $values[1];
                                    $config = $values[3];
                                    $couleur = $values[4];
                                    $date = $values[5];
                                    $code_gabarit = str_replace("~~", "", $values[5]);
                
                                    if (!preg_match('/Basique/', $couleur)) {
                                        $ids_customizations = array(0 => $customization[0]['id_customization']);
                                        $quantities = Customization::retrieveQuantitiesFromIds($ids_customizations);
                
                                        $gammes_couleurs[$couleur][] = array(
                                            'id_order' => $order_id,
                                            'modele' => $marque . ' - ' . $modele,
                                            'reference' => $product['product_reference'],
                                            'config' => $config,
                                            'annee' => $date,
                                            'quantity' => $quantities[$customization[0]['id_customization']]['quantity'],
                                            'code_gabarit' => $code_gabarit
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // Output CSV
        $datas[] = 'SYNTHESE ORDRE DE FABRICATION;'."\n";
        $datas[] = ''."\n";
        $datas[] = "Date d'envoi : ".date('d/m/Y H:i').";"."\n";
        foreach($gammes_couleurs as $gamme_couleur => $values)
        {
            $datas[] = "\n\n".'Gamme '.str_replace('GT', 'Grand Tourisme', $gamme_couleur)."\n\n";
            $datas[] = utf8_decode('Numéro de commande;Marque - modèle;Référence;Configuration;Année;Quantité;Code Gabarit')."\n";

            foreach($values as $value)
            {
                $datas[] = $value['id_order'].';'.$value['modele'].';'.$value['reference'].';'.$value['config'].';'.$value['annee'].';'.$value['quantity'].';'.$value['code_gabarit']."\n";
            }
        }

        export::download_csv_results($datas, 'recap-preparations');
        exit;
    }

    public static function getOrdersIdByDate($date_from, $date_to, $id_customer = null, $type = null)
    {
        $sql = 'SELECT `id_order`
                FROM `' . _DB_PREFIX_ . 'orders`
                WHERE DATE_ADD(date_add, INTERVAL -1 DAY) <= \'' . pSQL($date_to) . '\' AND date_add >= \'' . pSQL($date_from) . '\'
                    ' . ($type ? ' AND `' . bqSQL($type) . '_number` != 0' : '')
                    . ($id_customer ? ' AND id_customer = ' . (int) $id_customer : '');

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        $orders = [];
        foreach ($result as $order) {
            $orders[] = (int) $order['id_order'];
        }

        return $orders;
    }


    /*
    * Download CSV result
    */
    public static function download_csv_results($datas, $name = "")
    {
        if(!empty($name)) $name .= $name."-";
        $name .= md5(uniqid() . microtime(TRUE) . mt_rand());

        header('Content-Encoding: UTF-8');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename='. $name . '.csv');
        header('Pragma: no-cache');
        header("Expires: 0");

        foreach($datas as $data)
        {
            echo $data;
        }
    }

}
