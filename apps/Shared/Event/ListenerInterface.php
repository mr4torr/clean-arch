<?php

declare(strict_types=1);

namespace Shared\Event;

interface ListenerInterface
{
    public function listen(): array;
    public function process(object $event): void;
}
