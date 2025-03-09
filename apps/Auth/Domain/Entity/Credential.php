<?php

declare(strict_types=1);

namespace Auth\Domain\Entity;

use DateTime;
use JsonSerializable;
use DateTimeInterface;
use Auth\Domain\Enum\ProviderEnum;

class Credential implements JsonSerializable
{
    // protected $fillable = ["id", "password", "userId", "provider"];
    // protected $guarded = ["password"];

    public function __construct(
        private string $id,
        private string $hash,
        private string $userId,
        private ProviderEnum $provider = ProviderEnum::API,
        private bool $status = true,
        private ?DateTimeInterface $created_at = null,
        private ?DateTimeInterface $updated_at = null,
    ) {}

    public static function instance(array $data): self
    {
        if ($data['provider']) $data['provider'] = ProviderEnum::from($data['provider']);
        if ($data['created_at']) $data['created_at'] = new DateTime($data['created_at']);
        if ($data['updated_at']) $data['updated_at'] = new DateTime($data['updated_at']);

        return new self(...$data);
    }

    // public static function fill(string $id, string $password, string $userId, string $provider)
    // {
    //     return new self(...func_get_args());
    // }

    // public static function new(
    //     HashInterface $hasher,
    //     string $userId,
    //     Password $hash,
    //     ProviderEnum $provider = ProviderEnum::API,
    //     bool $status = false,
    // ): self {
    //     return new self($hasher->generate(), (string) $hash, $userId, $provider->value, $status);
    // }

    /**
     * @return array{
     *      id: string,
     *      userId: string
     *      provider: string,
     *      email_verified_at: DateTimeInterface|null,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "userId" => $this->getUserId(),
            "provider" => $this->getProvider(),
            "status" => $this->getStatus(),
            "created_at" => $this->getCreatedAt(),
            "updated_at" => $this->getUpdatedAt()
        ];
    }

    public function getId (): string {
        return $this->id;
    }

    public function getHash (): string {
        return $this->hash;
    }

    public function getUserId (): string {
        return $this->userId;
    }

    public function getProvider (): ProviderEnum {
        return $this->provider;
    }

    public function getStatus (): bool {
        return $this->status;
    }

    public function getCreatedAt(): ?DateTimeInterface {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?DateTimeInterface {
        return $this->updated_at;
    }
}
