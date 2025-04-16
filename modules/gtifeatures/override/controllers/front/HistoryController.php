<?php
/**
 * GTI Features
 * Add ndk extended data to template
 */
class HistoryController extends HistoryControllerCore
{
    /**
     * Add ndk extended data to smarty var
     */
    public function getTemplateVarOrders()
    {
        if (Configuration::get('GTIFEATURES_USE_NDK_EXTENDED_DATA_FOR_ORDER_HISTORY_AND_DETAIL')) {
            return $this->addCustomInformationsToTemplateVarOrders(parent::getTemplateVarOrders());
        }

        return parent::getTemplateVarOrders();
    }

    protected function addCustomInformationsToTemplateVarOrders(&$templateVarOrders)
    {
        $ordersId = array_keys($templateVarOrders);
        foreach ($ordersId as $orderId) {
            $sql = '
                SELECT cp.`id_customization`
                FROM `'._DB_PREFIX_.'orders` o
                INNER JOIN `'._DB_PREFIX_.'cart_product` cp ON o.`id_cart` = cp.`id_cart`
                WHERE o.`id_order` = ' . $orderId;

            $customizationId = Db::getInstance()->getValue($sql);

            $sql = '
                SELECT cde.`admin_name`, cde.`value`
                FROM `'._DB_PREFIX_.'ndk_customized_data_extended` cde
                WHERE cde.`id_customization` = ' . $customizationId;

            $formattedData = [];
            foreach (Db::getInstance()->executeS($sql) as $customizedData) {
                $formattedData[$customizedData['admin_name']] = $customizedData['value'];
            }

            $templateVarOrders[$orderId]['customized_data'] = $formattedData;
        }

        return $templateVarOrders;
    }
}
