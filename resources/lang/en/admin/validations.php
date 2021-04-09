<?php

return [
    "admin" => [
        'email:required'        => "Please Enter Your Email",
        'email:email'           => "Please Enter Valid Email Format",
        'password:required'     => "Please Enter Your Password",
    ],
    'settings' => [
        'settings:*:*:in' => 'Invalid value.',
        'settings:*:*:required' => 'This field is required.',
        'settings:array' => 'This field must be an array.'
    ]

    
];