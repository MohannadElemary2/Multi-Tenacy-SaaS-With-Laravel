<?php

namespace Modules\UserManagementSystem\Transformers\Role;

use Illuminate\Http\Resources\Json\JsonResource;
class RoleAssociatedResource extends JsonResource
{
    public function toArray($request)
    {
        return  [
            'id'     => $this->id,
            'name'   => $this->name
        ];
    }
}
