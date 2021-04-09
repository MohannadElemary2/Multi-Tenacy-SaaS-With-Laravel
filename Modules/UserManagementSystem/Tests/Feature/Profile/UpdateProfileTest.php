<?php

namespace Modules\UserManagementSystem\Tests\Feature\Profile;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Modules\UserManagementSystem\Entities\Client\TenantUser;
use Tests\TestCase;

class UpdateProfileTest extends TestCase
{
    use WithFaker;

    const ROUTE_UPDATE = 'client.profile.edit';
    public $mockConsoleOutput = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
    }

    /**
     * @test 
     */
    public function will_fail_if_not_authenticated_as_admin()
    {
        $response = $this->json(
            'GET', route(self::ROUTE_UPDATE)
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test 
     */
    public function will_fail_if_email_is_missing()
    {
        $this->loginAsTenantUser();

        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE),
            $this->getUsertData(['email' => null])
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(
            [
            'email'
            ]
        );
    }

    /**
     * @test 
     */
    public function will_update_profile_successfully()
    {
        $this->loginAsTenantUser();

        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE),
            $this->getUsertData()
        );

        $response->assertStatus(Response::HTTP_OK);
    }


    private function getUsertData($attributes = [])
    {
        return array_merge(
            factory(TenantUser::class)->raw(),
            $attributes
        );
    }
}
