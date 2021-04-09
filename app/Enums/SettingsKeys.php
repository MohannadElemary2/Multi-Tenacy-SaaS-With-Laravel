<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SettingsKeys extends Enum
{
    const LOCALE = 'locale';
    const TIME_ZONE = 'time_zone';
    const INVENTORY_ANDROID_FORCE_UPDATE_VERSION = 'inventory_android_force_update_version';
    const INVENTORY_IOS_FORCE_UPDATE_VERSION = 'inventory_ios_force_update_version';
    const PICKER_ANDROID_FORCE_UPDATE_VERSION = 'picker_android_force_update_version';
    const PICKER_IOS_FORCE_UPDATE_VERSION = 'picker_ios_force_update_version';
}
