<?php

namespace Modules\UserManagementSystem\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
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
        return $user->hasPermissionTo('view_roles')
            || $user->hasPermissionTo('add_users')
            || $user->hasPermissionTo('edit_users');
    }

    public function view($user)
    {
        return $user->hasPermissionTo('view_roles');
    }

    public function create($user)
    {
        return $user->hasPermissionTo('add_roles');
    }

    public function update($user)
    {
        return $user->hasPermissionTo('edit_roles');
    }

    public function delete($user)
    {
        return $user->hasPermissionTo('delete_roles');
    }
}