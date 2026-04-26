<?php

namespace App\Application\Academy\Actions;

use App\Domains\Academy\Entities\AcademyScheduleConfig;
use App\Domains\Academy\RepositoryInterface\IAcademyScheduleRepository;

class UpdateAcademyScheduleAction
{
    public function __construct(
        private IAcademyScheduleRepository $scheduleRepository
    ) {}

    /**
     * Update academy schedule configuration.
     * 
     * @param array $data ['available_days' => [...], 'start_time' => string, 'end_time' => string, 'slot_duration_minutes' => int]
     * @return void
     */
    public function execute(array $data): void
    {
        $config = $this->scheduleRepository->get();

        if (!$config) {
            $config = new AcademyScheduleConfig(
                id: null,
                availableDays: $data['available_days'],
                isFullDay: $data['is_full_day'] ?? false,
                startTime: $data['start_time'] ?? null,
                endTime: $data['end_time'] ?? null
            );
        } else {
            $config->updateSchedule(
                availableDays: $data['available_days'],
                isFullDay: $data['is_full_day'] ?? false,
                startTime: $data['start_time'] ?? null,
                endTime: $data['end_time'] ?? null
            );
        }

        $this->scheduleRepository->save($config);
    }
}
