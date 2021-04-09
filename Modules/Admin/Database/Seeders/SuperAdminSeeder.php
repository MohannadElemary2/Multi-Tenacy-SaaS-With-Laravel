<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Modules\Admin\Entities\System\User;
use Spatie\Permission\PermissionRegistrar;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!App::runningUnitTests()){
            // Reset cached roles and permissions
            app()[PermissionRegistrar::class]->forgetCachedPermissions();
        }


        User::updateOrCreate([
                'email' => 'mostafa@omniful.com',
            ],[
                'name'     => 'Mostafa',
                'email'    => 'mostafa@omniful.com',
                'password' => '12345678',
            ]);

    }
}
