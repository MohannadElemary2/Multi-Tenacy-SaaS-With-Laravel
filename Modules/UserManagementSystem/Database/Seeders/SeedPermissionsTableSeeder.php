<?php

namespace Modules\UserManagementSystem\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Modules\UserManagementSystem\Entities\Client\Permission;
use Spatie\Permission\PermissionRegistrar;

class SeedPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seed To Add The Required Permissions.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Reset cached roles and permissions
        if (!App::runningUnitTests()) {
            DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=0;');
            app()[PermissionRegistrar::class]->forgetCachedPermissions();
        }

        Permission::truncate();

        // create permissions
        Permission::insert(
            [
                ['name' => 'add_roles', 'guard_name' => 'client-users-api'],
                ['name' => 'view_roles', 'guard_name' => 'client-users-api'],
                ['name' => 'edit_roles', 'guard_name' => 'client-users-api'],
                ['name' => 'delete_roles', 'guard_name' => 'client-users-api'],
                ['name' => 'add_users', 'guard_name' => 'client-users-api'],
                ['name' => 'view_users', 'guard_name' => 'client-users-api'],
                ['name' => 'edit_users', 'guard_name' => 'client-users-api'],
                ['name' => 'delete_users', 'guard_name' => 'client-users-api'],
                ['name' => 'editLocale_settings', 'guard_name' => 'client-users-api'],
                ['name' => 'view_salesChannels', 'guard_name' => 'client-users-api'],
                ['name' => 'integrate_salesChannels', 'guard_name' => 'client-users-api'],
                ['name' => 'view_categories', 'guard_name' => 'client-users-api'],
                ['name' => 'sync_categories', 'guard_name' => 'client-users-api'],
                ['name' => 'view_attributes', 'guard_name' => 'client-users-api'],
                ['name' => 'sync_attributes', 'guard_name' => 'client-users-api'],
                ['name' => 'view_product', 'guard_name' => 'client-users-api'],
                ['name' => 'sync_product', 'guard_name' => 'client-users-api'],
                ['name' => 'export_product', 'guard_name' => 'client-users-api'],
                ['name' => 'add_hubs', 'guard_name' => 'client-users-api'],
                ['name' => 'view_hubs', 'guard_name' => 'client-users-api'],
                ['name' => 'edit_hubs', 'guard_name' => 'client-users-api'],
                ['name' => 'delete_hubs', 'guard_name' => 'client-users-api'],
                ['name' => 'activate_hubs', 'guard_name' => 'client-users-api'],
                ['name' => 'add_hubLocations', 'guard_name' => 'client-users-api'],
                ['name' => 'view_hubLocations', 'guard_name' => 'client-users-api'],
                ['name' => 'edit_hubLocations', 'guard_name' => 'client-users-api'],
                ['name' => 'delete_hubLocations', 'guard_name' => 'client-users-api'],
                ['name' => 'sort_hubLocations', 'guard_name' => 'client-users-api'],
                ['name' => 'export_hubLocations', 'guard_name' => 'client-users-api'],
                ['name' => 'import_hubLocations', 'guard_name' => 'client-users-api'],
                ['name' => 'viewQuantities_inventory', 'guard_name' => 'client-users-api'],
                ['name' => 'exportQuantities_inventory', 'guard_name' => 'client-users-api'],
                ['name' => 'manageQuantities_inventory', 'guard_name' => 'client-users-api'],
                ['name' => 'manageInventoryAsAudit_audit', 'guard_name' => 'client-users-api'],
                ['name' => 'viewProductsBuffer_inventory', 'guard_name' => 'client-users-api'],
                ['name' => 'exportProductsBuffer_inventory', 'guard_name' => 'client-users-api'],
                ['name' => 'editBuffer_hubs', 'guard_name' => 'client-users-api'],
                ['name' => 'moveQuantities_inventory', 'guard_name' => 'client-users-api'],
                ['name' => 'editProductsBuffer_inventory', 'guard_name' => 'client-users-api'],
                ['name' => 'importProductsBuffer_inventory', 'guard_name' => 'client-users-api'],
                ['name' => 'editHubs_salesChannels', 'guard_name' => 'client-users-api'],
                ['name' => 'viewOnline_orders', 'guard_name' => 'client-users-api'],
                ['name' => 'viewOffline_orders', 'guard_name' => 'client-users-api'],
                ['name' => 'view_carts', 'guard_name' => 'client-users-api'],
                ['name' => 'add_carts', 'guard_name' => 'client-users-api'],
                ['name' => 'edit_carts', 'guard_name' => 'client-users-api'],
                ['name' => 'delete_carts', 'guard_name' => 'client-users-api'],
                ['name' => 'activate_carts', 'guard_name' => 'client-users-api'],
                ['name' => 'view_dispatchingStations', 'guard_name' => 'client-users-api'],
                ['name' => 'add_dispatchingStations', 'guard_name' => 'client-users-api'],
                ['name' => 'edit_dispatchingStations', 'guard_name' => 'client-users-api'],
                ['name' => 'viewShippingMethods_settings', 'guard_name' => 'client-users-api'],
                ['name' => 'integrateShippingMethods_settings', 'guard_name' => 'client-users-api'],
                ['name' => 'editConfiguration_salesChannels', 'guard_name' => 'client-users-api'],
                ['name' => 'connectShippingMethods_settings', 'guard_name' => 'client-users-api'],
                ['name' => 'picker_picking', 'guard_name' => 'client-users-api'],
                ['name' => 'dispatcher_dispatching', 'guard_name' => 'client-users-api'],
                ['name' => 'viewPosIntegrations_settings', 'guard_name' => 'client-users-api'],

                // ['name' => 'editTimeZone_settings', 'guard_name' => 'client-users-api'], // hidden temporarily
            ]
        );

        if (!App::runningUnitTests()) {
            DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
