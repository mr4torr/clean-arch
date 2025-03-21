<?php

declare(strict_types=1);

namespace Shared\Support;

use ArrayIterator;
use BackedEnum;
use Closure;
use Countable;
use Exception;
use InvalidArgumentException;
use IteratorAggregate;
use Stringable;
use TypeError;
use ValueError;

use const FILTER_CALLBACK;
use const FILTER_DEFAULT;
use const FILTER_NULL_ON_FAILURE;
use const FILTER_REQUIRE_ARRAY;
use const FILTER_REQUIRE_SCALAR;
use const FILTER_VALIDATE_BOOL;
use const FILTER_VALIDATE_INT;

/**
 * Summary of ParameterBag.
 * @implements \IteratorAggregate<mixed>
 * @implements \Countable<mixed>
 */
class ParameterBag implements IteratorAggregate, Countable
{
    public function __construct(protected array $parameters = [])
    {
    }

    /**
     * Summary of all.
     * @param mixed $key
     * @throws Exception
     */
    public function all(?string $key = null): array
    {
        if ($key === null) {
            return $this->parameters;
        }

        if (! \is_array($value = $this->parameters[$key] ?? [])) {
            throw new Exception(
                \sprintf(
                    'Unexpected value for parameter "%s": expecting "array", got "%s".',
                    $key,
                    get_debug_type($value)
                )
            );
        }

        return $value;
    }

    /**
     * Summary of keys.
     */
    public function keys(): array
    {
        return array_keys($this->parameters);
    }

    public function replace(array $parameters = []): void
    {
        $this->parameters = $parameters;
    }

    /**
     * Summary of add.
     */
    public function add(array $parameters = []): void
    {
        $this->parameters = array_replace($this->parameters, $parameters);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return \array_key_exists($key, $this->parameters) ? $this->parameters[$key] : $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->parameters[$key] = $value;
    }

    /**
     * Returns true if the parameter is defined.
     */
    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->parameters);
    }

    /**
     * Removes a parameter.
     */
    public function remove(string $key): void
    {
        unset($this->parameters[$key]);
    }

    /**
     * Returns the alphabetic characters of the parameter value.
     */
    public function getAlpha(string $key, string $default = ''): string
    {
        return preg_replace('/[^[:alpha:]]/', '', $this->getString($key, $default));
    }

    /**
     * Returns the alphabetic characters and digits of the parameter value.
     */
    public function getAlnum(string $key, string $default = ''): string
    {
        return preg_replace('/[^[:alnum:]]/', '', $this->getString($key, $default));
    }

    /**
     * Returns the digits of the parameter value.
     */
    public function getDigits(string $key, string $default = ''): string
    {
        return preg_replace('/[^[:digit:]]/', '', $this->getString($key, $default));
    }

    /**
     * Returns the parameter as string.
     */
    public function getString(string $key, string $default = ''): string
    {
        $value = $this->get($key, $default);
        if (! \is_scalar($value) && ! $value instanceof Stringable) {
            throw new Exception(\sprintf('Parameter value "%s" cannot be converted to "string".', $key));
        }

        return (string) $value;
    }

    /**
     * Returns the parameter value converted to integer.
     */
    public function getInt(string $key, int $default = 0): int
    {
        return $this->filter($key, $default, FILTER_VALIDATE_INT, ['flags' => FILTER_REQUIRE_SCALAR]);
    }

    /**
     * Returns the parameter value converted to boolean.
     */
    public function getBoolean(string $key, bool $default = false): bool
    {
        return $this->filter($key, $default, FILTER_VALIDATE_BOOL, ['flags' => FILTER_REQUIRE_SCALAR]);
    }

    /**
     * Returns the parameter value converted to an enum.
     *
     * @template T of \BackedEnum
     *
     * @param class-string<T> $class
     * @param ?T $default
     *
     * @return ?T
     *
     * @psalm-return ($default is null ? T|null : T)
     */
    public function getEnum(string $key, string $class, ?BackedEnum $default = null): ?BackedEnum
    {
        $value = $this->get($key);

        if ($value === null) {
            return $default;
        }

        try {
            return $class::from($value);
        } catch (TypeError|ValueError $e) {
            throw new Exception(
                \sprintf('Parameter "%s" cannot be converted to enum: %s.', $key, $e->getMessage()),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Filter key.
     *
     * @param int $filter FILTER_* constant
     * @param array{flags?: int, options?: array}|int $options Flags from FILTER_* constants
     *
     * @see https://php.net/filter-var
     */
    public function filter(
        string $key,
        mixed $default = null,
        int $filter = FILTER_DEFAULT,
        mixed $options = []
    ): mixed {
        $value = $this->get($key, $default);

        // Always turn $options into an array - this allows filter_var option shortcuts.
        if (! \is_array($options) && $options) {
            $options = ['flags' => $options];
        }

        // Add a convenience check for arrays.
        if (\is_array($value) && ! isset($options['flags'])) {
            $options['flags'] = FILTER_REQUIRE_ARRAY;
        }

        if (\is_object($value) && ! $value instanceof Stringable) {
            throw new Exception(\sprintf('Parameter value "%s" cannot be filtered.', $key));
        }

        if (FILTER_CALLBACK & $filter && ! (($options['options'] ?? null) instanceof Closure)) {
            throw new InvalidArgumentException(
                \sprintf(
                    'A Closure must be passed to "%s()" when FILTER_CALLBACK is used, "%s" given.',
                    __METHOD__,
                    get_debug_type($options['options'] ?? null)
                )
            );
        }

        $options['flags'] ??= 0;
        $nullOnFailure = $options['flags'] & FILTER_NULL_ON_FAILURE;
        $options['flags'] |= FILTER_NULL_ON_FAILURE;

        $value = filter_var($value, $filter, $options);

        if ($value !== null || $nullOnFailure) {
            return $value;
        }

        throw new Exception(
            \sprintf('Parameter value "%s" is invalid and flag "FILTER_NULL_ON_FAILURE" was not set.', $key)
        );
    }

    /**
     * Returns an iterator for parameters.
     *
     * @return ArrayIterator<string, mixed>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->parameters);
    }

    /**
     * Returns the number of parameters.
     */
    public function count(): int
    {
        return \count($this->parameters);
    }
}
