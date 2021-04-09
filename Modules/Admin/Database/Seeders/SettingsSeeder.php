<?php

namespace Modules\Admin\Database\Seeders;


use Illuminate\Database\Seeder;
use Modules\Admin\Entities\System\Settings;
use Modules\Admin\Enums\SettingsGroups;
use Modules\Admin\Enums\SettingsKeys;
use Modules\Admin\Enums\SettingsValues;

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
            'key' => SettingsKeys::INVENTORY_ANDROID_FORCE_UPDATE_VERSION,
        ], [
            'group' => SettingsGroups::INVENTORY_MOBILE_APP,
            'value' => SettingsValues::INVENTORY_ANDROID_FORCE_UPDATE_VERSION
        ]);

        Settings::firstOrCreate([
            'key' => SettingsKeys::INVENTORY_IOS_FORCE_UPDATE_VERSION,
        ], [
            'group' => SettingsGroups::INVENTORY_MOBILE_APP,
            'value' => SettingsValues::INVENTORY_IOS_FORCE_UPDATE_VERSION
        ]);

        Settings::firstOrCreate([
            'key' => SettingsKeys::PICKER_ANDROID_FORCE_UPDATE_VERSION,
        ], [
            'group' => SettingsGroups::PICKER_MOBILE_APP,
            'value' => SettingsValues::PICKER_ANDROID_FORCE_UPDATE_VERSION
        ]);

        Settings::firstOrCreate([
            'key' => SettingsKeys::PICKER_IOS_FORCE_UPDATE_VERSION,
        ], [
            'group' => SettingsGroups::PICKER_MOBILE_APP,
            'value' => SettingsValues::PICKER_IOS_FORCE_UPDATE_VERSION
        ]);
    }
}
