<?php

declare(strict_types=1);

namespace Shared\Exception;

use Shared\Http\Enums\ErrorCodeEnum;
use Throwable;

class FieldException extends BusinessException
{
    public function __construct(
        public readonly array $fields,
        ErrorCodeEnum $errorCode = ErrorCodeEnum::VALIDATION_FIELDS,
        ?Throwable $previous = null
    ) {
        parent::__construct($errorCode, "", $previous);
    }
}
