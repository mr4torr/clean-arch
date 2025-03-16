<?php

declare(strict_types=1);

namespace Auth\Domain\Token;

interface TokenPayloadInterface
{
    public function toArray(): array;

    public function expiresAt(): int;
}
