<?php

namespace Modules\UserManagementSystem\Listeners;

use Modules\Client\Repositories\ClientRepository;
use Modules\UserManagementSystem\Entities\Client\TenantUser;
use Modules\UserManagementSystem\Events\TenantUserAdded;

class SendVerificationEmailListener
{
    /**
     * Handle the event.
     *
     * @param  TenantUserAdded  $event
     * @return void
     * @author Mohannad Elemary
     */
    public function handle(TenantUserAdded $event)
    {
        // Check if the user is added by a client user
        if (auth()->user() && get_class(auth()->user()) == TenantUser::class) {
            $domain = explode('.', request()->getHost())[0];

            $companyName = app(ClientRepository::class)
                ->findBy('domain', $domain)
                ->first(['*'], false);
            $companyName = $companyName ? $companyName->company_name : $event->user->name;

            $event->user->sendEmailVerificationNotification($domain, $companyName);
        }
    }
}
