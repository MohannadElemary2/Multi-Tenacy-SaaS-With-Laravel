<?php

namespace Modules\UserManagementSystem\Tests\Feature\Profile;

use Illuminate\Http\Response;
use Modules\UserManagementSystem\Entities\Client\TenantUser;
use Tests\TestCase;
use \Astrotomic\Translatable\Locales;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class UpdateLocaleTest extends TestCase
{
    use WithFaker;
    const ROUTE_UPDATE_LOCALE = 'client.profile.update_locale';
    protected $locales;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
        $this->locales = $this->app->make(Locales::class);
    }

    /**
     * @test
     */
    public function will_fail_if_not_authenticated_as_tenant_user()
    {
        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE_LOCALE)
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function will_fail_if_locale_is_missing()
    {
        $user = $this->createTenantUser();
        $this->loginAsTenantUser($user);

        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE_LOCALE),
            $this->getValidParams($user, ['locale' => null])
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(
            [
                'locale'
            ]
        );
    }


    /**
     * @test
     */
    public function will_fail_if_locale_is_not_available()
    {
        $user = $this->createTenantUser();
        $this->loginAsTenantUser($user);
        $locale = $this->faker()->lexify('????????');
        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE_LOCALE),
            $this->getValidParams($user, ['locale' => $locale])
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(
            [
                'locale'
            ]
        );
    }

    /**
     * @test
     */
    public function will_update_locale_successfully()
    {
        $user = $this->createTenantUser();
        $this->loginAsTenantUser($user);
        $available_locales = (array) $this->locales->all();
        $random_locale = (array) Arr::random($available_locales, count($available_locales) ? 1 : 0);
        $locale = $random_locale[0] ?? null;
        $response = $this->json(
            'PUT',
            route(self::ROUTE_UPDATE_LOCALE),
            $this->getValidParams($user, ['locale' => $locale])
        );

        $response->assertStatus(Response::HTTP_OK);
    }

    private function getValidParams($user, $overrides = [])
    {
        return array_merge(
            $user->toArray(),
            $overrides
        );
    }
}
