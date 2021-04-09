<?php

namespace Modules\UserManagementSystem\Tests\Feature\Role;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Modules\UserManagementSystem\Entities\Client\Permission;
use Modules\UserManagementSystem\Entities\Client\Role;
use Tests\TestCase;

class UpdateRoleTest extends TestCase
{
    use WithFaker;

    const ROUTE_UPDATE = 'client.roles.update';
    public $mockConsoleOutput = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
    }

    /**
     * @test
     */
    public function will_update_role_successfully()
    {
        $this->loginAsTenantUser();
        $role = factory(Role::class)->create();

        $roleData = $this->getRoleData();
        $response = $this->json('PUT', route(self::ROUTE_UPDATE, [$role->id]), $roleData);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function will_fail_if_name_is_missing()
    {
        $this->loginAsTenantUser();
        $role = factory(Role::class)->create()->toArray();
        $role['name'] = null;

        $response = $this->json('PUT', route(self::ROUTE_UPDATE, $role['id']), $role);

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
