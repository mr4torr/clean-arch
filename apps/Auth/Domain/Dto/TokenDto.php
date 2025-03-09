<?php

namespace Auth\Domain\Dto;

class TokenDto
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $refreshToken
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken
        ];
    }
}
