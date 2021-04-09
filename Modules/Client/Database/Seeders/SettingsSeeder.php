<?php

namespace Modules\Client\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Client\Entities\Client\Settings;
use Modules\Client\Enums\SettingsGroups;
use Modules\Client\Enums\SettingsKeys;
use Modules\Client\Enums\SettingsValues;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::firstOrCreate([
            'key' => SettingsKeys::LOCALE,
        ], [
            'group' => SettingsGroups::GENERAL,
            'value' => SettingsValues::LOCALE
        ]);

        Settings::firstOrCreate([
            'key' => SettingsKeys::TIME_ZONE,
        ], [
            'group' => SettingsGroups::GENERAL,
            'value' => SettingsValues::TIME_ZONE
        ]);
    }
}
