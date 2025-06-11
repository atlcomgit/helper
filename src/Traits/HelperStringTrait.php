<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Consts\HelperConsts;
use Atlcom\Enums\HelperStringBreakTypeEnum;
use Atlcom\Enums\HelperStringEncodingEnum;
use Atlcom\Internal\HelperInternal;
use BackedEnum;

/**
 * Трейт для работы со строками
 * @mixin \Atlcom\Helper
 */
trait HelperStringTrait
{
    /**
     * Возвращает длину строки
     * @see ../../tests/HelperStringTrait/HelperStringLengthTest.php
     *
     * @param int|float|string|null $value
     * @return int
     */
    public static function stringLength(int|float|string|null $value): int
    {
        return mb_strlen((string)$value, HelperStringEncodingEnum::Utf8->value);
    }


    /**
     * Переводит строку в верхний регистр
     * @see ../../tests/HelperStringTrait/HelperStringUpperTest.php
     *
     * @param int|float|string|null $value
     * @return string
     */
    public static function stringUpper(int|float|string|null $value): string
    {
        return mb_strtoupper((string)$value, HelperStringEncodingEnum::Utf8->value);
    }


    /**
     * Переводит строку в нижний регистр
     * @see ../../tests/HelperStringTrait/HelperStringLowerTest.php
     *
     * @param int|float|string|null $value
     * @return string
     */
    public static function stringLower(int|float|string|null $value): string
    {
        return mb_strtolower((string)$value, HelperStringEncodingEnum::Utf8->value);
    }


    /**
     * Возвращает строку с первый символом в верхнем регистре
     * @see ../../tests/HelperStringTrait/HelperStringUpperFirstTest.php
     *
     * @param int|float|string|null $value
     * @return string
     */
    public static function stringUpperFirst(int|float|string|null $value): string
    {
        $value = (string)$value;

        return static::stringChange($value, static::stringUpper(static::stringCopy($value, 0, 1)), 0);
    }


    /**
     * Возвращает строку с первый символом в нижнем регистре
     * @see ../../tests/HelperStringTrait/HelperStringLowerFirstTest.php
     *
     * @param int|float|string|null $value
     * @return string
     */
    public static function stringLowerFirst(int|float|string|null $value): string
    {
        $value = (string)$value;

        return static::stringChange($value, static::stringLower(static::stringCopy($value, 0, 1)), 0);
    }


    /**
     * Возвращает строку со всеми словами в верхнем регистре
     * @see ../../tests/HelperStringTrait/HelperStringUpperFirstAllTest.php
     *
     * @param int|float|string|null $value
     * @return string
     */
    public static function stringUpperFirstAll(int|float|string|null $value): string
    {
        $value = (string)$value;

        $words = [];
        foreach ([' ', '_', '-', chr(9), chr(13)] as $delimiter) {
            $words = static::stringSplit($value, $delimiter);

            foreach ($words as &$word) {
                $word = static::stringUpperFirst($word);
            }

            $value = implode($delimiter, $words);
        }

        return $value;
    }


    /**
     * Возвращает строку со всеми словами в нижнем регистре
     * @see ../../tests/HelperStringTrait/HelperStringLowerFirstAllTest.php
     *
     * @param int|float|string|null $value
     * @return string
     */
    public static function stringLowerFirstAll(int|float|string|null $value): string
    {
        $value = (string)$value;

        $words = [];
        foreach ([' ', '_', '-', chr(9), chr(13)] as $delimiter) {
            $words = static::stringSplit($value, $delimiter);

            foreach ($words as &$word) {
                $word = static::stringLowerFirst($word);
            }

            $value = implode($delimiter, $words);
        }

        return $value;
    }


    /**
     * Возвращает массив подстрок разбитых на части между разделителем в строке
     * Если передан параметр index, то возвращает значение элемента из этого массива
     * Если cacheEnabled выключен, то кеш не используется
     * @see ../../tests/HelperStringTrait/HelperStringSplitTest.php
     *
     * @param int|float|string|null $value
     * @param int|float|string|array|null $delimiters
     * @param int|null $index
     * @param bool $cacheEnabled
     * @return array|string|null
     */
    public static function stringSplit(
        int|float|string|null $value,
        int|float|string|array|null $delimiters,
        ?int $index = null,
        bool $cacheEnabled = true,
    ): array|string|null {
        $value = (string)$value;
        $delimiters = static::transformToArray($delimiters);
        $delimiters = array_values(static::arrayDot($delimiters));
        $cacheKey = static::hashXxh128(__CLASS__ . __FUNCTION__ . $value . implode('|', $delimiters));

        $splits = static::cacheRuntime($cacheKey, static function () use (&$value, &$delimiters) {
            $splits = [];
            $delimiter = '';
            $dotDelimiter = static::$dotDelimiter;
            static::$dotDelimiter = HelperConsts::STRING_SPLIT_DOT_DELIMITER;

            while ($value || $delimiter) {
                if ($delimiterIndexed = static::arrayDot(static::stringPosAll($value, $delimiters))) {
                    asort($delimiterIndexed);
                    $delimiter = array_key_first($delimiterIndexed);
                    $delimiterPos = $delimiterIndexed[$delimiter];
                    $delimiter = explode(static::$dotDelimiter, $delimiter)[0];
                } else {
                    $delimiter = '';
                }

                $delimiter
                    ? $splits[] = static::stringDelete(
                        static::stringCut($value, 0, $delimiterPos + static::stringLength($delimiter)),
                        -static::stringLength($delimiter)
                    )
                    : $splits[] = static::stringCut($value, 0);
            }

            static::$dotDelimiter = $dotDelimiter;

            return $splits;
        }, $cacheEnabled);

        return is_null($index)
            ? $splits
            : ($splits[$index < 0 ? count($splits) + $index : $index] ?? null);
    }


    /**
     * Возвращает строку в интервале разбитых на части между разделителем строки
     * Если cacheEnabled выключен, то кеш не используется
     * @see ../../tests/HelperStringTrait/HelperStringSplitRangeTest.php
     *
     * @param int|float|string|null $value
     * @param int|float|string|array|null $delimiters
     * @param int|null $from
     * @param int|null $to
     * @param bool $cacheEnabled
     * @return string|null
     */
    public static function stringSplitRange(
        int|float|string|null $value,
        int|float|string|array|null $delimiters,
        ?int $indexFrom = null,
        ?int $indexTo = null,
        bool $cacheEnabled = true,
    ): string|null {
        $value = (string)$value;
        $delimiters = static::transformToArray($delimiters);
        $delimiters = array_values(static::arrayDot($delimiters));
        $cacheKey = static::hashXxh128(__CLASS__ . __FUNCTION__ . $value . implode('|', $delimiters));

        $splits = static::cacheRuntime($cacheKey, static function () use (&$value, &$delimiters) {
            $splits = [];
            $delimiter = '';
            $dotDelimiter = static::$dotDelimiter;
            static::$dotDelimiter = HelperConsts::STRING_SPLIT_DOT_DELIMITER;

            while ($value || $delimiter) {
                $lastDelimiter = $delimiter;

                if ($delimiterIndexed = static::arrayDot(static::stringPosAll($value, $delimiters))) {
                    asort($delimiterIndexed);
                    $delimiter = array_key_first($delimiterIndexed);
                    $delimiterPos = $delimiterIndexed[$delimiter];
                    $delimiter = explode(static::$dotDelimiter, $delimiter)[0];
                } else {
                    $delimiter = '';
                }

                $delimiter
                    ? $splits[] = [
                        'delimiter' => $lastDelimiter,
                        'value' => static::stringDelete(
                            static::stringCut($value, 0, $delimiterPos + static::stringLength($delimiter)),
                            -static::stringLength($delimiter)
                        ),
                    ]
                    : $splits[] = [
                        'delimiter' => $lastDelimiter,
                        'value' => static::stringCut($value, 0),
                    ];
            }

            static::$dotDelimiter = $dotDelimiter;

            return $splits;
        }, $cacheEnabled);

        $result = '';
        $splitCount = count($splits);
        $indexFrom = match (true) {
            is_null($indexFrom) => 0,
            $indexFrom < 0 => $splitCount + $indexFrom,
            $indexFrom > $splitCount => $splitCount - 1,

            default => $indexFrom,
        };
        $indexTo = match (true) {
            is_null($indexTo) => $splitCount - 1,
            $indexTo < 0 => $splitCount + $indexTo,
            $indexTo > $splitCount => $splitCount - 1,

            default => $indexTo,
        };
        ($indexFrom < $indexTo) ?: $indexTo = $indexFrom; //static::varSwap($indexFrom, $indexTo);

        for ($index = $indexFrom; $index <= $indexTo; $index++) {
            $result .= (($index > $indexFrom) ? $splits[$index]['delimiter'] : '') . $splits[$index]['value'];
        }

        return $result;
    }


    /**
     * Возвращает массив индексов частей между $delimiters, в которых найдены строки $searches
     * @see ../../tests/HelperStringTrait/HelperStringSplitSearchTest.php
     *
     * @param int|float|string|null $value
     * @param int|float|string|array|null $delimiters
     * @param mixed ...$searches
     * @param mixed 
     * @return array
     */
    public static function stringSplitSearch(
        int|float|string|null $value,
        int|float|string|array|null $delimiters,
        mixed ...$searches,
    ): array {
        $value = (string)$value;
        $delimiters = static::transformToArray($delimiters);
        $delimiters = array_values(static::arrayDot($delimiters));
        $splits = static::stringSplit($value, $delimiters);
        $result = [];

        foreach ($searches as $search) {
            if (is_array($search) || is_object($search)) {
                !$search ?: $result = [
                    ...$result,
                    ...static::stringSplitSearch($value, ...static::transformToArray($search)),
                ];
            } else {
                for ($splitIndex = 0; $splitIndex < count($splits); $splitIndex++) {
                    !static::stringSearchAny($splits[$splitIndex], $search)
                        ?: $result[$search][] = $splitIndex;
                }
            }
        }

        return $result;
    }


    /**
     * Копирует подстроку из строки с позиции start длиной length
     * @see ../../tests/HelperStringTrait/HelperStringCopyTest.php
     *
     * @param int|float|string|null $value
     * @param int $start
     * @param int|null $length
     * @return string
     */
    public static function stringCopy(int|float|string|null $value, int $start, ?int $length = null): string
    {
        return mb_substr((string)$value, $start, $length, HelperStringEncodingEnum::Utf8->value);
    }


    /**
     * Удаляет из строки с позиции start длиной length
     * @see ../../tests/HelperStringTrait/HelperStringDeleteTest.php
     *
     * @param int|float|string|null $value
     * @param int $start
     * @param int|null $length
     * @return string
     */
    public static function stringDelete(int|float|string|null $value, int $start, ?int $length = null): string
    {
        return static::stringCopy($value, 0, $start) . match (true) {
            $length > 0 => static::stringCopy($value, $start + $length),
            $length === 0 => '',
            $length < 0 => static::stringCopy($value, static::stringLength($value) + $length),

            default => '',
        };
    }


    /**
     * Вырезает подстроку из строки с позиции start длиной length
     * @see ../../tests/HelperStringTrait/HelperStringCutTest.php
     *
     * @param int|float|string|null $value
     * @param int $start
     * @param int|null $length
     * @return string
     */
    public static function stringCut(int|float|string|null &$value, int $start, ?int $length = null): string
    {
        $value = (string)$value;

        if ($length === 0) {
            return '';
        }

        $result = static::stringCopy($value, $start, $length);
        $value = static::stringDelete($value, $start, $length);

        return $result;
    }


    /**
     * Вставляет подстроку в строку с позиции start
     * @see ../../tests/HelperStringTrait/HelperStringPasteTest.php
     *
     * @param int|float|string|null $value
     * @param int|float|string|null $paste
     * @param int $start
     * @return string
     */
    public static function stringPaste(int|float|string|null $value, int|float|string|null $paste, int $start): string
    {
        $value = (string)$value;
        $paste = (string)$paste;

        return match (true) {
            $start > 0 => static::stringCopy($value, 0, $start) . $paste . static::stringCopy($value, $start),
            $start === 0 => "{$paste}{$value}",
            $start < 0
            => static::stringCopy($value, 0, static::stringLength($value) + $start)
            . $paste
            . static::stringCopy($value, $start),
        };
    }


    /**
     * Заменяет на подстроку в строке с позиции start
     * @see ../../tests/HelperStringTrait/HelperStringChangeTest.php
     *
     * @param int|float|string|null $value
     * @param int|float|string|null $change
     * @param int $start
     * @return string
     */
    public static function stringChange(int|float|string|null $value, int|float|string|null $change, int $start): string
    {
        $value = (string)$value;
        $change = (string)$change;

        return match (true) {
            $start >= 0
            => static::stringCopy($value, 0, $start)
            . $change
            . static::stringCopy($value, $start + static::stringLength($change)),
            $start < 0
            => static::stringCopy($value, 0, $start)
            . $change
            . static::stringCopy($value, static::stringLength($value) + $start + static::stringLength($change)),
        };
    }


    /**
     * Проверяет начало строки на совпадение подстрок и возвращает найденную подстроку или null
     * @see ../../tests/HelperStringTrait/HelperStringStartsTest.php
     *
     * @param int|float|string|null $value
     * @param mixed ...$searches
     * @return string|null
     */
    public static function stringStarts(int|float|string|null $value, mixed ...$searches): ?string
    {
        $value = (string)$value;

        foreach ($searches as $search) {
            if (is_array($search) || is_object($search)) {
                if ($result = static::stringStarts($value, ...static::transformToArray($search))) {
                    return $result;
                }

            } else if (!is_null($search) && $search !== '' && str_starts_with($value, (string)$search)) {
                return $search;
            }
        }

        return null;
    }


    /**
     * Проверяет конец строки на совпадение подстрок и возвращает найденную подстроку или null
     * @see ../../tests/HelperStringTrait/HelperStringEndsTest.php
     *
     * @param int|float|string|null $value
     * @param mixed ...$searches
     * @return string|null
     */
    public static function stringEnds(int|float|string|null $value, mixed ...$searches): ?string
    {
        $value = (string)$value;

        foreach ($searches as $search) {
            if (is_array($search) || is_object($search)) {
                if ($result = static::stringEnds($value, ...static::transformToArray($search))) {
                    return $result;
                }

            } else if (!is_null($search) && $search !== '' && str_ends_with($value, (string)$search)) {
                return $search;
            }
        }

        return null;
    }


    /**
     * Возвращает число с числительным названием из массива с тремя вариантами для [0|5..9, 1, 2..4]
     * @see ../../tests/HelperStringTrait/HelperStringPluralTest.php
     *
     * @param int|float|string|null $number
     * @param array $words
     * @param bool $includeNumber
     * @return string
     */
    public static function stringPlural(int|float|string|null $value, array $plurals, bool $includeValue = true): string
    {
        $numberString = (string)abs(floor(floatval($value)));
        $numberPart = (int)static::stringCopy($numberString, static::stringLength($numberString) - 2, 2);
        $numberMod = $numberPart % 10;

        return is_null($value)
            ? ''
            : (
                ($includeValue ? "{$value} " : "")
                . (
                    (static::numberDecimalDigits($value) === 0)
                    ? match (true) {
                        $numberMod == 1 && $numberPart != 11 => (string)($plurals[1] ?? ''),
                        $numberMod > 1 && $numberMod < 5 && !($numberPart > 11 && $numberPart < 15)
                        => (string)($plurals[2] ?? ''),

                        default => (string)($plurals[0] ?? ''),
                    }
                    : ($value > 0.0 ? (string)($plurals[2] ?? '') : (string)($plurals[0] ?? ''))
                )

            );
    }


    /**
     * Разбивает текст на части указанной длины по словам или строкам и возвращает массив строк
     * @see ../../tests/HelperStringTrait/HelperStringBreakByLengthTest.php
     *
     * @param string|null $message
     * @param HelperStringBreakTypeEnum $breakType
     * @param int $partLengthMax
     * @param int|null $firstPartIsShort
     * @return array
     */
    public static function stringBreakByLength(
        ?string $value,
        HelperStringBreakTypeEnum $breakType,
        int $partLengthMax = 4000,
        ?int $firstPartLength = null, // 1000
    ): array {
        $result = [];
        $currentText = '';
        $currentLength = 0;

        if (
            static::stringLength($value) <= ($firstPartLength ? min($firstPartLength, $partLengthMax) : $partLengthMax)
        ) {
            is_null($value) ?: $result[] = $value;

        } else {
            // Разбиваем сообщение на строки
            foreach (explode(PHP_EOL, $value) as $lineIndex => $line) {
                $lineLength = static::stringLength($line);
                // Разбиваем строку на слова
                $words = preg_split("/[\ ]+/", rtrim($line, CHR(9) . ' '));
                $break = ($lineIndex > 0) ? PHP_EOL : '';
                $breakLength = static::stringLength($break);
                $lineLengthMax = (count($result) === 0)
                    ? ($firstPartLength ? min($firstPartLength, $partLengthMax) : $partLengthMax)
                    : $partLengthMax;

                switch ($breakType) {
                    // Разбитие по словам
                    case HelperStringBreakTypeEnum::Word:
                        foreach ($words as $wordIndex => $word) {
                            $wordLength = static::stringLength($word);
                            $space = ($wordIndex > 0) ? ' ' : '';
                            $spaceLength = static::stringLength($space);

                            if ($currentLength + $breakLength + $spaceLength + $wordLength <= $lineLengthMax) {
                                $currentText .= "{$break}{$space}{$word}";
                                $currentLength += $spaceLength + $wordLength;

                            } else {
                                if ($breakLength + $currentLength <= $lineLengthMax) {
                                    $currentText .= $break;
                                }
                                if (static::stringCopy($currentText, -static::stringLength(PHP_EOL)) == PHP_EOL) {
                                    $currentText = static::stringCopy($currentText, 0, -static::stringLength(PHP_EOL));
                                }
                                (!$currentText && !$currentLength) ?: $result[] = $currentText;
                                $currentText = $word;
                                $currentLength = $wordLength;
                            }

                            $break = '';
                            $breakLength = 0;
                        }
                        break;

                    // Разбитие по строкам
                    case HelperStringBreakTypeEnum::Line:
                        if ($currentLength + $breakLength + $lineLength <= $lineLengthMax) {
                            $currentText .= "{$break}{$line}";
                            $currentLength += $breakLength + $lineLength;

                        } else {
                            (!$currentText && !$currentLength) ?: $result[] = $currentText;
                            $currentText = $line;
                            $currentLength = $lineLength;
                        }
                        break;
                }
            }

            if ($currentText != '') {
                if (static::stringCopy($currentText, -static::stringLength(PHP_EOL)) == PHP_EOL) {
                    $currentText = static::stringCopy($currentText, 0, -static::stringLength(PHP_EOL));
                }
                $result[] = $currentText;
            }
        }

        return $result;
    }


    /**
     * Соединяет значения values через разделитель delimiter
     * @see ../../tests/HelperStringTrait/HelperStringConcatTest.php
     *
     * @param int|float|string|null $delimiter
     * @param mixed ...$values
     * @return string
     */
    public static function stringConcat(int|float|string|null $delimiter, mixed ...$values): string
    {
        $delimiter = (string)$delimiter;

        return implode(
            $delimiter,
            array_filter(
                array_map(
                    fn (mixed $value): string => (is_array($value) || is_object($value))
                    ? static::stringConcat($delimiter, ...static::transformToArray($value))
                    : (string)$value,
                    $values,
                ),
            ),
        );
    }


    /**
     * Добавляет в начало строки префикс при выполнении условия condition
     * @see ../../tests/HelperStringTrait/HelperStringPadPrefixTest.php
     *
     * @param int|float|string|null $value
     * @param int|float|string|null $prefix
     * @param mixed $condition
     * @return string
     */
    public static function stringPadPrefix(
        int|float|string|null $value,
        int|float|string|null $prefix,
        mixed $condition = null,
    ): string {
        !(is_object($value) && is_callable($condition)) ?: $condition = $condition($value, $prefix);
        !is_null($condition) ?: $condition = (bool)$value;

        return (($prefix && (bool)$condition) ? (string)$prefix : '') . (string)$value;
    }


    /**
     * Добавляет в конец строки суффикс при выполнении условия condition
     * @see ../../tests/HelperStringTrait/HelperStringPadSuffixTest.php
     *
     * @param int|float|string|null $value
     * @param int|float|string|null $suffix
     * @param mixed $condition
     * @return string
     */
    public static function stringPadSuffix(
        int|float|string|null $value,
        int|float|string|null $suffix,
        mixed $condition = null,
    ): string {
        !(is_object($value) && is_callable($condition)) ?: $condition = $condition($value, $suffix);
        !is_null($condition) ?: $condition = (bool)$value;

        return (string)$value . (($suffix && (bool)$condition) ? (string)$suffix : '');
    }


    /**
     * Объединяет значения в одну строку с заменой предыдущих символов
     * @see ../../tests/HelperStringTrait/HelperStringMergeTest.php
     *
     * @param mixed ...$values
     * @return string
     */
    public static function stringMerge(mixed ...$values): string
    {
        $result = '';

        foreach ($values as $value) {
            $result = (is_array($value) || is_object($value))
                ? static::stringMerge(...static::transformToArray($value))
                : static::stringChange($result, $value, 0);
        }

        return $result;
    }


    /**
     * Возвращает массив первой искомой подстроки найденной в строке
     * @see ../../tests/HelperStringTrait/HelperStringSearchAnyTest.php
     *
     * @param int|float|string|null $value
     * @param mixed ...$searches
     * @return array
     */
    public static function stringSearchAny(int|float|string|null $value, mixed ...$searches): array
    {
        $value = (string)$value;

        foreach ($searches as $search) {
            if (is_array($search) || is_object($search)) {
                if ($result = static::stringSearchAny($value, ...static::transformToArray($search))) {
                    return $result;
                }

            } else if (
                !is_null($search)
                && $search !== ''
                && (($searchString = (string)$search) || $searchString === '0')
                && (
                    $searchString === '*'
                    || mb_strpos($value, $searchString) !== false
                    || (static::regexpValidatePattern($searchString) && preg_match($searchString, $value))
                    || (mb_strpos($searchString, '*') !== false && fnmatch($searchString, $value))
                )
            ) {
                return [$search];
            }
        }

        return [];
    }


    /**
     * Возвращает массив всех искомых подстрок найденных в строке
     * @see ../../tests/HelperStringTrait/HelperStringSearchAllTest.php
     *
     * @param int|float|string|null $value
     * @param mixed ...$searches
     * @return array
     */
    public static function stringSearchAll(int|float|string|null $value, mixed ...$searches): array
    {
        $result = [];
        $value = (string)$value;

        foreach ($searches as $search) {
            if (is_array($search) || is_object($search)) {
                $result = [
                    ...$result,
                    ...(static::stringSearchAll($value, ...static::transformToArray($search)) ?: []),
                ];

            } else if (
                !is_null($search)
                && $search !== ''
                && (($searchString = (string)$search) || $searchString === '0')
                && (
                    $searchString === '*'
                    || mb_strpos($value, $searchString) !== false
                    || (static::regexpValidatePattern($searchString) && preg_match($searchString, $value))
                    || (mb_strpos($searchString, '*') !== false && fnmatch($searchString, $value))
                )
            ) {
                $result[] = $search;
            }
        }

        return array_values(array_unique($result));
    }


    /**
     * Заменяет подстроки в строке
     * @see ../../tests/HelperStringTrait/HelperStringReplaceTest.php
     *
     * @param int|float|string|null $value
     * @param int|float|string|array|null $searches
     * @param int|float|string|array|null $replaces
     * @param bool $useRegexp
     * @return string
     */
    public static function stringReplace(
        int|float|string|null $value,
        int|float|string|array|null $searches,
        int|float|string|array|null $replaces = null,
        bool $useRegexp = true,
    ): string {
        $value = (string)$value;

        if (!is_null($searches) && $searches !== '') {

            if (is_scalar($searches) && is_scalar($replaces)) {
                $searches = (string)$searches;
                $replaces = (string)$replaces;
                $value = ($useRegexp && static::regexpValidatePattern($searches))
                    ? (preg_replace($searches, $replaces, $value) ?? $value)
                    : str_replace($searches, $replaces, $value);

            } else if (is_scalar($searches) && is_array($replaces)) {
                $searches = (string)$searches;
                $value = ($useRegexp && static::regexpValidatePattern($searches))
                    ? (preg_replace($searches, (string)array_values($replaces)[0] ?? '', $value) ?? $value)
                    : str_replace($searches, (string)array_values($replaces)[0] ?? '', $value);

            } else if (is_array($searches)) {
                foreach ($searches as $key => $search) {
                    if (is_integer($key)) {
                        (is_null($search) || $search === '')
                            ?: (
                                $value = ($useRegexp && static::regexpValidatePattern($search))
                                ? (preg_replace($search, $replaces[$key] ?? '', $value) ?? $value)
                                : str_replace(
                                    $search,
                                    is_array($replaces) ? ($replaces[$key] ?? '') : ($replaces ?? ''),
                                    $value,
                                )
                            );

                    } else {
                        (is_null($key) || $key === '')
                            ?: (
                                $value = ($useRegexp && static::regexpValidatePattern($key))
                                ? (preg_replace($key, $search ?? '', $value) ?? $value)
                                : str_replace($key, $search ?? '', $value)
                            );
                    }
                }
            }
        }

        return $value;
    }


    /**
     * Возвращает количество найденных вхождений подстрок в строке
     * @see ../../tests/HelperStringTrait/HelperStringCountTest.php
     *
     * @param int|float|string|null $value
     * @param int|float|string|array|null $searches
     * @return int
     */
    public static function stringCount(int|float|string|null $value, int|float|string|array|null ...$searches): int
    {
        $value = (string)$value;
        $result = 0;

        foreach ($searches as $search) {
            if (is_array($search) || is_object($search)) {
                $result += static::stringCount($value, ...static::transformToArray($search));

            } else if (!is_null($search) && $search !== '') {
                $result += mb_substr_count($value, (string)$search, HelperStringEncodingEnum::Utf8->value);
            }
        }

        return $result;
    }


    /**
     * Возвращает повторяющуюся строку значения указанное количество раз
     * @see ../../tests/HelperStringTrait/HelperStringRepeatTest.php
     *
     * @param int|float|string|null $value
     * @param int $count
     * @return string
     */
    public static function stringRepeat(int|float|string|null $value, int $count): string
    {
        return str_repeat((string)$value, $count);
    }


    /**
     * Возвращает реверсивную строку значения
     * @see ../../tests/HelperStringTrait/HelperStringReverseTest.php
     *
     * @param int|float|string|null $value
     * @return string
     */
    public static function stringReverse(int|float|string|null $value): string
    {
        return implode(array_reverse(mb_str_split((string)$value)));
    }


    /**
     * Возвращает случайную строку по шаблону регулярного выражения
     * @see ../../tests/HelperStringTrait/HelperStringRandomTest.php
     *
     * @param string|BackedEnum|null $pattern
     * @return string
     */
    public static function stringRandom(string|BackedEnum|null $pattern): string
    {
        $result = '';

        $pattern = ($pattern instanceof BackedEnum) ? (string)$pattern->value : (string)$pattern;
        if (static::regexpValidatePattern($pattern)) {
            $patterns = static::stringSplit($pattern, '/');
            $patterns = array_slice($patterns, 1, -1);
            $pattern = implode('/', $patterns);
        } else {
            $pattern = trim($pattern, '/%$^');
        }

        $pattern = static::stringReplace($pattern, [
            '\+' => '%PLUS%',
            '\*' => '%STAR%',
            '\.' => '%DOT%',
            '\_' => '%UND%',
            '\-' => '%MINUS%',
            '+' => '{1,10}',
            '*' => '{0,10}',
            '\d' => '0-9',
            '\w' => 'A-Za-z',
            '\u' => 'А-ЯЁа-яё',
            '\s' => '\ ',
            '.+' => '[A-Za-zА-ЯЁа-яё0-9\ ]+',
            '.' => '[A-Za-zА-ЯЁа-яё0-9\ ]',
            '%PLUS%' => '\+',
            '%STAR%' => '\*',
            '%DOT%' => '\.',
            '%UND%' => '\_',
            '%MINUS%' => '\-',
            '\/' => '/',
            '^' => '',
            '$' => '',
            '(' => '',
            ')' => '',
        ]);
        $pattern = stripslashes($pattern);

        // Удаляем ограничители и якоря, упрощаем анализ
        // $pattern = preg_replace(['/^\^/', '/\$/', '/^\/|\/$/'], '', $pattern);

        // Разбиваем шаблон на токены
        preg_match_all('/(\\\[dws]|\[.*?\]|\\.|.)([*?+]|\{\d+,?\d*}|\{\d+})?/', $pattern, $matches, PREG_SET_ORDER);

        $result = '';
        foreach ($matches as $m) {
            $char = $m[1] ?? '';
            $quantifier = $m[2] ?? '';

            // Определяем допустимые символы
            $chars = [];
            if (preg_match('/^\[(.*)\]$/', $char, $charMatch)) {
                // Обработка символьного класса
                $chars = HelperInternal::internalParseCharacterClass($charMatch[1]);

            } elseif (preg_match('/^\\\\([dws])$/', $char, $escapeMatch)) {
                // Обработка специальных классов
                $chars = match ($escapeMatch[1]) {
                    'd' => range('0', '9'),
                    'w' => array_merge(
                        range('a', 'z'),
                        range('A', 'Z'),
                        HelperInternal::internalRange('А', 'Я'),
                        HelperInternal::internalRange('а', 'я'),
                        range('0', '9'),
                        ['Ё', 'ё'],
                        ['_'],
                    ),
                    's' => ["\t", "\n", "\r", "\f", " "],
                };
            } else {
                // Литерал
                $chars = [$char];
            }

            // Определяем количество повторений
            $min = 1;
            $max = 1;
            if ($quantifier) {
                preg_match('/(\d+)(,(\d+))?/', $quantifier, $qMatch);
                if (mb_strpos($quantifier, ',') !== false) {
                    $min = isset($qMatch[1]) ? (int)$qMatch[1] : 0;
                    $max = isset($qMatch[3]) ? (int)$qMatch[3] : 100;
                } else {
                    $min = $max = (int)mb_substr($quantifier, 1, -1);
                }
            }
            $count = ($min === $max) ? $min : rand($min, $max);

            // Генерируем часть строки
            for ($i = 0; $i < $count; $i++) {
                $result .= $chars[array_rand($chars)];
            }
        }

        return $result;
    }


    /**
     * Возвращает массив позиции первой искомой подстроки найденной в строке
     * @see ../../tests/HelperStringTrait/HelperStringPosAnyTest.php
     *
     * @param int|float|string|null $value
     * @param mixed ...$searches
     * @return array
     */
    public static function stringPosAny(int|float|string|null $value, mixed ...$searches): array
    {
        $value = (string)$value;

        foreach ($searches as $search) {
            if (is_array($search) || is_object($search)) {
                if ($result = static::stringPosAny($value, ...static::transformToArray($search))) {
                    return $result;
                }

            } else if (
                !is_null($search)
                && $search !== ''
                && (($searchString = (string)$search) || $searchString === '0')
                && ($pos = mb_strpos($value, $searchString)) !== false
            ) {
                return [$search => $pos];
            }
        }

        return [];
    }


    /**
     * Возвращает массив всех позиций искомых подстрок найденных в строке
     * @see ../../tests/HelperStringTrait/HelperStringPosAllTest.php
     *
     * @param int|float|string|null $value
     * @param mixed ...$searches
     * @return array
     */
    public static function stringPosAll(int|float|string|null $value, mixed ...$searches): array
    {
        $result = [];
        $value = (string)$value;

        foreach ($searches as $search) {
            if (is_array($search) || is_object($search)) {
                $result = [
                    ...$result,
                    ...(static::stringPosAll($value, ...static::transformToArray($search)) ?: []),
                ];

            } else {
                $searchString = (string)$search;
                $currentValue = $value;
                $offsetValue = 0;

                while (
                    !is_null($search)
                    && $search !== ''
                    && ($pos = mb_strpos($currentValue, $searchString)) !== false
                ) {
                    array_key_exists($search, $result)
                        ? (is_array($result[$search])
                            ? $result[$search][] = $offsetValue + $pos
                            : $result[$search] = [$result[$search], $offsetValue + $pos]
                        )
                        : $result[$search] = [$offsetValue + $pos];

                    $searchLength = static::stringLength($search);
                    $currentValue = static::stringDelete($currentValue, $pos, $searchLength);
                    $offsetValue += $searchLength;
                }
            }
        }

        return $result;
    }


    /**
     * Возвращает строку значения удаляя последовательные повторы подстрок
     * @see ../../tests/HelperStringTrait/HelperStringDeleteMultiplesTest.php
     *
     * @param int|float|string|null $value
     * @param mixed ...$multiples
     * @return string
     */
    public static function stringDeleteMultiples(int|float|string|null $value, mixed ...$multiples): string
    {
        $value = (string)$value;

        foreach ($multiples as $multiple) {
            $value = (is_array($multiple) || is_object($multiple))
                ? static::stringDeleteMultiples($value, ...static::transformToArray($multiple))
                : preg_replace("/(" . preg_quote($multiple = (string)$multiple, '/') . "){2,}/", $multiple, $value);
        }

        return $value;
    }


    /**
     * Возвращает строку с разбиением слитных слов и цифр
     * @see ../../tests/HelperStringTrait/HelperStringSegmentTest.php
     *
     * @param string|null $value
     * @return string
     */
    public static function stringSegment(?string $value): string
    {
        $result = '';

        $d = range('0', '9');
        $EN = range('A', 'Z');
        $en = range('a', 'z');
        $En = array_merge($EN, $en);
        $dEn = array_merge($d, $EN, $en);
        $RU = [...HelperInternal::internalRange('А', 'Я'), 'Ё'];
        $ru = [...HelperInternal::internalRange('а', 'я'), 'ё'];
        $closeChars = [')', '}', '>', ']', '"', '_', '.', ',', ':', ';', '!', '?', '%'];
        $prevChar = '';

        while ($value) {
            $currChar = static::stringCut($value, 0, 1);
            $nextChar = static::stringCopy($value, 0, 1);
            $nextNextChar = static::stringCopy($value, 1, 1);

            $suffixChar = match (true) {
                // Не добавляем пробел после символа
                $currChar === '!' => ($nextChar === $currChar) || in_array($nextChar, ['=']),

                in_array($currChar, $EN) => in_array($nextChar, $en) || !in_array($nextChar, $closeChars),
                in_array($currChar, $RU) => in_array($nextChar, $ru) || in_array($nextChar, $closeChars),

                in_array($currChar, $dEn) && $nextChar === '!' && $nextNextChar === '=' => false,

                in_array($currChar, $en) => in_array($nextChar, $en) || in_array($nextChar, $closeChars)
                || (in_array($nextChar, ['-', '_']) && in_array($nextNextChar, $en)),

                in_array($currChar, $ru) => in_array($nextChar, $ru) || in_array($nextChar, $closeChars)
                || (in_array($nextChar, ['-']) && in_array($nextNextChar, $ru)),

                in_array($currChar, $d) => in_array($nextChar, $d) || in_array($nextChar, $closeChars),

                $currChar === '@' => ($nextChar === $currChar) || in_array($nextChar, $En),
                $currChar === '.' => ($nextChar === $currChar) || in_array($nextChar, $d) && in_array($prevChar, $d),
                $currChar === '#' => ($nextChar === $currChar) || in_array($nextChar, $dEn),
                $currChar === '$' => ($nextChar === $currChar) || in_array($nextChar, $dEn),

                in_array($currChar, ['+', '*', '=', '/', '\\']) => ($nextChar === $currChar),
                in_array($currChar, ['-', ' ', '_', '(', '[', '{', '<', '"', "'"]) => true,
                in_array($currChar, $closeChars) => in_array($nextChar, $closeChars),

                // Добавляем пробел после символа
                default => false,

            } ? '' : ' ';

            $result .= "{$currChar}{$suffixChar}";
            $prevChar = $currChar;
        }

        return trim(static::stringDeleteMultiples($result, ' '));
    }
}
