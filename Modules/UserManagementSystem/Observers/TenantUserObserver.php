<?php

namespace Modules\UserManagementSystem\Observers;

use Modules\UserManagementSystem\Entities\Client\TenantUser;

class TenantUserObserver
{
    public function creating(TenantUser $user)
    {
        if (auth()->user() && get_class(auth()->user()) == TenantUser::class)
            $user->created_by_id = auth()->id();
    }
}