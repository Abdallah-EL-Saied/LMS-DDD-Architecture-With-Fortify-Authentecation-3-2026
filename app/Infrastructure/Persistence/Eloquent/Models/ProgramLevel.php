<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramLevel extends Model
{
    use \Spatie\Translatable\HasTranslations;

    protected $fillable = [
        'program_id',
        'name',
        'points',
    ];

    public $translatable = ['name', 'points'];

    public function getPointsAttribute($value)
    {
        if (is_string($value)) {
            return array_filter(explode("\n", str_replace("\r", "", $value)));
        }
        return $value ?: [];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
