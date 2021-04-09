<?php

namespace Modules\Client\Http\Requests;

use App\Http\Requests\BaseRequest;

class UpdateClientRequest extends BaseRequest
{
    protected $validations = [
        'company_name.required',
        'company_name.max',
        'email.required',
        'email.email',
        'email.unique',
        'phone.numeric'
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
            'email'             => ['required', 'email', 'unique:clients,email,' . $this->client . ",id,deleted_at,NULL"],
            'phone'             => ['nullable', 'numeric']
        ];
    }
}
