<?php

namespace Modules\UserManagementSystem\Listeners;

use Modules\UserManagementSystem\Events\TenantUserSetPassword;
use Modules\UserManagementSystem\Jobs\VerifyClientJob;

class ActivateClientListener
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
        if ($event->user->is_super) {
            $domain = explode('.', request()->getHost())[0];
            dispatch(new VerifyClientJob($domain));
        }
    }
}
