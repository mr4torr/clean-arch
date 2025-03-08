<?php

declare(strict_types=1);

namespace Auth\Domain\Entity;

use JsonSerializable;
use Shared\Support\HashInterface;
use Auth\Domain\Enum\ProviderEnum;
use Auth\Domain\ValueObject\Password;

class Credential implements JsonSerializable
{
    // protected $fillable = ["id", "password", "userId", "provider"];
    // protected $guarded = ["password"];

    private function __construct(
        public readonly string $id,
        public readonly string $hash,
        public readonly string $userId,
        public readonly string $provider
    ) {}

    // public static function fill(string $id, string $password, string $userId, string $provider)
    // {
    //     return new self(...func_get_args());
    // }

    public static function new(
        HashInterface $hasher,
        string $userId,
        Password $hash,
        ProviderEnum $provider = ProviderEnum::API
    ): self {
        return new self($hasher->generate(), (string) $hash, $userId, $provider->value);
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "userId" => $this->userId,
            "hash" => $this->hash,
            "provider" => $this->provider,
        ];
    }

    public function jsonSerialize(): array
    {
        $results = $this->toArray();
        unset($results["hash"]);
        return $results;
    }
}
