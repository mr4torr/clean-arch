<?php

declare(strict_types=1);

namespace Auth\Domain\Entity;

use JsonSerializable;
use Shared\Support\HashInterface;
use Auth\Domain\Enum\ProviderEnum;
use Auth\Domain\ValueObject\Password;
use DateTimeInterface;

class Credential implements JsonSerializable
{
    // protected $fillable = ["id", "password", "userId", "provider"];
    // protected $guarded = ["password"];
    private ?DateTimeInterface $created_at = null;
    private ?DateTimeInterface $updated_at = null;

    private function __construct(
        private string $id,
        private string $hash,
        private string $userId,
        private string $provider,
        private bool $status
    ) {}

    // public static function fill(string $id, string $password, string $userId, string $provider)
    // {
    //     return new self(...func_get_args());
    // }

    public static function new(
        HashInterface $hasher,
        string $userId,
        Password $hash,
        ProviderEnum $provider = ProviderEnum::API,
        bool $status = false,
    ): self {
        return new self($hasher->generate(), (string) $hash, $userId, $provider->value, $status);
    }

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
            "id" => $this->id,
            "userId" => $this->userId,
            "provider" => $this->provider,
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
    public function getProvider (): string {
        return $this->provider;
    }
    public function getStatus (): bool {
        return $this->status;
    }
}
