<?php

declare(strict_types=1);

namespace Auth\Domain;

// Shared -
use Shared\Exception\BusinessException;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\ValidationCodeEnum;
// Domain -
use Auth\Domain\Entity\User;
use Auth\Domain\Dao\UserDaoInterface;

class Verify
{
    public function __construct(private UserDaoInterface $userDao) {}

    public function make(string $userId): User
    {
        if (!($user = $this->userDao->find($userId))) {
            throw new BusinessException(ErrorCodeEnum::NOT_FOUND);
        }

        if ($user->isEmailVerified()) {
            throw new FieldException(["token" => ValidationCodeEnum::VERIFIED]);
        }

        $this->userDao->verified($user);
        return $user;
    }
}
