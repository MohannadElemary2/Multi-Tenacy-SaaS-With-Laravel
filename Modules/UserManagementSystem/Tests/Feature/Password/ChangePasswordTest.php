<?php

namespace Modules\UserManagementSystem\Tests\Feature\Password;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response;
use Modules\UserManagementSystem\Entities\Client\TenantUser;
use Illuminate\Support\Facades\Notification;
use Modules\UserManagementSystem\Notifications\PasswordChangedNotification;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class ChangePasswordTest extends TestCase
{
    use WithFaker;
    const ROUTE_CHANGE_PASSWORD = 'client.auth.change_password';

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
        Notification::fake();
        $response = $this->json(
            'PUT',
            route(self::ROUTE_CHANGE_PASSWORD)
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        Notification::assertNothingSent();
    }

    /**
     * @test
     */
    public function will_fail_if_old_password_is_missing()
    {
        Notification::fake();
        $user = $this->createTenantUser();
        $this->loginAsTenantUser($user);

        $response = $this->json(
            'PUT',
            route(self::ROUTE_CHANGE_PASSWORD),
            $this->getValidParams($user, ['old_password' => null])
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(
            [
                'old_password'
            ]
        );
        Notification::assertNothingSent();
    }

    /**
     * @test
     */
    public function will_fail_if_password_is_missing()
    {
        Notification::fake();
        $user = $this->createTenantUser();
        $this->loginAsTenantUser($user);

        $response = $this->json(
            'PUT',
            route(self::ROUTE_CHANGE_PASSWORD),
            $this->getValidParams($user, ['password' => null])
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(
            [
                'password'
            ]
        );
        Notification::assertNothingSent();
    }


    /**
     * @test
     */
    public function will_fail_if_invalid_old_password()
    {
        Notification::fake();
        $user = $this->createTenantUser();
        $this->loginAsTenantUser($user);
        $oldPassword =  $this->faker()->password();
        $response = $this->json(
            'PUT',
            route(self::ROUTE_CHANGE_PASSWORD),
            $this->getValidParams($user, [
                'old_password' => $oldPassword,
            ])
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        Notification::assertNothingSent();
    }

    /**
     * @test
     */
    public function will_fail_if_password_confirmation_did_not_match()
    {
        Notification::fake();
        $user = $this->createTenantUser();
        $this->loginAsTenantUser($user);
        $response = $this->json(
            'PUT',
            route(self::ROUTE_CHANGE_PASSWORD),
            $this->getValidParams($user, [
                'password_confirmation' => null
            ])
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(
            [
                'password'
            ]
        );
        Notification::assertNothingSent();
    }


    /**
     * @test
     */
    public function will_change_password_successfully()
    {
        Notification::fake();
        $user = $this->createTenantUser();
        $this->loginAsTenantUser($user);
        $response = $this->json(
            'PUT',
            route(self::ROUTE_CHANGE_PASSWORD),
            $this->getValidParams($user)
        );
        
        $response->assertStatus(Response::HTTP_OK);
        Notification::assertSentTo($user, PasswordChangedNotification::class);
    }

    private function getValidParams($user, $overrides = [])
    {
        $password = $this->faker()->password();
        $user_data =  factory(TenantUser::class)->raw();
        $old_password = $user_data['password'];
        return array_merge(
            $user->toArray(),
            [
                'old_password' => $old_password,
                'password' => $password,
                'password_confirmation' => $password
            ],
            $overrides
        );
    }
}
