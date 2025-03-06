<?php

namespace Shared\Support;

use ReflectionClass;

class ArrayToObject
{
    /**
     * @template T of object
     *
     * @param class-string<T> $className
     *
     * @return ?T
     *
     * @psalm-return ($default is null ? T|null : T)
     */
    public static function make(string $className, array $inputs): object
    {
        $arguments = self::extract($className, $inputs);
        return new $className(...$arguments);
    }

    public static function extract(string $className, array $inputs): array
    {
        $reflectionClass = new ReflectionClass($className);

        $parameters = $reflectionClass->getConstructor()?->getParameters() ?? [];

        $arguments = [];
        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            $type = $parameter->getType()->getName();

            $value = $inputs[$name] ?? null;
            if (empty($value) && $parameter->isDefaultValueAvailable()) {
                $value = $parameter->getDefaultValue();
            }

            if (is_string($type) && strpos($type, "\\") !== false) {
                if (str_ends_with($type, "Enum")) {
                    $arguments[$name] = is_string($value) ? $type::tryFrom($value) : $value;
                    continue;
                }

                $arguments[$name] = new $type($value);
                continue;
            }

            $arguments[$name] = $value;
        }

        return $arguments;
    }
}
