<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с кешем
 */
trait HelperCacheTrait
{
    public static array $cache = [];


    /**
     * Сохраняет значение value в кеше по ключу key
     * @see ./tests/HelperCacheTrait/HelperCacheRuntimeSetTest.php
     *
     * @param int|float|string $key
     * @param mixed $value
     * @return void
     */
    public static function cacheRuntimeSet(int|float|string $key, mixed $value): void
    {
        static::$cache[$key] = $value;
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
}
