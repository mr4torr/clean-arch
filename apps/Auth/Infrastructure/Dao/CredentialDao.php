<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Dao;

use Carbon\Carbon;
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
            "id" => $credencial->getId(),
            "user_id" => $credencial->getUserId(),
            "provider" => $credencial->getProvider(),
            "hash" => $credencial->getHash(),
            "status" => $credencial->getStatus(),
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ]);
    }
}
