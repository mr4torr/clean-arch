<?php

declare(strict_types=1);

namespace User\Infrastructure\Dao;

use Hyperf\DbConnection\Db;
// Domain -
use User\Domain\Dao\UserDaoInterface;
use User\Domain\Entity\User;

class UserDao implements UserDaoInterface
{
    private string $table = 'auth_users';

    public function find(string $id, array $columns = ['*']): ?User
    {
        return $this->resource(Db::table($this->table)->find($id, $columns));
    }

    private function resource(?object $resource): ?User
    {
        return $resource ? User::instantiate((array) $resource) : null;
    }
}
