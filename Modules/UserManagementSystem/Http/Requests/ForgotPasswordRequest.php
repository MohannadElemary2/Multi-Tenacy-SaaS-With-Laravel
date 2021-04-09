<?php

namespace Modules\UserManagementSystem\Http\Requests;

use App\Http\Requests\BaseRequest;

class ForgotPasswordRequest extends BaseRequest
{
    protected $validations = [
        'email.required',
        'email.email'
    ];

    protected $module = 'usermanagementsystem';
    protected $label  = 'auth';

    public function rules()
    {
        return [
            'email'     => 'required|email',
        ];
    }
}
