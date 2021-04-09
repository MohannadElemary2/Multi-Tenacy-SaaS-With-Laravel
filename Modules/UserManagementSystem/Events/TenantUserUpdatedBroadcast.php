<?php

namespace Modules\UserManagementSystem\Events;

use App\Enums\QueuesNames;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\UserManagementSystem\Enums\BroadcastingChannels;

class TenantUserUpdatedBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $queue = QueuesNames::SOCKET;
    private $domain;
    private $userId;

    public function __construct($domain, $userId)
    {
        $this->domain = $domain;
        $this->userId = $userId;
    }

    /**
    * The event's broadcast name.
    *
    * @return string
    */
    public function broadcastAs()
    {
        return 'tenant.user.updated';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("$this->domain." . BroadcastingChannels::USER_CHANGES . ".$this->userId");
    }
}
