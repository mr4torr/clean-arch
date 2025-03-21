<?php

declare(strict_types=1);

namespace Core\Application\Exception\Handler;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Shared\Exception\AppExceptionInterface;
use Shared\Exception\BusinessException;
use Shared\Exception\FieldException;
use Shared\Http\Enums\CodeEnumInterface;
use Shared\Http\Enums\ErrorCodeEnum;
use Throwable;

use function Hyperf\Support\env;

class HttpExceptionHandler extends ExceptionHandler implements AppExceptionInterface
{
    public function __construct(
        protected StdoutLoggerInterface $loggerStdout,
        protected LoggerInterface $logger,
        protected FormatterInterface $formatter
    ) {
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $message = $this->formatter->format($throwable);

        $this->stopPropagation();

        $this->logger->error($message);
        $this->loggerStdout->error($message);

        switch ($throwable::class) {
            case FieldException::class:
                $code = ErrorCodeEnum::VALIDATION_FIELDS->get();
                $statusCode = $throwable->getCode();

                $fields = array_map(function ($field) {
                    if (is_object($field) && $field instanceof CodeEnumInterface) {
                        $field = [$field->get()->message];
                    }

                    if (! is_array($field)) {
                        $field = [$field];
                    }

                    return $field;
                }, $throwable->fields);

                $content = [
                    'code' => $code->statusCode->value,
                    'message' => $code->message,
                    'fields' => $fields,
                ];
                break;
            case BusinessException::class:
                $errorCode = $throwable->errorCode->get();
                $statusCode = $throwable->getCode();
                $content = [
                    'code' => $errorCode->statusCode->value,
                    'message' => $errorCode->message,
                    'data' => $throwable->getMessage(),
                ];
                break;
            default:
                $statusCode = 500;
                $content
                    = env('APP_ENV') === 'dev'
                        ? [
                            'message' => sprintf(
                                '%s in %s:%s',
                                str_replace(["\n", "\r"], ' ', $throwable->getMessage()),
                                $throwable->getFile(),
                                $throwable->getLine()
                            ),
                            'trace' => $throwable->getTrace(),
                        ]
                        : $throwable->getMessage();
                break;
        }

        return $response
            ->withStatus($statusCode)
            ->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withBody(new SwooleStream(json_encode($content)));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
