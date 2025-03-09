<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Dao;

use Hyperf\DbConnection\Db;
use Auth\Domain\Entity\User;
use Auth\Domain\ValueObject\Email;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Enum\UserStatusEnum;
use Carbon\Carbon;

class UserDao implements UserDaoInterface
{
    private const TABLE_NAME = "auth_users";

    public function delete(string $id): bool
    {
        return Db::table(self::TABLE_NAME)->delete($id) > 0;
    }

    public function verified(User $user): bool
    {
        return Db::table(self::TABLE_NAME)->where("id", $user->getId())->update([
            "updated_at" => Carbon::now(),
            "email_verified_at" => Carbon::now(),
            "status" => UserStatusEnum::ACTIVE->value,
        ]) > 0;
    }

    public function findByEmail(Email $email, array $columns = ['*']): ?User
    {
        return $this->resource(
            Db::table(self::TABLE_NAME)->where('email', '=', (string) $email)->first($columns)
        );
    }

    public function find(string $id, array $columns = ['*']): ?User
    {
        return $this->resource(
            Db::table(self::TABLE_NAME)->find($id, $columns)
        );
    }

    public function create(User $user): bool
    {
        return Db::table(self::TABLE_NAME)->insert([
            "id" => $user->getId(),
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ]);
    }

    public function emailAlreadyExists(Email $email): bool
    {
        return Db::table(self::TABLE_NAME)->where("email", (string) $email)->exists();
    }

    private function resource(?object $resource): ?User
    {
        return $resource ? User::instance((array) $resource) : null;
    }
}
