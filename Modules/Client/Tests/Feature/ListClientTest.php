<?php

namespace Modules\Client\Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Modules\Client\Entities\System\Client;
use Tests\TestCase;

class ListClientTest extends TestCase
{
    use WithFaker;

    const ROUTE_LIST = 'system.clients.index';
    public $mockConsoleOutput = false;


    /**
     * @test 
     */
    public function will_fail_if_not_authenticated_as_admin()
    {
        $response = $this->json(
            'GET', route(self::ROUTE_LIST)
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test 
     */
    public function will_list_clients_successfully()
    {
        $this->loginAsSystemUser();

        $response = $this->json(
            'GET',
            route(self::ROUTE_LIST)
        );

        $response->assertStatus(Response::HTTP_OK);
        $this->assertArrayHasKey('data', $response->json());
    }

    private function createClient($attributes = [])
    {
        return factory(Client::class)->create($attributes);
    }

}
