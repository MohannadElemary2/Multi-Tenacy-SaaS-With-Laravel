<?php

namespace Modules\UserManagementSystem\Tests\Feature\Role;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Modules\UserManagementSystem\Entities\Client\Permission;
use Modules\UserManagementSystem\Entities\Client\Role;
use Tests\TestCase;

class AddRoleTest extends TestCase
{
    use WithFaker;

    const ROUTE_ADD = 'client.roles.store';
    public $mockConsoleOutput = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
    }

    /**
     * @test
     */
    public function will_add_role_successfully()
    {
        $this->loginAsTenantUser();

        $roleData = $this->getRoleData();
        $response = $this->json('POST', route(self::ROUTE_ADD), $roleData);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertDatabaseCount(app(Role::class)->getTable(), 1);
    }

    /**
     * @test
     */
    public function will_fail_if_name_already_exists()
    {
        $this->loginAsTenantUser();
        $role = factory(Role::class)->create();

        $response = $this->json('POST', route(self::ROUTE_ADD), $role->toArray());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function getRoleData($data = [])
    {
        $permission = factory(Permission::class)->create();

        return array_merge(
            factory(Role::class)->raw(),
            ['permissions' => [$permission->id]]
        );
    }
}
