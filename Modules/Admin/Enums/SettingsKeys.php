<?php

namespace Modules\Admin\Enums;

use App\Enums\SettingsKeys as AppSettingsKeys;
use BenSampo\Enum\Enum;

final class SettingsKeys extends Enum
{
    const INVENTORY_ANDROID_FORCE_UPDATE_VERSION = AppSettingsKeys::INVENTORY_ANDROID_FORCE_UPDATE_VERSION;
    const INVENTORY_IOS_FORCE_UPDATE_VERSION = AppSettingsKeys::INVENTORY_IOS_FORCE_UPDATE_VERSION;
    const PICKER_ANDROID_FORCE_UPDATE_VERSION = AppSettingsKeys::PICKER_ANDROID_FORCE_UPDATE_VERSION;
    const PICKER_IOS_FORCE_UPDATE_VERSION = AppSettingsKeys::PICKER_IOS_FORCE_UPDATE_VERSION;
}
