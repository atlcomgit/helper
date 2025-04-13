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
 */
trait HelperNumberTrait
{
    /**
     * Возвращает число прописью на русском языке с учетом склонения по падежу, роду и числительному перечислению
     * @see ../../tests/HelperNumberTrait/HelperNumberToStringTest.php
     *
     * @param int|float|string $value
     * @param Declension $declension
     * @param Gender $gender
     * @param Enumeration $enumeration
     * @return string
     */
    public static function numberToString(
        int|float|string $value,
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

        $value = static::stringReplace($value, '/[^0-9+-.]/u', '');

        if (!$value === '') {
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
            '-' => 'минус',
            '+' => 'плюс',
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
}
