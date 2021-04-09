<?php

namespace Modules\UserManagementSystem\Http\Requests;

use App\Http\Requests\BaseRequest;

class UpdateRoleRequest extends BaseRequest
{
    protected $validations = [
        'name.required',
        'name.min',
        'name.unique',
        'name.max',
        'permissions.required',
        'permissions.array',
        'permissions.*.exists'
    ];

    protected $module = 'usermanagementsystem';
    protected $label  = 'roles';

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $databaseConnection = currentTenantConnectionName();
        return [
            'name'          => "required|min:1|max:25|unique:$databaseConnection.role_translations,name," . $this->role . ",role_id,deleted_at,NULL",
            'permissions'   => 'required|array',
            'permissions.*' => "exists:$databaseConnection.permissions,id"
        ];
    }

    /**
     * Prepare the data to be translatable.
     *
     * @return void
     * @author Mohannad Elemary
     */
    protected function prepareForValidation()
    {
        $this->merge([
            app()->getLocale() => [
                'name'  => $this->name
            ],
        ]);
    }
}
