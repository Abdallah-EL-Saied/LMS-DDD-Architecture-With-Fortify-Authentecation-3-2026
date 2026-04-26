<?php

namespace App\Domains\Program\Entities;

/**
 * Program Domain Entity
 * Uses protected properties to trigger __get magic method for automatic localization.
 */
class Program
{
    public function __construct(
        public readonly ?int $id,
        protected readonly array $title_data,
        public readonly string $slug,
        protected readonly ?array $badge_data,
        public readonly ?string $icon,
        public readonly ?string $thumbnail_path,
        public readonly ?string $video_url,
        protected readonly array $short_description_data,
        protected readonly array $full_description_data,
        public readonly bool $is_active = true,
        public readonly mixed $levels = [],
        public readonly mixed $features = [],
        public readonly mixed $learnings = [],
        public readonly mixed $faqs = [],
        /** @var Bundle[] */
        public readonly array $bundles = [],
        public readonly ?\DateTimeImmutable $created_at = null,
        public readonly ?\DateTimeImmutable $updated_at = null
    ) {}

    /**
     * Magic getter for localized strings and compatibility.
     */
    public function __get($name)
    {
        $locale = app()->getLocale();
        
        // Map common names to protected data stores
        $map = [
            'title' => 'title_data',
            'short_description' => 'short_description_data',
            'full_description' => 'full_description_data',
            'badge' => 'badge_data',
        ];

        if (isset($map[$name])) {
            $data = $this->{$map[$name]};
            return $data[$locale] ?? $data['en'] ?? $data['ar'] ?? '';
        }

        return $this->{$name} ?? null;
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (!$this->video_url) return null;
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->video_url, $match);
        return isset($match[1]) ? "https://www.youtube.com/embed/{$match[1]}" : null;
    }

    public function getYoutubeEmbedUrl(): ?string
    {
        return $this->getYoutubeEmbedUrlAttribute();
    }
}
