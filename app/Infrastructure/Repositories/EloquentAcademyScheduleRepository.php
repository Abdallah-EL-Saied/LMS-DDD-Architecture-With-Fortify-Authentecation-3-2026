<?php

namespace App\Infrastructure\Repositories;

use App\Concerns\Repositories\BaseRepository;
use App\Domains\Academy\Entities\AcademyScheduleConfig as DomainScheduleConfig;
use App\Domains\Academy\RepositoryInterface\IAcademyScheduleRepository;
use App\Infrastructure\Persistence\Eloquent\Models\AcademyScheduleConfig as ScheduleConfigModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EloquentAcademyScheduleRepository extends BaseRepository implements IAcademyScheduleRepository
{
    public function __construct(ScheduleConfigModel $model)
    {
        parent::__construct($model);
    }

    protected function mapToDomain(Model $model): DomainScheduleConfig
    {
        return new DomainScheduleConfig(
            id: $model->id,
            availableDays: $model->available_days,
            isFullDay: $model->is_full_day,
            startTime: $model->start_time,
            endTime: $model->end_time,
            createdAt: $model->created_at ? Carbon::parse($model->created_at)->toDateTimeImmutable() : null,
            updatedAt: $model->updated_at ? Carbon::parse($model->updated_at)->toDateTimeImmutable() : null
        );
    }

    public function get(): ?DomainScheduleConfig
    {
        $model = $this->model->first();
        return $model ? $this->mapToDomain($model) : null;
    }

    public function save(DomainScheduleConfig $config): void
    {
        $model = $this->model->first() ?: new $this->model;

        $model->available_days = $config->availableDays();
        $model->is_full_day = $config->isFullDay();
        $model->start_time = $config->startTime();
        $model->end_time = $config->endTime();

        $model->save();
    }
}
