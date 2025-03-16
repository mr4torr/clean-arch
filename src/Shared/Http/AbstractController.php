<?php

declare(strict_types=1);

namespace Shared\Http;

use Psr\Container\ContainerInterface;
use Shared\Http\RequestFactoryInterface;
use Shared\Http\ResponseFactoryInterface;

abstract class AbstractController
{
    public function __construct(
        protected ContainerInterface $container,
        protected ResponseFactoryInterface $response,
        protected RequestFactoryInterface $request
    ) {}
}
