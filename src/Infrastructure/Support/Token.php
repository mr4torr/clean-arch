<?php

declare(strict_types=1);

namespace App\Infrastructure\Support;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Log\LoggerInterface;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\ValidationCodeEnum;
use Shared\Support\TokenInterface;

use function Hyperf\Support\env;

class Token implements TokenInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {}
    public function encode(array $payload, $exp = 60 * 30): string
    {
        $payload = [
            ...$payload,
            'iat' => time(),
            'exp' => time() + $exp,
        ];

        return JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');
    }

    public function decode(string $token, string $fieldName = 'token', bool $throw = false): array
    {
        $headers = new \stdClass();
        $payload = [];

        try {
            $payload = JWT::decode($token, new Key(env('JWT_SECRET_KEY'), 'HS256'), $headers);
        } catch (\Throwable $e) {
            if ($throw) {
                throw new FieldException(
                    [
                        $fieldName => $e instanceof ExpiredException
                            ? ValidationCodeEnum::TOKEN_EXPIRED
                            : ValidationCodeEnum::TOKEN_INVALID
                    ],
                    ErrorCodeEnum::AUTH_JWT_KEY_INVALID
                );
            }

            $this->logger->error(json_encode(["token" => $token, "message" => $e->getMessage()]), ['field' => $fieldName]);

            if ($e instanceof ExpiredException) {
                $payload = $e->getPayload();
            }
        }

        return (array) $payload;
    }
}