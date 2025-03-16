<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Psr\Http\Message\ResponseInterface;
// External -
use Core\Domain\Jwt\TokenInterface;
// Shared -
use Shared\Http\AbstractController;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Exception\BusinessException;
// Domain -
use Auth\Domain\Reset;
use Auth\Domain\Token\TokenForgotEmail;
use Auth\Domain\ValueObject\Password;

class ResetController extends AbstractController
{
    public function __invoke(Reset $service, TokenInterface $token): ResponseInterface
    {
        $this->request->validate([
            "token" => "required",
            "password" => "required|min:8|confirmed",
        ]);

        $resource = $token->decode($this->request->get("token"), throw: true);

        if (
            !array_key_exists("id", $resource) ||
            !array_key_exists("resource", $resource) ||
            $resource['resource'] !== TokenForgotEmail::RESOURCE_TYPE
        ) {
            throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID);
        }

        $service->make($resource["id"], new Password($this->request->get("password")));

        return $this->response->success("Senha alterada com sucesso");
    }
}
