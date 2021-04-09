<?php

namespace Modules\UserManagementSystem\Http\Requests;

use App\Http\Requests\BaseRequest;

class VerifyEmailRequest extends BaseRequest
{
    protected $validations = [
        'password.required_without',
        'password.confirmed',
        'password.min',
    ];

    protected $module = 'usermanagementsystem';
    protected $label  = 'setPassword';

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => 'required_without:check|confirmed|min:6',
            'check'    => 'nullable'
        ];
    }
}
