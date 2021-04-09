<?php

namespace Modules\UserManagementSystem\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserManagementSystemDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(SeedPermissionsTableSeeder::class);
        $this->call(SeedRolesTableSeeder::class);
    }
}
