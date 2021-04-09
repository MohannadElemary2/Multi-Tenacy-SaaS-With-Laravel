<?php

namespace Modules\Client\Enums;

use BenSampo\Enum\Enum;
use App\Enums\SettingsValues as AppSettingsValues;

final class SettingsValues extends Enum
{
    const LOCALE = AppSettingsValues::LOCALE;
    const TIME_ZONE = AppSettingsValues::TIME_ZONE;
}
