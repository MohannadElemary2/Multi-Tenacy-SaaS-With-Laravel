<?php

/**
 * @var Factory $factory 
 */

use Faker\Generator as Faker;
use Modules\UserManagementSystem\Entities\Client\TenantUser;

$factory->define(
    TenantUser::class,
    function (Faker $faker) {
        return [
            'name'      => $faker->name,
            'email'     => $faker->safeEmail,
            'phone'     => '11111111',
            'password'  => 'pa$$W0rD',
            'time_zone' => $faker->timezone,
            'is_super'  => 1,
            'locale'  => 'en'
        ];
    }
);
