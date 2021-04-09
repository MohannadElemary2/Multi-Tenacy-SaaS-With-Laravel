<?php

namespace Modules\UserManagementSystem\Services;

use Modules\UserManagementSystem\Repositories\PermissionRepository;
use App\Services\BaseService;

class PermissionService extends BaseService
{
    public function __construct(PermissionRepository $repository)
    {
        parent::__construct($repository);
    }
}
