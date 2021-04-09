<?php

namespace Modules\UserManagementSystem\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Client\Repositories\ClientRepository;

class VerifyClientJob implements ShouldQueue
{
    use Dispatchable, SerializesModels, InteractsWithQueue, Queueable;

    private $domain;

    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = app(ClientRepository::class)->where([
            'domain' => $this->domain
        ])->first(['*'], false);

        if ($client) {
            app(ClientRepository::class)->update([
                'is_active' => 1
            ], $client->id, false);
        }
    }

}