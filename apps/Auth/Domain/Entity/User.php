<?php

declare(strict_types=1);

namespace Auth\Domain\Entity;

use JsonSerializable;
use DateTimeInterface;
use Auth\Domain\Enum\UserStatusEnum;
use Auth\Domain\ValueObject\Email;
use DateTime;

use function Termwind\parse;

class User implements JsonSerializable
{
    public function __construct(
        private string $id,
        private string $name,
        private Email $email,
        private UserStatusEnum $status = UserStatusEnum::INACTIVE,
        private ?DateTimeInterface $email_verified_at = null,
        private ?DateTimeInterface $created_at = null,
        private ?DateTimeInterface $updated_at = null,
        private ?string $reason_status = null,
    ) {}

    public static function instance(array $data): self
    {
        if ($data['email']) $data['email'] = new Email($data['email']);
        if ($data['status']) $data['status'] = UserStatusEnum::from($data['status']);
        if ($data['email_verified_at']) $data['email_verified_at'] = new DateTime($data['email_verified_at']);
        if ($data['created_at']) $data['created_at'] = new DateTime($data['created_at']);
        if ($data['updated_at']) $data['updated_at'] = new DateTime($data['updated_at']);

        return new self(...$data);
    }

    /**
     * @return array{email: string, id: string, name: string}
     */
    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "email" => $this->getEmail(),
            "email_verified_at" => $this->getEmailVerifiedAt(),
            "status" => [
                "code" => $this->getStatus(),
                "reason" => $this->getReasonStatus(),
            ],
            "created_at" => $this->getCreatedAt(),
            "updated_at" => $this->getUpdatedAt(),
        ];
    }

    public function getId(): string {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getEmail(): Email {
        return $this->email;
    }

    public function getEmailVerifiedAt(): ?DateTimeInterface {
        return $this->email_verified_at;
    }

    public function getStatus(): UserStatusEnum {
        return $this->status;
    }

    public function getReasonStatus(): ?string {
        return $this->reason_status;
    }

    public function getCreatedAt(): ?DateTimeInterface {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?DateTimeInterface {
        return $this->updated_at;
    }
}
