<?php

namespace Modules\UserManagementSystem\Transformers\TenantUser;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Hub\Transformers\Hub\HubAssociatedResource;
use Modules\UserManagementSystem\Transformers\Role\RoleResource;

class TenantUserResource extends JsonResource
{
    public function toArray($request)
    {
        return  [
            'id'  => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'time_zone' => $this->time_zone,
            'locale' => $this->locale,
            'is_super' => $this->is_super,
            'last_login_at' => $this->last_login_at,
            'created_at' => $this->created_at,
            'roles'  => RoleResource::collection($this->roles),
            'created_by'  => new TenantUserAssociatedResource($this->createdBy),
            'is_setup_wizard_finished' => $this->is_setup_wizard_finished,
            'all_hubs' => $this->all_hubs,
        ];
    }
}
