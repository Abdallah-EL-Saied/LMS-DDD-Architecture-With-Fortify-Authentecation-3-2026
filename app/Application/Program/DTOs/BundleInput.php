<?php

namespace App\Application\Program\DTOs;

class BundleInput
{
    public function __construct(
        public array $name,
        public int $durationMinutes,
        public int $sessionsCount,
        public array $features,
        public float $monthlyPriceEgp,
        public float $monthlyPriceUsd,
        public ?int $programId = null,
        public bool $isBestSeller = false,
        public bool $isActive = true,
        public int $order = 0
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            durationMinutes: (int) $data['duration_minutes'],
            sessionsCount: (int) $data['sessions_count'],
            features: $data['features'],
            monthlyPriceEgp: (float) $data['monthly_price_egp'],
            monthlyPriceUsd: (float) $data['monthly_price_usd'],
            programId: $data['program_id'] ?? null,
            isBestSeller: (bool) ($data['is_best_seller'] ?? false),
            isActive: (bool) ($data['is_active'] ?? true),
            order: (int) ($data['order'] ?? 0),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'duration_minutes' => $this->durationMinutes,
            'sessions_count' => $this->sessionsCount,
            'features' => $this->features,
            'monthly_price_egp' => $this->monthlyPriceEgp,
            'monthly_price_usd' => $this->monthlyPriceUsd,
            'program_id' => $this->programId,
            'is_best_seller' => $this->isBestSeller,
            'is_active' => $this->isActive,
            'order' => $this->order,
        ];
    }
}
