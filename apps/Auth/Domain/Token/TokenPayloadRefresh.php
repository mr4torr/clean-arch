<?php

namespace Auth\Domain\Token;

final class TokenPayloadRefresh extends AbstractTokenPayload
{
    public const RESOURCE_TYPE = 'refresh';

    public function toArray(): array
    {
        return [...parent::toArray(), "resource" => self::RESOURCE_TYPE];
    }

    public function expiresAt(): int
    {
        return 24 * 60 * 60; // segundos = 1 dia
    }
}
