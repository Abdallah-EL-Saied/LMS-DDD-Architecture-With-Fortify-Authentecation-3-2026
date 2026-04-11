<?php

namespace App\Application\Recruitment\Actions;

use App\Domains\Identity\Entities\User as UserEntity;
use App\Domains\Identity\ValueObjects\Address;
use App\Domains\Identity\RepositoryInterface\IUserRepository;
use App\Domains\Recruitment\Entities\JobApplication;
use App\Domains\Identity\Enums\UserStatus;
use Illuminate\Support\Str;

class CreateTeacherFromApplicationAction
{
    public function __construct(
        private IUserRepository $userRepository
    ) {}

    public function execute(JobApplication $application): UserEntity
    {
        $password = Str::random(12);

        $address = $application->address()
            ? new Address('Unknown', 'Unknown', $application->address())
            : null;

        $user = UserEntity::fromPersistence(
            id: null,
            firstName: $application->firstName(),
            middleName: $application->middleName(),
            lastName: $application->lastName(),
            email: $application->email(),
            password: $password,
            dateOfBirth: null,
            phoneNumber: $application->phone(),
            gender: null,
            status: UserStatus::ACTIVE,
            address: $address,
            emailVerifiedAt: new \DateTimeImmutable(),
            roles: ['teacher']
        );

        $savedUser = $this->userRepository->save($user);

        $this->userRepository->syncSpecializations($savedUser->id(), $application->specializationIds());

        return $savedUser;
    }
}
