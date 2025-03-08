<?php
declare(strict_types=1);

namespace Shared\Support;

interface HashInterface
{
    public function generate(): string;
}
