<?php

namespace Modules\Client\Repositories;

use App\Repositories\BaseRepository;
use Modules\Client\Entities\Client\ProductHistoryActivity;

class ProductHistoryActivityRepository extends BaseRepository
{
    public function model()
    {
        return ProductHistoryActivity::class;
    }

    public function distinct($column)
    {
        $this->model = $this->model->distinct($column);
        return $this;
    }
}
