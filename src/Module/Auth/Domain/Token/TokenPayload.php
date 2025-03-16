<?php

declare(strict_types=1);

namespace Auth\Domain\Token;

use Auth\Domain\Enum\TokenEnum;

final class TokenPayload extends AbstractTokenPayload
{
    public function toArray(): array
    {
        return [...parent::toArray(), 'resource' => TokenEnum::Authorization];
    }

    public function expiresAt(): int
    {
        return 60 * 60; // segundos = 15min
    }
}
