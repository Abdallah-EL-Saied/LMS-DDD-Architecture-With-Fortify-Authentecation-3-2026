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
                $user->name(),
                $user->email(),
                $user->password(),
                $user->emailVerifiedAt(),
                $user->roles(),
                $googleId
            );

            return $this->userRepository->save($updatedUser);
        }

        // 3. Create new user
        // Generate a default password 'password' since they are login via social
        $password = bcrypt('password');

        $newUser = User::fromPersistence(
            0, // New user
            $name,
            $email,
            $password,
            now()->toDateTimeImmutable(),
            ['student'], // Default role for social signup
            $googleId
        );

        return $this->userRepository->save($newUser);
    }
}
