<?php

declare(strict_types=1);

namespace Shared\Http\Enums;

use Shared\Http\Objects\ResponseCode;

enum SuccessCodeEnum: int
{
    case OK = 2200;
    case CREATED = 2201;
    case ACCEPTED = 2202;
    case NO_CONTENT = 2204;
    case PARTIAL_CONTENT = 2206;

    public function get(): ResponseCode
    {
        return match ($this) {
            self::OK => ResponseCode::make("success.common.ok", StatusCodeEnum::OK),
            self::CREATED => ResponseCode::make("success.common.created", StatusCodeEnum::CREATED),
            self::ACCEPTED => ResponseCode::make("success.common.accept", StatusCodeEnum::ACCEPTED),
            self::NO_CONTENT => ResponseCode::make("success.common.no_content", StatusCodeEnum::NO_CONTENT),
            self::PARTIAL_CONTENT => ResponseCode::make(
                "success.common.partial_content",
                StatusCodeEnum::PARTIAL_CONTENT
            ),
        };
    }
}
