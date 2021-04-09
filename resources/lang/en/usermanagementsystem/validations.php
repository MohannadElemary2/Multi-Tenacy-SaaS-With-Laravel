<?php

return [
    "auth" => [
        'email:required'        => "Please Enter Your Email",
        'email:email'           => "Please Enter Valid Email Format",
        'password:required'     => "Please Enter Your Password",
        'password:min'     => "The password must be at least :min characters.",
    ],

    "profile" => [
        'email:required'        => "Please Enter Your Email",
        'email:email'           => "Please Enter Valid Email Format",
        'email:unique'          => "This Email is Used Before.",
        'name:required'         => "Please Enter Your Name.",
        'name:max'              => "Tha Name Shouldn't Exceed 100 Character.",
        'phone:numeric'         => "Phone Number Should Be Numbers Only.",
        'time_zone:required'         => "Please Enter Your Time zone.",
        'time_zone:max'         => "The Time zone Shouldn't Exceed :max Character.",
        'locale:required'         => "Please Enter Your Locale.",
        'locale:max'         => "The locale Shouldn't Exceed :max Character.",
        'locale:in'         => "Invalid locale."
    ],

    "setPassword" => [
        'password:required_without'  => "Please Enter The New Password",
        'password:confirmed'         => "The Passwords Didn't Match",
        'password:min'               => "The Password Minimum Length is :min Characters",
        'password:required'  => "Please Enter The New Password",
        'password:max'               => "The Password Maximum Length is :max Characters",
        'id:required'   =>  "The user id is required",
        'token:required' => "The token is required",
        'check:in' => "Invalid value."
    ],

    "roles" => [
        'name:required'        => "Please Enter The Role Name",
        'name:min'             => "The Minimum Number of Characters in The Role Name is 1",
        'name:unique'          => "This Name is Used Before",
        'name:max'             => "The Maximum Number of Characters in The Role Name is 25",
        'permissions:required' => "Please Select At Least One Permission For the Role",
        'permissions:array'    => "Please Enter Valid Permissions",
        'permissions:*:exists' => "Please Enter Valid Permissions",
    ],

    "users" => [
        'name:required'        => "Please Enter The User Name",
        'name:max'             => "The Maximum Number of Characters in The Name is 100",
        'email:required'       => "The Email Field is Required",
        'email:email'          => "Please Enter Valid Email Format",
        'email:unique'         => "This email is associated with another user account, please add a new email.",
        'phone:numeric'        => "Phone Number Should Be Numbers Only.",
        'roles:array'          => "Please Enter Valid Roles",
        'roles:*:exists'       => "Please Enter Valid Roles",
        'hubs:array'          => "Please Enter Valid Hubs",
        'hubs:*:exists'       => "Please Enter Valid Hubs",
    ],

    'setup_wizard' => [
        'is_setup_wizard_finished:required' => 'The is setup wizard finished field is Required.',
        'is_setup_wizard_finished:boolean' => 'The is setup wizard finished field must be true or false.',
    ]
];
