<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Enums\HelperRegexpEnum;

/**
 * Трейт для работы с regexp
 */
trait HelperRegexpTrait
{
    /**
     * Проверяет значение строки на формат электронной почты
     * @see ./tests/HelperRegexpTrait/HelperRegexpValidateEmailTest.php
     *
     * @param string $value
     * @return bool
     */
    public static function regexpValidateEmail(string $value): bool
    {
        return (bool)preg_match(HelperRegexpEnum::Email->value, $value);
    }


    /**
     * Проверяет значение строки на формат регулярного выражения
     * @see ./tests/HelperRegexpTrait/HelperRegexpValidatePatternTest.php
     *
     * @param string $value
     * @return bool
     */
    public static function regexpValidatePattern(string $value): bool
    {
        return (bool)preg_match(HelperRegexpEnum::Pattern->value, $value);
    }


    /**
     * Проверяет значение строки на формат номера телефона
     * @see ./tests/HelperRegexpTrait/HelperRegexpValidatePhoneTest.php
     *
     * @param string $value
     * @return bool
     */
    public static function regexpValidatePhone(string $value): bool
    {
        return (bool)(
            preg_match(HelperRegexpEnum::Phone->value, $value)
            && static::stringLength(preg_replace('/[^0-9]/', '', $value)) >= 10
        );
    }


    /**
     * Проверяет значение строки на формат идентификатора uuid
     * @see ./tests/HelperRegexpTrait/HelperRegexpValidateUuidTest.php
     *
     * @param string $value
     * @return bool
     */
    public static function regexpValidateUuid(string $value): bool
    {
        return (bool)preg_match(HelperRegexpEnum::Uuid->value, $value);
    }


    /**
     * Проверяет значение строки на формат json
     * @see ./tests/HelperRegexpTrait/HelperRegexpValidateJsonTest.php
     *
     * @param string $value
     * @return bool
     */
    public static function regexpValidateJson(string $value): bool
    {
        return (bool)($array = json_decode($value, true, 512, JSON_INVALID_UTF8_IGNORE))
            && is_array($array);
    }


    /**
     * Проверяет значение строки на формат ascii (латинский алфавита и цифры)
     * @see ./tests/HelperRegexpTrait/HelperRegexpValidateAsciiTest.php
     *
     * @param string $value
     * @return bool
     */
    public static function regexpValidateAscii(string $value): bool
    {
        return !preg_match(HelperRegexpEnum::Ascii->value, $value);
    }


    /**
     * Проверяет значение строки на формат юникода
     * @see ./tests/HelperRegexpTrait/HelperRegexpValidateUnicodeTest.php
     *
     * @param string $value
     * @return bool
     */
    public static function regexpValidateUnicode(string $value): bool
    {
        return (bool)preg_match(HelperRegexpEnum::Unicode->value, $value);
    }
}
