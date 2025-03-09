<?php

declare(strict_types=1);

namespace Auth\Domain\Entity;

use JsonSerializable;
use Shared\Support\HashInterface;
use Auth\Domain\ValueObject\Email;
use DateTimeInterface;

class User implements JsonSerializable
{
    private ?DateTimeInterface $email_verified_at = null;
    private ?DateTimeInterface $created_at = null;
    private ?DateTimeInterface $updated_at = null;

    private function __construct(
        private string $id,
        private string $name,
        private string $email
    ) {}

    // public static function fill(string $id, string $name, string $email)
    // {
    //     return new self(...func_get_args());
    // }

    public static function new(HashInterface $id, string $name, Email $email): self
    {
        $resource = new self(
            id: $id->generate(),
            name: $name,
            email: (string) $email,
        );

        return $resource;
    }

    /**
     * @return array{email: string, id: string, name: string}
     */
    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "email_verified_at" => $this->email_verified_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }

    public function getId(): string {
        return $this->id;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getEmail(): string {
        return $this->email;
    }
    public function getEmailVerifiedAt(): ?DateTimeInterface {
        return $this->email_verified_at;
    }
}
