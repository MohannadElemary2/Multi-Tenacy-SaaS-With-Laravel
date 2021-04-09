<?php

namespace Modules\UserManagementSystem\Enums;

use App\Enums\SettingsKeys as AppSettingsKeys;
use BenSampo\Enum\Enum;

final class SettingsKeys extends Enum
{
    const LOCALE = AppSettingsKeys::LOCALE;
    const TIME_ZONE = AppSettingsKeys::TIME_ZONE;
}
