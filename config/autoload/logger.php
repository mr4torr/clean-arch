<?php

declare(strict_types=1);

use Monolog\Level;

use function Hyperf\Support\env;

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

return [
    "default" => [
        "handler" => [
            // "class" => Monolog\Handler\RotatingFileHandler::class,
            "class" => Monolog\Handler\StreamHandler::class,
            "constructor" => [
                "stream" => BASE_PATH . "/runtime/logs/" . env("APP_ENV", "dev") . ".log",
                "level" => env("APP_ENV") === "prod" ? Monolog\Logger::WARNING : Monolog\Logger::DEBUG,
            ],
        ],
        "formatter" => [
            "class" =>
                env("APP_ENV") === "prod"
                    ? Monolog\Formatter\JsonFormatter::class
                    : Monolog\Formatter\LineFormatter::class,
            "constructor" => [
                "format" => "[%datetime%]: %channel%.%level_name%: %message%\n -> %context%\n -> %extra%\n",
                "includeStacktraces" => env("APP_ENV") === "dev",
                "dateFormat" => "Y-m-d H:i:s",
                "allowInlineLineBreaks" => true,
            ],
        ],
        "PsrLogMessageProcessor" => [
            "class" => Monolog\Processor\PsrLogMessageProcessor::class,
        ],
    ],
];
