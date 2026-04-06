<?php

namespace App\Application\Identity\DTOs;

readonly class UpdateUserInput
{
    public function __construct(
        public int $id,
        public string $firstName,
        public ?string $middleName,
        public string $lastName,
        public string $email,
        public ?string $password = null
    ) {
    }
}
