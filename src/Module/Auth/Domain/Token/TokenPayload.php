<?php

namespace Auth\Domain\Token;

final class TokenPayload extends AbstractTokenPayload
{
    public const RESOURCE_TYPE = 'authorization';

    public function toArray(): array
    {
        return [...parent::toArray(), "resource" => self::RESOURCE_TYPE];
    }

    public function expiresAt(): int
    {
        return 60 * 60; // segundos = 15min
    }
}
