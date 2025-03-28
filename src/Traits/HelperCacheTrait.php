<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Closure;

/**
 * Трейт для работы с кешем
 */
trait HelperCacheTrait
{
    public static array $cache = [];


    /**
     * Проверяет существование ключа в кеше и возвращает true/false
     * @see ./tests/HelperCacheTrait/HelperCacheRuntimeExistsTest.php
     *
     * @param int|float|string $key
     * @return bool
     */
    public static function cacheRuntimeExists(int|float|string $key): bool
    {
        return array_key_exists($key, static::$cache);
    }


    /**
     * Сохраняет значение value в кеше по ключу key и возвращает значение
     * @see ./tests/HelperCacheTrait/HelperCacheRuntimeSetTest.php
     *
     * @param int|float|string $key
     * @param mixed $value
     * @return mixed
     */
    public static function cacheRuntimeSet(int|float|string $key, mixed $value): mixed
    {
        return static::$cache[$key] = $value;
    }


    /**
     * Возвращает значение из кеша по ключу key или возвращает значение по умолчанию default
     * @see ./tests/HelperCacheTrait/HelperCacheRuntimeGetTest.php
     *
     * @param int|float|string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function cacheRuntimeGet(int|float|string $key, mixed $default = null): mixed
    {
        return static::$cache[$key] ?? $default;
    }


    /**
     * Сохраняет значение value в кеше по ключу key или возвращает значение при его наличии
     * @see ./tests/HelperCacheTrait/HelperCacheRuntimeTest.php
     *
     * @param int|float|string $key
     * @param Closure $callback
     * @return mixed
     */
    public static function cacheRuntime(int|float|string $key, Closure $callback): mixed
    {
        if (static::cacheRuntimeExists($key)) {
            return static::cacheRuntimeGet($key);
        }

        return static::cacheRuntimeSet($key, $callback());
    }
}
