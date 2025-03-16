<?php

declare(strict_types=1);

namespace Shared\Http;

use Psr\Http\Message\ServerRequestInterface;
use Shared\Support\ArrayToObject;

abstract class AbstractRequestFactory implements RequestFactoryInterface
{
    public function __construct(private ServerRequestInterface $serverRequest)
    {
    }

    public function all(): array
    {
        return [
            ...$this->serverRequest->getAttributes(),
            ...$this->serverRequest->getQueryParams(),
            ...is_array($this->serverRequest->getParsedBody()) ? $this->serverRequest->getParsedBody() : [],
            ...is_array($this->serverRequest->getUploadedFiles()) ? $this->serverRequest->getUploadedFiles() : [],
        ];
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->all();
        if (array_key_exists($key, $all)) {
            return $all[$key];
        }

        return $default;
    }

    public function getHeaders(): array
    {
        return array_map('current', $this->serverRequest->getHeaders());
    }

    public function getHeader(string $key): ?string
    {
        return $this->getHeaders()[strtolower($key)] ?? null;
    }

    public function getMethod(): string
    {
        return $this->serverRequest->getMethod();
    }

    public function getPayload(): array
    {
        return $this->serverRequest->getParsedBody();
    }

    public function getContent(): string
    {
        return $this->serverRequest->getBody()->getContents();
    }

    public function getContentMapped(string $classDto): object
    {
        return ArrayToObject::make($classDto, $this->getPayload());
    }
}
