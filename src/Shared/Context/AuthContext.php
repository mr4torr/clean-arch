<?php

declare(strict_types=1);

namespace Shared\Context;

use Core\Domain\Jwt\TokenInterface;
use Exception;
use Shared\Exception\BusinessException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\RequestFactoryInterface;

class AuthContext
{
    private array $parameters;

    private string $channel;

    public function __construct(private RequestFactoryInterface $request, private TokenInterface $token)
    {
        $this->channel = 'header';
    }

    public function channel(string $channel): self
    {
        $this->parameters = [];
        $this->channel = $channel;

        return $this;
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
        return $this->get('id') ?? throw new BusinessException(ErrorCodeEnum::AUTH_ERROR, $this->getEndpoint());
    }

    public function getSessionId(): string
    {
        return $this->get('session_id') ?? throw new BusinessException(ErrorCodeEnum::AUTH_ERROR, $this->getEndpoint());
    }

    public function validate(string $type): void
    {
        $parameters = $this->get();

        if (
            !array_key_exists('id', $parameters)
            || !array_key_exists('resource', $parameters)
            || $parameters['resource'] !== $type
        ) {
            throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID, $this->getEndpoint());
        }
    }

    private function getParameters()
    {
        if (!isset($this->parameters) || empty($this->parameters)) {
            $bearerToken = $this->channel === 'header'
                ? $this->request->getHeader('Authorization')
                : $this->request->get($this->channel);

            if (empty($bearerToken)) {
                throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_EMPTY, $this->getEndpoint());
            }

            if ($this->channel === 'header') {
                $bearerToken = substr($bearerToken, 7);
            }

            try {
                $this->parameters = $this->token->decode($bearerToken);
            } catch (Exception $e) {
                throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID, $this->getEndpoint(), $e);
            }
        }

        return $this->parameters;
    }

    private function getEndpoint(): string
    {
        if ($this->request->getHeader('X-target')) {
            return 'endpoint: ' . $this->request->getHeader('X-target');
        }

        return '';
    }
}
