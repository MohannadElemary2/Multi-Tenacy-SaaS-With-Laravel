<?php

namespace Modules\UserManagementSystem\Http\Controllers\V1\Client;

use Modules\UserManagementSystem\Services\TenantUserService;
use Modules\UserManagementSystem\Http\Requests\StoreTenantUserRequest;
use Modules\UserManagementSystem\Http\Requests\UpdateTenantUserRequest;
use App\Http\Controllers\BaseController;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\Response;
use Modules\UserManagementSystem\Http\Requests\LoginRequest;
use Modules\UserManagementSystem\Http\Requests\VerifyEmailRequest;
use Modules\UserManagementSystem\Transformers\TenantUser\TenantUserResource;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends BaseController
{
    protected $storeRequestFile = StoreTenantUserRequest::class;
    protected $updateRequestFile = UpdateTenantUserRequest::class;
    protected $resource = TenantUserResource::class;
    protected $relations = ['roles.permissions'];

    public function __construct(TenantUserService $service)
    {
        parent::__construct($service);
    }

    /**
     * Login tenant user and retrieve token from oauth server
     *
     * @param  LoginRequest     $request
     * @param  ServerRequestInterface $serverRequest
     * @return FailureResource|SuccessResource
     * @author Mohannad Elemary
     */
    public function login(LoginRequest $request, ServerRequestInterface $serverRequest)
    {
        $data = $this->service->login($request->all(), $serverRequest);
        return new SuccessResource(
            $data,
            __('usermanagementsystem/messages.profile.success_login'),
            Response::HTTP_OK,
            $data
        );
    }

    /**
     * Verify User's email and set his password
     *
     * @param  VerifyEmailRequest     $request
     * @return FailureResource|SuccessResource
     * @author Mohannad Elemary
     */
    public function verify(VerifyEmailRequest $request)
    {
        $this->service->verify($request->all());
        return new SuccessResource([], __('usermanagementsystem/messages.verify_account.success'));
    }
}
