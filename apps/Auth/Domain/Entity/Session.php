<?php

declare(strict_types=1);

namespace Auth\Domain\Entity;

use DateTime;
use JsonSerializable;
use DateTimeInterface;

class Session implements JsonSerializable
{
    public function __construct(
        private string $id,
        private string $user_id,
        private ?string $id_address,
        private ?string $user_agent,
        private ?string $payload = null,
        private ?DateTimeInterface $last_activity = null,
        private ?DateTimeInterface $created_at = null,
    ) {}

    public static function instantiate(array $data): self
    {
        if ($data['last_activity']) $data['last_activity'] = new DateTime($data['last_activity']);
        if ($data['created_at']) $data['created_at'] = new DateTime($data['created_at']);

        return new self(...$data);
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "user_id" => $this->getUserId(),
            "ip_address" => $this->getIdAddress(),
            "user_agent" => $this->getUserAgent(),
            "payload" => $this->getPayload(),
            "last_activity" => $this->getLastActivity(),
            "created_at" => $this->getCreatedAt(),
        ];
    }

    public function getId(): string {
        return $this->id;
    }

    public function getUserId(): string {
        return $this->user_id;
    }

    public function getIdAddress(): string {
        return $this->id_address;
    }

    public function getUserAgent(): string {
        return $this->user_agent;
    }

    public function getPayload(): string {
        return $this->payload;
    }

    public function getLastActivity(): ?DateTimeInterface {
        return $this->last_activity;
    }

    public function getCreatedAt(): ?DateTimeInterface {
        return $this->created_at;
    }
}
