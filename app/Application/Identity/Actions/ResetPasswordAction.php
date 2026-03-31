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
            $user->firstName(),
            $user->middleName(),
            $user->lastName(),
            $user->email(),
            Hash::make($newPassword),
            $user->dateOfBirth(),
            $user->phoneNumber(),
            $user->gender(),
            $user->status(),
            $user->address(),
            $user->emailVerifiedAt(),
            $user->roles(),
            $user->googleId()
        );

        $this->userRepository->save($user);
    }
}
