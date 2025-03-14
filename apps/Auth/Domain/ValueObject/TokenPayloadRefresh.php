<?php

namespace Auth\Domain\ValueObject;

class TokenPayloadRefresh extends TokenPayload
{
    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'refresh' => true,
        ];
    }

    public function exp(): int
    {
        return 24 * 60 * 60 ; // segundos = 1 dia
    }
}
