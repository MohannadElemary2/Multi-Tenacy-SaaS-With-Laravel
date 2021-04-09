<?php

namespace Modules\Client\Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Modules\Client\Entities\System\Client;
use Tests\TestCase;

class ShowClientTest extends TestCase
{
    use WithFaker;

    const ROUTE_SHOW = 'system.clients.show';
    public $mockConsoleOutput = false;

    private $createdClient;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        $this->createdClient = $this->createClient();
    }

    /**
     * @test
     */
    public function will_fail_if_not_authenticated_as_admin()
    {
        $response = $this->json(
            'GET',
            route(self::ROUTE_SHOW, [$this->createdClient->id])
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    private function createClient($attributes = [])
    {
        return factory(Client::class)->create($attributes);
    }
}
