<?php

declare(strict_types=1);

namespace App\Infrastructure\Support;

use Shared\Support\HashInterface;
use Symfony\Component\Uid\Ulid;

class Hash implements HashInterface
{
    public function generate(): string
    {
        return Ulid::generate();
    }
}