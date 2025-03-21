<?php

declare(strict_types=1);

namespace Shared\Exception;

use RuntimeException;
use Shared\Http\Enums\ErrorCodeEnum;
use Throwable;

class BusinessException extends RuntimeException
{
    public function __construct(
        public readonly ErrorCodeEnum $errorCode = ErrorCodeEnum::BAD_REQUEST,
        string $message = '',
        ?Throwable $previous = null
    ) {
        $error = $errorCode->get();
        parent::__construct($message, $error->statusCode->value, $previous);
    }
}
