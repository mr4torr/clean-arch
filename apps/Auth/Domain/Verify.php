<?php

declare(strict_types=1);

namespace Auth\Domain;

use Auth\Domain\Entity\User;
use Auth\Domain\Dao\UserDaoInterface;
use Shared\Support\TokenInterface;
use Shared\Exception\BusinessException;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\ValidationCodeEnum;

class Verify
{
    public function __construct(
        private TokenInterface $token,
        private UserDaoInterface $userDao,
    ) {}

    public function make(string $token): User
    {
        $resource = $this->token->decode($token);
        if (!$user = $this->userDao->find($resource['id'])) {
            throw new BusinessException(ErrorCodeEnum::NOT_FOUND);
        }

        if ($user->getEmailVerifiedAt() !== null) {
            throw new FieldException(['token' => ValidationCodeEnum::NOT_VERIFIED]);
        }

        $this->userDao->verified($user);
        return $user;
    }
}
