<?php

namespace Modules\Client\Tests\Feature;

use Illuminate\Http\Response;
use Modules\Client\Entities\Client\Settings;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Client\Enums\SettingsKeys;

class UpdateClientSettingsTest extends TestCase
{
    use WithFaker;
    const ROUTE_UPDATE = 'client.settings.bulk_update';

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
        $this->loginAsTenantUser();
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
        $this->loginAsTenantUser();
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
        $this->loginAsTenantUser();
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
    public function will_fail_if_invalid_locale()
    {
        $this->loginAsTenantUser();
        $settings = $this->createSettings();
        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE),
            ['settings' => [$this->getValidParams($settings, [
                'key' => SettingsKeys::LOCALE,
                'value' => $this->faker()->lexify('????????')
            ])]]
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
        $this->loginAsTenantUser();
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
