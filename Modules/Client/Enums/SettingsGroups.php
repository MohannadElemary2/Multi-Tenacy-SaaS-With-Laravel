<?php

namespace Modules\Client\Enums;

use App\Enums\SettingsGroups as AppSettingsGroups;
use BenSampo\Enum\Enum;

final class SettingsGroups extends Enum
{
    const GENERAL = AppSettingsGroups::GENERAL;
}
