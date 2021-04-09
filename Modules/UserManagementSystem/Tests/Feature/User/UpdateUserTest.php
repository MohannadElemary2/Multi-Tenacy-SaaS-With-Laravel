<?php

namespace Modules\UserManagementSystem\Tests\Feature\User;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Modules\UserManagementSystem\Entities\Client\Permission;
use Modules\UserManagementSystem\Entities\Client\Role;
use Modules\UserManagementSystem\Entities\Client\TenantUser;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use WithFaker;

    const ROUTE_UPDATE = 'client.users.update';
    public $mockConsoleOutput = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
    }

    /**
     * @test
     */
    public function will_update_user_successfully()
    {
        Event::fake();
        $this->loginAsTenantUser();
        $user = factory(TenantUser::class)->create();

        $userData = $this->getUserData();
        $response = $this->json('PUT', route(self::ROUTE_UPDATE, [$user->id]), $userData);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function will_fail_if_name_is_missing()
    {
        Event::fake();
        $this->loginAsTenantUser();
        $user = factory(TenantUser::class)->create()->toArray();
        $user['name'] = null;

        $response = $this->json('PUT', route(self::ROUTE_UPDATE, $user['id']), $user);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function getUserData($data = [])
    {
        $role = factory(Role::class)->create();

        return array_merge(
            factory(TenantUser::class)->raw(),
            ['roles' => [$role->id]]
        );
    }
}
