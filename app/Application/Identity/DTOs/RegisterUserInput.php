<?php

namespace App\Application\Identity\DTOs;

final class RegisterUserInput
{
    public function __construct(
        public readonly string $firstName,
        public readonly ?string $middleName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly string $password
    ) {
    }
}
