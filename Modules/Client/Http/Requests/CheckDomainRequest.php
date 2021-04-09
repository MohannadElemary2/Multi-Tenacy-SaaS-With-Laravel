<?php

namespace Modules\Client\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class CheckDomainRequest extends BaseRequest
{
    protected $validations = [
        'domain.required',
        'domain.string',
        'domain.unique',
        'domain.regex',
        'domain.not_in',
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
            'domain' => 
            [
                'required',
                'string',
                'unique:clients,domain,NULL,id,deleted_at,NULL',
                'regex:/^[a-z](?:[a-z0-9\-]{0,61}[a-z0-9])$/',
                Rule::notIn(config('app.invalid_subdomains')),
            ],
        ];
    }
}
