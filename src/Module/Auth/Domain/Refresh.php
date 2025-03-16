<?php

declare(strict_types=1);

namespace Auth\Domain;

// Shared -
use Shared\Exception\FieldException;
use Shared\Exception\BusinessException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\ValidationCodeEnum;
// Domain -
use Auth\Domain\Dto\TokenDto;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Token\TokenPayload;
use Auth\Domain\Token\TokenPayloadRefresh;

class Refresh
{
    public function __construct(private UserDaoInterface $userDao) {}

    public function make(string $userId, string $sessionId): TokenDto
    {
        if (!($user = $this->userDao->find($userId))) {
            throw new BusinessException(ErrorCodeEnum::NOT_FOUND);
        }

        if (!$user->isEmailVerified()) {
            throw new FieldException(["token" => ValidationCodeEnum::NOT_VERIFIED]);
        }

        if (!$user->isActive()) {
            throw new FieldException(["token" => $user->reason_status ?? ValidationCodeEnum::LOGIN_BLOCKED]);
        }

        return new TokenDto(new TokenPayload($userId, $sessionId), new TokenPayloadRefresh($userId, $sessionId));
    }
}
