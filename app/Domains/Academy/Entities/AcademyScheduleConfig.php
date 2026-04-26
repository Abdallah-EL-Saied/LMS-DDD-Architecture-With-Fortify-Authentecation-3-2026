<?php

namespace App\Domains\Academy\Entities;

use DateTimeImmutable;

class AcademyScheduleConfig
{
    public function __construct(
        private ?int $id,
        private array $availableDays, // [0,1,2,3,4,5,6] (Sunday=0)
        private bool $isFullDay,
        private ?string $startTime, // "HH:MM"
        private ?string $endTime, // "HH:MM"
        private ?DateTimeImmutable $createdAt = null,
        private ?DateTimeImmutable $updatedAt = null
    ) {}

    public function id(): ?int
    {
        return $this->id;
    }

    public function availableDays(): array
    {
        return $this->availableDays;
    }

    public function isFullDay(): bool
    {
        return $this->isFullDay;
    }

    public function startTime(): ?string
    {
        return $this->startTime;
    }

    public function endTime(): ?string
    {
        return $this->endTime;
    }

    public function createdAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function updateSchedule(array $availableDays, bool $isFullDay, ?string $startTime, ?string $endTime): void
    {
        $this->availableDays = $availableDays;
        $this->isFullDay = $isFullDay;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }
}
