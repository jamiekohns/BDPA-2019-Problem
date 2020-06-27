<?php
declare(strict_types=1);

namespace App\RestRequest;

class RestRequest {
    protected $url;
    protected $verb;
    protected $requestBody;
    protected $requestLength;
    protected $username;
    protected $password;
    protected $responseBody;
    protected $responseInfo;
    protected $headers;
    protected $payload;
    protected $timeout;
    protected $additional_curl_opts;
    protected $curlError;
    protected $curlErrorNum;
    protected $curlTimeout;
    protected $acceptType;

    public function __construct($url = null, $verb = 'GET', $requestBody = null) {
        $this->url = $url;
        $this->verb = $verb;
        $this->requestBody = $requestBody;
        $this->requestLength = 0;
        $this->timeout = 5;
        $this->username = null;
        $this->password = null;
        $this->responseBody = null;
        $this->responseInfo = null;
        $this->headers = array();
        $this->additional_curl_opts = array();

        if ($this->requestBody !== null) {
            $this->buildPostBody();
        }
    }

    public function flush() {
        $this->requestBody = null;
        $this->requestLength = 0;
        $this->verb = 'GET';
        $this->responseBody = null;
        $this->responseInfo = null;
    }

    public function request() {
        $ch = curl_init();
        $this->setAuth($ch);

        foreach ($this->additional_curl_opts as $opt => $val) {
            if (!curl_setopt($ch, $opt, $val)) {
                throw new \Exception("Error: could not set curl_opt ($opt, $val)");
            }
        }

        try {
            switch (strtoupper($this->verb)) {
                case 'GET':
                    $this->executeGet($ch);
                    break;
                case 'POST':
                    $this->executePost($ch);
                    break;
                case 'PUT':
                    $this->executePut($ch);
                    break;
                case 'DELETE':
                    $this->executeDelete($ch);
                    break;
                default:
                    throw new \InvalidArgumentException(
                        'Current verb (' . $this->verb . ') is an invalid REST verb.'
                    );
            }

            $ret = $this->responseBody;
            $info = $this->responseInfo;

            if (stristr($info['content_type'], 'json')) {
                return json_decode($ret,true);
            }

            return $this->responseBody;
        } catch (\InvalidArgumentException $e) {
            curl_close($ch);
            throw $e;
        } catch (\Exception $e) {
            curl_close($ch);
            throw $e;
        }
    }

    public function buildPostBody($data = null) {
        if (isset($this->payload)) {
            $this->requestBody = $this->payload;
        } else {
            $data = ($data !== null) ? $data : $this->requestBody;

            if (!is_array($data)) {
                throw new \InvalidArgumentException('Invalid data input for postBody.  Array expected');
            }

            $data = http_build_query($data, '', '&');
            $this->requestBody = $data;
        }
    }

    public function setTimeout($timeout=5) {
        $this->curlTimeout = $timeout;
    }

    protected function executeGet($ch) {
        $this->doExecute($ch);
    }

    protected function executePost($ch) {
        if (!is_string($this->requestBody)) {
            $this->buildPostBody();
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->requestBody);
        curl_setopt($ch, CURLOPT_POST, 1);

        $this->doExecute($ch);
    }

    public function putData($requestBody) {
        $this->requestBody = $requestBody;
    }

    protected function executePut($ch) {
        if (!is_string($this->requestBody)) {
            $this->buildPostBody();
        }

        $this->requestLength = strlen($this->requestBody);

        $fh = fopen('php://memory', 'rw');
        fwrite($fh, $this->requestBody);
        rewind($fh);

        curl_setopt($ch, CURLOPT_INFILE, $fh);
        curl_setopt($ch, CURLOPT_INFILESIZE, $this->requestLength);
        curl_setopt($ch, CURLOPT_PUT, true);

        $this->doExecute($ch);

        fclose($fh);
    }

    protected function executeDelete($ch) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $this->doExecute($ch);
    }

    protected function doExecute(&$curlHandle) {
        $this->setCurlOpts($curlHandle);
        $this->responseBody = curl_exec($curlHandle);
        $this->responseInfo = curl_getinfo($curlHandle);
        $this->curlError = curl_error($curlHandle);
        $this->curlErrorNum = curl_errno($curlHandle);

        curl_close($curlHandle);
    }

    protected function setCurlOpts(&$curlHandle) {
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curlHandle, CURLOPT_URL, $this->url);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $this->headers);
    }

    public function setAuth(&$curlHandle) {
        curl_setopt($curlHandle, CURLOPT_CAPATH, '/etc/ssl/certs');

        if ($this->username !== null && $this->password !== null) {
            curl_setopt($curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
            curl_setopt($curlHandle, CURLOPT_USERPWD, $this->username . ':' . $this->password);
        }
    }

    public function headers ($header) {
        if (is_array($header)) {
            $this->headers = array_merge($this->headers, $header);
        }
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function authorization($auth) {
        $this->auth = $auth;
    }

    public function getAcceptType() {
        return $this->acceptType;
    }

    public function setAcceptType($acceptType) {
        $this->acceptType = $acceptType;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getResponseBody() {
        return $this->responseBody;
    }

    public function getResponseInfo() {
        return $this->responseInfo;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getVerb() {
        return $this->verb;
    }

    public function setVerb($verb) {
        $this->verb = $verb;
    }
}