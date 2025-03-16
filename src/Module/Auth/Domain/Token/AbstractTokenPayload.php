<?php

declare(strict_types=1);

namespace Auth\Domain\Token;

abstract class AbstractTokenPayload implements TokenPayloadInterface
{
    public function __construct(public readonly string $id, public readonly string $sessionId)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'session_id' => $this->sessionId,
        ];
    }
}
