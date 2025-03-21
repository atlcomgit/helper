<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с массивами
 */
trait HelperArrayTrait
{
    public static array $ignoreFiles = ['/vendor/'];
    public static array $ignoreClasses = ['Illuminate'];


    /**
     * Исключает из массива трассировки пакетные ошибки
     * @see ./tests/HelperArrayTrait/HelperArrayExcludeTraceVendorTest.php
     *
     * @param array $trace
     * @return array
     */
    public static function arrayExcludeTraceVendor(array $value, ?string $basePath = null): array
    {
        $basePath ??= static::pathRoot();

        $resultTrace = [];
        foreach ($value as $item) {
            if (
                !static::stringSearchAny($item['file'] ?? '', static::$ignoreFiles)
                &&
                !static::stringSearchAny($item['class'] ?? '', static::$ignoreClasses)
            ) {
                $file = trim(static::stringReplace($item['file'] ?? '', $basePath, ''), '/');
                $resultTrace[] = [
                    'file' => static::stringConcat(':', $file, $item['line'] ?? ''),
                    'func' => static::stringConcat(
                        $item['type'] ?? '',
                        static::pathClassName($item['class'] ?? ''),
                        $item['function'] . '()',
                    ),
                ];
            }
        }

        return $resultTrace;
    }
}
