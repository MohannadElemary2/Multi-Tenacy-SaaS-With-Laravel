<?php

namespace Modules\UserManagementSystem\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Queue\SerializesModels;

class TenantUserLoggedOut
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var Authenticatable
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  Authenticatable $user
     * @return void
     * @author Mohannad Elemary
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
