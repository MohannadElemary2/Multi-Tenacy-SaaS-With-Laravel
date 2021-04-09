<?php

namespace Modules\UserManagementSystem\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Modules\UserManagementSystem\Enums\PredefinedRoles;
use Modules\UserManagementSystem\Entities\Client\Permission;
use Modules\UserManagementSystem\Entities\Client\Role;
use Spatie\Permission\PermissionRegistrar;

class SeedRolesTableSeeder extends Seeder
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

        // update or create predefined Roles
        foreach (PredefinedRoles::getValues() as  $predefinedRoles) {
            $role = Role::whereTranslation('name', $predefinedRoles['role']['en']['name'])->first();

            if (!$role) {
                $role =  Role::create($predefinedRoles['role']);
            } else {
                $role->update($predefinedRoles['role']);
            }

            $permissionsNames = array_map(fn ($permission) => $permission['name'], $predefinedRoles['permissions']);
            $role->permissions()->sync(Permission::whereIn('name', $permissionsNames)->pluck('id')
                ->toArray());
        }



        if (!App::runningUnitTests()) {
            DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
