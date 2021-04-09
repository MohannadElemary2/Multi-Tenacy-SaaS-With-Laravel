<?php

namespace Modules\Admin\Http\Requests;

use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    protected $validations = [
        'email.required',
        'email.email',
        'password.required',
    ];

    protected $module = 'admin';
    protected $label  = 'admin';

    public function rules()
    {
        return [
            'email'     => 'required|email',
            'password'  => 'required',
        ];
    }
}
