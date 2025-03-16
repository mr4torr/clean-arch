<?php

declare(strict_types= 1);

namespace Shared\Context;

use Psr\Http\Message\ServerRequestInterface;
// Core -
use App\Domain\Jwt\TokenInterface;
// Shared -
use Shared\Exception\BusinessException;
use Shared\Http\Enums\ErrorCodeEnum;

class AuthContext
{
    private array $parameters;

    public function __construct(private ServerRequestInterface $request, private TokenInterface $token) {
    }

    public function get(?string $key = null)
    {
        $parameters = $this->getParameters();
        if ($key === null) {
            return $parameters;
        }

        return $parameters[$key] ?? null;
    }

    public function getUserId(): string
    {
        return $this->get('id') ?? throw new BusinessException(ErrorCodeEnum::AUTH_ERROR);
    }

    public function getSessionId(): string
    {
        return $this->get('session_id') ?? throw new BusinessException(ErrorCodeEnum::AUTH_ERROR);
    }

    private function getParameters()
    {
        if (!isset($this->parameters)) {
            $authorization = $this->request->getHeader("Authorization");
            $this->parameters = $this->token->decode(substr($authorization[0], 7));
        }

        return $this->parameters;
    }
}