<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Dao;

use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Auth\Domain\Dao\SessionDaoInterface;
use Auth\Domain\Entity\Session;

class SessionDao implements SessionDaoInterface
{
    private string $table = "auth_sessions";

    public function create(Session $session): bool
    {
        return Db::table($this->table)->insert([
            "id" => $session->getId(),
            "user_id" => $session->getUserId(),
            "ip_address" => $session->getIdAddress(),
            "user_agent" => $session->getUserAgent(),
            "payload" => "",
            "created_at" => Carbon::now(),
            "last_activity" => Carbon::now(),
        ]);
    }

    public function clear(string $userId): bool
    {
        return Db::table($this->table)->where('user_id', $userId)->delete() > 0;
    }
}
