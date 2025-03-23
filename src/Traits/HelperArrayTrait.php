<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Closure;

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


    /**
     * Возвращает массив с маппингом ключей
     *
     * @param array|object $value
     * @param int|float|string|array|object $from
     * @param int|float|string|array|object|null|null $to
     * @param string|null $rootKey
     * @return array
     */
    public static function arrayMappingKeys(
        array|object $value,
        int|float|string|array|object $from,
        int|float|string|array|object|null $to = null,
        ?string $rootKey = null,
    ): array {
        $value = match (true) {
            is_object($value) && method_exists($value, 'toArray') => $value->toArray(),
            is_array($value) => $value,
            default => (array)$value,

        };
        $from = match (true) {
            is_null($from) => [],
            is_object($from) && method_exists($from, 'toArray') => $from->toArray(),
            is_array($from) => $from,
            default => (array)$from,

        };

        if (!$from) {
            return $value;
        }

        $to = match (true) {
            is_null($to) => [],
            is_object($to) && method_exists($to, 'toArray') => $to->toArray(),
            is_array($to) => $to,
            default => (array)$to,
        };


        $result = [];
        $mappings = [];
        foreach ($from as $keyFrom => $keyTo) {
            if (is_integer($keyFrom) && isset($to[$keyFrom])) {
                $keyTo = $to[$keyFrom];
                $keyFrom = $from[$keyFrom];
            }

            (is_null($keyTo) || $keyFrom === $keyTo) ?: $mappings[$keyFrom] = $keyTo;
        }
        $mappingKeys = array_keys($mappings);

        foreach ($value as $key => $v) {
            $keyCurrent = !is_null($rootKey) ? "{$rootKey}.{$key}" : (string)$key;
            !(is_array($v) || is_object($v)) ?: $v = static::arrayMappingKeys($v, $mappings, null, $keyCurrent);

            if (isset($mappings[$keyCurrent])) {
                $result[$mappings[$keyCurrent]] = $v;
            } else if ($keySearch = static::stringSearchAny($keyCurrent, $mappingKeys)) {
                $result[$mappings[$keySearch]] = $v;
            } else {
                $result[$key] = $v;
            }

            unset($value[$key]);
        }

        return $result;
    }
}
