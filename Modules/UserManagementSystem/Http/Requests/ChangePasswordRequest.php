<?php

namespace Modules\UserManagementSystem\Http\Requests;

use App\Http\Requests\BaseRequest;

class ChangePasswordRequest extends BaseRequest
{
    protected $validations = [
        'old_password.required',
        'password.required',
        'password.min',
        'password.max',
        'password.confirmed'
    ];

    protected $module = 'usermanagementsystem';
    protected $label  = 'setPassword';

    public function rules()
    {
        return [
            'old_password'  => 'required',
            'password'  => 'required|confirmed|min:6|max:255',
        ];
    }
}
