<?php

namespace Modules\UserManagementSystem\Tests\Feature\Password;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response;
use Modules\UserManagementSystem\Entities\Client\TenantUser;
use Illuminate\Support\Facades\Notification;
use Modules\UserManagementSystem\Notifications\ResetPasswordNotification;

class ForgotPasswordTest extends TestCase
{

    use WithFaker;
    const ROUTE_FORGOT_PASSWORD = 'client.auth.forgot';

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
    }


    /**
     * @test 
     */
    public function will_fail_if_email_is_missing()
    {
        Notification::fake();

        $user = $this->createTenantUser();

        $response = $this->json(
            'POST',
            route(self::ROUTE_FORGOT_PASSWORD),
            $this->getValidParams($user, ['email' => null])
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(
            [
                'email'
            ]
        );
        Notification::assertNothingSent();
    }

    /**
     * @test 
     */
    public function will_fail_if_email_is_not_exixts()
    {
        Notification::fake();

        $user = $this->createTenantUser();

        $response = $this->json(
            'POST',
            route(self::ROUTE_FORGOT_PASSWORD),
            $this->getValidParams($user, ['email' => $this->faker()->email])
        );
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        Notification::assertNothingSent();
    }


    /**
     * @test 
     */
    public function will_send_forget_password_email_successfully()
    {
        Notification::fake();
        $user = $this->createTenantUser();
        $response = $this->json(
            'POST',
            route(self::ROUTE_FORGOT_PASSWORD),
            $this->getValidParams($user)
        );
        $response->assertStatus(Response::HTTP_OK);
        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }


    private function getValidParams($user, $overrides = [])
    {
        return array_merge(
            $user->toArray(),
            $overrides
        );
    }
}
