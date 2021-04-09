<?php

namespace Modules\UserManagementSystem\Services;

use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use Modules\UserManagementSystem\Repositories\TenantUserRepository;
use App\Services\BaseService;
use App\Traits\HasAuthentications;
use Closure;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Arr;

class PasswordService extends BaseService
{
    use HasAuthentications;

    public function __construct(TenantUserRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Forget tenant user password
     *
     * @param  array  $data
     * @author Mohannad Elemary
     */
    public function forgotPassword($data)
    {
        $user = $this->repository->where([
            'email' => $data['email']
        ])->first(['*'], false);

        // Validate Account
        if (!$user) {
            return abort(new FailureResource([], __('usermanagementsystem/messages.forgot_password.email_not_found'), Response::HTTP_BAD_REQUEST));
        }

        // get password broker
        $passwordBroker = Password::broker();
        $passwordBrokerRepository = $passwordBroker->getRepository();
        // check throttle
        if ($passwordBrokerRepository->recentlyCreatedToken($user)) {
            return abort(new FailureResource([], __('usermanagementsystem/messages.forgot_password.recently_created'), Response::HTTP_BAD_REQUEST));
        }

        // create token
        $token = $passwordBroker->createToken($user);
        // send notification
        $domain = $this->getTenantUserDomain();
        $user->sendPasswordResetNotification(
            $token,
            $domain
        );
    }

    /**
     * Reset tenant user password
     *
     * @param  array  $data
     * @author Mohannad Elemary
     */
    public function resetPassword($data)
    {
        $response = $this->reset($data, function ($user, $password) use ($data) {
            $this->repository->update(Arr::except($data, ['id', 'token']), $user->id, false);
            $this->revokeAllUserTokens($user);
            $domain = $this->getTenantUserDomain();
            // send notification
            $user->sendPasswordChangedNotification($domain);
        });
        switch ($response) {
            case PasswordBroker::INVALID_USER:
                return abort(new FailureResource([], __('usermanagementsystem/messages.reset_password.user_not_found'), Response::HTTP_BAD_REQUEST));
                break;
            case PasswordBroker::INVALID_TOKEN:
                return abort(new FailureResource([], __('usermanagementsystem/messages.reset_password.invalid_token'), Response::HTTP_BAD_REQUEST));
                break;
        }
    }

    /**
     * Reset the password for the given token.
     *
     * @param  array  $data
     * @param  \Closure  $callback
     * @return mixed
     */
    private function reset(array $data, Closure $callback)
    {
        // Check if user and token are valid
        $user = $this->validateReset($data['id'], $data['token']);

        if (!$user instanceof CanResetPasswordContract) {
            abort(new FailureResource(
                [],
                __('usermanagementsystem/messages.reset_password.invalid_token'),
                Response::HTTP_BAD_REQUEST
            ));
        }

        if ($data['check'] ?? null) {
            abort(new SuccessResource(
                [],
                __('usermanagementsystem/messages.reset_password.valid_token')
            ));
        }

        $password = $data['password'];

        // Once the reset has been validated, we'll call the given callback with the
        // new password. This gives the user an opportunity to store the password
        // in their persistent storage. Then we'll delete the token and return.
        $callback($user, $password);
        $passwordBroker = Password::broker();
        $passwordBrokerRepository = $passwordBroker->getRepository();
        $passwordBrokerRepository->delete($user);

        return passwordBroker::PASSWORD_RESET;
    }
    /**
     * Validate a password reset for the given data.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Auth\CanResetPassword|string
     */
    private function validateReset($userId, $token)
    {
        $user = $this->repository->find($userId, ['*'], false);

        if (is_null($user)) {
            return passwordBroker::INVALID_USER;
        }

        $passwordBroker = Password::broker();
        $passwordBrokerRepository = $passwordBroker->getRepository();

        if (!$passwordBrokerRepository->exists($user, $token)) {
            return passwordBroker::INVALID_TOKEN;
        }

        return $user;
    }


    /**
     * Revoke all tenant user tokens
     *
     * @param TenantUser $user
     * @return void
     * @author Mohannad Elemary
     */
    public function revokeAllUserTokens($user)
    {
        $userTokens = $user->tokens;
        foreach ($userTokens as $token) {
            $token->revoke();
        }
    }

    /**
     * Change tenant change password
     *
     * @param  array  $data
     * @author Mohannad Elemary
     */
    public function changePassword($data)
    {
        $user = auth()->user();

        // Validate Account
        if (!Hash::check($data['old_password'], $user->getAuthPassword())) {
            return abort(new FailureResource([], __('usermanagementsystem/messages.change_password.invalid_old_password'), Response::HTTP_BAD_REQUEST));
        }
        $this->repository->update($data, $user->id, false);
        $this->revokeAllUserTokens($user);

        $domain = $this->getTenantUserDomain();
        // send notification
        $user->sendPasswordChangedNotification($domain);
    }

    private function getTenantUserDomain()
    {
        $host = request()->getHost();
        if (is_string($host)) {
            $domain = explode('.', request()->getHost())[0];
        }
        return $domain ?? null;
    }
}
