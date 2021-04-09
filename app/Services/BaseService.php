<?php

namespace App\Services;

use App\Repositories\BaseRepositoryInterface;

class BaseService implements BaseServiceInterface
{
    protected $repository;
    public $relations;
    public $pagination;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function index()
    {
        return $this->repository->index();
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function store($data)
    {
        return $this->repository->create($data, false);
    }

    public function update(array $data, $id, $resource = true)
    {
        return $this->repository->update($data, $id, false, $resource);
    }

    public function delete($id)
    {
        $this->repository->delete($id);
    }

    public function setResource($resource)
    {
        $this->repository->setResource($resource);
        return $this;
    }

    public function setRelations($relations = [])
    {
        $this->repository->setRelations($relations);
        return $this;
    }

    public function setPagination($pagination = 20)
    {
        $this->repository->setPagination($pagination);
        return $this;
    }

    public function setScopes($scopes = [])
    {
        $this->repository->setScopes($scopes);
        return $this;
    }

    public function setResourceAdditional()
    {
        $this->repository->setResourceAdditional([]);
        return $this;
    }
}
