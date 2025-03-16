<?php

declare(strict_types=1);

namespace Auth\Domain;

// Shared -
use Shared\Exception\FieldException;
use Shared\Http\Enums\ValidationCodeEnum;
// Domain -
use Auth\Domain\Dto\TokenDto;
use Auth\Domain\Entity\Session;
use Auth\Domain\ValueObject\Email;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\ValueObject\Password;
use Auth\Domain\Dao\SessionDaoInterface;
use Auth\Domain\Token\TokenPayload;
use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Domain\Token\TokenPayloadRefresh;

class SignIn
{
    public function __construct(
        private UserDaoInterface $userDao,
        private CredentialDaoInterface $credentialDao,
        private SessionDaoInterface $sessionDao
    ) {}

    public function make(
        Email $email,
        Password $password,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): TokenDto {
        $user = $this->userDao->findByEmail($email);

        if (!$user) {
            throw new FieldException(["email" => ValidationCodeEnum::LOGIN_INVALID]);
        }

        if (!$user->isEmailVerified()) {
            throw new FieldException(["email" => ValidationCodeEnum::NOT_VERIFIED]);
        }

        if (!$user->isActive()) {
            throw new FieldException(["email" => $user->reason_status ?? ValidationCodeEnum::LOGIN_BLOCKED]);
        }

        $credential = $this->credentialDao->findByUserId($user->id);

        if (!$credential || !$password->check($credential->hash)) {
            throw new FieldException(["email" => ValidationCodeEnum::LOGIN_INVALID]);
        }

        $this->sessionDao->clear($user->id);
        $session = $this->sessionDao->create(new Session($user->id, $ipAddress, $userAgent));

        return new TokenDto(
            new TokenPayload($user->id, $session->id),
            new TokenPayloadRefresh($user->id, $session->id)
        );
    }
}
