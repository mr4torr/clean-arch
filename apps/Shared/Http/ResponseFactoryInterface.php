<?php
declare(strict_types=1);

namespace Shared\Http;

use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\StatusCodeEnum;
use Shared\Http\Enums\SuccessCodeEnum;
use Psr\Http\Message\ResponseInterface;

interface ResponseFactoryInterface
{
    public function success(
        mixed $data = null,
        SuccessCodeEnum $code = SuccessCodeEnum::OK,
        ?string $message = null
    ): ResponseInterface;

    public function error(
        array $fields = [],
        ErrorCodeEnum $code = ErrorCodeEnum::BAD_REQUEST,
        string $message = ""
    ): ResponseInterface;

    public function message(ErrorCodeEnum $code, ?string $message = null): ResponseInterface;

    public function json($result, StatusCodeEnum $statusCode, int $options): ResponseInterface;
}
