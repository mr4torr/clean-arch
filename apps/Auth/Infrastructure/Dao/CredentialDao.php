<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Dao;

use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Auth\Domain\Entity\Credential;
use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Domain\Enum\ProviderEnum;

class CredentialDao implements CredentialDaoInterface
{
    private const TABLE_NAME = "auth_credentials";

    public function delete(string $id): bool
    {
        return Db::table(self::TABLE_NAME)->delete($id) > 0;
    }

    public function findByUserId(string $id, array $columns = ['*']): ?Credential
    {
        return $this->resource(
            Db::table(self::TABLE_NAME)->where('user_id', (string) $id)->where('status', true)->first($columns)
        );
    }

    public function create(Credential $credential): bool
    {
        return Db::table(self::TABLE_NAME)->insert([
            "id" => $credential->getId(),
            "user_id" => $credential->getUserId(),
            "provider" => $credential->getProvider(),
            "hash" => $credential->getHash(),
            "status" => $credential->getStatus(),
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ]);
    }

    public function activate(string $userId, bool $status = true, ProviderEnum $provider = ProviderEnum::API): bool
    {
        return Db::table(self::TABLE_NAME)->where([
            "user_id" => $userId,
            "provider" => $provider->value,
        ])->update([
            "status" => $status,
            "updated_at" => Carbon::now(),
        ]) > 0;
    }

    private function resource(?object $resource): ?Credential
    {
        return $resource ? Credential::instance((array) $resource) : null;
    }
}
