<?php

declare(strict_types=1);

namespace Auth\Domain\Entity;

use Auth\Domain\ValueObject\Email;
use JsonSerializable;
use Symfony\Component\Uid\Ulid; // esse é um pacote isolado

class User implements JsonSerializable
{
    private function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email
    ) {}

    // public static function fill(string $id, string $name, string $email)
    // {
    //     return new self(...func_get_args());
    // }

    public static function new(string $name, Email $email): self
    {
        return new self(Ulid::generate(), $name, (string) $email);
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => (string) $this->email,
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => (string) $this->email,
        ];
    }
}
