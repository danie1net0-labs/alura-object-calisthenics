<?php

namespace App\Domain\Models\Address;

class Address
{
    public function __construct(
        private readonly string $street,
        private readonly string $number,
        private readonly string $province,
        private readonly string $city,
        private readonly string $state,
        private readonly string $country,
    )
    {
    }

    public function __toString(): string
    {
        return "{$this->street}, n {$this->number} - {$this->province}. {$this->city} / {$this->state} - {$this->country}";
    }
}