<?php

namespace Modules\Client\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    public function toArray($request)
    {
        return  [
            'id' => $this->id,
            'key' => $this->key,
            'value' => $this->value,
            'group' => $this->group
        ];
    }
}
