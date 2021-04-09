<?php

namespace Modules\UserManagementSystem\Transformers\Role;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\UserManagementSystem\Transformers\Permission\PermissionResource;

class RoleResource extends JsonResource
{
    public function toArray($request)
    {
        return  [
            'id'                => $this->id,
            'name'              => $this->name,
            'permissions'       => PermissionResource::collection($this->permissions),
            'number_of_users'   => $this->users_count,
            'created_at'        => $this->created_at
        ];
    }
}
