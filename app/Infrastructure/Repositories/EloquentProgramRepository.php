<?php

namespace App\Infrastructure\Repositories;

use App\Concerns\Repositories\BaseRepository;
use App\Domains\Program\RepositoryInterface\IProgramRepository;
use App\Infrastructure\Persistence\Eloquent\Models\Program;
use Illuminate\Database\Eloquent\Builder;

class EloquentProgramRepository extends BaseRepository implements IProgramRepository
{
    public function __construct(Program $model)
    {
        parent::__construct($model);
    }

    protected function mapToDomain(\Illuminate\Database\Eloquent\Model $model): \App\Domains\Program\Entities\Program
    {
        $bundles = $model->bundles->map(function ($b) {
            return new \App\Domains\Program\Entities\Bundle(
                id: $b->id,
                name_data: $b->getTranslations('name'),
                duration_minutes: $b->duration_minutes,
                sessions_count: $b->sessions_count,
                features_data: $b->getTranslations('features'),
                monthly_price_egp: (float) $b->monthly_price_egp,
                monthly_price_usd: (float) $b->monthly_price_usd,
                is_best_seller: (bool) $b->is_best_seller,
                is_active: (bool) $b->is_active,
                order: (int) $b->order
            );
        })->toArray();

        $locale = app()->getLocale();

        return new \App\Domains\Program\Entities\Program(
            id: $model->id,
            title_data: $model->getTranslations('title'),
            slug: $model->slug,
            badge_data: $model->getTranslations('badge'),
            icon: $model->icon,
            thumbnail_path: $model->thumbnail_path,
            video_url: $model->video_url,
            short_description_data: $model->getTranslations('short_description'),
            full_description_data: $model->getTranslations('full_description'),
            is_active: (bool) $model->is_active,
            levels: $model->levels->map(function($l) use ($locale) {
                $points = $l->getTranslation('points', $locale);
                $pointsArray = is_string($points) ? array_filter(explode("\n", str_replace("\r", "", $points))) : ($points ?: []);
                return (object) [
                    'id' => $l->id,
                    'name' => $l->getTranslation('name', $locale),
                    'points' => $pointsArray
                ];
            }),
            features: $model->features->map(fn($f) => (object) [
                'id' => $f->id,
                'title' => $f->getTranslation('title', $locale),
                'description' => $f->getTranslation('description', $locale),
                'icon' => $f->icon
            ]),
            learnings: $model->learnings->map(fn($l) => (object) [
                'id' => $l->id,
                'title' => $l->getTranslation('title', $locale)
            ]),
            faqs: $model->faqs->map(fn($f) => (object) [
                'id' => $f->id,
                'question' => $f->getTranslation('question', $locale),
                'answer' => $f->getTranslation('answer', $locale)
            ]),
            bundles: $bundles,
            created_at: $model->created_at ? new \DateTimeImmutable($model->created_at->toDateTimeString()) : null,
            updated_at: $model->updated_at ? new \DateTimeImmutable($model->updated_at->toDateTimeString()) : null
        );
    }

    public function filter(array $filters = [], array $relations = [], ?string $sort = null, int $perPage = 15)
    {
        $query = $this->model->newQuery()
            ->with(array_merge(['levels', 'features', 'learnings'], $relations));

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('title->ar', 'like', "%{$search}%")
                  ->orWhere('title->en', 'like', "%{$search}%")
                  ->orWhere('badge->ar', 'like', "%{$search}%")
                  ->orWhere('badge->en', 'like', "%{$search}%");
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        $paginator = $query->orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $paginator->getCollection()->transform(fn($m) => $this->mapToDomain($m));

        return $paginator;
    }

    public function archive(int $id): void
    {
        $this->model->findOrFail($id)->delete();
    }

    public function restore(int $id): void
    {
        $this->model->withTrashed()->findOrFail($id)->restore();
    }

    public function getArchived(int $perPage = 15)
    {
        $paginator = $this->model->onlyTrashed()
            ->orderByDesc('deleted_at')
            ->paginate($perPage);
            
        $paginator->getCollection()->transform(fn($m) => $this->mapToDomain($m));

        return $paginator;
    }

    public function forceDelete(int $id): void
    {
        $this->model->withTrashed()->findOrFail($id)->forceDelete();
    }

    public function findBySlug(string $slug, array $relations = []): ?\App\Domains\Program\Entities\Program
    {
        $model = $this->model->where('slug', $slug)
            ->with($relations)
            ->first();

        return $model ? $this->mapToDomain($model) : null;
    }
}
