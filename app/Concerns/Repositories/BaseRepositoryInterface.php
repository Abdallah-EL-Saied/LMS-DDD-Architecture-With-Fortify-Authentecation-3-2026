<?php

namespace App\Concerns\Repositories;

interface BaseRepositoryInterface
{
    // Get all data from specific table
    public function all(array $columns = ['*'], array $relations = [], int $perPage = 15);

    // Get Data by id from specific table
    public function findById($id, array $columns = ['*'], array $relations = []);

    // Create data in specific table
    public function create(array $payload);

    // Update data in specific table
    public function update(int $id, array $payload);

    // Delete data from specific table
    public function delete(int $id);

    // Filter with pagination and sorting
    public function filter(array $filters = [], array $relations = [], ?string $sort = null, int $perPage = 15);
}
