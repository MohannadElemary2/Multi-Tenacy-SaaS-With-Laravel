<?php

namespace Modules\Client\Http\Controllers\V1\System;

use Modules\Client\Services\ClientService;
use Modules\Client\Http\Requests\StoreClientRequest;
use Modules\Client\Http\Requests\UpdateClientRequest;
use App\Http\Controllers\BaseController;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\Response;
use Modules\Client\Http\Requests\CheckDomainRequest;
use Modules\Client\Http\Requests\FilterClientStatsRequest;
use Modules\Client\Transformers\ClientResource;
use Modules\Client\Transformers\ClientShowResource;

class ClientController extends BaseController
{
    protected $storeRequestFile = StoreClientRequest::class;
    protected $updateRequestFile = UpdateClientRequest::class;
    protected $resource = ClientResource::class;
    protected $showResource = ClientShowResource::class;

    public function __construct(ClientService $service)
    {
        parent::__construct($service);
    }

    public function checkDomain(CheckDomainRequest $request)
    {
        return  new SuccessResource([], '', Response::HTTP_OK);
    }

    public function show($id)
    {
        $this->service->setResource($this->showResource);
        resolve(FilterClientStatsRequest::class);
        
        $data = $this->service->show($id);
        return new SuccessResource($data);
    }
}
