<?php

declare(strict_types=1);

namespace App\Infrastructure\Support;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Shared\Support\TokenInterface;

use function Hyperf\Support\env;

class Token implements TokenInterface
{
    public function encode(array $payload, $exp = 60 * 30): string
    {
        $payload = [
            ...$payload,
            'iat' => time(),
            'exp' => time() + $exp,
        ];

        return JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');
    }

    public function decode(string $token): array
    {
        $headers = new \stdClass();
        $decoded = JWT::decode($token, new Key(env('JWT_SECRET_KEY'), 'HS256'), $headers);
        return (array) $decoded;
    }
}