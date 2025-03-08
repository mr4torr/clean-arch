<?php
declare(strict_types=1);

namespace Shared\Support;

interface TokenInterface
{
    /**
     * @param array $payload
     * @param int $exp
     */
    public function encode(array $payload, ?int $exp = null): string;

    /**
     * @return array<mixed>
     */
    public function decode(string $token): array;
}
