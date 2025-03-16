<?php

declare(strict_types=1);

namespace Auth\Domain\Enum;

enum TokenEnum: string
{
    case REFRESH = 'refresh';
    case FORGOT = 'forgot';
    case CONFIRMATION = 'confirmation';
    case AUTHORIZATION = 'authorization';

    public static function default(): self
    {
        return self::AUTHORIZATION;
    }
}
