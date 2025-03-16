<?php

declare(strict_types=1);

namespace Shared\Http\Objects;

use Shared\Http\Enums\StatusCodeEnum;

class ResponseCode
{
    public function __construct(public readonly string $message, public readonly StatusCodeEnum $statusCode)
    {
    }

    public static function make(string $message, StatusCodeEnum $statusCode): self
    {
        return new self($message, $statusCode);
    }
}
