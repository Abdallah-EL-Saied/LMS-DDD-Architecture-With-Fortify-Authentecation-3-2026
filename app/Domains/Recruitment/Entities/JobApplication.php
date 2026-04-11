<?php

namespace App\Domains\Recruitment\Entities;

use App\Domains\Identity\Enums\RequestStatus;

class JobApplication
{
    public function __construct(
        private ?int $id,
        private string $firstName,
        private ?string $middleName,
        private string $lastName,
        private ?int $age,
        private ?string $address,
        private string $email,
        private string $phone,
        private ?int $yearsExperience,
        private string $cvPath,
        private ?string $coverLetter,
        private array $specializationIds, // [int, int, ...]
        private RequestStatus $status = RequestStatus::PENDING,
        private ?int $reviewerId = null,
        private ?string $reviewerNotes = null,
        private ?\DateTimeImmutable $submittedAt = null,
        private ?\DateTimeImmutable $reviewedAt = null,
        private ?\DateTimeImmutable $createdAt = null,
        private ?\DateTimeImmutable $updatedAt = null
    ) {}

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

    public function age(): ?int
    {
        return $this->age;
    }

    public function address(): ?string
    {
        return $this->address;
    }

    public function yearsExperience(): ?int
    {
        return $this->yearsExperience;
    }

    public function fullName(): string
    {
        return implode(' ', array_filter([
            $this->firstName,
            $this->middleName,
            $this->lastName,
        ], fn ($value) => $value !== null && $value !== ''));
    }

    public function email(): string
    {
        return $this->email;
    }

    public function phone(): string
    {
        return $this->phone;
    }

    public function cvPath(): string
    {
        return $this->cvPath;
    }

    public function coverLetter(): ?string
    {
        return $this->coverLetter;
    }

    public function specializationIds(): array
    {
        return $this->specializationIds;
    }

    public function status(): RequestStatus
    {
        return $this->status;
    }

    public function reviewerId(): ?int
    {
        return $this->reviewerId;
    }

    public function reviewerNotes(): ?string
    {
        return $this->reviewerNotes;
    }

    public function submittedAt(): ?\DateTimeImmutable
    {
        return $this->submittedAt;
    }

    public function reviewedAt(): ?\DateTimeImmutable
    {
        return $this->reviewedAt;
    }

    public function approve(int $reviewerId, ?string $notes = null): void
    {
        $this->status = RequestStatus::APPROVED;
        $this->reviewerId = $reviewerId;
        $this->reviewerNotes = $notes;
        $this->reviewedAt = new \DateTimeImmutable();
    }

    public function reject(int $reviewerId, ?string $notes = null): void
    {
        $this->status = RequestStatus::REJECTED;
        $this->reviewerId = $reviewerId;
        $this->reviewerNotes = $notes;
        $this->reviewedAt = new \DateTimeImmutable();
    }
}
