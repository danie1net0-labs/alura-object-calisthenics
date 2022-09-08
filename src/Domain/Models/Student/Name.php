<?php

namespace App\Domain\Models\Student;

class Name
{
    public function __construct(private readonly string $firstName, private readonly string $lastName)
    {
    }

    public function __toString(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }
}