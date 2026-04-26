<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BundleSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'bundle_id',
        'plan_type',
        'paid_amount',
        'currency',
        'gateway',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'paid_amount' => 'float',
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Infrastructure\Persistence\Eloquent\Models\User::class);
    }

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(Bundle::class);
    }
}
