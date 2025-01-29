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
 * @version   Release: $Revision: 9.0.0
 *
 * @date      22/08/2024
 * International Registered Trademark & Property of NetReviews SAS
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'netreviews/netreviews.php';

class NetreviewsAjaxRetroactiveOrderModuleFrontController extends ModuleFrontController
{
    public const RETURN_ERROR_CODE_PARAMETER = 0;
    public const RETURN_ERROR_CODE_SETTING = 1;
    public const RETURN_ERROR_CODE_APICO = 3;

    /** @var int|string|null */
    private $idShop;

    /** @var string|null */
    private $groupName;

    /**
     * Will do the main treatment et exit the response
     *
     * @return void
     */
    public function initContent()
    {
        parent::initContent();
        exit($this->prepareAndSendData());
    }

    /**
     * Do The main treatment To be tested
     *
     * @return string|bool
     */
    public function prepareAndSendData()
    {
        $ret = [
            'success' => false,
            'code' => self::RETURN_ERROR_CODE_PARAMETER,
            'message' => 'Not Ready, needs setted Up',
        ];

        $websiteIdInput = Tools::getValue('website-id', null);

        $allWebsite = InternalConfigManager::getAVShopWithWebsiteId();
        $found = false;
        $this->idShop = null;
        $this->groupName = null;
        if (is_array($allWebsite)) {
            foreach ($allWebsite as $websiteDb => $data) {
                if ($websiteDb === $websiteIdInput) {
                    $this->idShop = (int) $data['idShop'];
                    $this->groupName = (string) $data['groupName'];
                    $found = true;
                    break;
                }
            }
        }

        $this->groupName = (is_string($this->groupName) ? $this->groupName : '');
        $maxIteration = Tools::getValue('max-iteration');
        $currentIteration = Tools::getValue('cur-iteration');
        $ret['iteration'] = $currentIteration;
        $ret['maxIteration'] = $maxIteration;
        if (empty($maxIteration) || !$found) {
            return json_encode($ret);
        }
        $avReloaded = Configuration::get('AV_RELOADED' . $this->groupName, null, null, $this->idShop);
        $retroactiveStatus = (int) Configuration::get('AV_FIRST_RETROACTIVE' . $this->groupName, null, null, $this->idShop);
        $websiteId = Configuration::get('AV_IDWEBSITE' . $this->groupName, null, null, $this->idShop);

        $allowedProducts = Configuration::get(
            'AV_GETPRODREVIEWS' . $this->groupName,
            null,
            null,
            $this->idShop
        );

        $avActivateCollect = Configuration::get(
            'AV_ACTIVATE_COLLECT' . $this->groupName,
            null,
            null,
            $this->idShop
        );

        $purchaseEventType = Netreviews::getPurchaseEventType($allowedProducts, $avActivateCollect);
        if (empty($purchaseEventType) || empty($websiteId) || empty($avReloaded) || $retroactiveStatus == 1) {
            $ret['code'] = self::RETURN_ERROR_CODE_SETTING;
            $ret['message'] = 'Needs to SetUp First';

            return json_encode($ret);
        }

        $todo = Tools::getValue('retroactive');

        if ($todo === 'order') {
            $dataCountLimit = (int) ConfSkeepers::getInstance()->getEnv('RETROACTIVE_ORDER_COUNT_LIMIT');
            if ($retroactiveStatus !== -1) {
                Configuration::updateValue('AV_FIRST_RETROACTIVE' . $this->groupName, '-1', false, null, $this->idShop);
            }

            $iterationSize = (int) ceil($dataCountLimit / $maxIteration);
            $ret = $this->sendOrdersToApi(
                $ret,
                $iterationSize,
                $currentIteration,
                $websiteId,
                $purchaseEventType
            );
        } elseif ($todo === 'ends') {
            Configuration::updateValue('AV_FIRST_RETROACTIVE' . $this->groupName, '1', false, null, $this->idShop);

            $ret = [
                'success' => true,
                'message' => 'Realy ended',
            ];
        }

        return json_encode($ret);
    }

    /**
     * @param array<string,mixed> $ret
     * @param int $iterationSize
     * @param mixed $currentIteration
     * @param string $websiteId
     * @param string $purchaseEventType
     *
     * @return array<string,mixed>
     */
    private function sendOrdersToApi($ret, $iterationSize, $currentIteration, $websiteId, $purchaseEventType)
    {
        $orderGetter = new OrdersPrestashopGetter($this->idShop, $this->groupName);

        $orderGetter->setProducts(true);
        $orderGetter->setLimit($iterationSize);
        $orderGetter->setOffset($currentIteration * $iterationSize);
        $orders = $orderGetter->getRawOrders();
        if (is_array($orders)) {
            $ordersPurchase = OrderPurchaseFormatter::formatToPurchase($orders, $websiteId, $purchaseEventType, true);
        } else {
            $ordersPurchase = [];
        }

        $apiConnectors = new ApiConnectorsCaller($this->idShop, $this->groupName, $websiteId);
        $retApiCo = $apiConnectors->sendOrders($ordersPurchase, true);
        if (!empty($retApiCo) && is_array($retApiCo) && array_key_exists('status', $retApiCo)) {
            if ($retApiCo['status'] == 0) {
                LogsHandler::addLog(
                    'CURL AjaxRetroactiveOrder::sendOrders - idWebsite ' . $websiteId . ' - idShop ' .
                    $this->idShop . ' - responseCode (' . $retApiCo['status'] . ') for order references ('
                    . print_r($retApiCo['ordersReference'], true) . ')'
                );
                $ret['success'] = true;
                $ret['orders'] = $retApiCo['ordersReference'];
                if (isset($retApiCo['response'])) {
                    $ret['response'] = $retApiCo['response'];
                } else {
                    $ret['response'] = null;
                }
                if (count($ordersPurchase) == 0) {
                    $ret['message'] = 'Nothing to send to api-connectors';
                } else {
                    $ret['message'] = 'Bulk sent to api-connectors';
                }
                $ret['message'] = 'Bulk sent to api-connectors';
            } else {
                $ret['message'] = 'Error on bulk send';
                $ret['code'] = self::RETURN_ERROR_CODE_APICO;
                LogsHandler::addLog(
                    'CURL AjaxRetroactiveOrder::sendOrders - idWebsite ' . $websiteId . ' - idShop ' .
                    $this->idShop . ' - responseCode (' . $retApiCo['status'] . ') for order references (' .
                    print_r($retApiCo['ordersReference'], true)
                    . ') message: ' . (is_array($retApiCo['error']) ? array_pop(array_reverse($retApiCo['error'])[0]) : 'No error message')
                );
            }
        }

        return $ret;
    }
}
