<?php

declare(strict_types=1);

namespace Auth\Domain\Dto\Output;

use Auth\Domain\Token\TokenPayload;
use Auth\Domain\Token\TokenPayloadRefresh;

class TokenDto
{
    public function __construct(
        public readonly TokenPayload $accessToken,
        public readonly TokenPayloadRefresh $refreshToken
    ) {
    }
}
