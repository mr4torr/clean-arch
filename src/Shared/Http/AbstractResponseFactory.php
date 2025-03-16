<?php

declare(strict_types=1);

namespace Shared\Http;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\StreamInterface;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\SuccessCodeEnum;
use Shared\Http\Enums\StatusCodeEnum;

abstract class AbstractResponseFactory implements ResponseFactoryInterface
{
    abstract protected function response(): PsrResponseInterface;
    abstract protected function stream(string $data): StreamInterface;

    /**
     * @param mixed   $data
     * @param string $message
     *
     * @return PsrResponseInterface
     */
    public function success(
        $data = null,
        SuccessCodeEnum $code = SuccessCodeEnum::OK,
        ?string $message = null
    ): PsrResponseInterface {
        $success = $code->get();
        $result = [
            "code" => $code->value,
            "message" => $message ?? $success->message,
            "data" => $data,
        ];

        return $this->json($result, $success->statusCode);
    }

    /**
     * @param ErrorCodeEnum    $code
     * @param array $fields
     *
     * @return PsrResponseInterface
     */
    public function error(
        $fields = [],
        ErrorCodeEnum $code = ErrorCodeEnum::BAD_REQUEST,
        ?string $message = null
    ): PsrResponseInterface {
        $error = $code->get();
        $result = [
            "code" => $code->value,
            "message" => $message ?? $error->message,
            "fields" => $fields,
        ];

        return $this->json($result, $error->statusCode);
    }

    public function message(
        ErrorCodeEnum $code = ErrorCodeEnum::BAD_REQUEST,
        ?string $message = null
    ): PsrResponseInterface {
        $error = $code->get();
        $result = [
            "code" => $code->value,
            "message" => $message,
            "error" => $error->message,
        ];

        return $this->json($result, $error->statusCode);
    }

    /**
     * @param array|\Hyperf\Contract\Arrayable|\Hyperf\Contract\Jsonable $result
     * @param StatusCodeEnum $statusCode
     * @param int $options
     *
     * @return PsrResponseInterface
     */
    public function json(
        $result,
        StatusCodeEnum $statusCode = StatusCodeEnum::OK,
        $options = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    ): PsrResponseInterface {
        $data = $this->toJson($result, $options);

        return $this->response()
            ->withStatus($statusCode->value)
            ->withAddedHeader("content-type", "application/json; charset=utf-8")
            ->withBody($this->stream($data));
    }

    /**
     * @param array|\Hyperf\Contract\Arrayable|\Hyperf\Contract\Jsonable $data
     * @param int $options
     *
     * @return string
     */
    private function toJson($data, $options = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES): string
    {
        try {
            $result = json_encode($data, $options);
        } catch (\Throwable $exception) {
            throw new \RuntimeException($exception->getMessage(), $exception->getCode());
        }

        return $result;
    }
}
