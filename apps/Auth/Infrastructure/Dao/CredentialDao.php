<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Dao;

use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Symfony\Component\Uid\Ulid;
// Domain -
use Auth\Domain\Entity\Credential;
use Auth\Domain\Enum\ProviderEnum;
use Auth\Domain\Dao\CredentialDaoInterface;

class CredentialDao implements CredentialDaoInterface
{
    private string $table = "auth_credentials";

    public function create(Credential $credential): ?Credential
    {
        $credential->id = Ulid::generate();
        $credential->created_at = Carbon::now();
        $credential->updated_at = $credential->created_at;

        $insert = Db::table($this->table)->insert([
            "id" => $credential->id,
            "user_id" => $credential->user_id,
            "provider" => $credential->provider,
            "hash" => $credential->hash,
            "status" => $credential->status,
            "created_at" => $credential->created_at,
            "updated_at" => $credential->updated_at,
        ]);

        return $insert ? $credential : null;
    }

    public function delete(string $id): bool
    {
        return Db::table($this->table)->delete($id) > 0;
    }

    public function findByUserId(string $id, array $columns = ["*"]): ?Credential
    {
        return $this->resource(
            Db::table($this->table)->where("user_id", (string) $id)->where("status", true)->first($columns)
        );
    }

    public function activate(string $userId, bool $status = true, ProviderEnum $provider = ProviderEnum::API): bool
    {
        return Db::table($this->table)
            ->where([
                "user_id" => $userId,
                "provider" => $provider->value,
            ])
            ->update([
                "status" => $status,
                "updated_at" => Carbon::now(),
            ]) > 0;
    }

    private function resource(?object $resource): ?Credential
    {
        return $resource ? Credential::instantiate((array) $resource) : null;
    }
}
