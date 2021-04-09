<?php

/**
 * @var Factory $factory 
 */

use Modules\Client\Entities\Client\Settings;
use Modules\Client\Enums\SettingsGroups;
use Modules\Client\Enums\SettingsKeys;

$factory->define(
    Settings::class,
    function () {
        return [
            'key'  => SettingsKeys::LOCALE,
            'value'         => 'en',
            'group'         => SettingsGroups::GENERAL,
        ];
    }
);
