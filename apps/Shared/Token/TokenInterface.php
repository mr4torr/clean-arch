<?php
declare(strict_types=1);

namespace Shared\Token;

interface TokenInterface
{
    public function encode(TokenPayloadInterface $payload): string;

    /**
     * @return array<mixed>
     */
    public function decode(string $token): array;
}
