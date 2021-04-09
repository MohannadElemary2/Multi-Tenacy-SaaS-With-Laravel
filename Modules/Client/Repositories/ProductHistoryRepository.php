<?php

namespace Modules\Client\Repositories;

use App\Repositories\BaseRepository;
use Modules\Client\Entities\Client\ProductHistory;

class ProductHistoryRepository extends BaseRepository
{
    public function model()
    {
        return ProductHistory::class;
    }
}
