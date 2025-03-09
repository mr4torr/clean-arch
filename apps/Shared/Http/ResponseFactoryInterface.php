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

    /**
     * @param array{string: string} $fields
     * @param \Shared\Http\Enums\ErrorCodeEnum $code
     * @param string $message
     * @return void
     */
    public function error(
        array $fields = [],
        ErrorCodeEnum $code = ErrorCodeEnum::BAD_REQUEST,
        string $message = ""
    ): ResponseInterface;

    public function message(ErrorCodeEnum $code, ?string $message = null): ResponseInterface;

    /**
     * @param mixed $result
     * @param \Shared\Http\Enums\StatusCodeEnum $statusCode
     * @param int $options
     * @return void
     */
    public function json($result, StatusCodeEnum $statusCode, int $options): ResponseInterface;
}
