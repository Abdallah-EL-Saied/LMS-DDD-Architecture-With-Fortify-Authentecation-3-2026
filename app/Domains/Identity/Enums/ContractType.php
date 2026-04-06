<?php

namespace App\Domains\Identity\Enums;

enum ContractType: string
{
    case Hourly     = 'hourly';
    case PerSession = 'per_session';
}
