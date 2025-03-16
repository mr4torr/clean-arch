<?php

declare(strict_types=1);

namespace Shared\Either;

/*
 * Precisa instalar o pacote illuminate/collections
 * composer require illuminate/collections
 */
use Exception;
use Illuminate\Support\Collection;

/**
 * @template TKey of array-key
 * @template-covariant TValue
 */
class Either
{
    private Collection $left;

    private Collection $right;

    /**
     * @param null|iterable<TKey, TValue> $left
     * @param null|iterable<TKey, TValue> $right
     */
    private function __construct(array $left = [], array $right = [])
    {
        // if (isset($this->left) && !$this->left->isEmpty()) {
        //     $left = [...$this->left->toArray(), ...$left];
        // }

        // if (isset($this->right) && !$this->right->isEmpty()) {
        //     $right = [...$this->right->toArray(), ...$right];
        // }

        $this->left = Collection::make($left);
        $this->right = Collection::make($right);
    }

    /**
     * @param null|iterable<TKey, TValue> $values
     */
    public static function left(...$values): Either
    {
        return new self($values, []);
    }

    /**
     * @param null|iterable<TKey, TValue> $values
     */
    public static function right(...$values): Either
    {
        return new self([], $values);
    }

    final public static function tryCatch(callable $f): Either
    {
        try {
            return self::right($f());
        } catch (Exception $e) {
            return self::left($e);
        }
    }

    /**
     * @param null|iterable<TKey, TValue> $values
     */
    public function addRight(...$values): Either
    {
        return $this->right([...$this->right->toArray(), ...$values]);
    }

    /**
     * @param null|iterable<TKey, TValue> $values
     */
    public function addLeft(...$values): Either
    {
        return $this->left([...$this->left->toArray(), ...$values]);
    }

    public function isLeft(): bool
    {
        return $this->left->isNotEmpty();
    }

    public function isRight(): bool
    {
        return $this->left->isEmpty() && $this->right->isNotEmpty();
    }

    /**
     * @return Collection<TKey, TValue>
     */
    public function values(): Collection
    {
        return $this->isRight() ? $this->right : $this->left;
    }

    /**
     * @return TValue
     */
    public function current()
    {
        $values = $this->values();
        if ($values->count() > 1) {
            throw new Exception('Either::value() called on a Either with multiple values');
        }

        return $this->values()->last();
    }
}
