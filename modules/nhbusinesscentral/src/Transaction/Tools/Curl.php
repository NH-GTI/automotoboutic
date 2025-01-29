<?php

namespace InstanWeb\Module\NHBusinessCentral\Transaction\Tools;

class Curl
{
    const REQUEST_GET = 1;
    const REQUEST_POST = 2;
    const REQUEST_PUT = 3;
    const REQUEST_PATCH = 4;
    const REQUEST_DELETE = 5;

    const CONTENT_JSON = 1;

    private $debug = false;
    private $verbose = "";
    private $request;
    private $url;
    private $options = [];
    private $contentType;
    private $resultToObject = false;
    private $data;
    private array $headers = [];
    private array $acceptHttpCode = [200];
    private $timeout = 5000;
    private $result;
    private $resultHttpCode;
    private $parseXmlResponse;

    public function setDebug($bool)
    {
        $this->debug = $bool;
        return $this;
    }

    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    public function setHeader($header)
    {
        if (is_array($header)) {
            $this->headers = array_merge($this->headers, $header);
        } else {
            $this->headers[] = $header;
        }
        return $this;
    }

    public function setContentType($type)
    {
        $this->contentType = $type;
        if ($type == self::CONTENT_JSON) {
            $this->headers[] = 'content-type: application/json';
        }
        return $this;
    }

    public function setParseXmlResponse($bool)
    {
        $this->parseXmlResponse = $bool;
        return $this;
    }

    public function setResultToObject($bool)
    {
        $this->resultToObject = $bool;
        return $this;
    }

    public function clearHeader()
    {
        $this->headers = [];
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function setParam($param)
    {
        if (substr($this->url, -1) != '/') {
            $this->url .= '/';
        }
        $this->url .= $param;
        return $this;
    }

    public function setOption($option)
    {
        foreach($option as $k => $v) {
            $this->options[$k] = $v;
        }
        return $this;
    }

    public function clearOption()
    {
        $this->options = [];
        return $this;
    }

    public function setData($data)
    {
        if (is_array($data)) {
            if (is_array($this->data) == false) {
                $this->data = [];
            }
            $this->data = array_merge($this->data, $data);
        } else {
            $this->data = $data;
        }
        return $this;
    }

    public function clearData()
    {
        unset($this->data);
        return $this;
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function setAcceptHttpCode($code)
    {
        if (is_array($code)) {
            $this->acceptHttpCode = array_merge($this->acceptHttpCode, $code);
        } else {
            $this->acceptHttpCode[] = $code;
        }
        return $this;
    }

    public function clearAcceptHttpCode()
    {
        $this->acceptHttpCode = [];
        return $this;
    }

    protected function parseXML($response)
    {
        if ($response != '') {
            libxml_clear_errors();
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string(trim($response), 'SimpleXMLElement', LIBXML_NOCDATA);
            if (libxml_get_errors()) {
                $msg = var_export(libxml_get_errors(), true);
                libxml_clear_errors();
                throw new \Exception('HTTP XML response is not parsable: ' . $msg.' '.var_export($response,true));
            }
            return $xml;
        } else {
            throw new \Exception('HTTP response is empty');
        }
    }

    public function getResultHttpCode()
    {
        return $this->resultHttpCode;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getVerbose()
    {
        return $this->verbose;
    }

    public function isResultHttpCodeAccepted()
    {
        return (in_array($this->resultHttpCode, $this->acceptHttpCode));
    }

    public function call()
    {
        $ch = curl_init($this->url);

        if ($this->headers) {
            $this->setOption([CURLOPT_HTTPHEADER => $this->headers]);
        }
        $this->setOption([CURLOPT_TIMEOUT_MS => $this->timeout]);

        if ($this->request == self::REQUEST_POST) {
            $this->setOption([CURLOPT_CUSTOMREQUEST => 'POST']);
        }
        if ($this->request == self::REQUEST_PUT) {
            $this->setOption([CURLOPT_CUSTOMREQUEST => 'PUT']);
        }
        if ($this->request == self::REQUEST_DELETE) {
            $this->setOption([CURLOPT_CUSTOMREQUEST => 'DELETE']);
        }

        if ($this->request == self::REQUEST_POST || $this->request == self::REQUEST_PUT) {
            if ($this->contentType == self::CONTENT_JSON) {
                $data = json_encode($this->data);
            } else {
                $data = $this->data;
            }
            $this->setOption([CURLOPT_POSTFIELDS => $data]);
        }

        if ($this->debug) {
            $streamVerboseHandle = fopen('php://temp', 'w+');
            $this->setOption([CURLOPT_VERBOSE => true, CURLOPT_STDERR => $streamVerboseHandle]);
        }
        curl_setopt_array($ch, $this->options);

        $this->result = curl_exec($ch);
        $this->resultHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($this->debug) {
            rewind($streamVerboseHandle);
            $this->verbose = stream_get_contents($streamVerboseHandle);
        }

        if ($this->isResultHttpCodeAccepted()) {
            if ($this->contentType == self::CONTENT_JSON) {
                $this->result = json_decode($this->result, true);
            }
            if ($this->parseXmlResponse) {
                $index = strpos($this->result, "\r\n\r\n");
                $body = substr($this->result, $index + 4);
                $this->result = $this->parseXML($body);
            }
            if ($this->resultToObject) {
                $this->result = (object)$this->result;
            }
        } else {
            throw new \Exception(($this->debug ? $this->verbose : '').' '.$this->result);    
        }

        return $this;
    }
}
