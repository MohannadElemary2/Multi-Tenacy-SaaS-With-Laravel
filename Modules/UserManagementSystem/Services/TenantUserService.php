<?php

namespace Modules\UserManagementSystem\Services;

use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use Modules\UserManagementSystem\Repositories\TenantUserRepository;
use App\Services\BaseService;
use App\Traits\HasAuthentications;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Modules\UserManagementSystem\Events\TenantUserDeletedBroadcast;
use Modules\UserManagementSystem\Events\TenantUserLoggedOut;
use Modules\UserManagementSystem\Events\TenantUserUpdatedBroadcast;
use Modules\UserManagementSystem\Events\TenantUserSetPassword;
use Modules\UserManagementSystem\Transformers\TenantUserResource;

class TenantUserService extends BaseService
{
    use HasAuthentications;

    public function __construct(TenantUserRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Login tenant user and retrieve token from oauth server
     *
     * @param  array  $data
     * @param  ServerRequestInterface $serverRequest
     * @return FailureResource|SuccessResource
     * @author Mohannad Elemary
     */
    public function login($data, $serverRequest)
    {
        // Validating user credintials
        $user = $this->repository->where([
            'email' => $data['email']
        ])->first(['*'], false);

        // Try to check if the user exists but deleted
        if (!$user) {
            $user = $this->repository->where([
                'email' => $data['email']
            ])->withTrashed()->first(['*'], false);
        }

        // Validate Account
        if (!$user) {
            return abort(new FailureResource([], __('usermanagementsystem/messages.invalid_credentials'), Response::HTTP_BAD_REQUEST));
        }

        // Check if User is Active
        if (!$user->is_active || !$user->password) {
            return abort(new FailureResource([], __('usermanagementsystem/messages.inactive_account'), Response::HTTP_BAD_REQUEST));
        }

        if (!Hash::check($data['password'], $user->getAuthPassword())) {
            return abort(new FailureResource([], __('usermanagementsystem/messages.invalid_credentials'), Response::HTTP_BAD_REQUEST));
        }

        // Check if user is deleted before "deactivated"
        if ($user->deleted_at) {
            return abort(new FailureResource([], __('auth.unauthenticatedWhenDeletedLogin'), Response::HTTP_UNAUTHORIZED));
        }

        // Issue Access Token From Oauth Server
        $result = $this->tokenRequest($serverRequest, $data);

        // Validate If token generated successfully
        if ($result['statusCode'] != Response::HTTP_OK) {
            return abort(new FailureResource([], $result['response']['error_description'], $result['statusCode'], Response::HTTP_BAD_REQUEST));
        }

        // Update Last Login At
        $this->updateUserLoginTime($user);

        return array_merge(
            ["user" => $this->repository->wrapData($user->fresh())],
            $result['response']
        );
    }

    /**
     * View Current User's profile
     *
     * @return TenantUserResource
     * @author Mohannad Elemary
     */
    public function viewProfile()
    {
        return $this->repository->wrapData(auth()->user());
    }

    /**
     * Edit Current User's profile
     *
     * @param array $data
     * @author Mohannad Elemary
     */
    public function editProfile($data)
    {
        $this->repository->update($data, auth()->id(), false);
    }

    /**
     * Logout and revoke user token
     *
     * @author Mohannad Elemary
     */
    public function logout()
    {
        $user = auth()->user();
        $user->token()->revoke();
        if (request()->platform) {
            $user->firebaseTokens()->where('platform', request()->platform)->delete();
        }
        event(new TenantUserLoggedOut($user));
    }

    /**
     * Verify User's account and set his password
     *
     * @param  array $data
     * @author Mohannad Elemary
     */
    public function verify($data)
    {
        // Check if link is valid
        $user = $this->checkValidationLinkValidity();

        // Just check link validity if check flag is sent. No updates will happen
        if (isset($data['check'])) {
            return abort(new SuccessResource([]));
        }

        // Update the password of the user
        $this->repository->update($data, $user->id, false);

        // Update his client and verify him
        event(new TenantUserSetPassword($user));
    }

    /**
     * Check if The validation link is valid
     *
     * @return TenantUser $user
     * @author Mohannad Elemary
     */
    public function checkValidationLinkValidity()
    {
        // Check if link signature is valid
        if (!request()->hasValidSignature(false)) {
            return abort(new FailureResource([], __('usermanagementsystem/messages.invalid_reset_token'), Response::HTTP_BAD_REQUEST));
        }

        // Get The desired user to update
        $user = $this->repository->find(request()->route('id'));

        // Validating link hash
        if (!hash_equals((string) request()->route('hash'), sha1($user->getEmailForVerification()))) {
            return abort(new FailureResource([], __('usermanagementsystem/messages.invalid_reset_token'), Response::HTTP_BAD_REQUEST));
        }

        // Check if user already set his password
        if ($user->password) {
            return abort(new FailureResource([], __('usermanagementsystem/messages.invalid_reset_token'), Response::HTTP_BAD_REQUEST));
        }

        return $user;
    }

    /**
     * Update The Last Login time of the client user
     *
     * @param TenantUser $user
     * @return void
     * @author Mohannad Elemary
     */
    private function updateUserLoginTime($user)
    {
        $this->repository->update([
            'last_login_at' => Carbon::now()
        ], $user->id);
    }


    public function update(array $data, $id, $resource = true)
    {
        $update =  $this->repository->update($data, $id, false, $resource);

        $domain = getClientDomain(request());
        event(new TenantUserUpdatedBroadcast($domain, $id));

        return $update;
    }


    public function delete($id)
    {
        $this->repository->delete($id);
        $domain = getClientDomain(request());
        event(new TenantUserDeletedBroadcast($domain, $id));
    }

    /**
     * Set User's Firebase Token
     *
     * @return void
     * @author Mohannad Elemary
     */
    public function setFirebaseToken($data)
    {
        auth()->user()->firebaseTokens()->updateOrCreate([
            'platform'  => $data['platform'],
            'lang'      => $data['lang'],
        ], [
            'token'     => $data['token']
        ]);
    }
}
