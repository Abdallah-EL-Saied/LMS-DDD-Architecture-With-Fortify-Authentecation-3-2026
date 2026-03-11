<?php

namespace App\Application\Identity\Actions;

use App\Application\Identity\DTOs\UpdateUserInput;
use App\Domains\Identity\Entities\User;
use App\Domains\Identity\RepositoryInterface\IUserRepository;

class UpdateUserAction
{
    public function __construct(
        private IUserRepository $userRepository
    ) {
    }

    public function execute(UpdateUserInput $input): User
    {
        $user = $this->userRepository->findById($input->id);

        if (!$user) {
            throw new \Exception("User not found");
        }

        $user->changeName($input->name);
        // Add other domain methods like changeEmail if needed in the Entity

        return $this->userRepository->save($user);
    }
}
