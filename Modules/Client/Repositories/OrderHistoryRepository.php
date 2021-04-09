<?php

namespace Modules\Client\Repositories;

use App\Repositories\BaseRepository;
use Modules\Client\Entities\Client\OrderHistory;

class OrderHistoryRepository extends BaseRepository
{
    public function model()
    {
        return OrderHistory::class;
    }
}
