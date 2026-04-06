<?php

namespace App\Domains\Identity\Entities;

use App\Domains\Identity\ValueObjects\Address;
use App\Domains\Identity\Enums\UserStatus;

class User
{
    private function __construct(
        private ?int $id,
        private string $firstName,
        private ?string $middleName,
        private string $lastName,
        private string $email,
        private ?string $password = null,
        private ?\DateTimeImmutable $dateOfBirth = null,
        private ?string $phoneNumber = null,
        private ?string $gender = null,
        private UserStatus $status = UserStatus::ACTIVE,
        private ?Address $address = null,
        private ?\DateTimeImmutable $emailVerifiedAt = null,
        private array $roles = [],
        private ?string $googleId = null,
        private ?\DateTimeImmutable $lastLoginAt = null
    ) {
    }

    public static function create(
        string $firstName,
        ?string $middleName,
        string $lastName,
        string $email,
        string $password,
        ?Address $address = null
    ): self {
        return new self(
            id: null,
            firstName: $firstName,
            middleName: $middleName,
            lastName: $lastName,
            email: $email,
            password: $password,
            address: $address
        );
    }

    public static function fromPersistence(
        int $id,
        string $firstName,
        ?string $middleName,
        string $lastName,
        string $email,
        ?string $password = null,
        ?\DateTimeImmutable $dateOfBirth = null,
        ?string $phoneNumber = null,
        ?string $gender = null,
        UserStatus $status = UserStatus::ACTIVE,
        ?Address $address = null,
        ?\DateTimeImmutable $emailVerifiedAt = null,
        array $roles = [],
        ?string $googleId = null,
        ?\DateTimeImmutable $lastLoginAt = null
    ): self {
        return new self(
            $id,
            $firstName,
            $middleName,
            $lastName,
            $email,
            $password,
            $dateOfBirth,
            $phoneNumber,
            $gender,
            $status,
            $address,
            $emailVerifiedAt,
            $roles,
            $googleId,
            $lastLoginAt
        );
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function middleName(): ?string
    {
        return $this->middleName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function fullName(): string
    {
        return trim("{$this->firstName} {$this->middleName} {$this->lastName}");
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): ?string
    {
        return $this->password;
    }

    public function dateOfBirth(): ?\DateTimeImmutable
    {
        return $this->dateOfBirth;
    }

    public function phoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function gender(): ?string
    {
        return $this->gender;
    }

    public function status(): UserStatus
    {
        return $this->status;
    }

    public function address(): ?Address
    {
        return $this->address;
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

    public function lastLoginAt(): ?\DateTimeImmutable
    {
        return $this->lastLoginAt;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    public function changeName(string $firstName, ?string $middleName, string $lastName): void
    {
        if (empty($firstName) || empty($lastName)) {
            throw new \InvalidArgumentException('First and Last name cannot be empty');
        }
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
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

    public function updateProfile(
        ?\DateTimeImmutable $dob,
        ?string $phone,
        ?string $gender,
        ?Address $address
    ): void {
        $this->dateOfBirth = $dob;
        $this->phoneNumber = $phone;
        $this->gender = $gender;
        $this->address = $address;
    }

    public function activate(): void
    {
        $this->status = UserStatus::ACTIVE;
    }

    public function deactivate(): void
    {
        $this->status = UserStatus::INACTIVE;
    }

    public function ban(): void
    {
        $this->status = UserStatus::BANNED;
    }

    public function isBanned(): bool
    {
        return $this->status === UserStatus::BANNED;
    }

    public function isInactive(): bool
    {
        return $this->status === UserStatus::INACTIVE;
    }

    public function updateLastLogin(): void
    {
        $this->lastLoginAt = new \DateTimeImmutable();
        if ($this->isInactive()) {
            $this->activate();
        }
    }

    /**
     * Static placeholder — subscriptions will be managed via a separate table.
     * Returns a consistent random value based on user ID for UI demo purposes.
     */
    public function isSubscribed(): bool
    {
        return $this->id ? ($this->id % 3 !== 0) : false;
    }
}
