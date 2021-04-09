<?php

namespace Modules\Client\Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Modules\Client\Entities\System\Client;
use Tests\TestCase;

class UpdateClientTest extends TestCase
{
    use WithFaker;

    const ROUTE_UPDATE = 'system.clients.update';
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
    public function will_fail_with_validation_errors_when_email_is_missing()
    {
        $this->loginAsSystemUser();

        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE, [$this->createdClient->id]),
            $this->getClientData(['email' => null])
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(
            [
            'email'
            ]
        );
    }

    /**
     * @test 
     */
    public function will_fail_if_not_authenticated_as_admin()
    {
        $response = $this->json(
            'PUT', route(self::ROUTE_UPDATE, [$this->createdClient->id])
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test 
     */
    public function will_update_client_successfully()
    {
        $this->loginAsSystemUser();

        Event::fake();
        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE, [$this->createdClient->id]), 
            $this->getClientData()
        );

        $response->assertStatus(Response::HTTP_OK);
    }

    private function getClientData($attributes = [])
    {
        return array_merge(
            factory(Client::class)->raw(),
            $attributes
        );
    }

    private function createClient($attributes = [])
    {
        return factory(Client::class)->create($attributes);
    }

}
