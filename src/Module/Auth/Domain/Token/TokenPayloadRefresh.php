<?php

declare(strict_types=1);

namespace Auth\Domain\Token;

use Auth\Domain\Enum\TokenEnum;

final class TokenPayloadRefresh extends AbstractTokenPayload
{
    public function toArray(): array
    {
        return [...parent::toArray(), 'resource' => TokenEnum::Refresh];
    }

    public function expiresAt(): int
    {
        return 24 * 60 * 60; // segundos = 1 dia
    }
}
