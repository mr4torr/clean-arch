<?php

declare(strict_types=1);

namespace Core\Application\Http;

use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Response as HyperfResponse;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Shared\Http\AbstractResponseFactory;

final class ResponseFactory extends AbstractResponseFactory
{
    public function __construct(private HyperfResponse $response)
    {
    }

    protected function response(): PsrResponseInterface
    {
        return $this->response;
    }

    protected function stream(string $data): SwooleStream
    {
        return new SwooleStream($data);
    }
}
