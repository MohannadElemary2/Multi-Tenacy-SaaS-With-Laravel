<?php

namespace Modules\UserManagementSystem\Http\Requests;

use App\Http\Requests\BaseRequest;

class EditIsSetupWizardFinishedRequest extends BaseRequest
{
    protected $validations = [
        'is_setup_wizard_finished.required',
        'is_setup_wizard_finished.boolean',
    ];

    protected $module = 'usermanagementsystem';
    protected $label  = 'setup_wizard';

    public function rules()
    {
        return [
            'is_setup_wizard_finished' => ['required', 'boolean']
        ];
    }
}
