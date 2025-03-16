<?php

declare(strict_types=1);

namespace Auth\Domain\Dto\Input;

final class NetworkInfoDto
{
    public function __construct(
        public readonly string $ipAddress,
        public readonly string $userAgent
    ) {
    }

    public static function make(string $ipAddress, string $userAgent): self
    {
        return new self($ipAddress, $userAgent);
    }
}
