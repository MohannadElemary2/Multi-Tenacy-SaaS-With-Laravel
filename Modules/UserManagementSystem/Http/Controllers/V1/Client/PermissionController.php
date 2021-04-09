<?php

namespace Modules\UserManagementSystem\Http\Controllers\V1\Client;

use Modules\UserManagementSystem\Services\PermissionService;
use App\Http\Controllers\BaseController;
use Modules\UserManagementSystem\Transformers\Permission\PermissionResource;

class PermissionController extends BaseController
{
    protected $resource = PermissionResource::class;

    public function __construct(PermissionService $service)
    {
        parent::__construct($service);
    }

}
