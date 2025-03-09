<?php
declare(strict_types=1);

namespace Shared\Http;

interface RequestFactoryInterface
{
    /**
     * @return array
     */
    public function all(): array;

    public function get(string $key, mixed $default = null): mixed;

    public function getContent(): bool|array|string|null;

    public function getContentMapped(string $classDto): object;

    /**
     * @return array
     */
    public function getPayload(): array;

    /**
     * @return array
     */
    public function getHeaders(): array;

    public function getMethod(): string;

    /**
     * @param array $rules
     * @param array $messages
     * @return void
     */
    public function validate(array $rules, array $messages = []): void;
}
