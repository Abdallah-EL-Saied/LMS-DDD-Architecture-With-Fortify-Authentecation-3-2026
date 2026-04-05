<?php

namespace App\Application\Identity\Actions;

use App\Domains\Identity\Entities\User;
use App\Domains\Identity\RepositoryInterface\IUserRepository;
use Illuminate\Support\Str;

class HandleSocialLoginAction
{
    public function __construct(
        private IUserRepository $userRepository
    ) {
    }

    public function execute(string $googleId, string $email, string $name): User
    {
        // 1. Try to find user by Google ID
        $user = $this->userRepository->findByGoogleId($googleId);

        if ($user) {
            return $user;
        }

        // 2. Try to find user by Email
        $user = $this->userRepository->findByEmail($email);

        if ($user) {
            // Update user with google_id
            $updatedUser = User::fromPersistence(
                $user->id(),
                $user->firstName(),
                $user->middleName(),
                $user->lastName(),
                $user->email(),
                $user->password(),
                $user->dateOfBirth(),
                $user->phoneNumber(),
                $user->gender(),
                $user->status(),
                $user->address(),
                $user->emailVerifiedAt() ?? now()->toDateTimeImmutable(), // Mark verified when linking google
                $user->roles(),
                $googleId,
                $user->lastLoginAt()
            );

            return $this->userRepository->save($updatedUser);
        }

        // 3. Create new user
        // Split name into first and last
        $parts = explode(' ', $name, 2);
        $firstName = $parts[0];
        $lastName = $parts[1] ?? '';

        // Generate a default password 'password' since they are login via social
        $password = bcrypt('password');

        $newUser = User::fromPersistence(
            0, // New user
            $firstName,
            null,
            $lastName,
            $email,
            $password,
            null,
            null,
            null,
            \App\Domains\Identity\Enums\UserStatus::ACTIVE,
            null,
            now()->toDateTimeImmutable(),
            ['student'], // Default role for social signup
            $googleId,
            null
        );

        return $this->userRepository->save($newUser);
    }
}
