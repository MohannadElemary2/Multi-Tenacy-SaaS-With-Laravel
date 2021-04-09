<?php

namespace Modules\UserManagementSystem\Http\Requests;

use App\Http\Requests\BaseRequest;
use Astrotomic\Translatable\Locales;

class UpdateLocaleRequest extends BaseRequest
{
    protected $validations = [
        'locale.required',
        'locale.max',
        'locale.in'
    ];

    protected $module = 'usermanagementsystem';
    protected $label  = 'profile';

    public function rules()
    {
        $localesHelper = app()->get(Locales::class);
        $this->available_locales = implode(',', $localesHelper->all());
        return [
            'locale'  => ['required', 'max:255', "in:$this->available_locales"]
        ];
    }
}
