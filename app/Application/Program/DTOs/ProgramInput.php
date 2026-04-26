<?php

namespace App\Application\Program\DTOs;

class ProgramInput
{
    public function __construct(
        public array $title,
        public array $shortDescription,
        public array $fullDescription,
        public ?string $slug = null,
        public ?array $badge = null,
        public ?string $icon = null,
        public ?string $thumbnailPath = null,
        public ?string $videoUrl = null,
        public bool $isActive = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            shortDescription: $data['short_description'],
            fullDescription: $data['full_description'],
            slug: $data['slug'] ?? null,
            badge: $data['badge'] ?? null,
            icon: $data['icon'] ?? null,
            thumbnailPath: $data['thumbnail_path'] ?? null,
            videoUrl: $data['video_url'] ?? null,
            isActive: $data['is_active'] ?? true,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'short_description' => $this->shortDescription,
            'full_description' => $this->fullDescription,
            'slug' => $this->slug,
            'badge' => $this->badge,
            'icon' => $this->icon,
            'thumbnail_path' => $this->thumbnailPath,
            'video_url' => $this->videoUrl,
            'is_active' => $this->isActive,
        ];
    }
}
