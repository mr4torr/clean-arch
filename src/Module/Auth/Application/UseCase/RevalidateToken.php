<?php

declare(strict_types=1);

namespace Auth\Application\UseCase;

use Auth\Domain\Dao\SessionDaoInterface;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Dto\Output\TokenDto;
use Auth\Domain\Token\TokenPayload;
use Auth\Domain\Token\TokenPayloadRefresh;
use Shared\Exception\BusinessException;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\ValidationCodeEnum;

class RevalidateToken
{
    public function __construct(
        private UserDaoInterface $userDao,
        private SessionDaoInterface $sessionDao,
    ) {
    }

    public function make(string $userId, string $sessionId): TokenDto
    {
        if (!$this->sessionDao->exists($sessionId)) {
            throw new FieldException(['token' => ValidationCodeEnum::TOKEN_EXPIRED]);
        }

        if (!($user = $this->userDao->find($userId))) {
            throw new BusinessException(ErrorCodeEnum::NOT_FOUND);
        }

        if (!$user->isEmailVerified()) {
            throw new FieldException(['token' => ValidationCodeEnum::NOT_VERIFIED]);
        }

        if (!$user->isActive()) {
            throw new FieldException(['token' => $user->reason_status ?? ValidationCodeEnum::LOGIN_BLOCKED]);
        }

        return new TokenDto(new TokenPayload($userId, $sessionId), new TokenPayloadRefresh($userId, $sessionId));
    }
}
