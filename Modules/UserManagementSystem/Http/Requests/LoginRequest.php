<?php

namespace Modules\UserManagementSystem\Http\Requests;

use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    protected $validations = [
        'email.required',
        'email.email',
        'password.required',
    ];

    protected $module = 'usermanagementsystem';
    protected $label  = 'auth';

    public function rules()
    {
        return [
            'email'     => 'required|email',
            'password'  => 'required',
        ];
    }
}
