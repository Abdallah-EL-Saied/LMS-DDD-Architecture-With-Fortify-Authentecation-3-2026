<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramFeature extends Model
{
    use \Spatie\Translatable\HasTranslations;

    protected $fillable = [
        'program_id',
        'title',
        'description',
        'icon',
    ];

    public $translatable = ['title', 'description'];


    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
