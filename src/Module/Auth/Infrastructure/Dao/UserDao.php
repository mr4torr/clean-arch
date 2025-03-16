<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Dao;

use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Symfony\Component\Uid\Ulid;
// Domain -
use Auth\Domain\Entity\User;
use Auth\Domain\ValueObject\Email;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Enum\UserStatusEnum;

class UserDao implements UserDaoInterface
{
    private string $table = "auth_users";

    public function create(User $user): ?User
    {
        $user->id = Ulid::generate();
        $user->created_at = Carbon::now();
        $user->updated_at = $user->created_at;

        $insert = Db::table($this->table)->insert([
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at,
        ]);

        return $insert ? $user : null;
    }

    public function delete(string $id): bool
    {
        return Db::table($this->table)->delete($id) > 0;
    }

    public function verified(User $user): bool
    {
        return Db::table($this->table)
            ->where("id", $user->id)
            ->update([
                "updated_at" => Carbon::now(),
                "email_verified_at" => Carbon::now(),
                "status" => UserStatusEnum::ACTIVE,
            ]) > 0;
    }

    public function findByEmail(Email $email, array $columns = ["*"]): ?User
    {
        return $this->resource(Db::table($this->table)->where("email", "=", (string) $email)->first($columns));
    }

    public function find(string $id, array $columns = ["*"]): ?User
    {
        return $this->resource(Db::table($this->table)->find($id, $columns));
    }

    public function emailAlreadyExists(Email $email): bool
    {
        return Db::table($this->table)->where("email", (string) $email)->exists();
    }

    private function resource(?object $resource): ?User
    {
        return $resource ? User::instantiate((array) $resource) : null;
    }
}
