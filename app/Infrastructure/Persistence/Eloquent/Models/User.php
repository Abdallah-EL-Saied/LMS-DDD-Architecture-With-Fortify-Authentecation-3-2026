<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Concerns\Traits\Filterable;
use App\Domains\Identity\Enums\UserStatus;
use Database\Factories\UserFactory;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles, Filterable;

    protected array $searchable = ['first_name', 'last_name', 'email', 'phone_number'];
    protected array $filterable = ['status', 'roles', 'gender', 'country', 'city', 'initial_letter', 'first_name', 'last_name'];
    protected array $sortable = ['first_name', 'last_name', 'email', 'status', 'last_login_at', 'created_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'date_of_birth',
        'gender',
        'phone_number',
        'status',
        'country',
        'city',
        'street_address',
        'google_id',
        'last_login_at',
    ];

    /**
     * The specializations that belong to the teacher.
     */
    public function specializations()
    {
        return $this->belongsToMany(Specialization::class, 'teacher_specializations', 'teacher_id', 'specialization_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_of_birth' => 'date',
            'password' => 'hashed',
            'status' => UserStatus::class,
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::substr($this->first_name, 0, 1) . Str::substr($this->last_name, 0, 1);
    }

    /**
     * Get the user's full name
     */
    public function getNameAttribute(): string
    {
        return implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ], fn ($value) => filled($value)));
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
