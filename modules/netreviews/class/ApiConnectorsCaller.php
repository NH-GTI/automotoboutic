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

/**
 * This class represents the local configuration for the NetReviews module in PrestaShop.
 * It extends the ConfSkeepersProd class and provides specific environment values.
 */
class ApiConnectorsCaller
{
    /**
     * @var string|null
     */
    private $token;

    /**
     * @var string
     */
    private $apiBaseUrl;

    /**
     * @var string
     */
    private $websiteId;

    /**
     * @var string|int|null
     */
    private $shopId;

    /**
     * @var string
     */
    private $groupName;

    /**
     * @var string
     */
    private $avClientId;

    /**
     * @var bool
     */
    private $needGetToken;

    /**
     * @var AccessToken
     */
    private $tokenGetter;

    /**
     * Error array with status code and message.
     *
     * @var array<array<int, string>>
     */
    private $errors;

    public const URI_CONNECTOR_SETTINGS
        = '%sv2/connector-settings?website-id=%s&connector-type-id=1&release-version=%s';

    /**
     * @param int|string|null $shopId
     * @param string|null $groupName
     * @param string $websiteId
     */
    public function __construct($shopId, $groupName, $websiteId)
    {
        $this->errors = [];
        $this->websiteId = $websiteId;
        $this->shopId = $shopId;
        if (is_null($groupName)) {
            $groupName = '';
        }

        $this->groupName = $groupName;

        $apiBaseUrl = ConfSkeepers::getInstance()->getEnv('SKEEPERS_API_CONNECTORS');
        if (!is_string($apiBaseUrl)) {
            $this->errors = [[-3 => 'Error on ApiConnectorsCaller::__construct() : Not SKEEPERS_API_CONNECTORS']];
        } else {
            $this->apiBaseUrl = $apiBaseUrl;
        }
        try {
            $needGetToken = ConfSkeepers::getInstance()->getEnv('SKEEPERS_GET_TOKEN');
        } catch (Exception $e) {
            $needGetToken = true;
        }
        $this->needGetToken = !is_bool($needGetToken) || $needGetToken;

        if (empty($shopId)) {
            $groupName = InternalConfigManager::getGroupNameByWebsiteId($websiteId, $shopId);
        }
        $avClientId = Configuration::get('AV_CLIENTID' . $groupName, null, null, (int) $shopId);
        if (!empty($avClientId)) {
            $this->avClientId = $avClientId;
        }

        $this->tokenGetter = new AccessToken($this->shopId, $this->groupName);
    }

    /**
     * @param bool $force
     *
     * @return void
     */
    private function getAccessToken($force = false)
    {
        if (empty($this->token) || $force) {
            $this->token = $this->tokenGetter->getDecodedAccessToken();
            if (empty($this->token)) {
                $this->errors = $this->tokenGetter->getErrors();
            }
        }
    }

    /**
     * @param CurlCapsule $ccurl
     *
     * @return array<string, mixed>|null
     */
    private function handleSettingsResponse($ccurl)
    {
        $response = null;
        $code = -3;
        if (!is_null($ccurl->getResponse())) {
            if (isset($ccurl->getResponse()['body'])) {
                $response = $ccurl->getResponse()['body'];
            }
            if (isset($ccurl->getResponse()['code'])) {
                $code = $ccurl->getResponse()['code'];
            }
        }

        if (false !== $response && is_string($response) && $code === 200) {
            $response = json_decode($response, true);
            if (!is_array($response)) {
                $this->errors = [[
                    -3 => 'Error on ApiConnectorsCaller::handleSettingsResponse() : Invalid json response',
                ]];
                $response = null;
            }
        } else {
            if (count($ccurl->getErrors()) > 0) {
                $this->errors = array_merge($this->errors, $ccurl->getErrors());
            } else {
                $this->errors = [[
                    -3 => 'Error on ApiConnectorsCaller::handleSettingsResponse() : Curl: '
                    . $code . ' ' . $response]];
            }
            $response = null;
        }

        return $response;
    }

    /**
     * @param array<string,array<string,string>|string|null> $postData
     *
     * @return array<string,mixed>|null
     */
    public function getApiConf($postData = [])
    {
        $netreviews = new Netreviews();

        if ($this->needGetToken) {
            $this->getAccessToken();
        }
        if ((!empty($this->token) || !$this->needGetToken)
            && !empty($this->apiBaseUrl)) {
            $url = sprintf(
                self::URI_CONNECTOR_SETTINGS, $this->apiBaseUrl, $this->websiteId, $netreviews->version
            );
            $headers = [
                'Content-Type: application/json',
                'x-custom-loading-mode: API_PRESTASHOP',
            ];

            if (!empty($this->token)) {
                $headers[] = 'Authorization: Bearer ' . $this->token;
            }

            if (!empty($this->avClientId)) {
                $headers[] = 'client-id: ' . $this->avClientId;
            }
            try {
                $ccurl = (new CurlCapsule($url, [
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode(['remoteConfiguration' => $postData]),
                ], $headers))->addDefaultOpt();
                $ccurl->sendRequest();

                return $this->handleSettingsResponse($ccurl);
            } catch (Exception $e) {
                $this->errors = [[-3 => 'Error on ApiConnectorsCaller::getApiConf() : ' . $e->getMessage()]];
            } catch (Throwable $e) {
                $this->errors = [[-3 => 'Error on ApiConnectorsCaller::getApiConf() : ' . $e->getMessage()]];
            }
        }

        return null;
    }

    /**
     * Send Orders to the API co.
     *
     * @param array<mixed> $orderPurchaseFormat
     * @param bool $isRetroactiveOrder
     *
     * @return array<string,mixed>
     */
    public function sendOrders($orderPurchaseFormat, $isRetroactiveOrder = true)
    {
        if ($this->needGetToken) {
            $this->getAccessToken();
        }

        $ordersReference = [];
        if (!empty($orderPurchaseFormat) && is_array($orderPurchaseFormat)
            && array_key_exists('orderData', $orderPurchaseFormat[0])) {
            foreach ($orderPurchaseFormat as $element) {
                $ordersReference[] = $element['orderData']['purchase_reference'];
            }
        } else {
            foreach ($orderPurchaseFormat as $element) {
                $ordersReference[] = $element['purchase_reference'];
            }
        }

        $ret = [
            'status' => -2,
            'ordersReference' => $ordersReference,
        ];

        $endpointDbName = 'AV_PUSH_ORDERS_ENDPOINT';
        if ($isRetroactiveOrder) {
            $endpointDbName = 'AV_RETROACTIVE_ORDERS_ENDPOINT';
        }

        $urlApiConnector = Configuration::get($endpointDbName . $this->groupName, null, null, $this->shopId);

        if (empty($urlApiConnector)) {
            $ret['error'] = $this->errors = [[-3 => 'Error on ApiConnectorsCaller::sendOrders() : No AV_PUSH_ORDERS_ENDPOINT defined']];
        } else {
            if (empty($orderPurchaseFormat)) {
                $urlApiConnector .= '?noOrder=true';
            }

            $headers = [
                'Content-Type: application/json',
                'client-id: ' . $this->avClientId,
                'Authorization: Bearer ' . $this->token,
            ];
            $ordersJson = json_encode($orderPurchaseFormat, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $option = [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $ordersJson,
            ];
            $curlCapsule = new CurlCapsule($urlApiConnector, $option, $headers);
            try {
                $curlCapsule->sendRequest();
                $response = $curlCapsule->getResponse();
            } catch (Exception $e) {
                $this->errors = [[-3 => 'Error on ApiConnectorsCaller::sendOrders() : ' . $e->getMessage()]];
            } catch (Throwable $e) {
                $this->errors = [[-3 => 'Error on ApiConnectorsCaller::sendOrders() : ' . $e->getMessage()]];
            }
            if (empty($response)) {
                $ret['status'] = -1;
                $ret['error'] = $this->errors;
            } elseif ($response['code'] >= 200 && $response['code'] <= 299) {
                $ret['status'] = 0;
            } else {
                $ret['error'] = [[-3 => 'Error on ApiConnectorsCaller::sendOrders() Purchase call http code: ' . $response['code']
                    . ' response: ' . $response['body']]];
            }
        }

        return $ret;
    }

    /**
     * Get the errors that occurred during the last operation.
     *
     * @return array<array<int, string>>
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
