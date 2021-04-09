<?php

namespace App\Services\GoogleMaps\Enums;

use BenSampo\Enum\Enum;

final class GoogleMapsRequestParams extends Enum
{
    const CLIENT = 'client';
    const SIGNATURE = 'signature';
    const KEY = 'key';
    const LANGUAGE = 'language';
}
