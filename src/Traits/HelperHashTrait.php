<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с хешем
 */
trait HelperHashTrait
{
    /**
     * Возвращает хеш значения
     * @see ./tests/HelperHashTrait/HelperHashXxh128Test.php
     *
     * @param mixed $value
     * @param string|null $prefix
     * @param string|null $suffix
     * @return string
     */
    //?!? 
    public static function hashXxh128(mixed $value, ?string $prefix = null, ?string $suffix = null): string
    {
        is_scalar($value) ?: $value = json_encode($value);

        return hash('xxh128', (string)$prefix . $value . (string)$suffix);
    }
}
