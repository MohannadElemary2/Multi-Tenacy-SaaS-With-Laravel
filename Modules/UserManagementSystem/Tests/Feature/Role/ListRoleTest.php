<?php

namespace Modules\UserManagementSystem\Tests\Feature\Role;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Modules\UserManagementSystem\Entities\Client\Role;
use Tests\TestCase;

class ListRoleTest extends TestCase
{
    use WithFaker;

    const ROUTE_LIST = 'client.roles.index';
    public $mockConsoleOutput = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
    }

    /**
     * @test
     */
    public function will_list_roles_successfully()
    {
        $this->loginAsTenantUser();
        factory(Role::class)->create();

        $response = $this->json('GET', route(self::ROUTE_LIST));

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function will_fail_if_not_authorized()
    {
        $response = $this->json('GET', route(self::ROUTE_LIST));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
