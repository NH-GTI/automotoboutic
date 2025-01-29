<?php
/**
 * 2007-2020 PrestaShop
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA
 * @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
 * International Registered Trademark & Property of PrestaShop SA
 */
class CMCICPaiementValidationModuleFrontController extends ModuleFrontController
{
    /** @var bool If set to true, will be redirected to authentication page */
    public $auth = false;

    /** @var bool */
    public $ajax;

    /** @var CMCICPaiement */
    public $module;

    public function initContent()
    {
        // Avoid content to be displayed
        $this->ajax = true;

        header('Pragma: no-cache');
        header('Content-type: text/plain');
        ini_set('display_errors', 'off');

        $this->module->includeConf();
        $cmcic_error_behavior = (int) Configuration::get('CMCIC_ERROR_BEHAVIOR');
	$data = getMethode();

        $this->sendToLog('CMCIC - Order start ', 3);
	$missingData = $this->getMissingRequestedValues($data);
        if (!empty($missingData)) {
            $this->sendToLog('CMCIC - The following params are missing from the order validation query: ' . implode(', ', $missingData), 3);
            #http_response_code(400);

            #return;
        }
        $cmcic = new CmCicTpe();
        $hmac = new CmCicHmac($cmcic);

        $order_status = false;
        $order_message = '';
        $data_reference = Tools::substr($data['reference'], 0, -2);
        $reference = (int) $data_reference;

        // Check if the transaction has already been handled
        if ($this->module->isDuplicate($data_reference, $data['code-retour'])) {
            $this->sendToLog('CMCIC - the transaction has already been handled', 3);
            $this->module->logNotificationRequest($data_reference, $data['code-retour']);

            return;
	}

        $this->sendToLog('CMCIC - New transaction', 1);
        // Log the notification request in DB
        $this->module->logNotificationRequest($data_reference, $data['code-retour']);

        $MAC_source = $this->module->computeHmacSource($data, $cmcic);
        $computed_MAC = $hmac->computeHmac($MAC_source);

        if ($computed_MAC !== strtolower($data['MAC'])) {
            $this->sendToLog('CMCIC - Bad HMAC', 3);

            return;
        }
        switch ($data['code-retour']) {
            case 'Annulation':
                $order_status = Configuration::get('PS_OS_ERROR');
                foreach ($data as $key => $value) {
                    $order_message .= ' - ' . $key . ': ' . $value . '<br />' . "\n";
                }
                break;

            case 'payetest':
		    $order_status = Configuration::get('PS_OS_PAYMENT');

        $this->sendToLog('CMCIC - PayeTest', 1);
                $order_message = 'NOTICE: This is a test, nothing has really been paid';
                foreach ($data as $key => $value) {
                    $order_message .= ' - ' . $key . ': ' . $value . '<br />' . "\n";
		}
                break;

            case 'paiement':
                $order_status = Configuration::get('PS_OS_PAYMENT');
                foreach ($data as $key => $value) {
                    $order_message .= ' - ' . $key . ': ' . $value . '<br />' . "\n";
                }
                break;
	}
	
        $receipt = CMCIC_CGI2_MACOK;

        $this->sendToLog('CMCIC - Receipt: '.$receipt, 1);
        $this->sendToLog('CMCIC - CMCGI2 receipt: '.CMCIC_CGI2_RECEIPT, 1);
        $id_currency = (int) Currency::getIdByIsoCode(Tools::substr($data['montant'], -3));
        $amount = ($order_status == Configuration::get('PS_OS_PAYMENT') ? Tools::substr($data['montant'], 0, -3) : 0);

        if (($order_status != Configuration::get('PS_OS_ERROR') || $cmcic_error_behavior === 1 || $cmcic_error_behavior === 3) && !empty($data)) {
            $id_order = (int) Order::getOrderByCartId((int) $reference);

        	$this->sendToLog('CMCIC - Order ID '.$id_order, 1);
            if (0 !== $id_order) {
                $order = new Order((int) $id_order);
                $order->total_paid_real = $amount;
                $order->update();

                $history = new OrderHistory();
                $history->id_order = (int) $id_order;
                $history->changeIdOrderState((int) $order_status, (int) $id_order);
                $history->addWithemail();

                /** @var OrderPayment[] $order_payments */
                $order_payments = $order->getOrderPaymentCollection()->where('amount', '=', $amount)->getAll();

                foreach ($order_payments as $order_payment) {
                    if (Validate::isLoadedObject($order_payment)) {
                        if ($order_payment->transaction_id !== $data['reference']) {
                            $order_payment->transaction_id = $data['reference'];
                            $order_payment->update();
                        }
                    }
                }

        	$this->sendToLog('CMCIC - order message'. $order_message, 1);
                $message = new Message();
                $message->message = $order_message;
                $message->id_order = (int) $order->id;
                $message->private = true;
                $message->add();
            } elseif ((int) $reference && ($order_status === Configuration::get('PS_OS_PAYMENT') ||
            ($order_status === Configuration::get('PS_OS_ERROR') && ($cmcic_error_behavior === 1 || $cmcic_error_behavior === 3)))) {
                $cart = new Cart((int) $reference);
		$customer = new Customer((int) $cart->id_customer);

		$this->sendToLog('CMCIC - validate order '. $cart->id_customer. ' ' . $reference .' '. $order_status .' '. $amount .' '.$data['reference']. ' '.$customer->secure_key, 1);
                $this->module->validateOrder(
                    (int) $reference,
                    (int) $order_status,
                    $amount,
                    $this->module->displayName,
                    $order_message,
                    array('transaction_id' => $data['reference']),
                    (int) $id_currency,
                    true,
                    $customer->secure_key
		);
		$this->sendToLog('CMCIC - Order validated', 1);
            }
	}

        $this->sendToLog('CMCIC - order end', 1);
        if ($order_status === Configuration::get('PS_OS_ERROR')) {
            if ($cmcic_error_behavior === 2 || $cmcic_error_behavior === 3) {
                $this->module->sendErrorEmail($order_message);
            }
        }
        printf(CMCIC_CGI2_RECEIPT, $receipt);
    }

    /**
     * Check all keys we're going to use from the array are defined
     *
     * @return array Empty if all requirements are met
     */
    private function getMissingRequestedValues(array $data)
    {
        $requestedKeys = array(
            'date',
            'montant',
            'reference',
            'texte-libre',
            'code-retour',
            'cvx',
            'vld',
            'brand',
            'MAC',
        );
        if (empty($data)) {
            return $requestedKeys;
        }

        $missingKeys = array();
        foreach ($requestedKeys as $key) {
            if (!isset($data[$key])) {
                $missingKeys[] = $key;
            }
        }

        return $missingKeys;
    }

    /**
     * Send a message to the PrestaShop logger. Class to call is not the same between two major versions.
     *
     * @param string $message
     * @param int $severity
     */
    private function sendToLog($message, $severity)
    {
        // PrestaShop 1.6 and above
        if (class_exists('PrestaShopLogger')) {
            PrestaShopLogger::addLog($message, $severity, null, null, null, true);
        } elseif (class_exists('Logger')) {
            Logger::addLog($message, $severity, null, null, null, true);
        }
    }

    /**
     * Override displayMaintenancePage to prevent the maintenance page to be displayed
     *
     * @see FrontController::displayMaintenancePage()
     */
    protected function displayMaintenancePage()
    {
        return;
    }

    /**
     * Override displayRestrictedCountryPage to prevent page country is not allowed
     *
     * @see FrontController::displayRestrictedCountryPage()
     */
    protected function displayRestrictedCountryPage()
    {
        return;
    }

    /**
     * Override geolocationManagement to prevent country GEOIP blocking
     *
     * @see FrontController::geolocationManagement()
     *
     * @param Country $defaultCountry
     *
     * @return false
     */
    protected function geolocationManagement($defaultCountry)
    {
        return false;
    }

    /**
     * Override sslRedirection to prevent redirection
     *
     * @see FrontController::sslRedirection()
     */
    protected function sslRedirection()
    {
        return;
    }

    /**
     * Override canonicalRedirection to prevent redirection
     *
     * @see FrontController::canonicalRedirection()
     *
     * @param string $canonical_url
     */
    protected function canonicalRedirection($canonical_url = '')
    {
        return;
    }
}
