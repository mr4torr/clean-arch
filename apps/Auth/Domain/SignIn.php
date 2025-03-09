<?php

declare(strict_types=1);

namespace Auth\Domain;

use Psr\EventDispatcher\EventDispatcherInterface;
use Auth\Domain\ValueObject\Email;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\ValueObject\Password;
use Auth\Domain\Dao\CredentialDaoInterface;
use Shared\Support\TokenInterface;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ValidationCodeEnum;

class SignIn
{
    private const JWT_TTL = 60 * 60; // segundos = 15min
    private const REFRESH_JWT_TTL = 24 * 60 * 60 ; // segundos = 1 dia

    public function __construct(
        private TokenInterface $token,
        private UserDaoInterface $userDao,
        private CredentialDaoInterface $credentialDao,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    /**
     * @param \Auth\Domain\ValueObject\Email $email
     * @param \Auth\Domain\ValueObject\Password $password
     * @throws \Shared\Exception\FieldException
     * @return array{access_token: string, refresh_token: string}
     */
    public function make(Email $email, Password $password): array
    {
        $user = $this->userDao->findByEmail($email);
        if (empty($user->getEmailVerifiedAt())) {
            throw new FieldException(['email' => ValidationCodeEnum::NOT_VERIFIED]);
        }

        $credential = $this->credentialDao->findByUserId($user->getId());

        if (!$password->check($credential->getHash())) {
            throw new FieldException(['email' => ValidationCodeEnum::LOGIN_INVALID]);
        }


        $tokenPayload = [
            'id' => $user->getId(),
            // 'current_team_id' => $user->current_team_id,
            'iat' => time(),
            'exp' => time() + self::JWT_TTL,
        ];

        $accessToken = $this->token->encode($tokenPayload);
        $refreshToken = $this->token->encode([
            ...$tokenPayload,
            'exp' => time() + self::REFRESH_JWT_TTL
        ]);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
    }
}
