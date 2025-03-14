<?php

declare(strict_types=1);

namespace Auth\Domain;

use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Dto\TokenDto;
use Auth\Domain\Enum\UserStatusEnum;
use Auth\Domain\Logic\TokenLogic;
use Shared\Exception\BusinessException;
use Shared\Token\TokenInterface;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\ValidationCodeEnum;

class Refresh
{
    public function __construct(
        private TokenInterface $token,
        private UserDaoInterface $userDao,
        private TokenLogic $tokenLogic,
    ) {}

    public function make(string $token): TokenDto
    {
        try {
            $resource = $this->token->decode($token);
        } catch (\Throwable $th) {
            throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID);
        }

        if (
            !array_key_exists('refresh', $resource) ||
            !array_key_exists('id', $resource) ||
            !array_key_exists('session_id', $resource)
        ) {
            throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID);
        }

        if ($resource['refresh'] !== true) {
            throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID);
        }

        if (!$user = $this->userDao->find($resource['id'])) {
            throw new BusinessException(ErrorCodeEnum::NOT_FOUND);
        }

        if (empty($user->getEmailVerifiedAt())) {
            throw new FieldException(['password' => ValidationCodeEnum::NOT_VERIFIED]);
        }

        if ($user->getStatus() !== UserStatusEnum::ACTIVE) {
            throw new FieldException(['password' => $user->getReasonStatus() ?? ValidationCodeEnum::LOGIN_BLOCKED]);
        }

        return $this->tokenLogic->make($user->getId(), $resource['session_id']);
    }
}
