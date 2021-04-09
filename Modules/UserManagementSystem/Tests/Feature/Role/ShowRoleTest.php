<?php

namespace Modules\UserManagementSystem\Tests\Feature\Role;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Modules\UserManagementSystem\Entities\Client\Role;
use Tests\TestCase;

class ShowRoleTest extends TestCase
{
    use WithFaker;

    const ROUTE_SHOW = 'client.roles.show';
    public $mockConsoleOutput = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
    }

    /**
     * @test
     */
    public function will_show_role_successfully()
    {
        $this->loginAsTenantUser();
        $role = factory(Role::class)->create();

        $response = $this->json('GET', route(self::ROUTE_SHOW, [$role->id]));

        $response->assertStatus(Response::HTTP_OK);
        $this->assertArrayHasKey('permissions', $response->json()['data']);
    }

    /**
     * @test
     */
    public function will_fail_if_not_authorized()
    {
        $role = factory(Role::class)->create();

        $response = $this->json('GET', route(self::ROUTE_SHOW, [$role->id]));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
