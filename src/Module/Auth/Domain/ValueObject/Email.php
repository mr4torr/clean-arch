<?php

declare(strict_types=1);

namespace Auth\Domain\ValueObject;

final class Email
{
    public function __construct(private readonly string $email)
    {
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function validate(): bool
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
