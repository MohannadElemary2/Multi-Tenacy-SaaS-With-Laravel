<?php

namespace Modules\UserManagementSystem\Repositories;

use Modules\UserManagementSystem\Entities\Client\Role;
use Modules\UserManagementSystem\Filters\RoleFilter;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository
{
	public function model()
    {
        return Role::class;
    }

    public function indexResource()
    {
        return $this->resource::collection($this->getModelData(app(RoleFilter::class)));
    }
    
    protected function createOrUpdateOneToManyRelations($model, $data, $isUpdate = false)
    {
        $this->createOrUpdateRelations($model->permissions(), 'permissions', $data);
    }
}
