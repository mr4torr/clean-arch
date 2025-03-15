<?php

declare(strict_types=1);

namespace Auth\Domain\Entity;

use DateTime;
use DateTimeInterface;

class Session
{
    public function __construct(
        public string $user_id,
        public ?string $ip_address,
        public ?string $user_agent,
        public ?string $id = null,
        public ?string $payload = null,
        public ?DateTimeInterface $last_activity = null,
        public ?DateTimeInterface $created_at = null
    ) {}

    public static function instantiate(array $data): self
    {
        if ($data["last_activity"]) {
            $data["last_activity"] = new DateTime($data["last_activity"]);
        }
        if ($data["created_at"]) {
            $data["created_at"] = new DateTime($data["created_at"]);
        }

        return new self(...$data);
    }
}
