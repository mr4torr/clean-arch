<?php

declare(strict_types=1);

namespace Auth\Application\UseCase;

use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Entity\Credential;
use Auth\Domain\Enum\ProviderEnum;
use Auth\Domain\ValueObject\Password;
use Shared\Exception\BusinessException;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\ValidationCodeEnum;

class ChangePassword
{
    public function __construct(private UserDaoInterface $userDao, private CredentialDaoInterface $credentialDao)
    {
    }

    public function make(string $userId, Password $password): bool
    {
        if (!$password->validate()) {
            throw new FieldException(['password' => ValidationCodeEnum::PASSWORDS_NOT_MATCH]);
        }

        if (!($user = $this->userDao->find($userId))) {
            throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID);
        }

        if (!$user->isEmailVerified()) {
            throw new FieldException(['password' => ValidationCodeEnum::NOT_VERIFIED]);
        }

        if (!$user->isActive()) {
            throw new FieldException(['password' => $user->reason_status ?? ValidationCodeEnum::LOGIN_BLOCKED]);
        }

        $this->credentialDao->activate($user->id, false, ProviderEnum::API);
        $credential = $this->credentialDao->create(
            new Credential(user_id: $user->id, hash: (string) $password, provider: ProviderEnum::API)
        );

        if (!$credential) {
            $this->credentialDao->activate($user->id, true, ProviderEnum::API);
            throw new BusinessException(ErrorCodeEnum::INTERNAL_SERVER_ERROR, 'auth.error.reset_credential');
        }

        return true;
    }
}
