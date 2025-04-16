<?php
/**
 * Gtifeatures
 * Add ndk extended data to template
 * 
 */
class OrderDetailController extends OrderDetailControllerCore
{
    /*
    * module: gtifeatures
    * date: 2025-04-01 17:32:54
    * version: 1.0.0
    */
    public function initContent()
    {
        parent::initContent();
        if (Configuration::get('GTIFEATURES_USE_NDK_EXTENDED_DATA_FOR_ORDER_HISTORY_AND_DETAIL')) {
            $this->addExtendedDataToTemplateVars();
        }
    }
    /** 
     * OVERRIDE SMARTY VAR TO INCLUDE EXTENDED DATA
     */
    /*
    * module: gtifeatures
    * date: 2025-04-01 17:32:54
    * version: 1.0.0
    */
    protected function addExtendedDataToTemplateVars()
    {
        $id_order = (int) Tools::getValue('id_order');
        $id_order = $id_order && Validate::isUnsignedId($id_order) ? $id_order : false;
        if (!$id_order) {
            $reference = Tools::getValue('reference');
            $reference = $reference && Validate::isReference($reference) ? $reference : false;
            $order = $reference ? Order::getByReference($reference)->getFirst() : false;
            $id_order = $order ? $order->id : false;
        }
        if ($id_order) {
            $order = new Order($id_order);
            if (Validate::isLoadedObject($order) && $order->id_customer == $this->context->customer->id) {
                $this->order_to_display = $this->addCustomInformationsToTemplateVarOrders($this->order_to_display, $order->id);
                $this->context->smarty->assign([
                    'order' => $this->order_to_display,
                ]);
            }
            unset($order);
        }
    }
    /*
    * module: gtifeatures
    * date: 2025-04-01 17:32:54
    * version: 1.0.0
    */
    protected function addCustomInformationsToTemplateVarOrders(&$templateVarOrders, $orderId)
    {
        $sql = '
            SELECT cp.`id_customization`
            FROM `' . _DB_PREFIX_ . 'orders` o
            INNER JOIN `' . _DB_PREFIX_ . 'cart_product` cp ON o.`id_cart` = cp.`id_cart`
            WHERE o.`id_order` = ' . $orderId;
        $customizationId = Db::getInstance()->getValue($sql);
        $sql = '
            SELECT cde.`admin_name`, cde.`value`
            FROM `' . _DB_PREFIX_ . 'ndk_customized_data_extended` cde
            WHERE cde.`id_customization` = ' . $customizationId;
        $formattedData = [];
        foreach (Db::getInstance()->executeS($sql) as $customizedData) {
            $formattedData[$customizedData['admin_name']] = $customizedData['value'];
        }
        $templateVarOrders['customized_data'] = $formattedData;
        return $templateVarOrders;
    }
}
