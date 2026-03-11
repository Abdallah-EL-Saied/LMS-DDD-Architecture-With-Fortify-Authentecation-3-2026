<?php

namespace App\Application\Identity\Actions;

use App\Domains\Identity\Entities\User;
use App\Domains\Identity\RepositoryInterface\IUserRepository;
use Exception;

class LinkSocialAccountAction
{
    public function __construct(
        private IUserRepository $userRepository
    ) {
    }

    public function execute(string $googleId, int $userId): User
    {
        // 1. Check if this Google ID is already linked to ANOTHER user
        $existingLinkedUser = $this->userRepository->findByGoogleId($googleId);

        if ($existingLinkedUser && $existingLinkedUser->id() !== $userId) {
            throw new Exception(__('This Google account is already linked to another user.'));
        }

        // 2. Find current user
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new Exception(__('User not found.'));
        }

        // 3. Update user with google_id
        $updatedUser = User::fromPersistence(
            $user->id(),
            $user->name(),
            $user->email(),
            $user->password(),
            $user->emailVerifiedAt(),
            $user->roles(),
            $googleId
        );

        return $this->userRepository->save($updatedUser);
    }
}
