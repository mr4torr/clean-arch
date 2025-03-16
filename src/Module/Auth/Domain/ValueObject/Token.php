<?php

declare(strict_types=1);

namespace Auth\Domain\ValueObject;

use Auth\Domain\Enum\TokenEnum;

final class Token
{
    public function __construct(private readonly array $resource)
    {
    }

    public function __toString(): string
    {
        return $this->resource['id'];
    }

    public function toArray(): array
    {
        return $this->resource;
    }

    public function validate(TokenEnum $token): bool
    {
        if (
            !array_key_exists('id', $this->resource)
            || !array_key_exists('resource', $this->resource)
            || $this->resource['resource'] !== $token->value
        ) {
            return false;
        }

        return true;
    }
}
