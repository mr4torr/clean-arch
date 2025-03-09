<?php

declare(strict_types=1);

namespace Auth\Domain;

use Psr\EventDispatcher\EventDispatcherInterface;
use Auth\Domain\ValueObject\Email;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\ValueObject\Password;
use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Domain\Dto\TokenDto;
use Auth\Domain\Enum\UserStatusEnum;
use Auth\Domain\Logic\TokenLogic;
use Shared\Support\TokenInterface;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ValidationCodeEnum;

class SignIn
{
    public function __construct(
        private TokenInterface $token,
        private TokenLogic $tokenLogic,
        private UserDaoInterface $userDao,
        private CredentialDaoInterface $credentialDao,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    /**
     * @param \Auth\Domain\ValueObject\Email $email
     * @param \Auth\Domain\ValueObject\Password $password
     * @throws \Shared\Exception\FieldException
     */
    public function make(Email $email, Password $password): TokenDto
    {
        $user = $this->userDao->findByEmail($email);
        if (empty($user->getEmailVerifiedAt())) {
            throw new FieldException(['email' => ValidationCodeEnum::NOT_VERIFIED]);
        }

        if ($user->getStatus() !== UserStatusEnum::ACTIVE) {
            throw new FieldException(['email' => $user->getReasonStatus() ?? ValidationCodeEnum::LOGIN_BLOCKED]);
        }

        $credential = $this->credentialDao->findByUserId($user->getId());
        if (!$password->check($credential->getHash())) {
            throw new FieldException(['email' => ValidationCodeEnum::LOGIN_INVALID]);
        }

        return $this->tokenLogic->make($user);
    }
}
