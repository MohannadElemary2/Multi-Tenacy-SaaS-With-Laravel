<?php

namespace Modules\Admin\Services;

use Modules\Admin\Repositories\SettingsRepository;
use App\Services\BaseService;

class SettingsService extends BaseService
{
    public function __construct(SettingsRepository $repository)
    {
        parent::__construct($repository);
    }

    public function bulkUpdate($data)
    {
        return $this->repository->bulkUpdate($data);
    }
}
