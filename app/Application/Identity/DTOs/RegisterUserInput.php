<?php

namespace App\Application\Identity\DTOs;

final class RegisterUserInput
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password
    ) {
    }
}
