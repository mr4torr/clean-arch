<?php

declare(strict_types=1);

namespace Shared\Http\Enums;

use JsonSerializable;
use Shared\Http\Objects\ResponseCode;

enum ErrorCodeEnum: int implements JsonSerializable
{
    case NOT_FOUND = 1404;
    case UNAUTHORIZED = 1401;
    case FORBIDDEN = 1403;
    case BAD_REQUEST = 1400;
    case INTERNAL_SERVER_ERROR = 1500;
    case NOT_PERMISSION = 1601;

    case VALIDATION_FIELDS = 3000;

    case AUTH_ERROR = 9001;
    case AUTH_JWT_KEY_EMPTY = 9002;
    case AUTH_JWT_KEY_INVALID = 9003;

    public function get(): ResponseCode
    {
        return match ($this) {
            self::NOT_FOUND => ResponseCode::make("errors.common.not_found", StatusCodeEnum::NOT_FOUND),
            self::UNAUTHORIZED => ResponseCode::make("errors.common.unauthorized", StatusCodeEnum::UNAUTHORIZED),
            self::FORBIDDEN => ResponseCode::make("errors.common.forbidden", StatusCodeEnum::FORBIDDEN),
            self::INTERNAL_SERVER_ERROR => ResponseCode::make(
                "errors.common.internal_server_error",
                StatusCodeEnum::INTERNAL_SERVER_ERROR
            ),
            self::BAD_REQUEST => ResponseCode::make("errors.common.bad_request", StatusCodeEnum::BAD_REQUEST),
            self::NOT_PERMISSION => ResponseCode::make("errors.not_permission", StatusCodeEnum::BAD_REQUEST),
            self::AUTH_ERROR => ResponseCode::make("errors.auth.refused", StatusCodeEnum::BAD_REQUEST),
            self::AUTH_JWT_KEY_EMPTY => ResponseCode::make(
                "Token de autenticação ausente",
                StatusCodeEnum::UNAUTHORIZED
            ),
            self::AUTH_JWT_KEY_INVALID => ResponseCode::make(
                "Token de autenticação inválido",
                StatusCodeEnum::UNAUTHORIZED
            ),
            self::VALIDATION_FIELDS => ResponseCode::make("errors.validation.fields", StatusCodeEnum::BAD_REQUEST),
        };
    }

    public function jsonSerialize(): mixed
    {
        return $this->get()->message;
    }
}
