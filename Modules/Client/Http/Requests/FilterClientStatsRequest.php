<?php

namespace Modules\Client\Http\Requests;

use App\Http\Requests\BaseRequest;

class FilterClientStatsRequest extends BaseRequest
{
    protected $validations = [
    ];

    protected $module = 'client';
    protected $label  = 'client';

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'from' => 'nullable|numeric',
            'to' => 'nullable|numeric',
        ];
    }
}
