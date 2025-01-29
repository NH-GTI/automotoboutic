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

class HTMLTemplatePreparation extends HTMLTemplate
{
    public $order;
    public $available_in_your_account = false;

    /**
     * @param OrderInvoice $order_invoice
     * @param $smarty
     * @throws PrestaShopException
     */
    public function __construct($order_id=0, $smarty, $bulk_mode = false)
    {
        $this->order = new Order((int)$order_id);
        $this->order_invoice = new OrderInvoice($this->order->invoice_number);
        $this->smarty = $smarty;

        // If shop_address is null, then update it with current one.
        // But no DB save required here to avoid massive updates for bulk PDF generation case.
        // (DB: bug fixed in 1.6.1.1 with upgrade SQL script to avoid null shop_address in old orderInvoices)
        if (!isset($this->order_invoice->shop_address) || !$this->order_invoice->shop_address) {
            $this->order_invoice->shop_address = OrderInvoice::getCurrentFormattedShopAddress((int)$this->order->id_shop);
            if (!$bulk_mode) {
                OrderInvoice::fixAllShopAddresses();
            }
        }

        // header informations
        $id_lang = Context::getContext()->language->id;

        // Shipping Date
        $history = $this->order->getHistory($this->order->id_lang);
        foreach($history as $h)
            if ($h['id_order_state'] == Configuration::get('PS_OS_SHIPPING'))
                $shipping_date = $h['date_add'];
                if(isset($shipping_date))
                    $this->date = HTMLTemplatePreparation::l('Date d\'envoi')." : ".Tools::displayDate($shipping_date);

        // Title
        $this->title = HTMLTemplatePreparation::l('N° de commande')." : ".$this->order->id;

        // footer informations
        $this->shop = new Shop((int)$this->order->id_shop);
    }

    /**
     * Returns the template's HTML header
     *
     * @return string HTML header
     */
    public function getHeader()
    {
        $this->assignCommonHeaderData();
        $this->smarty->assign(array('header' => Context::getContext()->getTranslator()->trans('Ordre de fabrication', array(), 'Shop.Pdf')));

        return $this->smarty->fetch(_PS_MODULE_DIR_.'export/views/templates/front/pdf/header.tpl');
    }

    /**
     * Returns the template's HTML content
     *
     * @return string HTML content
     */
    public function getContent()
    {

        $delivery_address = new Address((int)$this->order->id_address_delivery);
        $formatted_delivery_address = AddressFormat::generateAddress($delivery_address, array(), '<br />', ' ');
        $formatted_invoice_address = '';

        if ($this->order->id_address_delivery != $this->order->id_address_invoice) {
            $invoice_address = new Address((int)$this->order->id_address_invoice);
            $formatted_invoice_address = AddressFormat::generateAddress($invoice_address, array(), '<br />', ' ');
        }

        $carrier = new Carrier($this->order->id_carrier);
        $carrier->name = ($carrier->name == '0' ? Configuration::get('PS_SHOP_NAME') : $carrier->name);

        $order_details = $this->order->getProducts();
        $customizedDatas = Product::getAllCustomizedDatas((int)($this->order->id_cart), null, false);

        // Get id_customization cart
        // $id_customization = Db::getInstance()->getValue("
        //     SELECT c.id_customization FROM "._DB_PREFIX_."customization c
        //     WHERE c.id_cart = ". (int)$this->order->id_cart );

        $order_detail["custom"] =  array();

        // Add custom information to $order_details
        foreach ($order_details as &$order_detail) {
            if (isset($customizedDatas[$order_detail['product_id']][$order_detail['product_attribute_id']])) {
                foreach ($customizedDatas[$order_detail['product_id']][$order_detail['product_attribute_id']] as $customizedData) {
                    $customizationGroup = $customizedData[$order_detail['id_customization']]['datas'];
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
                            $values = $customization[0]['value'];
                            $values = explode(' / ', $values);
                            $marque = $values[0];
                            $modele = $values[1];
                            $gamme  = $values[2];
                            $config = $values[3];
                            $couleur = $values[4];
                            $date = $values[5];
                            $code_gabarit = str_replace("~~", "", $values[6]);
        
                            if (!preg_match('/Basique/', $couleur)) {
                                $ids_customizations = array(0 => $customization[0]['id_customization']);
                                // Get quantity for customization
                                $quantities = Customization::retrieveQuantitiesFromIds($ids_customizations);
        
                                // Detail custom product
                                $products_list["custom"][] = array(
                                    'modele' => $marque . ' - ' . $modele,
                                    'gamme' => $gamme,
                                    'reference' => $order_detail['product_reference'],
                                    'config' => $config,
                                    'annee' => $date,
                                    'quantity' => $quantities[$customization[0]['id_customization']]['quantity'],
                                    'couleur' => $couleur,
                                    'code_gabarit' => $code_gabarit
                                );
                            }
                        }
                    }
                }
            }
        }

        if (Configuration::get('PS_PDF_IMG_DELIVERY')) {
            foreach ($order_details as &$order_detail) {
                if ($order_detail['image'] != null) {
                    $name = 'product_mini_'.(int)$order_detail['product_id'].(isset($order_detail['product_attribute_id']) ? '_'.(int)$order_detail['product_attribute_id'] : '').'.jpg';
                    $path = _PS_PROD_IMG_DIR_.$order_detail['image']->getExistingImgPath().'.jpg';

                    $order_detail['image_tag'] = preg_replace(
                        '/\.*'.preg_quote(__PS_BASE_URI__, '/').'/',
                        _PS_ROOT_DIR_.DIRECTORY_SEPARATOR,
                        ImageManager::thumbnail($path, $name, 45, 'jpg', false),
                        1
                    );

                    if (file_exists(_PS_TMP_IMG_DIR_.$name)) {
                        $order_detail['image_size'] = getimagesize(_PS_TMP_IMG_DIR_.$name);
                    } else {
                        $order_detail['image_size'] = false;
                    }
                }
            }
        }

        $this->smarty->assign(array(
            'order' => $this->order,
            'order_details' => $order_details,
            'products_list' => $products_list["custom"],
            'delivery_address' => $formatted_delivery_address,
            'invoice_address' => $formatted_invoice_address,
            'order_invoice' => $this->order_invoice,
            'carrier' => $carrier,
            'display_product_images' => Configuration::get('PS_PDF_IMG_DELIVERY')
        ));

        $tpls = array(
            'style_tab' => $this->smarty->fetch( _PS_MODULE_DIR_.'export/views/templates/front/pdf/preparation.style-tab.tpl'),
            'addresses_tab' => $this->smarty->fetch( _PS_MODULE_DIR_.'export/views/templates/front/pdf/preparation.addresses-tab.tpl'),
            'summary_tab' => $this->smarty->fetch( _PS_MODULE_DIR_.'export/views/templates/front/pdf/preparation.summary-tab.tpl'),
            'product_tab' => $this->smarty->fetch( _PS_MODULE_DIR_.'export/views/templates/front/pdf/preparation.product-tab.tpl'),
            // 'payment_tab' => $this->smarty->fetch( _PS_MODULE_DIR_.'export/views/templates/front/pdf/preparation.payment-tab.tpl'),
        );
        $this->smarty->assign($tpls);

        return $this->smarty->fetch( _PS_MODULE_DIR_.'export/views/templates/front/pdf/preparation.tpl');
    }

    /**
     * Returns the template filename when using bulk rendering
     *
     * @return string filename
     */
    public function getBulkFilename()
    {
        return 'preparation-'.time().'.pdf';
    }

    /**
     * Returns the template filename
     *
     * @return string filename
     */
    public function getFilename()
    {
        return 'preparation-'.time().'.pdf';
    }

    /**
     * Returns the template's HTML pagination block
     *
     * @return string HTML pagination block
     */
    public function getPagination()
    {
        return $this->smarty->fetch(_PS_MODULE_DIR_.'export/views/templates/front/pdf/pagination.tpl');
    }
}