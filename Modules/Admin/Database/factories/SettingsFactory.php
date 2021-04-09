<?php

/**
 * @var Factory $factory 
 */

use Modules\Admin\Entities\System\Settings;
use Modules\Admin\Enums\SettingsGroups;
use Modules\Admin\Enums\SettingsKeys;

$factory->define(
    Settings::class,
    function () {
        return [
            'key'  => SettingsKeys::PICKER_ANDROID_FORCE_UPDATE_VERSION,
            'value'         => '1',
            'group'         => SettingsGroups::PICKER_MOBILE_APP,
        ];
    }
);
