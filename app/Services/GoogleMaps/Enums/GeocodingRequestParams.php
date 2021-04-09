<?php

namespace App\Services\GoogleMaps\Enums;

use BenSampo\Enum\Enum;

final class GeocodingRequestParams extends Enum
{
    const LANGUAGE = 'language';
    const REGION = 'region';
    const PLACE_ID = 'place_id';
    const LATLNG = 'latlng';
    const ADDRESS = 'address';
}
