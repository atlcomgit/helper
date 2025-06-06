<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с типами
 * @mixin \Atlcom\Helper
 */
trait HelperCastTrait
{
    /**
     * Возвращает преобразование значения к целочисленному типу или null
     * @see ../../tests/HelperCastTrait/HelperCastToIntTest.php
     *
     * @param mixed $value
     * @return int|null
     */
    public static function castToInt(mixed $value): ?int
    {
        return match (true) {
            is_null($value) => null,
            is_integer($value) => $value,
            is_string($value) => (int)static::numberFromString($value),
            is_object($value) && is_callable($value) => static::castToInt($value()),

            default => (int)$value,
        };
    }


    /**
     * Возвращает преобразование значения к вещественному типу или null
     * @see ../../tests/HelperCastTrait/HelperCastToFloatTest.php
     *
     * @param mixed $value
     * @return float|null
     */
    public static function castToFloat(mixed $value): ?float
    {
        return match (true) {
            is_null($value) => null,
            is_float($value) => $value,
            is_string($value) => (float)static::numberFromString($value),
            is_object($value) && is_callable($value) => static::castToFloat($value()),

            default => (float)$value,
        };
    }


    /**
     * Возвращает преобразование значения к логическому типу или null
     * @see ../../tests/HelperCastTrait/HelperCastToBoolTest.php
     *
     * @param mixed $value
     * @return bool|null
     */
    public static function castToBool(mixed $value): ?bool
    {
        return match (true) {
            is_null($value) => null,
            is_bool($value) => $value,
            is_string($value) => (bool)static::numberFromString($value),
            is_object($value) && is_callable($value) => static::castToBool($value()),

            default => (bool)$value,
        };
    }


    /**
     * Возвращает преобразование значения к строковому типу или null
     * @see ../../tests/HelperCastTrait/HelperCastToStringTest.php
     *
     * @param mixed $value
     * @return string|null
     */
    public static function castToString(mixed $value): ?string
    {
        return match (true) {
            is_null($value) => null,
            $value === false => '0',
            is_string($value) => $value,
            is_object($value) && is_callable($value) => static::castToString($value()),
            is_array($value) => (string)json_encode($value, static::jsonFlags()),
            is_object($value) => (string)json_encode(
                    match (true) {
                        method_exists($value, 'toArray') => (array)$value->toArray(),
                        method_exists($value, 'all') => (array)$value->all(),

                        default => $value,
                    },
                static::jsonFlags(),
            ),

            default => (string)$value,
        };
    }


    /**
     * Возвращает преобразование значения к массиву или null
     * @see ../../tests/HelperCastTrait/HelperCastToArrayTest.php
     *
     * @param mixed $value
     * @return array|null
     */
    public static function castToArray(mixed $value): ?array
    {
        return match (true) {
            is_null($value) => null,
            is_array($value) => $value,
            is_string($value) && static::regexpValidateJson($value) => json_decode($value, true),
            is_string($value) && static::regexpValidateJson(trim($value, '" ')) => json_decode(trim($value, '" '), true),
            is_object($value) && is_callable($value) => static::castToArray($value()),
            is_object($value) => match (true) {
                    method_exists($value, 'toArray') => (array)$value->toArray(),
                    method_exists($value, 'all') => (array)$value->all(),

                    default => (array)$value,
                },

            default => (array)$value,
        };
    }


    /**
     * Возвращает преобразование значения к объекту или null
     * @see ../../tests/HelperCastTrait/HelperCastToObjectTest.php
     *
     * @param mixed $value
     * @return object|null
     */
    public static function castToObject(mixed $value): ?object
    {
        return match (true) {
            is_null($value) => null,
            is_string($value) && static::regexpValidateJson($value) => (object)json_decode($value),
            is_object($value) && is_callable($value) => static::castToObject($value()),
            is_object($value) => $value,

            default => (object)((array)$value),
        };
    }


    /**
     * Возвращает преобразование значения к статичной функции с результатом самого значения или null
     * @see ../../tests/HelperCastTrait/HelperCastToCallableTest.php
     *
     * @param mixed $value
     * @return callable|null
     */
    public static function castToCallable(mixed $value): ?callable
    {
        return match (true) {
            is_null($value) => null,
            is_object($value) && is_callable($value) => $value,

            default => static fn () => $value,
        };
    }


    /**
     * Возвращает преобразование значения к строке json или null
     * @see ../../tests/HelperCastTrait/HelperCastToJsonTest.php
     *
     * @param mixed $value
     * @return string|null
     */
    public static function castToJson(mixed $value): ?string
    {
        return match (true) {
            is_null($value) => null,
            is_scalar($value) => (string)json_encode($value, static::jsonFlags()),
            is_object($value) && is_callable($value) => static::castToJson($value()),
            is_object($value) => (string)json_encode(
                    match (true) {
                        method_exists($value, 'toArray') => (array)$value->toArray(),
                        method_exists($value, 'all') => (array)$value->all(),

                        default => $value,
                    },
                static::jsonFlags(),
            ),

            default => (string)json_encode($value, static::jsonFlags()),
        };
    }
}
