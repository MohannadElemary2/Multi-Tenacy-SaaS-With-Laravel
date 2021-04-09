<?php

namespace Modules\UserManagementSystem\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class TenantUserPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        if ($user->is_super) {
            return true;
        }
    }

    public function viewAny($user)
    {
        return $user->hasPermissionTo('view_users');
    }

    public function view($user)
    {
        return $user->hasPermissionTo('view_users');
    }

    public function create($user)
    {
        return $user->hasPermissionTo('add_users');
    }

    public function update($user)
    {
        return $user->hasPermissionTo('edit_users');
    }

    public function delete($user)
    {
        return $user->hasPermissionTo('delete_users');
    }
}