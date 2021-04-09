<?php

namespace Modules\Client\Http\Requests;

use App\Http\Requests\BaseRequest;
use Modules\Client\Http\Requests\Validation\SettingsValidation;

class UpdateSettingsRequest extends BaseRequest
{
    protected $validations = [
        'settings.array',
        'settings.*.*.required',
        'settings.*.*.max',
        'settings.*.*.in',
        'settings.*.*.boolean',
    ];

    protected $module = 'client';
    protected $label  = 'settings';

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $settingsValidation = new SettingsValidation($this->settings);
        return $settingsValidation->getRules() ?? [];
    }
}
