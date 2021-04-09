<?php

namespace Modules\UserManagementSystem\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\UserManagementSystem\Events\TenantUserAdded;
use Modules\UserManagementSystem\Events\TenantUserSetPassword;
use Modules\UserManagementSystem\Listeners\ActivateClientListener;
use Modules\UserManagementSystem\Listeners\SendPasswordChangedEmailListener;
use Modules\UserManagementSystem\Listeners\SendVerificationEmailListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TenantUserAdded::class => [
            SendVerificationEmailListener::class,
        ],
        TenantUserSetPassword::class => [
            ActivateClientListener::class,
            SendPasswordChangedEmailListener::class,
        ],
    ];
}