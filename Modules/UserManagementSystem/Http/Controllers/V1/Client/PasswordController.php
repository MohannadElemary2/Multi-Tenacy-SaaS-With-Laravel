<?php

namespace Modules\UserManagementSystem\Http\Controllers\V1\Client;

use App\Http\Controllers\BaseController;
use Modules\UserManagementSystem\Services\PasswordService;

use App\Http\Resources\SuccessResource;
use Modules\UserManagementSystem\Http\Requests\ChangePasswordRequest;
use Modules\UserManagementSystem\Http\Requests\ForgotPasswordRequest;
use Modules\UserManagementSystem\Http\Requests\ResetPasswordRequest;
use Modules\UserManagementSystem\Transformers\TenantUser\TenantUserResource;

class PasswordController extends BaseController
{
    protected $resource = TenantUserResource::class;

    public function __construct(PasswordService $service)
    {
        parent::__construct($service);
    }

    /**
     * forgot tenant user password
     *
     * @param  ForgotPasswordRequest $request
     * @author Mohannad Elemary
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $this->service->forgotPassword($request->all());
        return new SuccessResource(
            [],
            __('usermanagementsystem/messages.forgot_password.success')
        );
    }

    /**
     * reset tenant user password
     *
     * @param  ResetPasswordRequest $request
     * @author Mohannad Elemary
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $this->service->resetPassword($request->all());
        return new SuccessResource(
            [],
            __('usermanagementsystem/messages.reset_password.success')
        );
    }

    /**
     * Change tenant user password
     *
     * @param  ChangePasswordRequest $request
     * @author Mohannad Elemary
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $this->service->changePassword($request->all());
        return new SuccessResource(
            $user,
            __('usermanagementsystem/messages.change_password.success')
        );
    }
}
