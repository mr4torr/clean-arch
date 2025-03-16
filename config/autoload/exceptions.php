<?php

declare(strict_types=1);

use Core\Application\Exception\Handler\HttpExceptionHandler;

/*
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'handler' => [
        'http' => [HttpExceptionHandler::class],
    ],
];
