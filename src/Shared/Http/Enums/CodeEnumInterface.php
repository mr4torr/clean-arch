<?php

declare(strict_types=1);

namespace Shared\Http\Enums;

use Shared\Http\Objects\ResponseCode;

interface CodeEnumInterface
{
    public function get(): ResponseCode;
}
