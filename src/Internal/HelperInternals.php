<?php

declare(strict_types=1);

namespace Atlcom\Internal;

use Atlcom\Consts\HelperConsts as Consts;
use Atlcom\Enums\HelperNumberDeclensionEnum as Declension;
use Atlcom\Enums\HelperNumberEnumerationEnum as Enumeration;
use Atlcom\Enums\HelperNumberGenderEnum as Gender;
use Atlcom\Traits\HelperStringTrait;

class HelperInternals
{
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
                    $result = Consts::NUMERIC_NAMES[$digitC][$enum]
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
                                ? Consts::NUMERIC_NAMES[(int)"{$digitB}{$digitC}"][$enum]
                                . (
                                    $enum === 0
                                    ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    : Consts::NUMBER_ORD[$decl][$gend][Consts::NUMBER_ORD_X10[$digitB - 1]]
                                )

                                : Consts::NUMERIC_NAMES[(int)"{$digitB}{$digitC}"][$enum]
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
                                ? Consts::NUMERIC_NAMES[18 + $digitB][0]
                                . (
                                    $enum === 0
                                    ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    : Consts::NUMBER_CASE_10[0][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                )
                                . ($result !== '' ? ' ' : '')
                                . $result

                                : Consts::NUMERIC_NAMES[18 + $digitB][$enum]
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
                        ? Consts::NUMERIC_NAMES[27 + $digitA][0]
                        . (
                            $enum === 0
                            ? Consts::NUMBER_CASE_100[$decl][Consts::NUMBER_CASE_X100[$digitA - 1]]
                            : Consts::NUMBER_CASE_100[0][Consts::NUMBER_CASE_X100[$digitA - 1]]
                        )
                        . ($result !== '' ? ' ' : '')
                        . $result

                        : Consts::NUMERIC_NAMES[27 + $digitA][$enum]
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
                        ? Consts::NUMERIC_NAMES[$digitC][Enumeration::Numerical->value]
                        . (
                            $enum === 0
                            ? Consts::NUMBER_CASE_10[$decl][$gendFM][Consts::NUMBER_CASE_X1[$digitC]]
                            : Consts::NUMBER_CASE_10[$declN][$gendFM][Consts::NUMBER_CASE_X1[$digitC]]
                        )

                        : (
                            $enum === 0
                            ? Consts::NUMERIC_NAMES[$digitC][$enum]
                            : Consts::NUMERIC_NAMES[$digitC][Enumeration::Numerical->value]
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
                                    ? Consts::NUMERIC_NAMES[(int)"{$digitB}{$digitC}"][Enumeration::Numerical->value]
                                    . (
                                        $enum === 0
                                        ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                        : Consts::NUMBER_CASE_10[$declN][$gendFM][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    )

                                    : Consts::NUMERIC_NAMES[(int)"{$digitB}{$digitC}"][Enumeration::Numerical->value]
                                    . (
                                        $enum === 0
                                        ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                        : Consts::NUMBER_CASE_10[$declG][$gendFM][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    );
                            } else {
                                $result = $usePrev
                                    ? Consts::NUMERIC_NAMES[(int)"{$digitB}{$digitC}"][Enumeration::Numerical->value]
                                    . (
                                        $enum === 0
                                        ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                        : Consts::NUMBER_CASE_10[$declN][$gendFM][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    )

                                    : Consts::NUMERIC_NAMES[(int)"{$digitB}{$digitC}"][Enumeration::Numerical->value]
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
                                    ? Consts::NUMERIC_NAMES[18 + $digitB][0]
                                    . (
                                        $enum === 0
                                        ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                        : Consts::NUMBER_CASE_10[$declN][$gendFM][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    )
                                    . ($result !== '' ? ' ' : '')
                                    . $result

                                    : Consts::NUMERIC_NAMES[18 + $digitB][0]
                                    . (
                                        $enum === 0
                                        ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                        : Consts::NUMBER_CASE_10[$declG][$gendFM][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    )
                                    . ($result !== '' ? ' ' : '')
                                    . $result;
                            } else {
                                $result = $usePrev
                                    ? Consts::NUMERIC_NAMES[18 + $digitB][0]
                                    . (
                                        $enum === 0
                                        ? Consts::NUMBER_CASE_10[$decl][$gend][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                        : Consts::NUMBER_CASE_10[$declN][$gendFM][Consts::NUMBER_CASE_X10[$digitB - 1]]
                                    )

                                    : Consts::NUMERIC_NAMES[18 + $digitB][0]
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
                            ? Consts::NUMERIC_NAMES[27 + $digitA][0]
                            . (
                                $enum === 0
                                ? Consts::NUMBER_CASE_100[$decl][Consts::NUMBER_CASE_X100[$digitA - 1]]
                                : Consts::NUMBER_CASE_100[$declN][Consts::NUMBER_CASE_X100[$digitA - 1]]
                            )
                            . ($result !== '' ? ' ' : '')
                            . $result

                            : (
                                $enum === 0
                                ? Consts::NUMERIC_NAMES[27 + $digitA][0]
                                : Consts::NUMERIC_NAMES[27 + $digitA][$digitA === 1 ? 0 : 1]
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

                            ? Consts::NUMERIC_NAMES[27 + $digitA][0]
                            . (
                                $enum === 0
                                ? Consts::NUMBER_CASE_100[$decl][Consts::NUMBER_CASE_X100[$digitA - 1]]
                                : Consts::NUMBER_CASE_100[$declN][Consts::NUMBER_CASE_X100[Consts::NUMBER_CASE_X100[$digitA - 1] - 1]]
                            )

                            : (
                                $enum === 0
                                ? Consts::NUMERIC_NAMES[27 + $digitA][0]
                                : Consts::NUMERIC_NAMES[27 + $digitA][$digitA === 1 ? 0 : 1]
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
                            ? Consts::NUMERIC_NAMES[35 + $part][Enumeration::Numerical->value]
                            : Consts::NUMERIC_NAMES[35 + $part][$enum]
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
}
