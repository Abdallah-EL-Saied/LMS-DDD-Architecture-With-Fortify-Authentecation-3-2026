<?php

namespace App\Domains\Identity\ValueObjects;

use App\Domains\Identity\Enums\ContractType;

readonly class TeacherContract
{
    public function __construct(
        private ContractType $type,
        private float $rate,           // price per hour OR per session
        private float $maxDailyHours,  // daily cap from contract
        private array $availableDays,  // [0=Sun, 1=Mon, ...]
    ) {}

    public function type(): ContractType
    {
        return $this->type;
    }

    public function rate(): float
    {
        return $this->rate;
    }

    public function maxDailyHours(): float
    {
        return $this->maxDailyHours;
    }

    public function availableDays(): array
    {
        return $this->availableDays;
    }

    public function calculateSessionEarning(int $durationMinutes): float
    {
        if ($this->type === ContractType::PerSession) {
            return $this->rate;
        }

        return ($durationMinutes / 60) * $this->rate;
    }

    public function canAccommodate(float $existingHoursToday, int $sessionMinutes): bool
    {
        $newTotalHours = $existingHoursToday + ($sessionMinutes / 60);
        return $newTotalHours <= $this->maxDailyHours;
    }
}
