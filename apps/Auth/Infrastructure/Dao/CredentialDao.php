<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Dao;

use Hyperf\DbConnection\Db;
use Auth\Domain\Entity\Credential;
use Auth\Domain\Dao\CredentialDaoInterface;

class CredentialDao implements CredentialDaoInterface
{
    private const TABLE_NAME = "auth_credenciais";

    public function create(Credential $credencial): void
    {
        // Db::table(self::TABLE_NAME)->insert([
        //     "id" => $credencial->id,
        //     "userId" => $credencial->userId,
        //     "provider" => $credencial->provider->value,
        //     "password" => $credencial->password,
        // ]);
    }
}
