<?php

namespace Modules\UserManagementSystem\Tests\Feature\Profile;

use Illuminate\Http\Response;
use Modules\UserManagementSystem\Entities\Client\TenantUser;
use Tests\TestCase;

class UpdateTimeZoneTest extends TestCase
{
    const ROUTE_UPDATE_TIME_ZONE = 'client.profile.update_time_zone';

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
            'PUT', route(self::ROUTE_UPDATE_TIME_ZONE)
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test 
     */
    public function will_fail_if_time_zone_is_missing()
    {
        $this->loginAsTenantUser();

        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE_TIME_ZONE),
            $this->getUsertData(['time_zone' => null])
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(
            [
            'time_zone'
            ]
        );
    }

    /**
     * @test 
     */
    public function will_update_time_zone_successfully()
    {
        $this->loginAsTenantUser();

        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE_TIME_ZONE),
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
