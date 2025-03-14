<?php

declare(strict_types=1);

namespace Auth\Domain;

use Psr\EventDispatcher\EventDispatcherInterface;
use Auth\Domain\ValueObject\Email;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\ValueObject\Password;
use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Domain\Dao\SessionDaoInterface;
use Auth\Domain\Dto\TokenDto;
use Auth\Domain\Entity\Session;
use Auth\Domain\Enum\UserStatusEnum;
use Auth\Domain\Logic\TokenLogic;
use Shared\Token\TokenInterface;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ValidationCodeEnum;
use Shared\Support\HashInterface;

class SignIn
{
    public function __construct(
        private TokenInterface $token,
        private TokenLogic $tokenLogic,
        private HashInterface $hash,
        private UserDaoInterface $userDao,
        private CredentialDaoInterface $credentialDao,
        private SessionDaoInterface $sessionDao,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    /**
     * @param \Auth\Domain\ValueObject\Email $email
     * @param \Auth\Domain\ValueObject\Password $password
     * @throws \Shared\Exception\FieldException
     */
    public function make(
        Email $email,
        Password $password,
        ?string $ipAddress = null,
        ?string $userAgent = null,
    ): TokenDto {
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

        $this->sessionDao->clear($user->getId());
        $session = new Session(
            $this->hash->generate(),
            $user->getId(),
            $ipAddress,
            $userAgent,
        );

        $this->sessionDao->create($session);

        return $this->tokenLogic->make($user->getId(), $session->getId());
    }
}
