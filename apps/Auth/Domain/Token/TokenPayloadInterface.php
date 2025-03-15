<?php

namespace Auth\Domain\Token;

interface TokenPayloadInterface
{
    public function toArray(): array;

    public function expiresAt(): int;
}
