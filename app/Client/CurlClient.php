<?php

/**
 * Class CurlClient
 *
 * A simple client, based on cURL functions, to perform REST requests against
 * an API.
 *
 */

class CurlClient
{
    /**
     * @var resource $curlHandle the cURL resource handle
     */
    protected $curlHandle;

    /**
     * @var string $apiKey Your API key
     */
    protected $apiKey;

    /**
     * @var string $baseUrl API base URL
     */
    protected $baseUrl;

    /**
     * @var array $requestResponse response from API
     */
    protected $requestResponse;

    /**
     * @var array $requestInfo cURL request info
     */
    protected $requestInfo;

    /**
     * CurlClient constructor.
     *
     * @param string $apiKey you API key
     * @param string $baseUrl API base URL
     */
    public function __construct(string $apiKey, string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }

    /**
     * Set the API key
     *
     * @param string $apiKey your API key
     * @return CurlClient
     */
    public function setApiKey(string $apiKey) : CurlClient
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Set the base URL
     *
     * @param string $baseUrl API base URL
     * @return CurlClient
     */
    public function setBaseUrl(string $baseUrl) : CurlClient
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Make a request to the API
     *
     * @param string $endpoint API endpoint
     * @return CurlClient
     */
    public function request(string $endpoint) : CurlClient
    {
        $this->curlHandle = curl_init();

        $url = sprintf(
            '%s%s',
            $this->baseUrl,
            $endpoint
        );

        $keyHeader = sprintf(
            'key: %s',
            $this->apiKey
        );

        curl_setopt($this->curlHandle, CURLOPT_URL, $url);
        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->curlHandle, CURLOPT_HEADER, FALSE);

        curl_setopt($this->curlHandle, CURLOPT_HTTPHEADER, [
            $keyHeader,
            "content-type: application/json",
        ]);

        $this->requestResponse = curl_exec($this->curlHandle);
        $this->requestInfo = curl_getinfo($this->curlHandle);
        curl_close($this->curlHandle);

        return $this;
    }

    /**
     * Make a GET request to the API
     *
     * @param string $endpoint API endpoint
     * @param array $requestParams additional request parameters
     * @return CurlClient
     */
    public function get(string $endpoint, array $requestParams = []) : CurlClient
    {
        $endpoint = sprintf(
            '%s?%s',
            $endpoint,
            http_build_query($requestParams)
        );

        return $this->request($endpoint);
    }

    /**
     * Make a POST
     * @param string $endpoint
     * @param array $requestParams
     * @return CurlClient
     */
    public function post(string $endpoint, array $requestParams = []) : CurlClient
    {
        curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $requestParams);
        curl_setopt($this->curlHandle, CURLOPT_POST, true);

        return $this->request($endpoint);
    }

    /**
     * Get the API response
     * @return array
     */
    public function getResponse() : array
    {
        $responseData = [];

        if ($this->requestResponse !== null) {
            try {
                $responseData = json_decode($this->requestResponse, true);
            } catch (\Exception $e) {
                $responseData['errors'] = [
                    'invalid_data' => $e->getMessage()
                ];
            }
        } else {
            $responseData['errors'] = [
                'empty_result' => 'result is empty!',
            ];
        }

        return $responseData;
    }

    /**
     * Get cURL info for the last request
     *
     * @return array|null
     */
    public function getInfo()
    {
        return $this->requestInfo ?? null;
    }
}
