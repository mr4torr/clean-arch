<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Dao;

use Auth\Domain\Dao\SessionDaoInterface;
use Auth\Domain\Entity\Session;
use Carbon\Carbon;
use Hyperf\Collection\Collection;
// Domain -
use Hyperf\DbConnection\Db;
use Symfony\Component\Uid\Ulid;

class SessionDao implements SessionDaoInterface
{
    private string $table = 'auth_sessions';

    public function create(Session $session): ?Session
    {
        $session->id = Ulid::generate();
        $session->created_at = Carbon::now();
        $session->last_activity = $session->created_at;

        $insert = Db::table($this->table)->insert([
            'id' => $session->id,
            'user_id' => $session->user_id,
            'ip_address' => $session->ip_address,
            'user_agent' => $session->user_agent,
            'payload' => null,
            'created_at' => $session->created_at,
            'last_activity' => $session->last_activity,
        ]);

        return $insert ? $session : null;
    }

    public function clear(string $userId): bool
    {
        return Db::table($this->table)->where('user_id', $userId)->delete() > 0;
    }

    public function all(string $id, array $columns = ['*']): array
    {
        return $this->collection(Db::table($this->table)->where('user_id', (string) $id)->get($columns));
    }

    public function exists(string $id): bool
    {
        return Db::table($this->table)->where('id', (string) $id)->exists();
    }

    private function collection(Collection $collection): array
    {
        return $collection->map(fn ($item) => Session::instantiate((array) $item))->toArray();
    }
}
