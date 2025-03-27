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
     * @see ./tests/HelperArrayTrait/HelperArrayMappingKeysTest.php
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
        $value = static::transformToArray($value);
        $from = static::transformToArray($from);

        if (!$from) {
            return $value;
        }

        $to = static::transformToArray($to);

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
                $result[$mappings[static::arrayFirst($keySearch)]] = $v;
            } else {
                $result[$key] = $v;
            }

            unset($value[$key]);
        }

        return $result;
    }


    /**
     * Возвращает массив найденных ключей в искомом массиве
     * @see ./tests/HelperArrayTrait/HelperArraySearchKeysTest.php
     *
     * @param array|object $value
     * @param mixed ...$searches
     * @return array
     */
    public static function arraySearchKeys(array|object $value, mixed ...$searches): array
    {
        $result = [];
        $value = static::transformToArray($value);
        $searches = static::transformToArray($searches);

        foreach ($value as $key => $v) {
            if (is_array($v) || is_object($v)) {

                $subValue = [];
                foreach ($v as $key2 => $v2) {
                    $subValue["{$key}.{$key2}"] = $v2;
                }

                $result = [...$result, ...static::arraySearchKeys($subValue, ...$searches)];

            } else {
                foreach ($searches as $search) {
                    if (is_array($search) || is_object($search)) {
                        $result = [...$result, ...static::arraySearchKeys([$key => $v], ...$search)];

                    } else if (
                        !is_null($search)
                        && (($searchString = (string)$search) || $searchString === '0')
                        && (
                            $searchString === '*'
                            || $key === $search
                            || (static::regexpValidatePattern($searchString) && preg_match($searchString, $key))
                            || (mb_strpos($searchString, '*') !== false && fnmatch($searchString, $key))
                        )
                    ) {
                        $result[$key] = $v;
                    }
                }
            }

        }

        return $result;
    }


    /**
     * Возвращает массив найденных значений в искомом массиве
     * @see ./tests/HelperArrayTrait/HelperArraySearchValuesTest.php
     *
     * @param array|object $value
     * @param mixed ...$searches
     * @return array
     */
    public static function arraySearchValues(array|object $value, mixed ...$searches): array
    {
        $result = [];
        $value = static::transformToArray($value);
        $searches = static::transformToArray($searches);

        foreach ($value as $key => $v) {
            if (is_array($v) || is_object($v)) {
                $subResult = static::arraySearchValues($v, ...$searches);
                !$subResult ?: $result[$key] = $subResult;

            } else {
                foreach ($searches as $search) {
                    if (is_array($search) || is_object($search)) {
                        $subValues = (is_array($v) || is_object($v))
                            ? static::arraySearchValues($v, ...$search)
                            : static::arraySearchValues([$key => $v], ...$search);
                        !$subValues ?: $result = [...$result, ...$subValues];

                    } else if (
                        !is_null($search)
                        && (($searchString = (string)$search) || $searchString === '0')
                        && (
                            $searchString === '*'
                            || $v === $search
                            || (static::regexpValidatePattern($searchString) && preg_match($searchString, $v))
                            || (mb_strpos($searchString, '*') !== false && fnmatch($searchString, $v))
                        )
                    ) {
                        $result[$key] = $v;
                    }
                }
            }
        }

        return $result;
    }


    /**
     * Возвращает массив найденных ключей и значений в искомом массиве
     * @see ./tests/HelperArrayTrait/HelperArraySearchKeysAndValuesTest.php
     *
     * @param array|object $value
     * @param int|float|bool|string|array|object $keys
     * @param int|float|bool|string|array|object $values
     * @return array
     */
    public static function arraySearchKeysAndValues(
        array|object $value,
        int|float|bool|string|array|object $keys,
        int|float|bool|string|array|object $values,
    ): array {
        return static::arraySearchValues(static::arraySearchKeys($value, $keys), $values);
    }


    /**
     * Возвращает значение из массива по имению ключа
     * @see ./tests/HelperArrayTrait/HelperArrayGetTest.php
     *
     * @param array|object $value
     * @param int|float|string|bool|null $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function arrayGet(array|object $value, int|float|string|bool|null $key, mixed $default = null): mixed
    {
        if (is_null($key)) {
            return null;
        }

        $value = static::transformToArray($value);

        if (!isset($value[$key]) && mb_strpos((string)$key, '.') !== false) {
            foreach (explode('.', $key) as $partKey) {
                $value = is_array($value)
                    ? (array_key_exists($partKey, $value) ? $value[$partKey] : $default)
                    : $default;
            }

        } else {
            $value = array_key_exists($key, $value) ? $value[$key] : $default;
        }

        return $value;
    }


    /**
     * Возвращает значение первого элемента массива
     * @see ./tests/HelperArrayTrait/HelperArrayFirstTest.php
     *
     * @param array|object $value
     * @return mixed
     */
    public static function arrayFirst(array|object $value): mixed
    {
        $value = static::transformToArray($value);

        return !is_null($key = array_key_first($value)) ? ($value[$key] ?? null) : null;
    }


    /**
     * Возвращает значение последнего элемента массива
     * @see ./tests/HelperArrayTrait/HelperArrayLastTest.php
     *
     * @param array|object $value
     * @return mixed
     */
    public static function arrayLast(array|object $value): mixed
    {
        $value = static::transformToArray($value);

        return !is_null($key = array_key_last($value)) ? ($value[$key] ?? null) : null;
    }


    //?!? readme
    /**
     * Возвращает одномерный массив из многомерного
     * @see ./tests/HelperArrayTrait/HelperArrayDottedTest.php
     *
     * @param array|object $value
     * @return array
     */
    public static function arrayDotted(array|object $value): array
    {
        return static::arraySearchKeys(static::transformToArray($value), ['*']);
    }


    //?!? readme
    /**
     * Возвращает многомерный массив из одномерного
     * @see ./tests/HelperArrayTrait/HelperArrayNestedTest.php
     *
     * @param array $value
     * @return array
     */
    public static function arrayNested(array $value): array
    {
        $result = [];

        foreach ($value as $key => $v) {
            if (is_string($key) && mb_strpos($key, '.') !== false) {
                $keys = static::stringSplit($key, '.');
                $current = &$result;

                foreach (array_slice($keys, 0, -1) as $subKey) {
                    if (!isset($current[$subKey]) || !is_array($current[$subKey])) {
                        $current[$subKey] = [];
                    }

                    $current = &$current[$subKey];
                }

                $lastKey = end($keys);
                $current[$lastKey] = $v;

                unset($current);
            } else {
                $result[$key] = $v;
            }

            unset($value[$key]);
        }

        return $result;
    }
}
