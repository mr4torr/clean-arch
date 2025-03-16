<?php

declare(strict_types=1);

use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;

use function Hyperf\Support\env;

/*
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

return [
    'default' => [
        'handler' => [
            // "class" => Monolog\Handler\RotatingFileHandler::class,
            'class' => StreamHandler::class,
            'constructor' => [
                'stream' => BASE_PATH . '/runtime/logs/' . env('APP_ENV', 'dev') . '.log',
                'level' => env('APP_ENV') === 'prod' ? Logger::WARNING : Logger::DEBUG,
            ],
        ],
        'formatter' => [
            'class' => env('APP_ENV') === 'prod'
                    ? JsonFormatter::class
                    : LineFormatter::class,
            'constructor' => [
                'format' => "[%datetime%]: %channel%.%level_name%: %message%\n -> %context%\n -> %extra%\n",
                'includeStacktraces' => env('APP_ENV') === 'dev',
                'dateFormat' => 'Y-m-d H:i:s',
                'allowInlineLineBreaks' => true,
            ],
        ],
        'PsrLogMessageProcessor' => [
            'class' => PsrLogMessageProcessor::class,
        ],
    ],
];
