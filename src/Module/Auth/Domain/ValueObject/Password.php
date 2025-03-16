<?php

declare(strict_types=1);

namespace Auth\Domain\ValueObject;

use SensitiveParameter;

final class Password
{
    public function __construct(
        #[SensitiveParameter]
        private readonly string $password,
        private readonly string $algorithm = PASSWORD_BCRYPT,
        private readonly array $options = ['cost' => 12]
    ) {
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return array{password: string}
     */
    public function __debugInfo(): array
    {
        return [
            'password' => '********',
        ];
    }

    public function check(#[SensitiveParameter] string $hash): bool
    {
        return password_verify($this->password, $hash);
    }

    public function toString(): string
    {
        return password_hash($this->password, $this->algorithm, $this->options);
    }

    public function validate(): bool
    {
        return count($this->errors()) === 0;
    }

    public function errors(): array
    {
        $errors = [];

        $uppercase = preg_match('@[A-Z]@', $this->password);
        $lowercase = preg_match('@[a-z]@', $this->password);
        $number = preg_match('@[0-9]@', $this->password);
        $specialChars = preg_match('@[^\w]@', $this->password);

        if (strlen($this->password) < 8) {
            $errors[] = 'validation.password.min:8';
        }

        if (!$uppercase) {
            $errors[] = 'validation.password.uppercase';
        }

        if (!$lowercase) {
            $errors[] = 'validation.password.lowercase';
        }

        if (!$number) {
            $errors[] = 'validation.password.number';
        }

        if (!$specialChars) {
            $errors[] = 'validation.password.special';
        }

        return $errors;
    }
}
