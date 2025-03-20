<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Enums\HelperBreakTypeEnum;

/**
 * Трейт для работы со строками
 */
trait HelperStringTrait
{
    /**
     * Переводит строку в верхний регистр
     *
     * @param int|float|string|null $value
     * @return string
     */
    public static function stringUpper(int|float|string|null $value): string
    {
        return mb_strtoupper((string)$value, 'UTF-8');
    }


    /**
     * Переводит строку в нижний регистр
     *
     * @param int|float|string|null $value
     * @return string
     */
    public static function stringLower(int|float|string|null $value): string
    {
        return mb_strtolower((string)$value, 'UTF-8');
    }


    /**
     * Вырезает подстроку из строки
     *
     * @param int|float|string|null $string
     * @param int $start
     * @param int|null $length
     * @param string|null $encoding
     * @return string
     */
    public static function stringCopy(int|float|string|null $value, int $start, ?int $length = null, ?string $encoding = 'UTF-8'): string
    {
        return mb_substr((string)$value, $start, $length, $encoding);
    }


    //?!? stringCut
    //?!? stringPaste


    /**
     * Проверяет начало строки на совпадение
     *
     * @param int|float|string|null $value
     * @param int|float|string|array|null $search
     * @return bool|string
     */
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
     *
     * @param int|float|string|null $value
     * @param int|float|string|array|null $search
     * @return bool|string
     */
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
     * [Description for length]
     *
     * @param int|float|string|null $value
     * @param string|null $encoding
     * @return int
     */
    public static function length(int|float|string|null $value, ?string $encoding = null): int
    {
        return mb_strlen((string)$value, $encoding);
    }


    /**
     * Возвращает число с числительным названием
     *
     * @param int|float|string $number
     * @param array $words
     * @param bool $includeNumber
     * @return string
     */
    public static function plural(int|float|string $value, array $words, bool $includeNumber = true): string
    {
        $numberString = (string)abs(floor(floatval($value)));
        $numberPart = (int)static::stringCopy($numberString, static::length($numberString) - 2, 2);
        $numberMod = $numberPart % 10;

        return ($includeNumber ? "{$value} " : "")
            . match (true) {
                $numberMod == 1 && $numberPart != 11 => $words[1] ?? '',
                $numberMod > 1 && $numberMod < 5 && !($numberPart > 11 && $numberPart < 15) => $words[2] ?? '',

                default => $words[0] ?? '',
            };
    }


    /**
     * Разбивает текст на части указанной длины по словам или строкам и возвращает массив строк
     *
     * @param string $message
     * @param HelperBreakTypeEnum $breakType
     * @param int $partLengthMax
     * @param int|null $firstPartIsShort
     * @return array
     */
    public static function stringBreakByLength(
        string $value,
        HelperBreakTypeEnum $breakType,
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
                    case HelperBreakTypeEnum::Word:
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
                    case HelperBreakTypeEnum::Line:
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


    public static function stringConcat(int|float|string|null $delimiter, mixed ...$values): string
    {
        return implode((string)$delimiter, array_filter($values));
    }


    public static function stringAddPrefix(
        int|float|string|null $value,
        int|float|string|null $prefix,
        mixed $condition = null,
    ): string {
        is_callable($condition) ?: $condition = $condition($value, $prefix);

        return (($prefix && $condition) ? $prefix : '') . $value;
    }


    public static function stringAddSuffix(
        int|float|string|null $value,
        int|float|string|null $suffix,
        mixed $condition = null,
    ): string {
        is_callable($condition) ?: $condition = $condition($value, $suffix);

        return (($suffix && $condition) ? $suffix : '') . $value;
    }


    public static function stringMerge(): string
    {
        $s1 = '0001';
        $s2 = '11110';
        $s3 = 'a';
        $res = 'a1110';
    }


    public static function stringContains(int|float|string|null $value, int|float|string|array|null $search): string
    {
        return '';
    }
}
