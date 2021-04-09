<?php

namespace Modules\UserManagementSystem\Http\Controllers\V1\Client;

use Modules\UserManagementSystem\Services\RoleService;
use Modules\UserManagementSystem\Http\Requests\StoreRoleRequest;
use Modules\UserManagementSystem\Http\Requests\UpdateRoleRequest;
use App\Http\Controllers\BaseController;
use Modules\UserManagementSystem\Transformers\Role\RoleResource;

class RoleController extends BaseController
{
    protected $storeRequestFile = StoreRoleRequest::class;
    protected $updateRequestFile = UpdateRoleRequest::class;
    protected $resource = RoleResource::class;
    protected $relations = ['permissions'];
    protected $scopes = ['withUsersCount', 'withTranslation'];
    protected $enablePolicy = true;

    public function __construct(RoleService $service)
    {
        parent::__construct($service);
    }
}
