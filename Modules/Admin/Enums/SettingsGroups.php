<?php

namespace Modules\Admin\Enums;

use App\Enums\SettingsGroups as AppSettingsGroups;
use BenSampo\Enum\Enum;

final class SettingsGroups extends Enum
{
    const INVENTORY_MOBILE_APP = AppSettingsGroups::INVENTORY_MOBILE_APP;
    const PICKER_MOBILE_APP = AppSettingsGroups::PICKER_MOBILE_APP;
}
