<?php

declare(strict_types=1);

namespace Shared\Http;

interface RequestFactoryInterface
{
    public function all(): array;

    public function get(string $key, mixed $default = null): mixed;

    public function getContent(): null|array|bool|string;

    public function getContentMapped(string $classDto): object;

    public function getPayload(): array;

    public function getHeaders(): array;

    public function getHeader(string $key): ?string;

    public function getMethod(): string;

    public function validate(array $rules, array $messages = []): void;
}
