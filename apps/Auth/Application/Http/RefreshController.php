<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Psr\Http\Message\ResponseInterface;
// Core -
use App\Domain\Jwt\TokenInterface;
// Shared -
use Shared\Exception\BusinessException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\AbstractController;
// Domain -
use Auth\Domain\Refresh;

class RefreshController extends AbstractController
{
    public function __invoke(Refresh $service, TokenInterface $token): ResponseInterface
    {
        $this->request->validate(["token" => "required"]);

        $resource = $token->decode($this->request->get("token"), throw: true);

        if (
            !array_key_exists("id", $resource) ||
            !array_key_exists("resource", $resource) ||
            !array_key_exists("session_id", $resource) ||
            $resource['resource'] !== 'refresh'
        ) {
            throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID);
        }

        $resource = $service->make($resource["id"], $resource["session_id"]);

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
