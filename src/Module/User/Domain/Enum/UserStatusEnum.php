<?php

declare(strict_types=1);

namespace User\Domain\Enum;

enum UserStatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BLOCKED = 'blocked';

    public static function default(): self
    {
        return self::INACTIVE;
    }
}
