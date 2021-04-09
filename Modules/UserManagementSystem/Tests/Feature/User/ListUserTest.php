<?php

namespace Modules\UserManagementSystem\Tests\Feature\User;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Modules\UserManagementSystem\Entities\Client\TenantUser;
use Tests\TestCase;

class ListUserTest extends TestCase
{
    use WithFaker;

    const ROUTE_LIST = 'client.users.index';
    public $mockConsoleOutput = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
    }

    /**
     * @test
     */
    public function will_list_users_successfully()
    {
        Event::fake();
        $this->loginAsTenantUser();
        factory(TenantUser::class)->create();

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
