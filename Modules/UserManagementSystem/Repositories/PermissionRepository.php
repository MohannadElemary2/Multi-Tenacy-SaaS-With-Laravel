<?php

namespace Modules\UserManagementSystem\Repositories;

use Modules\UserManagementSystem\Entities\Client\Permission;
use Modules\UserManagementSystem\Filters\PermissionFilter;
use App\Repositories\BaseRepository;

class PermissionRepository extends BaseRepository
{
    private $excludedPermissions = ['picker_picking', 'dispatcher_dispatching', 'delete_hubs'];
    
    public function model()
    {
        return Permission::class;
    }

    /**
     * Return The permissions grouped by their categories (groups)
     * By handling the group name as a suffix in the permission name
     *
     * @return array
     * @author Mohannad Elemary
     */
    public function index()
    {
        $permissions = $this->all();
        $data = [];
        foreach ($permissions as $permission) {
            if (in_array($permission->name, $this->excludedPermissions)) {
                continue;
            }

            // Get permission group name ( the suffix )
            $name = explode('_', $permission->name)[1];
            $name = __('usermanagementsystem/permissions.' . $name, [], request()->header('Accept-Language'));
            // Add the permission under its group in the result array
            $data[$name][] = $this->wrapData($permission);
        }
        return ['data' => $data];
    }

    public function indexResource()
    {
        return $this->resource::collection($this->getModelData(app(PermissionFilter::class)));
    }
}
