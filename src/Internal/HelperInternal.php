<?php

declare(strict_types=1);

namespace Atlcom\Internal;

use Atlcom\Consts\HelperConsts as Consts;
use Atlcom\Enums\HelperNumberDeclensionEnum as Declension;
use Atlcom\Enums\HelperNumberEnumerationEnum as Enumeration;
use Atlcom\Enums\HelperNumberGenderEnum as Gender;
use Atlcom\Helper;
use Atlcom\Traits\HelperHashTrait;
use Atlcom\Traits\HelperStringTrait;
use InvalidArgumentException;

/**
 * Класс вспомогательных внутренних методов
 */
class HelperInternal
{
    use HelperHashTrait;
    use HelperStringTrait;


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
}
