<?php

declare(strict_types=1);

namespace Auth\Presentation\Http;

use Auth\Application\UseCase\RevalidateToken;
use Core\Domain\Jwt\TokenInterface;
use Psr\Http\Message\ResponseInterface;
use Shared\Exception\BusinessException;
use Shared\Http\AbstractController;
use Shared\Http\Enums\ErrorCodeEnum;

class RefreshController extends AbstractController
{
    public function __invoke(RevalidateToken $service, TokenInterface $token): ResponseInterface
    {
        $this->request->validate(['token' => 'required']);

        $resource = $token->decode($this->request->get('token'), throw: true);

        if (
            !array_key_exists('id', $resource)
            || !array_key_exists('resource', $resource)
            || !array_key_exists('session_id', $resource)
            || $resource['resource'] !== 'refresh'
        ) {
            throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID);
        }

        $resource = $service->make($resource['id'], $resource['session_id']);

        return $this->response->success([
            'access_token' => $token->encode(
                $resource->accessToken->toArray(),
                $resource->accessToken->expiresAt()
            ),
            'refresh_token' => $token->encode(
                $resource->refreshToken->toArray(),
                $resource->refreshToken->expiresAt()
            ),
        ]);
    }
}
