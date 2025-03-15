<?php

declare(strict_types=1);

namespace User\Domain\Entity;

use DateTimeInterface;
use User\Domain\Enum\UserStatusEnum;
use User\Domain\ValueObject\Email;
use DateTime;

class User
{
    public function __construct(
        public string $name,
        public string $id,
        public Email $email,
        public UserStatusEnum $status = UserStatusEnum::INACTIVE,
        public ?DateTimeInterface $email_verified_at = null,
        public ?DateTimeInterface $created_at = null,
        public ?DateTimeInterface $updated_at = null,
        public ?string $reason_status = null
    ) {}

    public static function instantiate(array $data): self
    {
        if ($data["email"]) {
            $data["email"] = new Email($data["email"]);
        }
        if ($data["status"]) {
            $data["status"] = UserStatusEnum::from($data["status"]);
        }
        if ($data["email_verified_at"]) {
            $data["email_verified_at"] = new DateTime($data["email_verified_at"]);
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
