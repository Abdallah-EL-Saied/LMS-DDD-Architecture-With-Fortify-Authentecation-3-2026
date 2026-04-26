<?php

namespace App\Infrastructure\Enums;

enum ProgramType: string
{
    case PRIVATE = 'private';
    case GROUP = 'group';

    public function label(): string
    {
        return match($this) {
            self::PRIVATE => __('Private'),
            self::GROUP => __('Group'),
        };
    }
}
