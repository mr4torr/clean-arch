<?php

declare(strict_types=1);

namespace Auth\Domain\Enum;

enum ProviderEnum: string
{
    case API = 'api';

    public static function default(): self
    {
        return self::API;
    }
}
