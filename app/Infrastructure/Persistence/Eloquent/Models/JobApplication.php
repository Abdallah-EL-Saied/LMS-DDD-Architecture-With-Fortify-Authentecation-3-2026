<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Concerns\Traits\Filterable;
use App\Domains\Identity\Enums\RequestStatus;
use Database\Factories\JobApplicationFactory;

class JobApplication extends Model
{
    use HasFactory;
    use Filterable {
        scopeFilter as traitFilter;
    }

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'age',
        'address',
        'email',
        'phone',
        'years_experience',
        'cv_path',
        'cover_letter',
        'specialization_ids',
        'status',
        'reviewer_id',
        'reviewer_notes',
        'submitted_at',
        'reviewed_at',
    ];

    protected $searchable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
    ];

    protected $filterable = [
        'status',
        'specialization_ids',
        'submitted_at',
        'reviewed_at',
    ];

    protected $sortable = [
        'first_name',
        'last_name',
        'submitted_at',
        'status',
    ];

    protected $casts = [
        'specialization_ids' => 'array',
        'status' => RequestStatus::class,
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    protected static function newFactory()
    {
        return JobApplicationFactory::new();
    }

    public function scopeFilter($query, array $filters = [])
    {
        if (isset($filters['search']) && !isset($filters['columns'])) {
            $term = $filters['search'];
            $query->where(function ($q) use ($term) {
                // Allows matching full names like "Ahmed Yahia" across multiple DB columns simultaneously.
                $q->whereRaw("CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?", ["%{$term}%"])
                  ->orWhere('email', 'LIKE', "%{$term}%")
                  ->orWhere('phone', 'LIKE', "%{$term}%");
            });
            unset($filters['search']);
        }

        // Apply remaining filters via trait logic
        return $this->traitFilter($query, $filters);
    }
}
