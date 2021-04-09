<?php

namespace Modules\UserManagementSystem\Listeners;

use Modules\UserManagementSystem\Events\TenantUserSetPassword;

class SendPasswordChangedEmailListener
{
    /**
     * Handle the event.
     *
     * @param  TenantUserSetPassword  $event
     * @return void
     * @author Mohannad Elemary
     */
    public function handle(TenantUserSetPassword $event)
    {
        $domain = explode('.', request()->getHost())[0];
        $event->user->sendPasswordChangedNotification($domain);
    }
}
