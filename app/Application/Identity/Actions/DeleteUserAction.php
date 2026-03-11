<?php

namespace App\Application\Identity\Actions;

use App\Domains\Identity\RepositoryInterface\IUserRepository;

class DeleteUserAction
{
    public function __construct(
        private IUserRepository $userRepository
    ) {
    }

    public function execute(int $userId): bool
    {
        return $this->userRepository->delete($userId);
    }
}
