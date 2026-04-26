<?php

namespace App\Domains\Program\Entities;

/**
 * Bundle Domain Entity
 * Uses protected properties to trigger __get magic method for automatic localization.
 */
class Bundle
{
    public function __construct(
        public readonly int $id,
        protected readonly array $name_data,
        public readonly int $duration_minutes,
        public readonly int $sessions_count,
        protected readonly array $features_data,
        public readonly float $monthly_price_egp,
        public readonly float $monthly_price_usd,
        public readonly bool $is_best_seller = false,
        public readonly bool $is_active = true,
        public readonly int $order = 0,
        public readonly ?int $program_id = null,
        public readonly mixed $program = null,
        public array $pricing = [],
        public readonly ?\DateTimeImmutable $created_at = null,
        public readonly ?\DateTimeImmutable $updated_at = null
    ) {}

    /**
     * Magic getter for localized strings.
     */
    public function __get($name)
    {
        $locale = app()->getLocale();
        
        if ($name === 'name') {
            return $this->name_data[$locale] ?? $this->name_data['en'] ?? $this->name_data['ar'] ?? '';
        }

        if ($name === 'features') {
            $features = $this->features_data[$locale] ?? $this->features_data['en'] ?? $this->features_data['ar'] ?? [];
            if (is_string($features)) {
                return array_filter(explode("\n", str_replace("\r", "", $features)));
            }
            return (array) $features;
        }

        return $this->{$name} ?? null;
    }

    /**
     * Get calculated annual prices.
     */
    public function annualPrice(string $currency = 'EGP'): float
    {
        $discount = (float) \App\Infrastructure\Persistence\Eloquent\Models\Setting::getValue('global_annual_discount', 0);
        $monthly = $currency === 'USD' ? $this->monthly_price_usd : $this->monthly_price_egp;
        return round($monthly * 12 * (1 - ($discount / 100)), 2);
    }

    /**
     * Get raw translation data for a field.
     */
    public function getTranslations(string $field): array
    {
        if ($field === 'name') {
            return $this->name_data;
        }

        if ($field === 'features') {
            return $this->features_data;
        }

        return [];
    }
}
