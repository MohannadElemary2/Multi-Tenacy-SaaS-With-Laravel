<?php

namespace Modules\UserManagementSystem\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\UserManagementSystem\Entities\Client\Role;
use Modules\UserManagementSystem\Entities\Client\TenantUser;
use Modules\UserManagementSystem\Policies\RolePolicy;
use Modules\UserManagementSystem\Policies\TenantUserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Role::class         => RolePolicy::class,
        TenantUser::class   => TenantUserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
