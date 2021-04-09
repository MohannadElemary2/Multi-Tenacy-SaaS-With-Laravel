<?php

namespace Modules\Client\Repositories;

use App\Repositories\BaseRepository;
use Modules\Client\Entities\Client\HubHistory;

class HubHistoryRepository extends BaseRepository
{
    public function model()
    {
        return HubHistory::class;
    }

    public function max($column)
    {
        return $this->model->max($column);
    }
}
