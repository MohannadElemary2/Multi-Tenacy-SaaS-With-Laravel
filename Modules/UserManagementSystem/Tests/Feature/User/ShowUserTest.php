<?php

namespace Modules\UserManagementSystem\Tests\Feature\User;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Modules\UserManagementSystem\Entities\Client\TenantUser;
use Tests\TestCase;

class ShowUserTest extends TestCase
{
    use WithFaker;

    const ROUTE_SHOW = 'client.users.show';
    public $mockConsoleOutput = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
    }

    /**
     * @test
     */
    public function will_show_user_successfully()
    {

        Event::fake();
        $this->loginAsTenantUser();
        $user = factory(TenantUser::class)->create();

        $response = $this->json('GET', route(self::ROUTE_SHOW, [$user->id]));

        $response->assertStatus(Response::HTTP_OK);
        $this->assertArrayHasKey('roles', $response->json()['data']);
    }

    /**
     * @test
     */
    public function will_fail_if_not_authorized()
    {
        Event::fake();
        $user = factory(TenantUser::class)->create();

        $response = $this->json('GET', route(self::ROUTE_SHOW, [$user->id]));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
