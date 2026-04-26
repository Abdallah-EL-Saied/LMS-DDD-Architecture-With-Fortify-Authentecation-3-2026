<?php

namespace App\Domains\Program\RepositoryInterface;

use App\Concerns\Repositories\BaseRepositoryInterface;

interface IProgramRepository extends BaseRepositoryInterface
{
    /**
     * Filter programs with advanced criteria.
     * 
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function filter(array $filters = [], array $relations = [], ?string $sort = null, int $perPage = 15);

    /**
     * Archive (Soft Delete) a program.
     */
    public function archive(int $id): void;

    /**
     * Restore an archived program.
     */
    public function restore(int $id): void;

    /**
     * Get archived (soft-deleted) programs.
     */
    public function getArchived(int $perPage = 15);

    /**
     * Permanently delete a program.
     */
    public function forceDelete(int $id): void;

    /**
     * Find a program by its slug.
     */
    public function findBySlug(string $slug, array $relations = []): ?\App\Domains\Program\Entities\Program;
}
