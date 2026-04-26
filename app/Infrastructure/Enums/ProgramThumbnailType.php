<?php

namespace App\Infrastructure\Enums;

enum ProgramThumbnailType: string
{
    case IMAGE = 'image';
    case VIDEO = 'video';

    public function label(): string
    {
        return match($this) {
            self::IMAGE => __('Image'),
            self::VIDEO => __('Video'),
        };
    }
}
