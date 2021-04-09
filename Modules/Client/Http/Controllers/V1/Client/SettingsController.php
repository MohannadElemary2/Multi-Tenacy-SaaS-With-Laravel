<?php

namespace Modules\Client\Http\Controllers\V1\Client;

use Modules\Client\Services\SettingsService;
use Modules\Client\Http\Requests\UpdateSettingsRequest;
use App\Http\Controllers\BaseController;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\Response;
use Modules\Client\Transformers\SettingsResource;

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
        $this->bulkUpdateAuthorization(request()->all(), 'edit');
        $this->service->bulkUpdate($request->all());
        return  new SuccessResource([], __('general.success_update'), Response::HTTP_OK);
    }

    protected function bulkUpdateAuthorization($data, $action)
    {
        $settings = is_array($data) ? ($data['settings'] ?? []) : [];
        $settingsKeys = array_filter(array_column($settings, 'key'));
        foreach ($settingsKeys as $key) {
            $policyMethodName = $this->getSettingsPolicyMethodName($key, $action);
            $this->enablePolicy($policyMethodName);
        }
    }

    protected function getSettingsPolicyMethodName($settingsKey, $action)
    {
        // convert snake case to camel case. ex: replace time_zone to timeZone
        $camelCaseKey =  preg_replace_callback('/_([a-z]?)/', function ($match) {
            return strtoupper($match[1]);
        }, $settingsKey);
        return   $action . ucfirst($camelCaseKey);
    }
}
