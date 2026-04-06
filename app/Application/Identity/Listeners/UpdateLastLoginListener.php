<?php

namespace App\Application\Identity\Listeners;

use App\Domains\Identity\RepositoryInterface\IUserRepository;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Infrastructure\Persistence\Eloquent\Models\User;

class UpdateLastLoginListener
{
    public function __construct(
        private IUserRepository $userRepository
    ) {}

    public function handle(Login $event): void
    {
        $userModel = $event->user;
        
        // Ensure we are dealing with our User model
        if (!$userModel instanceof User) {
            return;
        }

        // Use the Repository to update via Domain logic
        $userEmail = $userModel->email;
        $user = $this->userRepository->findByEmail($userEmail);

        if ($user) {
            $user->updateLastLogin();
            $this->userRepository->save($user);
        }
    }
}
