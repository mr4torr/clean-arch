<?php

declare(strict_types=1);

namespace Shared\Http;

use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    public function __construct(
        protected ContainerInterface $container,
        protected ResponseFactoryInterface $response,
        protected RequestFactoryInterface $request
    ) {
    }
}
