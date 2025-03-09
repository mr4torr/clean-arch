<?php

namespace Auth\Domain\Logic;

use Auth\Domain\Dto\TokenDto;
use Auth\Domain\Entity\User;
use Shared\Support\TokenInterface;

class TokenLogic
{
    private const JWT_TTL = 60 * 60; // segundos = 15min
    private const REFRESH_JWT_TTL = 24 * 60 * 60 ; // segundos = 1 dia

    public function __construct(
        private TokenInterface $token,
    ) {}

    public function make(User $user): TokenDto
    {
        $tokenPayload = [
            'id' => $user->getId(),
            // 'current_team_id' => $user->current_team_id,
            'iat' => time(),
            'exp' => time() + self::JWT_TTL,
        ];

        $accessToken = $this->token->encode($tokenPayload);
        $refreshToken = $this->token->encode([
            ...$tokenPayload,
            'refresh' => true,
            'exp' => time() + self::REFRESH_JWT_TTL
        ]);

        return new TokenDto($accessToken, $refreshToken);
    }
}
