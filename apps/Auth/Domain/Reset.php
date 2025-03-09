<?php

declare(strict_types=1);

namespace Auth\Domain;

use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\ValueObject\Password;
use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Domain\Dto\TokenDto;
use Auth\Domain\Entity\Credential;
use Auth\Domain\Enum\ProviderEnum;
use Auth\Domain\Enum\UserStatusEnum;
use Auth\Domain\Logic\TokenLogic;
use Shared\Exception\BusinessException;
use Shared\Support\TokenInterface;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\ValidationCodeEnum;
use Shared\Support\HashInterface;

class Reset
{
    public function __construct(
        private HashInterface $hash,
        private TokenInterface $token,
        private UserDaoInterface $userDao,
        private TokenLogic $tokenLogic,
        private CredentialDaoInterface $credentialDao,
    ) {}

    public function make(string $token, Password $password): TokenDto
    {
        try {
            $resource = $this->token->decode($token);
        } catch (\Throwable $th) {
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

        // if ($decoded->remember_token !== $user->remember_token) {
        //     throw new FieldException(['remember_token' => ValidationCodeEnum::DIFFERENT_VALUE]);
        // }

        $this->credentialDao->activate($user->getId(), false, ProviderEnum::API);
        $credential = new Credential(
            id: $this->hash->generate(),
            user_id: $user->getId(),
            hash: (string) $password,
            provider: ProviderEnum::API
        );

        if (!$this->credentialDao->create($credential)) {
            $this->credentialDao->activate($user->getId(), true, ProviderEnum::API);
            throw new BusinessException(ErrorCodeEnum::INTERNAL_SERVER_ERROR, "auth.error.reset_credential");
        }

        return $this->tokenLogic->make($user);
    }
}
