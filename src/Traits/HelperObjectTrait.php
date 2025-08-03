<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Closure;

/**
 * Трейт для работы с объектами
 * @mixin \Atlcom\Helper
 */
trait HelperObjectTrait
{
    /**
     * Возвращает преобразованный объект в массив рекурсивно или null
     * @see ../../tests/HelperObjectTrait/HelperObjectToArrayRecursiveTest.php
     *
     * @param array|object|null $value
     * @return array|null
     */
    public static function objectToArrayRecursive(array|object|null $value): ?array
    {
        return match (true) {
            is_null($value) => $value,

            $value instanceof Closure => match (($res = $value()) || true) {
                    is_array($res) => static::objectToArrayRecursive($res),
                    is_object($res) => match (true) {
                            method_exists($res, 'toArray') => static::objectToArrayRecursive($res->toArray()),
                            method_exists($res, 'all') => static::objectToArrayRecursive($res->all()),

                            default => static::objectToArrayRecursive((array)$res)
                        },

                    default => $res,
                },

            is_object($value) => match (true) {
                    method_exists($value, 'toArray') => static::objectToArrayRecursive($value->toArray()),
                    method_exists($value, 'all') => static::objectToArrayRecursive($value->all()),

                    default => static::objectToArrayRecursive((array)$value)
                },

            is_array($value) => (function (array $arr) {
                    foreach ($arr as $key => $item) {
                        $arr[$key] = match (true) {
                            is_array($item) => static::objectToArrayRecursive($item),
                            $item instanceof Closure => match (($res = $item()) || true) {
                                    is_array($res) => static::objectToArrayRecursive($res),
                                    is_object($res) => match (true) {
                                            method_exists($res, 'toArray') => static::objectToArrayRecursive($res->toArray()),
                                            method_exists($res, 'all') => static::objectToArrayRecursive($res->all()),
                                            default => static::objectToArrayRecursive((array)$res),
                                        },
                                    default => $res,
                                },
                            is_object($item) => match (true) {
                                    method_exists($item, 'toArray') => static::objectToArrayRecursive($item->toArray()),
                                    method_exists($item, 'all') => static::objectToArrayRecursive($item->all()),
                                    default => static::objectToArrayRecursive((array)$item),
                                },
                            default => $item,
                        };
                    }

                    return $arr;
                })($value),

            default => $value,
        };
    }
}
