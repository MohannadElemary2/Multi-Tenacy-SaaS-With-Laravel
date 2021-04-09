<?php

namespace Modules\Client\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray($request)
    {
        return  [
            'id'                => $this->id,
            'company_name'      => $this->company_name,
            'email'             => $this->email,
            'phone'             => $this->phone,
            'domain'            => $this->domain,
            'is_active'         => $this->is_active,
            'created_at'        => $this->created_at,
        ];
    }
}
