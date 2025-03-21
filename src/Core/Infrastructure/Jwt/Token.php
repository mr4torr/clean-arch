<?php

declare(strict_types=1);

namespace Core\Infrastructure\Jwt;

use Core\Domain\Jwt\TokenInterface;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
// Core -
use Psr\Log\LoggerInterface;
// Shared -
use Shared\Exception\FieldException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\ValidationCodeEnum;
use stdClass;
use Throwable;

use function Hyperf\Support\env;

class Token implements TokenInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function encode(array $payload, int $exp): string
    {
        $iat = time();
        $data = [...$payload, 'iat' => $iat, 'exp' => $iat + $exp];
        return JWT::encode($data, env('JWT_SECRET_KEY'), 'HS256');
    }

    public function decode(string $token, string $fieldName = 'token', bool $throw = false): array
    {
        $headers = new stdClass();
        $payload = [];

        try {
            $payload = JWT::decode($token, new Key(env('JWT_SECRET_KEY'), 'HS256'), $headers);
        } catch (Throwable $e) {
            if ($throw) {
                throw new FieldException(
                    [
                        $fieldName => $e instanceof ExpiredException
                                ? ValidationCodeEnum::TOKEN_EXPIRED
                                : ValidationCodeEnum::TOKEN_INVALID,
                    ],
                    ErrorCodeEnum::AUTH_JWT_KEY_INVALID,
                    $e
                );
            }

            $this->logger->error(json_encode(['token' => $token, 'message' => $e->getMessage()]), [
                'field' => $fieldName,
            ]);

            if ($e instanceof ExpiredException) {
                $payload = $e->getPayload();
            }
        }

        return (array) $payload;
    }
}
