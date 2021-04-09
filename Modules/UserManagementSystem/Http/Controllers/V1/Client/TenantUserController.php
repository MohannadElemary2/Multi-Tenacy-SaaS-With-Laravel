<?php

namespace Modules\UserManagementSystem\Http\Controllers\V1\Client;

use App\Http\Controllers\BaseController;
use App\Http\Resources\SuccessResource;
use Modules\UserManagementSystem\Http\Requests\SetFirebaseTokenRequest;
use Modules\UserManagementSystem\Http\Requests\StoreTenantUserRequest;
use Modules\UserManagementSystem\Http\Requests\UpdateTenantUserRequest;
use Modules\UserManagementSystem\Services\TenantUserService;
use Modules\UserManagementSystem\Transformers\TenantUser\TenantUserResource;

class TenantUserController extends BaseController
{
    protected $storeRequestFile = StoreTenantUserRequest::class;
    protected $updateRequestFile = UpdateTenantUserRequest::class;
    protected $resource = TenantUserResource::class;
    protected $relations = ['roles', 'createdBy'];
    protected $enablePolicy = true;

    public function __construct(TenantUserService $service)
    {
        parent::__construct($service);
    }

    /**
     * Set User's Firebase Token
     *
     * @param SetFirebaseTokenRequest $request
     * @return SuccessResource
     * @author Mohannad Elemary
     */
    public function setFirebaseToken(SetFirebaseTokenRequest $request)
    {
        $this->service->setFirebaseToken($request->all());

        return new SuccessResource([]);
    }
}
