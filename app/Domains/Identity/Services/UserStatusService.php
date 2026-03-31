<?php

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Entities\User;
use App\Domains\Identity\Enums\UserStatus;

class UserStatusService
{
    /**
     * Checks if the user should be marked as INACTIVE and updates the entity if so.
     * Returns true if the status was changed.
     */
    public function syncInactivityStatus(User $user): bool
    {
        // Only ACTIVE users can transition to INACTIVE via inactivity
        if ($user->status() !== UserStatus::ACTIVE) {
            return false;
        }

        if ($user->lastLoginAt()) {
            $oneMonthAgo = new \DateTimeImmutable('-1 month');
            if ($user->lastLoginAt() < $oneMonthAgo) {
                $user->deactivate(); // Sets status to INACTIVE
                return true;
            }
        }

        return false;
    }
}
