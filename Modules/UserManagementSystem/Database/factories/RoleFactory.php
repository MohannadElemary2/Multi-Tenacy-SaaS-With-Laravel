<?php

/**
 * @var Factory $factory 
 */

use Faker\Generator as Faker;
use Modules\UserManagementSystem\Entities\Client\Role;

$factory->define(
    Role::class, function (Faker $faker) {
        return [
            'name'          => $faker->name,
            'guard_name'    => 'client-users-api',
        ];
    }
);