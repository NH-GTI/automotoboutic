<?php
/**
 * GTI Features
 * Add ndk extended data to template
 */
class OrderConfirmationController extends OrderConfirmationControllerCore
{
    /**
     * Assign template vars related to page content.
     *
     * @see FrontController::initContent()
     */
    /*
    * module: gtifeatures
    * date: 2025-04-01 17:32:54
    * version: 1.0.0
    */
    public function initContent()
    {
        parent::initContent();
        
        if (Configuration::get('GTIFEATURES_USE_NDK_EXTENDED_DATA_FOR_ORDER_HISTORY_AND_DETAIL')) {
            $order = new Order(Order::getIdByCartId((int) ($this->id_cart)));
            $presentedOrder = $this->context->smarty->getTemplateVars('order');
            $presentedOrder = $this->addCustomInformationsToTemplateVarOrders($presentedOrder, $order->id);
            $this->context->smarty->assign([
                'order' => $presentedOrder,
            ]);
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
