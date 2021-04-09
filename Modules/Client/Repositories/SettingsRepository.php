<?php

namespace Modules\Client\Repositories;

use Modules\Client\Filters\SettingsFilter;
use App\Repositories\BaseRepository;
use Modules\Client\Entities\Client\Settings;

class SettingsRepository extends BaseRepository
{
    public function model()
    {
        return Settings::class;
    }

    public function indexResource()
    {
        return $this->resource::collection($this->getModelData(app(SettingsFilter::class)));
    }

    public function bulkUpdate($data)
    {
        foreach ($data['settings'] as $value) {
            $this->model->where(['key' => $value['key']])->update($value);
        }
    }
}
