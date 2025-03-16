<?php

declare(strict_types=1);

namespace Core\Domain\Support;

interface IpAddressInterface
{
    public function get(): string;
}
