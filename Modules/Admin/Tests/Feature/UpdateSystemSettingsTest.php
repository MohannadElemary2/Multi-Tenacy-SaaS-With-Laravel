<?php

namespace Modules\Admin\Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Admin\Entities\System\Settings;

class UpdateSystemSettingsTest extends TestCase
{
    use WithFaker;
    const ROUTE_UPDATE = 'system.settings.bulk_update';


    /**
     * @test 
     */
    public function will_fail_if_not_authenticated_as_client()
    {
        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE)
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test 
     */
    public function will_fail_if_settings_array_is_missing()
    {
        $this->loginAsSystemUser();
        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE)
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(
            [
                'settings'
            ]
        );
    }

    /**
     * @test 
     */
    public function will_fail_if_settings_key_is_missing()
    {
        $this->loginAsSystemUser();
        $settings = $this->createSettings();
        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE),
            ['settings' => [$this->getValidParams($settings, ['key' => null])]]
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(
            [
                'settings.0.key'
            ]
        );
    }

    /**
     * @test 
     */
    public function will_fail_if_settings_value_is_missing()
    {
        $this->loginAsSystemUser();
        $settings = $this->createSettings();
        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE),
            ['settings' => [$this->getValidParams($settings, ['value' => null])]]
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(
            [
                'settings.0.value'
            ]
        );
    }


    /**
     * @test 
     */
    public function will_update_settings_successfully()
    {
        $this->loginAsSystemUser();
        $settings = $this->createSettings();
        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE),
            ['settings' => [$this->getValidParams($settings)]]
        );
        $response->assertStatus(Response::HTTP_OK);
    }


    private function createSettings($attributes = [])
    {
        return factory(Settings::class)->create($attributes);
    }

    private function getValidParams($settings, $overrides = [])
    {
        return array_merge(
            $settings->toArray(),
            $overrides
        );
    }
}
