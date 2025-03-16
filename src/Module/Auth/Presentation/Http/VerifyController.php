<?php

declare(strict_types=1);

namespace Auth\Presentation\Http;

use Auth\Application\UseCase\MakeValidatedUser;
use Auth\Domain\Enum\TokenEnum;
use Psr\Http\Message\ResponseInterface;
use Shared\Context\AuthContext;
use Shared\Exception\BusinessException;
use Shared\Http\AbstractController;
use Shared\Http\Enums\ErrorCodeEnum;

class VerifyController extends AbstractController
{
    public function __invoke(MakeValidatedUser $service, AuthContext $context): ResponseInterface
    {
        $this->request->validate(['token' => 'required']);

        if ($context->channel('token')->validate(TokenEnum::AUTHORIZATION->value)) {
            throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID);
        }

        $service->make($context->getUserId());

        return $this->response->success('Usuário ativado');
    }
}
