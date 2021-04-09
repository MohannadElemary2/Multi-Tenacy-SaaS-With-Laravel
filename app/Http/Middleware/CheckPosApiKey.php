<?php

namespace App\Http\Middleware;

use App\Http\Resources\FailureResource;
use Closure;
use Modules\Catalog\Enums\PosConfigurationKeys;
use Modules\Order\Enums\PosIntegrationsTags;
use Modules\Order\Repositories\PosConfigurationRepository;

class CheckPosApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiConfig = app(PosConfigurationRepository::class)
            ->where(['key' => PosConfigurationKeys::API_KEY])
            ->whereHas('posIntegration', function ($q) {
                $q->where('tag', PosIntegrationsTags::DYNAMICS_AX);
            })->first(['value'], false);

        $apiKey = $apiConfig ? $apiConfig->value : null;

        if ($request->header('API-KEY') != $apiKey) {
            abort(new FailureResource([], __('messages.wrong_pos_api_key')));
        }

        return $next($request);
    }
}
