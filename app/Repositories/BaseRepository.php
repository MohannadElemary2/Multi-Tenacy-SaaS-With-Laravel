<?php


namespace App\Repositories;

use App\Transformers\DropdownResource;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder as BuilderAlias;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Container\Container as App;
use App\Exceptions\RepositoryException;
use App\Http\Filters\Filter;
use Closure;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;
    /**
     * @var Collection
     */
    protected $scopes;
    /**
     * @var Collection
     */
    protected $criteria;

    private $app;

    /**
     * @var array
     */
    protected $relations;

    /**
     * @var JsonResource | ResourceCollection
     */
    public $resource;

    /**
     * @var integer
     */
    protected $pagination;

    protected $resourceAdditional = [];

    /**
     * @param  App $app
     * @throws RepositoryException
     */

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->cleanRepository();
    }

    /**
     * Set resource used in wrapping data
     *
     * @param  $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Set relations needed to be eager loaded
     *
     * @param  $relations
     */
    public function setRelations($relations)
    {
        $this->relations = $relations;
    }

    /**
     * Set pagination count
     *
     * @param  $pagination
     */
    public function setPagination($pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * Get model scopes
     *
     * @param  $scopes
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * Set model scopes
     *
     * @param  $scopes
     */
    public function setScopes($scopes)
    {
        $this->scopes = $scopes;
    }

    /**
     * @param  $resourceAdditional
     */
    public function setResourceAdditional($resourceAdditional)
    {
        $this->resourceAdditional = $resourceAdditional;
    }

    public function index()
    {
        if (request('dropdown') && is_array($this->model->dropdownAttributes) && $this->model->dropdownAttributes) {
            $this->setResource(DropdownResource::class);
        }

        $resource = $this->resource ? $this->indexResource()->additional(
            array_merge([], !is_array($this->resourceAdditional) ? [] : $this->resourceAdditional)
        ) : $this->getModelData();
        $this->cleanRepository();
        return $resource;
    }

    public function indexResource()
    {
        return $this->resource::collection($this->getModelData());
    }

    public function wrapData($data)
    {
        return $this->resource ? new $this->resource($data) : $data;
    }

    public function filter($filter)
    {
        return $this->model->filter($filter);
    }

    public function getModelData(Filter $filter = null)
    {
        $model = $filter ? $this->filter($filter) : $this->model;

        $model = $this->applyScopes($model);

        if ($this->relations) {
            $model->with($this->relations);
        }

        return $this->pagination ? $model->paginate($this->pagination) : $model->get();
    }

    public function show($id)
    {
        $model = $this->model->query();

        $model = $this->applyScopes($model);

        if ($this->relations) {
            $model = $model->with($this->relations);
        }

        $model = $model->findOrFail($id);

        $resource = $this->wrapData($model);
        $this->cleanRepository();
        return $resource;
    }

    public function makeResource()
    {
        if ($this->resource) {
            if (
                !is_subclass_of($this->resource, 'Illuminate\Http\Resources\Json\ResourceCollection')
                && !is_subclass_of($this->resource, 'Illuminate\Http\Resources\Json\JsonResource')
            ) {
                throw new RepositoryException(
                    "Class {$this->resource} must be an instance of Illuminate\\Http\\Resources\\Json\\ResourceCollection or
            Illuminate\Http\Resources\Json\JsonResource"
                );
            }
        }

        return $this->resource;
    }

    public function create(array $data, $force = true, $resource = true)
    {
        $model = $force ? $this->model->forceCreate($data) : $this->model->create($data);
        $this->createOrUpdateOneToManyRelations($model, $data);
        $resource = $resource && $this->resource ? new $this->resource($model) : $model;
        $this->cleanRepository();
        return $resource;
    }

    public function update(array $data, $id = null, $force = true, $resource = true, $withTrashed = false)
    {
        if (is_null($id) and $this->model instanceof Builder) {
            $object = $withTrashed ? $this->withTrashed()->first() : $this->first();
            $model = $object;
        } elseif ($id && is_object($id) && is_subclass_of($id, Model::class)) {
            $model = $id;
            $object = $model;
        } else {
            $object = $withTrashed ? $this->withTrashed()->find($id) : $this->find($id);
            $model = $object;
        }

        $model = $force ? $model->forceFill($data)->save() : $model->update($data);
        $this->createOrUpdateOneToManyRelations($object->fresh(), $data, true);
        $resource = $resource && $this->resource ? new $this->resource($object->fresh()) : $object->fresh();
        $this->cleanRepository();
        return $resource;
    }

    protected function createOrUpdateOneToManyRelations($model, $data, $isUpdate = false)
    {
    }

    public function delete($id = null)
    {
        $model = null;
        if (is_array($id)) {
            $model = $this->model->destroy($id);
        } elseif (!is_null($id)) {
            $model = $this->find($id, ['*'], true)->delete();
        } elseif ($this->model instanceof Builder) {
            $model = $this->first()->delete();
        }
        $this->cleanRepository();

        return $model;
    }

    public function createOrUpdateRelations($relation, $requestKey, $data, $pivot = [])
    {
        if (isset($data[$requestKey])) {
            $relation->sync([]);
            $relationArray = [];
            foreach (request($requestKey) as $relationId) {
                $relationArray[$relationId] = $pivot ? $pivot[$relationId] : $pivot;
            }

            $relation->sync($relationArray);
        }
    }

    /**
     * @throws RepositoryException
     */
    protected function cleanRepository()
    {
        $this->scopes = [];
        $this->criteria = new Collection();
        $this->makeModel();
        $this->makeResource();
    }


    /**
     * @return Model
     *
     * @throws RepositoryException
     * @throws BindingResolutionException
     */
    protected function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * @return mixed
     */
    abstract public function model();

    public function truncate()
    {
        return $this->model->truncate();
    }

    public function updateOrCreate($attr, $value)
    {
        return $this->model->updateOrCreate($attr, $value);
    }

    /**
     * @param string $attribute
     * @param mixed  $value
     *
     * @return $this
     */
    public function findBy($attribute, $value)
    {
        $this->model = $this->model->where($attribute, '=', $value);

        return $this;
    }

    public function whereIn($attribute, array $value)
    {
        return $this->model->whereIn($attribute, $value);
    }

    /**
     * Filter By Pivot Attribute
     *
     * @param string $relation
     * @param string $field
     * @param mixed $value
     * @return Builder
     * @author Mohannad Elemary
     */
    public function wherePivot($relation, $field, $value)
    {
        $this->model = $this->model->whereHas($relation, function ($query) use ($field, $value) {
            $query->where($field, $value);
        });
        return $this;
    }

    /**
     * @param  array  $where
     * @param  string $boolean
     * @return $this
     */
    public function where(array $where, $boolean = 'and')
    {
        foreach ($where as $k => $v) {
            list($field, $condition, $value) = is_array($v) ? $v : [$k, '=', $v];
            $this->model = $this->model->where($field, $condition, $value, $boolean);
        }

        return $this;
    }

    public function withTrashed()
    {
        $this->model = $this->model->withTrashed();
        return $this;
    }

    public function onlyTrashed()
    {
        $this->model = $this->model->onlyTrashed();
        return $this;
    }

    /**
     * @param  array $data
     * @return mixed
     * @throws RepositoryException
     */
    public function insert(array $data)
    {
        $model = $this->model->insert($data);
        $this->cleanRepository();
        return $model;
    }

    /**
     * @param array $columns
     * @param bool  $fail
     *
     * @return mixed
     * @throws RepositoryException
     */
    public function first($columns = ['*'], $fail = true)
    {
        $method = $fail ? 'firstOrFail' : 'first';
        $result = $this->model->{$method}($columns);
        $this->cleanRepository();

        return $result;
    }

    protected function applyScopes($model)
    {
        $scopes = $this->scopes;
        if ($scopes) {
            foreach ($scopes as $scope) {
                $model->{$scope}();
            }
        }
        return $model;
    }

    /**
     * @param $id
     * @param array $columns
     * @param bool  $fail
     *
     * @return mixed
     * @throws RepositoryException
     */
    public function find($id, $columns = ['*'], $fail = true)
    {
        $method = $fail ? 'findOrFail' : 'find';
        $result = $this->model->{$method}($id, $columns);
        $this->cleanRepository();

        return $result;
    }

    /**
     * @return mixed
     * @throws RepositoryException
     */
    public function exists()
    {
        $result = $this->model->exists();
        $this->cleanRepository();

        return $result;
    }

    /**
     * @param int $qtd
     *
     * @return $this
     */
    public function random($qtd = 15)
    {
        $this->model = $this->model->orderByRaw('RAND()')->take($qtd);
        return $this;
    }

    public function orderBy($column, $type)
    {
        $this->model = $this->model->orderBy($column, $type);
        return $this;
    }

    /**
     * @param $class
     * @param array $args
     *
     * @return $this
     */
    public function criteria($class, array $args = [])
    {
        $this->criteria->push([$class, $args]);

        return $this;
    }

    /**
     * @param array|string $relations
     *
     * @return $this
     */
    public function with($relations)
    {
        $this->model = $this->model->with($relations);

        return $this;
    }


    /**
     * @param array $columns
     *
     * @return mixed
     * @throws RepositoryException
     */
    public function all($columns = ['*'])
    {
        return $this->get($columns);
    }

    /**
     * @param array $columns
     *
     * @return mixed
     * @throws RepositoryException
     */
    public function get($columns = ['*'])
    {
        if ($this->model instanceof Builder || $this->model instanceof BuilderAlias) {
            $results = $this->model->get($columns);
        } else {
            $results = $this->model->all($columns);
        }
        $this->cleanRepository();

        return $results;
    }

    public function pluck($columns)
    {
        return $this->model->pluck($columns);
    }

    public function applyScopeOnModel($scopes)
    {
        $model = $this->model->query();
        if ($scopes) {
            foreach ($scopes as $scope => $param) {
                $model->{$scope}(...Arr::wrap($param));
            }
        }
        $this->model = $model;
        return $this;
    }

    public function count()
    {
        $results = $this->model->count();
        $this->cleanRepository();
        return $results;
    }

    public function whereNotIn($attribute, array $value)
    {
        $this->model =  $this->model->whereNotIn($attribute, $value);
        return $this;
    }

    public function whereHas($relation, Closure $callback = null, $operator = '>=', $count = 1)
    {
        $this->model =  $this->model->whereHas($relation, $callback, $operator, $count);
        return $this;
    }

    public function whereDoesntHave($relation, Closure $callback = null)
    {
        $this->model =  $this->model->whereDoesntHave($relation, $callback);
        return $this;
    }

    public function doesntHave($relation, Closure $callback = null)
    {
        $this->model =  $this->model->doesntHave($relation, 'and', $callback);
        return $this;
    }

    public function orWhere($column, $operator = null, $value = null)
    {
        $this->model =  $this->model->orWhere($column, $operator, $value);
        return $this;
    }

    public function whereTranslation($column, $value)
    {
        $this->model = $this->model->whereTranslation($column, $value);
        return $this;
    }

    public function has($relation, $operator = '>=', $count = 1, $boolean = 'and', Closure $callback = null)
    {
        $this->model =  $this->model->has($relation, $operator, $count, $boolean, $callback);
        return $this;
    }

    public function destroy($delete = null, $alias = null)
    {
        $this->model = $this->model->delete($delete, $alias);
        return $this;
    }

    public function forceDelete()
    {
        $this->model = $this->model->forceDelete();
        return $this;
    }

    public function sum($column)
    {
        return $this->model->sum($column);
    }

    public function addSelect($column)
    {
        return $this->model->addSelect($column);
    }

    public function scope($scope, $paramenet = null)
    {
        $this->model = $this->model->$scope($paramenet);

        return $this;
    }

    public function whereNull($columns, $boolean = 'and', $not = false)
    {
        $this->model->whereNull($columns, $boolean, $not);
        return $this;
    }

    public function updateQuery($data)
    {
        $this->model->update($data);
    }

    public function findOrFail($id, $columns = ['*'])
    {
        $this->model = $this->model->findOrFail($id, $columns);
        return $this;
    }

    public function toSql()
    {
        return $this->model->toSql();
    }

    public function getBindings()
    {
        return $this->model->getBindings();
    }

    public function withoutGlobalScope($scope)
    {
        $this->model = $this->model->withoutGlobalScope($scope);
        return $this;
    }

    public function selectRaw($expression, array $bindings = [])
    {
        $this->model = $this->model->selectRaw($expression, $bindings);
        return $this;
    }

    public function join($fromAlias, $join, $alias, $condition = null)
    {
        $this->model = $this->model->join($fromAlias, $join, $alias, $condition);
        return $this;
    }

    public function whereClosure(Closure $closure)
    {
        $this->model = $this->model->where($closure);
        return $this;
    }

    public function groupBy($groupBy)
    {
        $this->model = $this->model->groupBy($groupBy);
        return $this;
    }


    public function having($column, $operator = null, $value = null, $boolean = 'and')
    {
        $this->model = $this->model->having($column, $operator, $value, $boolean);
        return $this;
    }

    public function paginate($perPage = null)
    {
        return $this->model->paginate($perPage);
    }

    public function edit($data)
    {
        return $this->model->update($data);
    }
}
