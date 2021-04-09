<?php

use Illuminate\Support\Facades\Broadcast;
use Modules\UserManagementSystem\Enums\BroadcastingChannels;

// Broadcast::channel('{domain}.' . BroadcastingChannels::USER_CHANGES . '.{id}', function ($user, $domain, $id) {
//     return (int) $user->id === (int) $id;
// });
