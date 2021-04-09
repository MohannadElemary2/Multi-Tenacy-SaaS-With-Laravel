<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SettingsValues extends Enum
{
    const LOCALE = 'en';
    const TIME_ZONE = 'Asia/Riyadh';
    const INVENTORY_ANDROID_FORCE_UPDATE_VERSION = 0;
    const INVENTORY_IOS_FORCE_UPDATE_VERSION = 0;
    const PICKER_ANDROID_FORCE_UPDATE_VERSION = 0;
    const PICKER_IOS_FORCE_UPDATE_VERSION = 0;
}
