<?php

namespace Modules\UserManagementSystem\Http\Requests;

use App\Http\Requests\BaseRequest;

class UpdateTimeZoneRequest extends BaseRequest
{
    protected $validations = [
        'time_zone.required',
        'time_zone.max'
    ];

    protected $module = 'usermanagementsystem';
    protected $label  = 'profile';

    public function rules()
    {
        return [
            'time_zone'  => ['required', 'max:255']
        ];
    }
}
