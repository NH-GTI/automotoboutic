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

class CurlCapsule
{
    /**
     * @var string
     */
    private $url;

    /**
     * cURL options.
     *
     * @var array<int,array<string>|bool|int|string|null>
     */
    private $opt;

    /**
     * http headers.
     *
     * @var array<string>
     */
    private $headers;

    /**
     * Response from the last request.
     *
     * @var array<string,string|bool|int>|null
     */
    private $response;

    /**
     * Error array with status code and message.
     *
     * @var array<array<int,string>>
     */
    private $errors;

    /**
     * Undocumented function
     *
     * @param string $url
     * @param array<int,array<string>|bool|int|string|null> $opt
     * @param array<string> $headers
     */
    public function __construct($url, $opt = [], $headers = [])
    {
        $this->url = $url;
        $this->opt = $opt;
        $this->headers = $headers;
        $this->errors = [];
        $this->response = null;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * cURL options.
     *
     * @return array<int,array<string>|bool|int|string|null>
     */
    public function getOpt()
    {
        return $this->opt;
    }

    /**
     * cURL options.
     *
     * @param array<int,array<string>|bool|int|string|null> $opt
     *
     * @return self
     */
    public function setOpt($opt)
    {
        $this->opt = $opt;

        return $this;
    }

    /**
     * cURL options aggregation.
     *
     * @param array<int,string|int|bool|null> $opt
     *
     * @return self
     */
    public function addOpt($opt)
    {
        foreach ($opt as $key => $value) {
            $this->opt[$key] = $value;
        }

        return $this;
    }

    /**
     * http headers.
     *
     * @return array<string>
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * http headers.
     *
     * @param array<string> $headers
     *
     * @return self
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return self
     */
    public function addDefaultOpt()
    {
        $this->addOpt([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_TIMEOUT => 60,
        ]);

        return $this;
    }

    /**
     * @return string|bool
     */
    public function sendRequest()
    {
        $curl = curl_init($this->url);
        if ($curl === false) {
            $this->errors[] = [
                -1 => 'Error while initializing curl',
            ];
        } else {
            if (!empty($this->headers)) {
                $this->opt[CURLOPT_HTTPHEADER] = $this->headers;
            }

            if (curl_setopt_array($curl, $this->opt) === false) {
                $this->errors[] = [
                    -1 => 'Error while setting curl options',
                ];
            }

            $response = curl_exec($curl);
            if (is_string($response)) {
                $this->response['body'] = $response;
            } else {
                $this->errors[] = [
                    -1 => 'Error while executing curl',
                ];
                $this->response['body'] = false;
            }
            $this->response['code'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (($curlErrno = curl_errno($curl)) !== 0) {
                $this->errors[] = [$curlErrno => curl_error($curl)];
            }
            curl_close($curl);

            return $this->response['body'];
        }

        return false;
    }

    /**
     * @return array<string,bool|int|string|null>|null|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get the errors that occurred during the last operation.
     *
     * @return array<array<int,string>>
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param string $handler
     *
     * @return array<string,mixed>|null
     */
    public function handleSettingsResponse($handler)
    {
        $response = null;
        $code = -3;

        if (!is_null($this->getResponse())) {
            if (isset($this->getResponse()['body'])) {
                $response = $this->getResponse()['body'];
            }
            if (isset($this->getResponse()['code'])) {
                $code = $this->getResponse()['code'];
            }
        }
        if (false !== $response && is_string($response) && $code === 200) {
            $response = json_decode($response, true);
            if (!is_array($response)) {
                $this->errors = [[-3 => 'Error on ' . $handler . ' : Invalid json response']];
                $response = null;
            }
        } else {
            if (count($this->getErrors()) > 0) {
                $this->errors = array_unique(array_merge($this->errors, $this->getErrors()), SORT_REGULAR);
            } else {
                $this->errors = [[
                    -3 => 'Error on ' . $handler . ' : Curl: '
                        . $code . ' ' . $response]];
            }
            $response = null;
        }

        return $response;
    }
}
