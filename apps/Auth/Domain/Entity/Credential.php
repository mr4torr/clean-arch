<?php

declare(strict_types=1);

namespace Auth\Domain\Entity;

use ArrayAccess;
use JsonSerializable;
use Auth\Domain\Enum\ProviderEnum;
use Auth\Domain\ValueObject\Password;
use Symfony\Component\Uid\Ulid; // esse é um pacote isolado

class Credential implements JsonSerializable
{
    // protected $fillable = ["id", "password", "userId", "provider"];
    // protected $guarded = ["password"];

    private function __construct(
        public readonly string $id,
        #[\SensitiveParameter] public readonly string $password,
        public readonly string $userId,
        public readonly string $provider
    ) {}

    // public static function fill(string $id, string $password, string $userId, string $provider)
    // {
    //     return new self(...func_get_args());
    // }

    public static function new(
        string $userId,
        #[\SensitiveParameter] Password $password,
        ProviderEnum $provider = ProviderEnum::API
    ): self {
        return new self(Ulid::generate(), (string) $password, $userId, $provider->value);
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "userId" => $this->userId,
            "password" => $this->password,
            "provider" => $this->provider,
        ];
    }

    public function jsonSerialize(): array
    {
        $results = $this->toArray();
        unset($results["password"]);
        return $results;
    }
}
