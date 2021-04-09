<?php

/**
 * @var Factory $factory 
 */

use Faker\Generator as Faker;
use Modules\Client\Entities\System\Client;

$factory->define(
    Client::class, function (Faker $faker) {
        return [
            'company_name'  => $faker->name,
            'email'         => $faker->safeEmail,
            'phone'         => '11111111',
            'domain'        => 'space',
        ];
    }
);