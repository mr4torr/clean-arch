<?php

declare(strict_types=1);

namespace Auth\Domain\Dao;

use Auth\Domain\Entity\Session;

interface SessionDaoInterface
{
    public function create(Session $session): ?Session;
    public function clear(string $userId): bool;

    public function all(string $userId, array $columns = ["*"]): array;

    public function exists(string $sessionId): bool;
}
