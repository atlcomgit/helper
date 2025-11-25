<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Consts\HelperConsts as Consts;
use Atlcom\Enums\HelperNumberDeclensionEnum as Declension;
use Atlcom\Enums\HelperNumberEnumerationEnum as Enumeration;
use Atlcom\Enums\HelperNumberGenderEnum as Gender;
use Atlcom\Internal\HelperInternal;

/**
 * Трейт для работы с числами
 * @mixin \Atlcom\Helper
 */
trait HelperNumberTrait
{
    /**
     * Возвращает число прописью на русском языке с учетом склонения по падежу, роду и числительному перечислению
     * @see ../../tests/HelperNumberTrait/HelperNumberToStringTest.php
     *
     * @param int|float|string|null $value
     * @param Declension $declension
     * @param Gender $gender
     * @param Enumeration $enumeration
     * @return string
     */
    public static function numberToString(
        int|float|string|null $value,
        Declension $declension = Declension::Nominative,
        Gender $gender = Gender::Male,
        Enumeration $enumeration = Enumeration::Numerical,
    ): string {
        $value = (string)$value;
        $numOrder = []; // Разложение числа по разрядам
        $useFrac = false; // Есть/нет предыдущего разряда
        $usePrev = false; // Продолжение прописи
        $getFrac = '';
        $result = '';

        $valueDigits = static::stringReplace($value, '/[^0-9+-.]/u', '');

        if ($valueDigits === '') {
            $valueString = static::stringReplace(static::stringSegment($value), '/[0-9+-.]/u', '');
            if (!static::regexpValidateUnicode($valueString)) {
                return '';
            }

            $value = static::numberFromString($valueString);

        } else {
            $value = $valueDigits;
        }

        if ($value === '') {
            return $result;
        }

        $numOrder = array_fill(0, 11, '');
        $symbols = static::stringSearchAll($value, ['+', '-', '.']);
        !in_array('+', $symbols) ?: $numOrder[0] .= '+';
        !in_array('-', $symbols) ?: $numOrder[0] .= '-';
        $value = static::stringReplace($value, ['+' => '', '-' => '']);

        if ($useFrac = (bool)in_array('.', $symbols)) {
            $getFrac = static::stringSplit($value, '.', 1);
            $value = static::stringSplit($value, '.', 0);
        }

        !($value == '' && ($numOrder[0] !== '' || $getFrac !== '')) ?: $value = '0';

        if ($value === '') {
            return $result;
        }

        if (static::stringLength($value) > 27) {
            return 'слишком большое целое число';
        }

        if (static::stringLength($getFrac) > 26) {
            return 'слишком большое дробное число';
        }

        $decl = $declension->value;
        $gend = $gender->value;
        $enum = $enumeration->value;

        if ($useFrac) {
            $gend = Gender::Female->value;
            $enum = Enumeration::Numerical->value;
        }

        $resultFrac = '';
        $zero = '';

        if (
            ($useFrac && ($value === '0' || (int)$value === 0))
            || (!$useFrac && $value === '000')
        ) {
            $numOrder[9] = match ($enum) {
                Enumeration::Numerical->value => Consts::NUMBER_NAMES[0][$enum] . Consts::NUMBER_CASE_10[$decl][$gend][0],
                Enumeration::Ordinal->value => Consts::NUMBER_NAMES[0][$enum] . Consts::NUMBER_ORD[$decl][$gend][0],
            };

            !$useFrac ?: $usePrev = true;

        } else {
            while ($value[0] === '0') {
                $zero .= ($zero !== '' ? ' ' : '')
                    . Consts::NUMBER_NAMES[0][$enum]
                    . Consts::NUMBER_CASE_10[$decl][$gend][0];
                $value = static::stringDelete($value, 0, 1);
            }

            $value = number_format((int)$value, 0, '.', ' ');
            $usePrev = false;
            $bb = static::stringCount($value, ' ') + 1;

            for ($aa = $bb - 1; $aa >= 0; $aa--) {
                $numOrder[11 - ($bb - $aa)] = HelperInternal::internalNumberToString(
                    static::stringSplit($value, ' ', $aa),
                    (int)($bb - $aa),
                    $declension,
                    $gender,
                    $enumeration,
                    $usePrev,
                );
            }
        }

        ($numOrder[0] === '') ?: $numOrder[0] = static::stringReplace($numOrder[0], [
            '+-' => 'плюс минус',
            '-'  => 'минус',
            '+'  => 'плюс',
        ]);

        for ($aa = 10; $aa >= 0; $aa--) {
            !(($aa === 1) && ($zero !== '')) ?: $result = $zero . ($result !== '' ? ' ' : '') . $result;
            $result = ($numOrder[$aa] !== '' ? $numOrder[$aa] . ($result !== '' ? ' ' : '') : '') . $result;
        }

        if ($useFrac) {
            $numOrder = array_fill(0, 11, '');

            if ($getFrac === '0' || (int)$getFrac === 0) {
                $numOrder[9] = match ($enum) {
                    Enumeration::Numerical->value => Consts::NUMBER_NAMES[0][$enum] . Consts::NUMBER_CASE_10[$decl][Gender::Female->value][0],
                    Enumeration::Ordinal->value => Consts::NUMBER_NAMES[0][$enum] . Consts::NUMBER_ORD[$decl][Gender::Female->value][0],
                };
            } else {
                $usePrev = false;
                $getFrac = number_format((int)$getFrac, 0, '.', ' ');
                $bb = static::stringCount($getFrac, ' ') + 1;

                for ($aa = $bb - 1; $aa >= 0; $aa--) {
                    $numOrder[11 - ($bb - $aa)] = HelperInternal::internalNumberToString(
                        static::stringSplit($getFrac, ' ', $aa),
                        (int)($bb - $aa),
                        $declension,
                        $gender,
                        $enumeration,
                        $usePrev,
                    );
                }
            }

            $result .=
                $usePrev
                ? ' ' . 'цел' . static::stringPlural(
                    $value,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                )
                : ''
            ;

            for ($aa = 10; $aa >= 0; $aa--) {
                $resultFrac = ($numOrder[$aa] !== '' ? $numOrder[$aa] . ($aa < 10 ? ' ' : '') : '') . $resultFrac;
            }

            $getFrac = match (static::stringLength(static::stringReplace($getFrac, ' ', ''))) {
                0, 1 => Consts::NUMBER_NAMES[10][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),

                2 => Consts::NUMBER_NAMES[28][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),

                3 => '' . Consts::NUMBER_NAMES[37][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                4 => 'десяти ' . Consts::NUMBER_NAMES[37][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                5 => 'ста ' . Consts::NUMBER_NAMES[37][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                6 => '' . Consts::NUMBER_NAMES[38][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                ),
                false,
                7 => 'десяти ' . Consts::NUMBER_NAMES[38][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                8 => 'ста ' . Consts::NUMBER_NAMES[38][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),

                9 => '' . Consts::NUMBER_NAMES[39][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                10 => 'десяти ' . Consts::NUMBER_NAMES[39][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                11 => 'ста ' . Consts::NUMBER_NAMES[39][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                12 => '' . Consts::NUMBER_NAMES[40][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                13 => 'десяти ' . Consts::NUMBER_NAMES[40][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                14 => 'ста ' . Consts::NUMBER_NAMES[40][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                15 => '' . Consts::NUMBER_NAMES[41][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                16 => 'десяти ' . Consts::NUMBER_NAMES[41][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                17 => 'ста ' . Consts::NUMBER_NAMES[41][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                18 => '' . Consts::NUMBER_NAMES[42][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                19 => 'десяти ' . Consts::NUMBER_NAMES[42][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                20 => 'ста ' . Consts::NUMBER_NAMES[42][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                21 => '' . Consts::NUMBER_NAMES[43][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                22 => 'десяти ' . Consts::NUMBER_NAMES[43][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                23 => 'ста ' . Consts::NUMBER_NAMES[43][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                24 => '' . Consts::NUMBER_NAMES[44][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                25 => 'десяти ' . Consts::NUMBER_NAMES[44][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
                26 => 'ста ' . Consts::NUMBER_NAMES[44][1]
                . static::stringPlural(
                    $getFrac,
                    [
                        Consts::NUMBER_INT_CASE[$decl][0],
                        Consts::NUMBER_INT_CASE[$decl][1],
                        Consts::NUMBER_INT_CASE[$decl][2],
                    ],
                    false,
                ),
            };

            $result .= ($resultFrac !== '') ? " {$resultFrac} {$getFrac}" : '';
        }

        return $result;
    }


    /**
     * Возвращает число из строки с числом прописью на русском языке
     * @see ../../tests/HelperNumberTrait/HelperNumberFromStringTest.php
     *
     * @param int|float|bool|string|null $value
     * @return int|float
     */
    public static function numberFromString(int|float|bool|string|null $value): int|float
    {
        $result = '';

        if (in_array($value, ['true', 'True', 'TRUE', true, '1', 1], true)) {
            $result = 1;

        } else if (in_array($value, ['false', 'False', 'FALSE', false, '0', 0, null], true)) {
            $result = 0;

        } else if (
            is_numeric($number = static::stringReplace($value, [',' => '.', ' ' => '']))
            || ($number = static::stringReplace($number, '/[^0-9.+-]/', ''))
        ) {
            $result = match (true) {
                is_null($number) => 0,
                is_integer($number), is_float($number) => $number,

                default => static::stringSearchAny($number, '.') ? (float)$number : (int)$number,
            };

        } else if ($value) {

            $value = static::stringLower($value);
            $nameNumbers = array_map(
                static fn ($v) => "{$v}*",
                static::arrayDot(Consts::NAME_NUMBERS),
            );
            $isMinus = false;
            $resultFrac = '';

            for ($aa = static::stringCount($value, ' '); $aa >= 0; $aa--) {
                $word = static::stringSplit($value, ' ', $aa);
                $name = static::arrayLast(static::stringSearchAll($word, $nameNumbers));

                if (!is_null($name)) {
                    $number = static::arrayFirst(
                        array_keys(static::arraySearchKeysAndValues($nameNumbers, '*', $name)),
                    );
                    $number = static::stringSplit($number, '.', 0);

                    // $number = str_pad($number, static::stringLength($result), '0', STR_PAD_LEFT);
                    // $result = str_pad($result, static::stringLength($number), '0', STR_PAD_LEFT);
                    // $result = static::stringMerge($number, $result);

                    $result = static::numberCalculate(($result ?: '0') . '+' . ($number ?: '0'), 0);
                    // $result = match (true) {
                    //     static::stringLength($number) > static::stringLength($result)
                    //     => static::stringChange($number, $result, -static::stringLength($result)),

                    //     static::stringLength($number) < static::stringLength($result)
                    //     => static::stringChange($result, $number, -static::stringLength($number)),

                    //     default => $result,
                    // };


                } else if (static::stringSearchAny($word, ['цел*'])) {
                    $resultFrac = $result;
                    $result = '';

                } else if (static::stringSearchAny($word, ['минус', '-'])) {
                    $isMinus = true;
                }
            }

            $result ?: $result = '0';
            !$resultFrac ?: $resultFrac = static::stringDelete($resultFrac, 0, 1);
            $result = $resultFrac ? (float)"{$result}.{$resultFrac}" : (int)$result;
            !$isMinus ?: $result *= -1;
        } else {
            $result = 0;
        }

        return $result;
    }


    /**
     * Возвращает строку с результатом калькуляции значений в строке
     * @see ../../tests/HelperNumberTrait/HelperNumberCalculateTest.php
     *
     * @param string|null $value
     * @param int|string|null $precision
     * @return string
     */
    public static function numberCalculate(?string $value, int|string|null $precision = 20): ?string
    {
        $value = (string)$value;
        $precision = (int)($precision ?? 20);

        // Унарный минус перед скобками
        $value = preg_replace('/(?<![\d\)])-\s*\(/', '-1*(', $value) ?? '';
        // Неявное умножение: число перед скобкой
        $value = preg_replace('/(\d)\s*\(/', '$1*(', $value) ?? '';
        // Неявное умножение: скобка перед числом
        $value = preg_replace('/\)\s*(\d)/', ')*$1', $value) ?? '';
        // Неявное умножение: скобка перед скобкой
        $value = preg_replace('/\)\s*\(/', ')*(', $value) ?? '';
        // Неявное умножение: число перед числом (опционально, если пробел между числами)
        // $value = preg_replace('/(\d)\s+(\d)/', '$1*$2', $value) ?? '';
        // Удаляем пробелы
        $value = trim(static::stringReplace($value, [' ' => '', ',' => '.']));
        // Удаляем дубликаты операторов
        $value = static::stringDeleteMultiples($value, ['-', '+', '*', '/']);

        for ($aa = static::bracketCount($value, '(', ')') - 1; $aa >= 0; $aa--) {
            $bracketBlock = static::bracketCopy($value, '(', ')', $aa);
            $bracketBlockCalculate = static::numberCalculate($bracketBlock, $precision);
            $value = static::bracketReplace($value, '(', ')', $aa, $bracketBlockCalculate);
        }

        $tokens = [];
        $num = '';
        for ($i = 0, $l = strlen($value); $i < $l; $i++) {
            $c = $value[$i];
            if (ctype_digit($c) || $c === '.') {
                $num .= $c;
            } elseif ($c === '-' && ($i === 0 || in_array($value[$i - 1], ['+', '-', '*', '/']))) {
                $num .= $c;
            } else {
                if ($num !== '') {
                    !(static::stringStarts($num, '.')) ?: $num = "0{$num}";
                    !(static::stringEnds($num, '.')) ?: $num = "{$num}0";
                    $tokens[] = $num;
                    $num = '';
                }
                $tokens[] = $c;
            }
        }
        if ($num !== '') {
            !(static::stringStarts($num, '.')) ?: $num = "0{$num}";
            !(static::stringEnds($num, '.')) ?: $num = "{$num}0";
            $tokens[] = $num;
        }

        $prec = ['+' => 1, '-' => 1, '*' => 2, '/' => 2];
        $out = [];
        $ops = [];

        foreach ($tokens as $t) {
            if (preg_match('/^-?\d+(\.\d+)?$/', $t)) {
                $out[] = $t;
            } else {
                while ($ops && $prec[end($ops)] >= $prec[$t]) {
                    $out[] = array_pop($ops);
                }
                $ops[] = $t;
            }
        }
        while ($ops) $out[] = array_pop($ops);

        $stack = [];

        foreach ($out as $t) {
            if (preg_match('/^-?\d+(\.\d+)?$/', $t)) {
                $stack[] = $t;
            } else {
                $b = array_pop($stack) ?? '0';
                $a = array_pop($stack) ?? '0';

                $aParts = explode('.', "{$a}.");
                $bParts = explode('.', "{$b}.");
                $maxDec = max(strlen($aParts[1] ?? '0'), strlen($bParts[1] ?? '0'));

                $aSign = $a[0] === '-' ? -1 : 1;
                $bSign = $b[0] === '-' ? -1 : 1;

                $aAbs = ltrim($a, '+-');
                $bAbs = ltrim($b, '+-');

                $aParts = explode('.', "{$aAbs}.");
                $bParts = explode('.', "{$bAbs}.");

                $maxDec = max(strlen($aParts[1] ?? '0'), strlen($bParts[1] ?? '0'));

                $aInt = str_replace('.', '', $aAbs) . str_repeat('0', $maxDec - strlen($aParts[1] ?? '0'));
                $bInt = str_replace('.', '', $bAbs) . str_repeat('0', $maxDec - strlen($bParts[1] ?? '0'));

                switch ($t) {
                    case '+':
                        if ($aSign === $bSign) {
                            $res = HelperInternal::internalNumberAdd($aInt, $bInt);
                            $result = HelperInternal::internalNumberInsertDecimal($res, $maxDec);
                            if ($aSign < 0) $result = "-$result";
                        } else {
                            $cmp = HelperInternal::internalNumberCompare($aInt, $bInt);
                            if ($cmp === 0) {
                                $result = '0';
                            } elseif ($cmp > 0) {
                                $res = HelperInternal::internalNumberSubtract($aInt, $bInt);
                                $result = HelperInternal::internalNumberInsertDecimal($res, $maxDec);
                                if ($aSign < 0) $result = "-$result";
                            } else {
                                $res = HelperInternal::internalNumberSubtract($bInt, $aInt);
                                $result = HelperInternal::internalNumberInsertDecimal($res, $maxDec);
                                if ($bSign < 0) $result = "-$result";
                            }
                        }
                        break;

                    case '-':
                        $bSign *= -1;
                        // теперь: a - b == a + (-b)
                        // => повторно используй блок "case '+'"
                        if ($aSign === $bSign) {
                            $res = HelperInternal::internalNumberAdd($aInt, $bInt);
                            $result = HelperInternal::internalNumberInsertDecimal($res, $maxDec);
                            if ($aSign < 0) $result = "-$result";
                        } else {
                            $cmp = HelperInternal::internalNumberCompare($aInt, $bInt);
                            if ($cmp === 0) {
                                $result = '0';
                            } elseif ($cmp > 0) {
                                $res = HelperInternal::internalNumberSubtract($aInt, $bInt);
                                $result = HelperInternal::internalNumberInsertDecimal($res, $maxDec);
                                if ($aSign < 0) $result = "-$result";
                            } else {
                                $res = HelperInternal::internalNumberSubtract($bInt, $aInt);
                                $result = HelperInternal::internalNumberInsertDecimal($res, $maxDec);
                                if ($bSign < 0) $result = "-$result";
                            }
                        }
                        break;
                    case '*':
                        $result = HelperInternal::internalNumberMultiply($a, $b, $precision);
                        break;
                    case '/':
                        $result = HelperInternal::internalNumberDivide($a, $b, $precision);
                        break;
                }

                $stack[] = HelperInternal::internalNumberTrimTrailingZeros(HelperInternal::internalNumberRoundStr($result, $precision));
            }
        }

        return $stack[0] ?? "0";
    }


    /**
     * Возвращает количество знаков дробной части числа
     * @see ../../tests/HelperNumberTrait/HelperNumberDecimalDigitsTest.php
     *
     * @param int|float|string|null $value
     * @param bool $ignoreTrailingZeros
     * @return int
     */
    public static function numberDecimalDigits(
        int|float|string|null $value,
        bool $ignoreTrailingZeros = true,
    ): int {
        $valueString = (string)$value;
        if ($valueString === '') {
            return 0;
        }

        $parts = explode('.', $valueString, 2);
        if (!isset($parts[1])) {
            return 0;
        }

        $fractionPart = $parts[1];
        $fractionPart = $ignoreTrailingZeros ? rtrim($fractionPart, '0') : $fractionPart;

        return $fractionPart === '' ? 0 : strlen($fractionPart);
    }


    /**
     * Возвращает число в виде строки с форматированием
     * @see ../../tests/HelperNumberTrait/HelperNumberFormatTest.php
     *
     * @param int|float|string|null $value
     * @param int|null $decimals
     * @param string|null $separator
     * @return string
     */
    public static function numberFormat(
        int|float|string|null $value,
        ?int $decimals = null,
        ?string $separator = null,
    ): string {
        $value = (float)static::numberFromString($value);

        return number_format(
            num: $value,
            decimals: is_null($decimals) ? static::numberDecimalDigits($value) : $decimals,
            decimal_separator: is_null($separator) ? ',' : $separator,
            thousands_separator: ' ',
        );
    }
}
