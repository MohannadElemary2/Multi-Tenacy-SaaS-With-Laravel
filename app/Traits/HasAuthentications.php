<?php

namespace App\Traits;

use App\Enums\PassportGrantTypes;
use App\Models\Tenants\PassportClient;
use Hyn\Tenancy\Environment;
use Laravel\Passport\Client;
use Laravel\Passport\Http\Controllers\AccessTokenController;

trait HasAuthentications
{
    /**
     * Issue New token or refresh the current one
     *
     * @param $serverRequest
     * @param array $data
     * @param boolean $refresh
     * @return void
     * @author Mohannad Elemary
     */
    public function tokenRequest($serverRequest, array $data, $refresh = false)
    {
        if ($refresh) {
            $body = $this->getAuthTokenBodyRefreshRequest($data);
        } else {
            $body = $this->getAuthTokenBodyRequest($data);
        }

        $request = $serverRequest->withParsedBody($body);
        $response =  app(AccessTokenController::class)->issueToken($request);
        $data['statusCode'] = $response->getStatusCode();
        $data['response'] =  json_decode((string) $response->getContent(), true);
        return $data;
    }

    /**
     * Prepare the body of the oauth request
     *
     * @param array $data
     * @return void
     * @author Mohannad Elemary
     */
    public function getAuthTokenBodyRequest(array $data)
    {
        $client = $this->getClient();

        return  [
            'grant_type' => 'password',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'username' => $data['email'],
            'password' => $data['password'],
            'scope' => '*'
          ];
    }

    /**
     * Prepare the body of the refresh oauth request
     *
     * @param array $data
     * @author Mohannad Elemary
     */
    public function getAuthTokenBodyRefreshRequest(array $data)
    {
        $client = $this->getClient();

        return  [
            'grant_type' => 'refresh_token',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'refresh_token' => $data['refresh_token'],
            'scope' => '*'
          ];
    }

    /**
     * Get The Client of the passport 
     *
     * @author Mohannad Elemary
     */
    private function getClient()
    {
        return app(Environment::class)->tenant()?
                PassportClient::firstWhere('password_client', PassportGrantTypes::PASSWORD_GRANT):
                Client::firstWhere('password_client', PassportGrantTypes::PASSWORD_GRANT);
    }

}