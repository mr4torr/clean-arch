<?php
declare(strict_types=1);

namespace Shared\Http;

interface RequestFactoryInterface
{
    public function all(): array;

    public function get(string $key, mixed $default = null): mixed;

    public function getContent(): bool|array|string|null;

    public function getContentMapped(string $classDto): object;

    /**
     * @return mixed
     */
    public function getPayload(): array;

    /**
     * @return mixed
     */
    public function getHeaders(): array;

    public function getMethod(): string;
}
