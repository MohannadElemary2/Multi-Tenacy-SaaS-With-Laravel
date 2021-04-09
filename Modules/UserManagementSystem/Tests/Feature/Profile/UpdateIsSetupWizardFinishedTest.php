<?php

namespace Modules\UserManagementSystem\Tests\Feature\Profile;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateIsSetupWizardFinishedTest extends TestCase
{
    use WithFaker;

    const ROUTE_UPDATE_TIME_ZONE = 'client.profile.update_is_setup_wizard_finished';

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
    }

    /**
     * @test 
     */
    public function will_fail_if_not_authenticated_as_tenant_user()
    {
        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE_TIME_ZONE)
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test 
     */
    public function will_fail_if_is_setup_wizard_finished_is_missing()
    {
        $this->loginAsTenantUser();

        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE_TIME_ZONE),
            ['is_setup_wizard_finished' => null]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(
            [
                'is_setup_wizard_finished'
            ]
        );
    }

    /**
     * @test 
     */
    public function will_update_is_setup_wizard_finished_successfully()
    {
        $this->loginAsTenantUser();

        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE_TIME_ZONE),
            ['is_setup_wizard_finished' => $this->faker->boolean()]
        );

        $response->assertStatus(Response::HTTP_OK);
    }
}
