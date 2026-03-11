<?php

namespace App\Application\Identity\Actions;

use App\Domains\Identity\RepositoryInterface\IUserRepository;
use Illuminate\Support\Facades\Hash;

class ResetPasswordAction
{
    public function __construct(
        private IUserRepository $userRepository
    ) {
    }

    public function execute(int $userId, string $newPassword): void
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new \Exception("User not found");
        }

        // Logic handled in Domain Entity (if we had complex rules)
        // For now, simple update
        $user = \App\Domains\Identity\Entities\User::fromPersistence(
            $user->id(),
            $user->name(),
            $user->email(),
            Hash::make($newPassword),
            $user->emailVerifiedAt()
        );

        $this->userRepository->save($user);
    }
}
