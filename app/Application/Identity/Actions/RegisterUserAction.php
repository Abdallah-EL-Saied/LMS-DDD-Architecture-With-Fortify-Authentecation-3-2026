<?php

namespace App\Application\Identity\Actions;

use App\Application\Identity\DTOs\RegisterUserInput;
use App\Domains\Identity\Entities\User;
use App\Domains\Identity\RepositoryInterface\IUserRepository;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function __construct(
        private IUserRepository $userRepository
    ) {
    }

    public function execute(RegisterUserInput $input): User
    {
        $user = User::fromPersistence(
            0,
            $input->name,
            $input->email,
            Hash::make($input->password),
            null,
            ['student']
        );

        return $this->userRepository->save($user);
    }
}
