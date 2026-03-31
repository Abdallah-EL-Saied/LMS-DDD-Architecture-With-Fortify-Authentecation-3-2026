<?php

namespace App\Domains\Identity\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BANNED = 'banned';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => __('Active'),
            self::INACTIVE => __('Inactive'),
            self::BANNED => __('Banned'),
        };
    }
}
