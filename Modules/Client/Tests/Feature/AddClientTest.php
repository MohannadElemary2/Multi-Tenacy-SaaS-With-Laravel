<?php

namespace Modules\Client\Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Modules\Client\Entities\System\Client;
use Modules\Client\Events\ClientAdded;
use Tests\TestCase;

class AddClientTest extends TestCase
{
    use WithFaker;

    const ROUTE_ADD = 'system.clients.store';
    public $mockConsoleOutput = false;

    /**
     * @test 
     */
    public function will_fail_with_validation_errors_when_email_is_missing()
    {
        $this->loginAsSystemUser();

        $response = $this->json(
            'POST',
            route(self::ROUTE_ADD),
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
            'POST', route(self::ROUTE_ADD)
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test 
     */
    public function will_fail_with_validation_errors_when_used_reserved_domain()
    {
        $this->loginAsSystemUser();

        $response = $this->json(
            'POST',
            route(self::ROUTE_ADD), 
            $this->getClientData(['domain' => 'www'])
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(
            [
            'domain'
            ]
        );
    }

    /**
     * @test 
     */
    public function will_add_client_successfully()
    {
        $this->loginAsSystemUser();

        Event::fake();
        $response = $this->json(
            'POST',
            route(self::ROUTE_ADD), 
            $this->getClientData()
        );

        $response->assertStatus(Response::HTTP_CREATED);
        Event::assertDispatched(ClientAdded::class);
    }

    private function getClientData($attributes = [])
    {
        return array_merge(
            factory(Client::class)->raw(),
            $attributes
        );
    }

}
