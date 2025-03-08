<?php

declare(strict_types=1);

namespace Auth\Domain\Entity;

use JsonSerializable;
use Shared\Support\HashInterface;
use Auth\Domain\ValueObject\Email;

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

    public static function new(HashInterface $id, string $name, Email $email): self
    {
        return new self($id->generate(), $name, (string) $email);
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
