<?php

namespace Modules\UserManagementSystem\Tests\Feature\User;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Modules\UserManagementSystem\Entities\Client\Role;
use Modules\UserManagementSystem\Entities\Client\TenantUser;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use WithFaker;

    const ROUTE_DELETE = 'client.users.destroy';
    public $mockConsoleOutput = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
    }

    /**
     * @test
     */
    public function will_delete_user_successfully()
    {
        Event::fake();
        $this->loginAsTenantUser();
        $user = factory(TenantUser::class)->create();

        $response = $this->json('DELETE', route(self::ROUTE_DELETE, [$user->id]));

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function will_fail_if_not_authorized()
    {
        Event::fake();
        $user = factory(TenantUser::class)->create();

        $response = $this->json('DELETE', route(self::ROUTE_DELETE, [$user->id]));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
