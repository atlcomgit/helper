<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Throwable;

/**
 * Трейт для работы с exception
 * @mixin \Atlcom\Helper
 */
trait HelperExceptionTrait
{
    /**
     * Возвращает исключение в виде строки json
     * @see ../../tests/HelperExceptionTrait/HelperExceptionToStringTest.php
     *
     * @param ?Throwable $value
     * @return string
     */
    public static function exceptionToString(?Throwable $value): string
    {
        if (is_null($value)) {
            return '';
        }

        $exceptionArray = [
            'code' => $value->getCode(),
            'message' => $value->getMessage(),
            'exception' => static::pathClassName($value::class),
            'file' => static::stringConcat(
                ':',
                trim(static::stringReplace($value->getFile(), static::pathRoot(), '', useRegexp: false), '/'),
                $value->getLine(),
            ),
            'trace' => static::arrayExcludeTraceVendor($value->getTrace()),
        ];

        return json_encode($exceptionArray, JSON_THROW_ON_ERROR | static::jsonFlags());
    }


    /**
     * Возвращает исключение в виде массива
     * @see ../../tests/HelperExceptionTrait/HelperExceptionToArrayTest.php
     *
     * @param ?Throwable $value
     * @return array
     */
    public static function exceptionToArray(?Throwable $value): array
    {
        if (is_null($value)) {
            return [];
        }

        return [
            'code' => $value->getCode(),
            'message' => $value->getMessage(),
            'exception' => static::pathClassName($value::class),
            'file' => static::stringConcat(
                ':',
                trim(static::stringReplace($value->getFile(), static::pathRoot(), '', useRegexp: false), '/'),
                $value->getLine(),
            ),
            'trace' => static::arrayExcludeTraceVendor($value->getTrace()),
            ...($value::class === 'Illuminate\Database\QueryException'
                ? [
                    'sql' => static::stringDeleteMultiples(static::stringReplace(
                        match (true) {
                            method_exists($value, 'getRawSql') => $value->getRawSql(),
                            method_exists($value, 'getSql') && method_exists($value, 'getBindings')
                            => static::sqlBindings($value->getSql(), $value->getBindings()),

                            default => '',
                        },
                        ['"' => ''],
                    ), ' '),
                ]
                : []
            ),
        ];
    }
}
