<?php


namespace App\Repositories;


interface BaseRepositoryInterface
{
    public function first($columns = ['*'], $fail = true);

    public function index();

    public function show($id);

    public function find($id, $columns = ['*'], $fail = true);

    public function findBy($attribute, $value);

    public function where(array $where, $boolean = 'and');

    public function create(array $data, $force = true, $resource = true);

    public function update(array $data, $id = null, $force = true, $resource = true);

    public function delete($id = null);

    public function exists();

    public function random($qtd = 15);

    public function with($relations);

    public function all($columns = ['*']);

    public function get($columns = ['*']);

    public function setRelations($relations);

    public function setPagination($pagination);

    public function setResource($resource);

    public function setScopes($scopes);

    public function setResourceAdditional($scopes);
}
