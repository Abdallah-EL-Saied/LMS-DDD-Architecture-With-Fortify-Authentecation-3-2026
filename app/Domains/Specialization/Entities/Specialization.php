<?php

namespace App\Domains\Specialization\Entities;

class Specialization
{
    public function __construct(
        private ?int $id,
        private array $name, // ["ar" => "...", "en" => "..."]
        private ?array $description,
        private bool $isActive = true,
        private ?\DateTimeImmutable $createdAt = null,
        private ?\DateTimeImmutable $updatedAt = null
    ) {}

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): array
    {
        return $this->name;
    }

    public function description(): ?array
    {
        return $this->description;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function createdAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function changeName(array $name): void
    {
        $this->name = $name;
    }

    public function changeDescription(?array $description): void
    {
        $this->description = $description;
    }

    public function toggleStatus(): void
    {
        $this->isActive = !$this->isActive;
    }
}
