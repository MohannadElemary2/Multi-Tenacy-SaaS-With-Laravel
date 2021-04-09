<?php

return [
    "client" => [
        'company_name:required'     => "Please Enter Company Name",
        'company_name:max'          => "Company Name Shouldn't Exceed 100 Character",
        'email:required'            => "Please Enter Email",
        'email:email'               => "Please Enter Valid Email Format",
        'email:unique'              => "This email is associated with another user account, please add a new email",
        'phone:numeric'             => "Phone Number Should Be Numbers Only",
        'domain:required'           => "Please Enter Workspace name",
        'domain:string'             => "The Workspace Name Can't Contain Any Spaces ,Special Characters or Uppercase Characters",
        'domain:unique'             => "This Workspace Name is Used Before",
        'domain:regex'              => "The Workspace Name Can't Contain Any Spaces ,Special Characters or Uppercase Characters",
        'domain:not_in'             => "This Name is Not Allowed To be Used as a Workspace Name",
    ],
    "settings" => [
        'settings:*:*:in' => 'Invalid value.',
        'settings:*:*:required' => 'This field is required.',
        'settings:*:*:max' => 'This field should not Exceed :max Character',
        'settings:*:*:boolean' => 'This field must be true or false.',
        'settings:array' => 'This field must be an array.'
    ]
];
