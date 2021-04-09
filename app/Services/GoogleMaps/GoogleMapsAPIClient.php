<?php

namespace App\Services\GoogleMaps;

use App\Services\GoogleMaps\Enums\GoogleMapsRequestParams;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Response;

class GoogleMapsAPIClient implements GoogleMapsAPIClientInterface
{
    /**
     * Google Maps Platform base API host
     */
    private $apiHost;

    /**
     * Google API Key
     * 
     * Authenticating by API Key, otherwise by client ID/digital signature
     *
     * @var string
     */
    private $apiKey;

    /**
     * GuzzleHttp\Client
     *
     * @var GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * Google Maps default language
     *
     * @var string ex. 'en'
     */
    protected $language;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->apiHost = config('googlemaps.api_host');
        $this->language = config('googlemaps.default_language');
        $this->apiKey = config('googlemaps.api_key');
        // Set HttpClient
        $this->setHttpClient();
    }

    public function setHttpClient(HttpClient $httpClient = null): self
    {
        $this->httpClient = $httpClient ??
            $this->httpClient = new HttpClient([
                'base_uri' => $this->apiHost,
                'timeout'  => config('googlemaps.request_time_out')
            ]);
        return $this;
    }

    /**
     * Request Google Map API
     *
     * @param string $apiPath
     * @param array $params
     * @param string $method HTTP request method
     * @param string $body
     * @return GuzzleHttp\Psr7\Response
     */
    public function request($apiPath, $params = [], $method = 'GET', $body = null): Response
    {
        // Guzzle request options
        $options = [
            'http_errors' => false,
        ];

        // Parameters for Auth
        $defaultParams = $this->getDefaultParams();

        // Query
        $options['query'] = array_merge($defaultParams, $params);

        // Body
        if ($body) {
            $options['body'] = $body;
        }
        return $this->httpClient->request($method, $apiPath, $options);
    }

    private function getDefaultParams()
    {
        return [
            GoogleMapsRequestParams::LANGUAGE => $this->language ?: config('googlemaps.default_language'),
            GoogleMapsRequestParams::KEY => $this->apiKey
        ];
    }

    /**
     * Set default language for Google Maps API
     *
     * @param string $language ex. 'en'
     * @return self
     */
    public function setLanguage($language = null): self
    {
        $this->language = $language ?: config('googlemaps.default_language');

        return $this;
    }


    /**
     * Get  Google Maps API Host url
     *
     * @return string
     */
    public function getApiHost(): string
    {
        return $this->apiHost;
    }
}
