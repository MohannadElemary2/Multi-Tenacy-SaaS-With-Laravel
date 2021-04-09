<?php

namespace Modules\Client\Tests\Feature;

use Illuminate\Http\Response;
use Modules\Client\Entities\Client\Settings;
use Tests\TestCase;

class ListSettingsTest extends TestCase
{

    const ROUTE_LIST = 'client.settings.index';

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
    }

    /**
     * @test 
     */
    public function will_fail_if_not_authenticated_as_client()
    {
        $response = $this->json(
            'GET',
            route(self::ROUTE_LIST)
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test 
     */
    public function will_list_settings_successfully()
    {
        $this->loginAsTenantUser();
        $this->createSettings();
        $response = $this->json(
            'GET',
            route(self::ROUTE_LIST)
        );
        $response->assertStatus(Response::HTTP_OK);
        $this->assertArrayHasKey('data', $response->json());
    }

    private function createSettings($attributes = [])
    {
        return factory(Settings::class)->create($attributes);
    }
}
