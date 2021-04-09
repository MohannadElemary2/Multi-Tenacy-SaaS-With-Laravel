<?php

namespace Modules\Client\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Client\Events\ClientAdded;
use Modules\Client\Listeners\CreateTenantListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ClientAdded::class => [
            CreateTenantListener::class,
        ]
    ];
}