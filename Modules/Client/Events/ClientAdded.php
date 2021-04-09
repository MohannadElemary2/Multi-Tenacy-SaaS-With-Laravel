<?php

namespace Modules\Client\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Queue\SerializesModels;

class ClientAdded
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var Authenticatable
     */
    public $client;

    /**
     * Create a new event instance.
     *
     * @param  Authenticatable $client
     * @return void
     * @author Mohannad Elemary
     */
    public function __construct($client)
    {
        $this->client = $client;
    }
}