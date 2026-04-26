<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\Traits\Filterable;
use Spatie\Translatable\HasTranslations;

class Specialization extends Model
{
    use Filterable, HasTranslations;

    protected $fillable = ['name', 'description', 'is_active'];

    public $translatable = ['name', 'description'];
    
    protected $searchable = ['name', 'description'];
    protected $filterable = ['is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The teachers that belong to the specialization.
     */
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_specializations', 'specialization_id', 'teacher_id');
    }
}
