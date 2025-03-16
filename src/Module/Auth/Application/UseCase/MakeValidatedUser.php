<?php

declare(strict_types=1);

namespace Auth\Application\UseCase;

use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Entity\User;
use Shared\Exception\BusinessException;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\ValidationCodeEnum;

class MakeValidatedUser
{
    public function __construct(private UserDaoInterface $userDao)
    {
    }

    public function make(string $userId): User
    {
        if (!($user = $this->userDao->find($userId))) {
            throw new BusinessException(ErrorCodeEnum::NOT_FOUND);
        }

        if ($user->isEmailVerified()) {
            throw new FieldException(['token' => ValidationCodeEnum::VERIFIED]);
        }

        $this->userDao->verified($user);
        return $user;
    }
}
