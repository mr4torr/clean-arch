<?php

declare(strict_types=1);

namespace Shared\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

interface AppExceptionInterface
{
    public function handle(Throwable $throwable, ResponseInterface $response);

    public function isValid(Throwable $throwable): bool;
}
