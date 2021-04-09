<?php

namespace Modules\UserManagementSystem\Http\Requests;

use App\Http\Requests\BaseRequest;

class SetFirebaseTokenRequest extends BaseRequest
{
    protected $validations = [
    ];

    protected $module = 'usermanagementsystem';
    protected $label  = 'firebase_tokens';

    public function rules()
    {
        return [
            'lang'          => 'required|in:en',
            'token'         => 'required',
            'platform'      => 'required|in:android,ios',
        ];
    }
}
