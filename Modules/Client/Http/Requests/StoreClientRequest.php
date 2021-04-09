<?php

namespace Modules\Client\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends BaseRequest
{
    protected $validations = [
        'company_name.required',
        'company_name.max',
        'email.required',
        'email.email',
        'email.unique',
        'phone.numeric',
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
            'company_name'      => ['required', 'max:100'],
            'email'             => ['required', 'email', 'unique:clients,email,NULL,id,deleted_at,NULL'],
            'phone'             => ['nullable', 'numeric'],
            'domain'            => 
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
