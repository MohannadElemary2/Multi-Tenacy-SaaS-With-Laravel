<?php

namespace Modules\Admin\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    public function toArray($request)
    {
        return  [
            "name"  => $this->name,
            "email" => $this->email,
        ];
    }
}
