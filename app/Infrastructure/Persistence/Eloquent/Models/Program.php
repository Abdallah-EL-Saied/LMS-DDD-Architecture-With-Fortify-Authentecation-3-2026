<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Infrastructure\Enums\ProgramThumbnailType;
use App\Infrastructure\Enums\ProgramType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use \Spatie\Translatable\HasTranslations, SoftDeletes;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'title',
        'slug',
        'badge',
        'icon',
        'thumbnail_path',
        'video_url',
        'short_description',
        'full_description',
        'is_active',
    ];

    public $translatable = ['title', 'short_description', 'full_description', 'badge'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'program_user');
    }

    public function getRealEnrollmentsCountAttribute(): int
    {
        return $this->users()->count();
    }

    public function levels(): HasMany
    {
        return $this->hasMany(ProgramLevel::class);
    }

    public function features(): HasMany
    {
        return $this->hasMany(ProgramFeature::class);
    }

    public function learnings(): HasMany
    {
        return $this->hasMany(ProgramLearning::class);
    }

    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'program_user');
    }

    public function bundles(): HasMany
    {
        return $this->hasMany(Bundle::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(ProgramFaq::class)->orderBy('order');
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (!$this->video_url) return null;

        $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        if (preg_match($pattern, $this->video_url, $matches)) {
            return "https://www.youtube.com/embed/" . $matches[1];
        }

        return $this->video_url;
    }
}
