<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Psr\Http\Message\ResponseInterface;
// Core -
use Core\Domain\Jwt\TokenInterface;
// Shared -
use Shared\Http\AbstractController;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Exception\BusinessException;
// Domain -
use Auth\Domain\Verify;
use Auth\Domain\Token\TokenConfirmationEmail;

class VerifyController extends AbstractController
{
    public function __invoke(Verify $service, TokenInterface $token): ResponseInterface
    {
        $this->request->validate(["token" => "required"]);

        $resource = $token->decode($this->request->get("token"), throw: true);

        if (
            !array_key_exists("id", $resource) ||
            !array_key_exists("resource", $resource) ||
            $resource['resource'] !== TokenConfirmationEmail::RESOURCE_TYPE
        ) {
            throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID);
        }

        $service->make($resource["id"]);

        return $this->response->success("Usuário ativado");
    }
}
