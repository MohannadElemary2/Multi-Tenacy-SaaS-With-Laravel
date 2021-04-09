<?php

namespace Modules\Client\Repositories;

use Modules\Client\Filters\ClientFilter;
use App\Repositories\BaseRepository;
use Modules\Client\Entities\System\Client;

class ClientRepository extends BaseRepository
{
	public function model()
    {
        return Client::class;
    }

    public function indexResource()
    {
        return $this->resource::collection($this->getModelData(app(ClientFilter::class)));
    }
}
