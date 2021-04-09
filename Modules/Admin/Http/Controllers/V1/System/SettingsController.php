<?php

namespace Modules\Admin\Http\Controllers\V1\System;

use Modules\Admin\Services\SettingsService;
use Modules\Admin\Http\Requests\UpdateSettingsRequest;
use App\Http\Controllers\BaseController;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\Response;
use Modules\Admin\Transformers\SettingsResource;

class SettingsController extends BaseController
{
    protected $updateRequestFile = UpdateSettingsRequest::class;
    protected $resource = SettingsResource::class;
    protected $enablePolicy = true;

    public function __construct(SettingsService $service)
    {
        parent::__construct($service);
    }

    public function bulkUpdate()
    {
        $request = resolve($this->updateRequestFile);
        $this->service->bulkUpdate($request->all());
        return  new SuccessResource([], __('general.success_update'), Response::HTTP_OK);
    }
}
