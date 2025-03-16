<?php

declare(strict_types=1);

namespace Shared\Http;

use Psr\Http\Message\ResponseInterface;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\StatusCodeEnum;
use Shared\Http\Enums\SuccessCodeEnum;

interface ResponseFactoryInterface
{
    public function success(
        mixed $data = null,
        SuccessCodeEnum $code = SuccessCodeEnum::OK,
        ?string $message = null
    ): ResponseInterface;

    /**
     * @param array{string: string} $fields
     */
    public function error(
        array $fields = [],
        ErrorCodeEnum $code = ErrorCodeEnum::BAD_REQUEST,
        string $message = ''
    ): ResponseInterface;

    public function message(ErrorCodeEnum $code, ?string $message = null): ResponseInterface;

    /**
     * @param mixed $result
     */
    public function json($result, StatusCodeEnum $statusCode, int $options): ResponseInterface;
}
