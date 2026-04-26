<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bundle extends Model
{
    use \Spatie\Translatable\HasTranslations;

    protected $fillable = [
        'program_id',
        'name',
        'duration_minutes',
        'sessions_count',
        'features',
        'monthly_price_egp',
        'monthly_price_usd',
        'is_best_seller',
        'is_active',
        'order',
    ];

    public $translatable = ['name', 'features'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_best_seller' => 'boolean',
            'order' => 'integer',
            'monthly_price_egp' => 'float',
            'monthly_price_usd' => 'float',
        ];
    }

    public function getFeaturesAttribute($value)
    {
        if (is_string($value)) {
            return array_filter(explode("\n", str_replace("\r", "", $value)));
        }
        return $value ?: [];
    }

    /**
     * Compute the annual price for EGP automatically based on global discount.
     */
    protected function annualPriceEgp(): Attribute
    {
        return Attribute::make(
            get: function () {
                $discount = (float) Setting::getValue('global_annual_discount', 0);
                return round($this->monthly_price_egp * 12 * (1 - ($discount / 100)), 2);
            },
        );
    }

    /**
     * Compute the annual price for USD automatically based on global discount.
     */
    protected function annualPriceUsd(): Attribute
    {
        return Attribute::make(
            get: function () {
                $discount = (float) Setting::getValue('global_annual_discount', 0);
                return round($this->monthly_price_usd * 12 * (1 - ($discount / 100)), 2);
            },
        );
    }

    /**
     * Get the program linked to this bundle.
     * If null, this is a general/global bundle.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
