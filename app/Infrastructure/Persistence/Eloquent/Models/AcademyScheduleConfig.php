<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class AcademyScheduleConfig extends Model
{
    protected $table = 'academy_schedule_config';

    protected $fillable = [
        'available_days',
        'is_full_day',
        'start_time',
        'end_time',
    ];

    protected function casts(): array
    {
        return [
            'available_days' => 'array',
            'is_full_day' => 'boolean',
        ];
    }
}
