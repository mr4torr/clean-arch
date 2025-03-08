<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Dao;

use Hyperf\DbConnection\Db;
use Auth\Domain\Entity\User;
use Auth\Domain\ValueObject\Email;
use Auth\Domain\Dao\UserDaoInterface;
use Carbon\Carbon;

class UserDao implements UserDaoInterface
{
    private const TABLE_NAME = "auth_users";

    public function delete(string $id): bool
    {
        return Db::table(self::TABLE_NAME)->delete($id) > 0;
    }
    public function create(User $user): bool
    {
        return Db::table(self::TABLE_NAME)->insert([
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ]);
    }

    public function emailAlreadyExists(Email $email): bool
    {
        return Db::table(self::TABLE_NAME)->where("email", (string) $email)->exists();
    }
}
