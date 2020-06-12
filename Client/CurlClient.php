<?php


class CurlClient
{
    protected $curlHandle;

    protected $apiKey;
    protected $baseUrl;

    protected $requestResponse;
    protected $requestInfo;

    public function __construct(string $apiKey, string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;

        $this->curlHandle = curl_init();
    }

    public function setApiKey(string $apiKey) : CurlClient
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function setBaseUrl(string $baseUrl) : CurlClient
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function request(string $endpoint, array $requestParams = []) : CurlClient
    {
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

    public function getInfo()
    {
        return $this->requestInfo ?? null;
    }

    public function get(string $endpoint, array $requestParams = []) : CurlClient
    {
        return $this->request($endpoint, $requestParams);
    }

    public function post(string $endpoint, array $requestParams = []) : CurlClient
    {
        curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $requestParams);
        curl_setopt($this->curlHandle, CURLOPT_POST, true);

        return $this->request($endpoint, $requestParams);
    }
}