<?php

namespace App\Services\GoogleMaps\Services;

use App\Services\GoogleMaps\Enums\GeocodingRequestParams;
use App\Services\GoogleMaps\GoogleMapsAPIClientInterface;
use App\Services\GoogleMaps\GoogleMapsClient;

class GeocodingService extends GoogleMapsService
{

    public function __construct(GoogleMapsAPIClientInterface $googleMapsAPIClient)
    {
        $this->apiPath = config('googlemaps.geocode_endpoint');
        parent::__construct($googleMapsAPIClient);
    }

    /**
     * Add language to $params list if necessary
     *
     * @param array $params
     * @return array
     */
    public function prepareParams(array $params): array
    {

        if ($this->language) {
            $params[GeocodingRequestParams::LANGUAGE] = $this->language;
        }

        return $params;
    }

    /**
     * get Geocode by address
     *
     * @param string $address
     * @return array Result
     */
    public  function getByAddress($address, $region = null)
    {

        $params = [GeocodingRequestParams::ADDRESS =>  $address];

        if ($region)
            $params[GeocodingRequestParams::REGION] = $region;

        $params = $this->prepareParams($params);
        return $this->requestHandler($params);
    }

    /**
     * get Geocode by place id
     *
     * @param string $placeId
     * @return array Result
     */
    public  function getPlaceId($placeId)
    {
        $params = [GeocodingRequestParams::PLACE_ID =>  $placeId];

        $params = $this->prepareParams($params);
        return $this->requestHandler($params);
    }

    /**
     * Reverse Geocode
     *
     * @param GoogleMapsClient $client
     * @param array|string $latlng ['lat', 'lng'] or latlng string
     * @return array Result
     */
    public  function getByLatLng($latlng)
    {
        if (!is_array($latlng)) {
            list($lat, $lng) = $latlng;
            $params['latlng'] = "{$lat},{$lng}";
        }

        $params = [
            GeocodingRequestParams::LATLNG => $latlng
        ];

        $params = $this->prepareParams($params);
        return $this->requestHandler($params);
    }


    /**
     * Reverse Geocode
     *
     * @param GoogleMapsClient $client
     * @param array|string $latlng ['lat', 'lng'] or latlng string
     * @return array Result
     */
    public  function getReverse($latlng)
    {
        return $this->getByLatLng($latlng);
    }
}
