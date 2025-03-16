<?php

declare(strict_types=1);

namespace Auth\Domain\Enum;

enum TokenEnum: string
{
    case Refresh = 'refresh';
    case Forgot = 'forgot';
    case Confirmation = 'confirmation';
    case Authorization = 'authorization';

    public static function default(): self
    {
        return self::Authorization;
    }
}
