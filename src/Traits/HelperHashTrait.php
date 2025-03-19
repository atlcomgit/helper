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
     *
     * @param mixed $value
     * @param string|null $prefix
     * @param string|null $suffix
     * @return string
     */
    public static function hash(mixed $value, ?string $prefix = null, ?string $suffix = null): string
    {
        is_scalar($value) ?: $value = json_encode($value);

        return hash('xxh128', (string)$prefix . $value . (string)$suffix);
    }
}
