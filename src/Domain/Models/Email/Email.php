<?php

namespace App\Domain\Models\Email;

use InvalidArgumentException;

class Email
{
    public function __construct(private readonly string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid e-mail address');
        }
    }

    public function __toString(): string
    {
        return $this->email;
    }
}