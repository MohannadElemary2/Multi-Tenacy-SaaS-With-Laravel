<?php

namespace App\Services\GoogleMaps\Services;

use App\Services\GoogleMaps\GoogleMapsAPIClientInterface;

abstract class GoogleMapsService
{

    protected $googleMapsAPIClient;
    protected $responseFormat;
    protected $language;
    protected $apiPath;


    public function __construct(GoogleMapsAPIClientInterface $googleMapsAPIClient)
    {
        $this->googleMapsAPIClient = $googleMapsAPIClient;
    }

    protected function setGoogleMapsAPIClient(GoogleMapsAPIClientInterface $googleMapsAPIClient)
    {
        $this->$googleMapsAPIClient = $googleMapsAPIClient;
        return $this;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    public function setResponseFormat($responseFormat)
    {
        $this->responseFormat = $responseFormat;
        return $this;
    }

    protected function requestHandler($params, $method = 'GET')
    {
        $url = $this->getServiceUrl();

        $response = $this->googleMapsAPIClient->request($url, $params, $method);
        $result = $response->getBody()->getContents();
        $result = json_decode($result, true);

        // Error Handler
        if (200 != $response->getStatusCode())
            return $result;
        // Error message Checker (200 situation form Google Maps API)
        elseif (isset($result['error_message']))
            return $result;

        // `results` parsing from Google Maps API, while pass parsing on error
        return  isset($result['results']) ? $result['results'] : $result;
    }

    protected function getServiceUrl()
    {
        $apiHost = $this->googleMapsAPIClient->getApiHost();
        $responseFormat = $this->responseFormat ?: config('googlemaps.response_format');
        return "{$apiHost}/{$this->apiPath}/{$responseFormat}";
    }

    public function setApiPath($apiPath)
    {
        $this->apiPath = $apiPath;
        return $this;
    }

    public function getApiPath()
    {
        return $this->apiPath;
    }
}
