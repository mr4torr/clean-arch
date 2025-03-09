<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Application\Listener;

use Hyperf\Collection\Arr;
use Hyperf\Context\ApplicationContext;
use Hyperf\Database\Events\QueryExecuted;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

#[Listener]
class DbQueryExecutedListener implements ListenerInterface
{
    private static LoggerInterface $logger;

    public function listen(): array
    {
        return [QueryExecuted::class];
    }

    /**
     * @param QueryExecuted $event
     */
    public function process(object $event): void
    {
        if ($event instanceof QueryExecuted) {
            $sql = $event->sql;
            if (!Arr::isAssoc($event->bindings)) {
                $position = 0;
                foreach ($event->bindings as $value) {
                    $position = strpos($sql, "?", $position);
                    if ($position === false) {
                        break;
                    }
                    $value = "'{$value}'";
                    $sql = substr_replace($sql, $value, $position, 1);
                    $position += strlen($value);
                }
            }

            self::logger()->info(sprintf("[%s] %s", $event->time, $sql));
        }
    }

    private static function logger(): LoggerInterface
    {
        if (!isset(static::$logger)) {
            self::$logger = ApplicationContext::getContainer()->get(LoggerFactory::class)->get("sql");
        }

        return self::$logger;
    }
}
