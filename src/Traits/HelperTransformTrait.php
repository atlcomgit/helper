<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use BackedEnum;

/**
 * Трейт для работы с трансформацией
 */
trait HelperTransformTrait
{
    /**
     * Возвращает массив из значения
     * @see ./tests/HelperTransformTrait/HelperTransformToArrayTest.php
     *
     * @param mixed $value
     * @return array
     */
    public static function transformToArray(mixed $value): array
    {
        return $value = match (true) {
            is_null($value) => [],
            $value instanceof BackedEnum => static::enumToArray($value),
            is_object($value) && method_exists($value, 'toArray') => $value->toArray(),
            is_array($value) => $value,
            is_scalar($value) => [$value],
            is_callable($value) => static::transformToArray($value()),

            default => (array)$value,
        };
    }
}
