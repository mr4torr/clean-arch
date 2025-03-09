<?php

declare(strict_types=1);

use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Log\LogLevel;

use function Hyperf\Support\env;

return [
    'app_timezone' => env('TIMEZONE', 'UTC'),
    'app_name' => env('APP_NAME', 'Tourops'),
    'app_url' => env('APP_URL', 'http://localhost'),
    // 'app_url_api' => env('APP_URL_API', 'http://localhost:9501'),
    'app_env' => env('APP_ENV', 'dev'),
    'secret' => env('SECRET', 'base64:3J6Q6Z3Z'),
    'scan_cacheable' => env('SCAN_CACHEABLE', false),
    StdoutLoggerInterface::class => [
        "log_level" => match (env("APP_ENV")) {
            "prod" => [LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::EMERGENCY, LogLevel::ERROR],
            "testing" => [LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::EMERGENCY, LogLevel::ERROR],
            default => [
                LogLevel::ALERT,
                LogLevel::CRITICAL,
                // LogLevel::DEBUG,
                LogLevel::EMERGENCY,
                LogLevel::ERROR,
                LogLevel::INFO,
                LogLevel::NOTICE,
                LogLevel::WARNING,
            ],
        },
    ],
];
