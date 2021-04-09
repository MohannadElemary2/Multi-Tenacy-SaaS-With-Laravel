<?php

namespace Modules\UserManagementSystem\Http\Requests;

use App\Http\Requests\BaseRequest;

class ResetPasswordRequest extends BaseRequest
{
    protected $validations = [
        'password.required',
        'password.min',
        'password.max',
        'password.confirmed',
        'token.required',
        'id.required',
        'check.in'
    ];

    protected $module = 'usermanagementsystem';
    protected $label  = 'setPassword';

    public function rules()
    {
        return [
            'check' => ['required', 'in:0,1'],
            'password'  => 'required_if:check,0|confirmed|min:6|max:255',
            'token' => 'required',
            'id' => 'required',
        ];
    }
}
