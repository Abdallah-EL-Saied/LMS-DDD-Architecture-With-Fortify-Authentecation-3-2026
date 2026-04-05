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
            $input->firstName,
            $input->middleName,
            $input->lastName,
            $input->email,
            Hash::make($input->password),
            null, // dob
            null, // phone
            null, // gender
            \App\Domains\Identity\Enums\UserStatus::ACTIVE,
            null, // address
            null, // emailVerifiedAt
            ['student']
        );

        return $this->userRepository->save($user);
    }
}
