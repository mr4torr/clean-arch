<?php

declare(strict_types=1);

namespace Auth\Domain\Entity;

use DateTime;
use DateTimeInterface;
// -
use Auth\Domain\Enum\ProviderEnum;

class Credential
{
    public function __construct(
        public string $hash,
        public string $user_id,
        public ?string $id = null,
        public ProviderEnum $provider = ProviderEnum::API,
        public bool $status = true,
        public ?DateTimeInterface $created_at = null,
        public ?DateTimeInterface $updated_at = null
    ) {}

    public static function instantiate(array $data): self
    {
        if ($data["provider"]) {
            $data["provider"] = ProviderEnum::from($data["provider"]);
        }
        if ($data["created_at"]) {
            $data["created_at"] = new DateTime($data["created_at"]);
        }
        if ($data["updated_at"]) {
            $data["updated_at"] = new DateTime($data["updated_at"]);
        }

        return new self(...$data);
    }
}
