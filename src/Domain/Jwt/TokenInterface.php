<?php
declare(strict_types=1);

namespace App\Domain\Jwt;

interface TokenInterface
{
    public function encode(array $payload, int $expiresAt): string;

    /**
     * @return array<mixed>
     */
    public function decode(string $token, string $fieldName = "token", bool $throw = false): array;
}
