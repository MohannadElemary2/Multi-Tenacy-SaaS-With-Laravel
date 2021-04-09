<?php

/**
 * @var Factory $factory 
 */

use Faker\Generator as Faker;
use Modules\UserManagementSystem\Entities\Client\Permission;

$factory->define(
    Permission::class, function (Faker $faker) {
        return [
            'name'          => 'add_admin',
            'guard_name'    => 'client-users-api',
        ];
    }
);