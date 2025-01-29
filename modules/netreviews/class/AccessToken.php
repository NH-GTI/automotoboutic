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
class AccessToken
{
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
    private $fgUrl;

    /**
     * @var string
     */
    private $fgDomaine;

    /**
     * @var string
     */
    private $avClientId;

    /**
     * @var string
     */
    private $avSecretApi;

    /**
     * Error array with status code and message.
     *
     * @var array<array<int, string>>
     */
    private $errors;

    /**
     * @param int|string|null $shopId
     * @param string $groupName
     */
    public function __construct($shopId, $groupName)
    {
        $fgUrl = ConfSkeepers::getInstance()->getEnv('SKEEPERS_SSO_TOKEN');
        if (!is_string($fgUrl)) {
            $this->errors = [[-2 => 'Error on AccessToken::__construct() : Not SKEEPERS_SSO_TOKEN']];
        } else {
            $this->fgUrl = $fgUrl;
        }
        $fgDomaine = ConfSkeepers::getInstance()->getEnv('SKEEPERS_SSO');
        if (!is_string($fgDomaine)) {
            $this->errors = [[-2 => 'Error on AccessToken::__construct() : Not SKEEPERS_SSO']];
        } else {
            $this->fgDomaine = $fgDomaine;
        }

        $this->shopId = $shopId;
        $this->groupName = $groupName;
        $this->errors = [];
        $avClientId = Configuration::get('AV_CLIENTID' . $this->groupName, null, null, (int) $this->shopId);
        if (!empty($avClientId)) {
            $this->avClientId = $avClientId;
        }
        $avSecretApi = Configuration::get('AV_SECRETAPI' . $this->groupName, null, null, (int) $this->shopId);
        if (!empty($avSecretApi)) {
            $this->avSecretApi = $avSecretApi;
        }
    }

    /**
     * return the FG access token json.
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        if (empty($this->avClientId) || empty($this->avSecretApi)
            || empty($this->fgDomaine) || empty($this->fgUrl)) {
            $this->errors = [[-2 => 'Error on AccessToken::getAccessToken() : Not AV_SECRETAPI or AV_CLIENTID']];
        } else {
            try {
                $ccurl = (new CurlCapsule($this->fgUrl, [
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => 'grant_type=client_credentials&scope=openid',
                    CURLOPT_USERPWD => $this->avClientId . ':' . $this->avSecretApi,
                ], [
                    'Content-Type: application/x-www-form-urlencoded',
                    'Host: ' . $this->fgDomaine,
                ]))->addDefaultOpt();
                $response = $ccurl->sendRequest();

                $code = -2;
                if (!is_null($ccurl->getResponse()) && isset($ccurl->getResponse()['code'])) {
                    $code = $ccurl->getResponse()['code'];
                }
                if (false !== $response && is_string($response) && $code === 200) {
                    return $response;
                } else {
                    $this->errors = array_merge([[-2 => 'Error on AccessToken::getAccessToken() : Wrong response code '
                        . $code]],
                        $ccurl->getErrors());
                }
            } catch (Exception $e) {
                $this->errors = [[-2 => 'Error on AccessToken::getAccessToken() : ' . $e->getMessage()]];
            } catch (Throwable $e) {
                $this->errors = [[-2 => 'Error on AccessToken::getAccessToken() : ' . $e->getMessage()]];
            }
        }

        return null;
    }

    /**
     * decode the FG json response
     *
     * @return string|null
     */
    public function getDecodedAccessToken()
    {
        $tokenResponse = $this->getAccessToken();
        if (empty($this->errors)) {
            if (is_string($tokenResponse)) {
                $tokenResponse = json_decode($tokenResponse, true);
            }
            if (false === $tokenResponse || !is_array($tokenResponse) || !array_key_exists('access_token', $tokenResponse)) {
                $this->errors = [[-2 => 'Error on AccessToken::getDecodedAccessToken() : Invalid json response']];
                $tokenResponse = null;
            } else {
                $tokenResponse = $tokenResponse['access_token'];
            }
        }

        return $tokenResponse;
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
