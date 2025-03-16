<?php

declare(strict_types=1);

namespace Shared\Http;

use Hyperf\Contract\Arrayable;
use Hyperf\Contract\Jsonable;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\StatusCodeEnum;
use Shared\Http\Enums\SuccessCodeEnum;
use Throwable;

abstract class AbstractResponseFactory implements ResponseFactoryInterface
{
    /**
     * @param mixed $data
     */
    public function success(
        $data = null,
        SuccessCodeEnum $code = SuccessCodeEnum::OK,
        ?string $message = null
    ): PsrResponseInterface {
        $success = $code->get();
        $result = [
            'code' => $code->value,
            'message' => $message ?? $success->message,
            'data' => $data,
        ];

        return $this->json($result, $success->statusCode);
    }

    /**
     * @param array $fields
     */
    public function error(
        $fields = [],
        ErrorCodeEnum $code = ErrorCodeEnum::BAD_REQUEST,
        ?string $message = null
    ): PsrResponseInterface {
        $error = $code->get();
        $result = [
            'code' => $code->value,
            'message' => $message ?? $error->message,
            'fields' => $fields,
        ];

        return $this->json($result, $error->statusCode);
    }

    public function message(
        ErrorCodeEnum $code = ErrorCodeEnum::BAD_REQUEST,
        ?string $message = null
    ): PsrResponseInterface {
        $error = $code->get();
        $result = [
            'code' => $code->value,
            'message' => $message,
            'error' => $error->message,
        ];

        return $this->json($result, $error->statusCode);
    }

    /**
     * @param array|Arrayable|Jsonable $result
     * @param int $options
     */
    public function json(
        $result,
        StatusCodeEnum $statusCode = StatusCodeEnum::OK,
        $options = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    ): PsrResponseInterface {
        $data = $this->toJson($result, $options);

        return $this->response()
            ->withStatus($statusCode->value)
            ->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withBody($this->stream($data));
    }

    abstract protected function response(): PsrResponseInterface;

    abstract protected function stream(string $data): StreamInterface;

    /**
     * @param array|Arrayable|Jsonable $data
     * @param int $options
     */
    private function toJson($data, $options = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES): string
    {
        try {
            $result = json_encode($data, $options);
        } catch (Throwable $exception) {
            throw new RuntimeException($exception->getMessage(), $exception->getCode());
        }

        return $result;
    }
}
