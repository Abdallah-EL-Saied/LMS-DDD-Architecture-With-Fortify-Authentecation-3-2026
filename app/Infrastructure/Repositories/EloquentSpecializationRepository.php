<?php

namespace App\Infrastructure\Repositories;

use App\Concerns\Repositories\BaseRepository;
use App\Domains\Specialization\Entities\Specialization as DomainSpecialization;
use App\Domains\Specialization\RepositoryInterface\ISpecializationRepository;
use App\Infrastructure\Persistence\Eloquent\Models\Specialization as SpecializationModel;
use Illuminate\Database\Eloquent\Model;

class EloquentSpecializationRepository extends BaseRepository implements ISpecializationRepository
{
    public function __construct(SpecializationModel $model)
    {
        parent::__construct($model);
    }

    protected function mapToDomain(Model $model): DomainSpecialization
    {
        return new DomainSpecialization(
            id: $model->id,
            name: $model->getTranslations('name'),
            description: $model->getTranslations('description'),
            isActive: $model->is_active,
            createdAt: $model->created_at ? \Illuminate\Support\Carbon::parse($model->created_at)->toDateTimeImmutable() : null,
            updatedAt: $model->updated_at ? \Illuminate\Support\Carbon::parse($model->updated_at)->toDateTimeImmutable() : null
        );
    }

    public function save(DomainSpecialization $specialization): DomainSpecialization
    {
        $model = $this->model->findOrNew($specialization->id());
        
        $model->name = $specialization->name();
        $model->description = $specialization->description();
        $model->is_active = $specialization->isActive();
        
        $model->save();
        
        return $this->mapToDomain($model);
    }

    public function getActive(): array
    {
        return $this->model->where('is_active', true)->get()
            ->map(fn(SpecializationModel $model) => $this->mapToDomain($model))
            ->all();
    }

    public function getAll(): array
    {
        return $this->model->all()
            ->map(fn(SpecializationModel $model) => $this->mapToDomain($model))
            ->all();
    }
}
