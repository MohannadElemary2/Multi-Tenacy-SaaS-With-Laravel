<?php

/**
 * @var Factory $factory 
 */

use Faker\Generator as Faker;
use Modules\Admin\Entities\System\User;

$factory->define(
    User::class, function (Faker $faker) {
        return [
        'name'      => $faker->name,
        'email'     => $faker->safeEmail,
        'password'  => 'pa$$W0rD'
        ];
    }
);