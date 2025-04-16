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
     * @see ../../tests/HelperCacheTrait/HelperCacheRuntimeExistsTest.php
     *
     * @param int|float|string|null $key
     * @return bool
     */
    public static function cacheRuntimeExists(int|float|string|null $key): bool
    {
        return array_key_exists($key, static::$cache);
    }


    /**
     * Сохраняет значение value в кеше по ключу key и возвращает значение
     * @see ../../tests/HelperCacheTrait/HelperCacheRuntimeSetTest.php
     *
     * @param int|float|string|null $key
     * @param mixed $value
     * @return mixed
     */
    public static function cacheRuntimeSet(int|float|string|null $key, mixed $value): mixed
    {
        return static::$cache[$key ?? ''] = $value;
    }


    /**
     * Возвращает значение из кеша по ключу key или возвращает значение по умолчанию default
     * @see ../../tests/HelperCacheTrait/HelperCacheRuntimeGetTest.php
     *
     * @param int|float|string|null $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function cacheRuntimeGet(int|float|string|null $key, mixed $default = null): mixed
    {
        return static::$cache[$key ?? ''] ?? $default;
    }


    /**
     * Сохраняет значение value в кеше по ключу key или возвращает значение при его наличии
     * Если cacheEnabled выключен, то кеш не используется
     * @see ../../tests/HelperCacheTrait/HelperCacheRuntimeTest.php
     *
     * @param int|float|string|null $key
     * @param Closure $callback
     * @param bool $cacheEnabled
     * @return mixed
     */
    public static function cacheRuntime(int|float|string|null $key, Closure $callback, bool $cacheEnabled = true): mixed
    {
        if (!$cacheEnabled) {
            return $callback();
        }

        if (static::cacheRuntimeExists($key)) {
            return static::cacheRuntimeGet($key);
        }

        return static::cacheRuntimeSet($key, $callback());
    }


    /**
     * Удаляет значение из кеша по ключу key
     * @see ../../tests/HelperCacheTrait/HelperCacheRuntimeDeleteTest.php
     *
     * @param int|float|string|null $key
     * @return void
     */
    public static function cacheRuntimeDelete(int|float|string|null $key): void
    {
        unset(static::$cache[$key ?? '']);
    }


    /**
     * Очищает весь кеш
     * @see ../../tests/HelperCacheTrait/HelperCacheRuntimeClearTest.php
     *
     * @return void
     */
    public static function cacheRuntimeClear(): void
    {
        static::$cache = [];
    }
}
