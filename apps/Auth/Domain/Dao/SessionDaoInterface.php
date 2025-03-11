<?php

declare(strict_types=1);

namespace Auth\Domain\Dao;

use Auth\Domain\Entity\Session;

interface SessionDaoInterface
{
    public function create(Session $session): bool;
    public function clear(string $userId): bool;
}
