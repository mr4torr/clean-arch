<?php

namespace Auth\Domain\Dto;

use Auth\Domain\Token\TokenPayload;
use Auth\Domain\Token\TokenPayloadRefresh;

class TokenDto
{
    public function __construct(
        public readonly TokenPayload $accessToken,
        public readonly TokenPayloadRefresh $refreshToken
    ) {}
}
