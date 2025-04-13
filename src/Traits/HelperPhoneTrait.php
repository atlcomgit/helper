<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с трансформацией
 */
trait HelperPhoneTrait
{
    public static string $phoneFormat = '+%s (%s%s%s) %s%s%s-%s%s-%s%s';


    /**
     * Возвращает отформатированный номер телефона
     * @see ../../tests/HelperPhoneTrait/HelperPhoneFormatTest.php
     *
     * @param int|float|string $value
     * @param string|null $countryNumber
     * @return string
     */
    public static function phoneFormat(int|float|string $value, ?string $countryNumber = '7'): string
    {
        $value = (string)$value;
        $value = preg_replace('/[^0-9]/', '', $value);
        (static::stringStarts($value, $countryNumber) && static::stringLength($value) >= 11)
            ?: $value = "{$countryNumber}{$value}";

        $digits = static::stringCount(static::$phoneFormat, ['%d', '%s']);
        $chars = str_split($value);

        return static::stringReplace(
            sprintf(
                static::$phoneFormat,
                ...[
                    ...array_fill(0, max(0, $digits - count($chars)), null),
                    ...$chars,
                ],
            ),
            ['+ ' => ''],
        );
    }


    /**
     * Возвращает только цифры номера телефона
     * @see ../../tests/HelperPhoneTrait/HelperPhoneNumberTest.php
     *
     * @param int|float|string $value
     * @param string|null $countryNumber
     * @return string
     */
    public static function phoneNumber(int|float|string $value, ?string $countryNumber = '7'): string
    {
        $value = (string)$value;
        $value = preg_replace('/[^0-9]/', '', $value);
        (static::stringStarts($value, $countryNumber) && static::stringLength($value) >= 11)
            ?: $value = "{$countryNumber}{$value}";

        return $value;
    }
}
