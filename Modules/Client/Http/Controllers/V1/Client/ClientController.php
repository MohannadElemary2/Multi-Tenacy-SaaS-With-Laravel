<?php

namespace Modules\Client\Http\Controllers\V1\Client;

use Modules\Client\Services\ClientService;
use App\Http\Controllers\BaseController;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\Response;

class ClientController extends BaseController
{
    public function __construct(ClientService $service)
    {
        parent::__construct($service);
    }

    /**
     * Check if the current domain the user requesting on is valid and exists
     *
     * @return SuccessResource
     * @author Mohannad Elemary
     */
    public function checkDomainExistence()
    {
        $this->service->checkDomainExistence();
        return new SuccessResource([], '', Response::HTTP_OK);
    }

}
