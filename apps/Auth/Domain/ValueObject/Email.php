<?php

declare(strict_types=1);

namespace Auth\Domain\ValueObject;

final class Email
{
    public function __construct(private readonly string $email)
    {
        $this->validate();
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    private function validate(): void
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            throw new \InvalidArgumentException(sprintf("The email <%s> is not valid", $this->email));
        }
    }
}
