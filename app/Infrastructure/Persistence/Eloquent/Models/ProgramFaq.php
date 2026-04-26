<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramFaq extends Model
{
    use \Spatie\Translatable\HasTranslations;

    protected $fillable = [
        'program_id',
        'question',
        'answer',
        'order',
    ];

    public $translatable = ['question', 'answer'];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
