<?php

namespace App\Services\GoogleMaps;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Response;

interface GoogleMapsAPIClientInterface
{
    public function setHttpClient(HttpClient $httpClient = null): self;
    public function request($apiPath, $params = [], $method = 'GET', $body = null): Response;
    public function setLanguage($language = null): self;
    public function getApiHost(): string;
}
