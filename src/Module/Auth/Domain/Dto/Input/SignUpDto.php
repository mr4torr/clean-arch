<?php

declare(strict_types=1);

namespace Auth\Domain\Dto\Input;

use Auth\Domain\Enum\ProviderEnum;
use Auth\Domain\ValueObject\Email;
use Auth\Domain\ValueObject\Password;

final class SignUpDto
{
    public function __construct(
        public readonly string $name,
        public readonly Email $email,
        public readonly Password $password,
        public readonly ProviderEnum $provider = ProviderEnum::API
    ) {
    }

    public static function make(string $name, string $email, string $password, string $provider): self
    {
        return new self($name, new Email($email), new Password($password), ProviderEnum::tryFrom($provider));
    }
}
