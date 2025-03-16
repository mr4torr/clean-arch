<?php

declare(strict_types=1);

namespace Auth\Domain\Token;

use Auth\Domain\Enum\TokenEnum;

final class TokenForgotEmail implements TokenPayloadInterface
{
    public function __construct(public readonly string $id)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'resource' => TokenEnum::Forgot,
        ];
    }

    public function expiresAt(): int
    {
        return 60 * 60; // segundos = 15min
    }
}
