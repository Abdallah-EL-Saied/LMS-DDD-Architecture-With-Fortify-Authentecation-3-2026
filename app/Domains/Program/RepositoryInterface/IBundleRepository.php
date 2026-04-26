<?php

namespace App\Domains\Program\RepositoryInterface;

use App\Concerns\Repositories\BaseRepositoryInterface;

interface IBundleRepository extends BaseRepositoryInterface
{
    /**
     * Get bundles with optional program filter.
     * 
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function filter(array $filters = [], array $relations = [], ?string $sort = null, int $perPage = 15);

    /**
     * Mark a specific bundle as the best seller and unset others.
     */
    public function markAsBestSeller(?int $id): void;

    /**
     * Get the ID of the bundle with the highest subscription count.
     */
    public function getTopSellingBundleId(): ?int;

    /**
     * Unset best seller flag for all bundles.
     */
    public function clearBestSellers(): void;
}
