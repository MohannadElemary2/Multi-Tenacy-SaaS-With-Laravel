<?php

namespace Modules\Admin\Http\Requests;

use App\Http\Requests\BaseRequest;
use Modules\Admin\Enums\SettingsKeys;

class UpdateSettingsRequest extends BaseRequest
{
    protected $validations = [
        'settings.array',
        'settings.*.*.required',
        'settings.*.*.in'
    ];

    protected $module = 'admin';
    protected $label  = 'settings';

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $settingsKeys = implode(',', SettingsKeys::getValues());
        return [
            'settings' => ['required', 'array'],
            'settings.*.key'  => ['required', "in:$settingsKeys"],
            'settings.*.value' => ['required']
        ];
    }
}
