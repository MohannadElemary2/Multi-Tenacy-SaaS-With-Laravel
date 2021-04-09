<?php

namespace Modules\UserManagementSystem\Http\Requests;

use App\Http\Requests\BaseRequest;
use Modules\UserManagementSystem\Enums\SettingsValues;

class StoreTenantUserRequest extends BaseRequest
{
    protected $validations = [
        'name.required',
        'name.max',
        'email.required',
        'email.email',
        'email.unique',
        'phone.numeric',
        'roles.array',
        'roles.*.exists',
        'hubs.*.exists',
    ];

    protected $module = 'usermanagementsystem';
    protected $label  = 'users';

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $databaseConnection = currentTenantConnectionName();
        return [
            'name'      => 'required|max:100',
            'email'     => "required|email|unique:$databaseConnection.users,email,NULL,id,deleted_at,NULL",
            'phone'     => 'nullable|numeric',
            'roles'     => 'nullable|array',
            'roles.*'   => "exists:$databaseConnection.roles,id",
            'all_hubs'  => "nullable|boolean",
            'hubs'      => 'nullable|array',
            'hubs.*'    => "exists:$databaseConnection.hubs,id",
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'time_zone' =>  SettingsValues::TIME_ZONE,
            'locale' =>  SettingsValues::LOCALE
        ]);
    }
}
