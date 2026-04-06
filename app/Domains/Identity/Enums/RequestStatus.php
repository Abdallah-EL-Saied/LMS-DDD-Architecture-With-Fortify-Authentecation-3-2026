<?php

namespace App\Domains\Identity\Enums;

enum RequestStatus: string
{
    case PENDING  = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
