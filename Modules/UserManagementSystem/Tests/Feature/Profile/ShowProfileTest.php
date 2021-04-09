<?php

namespace Modules\UserManagementSystem\Tests\Feature\Profile;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ShowProfileTest extends TestCase
{
    use WithFaker;

    const ROUTE_SHOW = 'client.profile.view';
    public $mockConsoleOutput = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
    }

    /**
     * @test 
     */
    public function will_fail_if_not_authenticated_as_admin()
    {
        $response = $this->json(
            'GET', route(self::ROUTE_SHOW)
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test 
     */
    public function will_show_profile_successfully()
    {
        $this->loginAsTenantUser();

        $response = $this->json(
            'GET',
            route(self::ROUTE_SHOW)
        );

        $response->assertStatus(Response::HTTP_OK);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertArrayHasKey('name', $response->json()['data']);
    }

}
