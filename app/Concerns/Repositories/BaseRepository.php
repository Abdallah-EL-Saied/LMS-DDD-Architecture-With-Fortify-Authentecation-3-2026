<?php

namespace App\Concerns\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Convert Eloquent Model to Domain Entity.
     * Must be implemented by concrete repository.
     */
    abstract protected function mapToDomain(Model $model);

    // Get all data transformed to Domain
    public function all(array $columns = ['*'], array $relations = ['roles'], int $perPage = 15)
    {
        $paginator = $this->model->select($columns)->with($relations)->paginate($perPage);

        $paginator->getCollection()->transform(fn($model) => $this->mapToDomain($model));

        return $paginator;
    }

    // Find by id and return Domain Entity
    public function findById($id, array $columns = ['*'], array $relations = [])
    {
        $model = $this->model->select($columns)->with($relations)->find($id);

        return $model ? $this->mapToDomain($model) : null;
    }

    // Create and return Domain Entity
    public function create(array $payload)
    {
        $model = $this->model->create($payload);
        return $this->mapToDomain($model);
    }

    // Update and return Domain Entity
    public function update(int $id, array $payload)
    {
        $model = $this->model->findOrFail($id);
        $model->update($payload);
        return $this->mapToDomain($model->fresh());
    }

    // Delete
    public function delete(int $id)
    {
        $model = $this->model->findOrFail($id);
        return $model->delete();
    }

    // Filter with pagination and sorting
    public function filter(array $filters = [], array $relations = [], ?string $sort = null, int $perPage = 15)
    {
        // Query builder
        $query = $this->model->query();

        // Apply relations
        if ($relations) {
            $query->with($relations);
        }

        // Apply filters
        if (method_exists($this->model, 'scopeFilter')) {
            $query->filter($filters);
        }

        // Apply sorting
        if (method_exists($this->model, 'scopeSort')) {
            $query->sort($sort);
        }

        // Apply pagination
        $paginator = $query->paginate($perPage);
        
        $paginator->getCollection()->transform(fn($model) => $this->mapToDomain($model));

        return $paginator;
    }

    // $cacheKey = 'users_' . md5(json_encode($filters));

    // return Cache::remember($cacheKey, 60, function () use ($filters) {
    // return User::filter($filters)->paginate();
    // });
}