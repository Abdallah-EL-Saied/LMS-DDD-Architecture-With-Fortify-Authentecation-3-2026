<?php

namespace App\Domains\Identity\Entities;

class User
{
    private function __construct(
        private ?int $id,
        private string $name,
        private string $email,
        private ?string $password = null,
        private ?\DateTimeImmutable $emailVerifiedAt = null,
        private array $roles = [],
        private ?string $googleId = null
    ) {
    }

    public static function create(string $name, string $email, string $password): self
    {
        return new self(null, $name, $email, $password);
    }

    public static function fromPersistence(
        int $id,
        string $name,
        string $email,
        ?string $password = null,
        ?\DateTimeImmutable $emailVerifiedAt = null,
        array $roles = [],
        ?string $googleId = null
    ): self {
        return new self($id, $name, $email, $password, $emailVerifiedAt, $roles, $googleId);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): ?string
    {
        return $this->password;
    }

    public function emailVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->emailVerifiedAt;
    }

    public function roles(): array
    {
        return $this->roles;
    }

    public function googleId(): ?string
    {
        return $this->googleId;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    public function changeName(string $name): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }
        $this->name = $name;
    }

    public function changeEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }
        $this->email = $email;
    }

    public function changePassword(string $hashedPassword): void
    {
        $this->password = $hashedPassword;
    }
}
