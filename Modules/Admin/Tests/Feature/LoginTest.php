<?php

namespace Modules\Admin\Tests\Feature;

use Illuminate\Http\Response;
use Modules\Admin\Entities\System\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    const ROUTE_LOGIN = 'system.login';
    public $mockConsoleOutput = false;

    /**
     * @test
     */
    public function will_fail_with_validation_errors_when_email_is_missing()
    {
        $response = $this->json(
            'POST',
            route(self::ROUTE_LOGIN),
            [
            'password' => 'password',
            ]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(
                [
                'email'
                ]
            );
    }

    /**
     * @test
     */
    public function will_fail_with_validation_errors_when_password_is_missing()
    {
        $response = $this->json(
            'POST',
            route(self::ROUTE_LOGIN),
            [
            'email' => 'john.doe@email.com',
            'password' => '',
            ]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(
                [
                'password'
                ]
            );
    }

    /**
     * @test
     */
    public function will_fail_with_incorrect_credentials()
    {
        $admin = factory(User::class)->create();

        $response = $this->json(
            'POST',
            route(self::ROUTE_LOGIN),
            [
            'email' => $admin->email,
            'password' => 'invalid-password',
            ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     */
    public function will_login_successfully_with_correct_credentials()
    {
        $admin = factory(User::class)->create();

        $response = $this->json(
            'POST',
            route(self::ROUTE_LOGIN),
            [
            'email' => $admin->email,
            'password' => 'pa$$W0rD',
            ]
        );

        $response->assertStatus(Response::HTTP_OK);

        $this->assertArrayHasKey('access_token', $response->json()['data']);
        $this->assertArrayHasKey('refresh_token', $response->json()['data']);
        $this->assertArrayHasKey('admin', $response->json()['data']);
    }
}
