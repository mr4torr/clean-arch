<?php

namespace Shared\Token;

interface TokenPayloadInterface
{
    public function toArray(): array;

    public function exp(): int;
}
