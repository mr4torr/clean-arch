<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Dao;

use Hyperf\DbConnection\Db;
use Auth\Domain\Entity\User;
use Auth\Domain\ValueObject\Email;
use Auth\Domain\Dao\UserDaoInterface;

class UserDao implements UserDaoInterface
{
    private const TABLE_NAME = "auth_user";

    public function create(User $credencial): void
    {
        // Db::table(self::TABLE_NAME)->insert([
        //     "id" => $credencial->id,
        //     "userId" => $credencial->userId,
        //     "provider" => $credencial->provider->value,
        //     "password" => $credencial->password,
        // ]);
    }

    public function emailAlreadyExists(Email $email): bool
    {
        return false;
    }
}
