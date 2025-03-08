<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Dao;

use Hyperf\DbConnection\Db;
use Auth\Domain\Entity\Credential;
use Auth\Domain\Dao\CredentialDaoInterface;

class CredentialDao implements CredentialDaoInterface
{
    private const TABLE_NAME = "auth_credentials";

    public function delete(string $id): bool
    {
        return Db::table(self::TABLE_NAME)->delete($id) > 0;
    }

    public function create(Credential $credencial): bool
    {
        return Db::table(self::TABLE_NAME)->insert([
            "id" => $credencial->id,
            "user_id" => $credencial->userId,
            "provider" => $credencial->provider,
            "hash" => $credencial->hash,
        ]);
    }
}
