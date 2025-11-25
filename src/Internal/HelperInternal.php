<?php

declare(strict_types=1);

namespace Atlcom\Internal;

use Atlcom\Consts\HelperConsts as Consts;
use Atlcom\Enums\HelperNumberDeclensionEnum as Declension;
use Atlcom\Enums\HelperNumberEnumerationEnum as Enumeration;
use Atlcom\Enums\HelperNumberGenderEnum as Gender;
use Atlcom\Helper;
use Atlcom\Traits\HelperHashTrait;
use Atlcom\Traits\HelperNumberTrait;
use Atlcom\Traits\HelperStringTrait;
use InvalidArgumentException;
use ReflectionMethod;
use ReflectionProperty;
use Throwable;

/**
 * @internal
 * Класс вспомогательных внутренних методов
 */
class HelperInternal
{
    use HelperHashTrait;
    use HelperStringTrait;
    use HelperNumberTrait;


    /**
     * Возвращает число по номеру разряда в виде строки прописью
     * 
     * @param string $value
     * @param int $part
     * @param Declension $declension
     * @param Gender $gender
     * @param Enumeration $enumeration
     */
    public static function internalNumberToString(
        ?string $value, // Значение разряда числа
        int $part, // Номер разряда числа
        Declension $declension,
        Gender $gender,
        Enumeration $enumeration,
        bool &$usePrev, // Продолжение прописи
    ): string {
        $value ??= '';
        $value = static::stringRepeat('0', 3 - mb_strlen($value)) . $value;
        $digitA = (int)$value[0];
        $digitB = (int)$value[1];
        $digitC = (int)$value[2];
        $decl = $declension->value;
        $gend = $gender->value;
        $enum = $enumeration->value;
        $declND = $digitC === 1 ? Declension::Nominative->value : Declension::Genitive->value;
        $declN = Declension::Nominative->value;
        $declG = Declension::Genitive->value;
        $gendFM = $part === 2 ? Gender::Female->value : Gender::Male->value;
        $result = '';

        switch ($part) {

            case 1:
                if (($digitC !== 0) && ($digitB !== 1)) {
                    $result = Consts::NUMBER_NAMES[$digitC][$enum]
                        . (
                            $enum === 0
                            ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X1[$digitC]]
                            : Consts::NUMBER_ORD[$decl][$gend][Consts::NUMBER_ORD_X1[$digitC]]
                        );
                }
                if ($digitB !== 0) {
                    switch ($digitB) {
                        case '1':
                            $result = ($digitC !== 0)
                                ? Consts::NUMBER_NAMES[(int)"{$digitB}{$digitC}"][$enum]
                                . (
                                    $enum === 0
                                    ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    : Consts::NUMBER_ORD[$decl][$gend][Consts::NUMBER_ORD_X10[$digitB - 1]]
                                )

                                : Consts::NUMBER_NAMES[(int)"{$digitB}{$digitC}"][$enum]
                                . (
                                    $enum === 0
                                    ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    : Consts::NUMBER_ORD[$decl][$gend][Consts::NUMBER_ORD_X10[$digitB - 1]]
                                );
                            break;

                        case 2:
                        case 3:
                        case 4:
                        case 5:
                        case 6:
                        case 7:
                        case 8:
                        case 9:
                            $result = ($digitC !== 0)
                                ? Consts::NUMBER_NAMES[18 + $digitB][0]
                                . (
                                    $enum === 0
                                    ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    : Consts::NUMBER_CASE_10[0][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                )
                                . ($result !== '' ? ' ' : '')
                                . $result

                                : Consts::NUMBER_NAMES[18 + $digitB][$enum]
                                . (
                                    $enum === 0
                                    ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    : Consts::NUMBER_ORD[$decl][$gend][Consts::NUMBER_ORD_X10[$digitB - 1]]
                                );
                            break;
                    }

                }

                if ($digitA !== 0) {
                    $result = ($result !== '')
                        ? Consts::NUMBER_NAMES[27 + $digitA][0]
                        . (
                            $enum === 0
                            ? Consts::NUMBER_CASE_100[$decl][Consts::NUMBER_CASE_X100[$digitA - 1]]
                            : Consts::NUMBER_CASE_100[0][Consts::NUMBER_CASE_X100[$digitA - 1]]
                        )
                        . ($result !== '' ? ' ' : '')
                        . $result

                        : Consts::NUMBER_NAMES[27 + $digitA][$enum]
                        . (
                            $enum === 0
                            ? Consts::NUMBER_CASE_100[$decl][Consts::NUMBER_CASE_X100[$digitA - 1]]
                            : Consts::NUMBER_ORD[$decl][$gend][Consts::NUMBER_ORD_X100[$digitA - 1]]
                        );
                }
                $usePrev = $result !== '';
                break;

            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
                if (($digitC !== 0) && ($digitB !== 1)) {
                    $result = $usePrev
                        ? Consts::NUMBER_NAMES[$digitC][Enumeration::Numerical->value]
                        . (
                            $enum === 0
                            ? Consts::NUMBER_CASE_10[$decl][$gendFM][Consts::NUMBER_CASE_X1[$digitC]]
                            : Consts::NUMBER_CASE_10[$declN][$gendFM][Consts::NUMBER_CASE_X1[$digitC]]
                        )

                        : (
                            $enum === 0
                            ? Consts::NUMBER_NAMES[$digitC][$enum]
                            : Consts::NUMBER_NAMES[$digitC][Enumeration::Numerical->value]
                        )
                        . (
                            $enum === 0
                            ? Consts::NUMBER_CASE_10[$decl][$gendFM][Consts::NUMBER_CASE_X1[$digitC]]
                            : Consts::NUMBER_CASE_10[$declND][$gendFM][Consts::NUMBER_CASE_X1[$digitC]]
                        );
                }

                if ($digitB !== 0) {
                    switch ($digitB) {
                        case '1':
                            if ($digitC !== 0) {
                                $result = $usePrev
                                    ? Consts::NUMBER_NAMES[(int)"{$digitB}{$digitC}"][Enumeration::Numerical->value]
                                    . (
                                        $enum === 0
                                        ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                        : Consts::NUMBER_CASE_10[$declN][$gendFM][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    )

                                    : Consts::NUMBER_NAMES[(int)"{$digitB}{$digitC}"][Enumeration::Numerical->value]
                                    . (
                                        $enum === 0
                                        ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                        : Consts::NUMBER_CASE_10[$declG][$gendFM][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    );

                            } else {
                                $result = $usePrev
                                    ? Consts::NUMBER_NAMES[(int)"{$digitB}{$digitC}"][Enumeration::Numerical->value]
                                    . (
                                        $enum === 0
                                        ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                        : Consts::NUMBER_CASE_10[$declN][$gendFM][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    )

                                    : Consts::NUMBER_NAMES[(int)"{$digitB}{$digitC}"][Enumeration::Numerical->value]
                                    . (
                                        $enum === 0
                                        ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                        : Consts::NUMBER_CASE_10[$declG][$gendFM][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    );
                            }
                            break;

                        case '2':
                        case '3':
                        case '4':
                        case '5':
                        case '6':
                        case '7':
                        case '8':
                        case '9':
                            if ($digitC !== 0) {
                                $result = $usePrev
                                    ? Consts::NUMBER_NAMES[18 + $digitB][0]
                                    . (
                                        $enum === 0
                                        ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                        : Consts::NUMBER_CASE_10[$declN][$gendFM][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    )
                                    . ($result !== '' ? ' ' : '')
                                    . $result

                                    : Consts::NUMBER_NAMES[18 + $digitB][0]
                                    . (
                                        $enum === 0
                                        ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                        : Consts::NUMBER_CASE_10[$declG][$gendFM][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    )
                                    . ($result !== '' ? ' ' : '')
                                    . $result;

                            } else {
                                $result = $usePrev
                                    ? Consts::NUMBER_NAMES[18 + $digitB][0]
                                    . (
                                        $enum === 0
                                        ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                        : Consts::NUMBER_CASE_10[$declN][$gendFM][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    )

                                    : Consts::NUMBER_NAMES[18 + $digitB][0]
                                    . (
                                        $enum === 0
                                        ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                        : Consts::NUMBER_CASE_10[$declG][$gendFM][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    );
                            }
                            break;
                    }
                }

                if ($digitA !== 0) {
                    if ($result !== '') {
                        $result = $usePrev
                            ? Consts::NUMBER_NAMES[27 + $digitA][0]
                            . (
                                $enum === 0
                                ? Consts::NUMBER_CASE_100[$decl][Consts::NUMBER_CASE_X100[$digitA - 1]]
                                : Consts::NUMBER_CASE_100[$declN][Consts::NUMBER_CASE_X100[$digitA - 1]]
                            )
                            . ($result !== '' ? ' ' : '')
                            . $result

                            : (
                                $enum === 0
                                ? Consts::NUMBER_NAMES[27 + $digitA][0]
                                : Consts::NUMBER_NAMES[27 + $digitA][$digitA === 1 ? 0 : 1]
                            )
                            . (
                                $enum === 0
                                ? Consts::NUMBER_CASE_100[$decl][Consts::NUMBER_CASE_X100[$digitA - 1]]
                                : (
                                    $digitA === 1
                                    ? Consts::NUMBER_CASE_100[$declG][Consts::NUMBER_CASE_X100[$digitA - 1]]
                                    : ''
                                )
                            )
                            . ($result !== '' ? ' ' : '')
                            . $result;

                    } else {
                        $result = $usePrev

                            ? Consts::NUMBER_NAMES[27 + $digitA][0]
                            . (
                                $enum === 0
                                ? Consts::NUMBER_CASE_100[$decl][Consts::NUMBER_CASE_X100[$digitA - 1]]
                                : Consts::NUMBER_CASE_100[$declN][Consts::NUMBER_CASE_X100[Consts::NUMBER_CASE_X100[$digitA - 1] - 1]]
                            )

                            : (
                                $enum === 0
                                ? Consts::NUMBER_NAMES[27 + $digitA][0]
                                : Consts::NUMBER_NAMES[27 + $digitA][$digitA === 1 ? 0 : 1]
                            )
                            . (
                                $enum === 0
                                ? Consts::NUMBER_CASE_100[$decl][Consts::NUMBER_CASE_X100[$digitA - 1]]
                                : (
                                    $digitA === 1
                                    ? Consts::NUMBER_CASE_100[$declG][Consts::NUMBER_CASE_X100[$digitA - 1]]
                                    : ''
                                )
                            )
                            . ($result !== '' ? ' ' : '')
                            . $result;
                    }
                }

                if ($value !== '000') {
                    $result = $result
                        . (($result !== '' && $part > 1) ? ' ' : '')
                        . (
                            $usePrev
                            ? Consts::NUMBER_NAMES[35 + $part][Enumeration::Numerical->value]
                            : Consts::NUMBER_NAMES[35 + $part][$enum]
                        )
                        . (
                            $enum === 0
                            ? (
                                $part === 2
                                ? (
                                    $usePrev
                                    ? static::stringPlural(
                                        $value,
                                        [
                                            Consts::NUMBER_CASE_X1K[$declG][0],
                                            Consts::NUMBER_CASE_X1K[$decl][1],
                                            Consts::NUMBER_CASE_X1K[$decl][2],
                                        ],
                                        false,
                                    )
                                    : static::stringPlural(
                                        $value,
                                        [
                                            Consts::NUMBER_CASE_X1K[$decl][0],
                                            Consts::NUMBER_CASE_X1K[$decl][1],
                                            Consts::NUMBER_CASE_X1K[$decl][2],
                                        ],
                                        false,
                                    )
                                )

                                : (
                                    $usePrev
                                    ? static::stringPlural(
                                        $value,
                                        [
                                            Consts::NUMBER_CASE_X1M[$declG][0],
                                            Consts::NUMBER_CASE_X1M[$decl][1],
                                            Consts::NUMBER_CASE_X1M[$decl][2],
                                        ],
                                        false,
                                    )
                                    : static::stringPlural(
                                        $value,
                                        [
                                            Consts::NUMBER_CASE_X1M[$decl][0],
                                            Consts::NUMBER_CASE_X1M[$decl][1],
                                            Consts::NUMBER_CASE_X1M[$decl][2],
                                        ],
                                        false,
                                    )
                                )
                            )

                            : (
                                $usePrev
                                ? (
                                    $part === 2
                                    ? static::stringPlural(
                                        $value,
                                        [
                                            Consts::NUMBER_CASE_X1K[0][0],
                                            Consts::NUMBER_CASE_X1K[0][1],
                                            Consts::NUMBER_CASE_X1K[0][2],
                                        ],
                                        false,
                                    )
                                    : static::stringPlural(
                                        $value,
                                        [
                                            Consts::NUMBER_CASE_X1M[0][0],
                                            Consts::NUMBER_CASE_X1M[0][1],
                                            Consts::NUMBER_CASE_X1M[0][2],
                                        ],
                                        false,
                                    )
                                )

                                : (
                                    $part === 2
                                    ? Consts::NUMBER_ORD[$decl][$gend][1]
                                    : (
                                        $usePrev
                                        ? static::stringPlural(
                                            $value,
                                            [
                                                Consts::NUMBER_CASE_X1M[0][0],
                                                Consts::NUMBER_CASE_X1M[0][1],
                                                Consts::NUMBER_CASE_X1M[0][2],
                                            ],
                                            false,
                                        )
                                        : Consts::NUMBER_ORD[$decl][$gend][1]
                                    )
                                )
                            )
                        );
                }

                if (!$usePrev) {
                    $usePrev = $result !== '';
                }
                break;
        }

        return $result;
    }


    /**
     * Задает случайный генератор
     *
     * @return float
     */
    public static function internalMakeRandom(): float
    {
        [$usec, $sec] = explode(' ', microtime());

        return (float)$sec + (float)$usec * 100000;
    }


    /**
     * Упаковывает строку из чисел
     *
     * @param mixed $value
     * @return string
     */
    public static function internalShrink(string $value): string
    {
        $result = '';
        if ($value == '') {
            return $result;
        }

        for ($aa = 0; $aa < mb_strlen($value); $aa++) {
            $code = mb_substr($value, $aa, 2);
            if (mb_strlen($code) < 2) {
                $result .= $value[$aa];

            } else if (($code >= '65' && $code <= '90') || ($code >= '97' && $code <= '99')) {
                $result .= mb_chr((int)$code);
                $aa++;

            } else if ($code >= '00' && $code <= '22') {
                $result .= mb_chr((int)$code + 100);
                $aa++;

            } else {
                $result .= $value[$aa];
            }
        }
        return $result;
    }


    /**
     * Распаковывает строку из чисел
     *
     * @param string $value
     * @return string
     */
    public static function internalUnShrink(string $value): string
    {
        $result = '';
        if ($value == '') {
            return $result;
        }

        for ($aa = 0; $aa < mb_strlen($value); $aa++) {
            $code = mb_ord($value[$aa]);
            if (($code >= 65 && $code <= 90) || ($code >= 97 && $code <= 99)) {
                $result .= $code;

            } else if ($code >= 100 && $code <= 122) {
                $result .= str_pad((string)($code - 100), 2, "0", STR_PAD_LEFT);

            } else {
                $result .= $value[$aa];
            }
        }
        return $result;
    }


    /**
     * Добавляет хеш в строку
     * 
     * @param string $value
     * @return string
     */
    public static function internalHash(string $value): string
    {
        $chunkLength = (int)floor(mb_strlen($value) / 32);
        if ($chunkLength === 0) {
            return $value;
        }

        $hash = static::hashXxh128($value);
        $chunks = array_chunk(str_split($value), $chunkLength, false);
        $chunkIndex = 0;

        foreach ($chunks as &$chunk) {
            $chunk[] = $hash[$chunkIndex++] ?? '';
            if ($chunkIndex >= 32) {
                break;
            }
        }

        $chunks = array_map(static fn ($chunk) => implode('', $chunk), $chunks);

        return implode('', $chunks);
    }


    /**
     * Удаляет хеш из строки
     * 
     * @param string $value
     * @return string
     */
    public static function internalUnHash(string $value): string
    {
        $chunkLength = (int)floor(mb_strlen($value) / 32);
        if ($chunkLength === 0) {
            return $value;
        }

        $hash = '';
        $chunks = array_chunk(str_split($value), $chunkLength, false);
        $chunkIndex = 0;

        foreach ($chunks as &$chunk) {
            $hash .= array_pop($chunk);
            $chunkIndex++;
            if ($chunkIndex >= 32) {
                break;
            }
        }

        $chunks = array_map(static fn ($chunk) => implode('', $chunk), $chunks);
        $result = implode('', $chunks);

        return static::hashXxh128($result) === $hash ? $result : '';
    }


    /**
     * Обработка символьного класса
     *
     * @param string $value
     * @return array
     */
    public static function internalParseCharacterClass(string $value): array
    {
        $chars = [];
        $i = 0;
        $len = mb_strlen($value);

        while ($i < $len) {
            if (mb_substr($value, $i, 1) === '\\') {
                // Экранированный символ
                $chars[] = mb_substr($value, $i + 1, 1);
                $i += 2;

            } elseif ($i + 2 < $len && mb_substr($value, $i + 1, 1) === '-') {
                // Диапазон символов
                $start = mb_substr($value, $i, 1);
                $end = mb_substr($value, $i + 2, 1);
                $chars = array_merge($chars, static::internalRange($start, $end));
                $i += 3;

            } else {
                // Одиночный символ
                $chars[] = mb_substr($value, $i, 1);
                $i++;
            }
        }

        return array_unique($chars);
    }


    /**
     * Возвращает массив символов между start и end
     *
     * @param mixed $start
     * @param mixed $end
     * @return array
     */
    public static function internalRange($start, $end): array
    {
        if ($start === $end) {
            return [$start];
        }

        $result = [];
        [, $_start, $_end] = unpack("N*", mb_convert_encoding("{$start}{$end}", "UTF-32BE", "UTF-8"));
        $_offset = $_start < $_end ? 1 : -1;
        $_current = $_start;
        while ($_current != $_end) {
            $result[] = mb_convert_encoding(pack("N*", $_current), "UTF-8", "UTF-32BE");
            $_current += $_offset;
        }
        $result[] = $end;

        return $result;
    }


    /**
     * Возвращает кодирование код символа
     *
     * @param int $value
     * @return string
     */
    public static function internalDigitEncode(int $value): string
    {
        return ($value < 26) ? chr($value + 97) : chr($value + 22);
    }


    /**
     * Возвращает декодирование символа в код
     *
     * @param string $value
     * @return int
     */
    public static function internalDigitDecode(string $value): int
    {
        $cp = ord($value);

        return match (true) {
            ($cp >= 0x30 && $cp <= 0x39) => $cp - 22, // 0-9
            ($cp >= 0x41 && $cp <= 0x5A) => $cp - 65, // A-Z
            ($cp >= 0x61 && $cp <= 0x7A) => $cp - 97, // a-z

            default => throw new InvalidArgumentException("Недопустимый символ: {$value}"),
        };
    }


    /**
     * Адаптация смещения для корректировки кодирования
     * Динамически регулирует параметры кодирования/декодирования
     *
     * @param int $value - начальная коррекция
     * @param int $numberPoints - количество уже обработанных символов
     * @param bool $isFirst
     * @return int
     */
    public static function internalAdapt(int $value, int $numberPoints, bool $isFirst): int
    {
        $value = $isFirst ? (int)($value / 700) : (int)($value / 2);
        $value += (int)($value / $numberPoints);

        for ($k = 0; $value > 455; $k += 36) {
            $value = (int)($value / 35);
        }

        return (int)($k + (36 * $value) / ($value + 38));
    }


    /**
     * Возвращает кодирование русских доменов в Punycode
     *
     * @param string $value
     * @return string
     */
    public static function internalPunycodeEncode(string $value): string
    {
        $result = '';
        $chars = preg_split('//u', $value, -1, PREG_SPLIT_NO_EMPTY);
        $n = 128;
        $delta = 0;
        $bias = 72;
        $basic = [];

        // Отделяем базовые символы
        foreach ($chars as $c) {
            !(ord($c) < 128) ?: $basic[] = ord($c);
        }

        $basic = array_unique($basic);
        sort($basic);

        $result = implode('', array_map('chr', $basic));
        $h = $b = count($basic);
        !($b > 0) ?: $result .= '-';

        // Кодирование не базовых символов
        $codePoints = [];
        foreach ($chars as $c) {
            $code = mb_ord($c, 'UTF-8');
            if ($code >= 128) $codePoints[$code] = true;
        }

        $codePoints = array_keys($codePoints);
        sort($codePoints);
        $valueLength = Helper::stringLength($value);

        while ($h < $valueLength) {
            $m = PHP_INT_MAX;
            foreach ($codePoints as $c) {
                !($c >= $n && $c < $m) ?: $m = $c;
            }

            $delta += ($m - $n) * ($h + 1);
            $n = $m;

            foreach ($chars as $char) {
                $code = mb_ord($char, 'UTF-8');
                if ($code < $n && ++$delta === PHP_INT_MAX) {
                    break 2;
                }
                if ($code === $n) {
                    for ($q = $delta, $k = 36; ; $k += 36) {
                        $t = ($k <= $bias) ? 1 : (($k >= $bias + 26) ? 26 : $k - $bias);

                        if ($q < $t) {
                            break;
                        }

                        $result .= static::internalDigitEncode($t + ($q - $t) % (36 - $t));
                        $q = (int)(($q - $t) / (36 - $t));
                    }

                    $result .= static::internalDigitEncode($q);
                    $bias = static::internalAdapt($delta, $h + 1, $h === $b);
                    $delta = 0;
                    $h++;
                }
            }

            $delta++;
            $n++;
        }

        return "xn--{$result}";
    }


    /**
     * Возвращает декодирование русских доменов из Punycode
     *
     * @param string $value
     * @return string
     */
    public static function internalPunycodeDecode(string $value): string
    {
        $n = 128;
        $index = 0;
        $bias = 72;
        $output = [];

        // Разделение базовых и расширенных символов
        $lastDash = strrpos($value, '-');
        $basic = ($lastDash !== false) ? substr($value, 0, $lastDash) : '';
        $value = ($lastDash !== false) ? substr($value, $lastDash + 1) : $value;

        // Обработка базовых символов
        for ($j = 0; $j < strlen($basic); $j++) {
            $output[] = ord($basic[$j]);
        }

        $count = ($lastDash !== false) ? count($output) : 0;

        // Декодирование расширенных символов
        while (!empty($value)) {
            $indexOld = $index;
            $w = 1;

            for ($k = 36; ; $k += 36) {
                $digit = static::internalDigitDecode($value[0]);
                $value = substr($value, 1);
                $index += $digit * $w;
                $t = ($k <= $bias) ? 1 : (($k >= $bias + 26) ? 26 : $k - $bias);

                if ($digit < $t) {
                    break;
                }

                $w *= 36 - $t;
            }

            $bias = static::internalAdapt($index - $indexOld, $count + 1, $indexOld === 0);
            $n += (int)($index / ($count + 1));
            $index %= $count + 1;

            array_splice($output, $index, 0, $n);
            $index++;
            $count++;
        }

        // Преобразование кодовых точек в UTF-8
        $result = '';
        foreach ($output as $code) {
            $result .= mb_chr($code);
        }

        return $result;
    }


    /**
     * Возвращает массив индексов частей между скобками
     *
     * @param string|null $value
     * @param string $left
     * @param string $right
     * @return array
     */
    public static function internalBrackets(string|null $value, string $left, string $right): array
    {
        $cacheKey = Helper::hashXxh128(__CLASS__ . __FUNCTION__ . "{$value}{$left}{$right}");

        return $value
            ? Helper::cacheRuntime($cacheKey, static function () use (&$value, &$left, &$right) {
                $value = (string)$value;
                $tokens = $stack = $brackets = [];
                $leftLen = static::stringLength($left);
                $rightLen = static::stringLength($right);
                $valueLen = static::stringLength($value);
                $currentPos = 0;

                while ($currentPos < $valueLen) {
                    $nextLeft = mb_strpos($value, $left, $currentPos);
                    $nextRight = mb_strpos($value, $right, $currentPos);

                    if ($nextLeft === false && $nextRight === false) {
                        break;

                    } else if ($nextLeft === false) {
                        $minPos = $nextRight;
                        $type = 'right';

                    } else if ($nextRight === false) {
                        $minPos = $nextLeft;
                        $type = 'left';

                    } else {
                        if ($nextLeft < $nextRight) {
                            $minPos = $nextLeft;
                            $type = 'left';

                        } else {
                            $minPos = $nextRight;
                            $type = 'right';
                        }
                    }

                    $tokens[] = ['type' => $type, 'pos' => $minPos];
                    $currentPos = $minPos + ($type === 'left' ? $leftLen : $rightLen);
                }

                foreach ($tokens as $token) {
                    if ($token['type'] === 'left') {
                        array_push($stack, $token['pos']);

                    } else {
                        if (empty($stack)) {
                            continue;
                        }

                        $start = array_pop($stack);
                        $brackets[] = ['start' => $start, 'end' => $token['pos']];
                    }
                }

                usort($brackets, static fn ($a, $b) => $a['start'] - $b['start']);

                return $brackets;
            })
            : [];
    }


    /**
     * Складывает строковые числа
     *
     * @param string $a
     * @param string $b
     * @return string
     */
    public static function internalNumberAdd(string $a, string $b): string
    {
        $a = str_pad($a, max(strlen($a), strlen($b)), '0', STR_PAD_LEFT);
        $b = str_pad($b, strlen($a), '0', STR_PAD_LEFT);
        $carry = 0;
        $res = '';

        for ($i = strlen($a) - 1; $i >= 0; $i--) {
            $sum = (int)$a[$i] + (int)$b[$i] + $carry;
            $carry = intdiv($sum, 10);
            $res = ($sum % 10) . $res;
        }

        return $carry ? "{$carry}{$res}" : $res;
    }


    /**
     * Вычитает строковые числа
     *
     * @param string $a
     * @param string $b
     * @return string
     */
    public static function internalNumberSubtract(string $a, string $b): string
    {
        $neg = false;
        if (static::internalNumberCompare($a, $b) < 0) {
            [$a, $b] = [$b, $a];
            $neg = true;
        }

        $b = str_pad($b, strlen($a), '0', STR_PAD_LEFT);
        $carry = 0;
        $res = '';

        for ($i = strlen($a) - 1; $i >= 0; $i--) {
            $diff = (int)$a[$i] - (int)$b[$i] - $carry;
            if ($diff < 0) {
                $diff += 10;
                $carry = 1;

            } else {
                $carry = 0;
            }

            $res = "{$diff}{$res}";
        }

        return $neg ? ('-' . ltrim($res, '0')) : (ltrim($res, '0') ?: '0');
    }


    /**
     * Умножает строковые числа
     *
     * @param string $a
     * @param string $b
     * @param int $precision
     * @return string
     */
    public static function internalNumberMultiply(string $a, string $b, int $precision): string
    {
        $neg = ($a[0] === '-') ^ ($b[0] === '-');
        $a = ltrim($a, '+-');
        $b = ltrim($b, '+-');
        $aDec = Helper::numberDecimalDigits($a, false);
        $bDec = Helper::numberDecimalDigits($b, false);
        $a = str_replace('.', '', $a);
        $b = str_replace('.', '', $b);
        $res = array_fill(0, strlen($a) + strlen($b), 0);

        for ($i = strlen($a) - 1; $i >= 0; $i--) {
            for ($j = strlen($b) - 1; $j >= 0; $j--) {
                $res[$i + $j + 1] += (int)$a[$i] * (int)$b[$j];
            }
        }

        for ($i = count($res) - 1; $i > 0; $i--) {
            $res[$i - 1] += intdiv($res[$i], 10);
            $res[$i] %= 10;
        }

        $result = ltrim(implode('', $res), '0') ?: '0';
        $precisionDigits = $aDec + $bDec;

        return ($neg ? '-' : '') . static::internalNumberInsertDecimal($result, $precisionDigits);
    }


    /**
     * Делит строковые числа
     *
     * @param string $a
     * @param string $b
     * @param int $precision
     * @return string
     */
    public static function internalNumberDivide(string $a, string $b, int $precision): string
    {
        if (static::internalNumberIsZero($b)) {
            return 'NaN';
        }

        $neg = ($a[0] === '-') ^ ($b[0] === '-');
        $a = ltrim($a, '+-');
        $b = ltrim($b, '+-');

        $aDec = Helper::numberDecimalDigits($a, false);
        $bDec = Helper::numberDecimalDigits($b, false);

        $aInt = str_replace('.', '', $a);
        $bInt = str_replace('.', '', $b);

        // Выравниваем масштаб чисел, чтобы дальше работать с целыми значениями
        if ($aDec > $bDec) {
            $bInt .= str_repeat('0', $aDec - $bDec);
        } elseif ($bDec > $aDec) {
            $aInt .= str_repeat('0', $bDec - $aDec);
        }

        $aInt = ltrim($aInt, '0');
        $bInt = ltrim($bInt, '0');

        if ($aInt === '') {
            $aInt = '0';
        }

        if ($bInt === '') {
            return 'NaN';
        }

        $precision = max($precision, 0);
        $dividend = $aInt . str_repeat('0', $precision);
        $res = '';
        $rem = '';

        for ($i = 0; $i < strlen($dividend); $i++) {
            $rem .= $dividend[$i];
            $q = '0';

            while (static::internalNumberCompare($rem, $bInt) >= 0) {
                $rem = static::internalNumberSubtract($rem, $bInt);
                $q++;
            }

            $res .= $q;
        }

        $res = ltrim($res, '0') ?: '0';
        $result = static::internalNumberInsertDecimal($res, $precision);

        return $neg && $result !== '0' ? "-{$result}" : $result;
    }


    /**
     * Сравнивает строковые числа
     *
     * @param string $a
     * @param string $b
     * @return int
     */
    public static function internalNumberCompare(string $a, string $b): int
    {
        $a = ltrim($a, '0');
        $b = ltrim($b, '0');

        if (strlen($a) > strlen($b)) {
            return 1;
        }

        if (strlen($a) < strlen($b)) {
            return -1;
        }

        return strcmp($a, $b);
    }


    /**
     * Добавляет нули к строковому числу
     *
     * @param string $num
     * @param int $pos
     * @return string
     */
    public static function internalNumberInsertDecimal(string $num, int $pos): string
    {
        if ($pos <= 0) return $num;
        $num = str_pad($num, $pos + 1, '0', STR_PAD_LEFT);

        return substr($num, 0, -$pos) . '.' . substr($num, -$pos);
    }


    /**
     * Округляет строковое число
     *
     * @param string $num
     * @param int $precision
     * @return string
     */
    public static function internalNumberRoundStr(string $num, int $precision): string
    {
        if (!str_contains($num, '.')) return $num;
        [$int, $dec] = explode('.', $num);
        if (strlen($dec) <= $precision) return $num;
        $roundDigit = (int)($dec[$precision] ?? '0');
        $dec = substr($dec, 0, $precision);

        if ($roundDigit >= 5) {
            $inc = '0.' . str_pad('1', $precision, '0', STR_PAD_LEFT);
            $num = static::internalNumberAdd(str_replace('.', '', "{$int}{$dec}"), str_replace('.', '', $inc));
            $num = static::internalNumberInsertDecimal($num, $precision);

        } else {
            $num = "{$int}.{$dec}";
        }

        return $num;
    }


    /**
     * Удаляет нули справа у строкового числа
     *
     * @param string $num
     * @return string
     */
    public static function internalNumberTrimTrailingZeros(string $num): string
    {
        if (str_contains($num, '.')) {
            $num = rtrim(rtrim($num, '0'), '.');
        }
        return $num === '-0' ? '0' : $num;
    }


    /**
     * Проверяет строковое число на ноль
     *
     * @param string $value
     * @return bool
     */
    public static function internalNumberIsZero(string $value): bool
    {
        return ltrim(str_replace('.', '', $value), '0') === '';
    }


    /**
     * Возвращает значение параметра по названию ключа из .env файла в корне или из указанного в file
     * @see ../../tests/HelperInternal/HelperInternalEnvTest.php
     *
     * @param string|null $value
     * @param string|null $file
     * @return mixed
     */
    public static function internalEnv(string|null $value, ?string $file = null): mixed
    {
        $cacheKey = __CLASS__ . __FUNCTION__ . static::hashXxh128($file ?? '');

        $env = Helper::cacheRuntime($cacheKey, static function () use ($file) {
            $env = [];
            $file ??= Helper::pathRoot() . '/.env';
            $fileData = (is_file($file) && file_exists($file)) ? file_get_contents($file) : $file;
            $lines = preg_split('/\R/', $fileData);
            $buffer = '';
            $key = null;
            $in_multiline = false;
            $quote_type = null;

            foreach ($lines as $line) {
                $trimmed = ltrim($line);
                if (($trimmed[0] ?? null) === '#') {
                    continue;
                }

                if ($in_multiline) {
                    $buffer .= "\n{$line}";
                    if (
                        ($quote_type === '"' && Helper::stringEnds($line, '"')) ||
                        ($quote_type === "'" && Helper::stringEnds($line, "'"))
                    ) {
                        $valueBuf = $buffer;
                        if (
                            ($quote_type === '"' && Helper::stringStarts($buffer, '"') && Helper::stringEnds($buffer, '"')) ||
                            ($quote_type === "'" && Helper::stringStarts($buffer, "'") && Helper::stringEnds($buffer, "'"))
                        ) {
                            $valueBuf = substr($buffer, 1, -1);
                        }
                        // Убедимся, что пустые строки внутри кавычек сохраняются
                        if ((Helper::stringStarts($valueBuf, '"') && Helper::stringEnds($valueBuf, '"')) || (Helper::stringStarts($valueBuf, "'") && str_ends_with($valueBuf, "'"))) {
                            $valueBuf = preg_replace('/\\n/', "\n", $valueBuf);
                        }
                        // Нормализация символов новой строки
                        $valueBuf = Helper::stringReplace($valueBuf, "\r\n", "\n");
                        // Убедимся, что символы новой строки и пробелы обрабатываются корректно
                        $valueBuf = preg_replace("/\r\n|\r/", "\n", $valueBuf);
                        $env[$key] = $valueBuf;
                        $in_multiline = false;
                        $buffer = '';
                        $key = null;
                        $quote_type = null;
                    }

                    continue;
                }

                if (mb_strpos($line, '=') === false) {
                    continue;
                }

                [$k, $v] = explode('=', $line, 2);
                $k = trim($k);
                $v = ltrim($v);

                // Удаление лишних пробелов вокруг значений
                $v = trim($v);

                if (($pos = mb_strpos($v, ' #')) !== false) {
                    $v = mb_substr($v, 0, $pos);
                }

                // Многострочные значения
                if (
                    (Helper::stringStarts($v, '"') && !Helper::stringEnds($v, '"')) ||
                    (Helper::stringStarts($v, "'") && !Helper::stringEnds($v, "'"))
                ) {
                    $in_multiline = true;
                    $key = $k;
                    $buffer = $v;
                    $quote_type = $v[0] ?? null;

                    continue;
                }

                $env[$k] = $v;
            }

            // Рекурсивный резолвер с защитой от зацикливания
            $resolve = function ($name, $value, $env, $stack = []) use (&$resolve) {
                // Убедимся, что значение не null перед вызовом preg_replace_callback
                if ($value === null) {
                    return null;
                }

                if ($value === "\$\{$name\}") {
                    // throw new Exception("Ссылка к самой себе: {$name}");
                    return null;
                }

                if (in_array($name, $stack, true)) {
                    // throw new Exception("Ссылка с зацикливанием: $name");
                    return null;
                }

                // Резолвинг ${KEY}
                $value = preg_replace_callback('/\$\{([A-Z0-9_]+)\}/i', function ($m) use ($env, $stack, $resolve, $name) {
                    $var = $m[1];
                    if (in_array($var, $stack, true)) {
                        return null;
                    }

                    if (isset($env[$var])) {
                        $resolved = $resolve($var, $env[$var], $env, array_merge($stack, [$name]));

                        return $resolved;
                        // return $resolved === null ? '' : $resolved;
                    }

                    return null;
                }, $value);

                if ($value === '') {
                    return null;
                }

                $len = strlen($value);
                $was_quoted = false;
                if (
                    $len >= 2 &&
                    (
                        (($value[0] ?? null) === '"' && ($value[$len - 1] ?? null) === '"') ||
                        (($value[0] ?? null) === "'" && ($value[$len - 1] ?? null) === "'")
                    )
                ) {
                    $value = substr($value, 1, -1);
                    $was_quoted = true;
                }

                // Если не было кавычек — trim пробелы и табы
                if (!$was_quoted) {
                    $value = trim($value, " \t");
                }

                $map = [
                    'true'    => true,
                    '(true)'  => true,
                    'false'   => false,
                    '(false)' => false,
                    'null'    => null,
                    '(null)'  => null,
                    'empty'   => '',
                    '(empty)' => '',
                ];
                $v = strtolower($value);
                if (!$was_quoted && array_key_exists($v, $map)) {
                    return $map[$v];
                }

                // Числа
                if (!$was_quoted && is_numeric($value)) {
                    if (strpos($value, '.') === false) {
                        if (strlen($value) > strlen((string)PHP_INT_MAX)) {
                            return $value;
                        }

                        return (int)$value;

                    } else {
                        [$int, $frac] = explode('.', $value);
                        if (strlen($int) > strlen((string)PHP_INT_MAX) || strlen($frac) > PHP_FLOAT_DIG) {
                            return $value;
                        }

                        return (float)$value;
                    }
                }

                // Массивы
                if (
                    $len >= 2 && (
                        (($value[0] ?? null) === '[' && ($value[$len - 1] ?? null) === ']') ||
                        (($value[0] ?? null) === "{" && ($value[$len - 1] ?? null) === "}")
                    )
                ) {
                    return json_decode($value, true, 512, JSON_INVALID_UTF8_IGNORE)
                        ?: json_decode(Helper::stringReplace($value, ["'" => '"']), true, 512, JSON_INVALID_UTF8_IGNORE)
                        ?: json_decode(
                            Helper::stringReplace(stripslashes($value), ["'" => '"']),
                            true,
                            512,
                            JSON_INVALID_UTF8_IGNORE,
                        )
                        ?: [];
                }

                return $value;
            };

            $result = [];
            foreach ($env as $k => $v) {
                try {
                    $result[$k] = $resolve($k, $v, $env, []);
                } catch (Throwable $e) {
                    $result[$k] = null;
                }
            }

            return $result;
        });

        return is_null($value) ? null : ($env[$value] ?? null);
    }


    /**
     * Возвращает массив с разложенными параметрами из phpdoc
     *
     * @param string|bool|null $phpdoc
     * @return array
     */
    public static function internalParsePhpDoc(string|bool|null $phpdoc): array
    {
        if (!$phpdoc) {
            return [];
        }

        $parsed = [];

        // Убираем /** и */
        $phpdoc = trim(preg_replace('/^\/\*\*|\*\/$/', '', $phpdoc));

        $lines = preg_split('/\r?\n/', $phpdoc);

        foreach ($lines as $line) {
            $line = trim(ltrim($line, "* \t"));

            if (empty($line)) {
                continue;
            }

            if (strpos($line, '@') === 0) {
                preg_match('/^@(\w+)\s+(.*)$/', $line, $matches);
                if ($matches) {
                    $tag = $matches[1];
                    $content = trim($matches[2]);

                    switch ($tag) {
                        case 'param':
                            preg_match('/^([\w\|\\\\\[\]]+)\s+\$(\w+)\s*(.*)$/', $content, $paramMatches);
                            if ($paramMatches) {
                                $type = $paramMatches[1];
                                $name = $paramMatches[2];
                                $description = trim($paramMatches[3] ?? '');

                                $parsed['param'][$name] = [
                                    'types'       => preg_split('/\|/', $type),
                                    'description' => $description,
                                ];
                            }
                            break;

                        case 'return':
                            preg_match('/^([\w\|\\\\\[\]]+)\s*(.*)$/', $content, $returnMatches);
                            if ($returnMatches) {
                                $type = $returnMatches[1];
                                $description = trim($returnMatches[2] ?? '');

                                $parsed['return'] = [
                                    'types'       => preg_split('/\|/', $type),
                                    'description' => $description,
                                ];
                            }
                            break;

                        default:
                            if (!isset($parsed[$tag])) {
                                $parsed[$tag] = [];
                            }
                            $parsed[$tag][] = $content;
                            break;
                    }
                }
            } else {
                $parsed['description'] ??= '';
                $parsed['description'] .= ($parsed['description'] ? ' ' : '') . $line;
            }
        }

        return $parsed;
    }


    /**
     * Определяет видимость свойства или метода
     *
     * @param mixed ReflectionProperty|ReflectionMethod $member
     * @return void
     */
    public static function internalVisibility(ReflectionProperty|ReflectionMethod $member): string
    {
        return match (true) {
            $member->isPrivate() => 'private',
            $member->isProtected() => 'protected',

            default => 'public',
        };
    }
}
