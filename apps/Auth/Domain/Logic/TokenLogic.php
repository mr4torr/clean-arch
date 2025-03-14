<?php

namespace Auth\Domain\Logic;

use Auth\Domain\Dto\TokenDto;
use Auth\Domain\ValueObject\TokenPayload;
use Auth\Domain\ValueObject\TokenPayloadRefresh;
use Shared\Token\TokenInterface;

class TokenLogic
{
    public function __construct(
        private TokenInterface $token,
    ) {}

    public function make(string $userId, string $sessionId): TokenDto
    {
        return new TokenDto(
            $this->token->encode(new TokenPayload($userId, $sessionId)),
            $this->token->encode(new TokenPayloadRefresh($userId, $sessionId))
        );
    }
}
