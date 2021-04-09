<?php

namespace Modules\UserManagementSystem\Tests\Feature\Password;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Modules\UserManagementSystem\Entities\Client\TenantUser;

class ResetPasswordTest extends TestCase
{
    use WithFaker;
    const ROUTE_RESET_PASSWORD = 'client.auth.reset';

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenantDB();
    }

    /**
     * @test
     */
    public function will_fail_if_id_is_missing()
    {
        $user = $this->createTenantUser();

        $response = $this->json(
            'POST',
            route(self::ROUTE_RESET_PASSWORD),
            $this->getValidParams($user, ['id' => null])
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(
            [
                'id'
            ]
        );
    }

    /**
     * @test
     */
    public function will_fail_if_token_is_missing()
    {
        $user = $this->createTenantUser();

        $response = $this->json(
            'POST',
            route(self::ROUTE_RESET_PASSWORD),
            $this->getValidParams($user, ['token' => null])
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(
            [
                'token'
            ]
        );
    }

    /**
     * @test
     */
    public function will_fail_if_password_is_missing()
    {
        $user = $this->createTenantUser();

        $response = $this->json(
            'POST',
            route(self::ROUTE_RESET_PASSWORD),
            $this->getValidParams($user, ['password' => null])
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(
            [
                'password'
            ]
        );
    }

    /**
     * @test
     */
    public function will_fail_if_password_is_mismatch()
    {
        $user = $this->createTenantUser();

        $password = $this->faker()->password();
        $response = $this->json(
            'POST',
            route(self::ROUTE_RESET_PASSWORD),
            $this->getValidParams($user, ['password' => $password])
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(
            [
                'password'
            ]
        );
    }

    /**
     * @test
     */
    public function will_fail_if_invalid_token()
    {
        $user = $this->createTenantUser();

        $token = $this->faker()->password();
        $response = $this->json(
            'POST',
            route(self::ROUTE_RESET_PASSWORD),
            $this->getValidParams($user, ['token' => $token, 'check' => 0])
        );
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     */
    public function will_reset_password_successfully()
    {
        $user = $this->createTenantUser();
        $response = $this->json(
            'POST',
            route(self::ROUTE_RESET_PASSWORD),
            $this->getValidParams($user)
        );
        $response->assertStatus(Response::HTTP_OK);
    }


    private function getValidParams($user, $overrides = [])
    {
        $token = Password::broker()->createToken($user);
        $password = $this->faker()->password();
        return array_merge(
            $user->toArray(),
            [
                'token' => $token,
                'password' => $password,
                'password_confirmation' => $password,
                'check' => 0
            ],
            $overrides
        );
    }
}
