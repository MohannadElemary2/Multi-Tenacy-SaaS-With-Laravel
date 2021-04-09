<?php

namespace Modules\UserManagementSystem\Transformers\Permission;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray($request)
    {
        return  [
            'id'    => $this->id,
            'tag'   => $this->name,
            'name'  => $this->translated_name,
        ];
    }
}
