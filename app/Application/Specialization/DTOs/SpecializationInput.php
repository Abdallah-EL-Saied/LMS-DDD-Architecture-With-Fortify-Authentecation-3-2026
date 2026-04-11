<?php

namespace App\Application\Specialization\DTOs;

class SpecializationInput
{
    public function __construct(
        public array $name,
        public ?array $description = null,
        public bool $isActive = true
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            isActive: $data['is_active'] ?? true
        );
    }
}
