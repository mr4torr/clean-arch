<?php

declare(strict_types=1);

namespace User\Domain\Dao;

use User\Domain\Entity\User;

interface UserDaoInterface
{
    public function find(string $id, array $columns = ['*']): ?User;
}
