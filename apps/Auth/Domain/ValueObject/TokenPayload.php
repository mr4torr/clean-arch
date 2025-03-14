<?php

namespace Auth\Domain\ValueObject;

use Shared\Token\TokenPayloadInterface;

class TokenPayload implements TokenPayloadInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $sessionId,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'session_id' => $this->sessionId,
            'refresh' => false,
        ];
    }

    public function exp(): int
    {
        return 60 * 60; // segundos = 15min
    }
}
