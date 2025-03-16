<?php

declare(strict_types=1);

namespace Auth\Presentation\Http;

use Auth\Domain\Dao\SessionDaoInterface;
use Auth\Domain\Enum\TokenEnum;
use Shared\Context\AuthContext;
use Shared\Exception\BusinessException;
use Shared\Http\AbstractController;
use Shared\Http\Enums\ErrorCodeEnum;

/**
 * Responsável por validar o token JWT do usuário via api gateway do nginx.
 */
class AuthenticateController extends AbstractController
{
    public function __invoke(
        AuthContext $context,
        SessionDaoInterface $sessionDao,
    ): void {
        $context->validate(TokenEnum::Authorization->value);

        if (!$sessionDao->exists($context->getSessionId())) {
            throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID);
        }
    }
}
