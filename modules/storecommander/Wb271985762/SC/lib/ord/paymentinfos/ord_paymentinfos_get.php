<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

$list = Tools::getValue('id_order_list', 0);

function getRowsFromDB($orderListId)
{
    $xml = '';
    $orderListId = explode(',', $orderListId);
    foreach ($orderListId as $idOrder)
    {
        $order = new Order($idOrder);
        if (Validate::isLoadedObject($order))
        {
            $payments = $order->getOrderPaymentCollection();
            foreach ($payments as $payment)
            {
                $xml .= "<row id='".(int) $payment->id."'>";
                $xml .= '   <cell>'.(int) $order->id.'</cell>';
                $xml .= '   <cell><![CDATA['.$payment->date_add.']]></cell>';
                $xml .= '   <cell><![CDATA['.$payment->payment_method.']]></cell>';
                $xml .= '   <cell><![CDATA['.$payment->transaction_id.']]></cell>';
                $xml .= '   <cell>'.(float) $payment->amount.'</cell>';
                $invoice = $payment->getOrderInvoice($order->id);
                $invoice_number = false;
                if (!empty($invoice))
                {
                    $invoice_number = $invoice->getInvoiceNumberFormatted((int) $order->id_lang, $order->id_shop);
                }
                $xml .= '   <cell><![CDATA['.($invoice_number ? $invoice_number : '').']]></cell>';
                $xml .= '</row>';
            }
        }
    }

    return $xml;
}

$xml = getRowsFromDB($list);

//XML HEADER
if (stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml'))
{
    header('Content-type: application/xhtml+xml');
}
else
{
    header('Content-type: text/xml');
}
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

?>
<rows id="0">
    <head>
        <column id="id_order" width="45" type="ro" align="right" sort="int"><?php echo _l('id order'); ?></column>
        <column id="date_add" width="140" type="ro" align="right" sort="str"><?php echo _l('Creation date'); ?></column>
        <column id="payment_method" width="150" type="ro" align="left" sort="str"><?php echo _l('Payment method'); ?></column>
        <column id="transaction_id" width="120" type="ro" align="left" sort="str"><?php echo _l('Transaction ID'); ?></column>
        <column id="amount" width="80" type="ro" align="left" sort="int" format="0.00"><?php echo _l('Amount'); ?></column>
        <column id="invoice_number" width="120" type="ro" align="left" sort="str"><?php echo _l('Invoice'); ?></column>
    </head>
    <?php
    echo '<userdata name="uisettings">'.UISettings::getSetting('ord_paymentinfos').'</userdata>'."\n";
    echo $xml;
    ?>
</rows>