<?php

namespace App\Infrastructure\Repositories;

use App\Concerns\Repositories\BaseRepository;
use App\Domains\Program\RepositoryInterface\IBundleRepository;
use App\Infrastructure\Persistence\Eloquent\Models\Bundle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class EloquentBundleRepository extends BaseRepository implements IBundleRepository
{
    public function __construct(Bundle $model)
    {
        parent::__construct($model);
    }

    protected function mapToDomain(\Illuminate\Database\Eloquent\Model $model): \App\Domains\Program\Entities\Bundle
    {
        return new \App\Domains\Program\Entities\Bundle(
            id: $model->id,
            name_data: $model->getTranslations('name'),
            duration_minutes: $model->duration_minutes,
            sessions_count: $model->sessions_count,
            features_data: $model->getTranslations('features'),
            monthly_price_egp: (float) $model->monthly_price_egp,
            monthly_price_usd: (float) $model->monthly_price_usd,
            is_best_seller: (bool) $model->is_best_seller,
            is_active: (bool) $model->is_active,
            order: (int) $model->order,
            program_id: $model->program_id,
            program: $model->program,
            created_at: $model->created_at ? new \DateTimeImmutable($model->created_at->toDateTimeString()) : null,
            updated_at: $model->updated_at ? new \DateTimeImmutable($model->updated_at->toDateTimeString()) : null
        );

    }

    public function filter(array $filters = [], array $relations = [], ?string $sort = null, int $perPage = 15)
    {
        $query = $this->model->newQuery()->with(array_merge(['program'], $relations));

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('name->ar', 'like', "%{$search}%")
                  ->orWhere('name->en', 'like', "%{$search}%");
            });
        }

        if (isset($filters['program_id'])) {
            if ($filters['program_id'] === 'general') {
                $query->whereNull('program_id');
            } else {
                $query->where('program_id', $filters['program_id']);
            }
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        $paginator = $query->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $paginator->getCollection()->transform(fn($model) => $this->mapToDomain($model));

        return $paginator;
    }

    public function markAsBestSeller(?int $id): void
    {
        if (!$id) {
            $this->clearBestSellers();
            return;
        }

        DB::transaction(function () use ($id) {
            $this->clearBestSellers();
            $this->model->where('id', $id)->update(['is_best_seller' => true]);
        });
    }

    public function getTopSellingBundleId(): ?int
    {
        return DB::table('bundle_subscriptions')
            ->select('bundle_id', DB::raw('COUNT(*) as total'))
            ->groupBy('bundle_id')
            ->orderByDesc('total')
            ->value('bundle_id');
    }

    public function clearBestSellers(): void
    {
        $this->model->where('is_best_seller', true)->update(['is_best_seller' => false]);
    }
}
