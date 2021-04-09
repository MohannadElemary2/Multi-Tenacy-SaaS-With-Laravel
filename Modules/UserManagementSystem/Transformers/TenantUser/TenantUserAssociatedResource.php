<?php

namespace Modules\UserManagementSystem\Transformers\TenantUser;

use Illuminate\Http\Resources\Json\JsonResource;

class TenantUserAssociatedResource extends JsonResource
{
    public function toArray($request)
    {
        return  [
            'id'            => $this->id,
            'name'          => $this->name,
            'is_deleted'    => (bool) $this->deleted_at,
        ];
    }
}
