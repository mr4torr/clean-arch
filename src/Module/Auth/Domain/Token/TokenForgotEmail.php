<?php

namespace Auth\Domain\Token;

final class TokenForgotEmail implements TokenPayloadInterface
{
    public const RESOURCE_TYPE = 'forgot';

    public function __construct(public readonly string $id) {}

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "resource" => self::RESOURCE_TYPE
        ];
    }

    public function expiresAt(): int
    {
        return 60 * 60; // segundos = 15min
    }
}
