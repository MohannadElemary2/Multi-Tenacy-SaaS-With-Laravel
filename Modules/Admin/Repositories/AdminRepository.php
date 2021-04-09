<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Filters\AdminFilter;
use App\Repositories\BaseRepository;
use Modules\Admin\Entities\System\User;

class AdminRepository extends BaseRepository
{
	public function model()
    {
        return User::class;
    }

    public function indexResource()
    {
        return $this->resource::collection($this->getModelData(app(AdminFilter::class)));
    }
}
