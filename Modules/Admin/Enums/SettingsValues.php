<?php

namespace Modules\Admin\Enums;

use App\Enums\SettingsValues as AppSettingsValues;
use BenSampo\Enum\Enum;

final class SettingsValues extends Enum
{
    const INVENTORY_ANDROID_FORCE_UPDATE_VERSION = AppSettingsValues::INVENTORY_ANDROID_FORCE_UPDATE_VERSION;
    const INVENTORY_IOS_FORCE_UPDATE_VERSION = AppSettingsValues::INVENTORY_IOS_FORCE_UPDATE_VERSION;
    const PICKER_ANDROID_FORCE_UPDATE_VERSION = AppSettingsValues::PICKER_ANDROID_FORCE_UPDATE_VERSION;
    const PICKER_IOS_FORCE_UPDATE_VERSION = AppSettingsValues::PICKER_IOS_FORCE_UPDATE_VERSION;
}
