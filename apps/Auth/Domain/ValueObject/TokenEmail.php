<?php

namespace Auth\Domain\ValueObject;

use Shared\Token\TokenPayloadInterface;

class TokenEmail implements TokenPayloadInterface
{
    public function __construct(
        public readonly string $id,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }

    public function exp(): int
    {
        return 60 * 60; // segundos = 15min
    }
}
