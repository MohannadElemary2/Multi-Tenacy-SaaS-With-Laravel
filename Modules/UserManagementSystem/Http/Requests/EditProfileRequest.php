<?php

namespace Modules\UserManagementSystem\Http\Requests;

use App\Http\Requests\BaseRequest;

class EditProfileRequest extends BaseRequest
{
    protected $validations = [
        'email.required',
        'email.email',
        'email.unique',
        'name.required',
        'name.max',
        'phone.numeric',
    ];

    protected $module = 'usermanagementsystem';
    protected $label  = 'profile';

    public function rules()
    {
        $databaseConnection = currentTenantConnectionName();
        return [
            'name'   => ['required', 'max:100'],
            'email'  => ['required', 'email', "unique:$databaseConnection.users,email," . auth()->id() . ',id,deleted_at,NULL'],
            'phone'  => ['nullable', 'numeric'],
        ];
    }
}
