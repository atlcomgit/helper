<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с массивами
 * @mixin \Atlcom\Helper
 */
trait HelperArrayTrait
{
    public static string $dotDelimiter = '.';
    public static array $ignoreFiles = ['/vendor/'];
    public static array $ignoreClasses = ['Illuminate'];


    /**
     * Исключает из массива трассировки пакетные ошибки
     * @see ../../tests/HelperArrayTrait/HelperArrayExcludeTraceVendorTest.php
     *
     * @param array|null $trace
     * @return array
     */
    public static function arrayExcludeTraceVendor(?array $value, ?string $basePath = null): array
    {
        $basePath ??= static::pathRoot();

        $resultTrace = [];
        foreach ($value ?? [] as $item) {
            if (
                !static::stringSearchAny($item['file'] ?? '', static::$ignoreFiles)
                &&
                !static::stringSearchAny($item['class'] ?? '', static::$ignoreClasses)
            ) {
                $file = trim(static::stringReplace($item['file'] ?? '', $basePath, '', false), '/');
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
     * @see ../../tests/HelperArrayTrait/HelperArrayMappingKeysTest.php
     *
     * @param array|object|null $value
     * @param int|float|string|array|object|null $from
     * @param int|float|string|array|object|null|null $to
     * @param string|null $rootKey
     * @return array
     */
    public static function arrayMappingKeys(
        array|object|null $value,
        int|float|string|array|object|null $from,
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
     * @see ../../tests/HelperArrayTrait/HelperArraySearchKeysTest.php
     *
     * @param array|object|null $value
     * @param mixed ...$searches
     * @return array
     */
    public static function arraySearchKeys(array|object|null $value, mixed ...$searches): array
    {
        $result = [];
        $value = static::transformToArray($value);
        $searches = static::transformToArray($searches);

        foreach ($value as $key => $v) {
            $key = (string)$key;
            if ($v && (is_array($v) || is_object($v)) && static::transformToArray($v)) {
                $subValue = [];
                foreach ($v as $key2 => $v2) {
                    $subValue["{$key}" . static::$dotDelimiter . "{$key2}"] = $v2;
                }

                // Используем присваивание через foreach вместо распаковки массива,
                // чтобы избежать переиндексации числовых ключей (например, '2' => 5 -> [1 => 5])
                $subResult = static::arraySearchKeys($subValue, ...$searches);
                foreach ($subResult as $rk => $rv) {
                    $result[$rk] = $rv;
                }

            } else {
                foreach ($searches as $search) {
                    if (is_array($search) || is_object($search)) {
                        // Аналогично — запрещаем переиндексацию числовых ключей при мердже результатов
                        $subResult = static::arraySearchKeys([$key => $v], ...$search);
                        foreach ($subResult as $rk => $rv) {
                            $result[$rk] = $rv;
                        }

                    } else if (
                        !is_null($search)
                        && (($searchString = (string)$search) || $searchString === '0')
                        && (
                            $searchString === '*'
                            || $key === $search
                            || $key === $searchString
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
     * @see ../../tests/HelperArrayTrait/HelperArraySearchValuesTest.php
     *
     * @param array|object|null $value
     * @param mixed ...$searches
     * @return array
     */
    public static function arraySearchValues(array|object|null $value, mixed ...$searches): array
    {
        $result = [];
        $value = static::transformToArray($value);
        $searches = static::transformToArray($searches);

        foreach ($value as $key => $v) {
            $key = (string)$key;
            if ($v && (is_array($v) || is_object($v)) && static::transformToArray($v)) {
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
     * @see ../../tests/HelperArrayTrait/HelperArraySearchKeysAndValuesTest.php
     *
     * @param array|object|null $value
     * @param int|float|bool|string|array|object|null $keys
     * @param int|float|bool|string|array|object|null $values
     * @return array
     */
    public static function arraySearchKeysAndValues(
        array|object|null $value,
        int|float|bool|string|array|object|null $keys,
        int|float|bool|string|array|object|null $values,
    ): array {
        return static::arraySearchValues(static::arraySearchKeys($value, $keys), $values);
    }


    /**
     * Возвращает значение из массива по имению ключа
     * @see ../../tests/HelperArrayTrait/HelperArrayGetTest.php
     *
     * @param array|object|null $value
     * @param int|float|string|bool|null $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function arrayGet(
        array|object|null $value,
        int|float|string|bool|null $key,
        mixed $default = null,
    ): mixed {
        if (is_null($key)) {
            return null;
        }

        $value = static::transformToArray($value);

        if (!isset($value[$key]) && mb_strpos((string)$key, static::$dotDelimiter) !== false) {
            foreach (explode(static::$dotDelimiter, $key) as $partKey) {
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
     * @see ../../tests/HelperArrayTrait/HelperArrayFirstTest.php
     *
     * @param array|object|null $value
     * @return mixed
     */
    public static function arrayFirst(array|object|null $value): mixed
    {
        $value = static::transformToArray($value);

        return !is_null($key = array_key_first($value)) ? ($value[$key] ?? null) : null;
    }


    /**
     * Возвращает значение последнего элемента массива
     * @see ../../tests/HelperArrayTrait/HelperArrayLastTest.php
     *
     * @param array|object|null $value
     * @return mixed
     */
    public static function arrayLast(array|object|null $value): mixed
    {
        $value = static::transformToArray($value);

        return !is_null($key = array_key_last($value)) ? ($value[$key] ?? null) : null;
    }


    /**
     * Возвращает одномерный массив из многомерного
     * @see ../../tests/HelperArrayTrait/HelperArrayDotTest.php
     *
     * @param array|object|null $value
     * @return array
     */
    public static function arrayDot(array|object|null $value): array
    {
        return static::arraySearchKeys(static::transformToArray($value), ['*']);
    }


    /**
     * Возвращает многомерный массив из одномерного
     * @see ../../tests/HelperArrayTrait/HelperArrayUnDotTest.php
     *
     * @param array|null $value
     * @return array
     */
    public static function arrayUnDot(array|null $value): array
    {
        $result = [];

        foreach ($value ?? [] as $key => $v) {
            if (is_string($key) && mb_strpos($key, static::$dotDelimiter) !== false) {
                $keys = static::stringSplit($key, static::$dotDelimiter);
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


    /**
     * Возвращает массив с удаленными ключами
     * @see ../../tests/HelperArrayTrait/HelperArrayDeleteKeysTest.php
     *
     * @param array|object|null $value
     * @param mixed ...$searches
     * @return array
     */
    public static function arrayDeleteKeys(array|object|null $value, mixed ...$searches): array
    {
        $value = static::transformToArray($value);
        $searches = static::transformToArray($searches);

        foreach ($value as $key => $v) {
            if (is_array($v) || is_object($v)) {

                $subValue = [];
                foreach ($v as $key2 => $v2) {
                    $subValue["{$key}.{$key2}"] = $v2;
                }

                $value[$key] = static::arrayDeleteKeys($subValue, ...$searches)[$key] ?? [];

            }

            foreach ($searches as $search) {
                if (is_array($search) || is_object($search)) {
                    $v = static::arrayDeleteKeys([$key => $v], ...$search)[$key] ?? null;
                    if (is_null($v)) {
                        unset($value[$key]);

                    } else {
                        $value[$key] = $v;
                    }

                } else if (
                    !is_null($search)
                    && (($searchString = (string)$search) || $searchString === '0')
                    && (
                        $searchString === '*'
                        || $key === $search
                        || $key === $searchString
                        || (
                            is_scalar($key)
                            && static::regexpValidatePattern($searchString)
                            && preg_match($searchString, (string)$key)
                        )
                        || (
                            is_scalar($key)
                            && mb_strpos($searchString, '*') !== false
                            && fnmatch($searchString, (string)$key)
                        )
                    )
                ) {
                    unset($value[$key]);
                }
            }
        }

        return static::arrayUnDot($value);
    }


    /**
     * Возвращает массив с удаленными значениями
     * @see ../../tests/HelperArrayTrait/HelperArrayDeleteValuesTest.php
     *
     * @param array|object|null $value
     * @param mixed ...$searches
     * @return array
     */
    public static function arrayDeleteValues(array|object|null $value, mixed ...$searches): array
    {
        $value = static::transformToArray($value);
        $searches = static::transformToArray($searches);

        foreach ($value as $key => $v) {
            if (is_array($v) || is_object($v)) {
                $value[$key] = $v = static::arrayDeleteValues($v, ...$searches) ?? [];
            }

            foreach ($searches as $search) {
                if (is_array($search) || is_object($search)) {
                    $v = static::arrayDeleteValues([$key => $v], ...$search)[$key] ?? null;
                    if (is_null($v)) {
                        unset($value[$key]);

                    } else {
                        $value[$key] = $v;
                    }

                } else if (
                    !is_null($search)
                    && (($searchString = (string)$search) || $searchString === '0')
                    && (
                        $searchString === '*'
                        || $v === $search
                        || $v === $searchString
                        || (
                            is_scalar($v)
                            && static::regexpValidatePattern($searchString)
                            && preg_match($searchString, (string)$v)
                        )
                        || (
                            is_scalar($v)
                            && mb_strpos($searchString, '*') !== false
                            && fnmatch($searchString, (string)$v)
                        )
                    )
                ) {
                    unset($value[$key]);
                }
            }
        }

        return static::arrayUnDot($value);
    }


    /**
     * Возвращает массив с заменой местами ключей и значений
     * @see ../../tests/HelperArrayTrait/HelperArrayFlipTest.php
     *
     * @param array|object|null $value
     * @return array
     */
    public static function arrayFlip(array|object|null $value): array
    {
        $value = static::transformToArray($value);
        $result = [];

        foreach ($value as $key => $v) {
            if (is_array($v) || is_object($v)) {
                $result += static::arrayFlip($v);
            } else {
                $result[$v] = $key;
            }
        }

        return $result;
    }


    /**
     * Возвращает массив с применением trim для каждого значения
     * @see ../../tests/HelperArrayTrait/HelperArrayTrimValuesTest.php
     *
     * @param array|object|null $value
     * @param array|string|null|null $trims
     * @return array
     */
    public static function arrayTrimValues(array|object|null $value, array|string|null $trims = null): array
    {
        $value = static::transformToArray($value);

        foreach ($value as $key => $v) {
            $value[$key] = (is_array($v) || is_object($v))
                ? (static::arrayTrimValues($v, $trims) ?? [])
                : trim($v, is_array($trims) ? implode('', $trims) : $trims ?? " .,:;\n\r\t\v\x00");
        }

        return $value;
    }


    /**
     * Проверяет является ли массив ассоциативным
     * @see ../../tests/HelperArrayTrait/HelperArrayIsAssociativeTest.php
     *
     * @param array|object|null $value
     * @param array|string|null|null $trims
     * @return bool
     */
    public static function arrayIsAssociative(array|object|null $value): bool
    {
        return !array_is_list(static::transformToArray($value));
    }
}
