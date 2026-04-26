<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramLearning extends Model
{
    use \Spatie\Translatable\HasTranslations;

    protected $fillable = [
        'program_id',
        'title',
    ];

    public $translatable = ['title'];


    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
