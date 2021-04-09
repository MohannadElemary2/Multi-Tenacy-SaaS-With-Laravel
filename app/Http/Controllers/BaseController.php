<?php

namespace App\Http\Controllers;

use App\Http\Resources\SuccessResource;
use App\Services\BaseServiceInterface;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    protected $service;
    protected $storeRequestFile;
    protected $updateRequestFile;
    protected $relations = [];
    protected $resource;
    protected $showResource;
    protected $pagination;
    protected $scopes = [];
    protected $enablePolicy;

    public function __construct(BaseServiceInterface $service)
    {
        $this->service = $service;
        $this->constructRepository();
    }

    public function index()
    {
        $this->enablePolicy('viewAny');

        return $this->service->index();
    }

    public function show($id)
    {
        $this->enablePolicy('view');
        $this->service->setResource($this->showResource ?? $this->resource);
        $data = $this->service->show(is_object($id) ? $id->id : $id);
        return  new SuccessResource($data);
    }

    public function store()
    {
        $this->enablePolicy('create');

        $request = $this->storeRequestFile ? resolve($this->storeRequestFile) : request();
        $data = $this->service->store($request->all());
        return  new SuccessResource($data, __('general.success_create'), Response::HTTP_CREATED);
    }

    public function update($id)
    {
        $this->enablePolicy('update');

        $request = $this->updateRequestFile ? resolve($this->updateRequestFile) : request();
        $this->service->update($request->all(), is_object($id) ? $id->id : $id);
        return  new SuccessResource([], __('general.success_update'), Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $this->enablePolicy('delete');

        $this->service->delete(is_object($id) ? $id->id : $id);
        return  new SuccessResource([], __('general.success_distroy'), Response::HTTP_OK);
    }

    public function export()
    {
        $this->enablePolicy('export');

        $this->service->export();
        return  new SuccessResource([], '', Response::HTTP_OK);
    }

    private function constructRepository()
    {
        $this->service->setRelations($this->relations);
        $this->service->setResource($this->resource);
        $this->service->setPagination(request('per_page') ?? ($this->pagination ?? 20));
        $this->service->setScopes($this->scopes);
        $this->service->setResourceAdditional([]);
    }

    protected function enablePolicy($action, $args = null)
    {
        if ($this->enablePolicy) {
            $this->authorize($action, $args ?: $this->service->getRepository()->model());
        }
    }
}
