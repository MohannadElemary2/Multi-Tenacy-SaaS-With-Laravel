<?php

namespace Modules\UserManagementSystem\Services;

use App\Http\Resources\FailureResource;
use Modules\UserManagementSystem\Repositories\RoleRepository;
use App\Services\BaseService;
use Illuminate\Http\Response;
use Modules\UserManagementSystem\Enums\PredefinedRoles;
use Modules\UserManagementSystem\Events\TenantUserUpdatedBroadcast;

class RoleService extends BaseService
{
    public function __construct(RoleRepository $repository)
    {
        parent::__construct($repository);
    }


    public function update(array $data, $id, $resource = true)
    {
        $role = $this->repository->find($id);

        if ($this->isRolePredefined($role)) {
            return abort(new FailureResource([], __('usermanagementsystem/messages.roles.cant_be_updated'), Response::HTTP_BAD_REQUEST));
        }
        
        $update =  $this->repository->update($data, $id, false, false);

        $users = $update->users()->select(['id'])->get();
        $domain = getClientDomain(request());
        $users->each(function ($user) use ($domain) {
            event(new TenantUserUpdatedBroadcast($domain, $user->id));
        });

        return $update;
    }

    public function delete($id)
    {
        $role = $this->repository->find($id);

        if ($this->isRolePredefined($role)) {
            return abort(new FailureResource([], __('usermanagementsystem/messages.roles.cant_be_deleted'), Response::HTTP_BAD_REQUEST));
        }

        return $this->repository->delete($id);
    }

    public function getPredefinedRolesNames()
    {
        $predefinedRolesNames = [];
        foreach (PredefinedRoles::getValues() as $role) {
            $roleName = strtolower($role['role']['en']['name']) ?? null;
            $roleName ? array_push($predefinedRolesNames, $roleName) : null;
        }
        return $predefinedRolesNames;
    }

    public function isRolePredefined($role)
    {
        $roleName = strtolower($role->name) ?? null;
        $predefinedRolesNames = $this->getPredefinedRolesNames();
        return in_array($roleName, $predefinedRolesNames);
    }
}
