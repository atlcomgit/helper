<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Throwable;

/**
 * Трейт для работы с exception
 */
trait HelperExceptionTrait
{
    /**
     * Возвращает исключение в виде строки json
     * @see ./tests/HelperExceptionTrait/HelperExceptionToStringTest.php
     *
     * @param Throwable $value
     * @return string
     */
    public static function exceptionToString(Throwable $value): string
    {
        $exceptionArray = [
            'code' => $value->getCode(),
            'message' => $value->getMessage(),
            'exception' => static::pathClassName($value::class),
            'file' => static::stringConcat(
                ':',
                trim(static::stringReplace($value->getFile(), static::pathRoot(), ''), '/'),
                $value->getLine(),
            ),
            'trace' => static::arrayExcludeTraceVendor($value->getTrace()),
        ];

        return json_encode(
            $exceptionArray,
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        );
    }
}
