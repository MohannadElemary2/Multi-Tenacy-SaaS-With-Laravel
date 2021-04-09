<?php

namespace Modules\UserManagementSystem\Tests\Feature\User;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Modules\UserManagementSystem\Entities\Client\Role;
use Modules\UserManagementSystem\Entities\Client\TenantUser;
use Tests\TestCase;

class AddUserTest extends TestCase
{
    use WithFaker;

    const ROUTE_ADD = 'client.users.store';
    public $mockConsoleOutput = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
    }

    /**
     * @test
     */
    public function will_add_user_successfully()
    {
        Event::fake();
        $this->loginAsTenantUser();

        $userData = $this->getUserData();
        $response = $this->json('POST', route(self::ROUTE_ADD), $userData);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertDatabaseCount(app(TenantUser::class)->getTable(), 2);
    }

    /**
     * @test
     */
    public function will_fail_if_name_already_exists()
    {
        Event::fake();
        $this->loginAsTenantUser();
        $user = factory(TenantUser::class)->create();

        $response = $this->json('POST', route(self::ROUTE_ADD), $user->toArray());

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
