<?php

declare(strict_types=1);

namespace Shared\Exception;

use Throwable;
use Psr\Http\Message\ResponseInterface;

interface AppExceptionInterface
{
    public function handle(Throwable $throwable, ResponseInterface $response);

    public function isValid(Throwable $throwable): bool;
}
