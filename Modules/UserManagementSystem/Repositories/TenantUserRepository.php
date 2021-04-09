<?php

namespace Modules\UserManagementSystem\Repositories;

use Modules\UserManagementSystem\Filters\TenantUserFilter;
use App\Repositories\BaseRepository;
use Modules\Order\Policies\OrderPolicy;
use Modules\Picking\Enums\BatchStatus;
use Modules\UserManagementSystem\Entities\Client\TenantUser;

class TenantUserRepository extends BaseRepository
{
    public function model()
    {
        return TenantUser::class;
    }

    public function indexResource()
    {
        return $this->resource::collection($this->getModelData(app(TenantUserFilter::class)));
    }

    protected function createOrUpdateOneToManyRelations($model, $data, $isUpdate = false)
    {
        $this->createOrUpdateRelations($model->roles(), 'roles', $data);
    }

    /**
     * Get Picker Who Manages Specific Hub
     *
     * @param int $hubID
     * @return Collection
     * @author Mohannad Elemary
     */
    public function managingHubAsPicker($hubID)
    {
        return $this->whereHas('hubs', function ($q) use ($hubID) {
            $q->where('hubs.id', $hubID);
        })
        ->whereHas('rolesBasic', function ($roles) {
            $roles->whereHas('permissions', function ($permissions) {
                $permissions->where('name', OrderPolicy::PICKING);
            });
        })
        ->whereDoesntHave('pickerBatches', function ($q) {
            $q->where('status', BatchStatus::PICKING);
        })
        ->get();
    }

    public function permission($permission)
    {
        $this->model =  $this->model->permission($permission);
        return $this;
    }
}
