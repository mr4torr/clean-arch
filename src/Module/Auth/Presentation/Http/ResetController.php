<?php

declare(strict_types=1);

namespace Auth\Presentation\Http;

use Auth\Application\UseCase\ChangePassword;
use Auth\Domain\Enum\TokenEnum;
use Auth\Domain\ValueObject\Password;
use Psr\Http\Message\ResponseInterface;
use Shared\Context\AuthContext;
use Shared\Exception\BusinessException;
use Shared\Http\AbstractController;
use Shared\Http\Enums\ErrorCodeEnum;

class ResetController extends AbstractController
{
    public function __invoke(ChangePassword $service, AuthContext $context): ResponseInterface
    {
        $this->request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($context->channel('token')->validate(TokenEnum::Forgot->value)) {
            throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID);
        }

        $service->make($context->getUserId(), new Password($this->request->get('password')));

        return $this->response->success('Senha alterada com sucesso');
    }
}
