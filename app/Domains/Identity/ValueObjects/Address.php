<?php

namespace App\Domains\Identity\ValueObjects;

final class Address
{
    public function __construct(
        private readonly string $country,
        private readonly string $city,
        private readonly string $streetAddress
    ) {
        if (empty($country)) {
            throw new \InvalidArgumentException('Country cannot be empty');
        }
        if (empty($city)) {
            throw new \InvalidArgumentException('City cannot be empty');
        }
    }

    public function country(): string
    {
        return $this->country;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function streetAddress(): string
    {
        return $this->streetAddress;
    }

    public function equals(Address $other): bool
    {
        return $this->country === $other->country &&
               $this->city === $other->city &&
               $this->streetAddress === $other->streetAddress;
    }

    public function __toString(): string
    {
        return "{$this->streetAddress}, {$this->city}, {$this->country}";
    }
}
