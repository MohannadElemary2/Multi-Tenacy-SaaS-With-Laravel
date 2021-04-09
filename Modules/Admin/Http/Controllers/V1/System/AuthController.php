<?php

namespace Modules\Admin\Http\Controllers\V1\System;

use App\Http\Controllers\BaseController;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\Response;
use Modules\Admin\Http\Requests\LoginRequest;
use Modules\Admin\Services\AdminService;
use Modules\Admin\Transformers\AdminResource;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends BaseController
{
    protected $resource = AdminResource::class;

    public function __construct(AdminService $service)
    {
        parent::__construct($service);
    }

    /**
     * Login admin and retrieve token from oauth server
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
}
