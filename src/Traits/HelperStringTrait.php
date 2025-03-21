<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Enums\HelperStringBreakTypeEnum;

/**
 * Трейт для работы со строками
 */
trait HelperStringTrait
{
    public static string $encoding = 'UTF-8';


    /**
     * Возвращает длину строки
     * @see ./tests/HelperStringTrait/HelperStringLengthTest.php
     *
     * @param int|float|string|null $value
     * @return int
     */
    public static function stringLength(int|float|string|null $value): int
    {
        return mb_strlen((string)$value, static::$encoding);
    }


    /**
     * Переводит строку в верхний регистр
     * @see ./tests/HelperStringTrait/HelperStringUpperTest.php
     *
     * @param int|float|string|null $value
     * @return string
     */
    public static function stringUpper(int|float|string|null $value): string
    {
        return mb_strtoupper((string)$value, static::$encoding);
    }


    /**
     * Переводит строку в нижний регистр
     * @see ./tests/HelperStringTrait/HelperStringLowerTest.php
     *
     * @param int|float|string|null $value
     * @return string
     */
    public static function stringLower(int|float|string|null $value): string
    {
        return mb_strtolower((string)$value, static::$encoding);
    }


    /**
     * Копирует подстроку из строки с позиции start длиной length
     * @see ./tests/HelperStringTrait/HelperStringCopyTest.php
     *
     * @param int|float|string|null $value
     * @param int $start
     * @param int|null $length
     * @return string
     */
    public static function stringCopy(int|float|string|null $value, int $start, ?int $length = null): string
    {
        return mb_substr((string)$value, $start, $length, static::$encoding);
    }


    /**
     * Удаляет из строки с позиции start длиной length
     * @see ./tests/HelperStringTrait/HelperStringDeleteTest.php
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
     * @see ./tests/HelperStringTrait/HelperStringCutTest.php
     *
     * @param int|float|string|null $value
     * @param int $start
     * @param int|null $length
     * @return string
     */
    public static function stringCut(int|float|string|null &$value, int $start, ?int $length = null): string
    {
        $value = (string)$value;
        $result = static::stringCopy($value, $start, $length);
        $value = static::stringDelete($value, $start, $length);

        return $result;
    }


    /**
     * Вставляет подстроку в строку с позиции start
     * @see ./tests/HelperStringTrait/HelperStringPasteTest.php
     *
     * @param int|float|string|null $value
     * @param int|float|string $paste
     * @param int $start
     * @return string
     */
    public static function stringPaste(int|float|string|null $value, int|float|string $paste, int $start): string
    {
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
     * @see ./tests/HelperStringTrait/HelperStringChangeTest.php
     *
     * @param int|float|string|null $value
     * @param int|float|string $change
     * @param int $start
     * @return string
     */
    public static function stringChange(int|float|string|null $value, int|float|string $change, int $start): string
    {
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
     * Проверяет начало строки на совпадение
     * @see ./tests/HelperStringTrait/HelperStringStartsTest.php
     *
     * @param int|float|string|null $value
     * @param int|float|string|array|null $search
     * @return bool|string
     */
    //?!? 
    public static function stringStarts(int|float|string|null $value, int|float|string|array|null $search): bool|string
    {
        is_iterable($search) ?: $search = [$search];
        $search = array_filter($search);
        $value = (string)$value;

        foreach ($search ?? [] as $item) {
            $item = (string)$item;
            if ($item && str_starts_with($value, $item)) {
                return (string)$item;
            }
        }

        return false;
    }


    /**
     * Проверяет конец строки на совпадение
     * @see ./tests/HelperStringTrait/HelperStringEndsTest.php
     *
     * @param int|float|string|null $value
     * @param int|float|string|array|null $search
     * @return bool|string
     */
    //?!? 
    public static function stringEnds(int|float|string|null $value, int|float|string|array|null $search): bool|string
    {
        is_iterable($search) ?: $search = [$search];
        $search = array_filter($search);
        $value = (string)$value;

        foreach ($search ?? [] as $item) {
            $item = (string)$item;
            if ($item && str_ends_with($value, $item)) {
                return (string)$item;
            }
        }

        return false;
    }


    /**
     * Возвращает число с числительным названием
     * @see ./tests/HelperStringTrait/HelperStringPluralTest.php
     *
     * @param int|float|string $number
     * @param array $words
     * @param bool $includeNumber
     * @return string
     */
    public static function stringPlural(int|float|string $value, array $plurals, bool $includeValue = true): string
    {
        $numberString = (string)abs(floor(floatval($value)));
        $numberPart = (int)static::stringCopy($numberString, static::stringLength($numberString) - 2, 2);
        $numberMod = $numberPart % 10;

        return ($includeValue ? "{$value} " : "")
            . match (true) {
                $numberMod == 1 && $numberPart != 11 => (string)($plurals[1] ?? ''),
                $numberMod > 1 && $numberMod < 5 && !($numberPart > 11 && $numberPart < 15)
                => (string)($plurals[2] ?? ''),

                default => (string)($plurals[0] ?? ''),
            };
    }


    /**
     * Разбивает текст на части указанной длины по словам или строкам и возвращает массив строк
     * @see ./tests/HelperStringTrait/HelperStringBreakByLengthTest.php
     *
     * @param string $message
     * @param HelperStringBreakTypeEnum $breakType
     * @param int $partLengthMax
     * @param int|null $firstPartIsShort
     * @return array
     */
    //?!? 
    public static function stringBreakByLength(
        string $value,
        HelperStringBreakTypeEnum $breakType,
        int $partLengthMax = 4000,
        ?int $firstPartLength = null, // 1000
    ): array {
        $result = [];
        $currentText = '';
        $currentLength = 0;

        if (mb_strlen($value) <= ($firstPartLength ? min(1000, $partLengthMax) : $partLengthMax)) {
            $result[] = $value;

        } else {
            // Разбиваем сообщение на строки
            foreach (explode(PHP_EOL, $value) as $lineIndex => $line) {
                $lineLength = mb_strlen($line);
                // Разбиваем строку на слова
                $words = preg_split("/[\ ]+/", rtrim($line, CHR(9) . ' '));
                $break = ($lineIndex > 0) ? PHP_EOL : '';
                $breakLength = mb_strlen($break);
                $lineLengthMax = (count($result) === 0)
                    ? ($firstPartLength ? min($firstPartLength, $partLengthMax) : $partLengthMax)
                    : $partLengthMax;

                switch ($breakType) {
                    // Разбитие по словам
                    case HelperStringBreakTypeEnum::Word:
                        foreach ($words as $wordIndex => $word) {
                            $wordLength = mb_strlen($word);
                            $space = ($wordIndex > 0) ? ' ' : '';
                            $spaceLength = mb_strlen($space);

                            if ($currentLength + $breakLength + $spaceLength + $wordLength <= $lineLengthMax) {
                                $currentText .= "{$break}{$space}{$word}";
                                $currentLength += $spaceLength + $wordLength;

                            } else {
                                if ($breakLength + $currentLength <= $lineLengthMax) {
                                    $currentText .= $break;
                                }
                                if (substr($currentText, -mb_strlen(PHP_EOL)) == PHP_EOL) {
                                    $currentText = substr($currentText, 0, -1);
                                }
                                $result[] = $currentText;
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
                            $result[] = $currentText;
                            $currentText = $line;
                            $currentLength = $lineLength;
                        }
                        break;
                }
            }

            if ($currentText != '') {
                if (substr($currentText, -mb_strlen(PHP_EOL)) == PHP_EOL) {
                    $currentText = substr($currentText, 0, -1);
                }
                $result[] = $currentText;
            }
        }

        return $result;
    }


    /**
     * Соединяет значения values через разделитель delimiter
     * @see ./tests/HelperStringTrait/HelperStringConcatTest.php
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
                    ? static::stringConcat($delimiter, ...(array)$value)
                    : (string)$value,
                    $values,
                ),
            ),
        );
    }


    /**
     * Добавляет в начало строки префикс при выполнении условия condition
     * @see ./tests/HelperStringTrait/HelperStringAddPrefixTest.php
     *
     * @param int|float|string|null $value
     * @param int|float|string|null $prefix
     * @param mixed $condition
     * @return string
     */
    public static function stringAddPrefix(
        int|float|string|null $value,
        int|float|string|null $prefix,
        mixed $condition = true,
    ): string {
        !is_callable($condition) ?: $condition = $condition($value, $prefix);

        return (($prefix && (bool)$condition) ? (string)$prefix : '') . (string)$value;
    }


    /**
     * Добавляет в конец строки суффикс при выполнении условия condition
     * @see ./tests/HelperStringTrait/HelperStringAddSuffixTest.php
     *
     * @param int|float|string|null $value
     * @param int|float|string|null $suffix
     * @param mixed $condition
     * @return string
     */
    public static function stringAddSuffix(
        int|float|string|null $value,
        int|float|string|null $suffix,
        mixed $condition = true,
    ): string {
        !is_callable($condition) ?: $condition = $condition($value, $suffix);

        return (string)$value . (($suffix && $condition) ? (string)$suffix : '');
    }


    /**
     * Объединяет значения в одну строку с заменой предыдущих символов
     * @see ./tests/HelperStringTrait/HelperStringMergeTest.php
     *
     * @param mixed ...$values
     * @return string
     */
    public static function stringMerge(mixed ...$values): string
    {
        $result = '';

        foreach ($values as $value) {
            $result = (is_array($value) || is_object($value))
                ? static::stringMerge(...(array)$value)
                : static::stringChange($result, $value, 0);
        }

        return $result;
    }


    /**
     * Проверяет вхождение подстрок в строке и возвращает первое найденное искомое значение
     * @see ./tests/HelperStringTrait/HelperStringSearchAnyTest.php
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
                if ($result = static::stringSearchAny($value, ...(array)$search)) {
                    return $result;
                }

            } else if (!is_null($search) && $search !== '' && str_contains($value, (string)$search)) {
                return [$search];
            }
        }

        return [];
    }


    /**
     * Проверяет вхождение подстрок в строке и возвращает все найденные искомые значения
     * @see ./tests/HelperStringTrait/HelperStringSearchAllTest.php
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
                $result = [...$result, ...(static::stringSearchAll($value, ...(array)$search) ?: [])];

            } else if (!is_null($search) && $search !== '' && str_contains($value, (string)$search)) {
                $result[] = $search;
            }
        }

        return $result;
    }


    /**
     * Заменяет подстроки в строке
     * @see ./tests/HelperStringTrait/HelperStringReplaceTest.php
     *
     * @param int|float|string|null $value
     * @param int|float|string|array $searches
     * @param int|float|string|array|null $replaces
     * @return string
     */
    public static function stringReplace(
        int|float|string|null $value,
        int|float|string|array $searches,
        int|float|string|array|null $replaces = null,
    ): string {
        $value = (string)$value;

        if (!is_null($searches) && $searches !== '') {

            if (is_scalar($searches) && is_scalar($replaces)) {
                $value = str_replace($searches, $replaces, $value);

            } else if (is_scalar($searches) && is_array($replaces)) {
                $value = str_replace($searches, array_values($replaces)[0] ?? '', $value);

            } else if (is_array($searches)) {
                foreach ($searches as $key => $search) {
                    if (is_integer($key)) {
                        (is_null($search) || $search === '')
                            ?: $value = str_replace($search, $replaces[$key] ?? '', $value);

                    } else {
                        (is_null($key) || $key === '')
                            ?: $value = str_replace($key, $search ?? '', $value);
                    }
                }
            }

        }

        return $value;
    }
}
