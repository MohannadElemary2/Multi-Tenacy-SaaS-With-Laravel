<?php

namespace Modules\UserManagementSystem\Tests\Feature\Role;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Modules\UserManagementSystem\Entities\Client\Permission;
use Modules\UserManagementSystem\Entities\Client\Role;
use Tests\TestCase;

class DeleteRoleTest extends TestCase
{
    use WithFaker;

    const ROUTE_DELETE = 'client.roles.destroy';
    public $mockConsoleOutput = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
    }

    /**
     * @test
     */
    public function will_delete_role_successfully()
    {
        $this->loginAsTenantUser();
        $role = factory(Role::class)->create();

        $response = $this->json('DELETE', route(self::ROUTE_DELETE, [$role->id]));

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function will_fail_if_not_authorized()
    {
        $role = factory(Role::class)->create();

        $response = $this->json('DELETE', route(self::ROUTE_DELETE, [$role->id]));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
